<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class Post
{
    public function __construct(private readonly Database $db)
    {
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function all(): array
    {
        return $this->db->select('SELECT id, title, content FROM posts ORDER BY id');
    }
}
