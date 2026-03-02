<?php

class Auth
{
  public static function login(array $user): void
  { //Salva os dados do usuario na sessão após o login bem sucedido
    $_SESSION['user'] = [
      'id' => $user['id'],
      'nome' => $user['nome'],
      'email' => $user['email'],
      'role' => $user['role_nome'] //Papel no sistema, join junto com a tabela de roles
    ];
  }

  public static function logout(): void
  {
    unset($_SESSION['user']); //Remove os dados da sessão

    session_destroy(); //Destroi a sessão
  }


  // Verifica se há um usuario logado
  public static function check(): bool
  {
    return isset($_SESSION['user']);
  }

  // Retorna os dados do usuario logado ou null
  public static function user(): ?array
  {
    return $_SESSION['user'] ?? null;
  }

  // Verifica se o usuario tem um dos papeis permitidos
  // O ... spread permite passar multiplos papeis
  // Auth::hasRole('admin', 'editor')
  public static function hasRole(string ...$roles): bool
  {
    $userRole = $_SESSION['user']['role'] ?? null;
    return in_array($userRole, $roles, true);
  }
}
