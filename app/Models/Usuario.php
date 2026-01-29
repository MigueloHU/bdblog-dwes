<?php
namespace App\Models;

use PDO;

class Usuario
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT id, nick, email, password, perfil
                FROM usuarios
                WHERE email = :email
                LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);

        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function listar(): array
    {
        $sql = "SELECT id, nick, nombre, apellidos, email, imagen_avatar, perfil
                FROM usuarios
                ORDER BY id DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function detalle(int $id): ?array
    {
        $sql = "SELECT id, nick, nombre, apellidos, email, imagen_avatar, perfil
                FROM usuarios
                WHERE id = :id
                LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch();
        return $row ?: null;
    }
}
