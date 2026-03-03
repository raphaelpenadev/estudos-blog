<?php

require_once BASE_PATH . '/coore/Model.php';

class User extends Model
{

  protected string $table = 'users';

  // Buscar usuario por email
  public function findByEmail(string $email): array|false
  {
    $stmt = $this->db->prepare("
    SELECT u.*, r.nome AS role_name
    FROM users u
    JOIN roles r ON r.id = u.role_id
    WHERE u.email = ?
    ");

    $stmt->execute([$email]);

    return $stmt->fetch();
  }


  // Novo usuario
  public function create(string $nome, string $email, string $senha, int $roleId): bool
  {
    $hash = password_hash($senha, PASSWORD_BCRYPT);
    $stmt = $this->db->prepare("
      INSERT INTO users (nome, email, senha, role_id, criado_em)
      VALUES (?,?,?,?,NOW())
    ");

    return $stmt->execute([$nome, $email, $hash, $roleId]);
  }

  // Atualiza dados do usuario
  public function update(int $id, string $nome, string $email, int $roleId): bool
  {
    $stmt = $this->db->prepare("
      UPDATE users SET nome = ?, email = ?, role_id = ? WHERE id = ?
    ");

    return $stmt->execute([$nome, $email, $roleId, $id]);
  }

  // Lista todos os usuarios com seu perfil
  public function allWithRoles(): array
  {
    $stmt = $this->db->query("
    SELECT u.*, r.nome AS role_name
    FROM users u
    JOIN roles r ON r.id = u.role_id
    ORDER BY u.criado_em DESC
  ");

    return $stmt->fetchAll();
  }
}
