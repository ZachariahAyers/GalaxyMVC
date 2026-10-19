# GalaxyMVC

A small PHP 8.4 MVC framework: front controller, reflection-checked router, PDO data layer, plain PHP views. No runtime dependencies. How a request moves through the code: `docs/ARCHITECTURE.md`.

## Commands

- `composer install`: install the dev tools (PHPUnit, PHPStan, PHP-CS-Fixer).
- `composer setup`: create and seed the database named by `DB_DSN` (default `var/galaxy.sqlite`).
- `composer check`: everything CI runs, in order: `syntax`, `style`, `analyse`, `test`.
- `composer test -- --filter RouterTest`: one test class or method.
- `composer style:fix`: apply PSR-12 fixes. Run it before `composer style` rather than hand-formatting.
- `php -S localhost:8000 -t public public/index.php`: dev server; it runs until stopped, so start it in the background.
- Workflows: `ci.yml` runs the checks; `demo.yml` builds the image and redeploys ayers.repair/opensource/galaxymvc/ on a push to `master`.

## Where things live

- `public/index.php`: front controller. Path -> `Router` -> controller -> response, or the 404 view.
- `app/Core/Router.php`: `/controller/method/params` -> `Route`. The only place URLs are trusted.
- `app/Core/Database.php`: every query. `select`, `first`, `execute`; parameters always bound.
- `app/Core/View.php`: renders `app/views/<name>.php` inside `app/views/layout.php`.
- `app/Core/Config.php`: environment settings (`DB_DSN`, `DB_USER`, `DB_PASSWORD`).
- `app/Core/BasePath.php`: `BASE_PATH`, stripped before routing and put in front of every link in the views (`$base`).
- `Dockerfile`: the demo image, `php -S` on 8080 with the database on `/data`.
- `app/Controllers/`, `app/Models/`: application code. `tests/`: PHPUnit; `tests/Fixtures/` holds test-only controllers.

## Rules

- Every public method on a controller is a URL. Helpers on a controller are `protected` or `private`.
- Actions return `string` and take only `string` parameters; convert types inside the action.
- SQL goes through `Database` with placeholders. Never concatenate a value into SQL.
- Views escape every value with `htmlspecialchars`, and start every site link with `$base`. No inline `<style>`, `style=` or `<script>`; CSS lives in `public/css/style.css`.
- Every PHP file starts with `declare(strict_types=1);`. Classes are `final` unless built to be extended; one class per file under PSR-4 (`App\` -> `app/`, `Tests\` -> `tests/`).
- `app/views` is excluded from PHP-CS-Fixer and PHPStan. Keep logic out of templates so that costs nothing.
- Do not lower the PHPStan level or add baseline entries; fix the type.

## Definition of done

- `composer check` passes, and the output has been read.
- New behaviour has a test that fails without the change.
- `README.md`, `docs/ARCHITECTURE.md` and `CHANGELOG.md` match the change.
- Commits use Conventional Commits with a lower-case subject: `fix: reject empty route segments`.
