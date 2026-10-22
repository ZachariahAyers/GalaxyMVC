<?php

declare(strict_types=1);

namespace Tests;

use App\Controllers\Home;
use App\Core\Config;
use App\Core\Database;
use App\Core\Router;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\TestCase;

#[CoversClass(Home::class)]
#[RequiresPhpExtension('pdo_sqlite')]
final class DemoSourceTest extends TestCase
{
    public function testDemoShowsEscapedActualSourceAlongsideLiveData(): void
    {
        $db = Database::connect(new Config('sqlite::memory:'));
        $db->execute('CREATE TABLE posts (id INTEGER PRIMARY KEY, title TEXT, content TEXT)');
        $db->execute('INSERT INTO posts VALUES (?, ?, ?)', [7, 'A real row', '<b>Untrusted</b>']);

        $html = (new Home($db))->index();

        self::assertStringContainsString('A real row', $html);
        self::assertStringContainsString('&lt;b&gt;Untrusted&lt;/b&gt;', $html);
        self::assertStringContainsString('<summary>', $html);
        foreach (['app/Controllers/Home.php', 'app/Models/Post.php', 'app/views/home/posts.php'] as $path) {
            $source = file_get_contents(dirname(__DIR__) . '/' . $path);
            self::assertIsString($source);
            self::assertStringContainsString(htmlspecialchars($source), $html);
        }
    }

    public function testSourceReaderIsNotARoutableAction(): void
    {
        self::assertNull((new Router())->resolve('/home/source'));
    }
}
