<?php
namespace App\Models;

use PDO;
use PDOException;

class Entrada
{
    private PDO $pdo;
    private ?string $lastError = null;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getLastError(): ?string
    {
        return $this->lastError;
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

    public function listarTodasOrdenado(string $orden = 'fecha', string $dir = 'desc'): array
    {
        $ordenesPermitidos = [
            'fecha' => 'e.fecha',
            'titulo' => 'e.titulo',
            'categoria' => 'c.nombre',
            'autor' => 'u.nick'
        ];

        $dir = strtolower($dir);
        if ($dir !== 'asc' && $dir !== 'desc') {
            $dir = 'desc';
        }

        $col = $ordenesPermitidos[$orden] ?? 'e.fecha';

        $sql = "SELECT e.*, c.nombre AS categoria_nombre, u.nick AS autor_nick
                FROM entradas e
                INNER JOIN categorias c ON c.id = e.categoria_id
                INNER JOIN usuarios u ON u.id = e.usuario_id
                ORDER BY $col $dir";

        return $this->pdo->query($sql)->fetchAll();
    }

    public function buscar(string $texto, string $orden = 'fecha', string $dir = 'desc'): array
    {
        $ordenesPermitidos = [
            'fecha' => 'e.fecha',
            'titulo' => 'e.titulo',
            'categoria' => 'c.nombre',
            'autor' => 'u.nick'
        ];

        $dir = strtolower($dir);
        if ($dir !== 'asc' && $dir !== 'desc') {
            $dir = 'desc';
        }

        $col = $ordenesPermitidos[$orden] ?? 'e.fecha';

        $sql = "SELECT e.*, c.nombre AS categoria_nombre, u.nick AS autor_nick
                FROM entradas e
                INNER JOIN categorias c ON c.id = e.categoria_id
                INNER JOIN usuarios u ON u.id = e.usuario_id
                WHERE e.titulo LIKE :q
                   OR e.descripcion LIKE :q
                   OR c.nombre LIKE :q
                   OR u.nick LIKE :q
                ORDER BY $col $dir";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':q' => '%' . $texto . '%']);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM entradas WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findDetalle(int $id): ?array
    {
        $sql = "SELECT e.*, 
                       c.nombre AS categoria_nombre,
                       u.nick AS autor_nick
                FROM entradas e
                INNER JOIN categorias c ON c.id = e.categoria_id
                INNER JOIN usuarios u ON u.id = e.usuario_id
                WHERE e.id = :id
                LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function crear(array $data): bool
    {
        $this->lastError = null;

        try {
            $sql = "INSERT INTO entradas (usuario_id, categoria_id, titulo, imagen, descripcion, fecha)
                    VALUES (:usuario_id, :categoria_id, :titulo, :imagen, :descripcion, NOW())";
            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute([
                ':usuario_id' => (int)$data['usuario_id'],
                ':categoria_id' => (int)$data['categoria_id'],
                ':titulo' => $data['titulo'],
                ':imagen' => $data['imagen'],
                ':descripcion' => $data['descripcion']
            ]);
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    public function actualizar(int $id, array $data): bool
    {
        $this->lastError = null;

        try {
            $sql = "UPDATE entradas
                    SET categoria_id = :categoria_id,
                        titulo = :titulo,
                        imagen = :imagen,
                        descripcion = :descripcion
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute([
                ':categoria_id' => (int)$data['categoria_id'],
                ':titulo' => $data['titulo'],
                ':imagen' => $data['imagen'],
                ':descripcion' => $data['descripcion'],
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    public function eliminar(int $id): bool
    {
        $this->lastError = null;

        try {
            $stmt = $this->pdo->prepare("DELETE FROM entradas WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }
}
