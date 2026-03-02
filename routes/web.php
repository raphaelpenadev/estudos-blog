<?php


// Rotas Publicas
$router->get('/', 'Public\HomeController@index');
$router->get('/noticia/{slug}', 'Public\HomeController@show');
// Slug é passado como argumento no método show()

// Autenticação
$router->get('login', 'Auth\AuthController@showLogin'); // exibe o formulario
$router->get('login', 'Auth\AuthController@login'); // processa o formulario
// GET e POST na mesma URI é intencional:
// GET /login -> mostra o form | POST /login -> Processa o login
$router->get('/logout', 'Auth\AuthController@logout');


// Admin - Usuarios
// Padrão RESTful: substantivo no plural + verbo HTTP define a ação
$router->get('/admin/usuarios', 'Admin\UserController@index');
$router->get('/admin/usuarios/criar', 'Admin\UserController@create');
$router->get('/admin/usuarios/criar', 'Admin\UserController@store');
$router->get('/admin/usuarios/{id}/editar', 'Admin\UserController@edit');
$router->post('/admin/usuarios/{id}/editar', 'Admin\UserController@update');
$router->get('/admin/usuarios/{id}/deletar', 'Admin\UserController@destroy');
// Usamos POST para deletar porque HTML forms só suporta GET e POST
// Em APIs REST usuariamos DELETE, mas aqui é PHP puro com forms HTML
