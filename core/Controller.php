<?php


abstract class Controller
{

  // Atalho para renderizar as views
  protected function render(string $view, array $data = [], string $layout = 'main'): void
  {
    View::render($view, $data, $layout);
  }

  // Redireciona para outra URL
  // exit() é obrigatório - sem ele o PHP continua executando o código abaixo
  protected function redirect(string $uri): void
  {
    header('Location: ' . $uri);
    exit;
  }

  // Bloqueia acesso se não estiver logado
  protected function requireAuth(): void
  {
    if (!Auth::check()) {
      $this->redirect('/login');
    }
  }

  // Bloqueia acesso se não tiver o papel necessário
  // Primeiro verifica autenticação, depois o papel
  protected function requireRole(string ...$roles): void
  {
    $this->requireAuth();

    if (!Auth::hasRole(...$roles)) {
      http_response_code(403);
      die('<h1>403 - Acesso Negado</h1>');
    }
  }
}
