# Changelog

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and versions follow [Semantic Versioning](https://semver.org/).

## [Unreleased]

### Added

- `BASE_PATH` serves the app under a path prefix, and views prefix their links with it.
- A `Dockerfile` and a `demo` job in `ci.yml` that publishes the live demo at ayers.repair/opensource/galaxymvc/ once the checks pass.

### Security

- Require PHP 8.4.27 or 8.5.12 and later patch releases in those branches for the PHP2Shell fixes (CVE-2026-109618 and CVE-2026-109619). Pin and verify the demo runtime before deployment; link its patch seal to the PHP advisory.

- The demo image sets `expose_php = Off`, so responses no longer carry `X-Powered-By`.

## [2.0.0] - 2026-10-19

A small PHP 8.4 MVC framework: front controller, reflection-checked router, PDO data layer, plain PHP views.

### Added

- Composer project with PSR-4 autoloading (`App\` -> `app/`). Requires PHP 8.4; every file uses strict types and typed, readonly properties.
- `Router` and `Route`: path to controller action, with reflection checks so only public, non-static controller methods with a matching parameter count are reachable. Anything else is a 404.
- Routing reads `REQUEST_URI`, so it works under Apache, nginx and `php -S` alike. Route parameters are passed as separate arguments and URL-decoded.
- `Database`: a PDO wrapper (`select`, `first`, `execute`), with every value bound by type.
- `Config`: database settings from `DB_DSN`, `DB_USER` and `DB_PASSWORD`, defaulting to a SQLite file.
- `View`: templates rendered inside one layout, with a 404 page. Views escape all output; the stylesheet uses colour tokens with a dark theme.
- `database/setup.php` (`composer setup`) to create and seed the `posts` table.
- PHPUnit 13 tests for the router, the database layer and the configuration.
- PHP-CS-Fixer (`@PSR12`), PHPStan at level 10, and a GitHub Actions workflow that runs them with the tests on PHP 8.4.
- A README that describes the request flow, setup and project layout.
- `CLAUDE.md`, `.claude/settings.json` and `docs/ARCHITECTURE.md` for working on the project with Claude Code.

[2.0.0]: https://github.com/ZachariahAyers/GalaxyMVC/tree/v2.0.0
