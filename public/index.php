<?php

// Caminho absoluto para a raiz do projeto
define('BASE_PATH', dirname(__DIR__));

// Carregar as configurações do banco
require_once BASE_PATH . '/config/database.php';

// Carrega os contrutores do sistema


require_once BASE_PATH . '/core/Database.php';
require_once BASE_PATH . '/core/Model.php';
require_once BASE_PATH . '/core/View.php';
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/core/Router.php';
require_once BASE_PATH . '/core/Auth.php';

// Inicia a sessão, necessario para login/logout
session_start();


// Cria o roteador com as rotas definidas
$router = new Router();

require_once BASE_PATH . '/routes/web.php';


// Analisa a url e direciona para o controller correto
$router->dispatch();
