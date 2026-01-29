<?php
namespace App\Controllers;


use PDO;
use App\Models\Usuario;
use App\Config\Auth;

class AuthController
{
    private PDO $pdo;
    private array $errores = [];

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        Auth::start();
    }

    public function login(): void
    {
        $titulo = "Login";
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $pass  = $_POST['password'] ?? '';

            if ($email === '' || $pass === '') {
                $errores[] = "Debes rellenar email y contraseña.";
            } else {
                $usuarioModel = new Usuario($this->pdo);
                $user = $usuarioModel->findByEmail($email);

                if (!$user || !password_verify($pass, $user['password'])) {
                    $errores[] = "Credenciales incorrectas.";
                } else {
                    Auth::login($user);
                    header('Location: index.php?controller=entrada&action=listar');
                    exit;
                }
            }
        }

        require __DIR__ . '/../Views/auth/login.php';
    }

    public function logout(): void
    {
        Auth::logout();
        header('Location: index.php?controller=auth&action=login');
        exit;
    }
}
