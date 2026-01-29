<?php
namespace App\Controllers;

use PDO;
use App\Config\Auth;
use App\Models\Usuario;

class UsuarioController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        Auth::start();
    }

    public function listar(): void
    {
        Auth::requireLogin();

        // Si quieres que solo admin vea usuarios, descomenta:
        // if (!Auth::isAdmin()) { die("Acceso restringido."); }

        $usuarios = (new Usuario($this->pdo))->listar();
        $titulo = "Listado de usuarios";
        require __DIR__ . '/../Views/usuarios/listar.php';
    }

    public function detalle(): void
    {
        Auth::requireLogin();

        // Si quieres que solo admin vea detalle, descomenta:
        // if (!Auth::isAdmin()) { die("Acceso restringido."); }

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) die("ID inválido.");

        $usuario = (new Usuario($this->pdo))->detalle($id);
        if (!$usuario) die("Usuario no encontrado.");

        $titulo = "Detalle de usuario";
        require __DIR__ . '/../Views/usuarios/detalle.php';
    }
}
