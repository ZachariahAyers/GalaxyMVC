<?php

declare(strict_types=1);

namespace Tests;

use App\Core\Config;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Config::class)]
final class ConfigTest extends TestCase
{
    private const VARIABLES = ['DB_DSN', 'DB_USER', 'DB_PASSWORD'];

    protected function tearDown(): void
    {
        foreach (self::VARIABLES as $name) {
            putenv($name);
        }
    }

    public function testDefaultsToTheProjectSqliteFile(): void
    {
        foreach (self::VARIABLES as $name) {
            putenv($name);
        }

        $config = Config::fromEnvironment();

        self::assertSame('sqlite:' . dirname(__DIR__) . '/var/galaxy.sqlite', $config->dsn);
        self::assertNull($config->user);
        self::assertNull($config->password);
    }

    public function testReadsTheEnvironment(): void
    {
        putenv('DB_DSN=mysql:host=localhost;dbname=galaxymvc');
        putenv('DB_USER=app');
        putenv('DB_PASSWORD=secret');

        self::assertEquals(
            new Config('mysql:host=localhost;dbname=galaxymvc', 'app', 'secret'),
            Config::fromEnvironment(),
        );
    }

    public function testTreatsEmptyVariablesAsUnset(): void
    {
        putenv('DB_USER=');

        self::assertNull(Config::fromEnvironment()->user);
    }
}
