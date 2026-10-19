<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Post;

final class Home extends Controller
{
    public function index(): string
    {
        return $this->render('home/index', [
            'title' => 'GalaxyMVC',
            'posts' => (new Post($this->db))->all(),
        ]);
    }

    public function about(): string
    {
        return $this->render('home/about', ['title' => 'About']);
    }
}
