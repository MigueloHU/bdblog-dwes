<?php
namespace App\Config;

class Auth
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function login(array $user): void
    {
        self::start();
        $_SESSION['user'] = [
            'id'     => (int)$user['id'],
            'nick'   => $user['nick'],
            'perfil' => $user['perfil']
        ];
    }

    public static function logout(): void
    {
        self::start();
        $_SESSION = [];
        session_destroy();
    }

    public static function user(): ?array
    {
        self::start();
        return $_SESSION['user'] ?? null;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function isAdmin(): bool
    {
        $u = self::user();
        return $u && $u['perfil'] === 'admin';
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
    }
}
