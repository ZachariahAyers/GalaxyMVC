# GalaxyMVC

A small PHP MVC framework built from scratch: a front controller, a URL router, a PDO data layer and plain PHP views. No runtime dependencies beyond PHP and PDO.

[ayers.repair](https://ayers.repair) · [GitHub](https://github.com/ZachariahAyers)

## Why it is interesting

Most PHP developers meet MVC through Laravel or Symfony, where routing, controllers and data access arrive as a finished machine. GalaxyMVC is the same idea at a size you can read in one sitting. Each step of a request lives in one short file under `app/Core`, so the path from URL to HTML can be followed line by line:

- **Routing with a safety check.** The router turns `/controller/method/params` into a class and method, then uses reflection to confirm the target is a public, non-static action on a `Controller` subclass with the right number of parameters. Anything else is a 404, so helper methods and arbitrary classes can never be called from a URL.
- **Prepared statements only.** The database class has no way to run a query without binding. Each value is bound with a PDO type taken from its PHP type (`int`, `bool`, `null`, string).
- **Escaped views.** Templates are plain PHP rendered inside one layout, with output passed through `htmlspecialchars`.
- **Modern PHP.** PHP 8.4, strict types throughout, typed and readonly properties, PSR-4 autoloading through Composer, PSR-12 style, and a PHPUnit 13 suite.

## How a request flows

```
GET /home/about
  -> public/index.php            front controller
  -> Router::resolve()           "home" -> App\Controllers\Home, "about" -> about()
  -> new Home($db)               controller receives the Database
  -> Home::about()               asks models for data, picks a view
  -> View::render()              app/views/home/about.php inside app/views/layout.php
  -> HTML response
```

1. **Front controller.** The web server sends every request that is not a real file to `public/index.php`.
2. **Router.** `Router::resolve()` splits the path. The first segment names the controller (default `Home`), the second the method (default `index`), and the rest become string arguments. It returns a `Route`, or `null` for a 404.
3. **Controller.** The front controller builds the controller with a `Database` and calls the action with the route's parameters.
4. **Model.** `Post::all()` runs `SELECT id, title, content FROM posts ORDER BY id` through `Database::select()`.
5. **View.** The action returns `View::render('home/index', [...])`, which renders the template and wraps it in the layout.

Adding a page means adding a public method. `public function show(string $id): string` on a `Posts` controller answers `/posts/show/5`.

## Running it

Requirements: PHP 8.4 or later with `pdo_sqlite` (or another PDO driver), and Composer.

```bash
git clone https://github.com/ZachariahAyers/GalaxyMVC.git
cd GalaxyMVC
composer install
composer setup                                   # creates var/galaxy.sqlite with sample posts
php -S localhost:8000 -t public public/index.php
```

Open http://localhost:8000. PHP's built-in server is for development only. In production, point Apache (with `mod_rewrite`) or nginx at `public/`; `public/.htaccess` already routes requests to `index.php`.

### Configuration

Settings come from environment variables, read in `app/config.php`:

| Variable | Default |
| --- | --- |
| `DB_DSN` | `sqlite:<project>/var/galaxy.sqlite` |
| `DB_USER` | none |
| `DB_PASSWORD` | none |

For MySQL, for example: `DB_DSN="mysql:host=localhost;dbname=galaxymvc" DB_USER=app DB_PASSWORD=... composer setup`.

### Tests

```bash
composer test
```

The suite covers the router (default routes, parameters, decoding, and every path it must refuse) and the database layer (positional and named binding, typed values, row counts, and SQL injection attempts) against an in-memory SQLite database.

## Project structure

```
GalaxyMVC/
├── app/
│   ├── Controllers/      Home.php: the actions
│   ├── Core/             Router, Route, Controller, View, Database
│   ├── Models/           Post.php
│   ├── views/            layout.php, home/, errors/
│   └── config.php        settings from the environment
├── database/
│   └── setup.php         creates and seeds the posts table
├── public/               web root
│   ├── css/style.css
│   ├── .htaccess
│   └── index.php         front controller
├── tests/                PHPUnit suite and a fixture controller
├── composer.json
└── phpunit.xml.dist
```

## License

Apache License 2.0. See [LICENSE](LICENSE).

## Author

Zachariah Ayers, [ayers.repair](https://ayers.repair)
