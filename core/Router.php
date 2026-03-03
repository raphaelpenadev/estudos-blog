<?php

class Router
{

  // Armazena todas as rotas registradas
  // Cada rota é um array com: method, uri, action
  // Como fica após o registro
  // [
  //  ['method' => 'GET',  'uri' => '/',              'action' => 'Public\HomeController@index'],
  //  ['method' => 'GET',  'uri' => '/noticia/{slug}','action' => 'Public\HomeController@show'],
  //  ['method' => 'POST', 'uri' => '/login',         'action' => 'Auth\AuthController@login'],
  // ]


  private array $routes = [];

  // Métodos publicos que são chamados no routes/web.php

  public function get(string $uri, $action): void
  {
    $this->addRoute('GET', $uri, $action);
  }

  // Registra uma rota POST
  public function post(string $uri, string $action): void
  {
    $this->addRoute('POST', $uri, $action);
  }

  // Metodo que adiciona de fato a rota ao array
  // private: só pode ser chamada pelo proprio router
  // Metodos get() e post() são só atalhos

  private function addRoute(string $method, string $uri, string $action): void
  {
    $this->routes[] = [
      'method' => $method,
      'uri' => $uri,
      'action' => $action
    ];
  }

  public function dispatch(): void
  {

    // $_SERVER['REQUEST_URI'] contém a URI completa, incluindo query string

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
    // explode('@', $action) divide a string pelo @
    [$controllerName, $method] = explode('@', $action);

    // Converte namespace em caminho de arquivo
    $file = BASE_PATH . '/app/controllers/' . str_replace('\\', '/', $controllerName) . '.php';

    if (!file_exists($file)) {
      die("Controller não encontrado: {$file}");
    }

    require_once $file;

    // Instancia o controller pelo nome da classe (variável como nome de classe)
    $controller = new $controllerName();

    if (!method_exists($controller, $method)) {
      die("Método: '{$method}' não encontrado em '{$controllerName}'");
    }


    // call_user_func_array() chama um método passando um array como argumentos.
    // É equivalente a: $controller->show('minha-noticia')
    // Mas de forma dinâmica, sem saber o nome do método em tempo de escrita.
    call_user_func_array([$controller, $method], $params);
  }
}
