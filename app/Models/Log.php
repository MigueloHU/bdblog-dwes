<?php
namespace App\Models;

use PDO;

class Log
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // 2.13
    public function insertar(string $usuario, string $operacion): void
    {
        $stmt = $this->pdo->prepare("CALL sp_insert_log(:usuario, :operacion)");
        $stmt->execute([
            ':usuario' => $usuario,
            ':operacion' => $operacion
        ]);
    }

    // 2.14
    public function listar(): array
    {
        $sql = "SELECT id, fecha_hora, usuario, operacion
                FROM logs
                ORDER BY id DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function eliminar(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM logs WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function eliminarTodos(): bool
    {
        return (bool)$this->pdo->exec("DELETE FROM logs");
    }
}
