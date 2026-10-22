<?php

declare(strict_types=1);

namespace Tests;

use App\Core\View;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(View::class)]
final class RenderedPagesTest extends TestCase
{
    protected function tearDown(): void
    {
        putenv('BASE_PATH');
    }

    public function testDemoEscapesDatabaseValuesAndKeepsPrefixedLinks(): void
    {
        putenv('BASE_PATH=/opensource/galaxymvc');
        $html = View::render('home/index', [
            'title' => 'GalaxyMVC',
            'sources' => [],
            'posts' => [['id' => 1, 'title' => '<script>alert(1)</script>', 'content' => '<img src=x onerror=alert(1)>']],
        ]);

        self::assertStringNotContainsString('<script>', $html);
        self::assertStringNotContainsString('<img src=x', $html);
        self::assertStringContainsString('&lt;script&gt;', $html);
        self::assertStringContainsString('&lt;img src=x', $html);
        self::assertStringContainsString('href="/opensource/galaxymvc/home/about#start"', $html);
        self::assertStringContainsString('href="/opensource/galaxymvc/css/style.css"', $html);
        self::assertStringContainsString('aria-label="Records rendered from the database"', $html);
        self::assertSame(1, substr_count($html, 'class="patch-seal"'));
    }

    public function testEmptyDatabaseHasAnExplanation(): void
    {
        $html = View::render('home/index', ['title' => 'GalaxyMVC', 'posts' => [], 'sources' => []]);

        self::assertStringContainsString('No posts in the database.', $html);
        self::assertStringNotContainsString('class="result-row"', $html);
    }

    public function testGuideAndMissingRouteUseTheSharedShell(): void
    {
        putenv('BASE_PATH=/opensource/galaxymvc');
        foreach (['home/about', 'errors/404'] as $view) {
            $html = View::render($view, ['title' => '<Test>']);
            self::assertStringContainsString('&lt;Test&gt;', $html);
            self::assertStringContainsString('href="/opensource/galaxymvc/"', $html);
            self::assertStringContainsString('id="main"', $html);
            self::assertStringContainsString('Ayers Software Repair', $html);
        }
    }
}
