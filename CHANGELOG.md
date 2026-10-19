# Changelog

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and versions follow [Semantic Versioning](https://semver.org/).

## [2.0.0] - 2026-10-19

A rebuild of the framework on current PHP. The purpose and the request cycle are unchanged; every file was rewritten.

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
- `Database` is a real PDO wrapper again (`select`, `first`, `execute`), with every value bound by type. v1 had replaced it with an in-memory mock.
- Routing reads `REQUEST_URI`, so it works under Apache, nginx and `php -S` alike. v1 read `$_GET['url']`, which only the Apache rewrite set.
- Unknown controllers or methods return 404 instead of silently showing the home page.
- Route parameters are passed as separate arguments and URL-decoded.
- Views escape all output and no longer use emoji or inline styles; the stylesheet uses colour tokens with a dark theme.
- The README describes the request flow, setup and project layout.

### Removed

- The EC2 and ECR deployment workflow and its Dockerfile.
- The mock database, its sample code posts and its seeded admin account.
- The empty JavaScript file, the empty Dependabot config, and the hard-coded `RewriteBase`.

## [1.0.0] - 2025-10-05

The original framework, tagged `v1`: a `Core` router reading `$_GET['url']`, a base `Controller` that loaded models and views by file path, a PDO `Database` class later replaced by an in-memory mock, and header and footer view includes.

[2.0.0]: https://github.com/ZachariahAyers/GalaxyMVC/compare/v1...v2.0.0
[1.0.0]: https://github.com/ZachariahAyers/GalaxyMVC/releases/tag/v1
