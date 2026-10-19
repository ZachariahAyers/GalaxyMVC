# Changelog

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and versions follow [Semantic Versioning](https://semver.org/).

## [Unreleased]

### Added

- `BASE_PATH` serves the app under a path prefix, and views prefix their links with it.
- A `Dockerfile` and a `demo` job in `ci.yml` that publishes the live demo at ayers.repair/opensource/galaxymvc/ once the checks pass.

### Security

- The demo image sets `expose_php = Off`, so responses no longer carry `X-Powered-By`.

## [2.0.0] - 2026-10-19

Built on current PHP.

### Added

- Composer project with PSR-4 autoloading (`App\` -> `app/`).
- `Router` and `Route`: path to controller action, with reflection checks so only public, non-static controller methods with a matching parameter count are reachable. Anything else is a 404.
- `Config`: database settings from `DB_DSN`, `DB_USER` and `DB_PASSWORD`, defaulting to a SQLite file.
- `View`: templates rendered inside one layout, with a 404 page.
- `database/setup.php` (`composer setup`) to create and seed the `posts` table.
- PHPUnit 13 tests for the router, the database layer and the configuration.
- PHP-CS-Fixer (`@PSR12`), PHPStan at level 10, and a GitHub Actions workflow that runs them with the tests on PHP 8.4.
- `CLAUDE.md`, `.claude/settings.json` and `docs/ARCHITECTURE.md` for working on the project with Claude Code.

### Changed

- Requires PHP 8.4. Every file uses strict types and typed, readonly properties.
- `Database` is a real PDO wrapper (`select`, `first`, `execute`), with every value bound by type.
- Routing reads `REQUEST_URI`, so it works under Apache, nginx and `php -S` alike.
- Unknown controllers or methods return 404 instead of silently showing the home page.
- Route parameters are passed as separate arguments and URL-decoded.
- Views escape all output and no longer use emoji or inline styles; the stylesheet uses colour tokens with a dark theme.
- The README describes the request flow, setup and project layout.

### Removed

- The EC2 and ECR deployment workflow and its Dockerfile.
- The mock database, its sample code posts and its seeded admin account.
- The empty JavaScript file, the empty Dependabot config, and the hard-coded `RewriteBase`.

[2.0.0]: https://github.com/ZachariahAyers/GalaxyMVC/tree/v2.0.0
