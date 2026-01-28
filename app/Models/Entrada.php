<?php
namespace App\Models;

use PDO;

class Entrada
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function listarAdmin(): array
    {
        $sql = "SELECT e.*, c.nombre AS categoria_nombre, u.nick AS autor_nick
                FROM entradas e
                INNER JOIN categorias c ON c.id = e.categoria_id
                INNER JOIN usuarios u ON u.id = e.usuario_id
                ORDER BY e.fecha DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function listarPorUsuario(int $usuarioId): array
    {
        $sql = "SELECT e.*, c.nombre AS categoria_nombre, u.nick AS autor_nick
                FROM entradas e
                INNER JOIN categorias c ON c.id = e.categoria_id
                INNER JOIN usuarios u ON u.id = e.usuario_id
                WHERE e.usuario_id = :uid
                ORDER BY e.fecha DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':uid' => $usuarioId]);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM entradas WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function crear(array $data): bool
    {
        $sql = "INSERT INTO entradas (usuario_id, categoria_id, titulo, imagen, descripcion, fecha)
                VALUES (:usuario_id, :categoria_id, :titulo, :imagen, :descripcion, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':usuario_id' => $data['usuario_id'],
            ':categoria_id' => $data['categoria_id'],
            ':titulo' => $data['titulo'],
            ':imagen' => $data['imagen'],
            ':descripcion' => $data['descripcion']
        ]);
    }

    public function actualizar(int $id, array $data): bool
    {
        $sql = "UPDATE entradas
                SET categoria_id = :categoria_id,
                    titulo = :titulo,
                    imagen = :imagen,
                    descripcion = :descripcion
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':categoria_id' => $data['categoria_id'],
            ':titulo' => $data['titulo'],
            ':imagen' => $data['imagen'],
            ':descripcion' => $data['descripcion'],
            ':id' => $id
        ]);
    }

    public function eliminar(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM entradas WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
