<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Post;
use RuntimeException;

final class Home extends Controller
{
    public function index(): string
    {
        return $this->render('home/index', [
            'title' => 'GalaxyMVC',
            'posts' => (new Post($this->db))->all(),
            'sources' => $this->source(),
        ]);
    }

    public function about(): string
    {
        return $this->render('home/about', ['title' => 'Guide']);
    }

    /** @return array<string, string> */
    private function source(): array
    {
        // Only these public demo files can be displayed; no request value becomes a file path.
        $sources = [];
        foreach (['Controllers/Home.php', 'Models/Post.php', 'views/home/posts.php'] as $path) {
            $contents = file_get_contents(dirname(__DIR__) . '/' . $path);
            if ($contents === false) {
                throw new RuntimeException('Demo source is unavailable.');
            }
            $sources['app/' . $path] = $contents;
        }

        return $sources;
    }
}
