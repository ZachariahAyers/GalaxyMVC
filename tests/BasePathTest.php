<?php

declare(strict_types=1);

namespace Tests;

use App\Core\BasePath;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(BasePath::class)]
final class BasePathTest extends TestCase
{
    protected function tearDown(): void
    {
        putenv('BASE_PATH');
    }

    public function testDefaultsToTheRoot(): void
    {
        putenv('BASE_PATH');

        $base = BasePath::fromEnvironment();

        self::assertSame('', $base->prefix);
        self::assertSame('/home/about', $base->strip('/home/about'));
    }

    public function testReadsTheEnvironmentWithOneLeadingSlash(): void
    {
        putenv('BASE_PATH=opensource/galaxymvc/');

        self::assertSame('/opensource/galaxymvc', BasePath::fromEnvironment()->prefix);
    }

    /**
     * @return iterable<string, array{string, ?string}>
     */
    public static function paths(): iterable
    {
        yield 'the base itself is the root' => ['/opensource/galaxymvc', '/'];
        yield 'the base with a slash is the root' => ['/opensource/galaxymvc/', '/'];
        yield 'a route under the base' => ['/opensource/galaxymvc/home/about', '/home/about'];
        yield 'a file under the base' => ['/opensource/galaxymvc/css/style.css', '/css/style.css'];
        yield 'the site root is outside' => ['/', null];
        yield 'a route outside the base' => ['/home/about', null];
        yield 'a longer name is not the base' => ['/opensource/galaxymvcx/home', null];
    }

    #[DataProvider('paths')]
    public function testStripsTheBaseBeforeRouting(string $path, ?string $expected): void
    {
        self::assertSame($expected, (new BasePath('/opensource/galaxymvc'))->strip($path));
    }
}
