<?php

/*Importar la libreria de generación de PDFs*/
require_once('tcpdf/tcpdf.php');
require_once("../funciones/funciones.php");

$con = Conexion();


/*Bloque que siempre va igual*/
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('GAAG');
$pdf->SetTitle('Prueba de daniel 01');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetAutoPageBreak(TRUE, 0);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

/*Agrega una neva pagina al PDF*/
$pdf->AddPage('P', 'A4');

/*Configuracion del estilo de letra*/
$pdf->SetFont('courier', 'I', 10);

$acumuladorY=10;

for($i=0;$i<10;$i++){
    /*Ajuste del puntero*/
    $pdf->SetXY(10,$acumuladorY);
    /*Impresion de celda   ancho, alto, texto, borde=1 sin borde=0*/
    $pdf->Cell(40, 4, "Informe Empresa", 0, 1, "C", 0, '', 0);
    $acumuladorY+=5;
}


/*$buscaempresa = $_GET["empresa"];*/


$sqlselect="select * from empresa order by nombre";
$resultselect=mysql_query($sqlselect,$con) or die(mysql_error());

                                        
if(mysql_num_rows($resultselect)>0){
    while ($fila = mysql_fetch_assoc($resultselect)) {
        $pdf->SetXY(10,$acumuladorY);
        $pdf->Cell(40, 4,$fila["nombre"], 0, 1, "L", 0, '', 0);
        $acumuladorY+=5;
    }
}

$acumuladorY+=10;
$pdf->Line(10, $acumuladorY, 200, $acumuladorY);

$pdf->Image('logo300px.png', 100, 10, 30, 12.5, 'PNG', 'http://www.gaagdesarrolloempresarial.com', '', true, 150, '', false, false, 0, false, false, false);

/*Genera el PDF*/
$pdf->Output('Listado Empresas.pdf', 'I');


?>