<?php

// abstract: não pode ser instanciada diretamente
// Serve de molde para os outros modelos

abstract class Model
{
  protected PDO $db;

  // Cada model filho declara sua propria tabela
  // protected: acessivel apenas nas classes filhas, mas não fora
  protected string $table;

  public function __construct()
  {
    // Pega a conexão
    $this->db = Database::getInstance();
  }

  // Busca todos os registros da tabela
  public function findAll(): array
  {
    $stmt = $this->db->query("SELECT * FROM {$this->table}");
    return $stmt->fetchAll();
  }

  // Busca por ID, sempre usar prepared statements
  public function findById(int $id): array | false
  {
    $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE id = :id");
    $stmt->execute([
      ':id' => $id
    ]);
    return $stmt->fetch();
  }

  // Busca por qualquer coluna - flexivel e reutilizavel
  public function findBy(string $column, mixed $value): array | false
  {
    $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$column} = ?");
    $stmt->execute([$value]);
    return $stmt->fetch();
  }

  public function delete(int $id): bool
  {
    $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
    $stmt->execute([$id]);
  }
}
