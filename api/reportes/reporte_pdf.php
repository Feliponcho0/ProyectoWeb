<?php 
require_once '../../lib/fpdf/fpdf.php';
require_once '../../validations/conection.php';
require_once '../../validations/check.php';


$tiendas_id = $_SESSION['tiendas_id'] ?? 0;
$fecha_inicio = $_GET['fecha_inicio'] ?? '';
$fecha_fin = $_GET['fecha_fin'] ?? '';

//obtener nombre de la tienda
$queryTienda = "SELECT nombre_tienda FROM tiendas WHERE tiendas_id = ?";
$rowsTienda = $conn->prepare($queryTienda);
$rowsTienda->bind_param("i", $tiendas_id);
$rowsTienda->execute();
$resultadoTienda = $rowsTienda->get_result();
$tienda = $resultadoTienda->fetch_assoc();
$nombre_tienda = $tienda['nombre_tienda'] ?? '';

//obtener ventas
$queryVentas = "SELECT v.numero_ticket, v.total, v.fecha FROM ventas v
WHERE v.tiendas_id = ? AND DATE(v.fecha) BETWEEN ? AND ? ORDER BY v.fecha ASC";
$rowsVentas = $conn->prepare($queryVentas);
$rowsVentas->bind_param("iss", $tiendas_id, $fecha_inicio, $fecha_fin);
$rowsVentas->execute();
$resultadoVentas = $rowsVentas->get_result();

$ventas = [];
$total = 0;
//recorrer ventas
while($row = $resultadoVentas->fetch_assoc()){
    $ventas[] = $row;
    $total = $total + $row['total'];
}

$pdf= new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);

// Titulo del reporte
$pdf->Cell(0,10,'Reporte de Ventas',0,1,'C');
$pdf->SetFont('Arial','',12);// fuente normal
$pdf->Cell(0, 8, 'Tienda: ' . $nombre_tienda, 0, 1, 'C');//ancho, alto, texto, borde, salto de linea, alineacion
$pdf->Cell(0, 8, 'Periodo: '. $fecha_inicio .' al '.$fecha_fin, 0, 1, 'C');
$pdf->Ln(5);

//encabezado
$pdf->SetFont('Arial','B',11);
$pdf->SetFillColor(1, 46, 70);//color de relleno
$pdf->SetTextColor(255, 255, 255);//color de texto
$pdf->Cell(60, 8, 'Ticket', 1, 0, 'C', true);//el true asigna el color del relleno
$pdf->Cell(80, 8, 'Fecha', 1, 0, 'C', true);
$pdf->Cell(50, 8, 'Total', 1, 1, 'C', true);

//filas
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(0, 0, 0);
$filas=false;

foreach ($ventas as $v) {
    $pdf->SetFillColor(220, 230, 241);
    $pdf->Cell(60, 8, $v['numero_ticket'], 1, 0, 'C', $filas);
    $pdf->Cell(80, 8, $v['fecha'], 1, 0, 'C', $filas);
    $pdf->Cell(50, 8, '$' . number_format($v['total'], 2), 1, 1, 'C', $filas);
    $filas = !$filas;//cambian el color de fondo
}

$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(140, 8, 'Total General:', 0, 0, 'R');
$pdf->Cell(50, 8, '$' . number_format($total, 2), 1, 1, 'C');

$pdf->Output('I', 'reporte_ventas.pdf');
?>