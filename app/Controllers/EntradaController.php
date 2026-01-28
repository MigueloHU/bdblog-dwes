<?php
namespace App\Controllers;

use PDO;

class EntradaController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function listar(): void
    {
        $titulo = "Listado de entradas (placeholder)";
        require __DIR__ . '/../Views/entradas/listar.php';
    }
}
