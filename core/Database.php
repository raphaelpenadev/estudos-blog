<?php

class Database
{
  // static: pertence a classe independente da instancia
  // PDO: pode ser null antes da primeira conexão

  private static ?PDO $instance = null;

  public static function getInstance(): PDO
  {

    // Cria a conexão caso não exista
    if (self::$instance === null) {
      $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

      try {
        self::$instance = new PDO($dsn, DB_USER, DB_PASS, [
          // Lança exceções, ao invés de retornar false silencioso
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          // Retorna arrays associativos por padrão
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
          // Usa prepared statements reais (segurança)
          PDO::ATTR_EMULATE_PREPARES => false
        ]);
      } catch (PDOException $e) {
        //Para produção sempre um erro visual e para analisar (logs)
        die('Erro de conexão: ' . $e->getMessage());
      }
    }

    return self::$instance;
  }
}
