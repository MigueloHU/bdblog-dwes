<?php
namespace App\Controllers;

use PDO;
use App\Config\Auth;
use App\Models\Log;

class LogController
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

        // Recomendado: solo admin gestiona logs
        if (!Auth::isAdmin()) {
            die("Acceso restringido.");
        }

        $logs = (new Log($this->pdo))->listar();
        $titulo = "Listado de logs";
        require __DIR__ . '/../Views/logs/listar.php';
    }

    public function eliminar(): void
    {
        Auth::requireLogin();

        if (!Auth::isAdmin()) {
            die("Acceso restringido.");
        }

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) die("ID inválido.");

        (new Log($this->pdo))->eliminar($id);

        header('Location: index.php?controller=log&action=listar');
        exit;
    }

    public function pdf(): void
    {
        Auth::requireLogin();

        if (!Auth::isAdmin()) {
            die("Acceso restringido.");
        }

        require_once __DIR__ . '/../../lib/fpdf.php';

        $logs = (new Log($this->pdo))->listar();

        $pdf = new \FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', 'Listado de Logs - Mi pequeño Blog'), 0, 1, 'C');
        $pdf->Ln(4);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(15, 8, 'ID', 1);
        $pdf->Cell(45, 8, iconv('UTF-8', 'ISO-8859-1', 'Fecha/Hora'), 1);
        $pdf->Cell(40, 8, iconv('UTF-8', 'ISO-8859-1', 'Usuario'), 1);
        $pdf->Cell(90, 8, iconv('UTF-8', 'ISO-8859-1', 'Operación'), 1);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 9);
        foreach ($logs as $l) {
            $pdf->Cell(15, 7, (string)$l['id'], 1);
            $pdf->Cell(45, 7, iconv('UTF-8', 'ISO-8859-1', $l['fecha_hora']), 1);
            $pdf->Cell(40, 7, iconv('UTF-8', 'ISO-8859-1', $l['usuario']), 1);

            $op = $this->limitar($l['operacion'], 45);
            $pdf->Cell(90, 7, iconv('UTF-8', 'ISO-8859-1', $op), 1);
            $pdf->Ln();

            if ($pdf->GetY() > 270) {
                $pdf->AddPage();
            }
        }

        $pdf->Output('I', 'logs.pdf');
        exit;
    }

    private function limitar(string $txt, int $max): string
    {
        $txt = trim($txt);
        if (mb_strlen($txt) <= $max) return $txt;
        return mb_substr($txt, 0, $max - 3) . '...';
    }
}
