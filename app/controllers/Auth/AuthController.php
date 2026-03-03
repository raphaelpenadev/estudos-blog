<?php

namespace Auth;

require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/app/models/User.php';

class AuthController extends \Controller
{
  // Exibe o formulario de login caso não esteja logado
  public function showLogin(): void
  {
    if (\Auth::check()) {
      $this->redirect('/');
    }

    $this->render('auth.login', []);
  }

  public function login(): void
  {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    $userModel = new \User();
    $user = $userModel->findByEmail($email);

    if ($user && password_verify($senha, $user['senha'])) {
      \Auth::login($user);
      $this->redirect('/');
    }

    $this->render('auth.login', [
      'erro' => 'E-mail ou senha inválidos.'
    ]);
  }

  public function logout(): void
  {
    \Auth::logout();
    $this->redirect('/login');
  }
}
