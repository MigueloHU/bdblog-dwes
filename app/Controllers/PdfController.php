<?php
namespace App\Controllers;

use PDO;
use App\Config\Auth;
use App\Models\Entrada;

class PdfController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        Auth::start();
    }

    public function entradas(): void
    {
        Auth::requireLogin();

        // Cargar FPDF
        require_once __DIR__ . '/../../lib/fpdf.php';

        $orden = $_GET['orden'] ?? 'fecha';
        $dir   = $_GET['dir'] ?? 'desc';
        $q     = trim($_GET['q'] ?? '');

        $entradaModel = new Entrada($this->pdo);

        // Para PDF: sacamos todo el listado filtrado/ordenado (sin paginación)
        if ($q !== '') {
            $entradas = $entradaModel->buscar($q, $orden, $dir);
        } else {
            $entradas = $entradaModel->listarTodasOrdenado($orden, $dir);
        }

        // Generar PDF
        $pdf = new \FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', 'Listado de Entradas - Mi pequeño Blog'), 0, 1, 'C');

        $pdf->SetFont('Arial', '', 10);
        $sub = 'Orden: ' . $orden . ' (' . $dir . ')';
        if ($q !== '') $sub .= ' | Búsqueda: ' . $q;
        $pdf->Cell(0, 7, iconv('UTF-8', 'ISO-8859-1', $sub), 0, 1, 'C');

        $pdf->Ln(4);

        // Cabecera tabla
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(90, 8, iconv('UTF-8', 'ISO-8859-1', 'Título'), 1);
        $pdf->Cell(35, 8, iconv('UTF-8', 'ISO-8859-1', 'Categoría'), 1);
        $pdf->Cell(30, 8, iconv('UTF-8', 'ISO-8859-1', 'Autor'), 1);
        $pdf->Cell(35, 8, iconv('UTF-8', 'ISO-8859-1', 'Fecha'), 1);
        $pdf->Ln();

        // Filas
        $pdf->SetFont('Arial', '', 9);
        foreach ($entradas as $e) {
            $titulo = $this->limitar($e['titulo'] ?? '', 45);
            $cat    = $this->limitar($e['categoria_nombre'] ?? '', 18);
            $autor  = $this->limitar($e['autor_nick'] ?? '', 12);
            $fecha  = $this->limitar($e['fecha'] ?? '', 16);

            $pdf->Cell(90, 7, iconv('UTF-8', 'ISO-8859-1', $titulo), 1);
            $pdf->Cell(35, 7, iconv('UTF-8', 'ISO-8859-1', $cat), 1);
            $pdf->Cell(30, 7, iconv('UTF-8', 'ISO-8859-1', $autor), 1);
            $pdf->Cell(35, 7, iconv('UTF-8', 'ISO-8859-1', $fecha), 1);
            $pdf->Ln();

            // Salto de página si hace falta
            if ($pdf->GetY() > 270) {
                $pdf->AddPage();
            }
        }

        $nombre = 'listado_entradas.pdf';
        $pdf->Output('I', $nombre); // I = inline en navegador
        exit;
    }

    private function limitar(string $txt, int $max): string
    {
        $txt = trim($txt);
        if (mb_strlen($txt) <= $max) return $txt;
        return mb_substr($txt, 0, $max - 3) . '...';
    }
}
