<?php

require_once BASE_PATH . '/core/Model.php';

class Post extends Model
{
  protected string $table = 'posts';

  public function create(array $data): bool
  {
    $stmt = $this->db->prepare("INSERT INTO posts (titulo, slug, conteudo, status, autor_id, criado_em) VALUES (:titulo, :slug, :conteudo, :status, :autor_id, NOW())");

    return $stmt->execute($data);
  }

  public function update(int $id, array $data): bool
  {
    $stmt = $this->db->prepare("UPDATE posts SET titulo = :titulo, slug = :slug, conteudo = :conteudo, status = :status WHERE id = :id");

    $data['id'] = $id;

    return $stmt->execute($data);
  }

  public function publish(int $id): bool
  {
    $stmt = $this->db->prepare("UPDATE posts SET status = 'published', publicado_em = NOW() WHERE id = ?");

    return $stmt->execute([$id]);
  }

  public function findPublished(): array
  {
    $stmt = $this->db->query("
    SELECT p.*, u.nome AS autor_nome
    FROM posts p
    JOIN users u ON u.id = p.autor_id
    WHERE p.status = 'published'
    ORDER BY p.publicado_em DESC
    ");

    return $stmt->fetchAll();
  }

  public function findBySlug(string $slug): array|false
  {
    $stmt = $this->db->prepare("
    SELECT p.*, u.nome AS autor_nome
    FROM posts p
    JOIN users u ON u.id = p.author_id
    WHERE p.slug = ?
    AND p.status = 'published'
    ");

    $stmt->execute([$slug]);

    return $stmt->fetch();
  }
}
