<?php
namespace App\Controllers;

use PDO;
use App\Config\Auth;
use App\Models\Entrada;
use App\Models\Categoria;

class EntradaController
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

        $entradaModel = new Entrada($this->pdo);

        $orden = $_GET['orden'] ?? 'fecha';
        $dir   = $_GET['dir'] ?? 'desc';
        $q     = trim($_GET['q'] ?? '');

        if ($q !== '') {
            $entradas = $entradaModel->buscar($q, $orden, $dir);
        } else {
            $entradas = $entradaModel->listarTodasOrdenado($orden, $dir);
        }

        $titulo = "Listado de entradas";
        require __DIR__ . '/../Views/entradas/listar.php';
    }

    public function crear(): void
    {
        Auth::requireLogin();

        $categoriaModel = new Categoria($this->pdo);
        $categorias = $categoriaModel->all();

        $titulo = "Crear entrada";
        $modo = "crear";
        $entrada = [
            'id' => null,
            'categoria_id' => '',
            'titulo' => '',
            'imagen' => null,
            'descripcion' => ''
        ];
        $errores = [];

        require __DIR__ . '/../Views/entradas/form.php';
    }

    public function guardar(): void
    {
        Auth::requireLogin();

        $user = Auth::user();

        $categoriaId = (int)($_POST['categoria_id'] ?? 0);
        $tituloPost  = trim($_POST['titulo'] ?? '');

        // CKEditor: llega HTML, validamos en texto plano (sin tags)
        $descripcionHtml  = $_POST['descripcion'] ?? '';
        $descripcionTexto = trim(strip_tags($descripcionHtml));

        $errores = [];
        if ($categoriaId <= 0) $errores[] = "Debes seleccionar una categoría.";
        if ($tituloPost === '') $errores[] = "El título es obligatorio.";
        if ($descripcionTexto === '') $errores[] = "La descripción es obligatoria.";

        $nombreImagen = null;
        if (!empty($_FILES['imagen']['name'])) {
            $nombreImagen = $this->subirImagen($_FILES['imagen'], $errores);
        }

        if (!empty($errores)) {
            $categoriaModel = new Categoria($this->pdo);
            $categorias = $categoriaModel->all();

            $titulo = "Crear entrada";
            $modo = "crear";
            $entrada = [
                'id' => null,
                'categoria_id' => $categoriaId,
                'titulo' => $tituloPost,
                'imagen' => $nombreImagen,
                'descripcion' => $descripcionHtml
            ];

            require __DIR__ . '/../Views/entradas/form.php';
            return;
        }

        $entradaModel = new Entrada($this->pdo);

        $ok = $entradaModel->crear([
            'usuario_id' => (int)$user['id'],
            'categoria_id' => $categoriaId,
            'titulo' => $tituloPost,
            'imagen' => $nombreImagen,
            'descripcion' => $descripcionHtml
        ]);

        if (!$ok) {
            // Si tienes getLastError() en el modelo, lo mostramos
            $msg = method_exists($entradaModel, 'getLastError') ? $entradaModel->getLastError() : 'Error desconocido';
            die("Error al insertar en BD: " . $msg);
        }

        header('Location: index.php?controller=entrada&action=listar');
        exit;
    }

    public function editar(): void
    {
        Auth::requireLogin();

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) die("ID inválido.");

        $entradaModel = new Entrada($this->pdo);
        $entrada = $entradaModel->find($id);
        if (!$entrada) die("Entrada no encontrada.");

        // Permisos: solo autor o admin
        $user = Auth::user();
        if (!Auth::isAdmin() && (int)$entrada['usuario_id'] !== (int)$user['id']) {
            die("No tienes permiso para editar esta entrada.");
        }

        $categoriaModel = new Categoria($this->pdo);
        $categorias = $categoriaModel->all();

        $titulo = "Editar entrada";
        $modo = "editar";
        $errores = [];

        require __DIR__ . '/../Views/entradas/form.php';
    }

    public function actualizar(): void
    {
        Auth::requireLogin();

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) die("ID inválido.");

        $entradaModel = new Entrada($this->pdo);
        $entradaActual = $entradaModel->find($id);
        if (!$entradaActual) die("Entrada no encontrada.");

        // Permisos: solo autor o admin
        $user = Auth::user();
        if (!Auth::isAdmin() && (int)$entradaActual['usuario_id'] !== (int)$user['id']) {
            die("No tienes permiso para modificar esta entrada.");
        }

        $categoriaId = (int)($_POST['categoria_id'] ?? 0);
        $tituloPost  = trim($_POST['titulo'] ?? '');

        // CKEditor: validamos texto plano
        $descripcionHtml  = $_POST['descripcion'] ?? '';
        $descripcionTexto = trim(strip_tags($descripcionHtml));

        $errores = [];
        if ($categoriaId <= 0) $errores[] = "Debes seleccionar una categoría.";
        if ($tituloPost === '') $errores[] = "El título es obligatorio.";
        if ($descripcionTexto === '') $errores[] = "La descripción es obligatoria.";

        $nombreImagen = $entradaActual['imagen']; // mantenemos si no sube nueva
        if (!empty($_FILES['imagen']['name'])) {
            $nuevo = $this->subirImagen($_FILES['imagen'], $errores);
            if ($nuevo !== null) {
                $nombreImagen = $nuevo;
            }
        }

        if (!empty($errores)) {
            $categoriaModel = new Categoria($this->pdo);
            $categorias = $categoriaModel->all();

            $titulo = "Editar entrada";
            $modo = "editar";
            $entrada = [
                'id' => $id,
                'categoria_id' => $categoriaId,
                'titulo' => $tituloPost,
                'imagen' => $nombreImagen,
                'descripcion' => $descripcionHtml
            ];

            require __DIR__ . '/../Views/entradas/form.php';
            return;
        }

        $ok = $entradaModel->actualizar($id, [
            'categoria_id' => $categoriaId,
            'titulo' => $tituloPost,
            'imagen' => $nombreImagen,
            'descripcion' => $descripcionHtml
        ]);

        if (!$ok) {
            $msg = method_exists($entradaModel, 'getLastError') ? $entradaModel->getLastError() : 'Error desconocido';
            die("Error al actualizar en BD: " . $msg);
        }

        header('Location: index.php?controller=entrada&action=listar');
        exit;
    }

    public function eliminar(): void
    {
        Auth::requireLogin();

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) die("ID inválido.");

        $entradaModel = new Entrada($this->pdo);
        $entrada = $entradaModel->find($id);
        if (!$entrada) die("Entrada no encontrada.");

        // Permisos: solo autor o admin
        $user = Auth::user();
        if (!Auth::isAdmin() && (int)$entrada['usuario_id'] !== (int)$user['id']) {
            die("No tienes permiso para eliminar esta entrada.");
        }

        // borrar fichero si existe
        if (!empty($entrada['imagen'])) {
            $ruta = __DIR__ . '/../../public/uploads/' . $entrada['imagen'];
            if (is_file($ruta)) {
                @unlink($ruta);
            }
        }

        $ok = $entradaModel->eliminar($id);
        if (!$ok) {
            $msg = method_exists($entradaModel, 'getLastError') ? $entradaModel->getLastError() : 'Error desconocido';
            die("Error al eliminar en BD: " . $msg);
        }

        header('Location: index.php?controller=entrada&action=listar');
        exit;
    }

    public function detalle(): void
    {
        Auth::requireLogin();

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) die("ID inválido.");

        $entradaModel = new Entrada($this->pdo);
        $entrada = $entradaModel->findDetalle($id);

        if (!$entrada) {
            die("Entrada no encontrada.");
        }

        $titulo = "Detalle de la entrada";
        require __DIR__ . '/../Views/entradas/detalle.php';
    }

    private function subirImagen(array $file, array &$errores): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errores[] = "Error al subir la imagen.";
            return null;
        }

        $tmp = $file['tmp_name'];
        $original = $file['name'];

        $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (!in_array($ext, $permitidas, true)) {
            $errores[] = "Formato de imagen no permitido. Usa JPG, PNG, WEBP o GIF.";
            return null;
        }

        $nombre = uniqid('img_', true) . '.' . $ext;
        $destino = __DIR__ . '/../../public/uploads/' . $nombre;

        if (!is_dir(dirname($destino))) {
            @mkdir(dirname($destino), 0777, true);
        }

        if (!move_uploaded_file($tmp, $destino)) {
            $errores[] = "No se pudo guardar la imagen subida.";
            return null;
        }

        return $nombre;
    }
}
