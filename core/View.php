<?php

class View
{
  public static function render(string $view, array $data = [], string $layout = 'main'): void
  {
    // extract transforma chaves do array em variaveis
    // ['posts' => [...]] vira $post = [...]
    // Assim as views podem usar $posts diretamente
    extract($data);

    // Converte notação de ponto em caminho de arquivo
    // 'public.home' -> /app/views/public/home.php'
    $viewPath = BASE_PATH . '/app/views/' . str_replace('.', '/', $view) . '.php';

    if (!file_exists($viewPath)) {
      die("View não encontrada: {$viewPath}");
    }

    // Inicia o buffer - o HTML da view vai para a memória, não para o navegador
    ob_start();

    require $viewPath;

    // Pega o HTML gerado e limpa o buffer
    $content = ob_get_clean();

    // Renderiza o layout
    // O layout usa a variável $content para inserir o HTML da view
    $layoutPath = BASE_PATH . '/app/views/layouts/' . $layout . '.php';

    if (file_exists($layoutPath)) {
      require $layoutPath; // $content fica disponivel aqui
    } else {
      echo $content;
    }
  }
}
