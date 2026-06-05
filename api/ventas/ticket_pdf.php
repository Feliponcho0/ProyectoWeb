<?php
require_once '../../lib/fpdf/fpdf.php';
require_once '../../validations/conection.php';


$id=$_GET['id'] ?? 0;

try{
    //obtener venta
    $queryVenta= "SELECT * FROM ventas WHERE ventas_id = ?";
    $rowsVenta = $conn->prepare($queryVenta);
    $rowsVenta->bind_param("i", $id);
    $rowsVenta->execute();

    $resultadoVenta = $rowsVenta->get_result();
    $venta = $resultadoVenta->fetch_assoc();

    if (!$venta){
        throw new Exception("Venta no encontrada");
    }

    //obtener tienda
    $queryTienda= "SELECT * FROM tiendas WHERE tiendas_id = ?";
    $rowsTienda = $conn->prepare($queryTienda);
    $rowsTienda->bind_param("i", $venta['tiendas_id']);
    $rowsTienda->execute();

    $resultadoTienda = $rowsTienda->get_result();
    $tienda = $resultadoTienda->fetch_assoc();

    //obtener detalle
    $queryDetalle= "SELECT dv.*, p.nombre_producto FROM detalle_venta dv INNER JOIN
    productos p ON dv.producto_id = p.producto_id WHERE dv.ventas_id = ?";
    $rowsDetalle = $conn->prepare($queryDetalle);
    $rowsDetalle->bind_param("i", $id);
    $rowsDetalle->execute();

    $resultadoDetalle = $rowsDetalle->get_result();

    //pdf
    $pdf = new FPDF('P', 'mm', array(80,200));// p vertical, mm milimetros, tamaño de la hoja
    $pdf->AddPage();
    $pdf->setMargins(5,5,5);

    //nombre de tienda
    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(60,5,$tienda['nombre_tienda'],0,1,'C');// ancho, alto, texto, borde, salto de linea, alineacion

    
    //titulo
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(70,5,'TICKET DE VENTA',0,1,'C');

    //datos de la venta
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(50,5,'Ticket: '.$venta['numero_ticket'],0,1,'L');
    $pdf->Cell(50,5,'Fecha: '.$venta['fecha'],0,1,'L');
    $pdf->Ln(4);

    $pdf->Cell(70,2,'----------------------------------------------------------',0,1,'L');
    //encabezados
    $pdf->Cell(25,4,'Producto',0,0,'L');
    $pdf->Cell(10,4,'Cant',0,0,'L');
    $pdf->Cell(15,4,'Precio',0,0,'L');
    $pdf->Cell(20,4,'Subtotal',0,1,'L');
    $pdf->Cell(70,4,'----------------------------------------------------------',0,1,'L');

    //detalle de la venta
    $pdf->SetFont('Arial','',10);

    while($row = $resultadoDetalle->fetch_assoc()){
        $xInicial = $pdf->GetX();//posicion inicial
        $yInicial = $pdf->GetY();
        $pdf->MultiCell(25,4,$row['nombre_producto'],0,'L');
        $yFinal = $pdf->GetY();//posicion final
        $altura = $yFinal - $yInicial;//altura
        $pdf->SetXY($xInicial + 25, $yInicial);
        $pdf->Cell(10,4,$row['cantidad'],0,0,'L');
        $pdf->Cell(15,4,$row['precio_unitario'],0,0,'L');
        $pdf->Cell(20,4,$row['subtotal'],0,1,'L');
        $pdf->SetY($yFinal);
    }


    //total
    $pdf->Ln();
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(70,5,'TOTAL: $'.$venta['total'],0,1,'R');
    $pdf->Ln();


    //titulo final
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(70,5,'GRACIAS POR SU COMPRA',0,1,'C');
    $pdf->Output('I', 'ticket_'.$venta['numero_ticket'].'.pdf');
    exit;

}catch(Exception $e){
    echo json_encode(['ok' => false, 'msg' => 'Error al consultar la venta']);
    exit;
}

?>