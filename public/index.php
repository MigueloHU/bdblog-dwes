<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Database;

$pdo = (new Database())->getConnection();


 // index.php?controller=entrada&action=listar
 
$controller = filter_input(INPUT_GET, 'controller') ?? 'entrada';
$action     = filter_input(INPUT_GET, 'action') ?? 'listar';

$controllerClass = 'App\\Controllers\\' . ucfirst($controller) . 'Controller';

if (!class_exists($controllerClass)) {
    die("Controlador no encontrado: " . htmlspecialchars($controllerClass));
}

$c = new $controllerClass($pdo);

if (!method_exists($c, $action)) {
    die("Acción no encontrada: " . htmlspecialchars($action));
}

$c->$action();
