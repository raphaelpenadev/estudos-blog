<?php

namespace Public;

require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/app/models/Post.php';

class HomeController extends \Controller
{
  public function index(): void
  {
    $posts = (new \Post())->findPublished();

    $this->render('public.home', [
      'posts' => $posts
    ]);
  }

  public function show(string $slug): void
  {
    $post = (new \Post())->findBySlug($slug);

    if (!$post) {
      http_response_code(404);
      die('<h1>Notícia não encontrada</h1>');
    }

    $this->render('public.post', [
      'post' => $post
    ]);
  }
}
