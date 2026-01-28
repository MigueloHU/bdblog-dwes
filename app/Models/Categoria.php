<?php
namespace App\Models;

use PDO;

class Categoria
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function all(): array
    {
        $sql = "SELECT id, nombre FROM categorias ORDER BY nombre ASC";
        return $this->pdo->query($sql)->fetchAll();
    }
}
