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

    public function insertar(string $usuario, string $operacion): void
    {
        $stmt = $this->pdo->prepare("CALL sp_insert_log(:usuario, :operacion)");
        $stmt->execute([
            ':usuario' => $usuario,
            ':operacion' => $operacion
        ]);
    }
}
