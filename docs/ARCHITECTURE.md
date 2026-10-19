# Architecture

GalaxyMVC handles a request in five steps, each in one file. Nothing is configured by convention files or annotations; what runs is what the code says.

```
HTTP request
  -> public/index.php        front controller
  -> App\Core\Router         path -> Route(controller, method, params) or null
  -> App\Controllers\*       action, given a Database
  -> App\Models\*            queries through App\Core\Database
  -> App\Core\View           template inside the layout
HTTP response
```

## 1. Front controller: `public/index.php`

Apache (`public/.htaccess`), nginx `try_files`, or `php -S ... public/index.php` send every request that is not a real file here. The script:

1. Takes the path from `REQUEST_URI`, without the query string.
2. Under `php -S`, returns `false` for files that exist inside `public/` so the server sends them as-is. `realpath` keeps that check inside `public/`.
3. Asks the router for a route. `null` becomes a 404 rendered with `app/views/errors/404.php`.
4. Builds the controller with a `Database` from `Config::fromEnvironment()`, calls the action, and requires a string back.

The database connection opens only after a route matches, so a 404 never touches the database.

## 2. Router: `app/Core/Router.php`

`resolve(string $path): ?Route` is a pure function of the path and the loaded classes, which is why it is the most tested part.

- Empty segments are dropped, so `//home//about/` is `/home/about`.
- Segment one is the controller (default `Home`), segment two the method (default `index`), and the rest are parameters, URL-decoded.
- The controller and method segments must match `^[A-Za-z][A-Za-z0-9]*$`. This rules out `..`, namespace separators, `__construct` and other magic methods before any class name is built.
- `App\Controllers\` plus `ucfirst(segment)` must be a subclass of `App\Core\Controller`. `is_subclass_of` autoloads it.
- Reflection then requires a public, non-static method whose parameter count fits the number of segments given. Inherited `protected` helpers such as `render()` can never be reached.

Anything that fails a check is `null`. The router never throws for a bad URL.

## 3. Controllers: `app/Controllers`

A controller extends `App\Core\Controller`, receives the `Database` in its constructor, and returns the rendered page as a string. Its public methods are its routes.

```php
final class Home extends Controller
{
    public function index(): string
    {
        return $this->render('home/index', ['posts' => (new Post($this->db))->all()]);
    }
}
```

## 4. Models and the database: `app/Models`, `app/Core/Database.php`

Models take the `Database` and expose named queries (`Post::all()`). `Database` wraps PDO with three methods:

| Method | Returns |
| --- | --- |
| `select($sql, $params)` | every row, as associative arrays |
| `first($sql, $params)` | the first row, or `null` |
| `execute($sql, $params)` | the number of affected rows |

Every call is `PDO::prepare` followed by `bindValue` for each parameter, typed from the PHP value: `int` -> `PARAM_INT`, `bool` -> `PARAM_BOOL`, `null` -> `PARAM_NULL`, anything else -> `PARAM_STR`. A list binds `?` placeholders; a map binds named ones, with or without the leading colon. The class has no method that runs SQL without a prepared statement.

`Config::fromEnvironment()` reads `DB_DSN`, `DB_USER` and `DB_PASSWORD`. The default DSN is a SQLite file in `var/`; any PDO driver works. `database/setup.php` creates and seeds the `posts` table through the same class.

## 5. Views: `app/Core/View.php`, `app/views`

`View::render($name, $data)` extracts `$data` into local variables, renders `app/views/$name.php` into `$content`, and renders `app/views/layout.php` around it. Templates escape output with `htmlspecialchars`. Styling is in `public/css/style.css`; templates carry no inline CSS or scripts.

## Quality gates

`composer check` runs, in order:

1. `php -l` on every PHP file
2. PHP-CS-Fixer with `@PSR12`, in check mode
3. PHPStan at level 10
4. PHPUnit 13

CI (`.github/workflows/ci.yml`) runs the same steps on PHP 8.4 after `composer validate --strict`. Templates in `app/views` are skipped by the fixer and by PHPStan because they read variables that `View` extracts at runtime.
