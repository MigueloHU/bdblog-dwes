<?php
namespace App\Controllers;

use PDO;
use App\Config\Auth;


class EntradaController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function listar(): void
    {
        Auth::requireLogin();
        $titulo = "Listado de entradas (placeholder)";
        require __DIR__ . '/../Views/entradas/listar.php';
    }
}
