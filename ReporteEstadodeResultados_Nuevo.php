<?php
require_once('recursos/tcpdf/tcpdf.php');
require_once("funciones/funciones.php");


/*Captura de Parametros*/
$anno = $_GET["anno"];
$mes  = $_GET["mes"];
$idestadofinanciero = 2;

/*Consultas de Base de Datos*/
$con = Conexion();
$sqlselect="select * from empresa where idempresa = ". $_GET["empresa"];
$resultselect=mysql_query($sqlselect,$con) or die(mysql_error());

/*Datos de la empresa*/
$fila = mysql_fetch_assoc($resultselect);
$idempresa = $fila["idempresa"];
$nombre = $fila["nombre"];
$logo = $fila["logo"];
$idejercicio =  fncbuscaridejercicio($idempresa, $anno);

//Bloque que siempre va igual
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('GAAG');
$pdf->SetTitle('Resumen del Estado de Resultados');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetAutoPageBreak(TRUE, 0);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//Agrega una neva pagina al PDF
$pdf->AddPage('P', 'A4');

$pdf->Image('recursos/logo300px.jpg', 65, 100, 75, 31, 'JPG', 'http://www.gaagdesarrolloempresarial.com', '', true, 150, '', false, false, 0, false, false, false);
$pdf->Line(30, 140, 180, 140);
$pdf->SetFont('Helvetica', '', 16);
$pdf->Text(30, 143, 'Resumen del Estado de Resultados');
$pdf->Line(30, 153, 180, 153);

$pdf->SetFont('Helvetica', '', 8);    
$pdf->SetTextColor(126,130,109);
$pdf->Text(30, 160, 'Preparado por');    
$pdf->SetFont('Helvetica', '', 12);        
$pdf->SetTextColor(0,0,0);
$pdf->Text(30, 165, 'GAAG Desarrollo Empresarial');

$pdf->SetFont('Helvetica', '', 8);    
$pdf->SetTextColor(126,130,109);
$pdf->Text(30, 174, 'Para');    
$pdf->SetFont('Helvetica', '', 12);        
$pdf->SetTextColor(0,0,0);
$pdf->Text(30, 179,$nombre);    

$pdf->SetFont('Helvetica', '', 8);
$pdf->SetTextColor(126,130,109);    
$pdf->Text(30, 189, 'Periodo en Revisión');    
$pdf->SetFont('Helvetica', '', 12);
$pdf->SetTextColor(0,0,0);    
$pdf->Text(30, 194,retornames($_GET["mes"]).' '.$_GET["anno"]);

$pdf->SetFont('Helvetica', '', 8);
$pdf->SetTextColor(126,130,109);    
$pdf->Text(30, 203, 'Creado el');     
$pdf->SetFont('Helvetica', '', 9);
$pdf->SetTextColor(0,0,0);  
$diadate=date("d");
$mesdate=date("m");
$anodate=date("Y");
$pdf->Text(30, 208,$diadate." de ". retornames(intval($mesdate))." de ".$anodate);

$pdf->AddPage('P', 'A4');   
$pdf->Image('recursos/logo300px.jpg', 10, 10, 30, 12.8, 'JPG', 'http://www.gaagdesarrolloempresarial.com', '', true, 150, '', false, false, 0, false, false, false);
$extension=explode(".",$logo);
$pdf->Image('logos/'.$logo, 170, 10, 30, 12.8,$extension[1], '', '', true, 150, '', false, false, 0, false, false, false);
$pdf->Line(10, 25, 200, 25);
$pdf->SetFont('Helvetica', '', 10);
$pdf->Text(9, 26, 'Estado de Resultados '.$nombre." ".retornames($_GET["mes"]).' '.$_GET["anno"]);  
$pdf->SetFont('Helvetica', '', 7);


$maxestructura = fncmaxestructestadofinan($idestadofinanciero);
$minestructura = fncminestructestadofinan($idestadofinanciero);
$dif = $maxestructura-$minestructura;

$Y=40;
$X=10;

$pdf->SetXY($X+80,$Y-5);                            
$pdf->Cell(30, 4,"Periodo", 0, 1, "R", 0, '', 0); 
$pdf->SetXY($X+105,$Y-5);                            
$pdf->Cell(30, 4,"% Intregrales", 0, 1, "R", 0, '', 0); 
$pdf->SetXY($X+130,$Y-5);                            
$pdf->Cell(30, 4,"Acumulado", 0, 1, "R", 0, '', 0); 
$pdf->SetXY($X+155,$Y-5);                            
$pdf->Cell(30, 4,"% Integrales", 0, 1, "R", 0, '', 0);


