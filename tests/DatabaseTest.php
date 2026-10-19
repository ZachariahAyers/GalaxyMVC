<?php

declare(strict_types=1);

namespace Tests;

use App\Core\Config;
use App\Core\Database;
use App\Models\Post;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\TestCase;

#[CoversClass(Database::class)]
#[CoversClass(Post::class)]
#[RequiresPhpExtension('pdo_sqlite')]
final class DatabaseTest extends TestCase
{
    private Database $db;

    protected function setUp(): void
    {
        $this->db = Database::connect(new Config('sqlite::memory:'));
        $this->db->execute(
            'CREATE TABLE posts (id INTEGER PRIMARY KEY, title TEXT NOT NULL, content TEXT, published INTEGER)',
        );
    }

    public function testBindsPositionalParameters(): void
    {
        $this->db->execute('INSERT INTO posts (id, title, content) VALUES (?, ?, ?)', [1, 'First', 'Body']);

        self::assertSame(
            ['id' => 1, 'title' => 'First', 'content' => 'Body'],
            $this->db->first('SELECT id, title, content FROM posts WHERE id = ?', [1]),
        );
    }

    public function testBindsNamedParametersWithOrWithoutColon(): void
    {
        $this->db->execute(
            'INSERT INTO posts (id, title, content) VALUES (:id, :title, :content)',
            ['id' => 2, ':title' => 'Named', 'content' => 'Body'],
        );

        $row = $this->db->first('SELECT title FROM posts WHERE id = :id', ['id' => 2]);

        self::assertSame('Named', $row['title'] ?? null);
    }

    public function testBindsNullAndBooleanValues(): void
    {
        $this->db->execute(
            'INSERT INTO posts (id, title, content, published) VALUES (?, ?, ?, ?)',
            [3, 'Draft', null, true],
        );

        $row = $this->db->first('SELECT content, published FROM posts WHERE id = ?', [3]);

        self::assertSame(['content' => null, 'published' => 1], $row);
    }

    public function testFirstReturnsNullWhenNothingMatches(): void
    {
        self::assertNull($this->db->first('SELECT * FROM posts WHERE id = ?', [99]));
    }

    public function testExecuteReturnsAffectedRowCount(): void
    {
        $this->db->execute('INSERT INTO posts (id, title) VALUES (?, ?), (?, ?)', [1, 'a', 2, 'b']);

        self::assertSame(2, $this->db->execute('UPDATE posts SET title = ?', ['c']));
        self::assertSame(1, $this->db->execute('DELETE FROM posts WHERE id = ?', [1]));
    }

    public function testValuesAreNeverInterpretedAsSql(): void
    {
        $hostile = "x'); DROP TABLE posts; --";
        $this->db->execute('INSERT INTO posts (id, title) VALUES (?, ?)', [1, $hostile]);

        self::assertSame([['title' => $hostile]], $this->db->select('SELECT title FROM posts'));
    }

    public function testPostModelListsPostsInIdOrder(): void
    {
        $this->db->execute(
            'INSERT INTO posts (id, title, content) VALUES (?, ?, ?), (?, ?, ?)',
            [2, 'Second', 'B', 1, 'First', 'A'],
        );

        self::assertSame(
            [
                ['id' => 1, 'title' => 'First', 'content' => 'A'],
                ['id' => 2, 'title' => 'Second', 'content' => 'B'],
            ],
            (new Post($this->db))->all(),
        );
    }
}
