<?php

declare(strict_types=1);

namespace Tests;

use App\Controllers\Home;
use App\Core\Route;
use App\Core\Router;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\Greeter;

#[CoversClass(Router::class)]
#[CoversClass(Route::class)]
final class RouterTest extends TestCase
{
    /**
     * @return iterable<string, array{string, string, string}>
     */
    public static function appRoutes(): iterable
    {
        yield 'root uses the defaults' => ['/', Home::class, 'index'];
        yield 'controller only uses the default method' => ['/home', Home::class, 'index'];
        yield 'controller and method' => ['/home/about', Home::class, 'about'];
        yield 'trailing and doubled slashes are ignored' => ['//home//about/', Home::class, 'about'];
    }

    #[DataProvider('appRoutes')]
    public function testResolvesApplicationRoutes(string $path, string $controller, string $method): void
    {
        $route = (new Router())->resolve($path);

        self::assertEquals(new Route($controller, $method, []), $route);
    }

    public function testPassesRemainingSegmentsAsParameters(): void
    {
        $router = new Router('Tests\\Fixtures\\', 'Greeter');

        self::assertEquals(
            new Route(Greeter::class, 'hello', ['zach']),
            $router->resolve('/greeter/hello/zach'),
        );
        self::assertEquals(
            new Route(Greeter::class, 'between', ['a']),
            $router->resolve('/greeter/between/a'),
        );
        self::assertEquals(
            new Route(Greeter::class, 'between', ['a', 'b']),
            $router->resolve('/greeter/between/a/b'),
        );
    }

    public function testDecodesParameters(): void
    {
        $router = new Router('Tests\\Fixtures\\', 'Greeter');

        self::assertEquals(
            new Route(Greeter::class, 'hello', ['Zach Ayers']),
            $router->resolve('/greeter/hello/Zach%20Ayers'),
        );
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function unroutablePaths(): iterable
    {
        yield 'unknown controller' => ['/missing'];
        yield 'unknown method' => ['/greeter/missing'];
        yield 'protected method' => ['/greeter/secret'];
        yield 'static method' => ['/greeter/make'];
        yield 'magic method' => ['/greeter/__construct'];
        yield 'missing required parameter' => ['/greeter/hello'];
        yield 'too many parameters' => ['/greeter/index/extra'];
        yield 'path traversal' => ['/../greeter'];
        yield 'namespace separator' => ['/Fixtures%5CGreeter'];
    }

    #[DataProvider('unroutablePaths')]
    public function testRejectsPathsThatAreNotPublicActions(string $path): void
    {
        $router = new Router('Tests\\Fixtures\\', 'Greeter');

        self::assertNull($router->resolve($path));
    }

    public function testRejectsClassesThatAreNotControllers(): void
    {
        // RouterTest lives in the Tests namespace but does not extend Controller.
        $router = new Router('Tests\\');

        self::assertNull($router->resolve('/routerTest/testRejectsClassesThatAreNotControllers'));
    }
}
