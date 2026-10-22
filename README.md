# GalaxyMVC

[![CI](https://github.com/ZachariahAyers/GalaxyMVC/actions/workflows/ci.yml/badge.svg)](https://github.com/ZachariahAyers/GalaxyMVC/actions/workflows/ci.yml)

A small PHP MVC framework with a front controller, a URL router, a PDO data layer and plain PHP views.

Live demo: [ayers.repair/opensource/galaxymvc](https://ayers.repair/opensource/galaxymvc/).

## Features

- Routes `/controller/method/params` URLs to controller actions, and returns a 404 for anything that is not a public action
- Prepared-statement database access through PDO
- Plain PHP views rendered inside a shared layout, with escaped output
- Database settings from environment variables, with SQLite as the default
- No runtime dependencies beyond PHP and PDO

## Tech stack

PHP 8.4.27 or 8.5.12 and later patched releases in those branches, PDO, Composer, PHPUnit, PHPStan, PHP-CS-Fixer.

## Getting started

Requires PHP 8.4.27+ or 8.5.12+ within those branches, with `pdo_sqlite` (or another PDO driver), and Composer.

```bash
git clone https://github.com/ZachariahAyers/GalaxyMVC.git
cd GalaxyMVC
composer install
composer setup
php -S localhost:8000 -t public public/index.php
```

Open http://localhost:8000. Set `DB_DSN`, `DB_USER` and `DB_PASSWORD` to use another database.

## PHP security

The demo uses PHP 8.4.27, which includes the fixes for
[CVE-2026-109618 and CVE-2026-109619 (PHP2Shell)](https://www.php.net/archive/2026.php#2026-10-21-5).
Composer rejects older releases in both supported PHP branches. CI checks both patched minimum
versions and verifies the published container's PHP version before deploying its immutable digest.

These are PHP engine vulnerabilities, so upgrading framework code alone does not fix an older
server runtime. Update PHP and restart all workers when deploying outside the supplied image.
The demo's seal refers only to these two fixes, not a general security certification.

## Development

```bash
composer syntax
composer style
composer analyse
composer test
composer check     # all of the above, as CI runs it
```

How it is put together: [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md).

## License

Apache License 2.0. See [LICENSE](LICENSE).

---

Built by [Zachariah Ayers](https://ayers.repair).