for ($idestructura=$minestructura;$idestructura<=$maxestructura;$idestructura++){
    $nombreestructura = fncnombreestructura($idestructura,$idestadofinanciero);
    $Y+=5;
    crearEstructura($pdf,$idempresa,$idestructura);
    $Y+=5;
    crearTotales($pdf,$idestructura-$dif+1,$X,$Y);
}   
$totalparcialxagrupacion=0;
$totalsaldoxagrupacio=0;
$restaxagrupacion=0;
$restahaberxagrupacion=0;
$sumaxagrupacioncuenta=0;
$sumahaberxagrupacioncuenta=0;
function crearEstructura($pdf,$idempresa,$idestructura)
{
    global $X;
    global $Y;
    global $con;
    global $restaxagrupacion;
    global $restahaberxagrupacion;
    global $sumaxagrupacioncuenta;
    global $sumahaberxagrupacioncuenta;
    global $totalsaldoxagrupacion;
    global $totalparcialxagrupacion;
    $IdTipoAgrupacionEst = fncIdTipoAgrupacionEst($idestructura);
    $minidtipoagrupacionest = fncminestructaasociacion($IdTipoAgrupacionEst);
    $maxidtipoagrupacionest = fncmaxestructaasociacion($IdTipoAgrupacionEst);
    for ($idagrupacionest=$minidtipoagrupacionest; $idagrupacionest<=$maxidtipoagrupacionest; $idagrupacionest++ ){
        $sumaxagrupacioncuenta=0;
        $sumahaberxagrupacioncuenta=0;
        $sqlselectagrup="select * from agrupacioncuentasest where idempresa= " . $idempresa . " and idagrupacionest = " . $idagrupacionest;                         
        $resultselectagrup=mysql_query($sqlselectagrup,$con) or die(mysql_error());
        $filaagrup = mysql_fetch_assoc($resultselectagrup);                            
        if(mysql_num_rows($resultselectagrup)>0){
            do{
                $parcialcuenta=0;    
                $codigocuenta   = $filaagrup["codigocuenta"];
                $tipoa=$filaagrup["tipoa"];
                $tipop=$filaagrup["tipop"];
                $saldocuenta    = fncbuscasaldocta($idempresa,fncbuscaridcuenta($idempresa,$codigocuenta),fncbuscaridejercicio($idempresa, $_GET["anno"]),$_GET["mes"],$tipoa) * $filaagrup["signo"];
                $parcialcuenta  = fncbuscasaldocta($idempresa,fncbuscaridcuenta($idempresa,$codigocuenta),fncbuscaridejercicio($idempresa, $_GET["anno"]),$_GET["mes"],$tipop) * $filaagrup["signo"];
                if ($restaxagrupacion==0){
                    $totalsaldoxagrupacion = $saldocuenta;
                    $totalparcialxagrupacion = $parcialcuenta;
                };
                if ($restaxagrupacion==0){$restaxagrupacion=$saldocuenta;}
                else{$restaxagrupacion=$restaxagrupacion-$saldocuenta;}    
                if ($sumaxagrupacioncuenta==0){$sumaxagrupacioncuenta=$saldocuenta;}
                else{$sumaxagrupacioncuenta=$sumaxagrupacioncuenta-$saldocuenta;}             
                if ($sumahaberxagrupacioncuenta==0){$sumahaberxagrupacioncuenta=$parcialcuenta;}
                else{$sumahaberxagrupacioncuenta=$sumahaberxagrupacioncuenta-$parcialcuenta;}                
                if ($restahaberxagrupacion==0){$restahaberxagrupacion=$parcialcuenta;}
                else{$restahaberxagrupacion=$restahaberxagrupacion-$parcialcuenta;}    
            } while ($filaagrup = mysql_fetch_assoc($resultselectagrup));
        }
        $Y+=5;
        $pdf->SetXY($X,$Y);
        $pdf->Cell(30, 4,fncnombreagrupacionest($idagrupacionest) , 0, 1, "L", 0, '', 0);
        $pdf->SetXY($X+130,$Y);                            
        $pdf->Cell(30, 4,number_format($sumaxagrupacioncuenta,2), 0, 1, "R", 0, '', 0);
        $pdf->SetXY($X+80,$Y);                            
        $pdf->Cell(30, 4,number_format($sumahaberxagrupacioncuenta,2), 0, 1, "R", 0, '', 0);

        $pdf->SetXY($X+105,$Y);                            
        $pdf->Cell(30, 4,number_format( ($sumahaberxagrupacioncuenta*100)/$totalparcialxagrupacion,2). " %", 0, 1, "R", 0, '', 0);

        $pdf->SetXY($X+155,$Y);                            
        $pdf->Cell(30, 4,number_format(($sumaxagrupacioncuenta*100)/$totalsaldoxagrupacion,2). " %", 0, 1, "R", 0, '', 0);
        
        
    }
}

function crearTotales($pdf,$idtipoagrupacionest,$X,$Y){
    global $totalsaldoxagrupacion;
    global $totalparcialxagrupacion;
    global $restaxagrupacion;
    global $restahaberxagrupacion;
    $pdf->SetFont('Helvetica', 'B', 8);
    $pdf->Line($X+100, $Y-1, $X+190 , $Y-1);
    $pdf->SetXY($X+5,$Y);
    $pdf->Cell(30, 4,fncNombreTipoAgrupacionEst($idtipoagrupacionest) , 0, 1, "L", 0, '', 0);
    $pdf->SetXY($X+130,$Y);                            
    $pdf->Cell(30, 4,number_format($restaxagrupacion,2), 0, 1, "R", 0, '', 0);
    $pdf->SetXY($X+80,$Y);                            
    $pdf->Cell(30, 4,number_format($restahaberxagrupacion,2), 0, 1, "R", 0, '', 0);
    $pdf->SetXY($X+105,$Y);                            
    $pdf->Cell(30, 4,number_format( ($restahaberxagrupacion*100)/$totalparcialxagrupacion,2). " %", 0, 1, "R", 0, '', 0);
    $pdf->SetXY($X+155,$Y);                            
    $pdf->Cell(30, 4,number_format(($restaxagrupacion*100)/$totalsaldoxagrupacion,2)." %", 0, 1, "R", 0, '', 0);
    $pdf->SetFont('Helvetica', '', 7);
}
$pdf->Output('Listado Empresas.pdf', 'I');
?>