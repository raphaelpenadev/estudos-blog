<?php

class Router
{

  // Armazena todas as rotas registradas
  private array $routes = [];

  public function get(string $uri, $action): void
  {
    $this->addRoute('GET', $uri, $action);
  }

  // Registra uma rota POST
  public function post(string $uri, string $action): void
  {
    $this->addRoute('POST', $uri, $action);
  }

  private function addRoute(string $method, string $uri, string $action): void
  {
    $this->routes[] = [
      'method' => $method,
      'uri' => $uri,
      'action' => $action
    ];
  }

  public function dispatch(string $uri): void
  {

    // Pega o caminho da URL sem query strings
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $requestMethod = $_SERVER['REQUEST_METHOD'];

    // Remove subdiretorios se o projeto não estiver na raiz
    $scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    if ($scriptDir !== '' && str_starts_with($requestUri, $scriptDir)) {
      $requestUri = substr($requestUri, strlen($scriptDir));
    }

    // Normaliza: garante que começa com '/' e não tem '/' no final
    $requestUri = '/' . trim($requestUri, '/');

    foreach ($this->routes as $route) {
      $pattern = $this->uriToRegex($route['uri']);

      if ($route['method'] === $requestMethod && preg_match($pattern, $requestUri, $matches)) {
        array_shift($matches); //Remove o match completo, restando somente o grupo

        $this->callAction($route['action'], $matches);
        return;
      }
    }

    http_response_code(404);
    echo '<h1>404 - Página não encontrada</h1>';
  }

  // Converte URI com parametros em REGEX
  private function uriToRegex(string $uri): string
  {
    $pattern = preg_replace('/\{[a-zA-Z_]+\}/', '([^/]+)', $uri);
    return '#^' . $pattern . '$#';
  }

  // Instancia o controller e chama o metodo
  private function callAction(string $action, array $params = []): void
  {
    [$controllerName, $method] = explode('@', $action);

    // Converte namespace em caminho de arquivo
    $file = BASE_PATH . '/app/controllers/' . str_replace('\\', '/', $controllerName) . '.php';

    if (!file_exists($file)) {
      die("Controller não encontrado: {$file}");
    }

    require_once $file;

    $controller = new $controllerName();

    if (!method_exists($controller, $method)) {
      die("Método: {$method} não encontrado em {$controllerName}");
    }

    // Chama o método passando parametros da URL
    call_user_func_array([$controller, $method], $params);
  }
}
