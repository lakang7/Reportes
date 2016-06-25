<?php

require_once('recursos/tcpdf/tcpdf.php');
require_once("funciones/funciones.php");


/*Captura de Parametros*/
$anno = $_GET["anno"];
$mes  = $_GET["mes"];
$idestadofinanciero = 1;

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
$pdf->SetTitle('Resumen del Balance General');
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
$pdf->Text(30, 143, 'Resumen de Balance General');
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
$pdf->Text(9, 26, 'Balance General '.$nombre." ".retornames($_GET["mes"]).' '.$_GET["anno"]);  
$pdf->SetFont('Helvetica', '', 7);
$X=10;
$Y=50;
$vectorSaldo[]=0;
$vectorY[]=0;
$vectorX[]=0;

$vectorTotalX[]=0;
$vectorTotalY[]=0;
$vectorTotal[]=0;

$sumatotalagrupacion=0;
$sumapasivocapital=0;
$sumacapital=0;
$maxestructura = fncmaxestructestadofinan($idestadofinanciero);

crearEncabezadoPorcentajes($pdf);


for ($ciclo=1;$ciclo<=$maxestructura;$ciclo++){
    $sumatotalagrupacion=0;
    $contador=0;
    $nombreestructura = fncnombreestructura($ciclo,$idestadofinanciero);
    if ($ciclo==2){ $X=110; $Y=50; };
    crearEncabezado($pdf, $nombreestructura,$X,$Y);
    $Y=crearEstructura($pdf,$idempresa,$ciclo,$X,$Y);
    if ($ciclo==1){$sumacapital=$sumatotalagrupacion;} else {$sumapasivocapital+=$sumatotalagrupacion;} 
    totalesxcuerpo($pdf, $X+5, $Y+30,$ciclo, $sumatotalagrupacion,$sumacapital,$sumapasivocapital, $nombreestructura);
    $Y+=+25;
}    

function crearEstructura($pdf,$idempresa,$idestructura,$X,$Y){
    $con = Conexion();
    global $vectorTotalX;           
    global $vectorTotalY;
    global $vectorTotal;
    global $vectorY;           
    global $vectorX;
    global $vectorSaldo;
    global $sumatotalagrupacion;
    $sumatotal =0;
    $maxestructuraagrupacion=fncmaxestructuraagrupacion($idestructura);
    $minestructuraagrupacion=fncminestructuraagrupacion($idestructura);
    global $contador;
    for ($idtipoagrupacion=$minestructuraagrupacion;$idtipoagrupacion<=$maxestructuraagrupacion;$idtipoagrupacion++){
        $i = 0;
        $sumatotal =0;
        $suma=0;        
        $Y+=10;       
        $pdf->SetFont('Helvetica', 'I', 7);
        $pdf->SetXY($X,$Y);
        $pdf->Cell(40, 4,fncnombresubagrupacion ($idtipoagrupacion), 0, 1, "L", 0, '', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $sqlselect="select * from enasociacion where idempresa= " . $idempresa . " and idtipoagrupacion = " . $idtipoagrupacion . " order by posicion";                         
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        if(mysql_num_rows($resultselect)>0){
            do{
                $sumaxagrupacion=0;
                $tipoelemento = $fila["tipoelemento"];
                $idagrupacion = $fila["idagrupacion"];
                if ($tipoelemento == "a"){
                    $sqlselectagrup="select * from agrupacioncuentas where idempresa= " . $idempresa . " and idagrupacion = " . $idagrupacion;                         
                    $resultselectagrup=mysql_query($sqlselectagrup,$con) or die(mysql_error());
                    $filaagrup = mysql_fetch_assoc($resultselectagrup);                            
                    if(mysql_num_rows($resultselectagrup)>0){
                        do{                            
                            $codigocuenta = $filaagrup["codigocuenta"];
                            $saldocuenta = 0;
                            $saldocuenta = fncbuscasaldocta($idempresa,fncbuscaridcuenta($idempresa,$codigocuenta),fncbuscaridejercicio($idempresa, $_GET["anno"]),$_GET["mes"],1) * $filaagrup["signo"];
                            if ($saldocuenta<>0) {
                                $sumaxagrupacion+=$saldocuenta;
                                $suma+=$saldocuenta;
                                $sumatotalagrupacion+=$saldocuenta;
                            }
                        } while ($filaagrup = mysql_fetch_assoc($resultselectagrup));
                        if ($sumaxagrupacion>0){
                            $Y+=5;
                            $pdf->SetXY($X+5,$Y);
                            $pdf->Cell(30, 4,fncnombreagrupacion($idagrupacion) , 0, 1, "L", 0, '', 0);
                            $pdf->SetXY($X+35,$Y);                            
                            $pdf->Cell(30, 4,number_format($sumaxagrupacion,2), 0, 1, "R", 0, '', 0);
                            $i+=1;
                            $vectorSaldo[$i] = $sumaxagrupacion;
                            $vectorY[$i]     = $Y;
                            $vectorX[$i]    = $X+55;
                            $pdf->SetXY(120,$i*5);
                        }
                    }                          
                }elseif (($tipoelemento == "c")){
                    $codigocuenta = $fila["codigocuenta"];
                    $saldocuenta = fncbuscasaldocta($idempresa,fncbuscaridcuenta($idempresa,$codigocuenta),fncbuscaridejercicio($idempresa, $_GET["anno"]),$_GET["mes"],1) * $fila["signo"];
                        if ($saldocuenta<>0) {
                        $Y+=5;
                        $i+=1;
                        $sumatotalagrupacion+=$saldocuenta;
                        $vectorSaldo[$i] = $saldocuenta;
                        $vectorX[$i] = $X+55;
                        $vectorY[$i] = $Y;
                        $pdf->SetXY($X+5,$Y);
                        $pdf->Cell(30, 4,fncnombrecuenta(fncbuscaridcuenta($idempresa,$codigocuenta)) , 0, 1, "L", 0, '', 0);
                        $pdf->SetXY($X+35,$Y);                            
                        $pdf->Cell(30, 4,number_format($saldocuenta,2), 0, 1, "R", 0, '', 0);
                        $suma+=$saldocuenta;
                    }
                }                
            } while ($fila = mysql_fetch_assoc($resultselect));
        }
        $Y+=8;
        $pdf->Line($X+45, $Y, $X+90, $Y);
        $Y+=2;
        $pdf->SetFont('Helvetica', 'I', 7);
        $pdf->SetXY($X,$Y);
        $pdf->Cell(30, 4, "Total " . fncnombresubagrupacion  ($idtipoagrupacion), 0, 1, "L", 0, '', 0);
        $pdf->SetXY($X+35,$Y);
        $pdf->Cell(30, 4, number_format($suma,2), 0, 1, "R", 0, '', 0);
        $pdf->SetFont('Helvetica', '', 7);    
        
        $contador++;              
        $vectorTotalX[$contador]=$X;
        $vectorTotalY[$contador]=$Y;
        $vectorTotal[$contador]=$suma;

        for($j=1;$j<=$i;$j++){
            $sumatotal+=$vectorSaldo[$j];
        }       
        for($r=1;$r<=$i;$r++){
            $pdf->SetXY($vectorX[$r]+20,$vectorY[$r]-18);
            $pdf->Cell(3,40,number_format(($vectorSaldo[$r]/$sumatotal)*100,2). " %" , 0, 1, "R", 0, '', 0);
            if ($r==$i){
              $pdf->SetFont('Helvetica', 'I', 7);  
              $pdf->SetXY($vectorX[$r]-4,$vectorY[$r]+10);
              $pdf->Cell(30, 4, "100.00 %" , 0, 1, "R", 0, '', 0);
              $pdf->SetFont('Helvetica', '', 7);
            }
        }
    }
    return $Y;
}


function crearEncabezado($pdf, $nombreestructura,$X,$Y){
    $pdf->SetFont('Helvetica', 'B', 8);
    $pdf->SetXY($X,$Y);
    $pdf->Cell(40, 4,strtoupper($nombreestructura), 0, 1, "L", 0, '', 0);
    $pdf->SetFont('Helvetica', '', 7);
}

function totalesxcuerpo($pdf,$X,$Y,$ciclo,$sumatotalagrupacion,$sumacapital,$sumapasivocapital,$nombreestructura){
    global $maxestructura;
    global $contador;
    global $vectorTotal;
    global $vectorTotalX;
    global $vectorTotalY;

    for($r=1;$r<=$contador;$r++){
        $pdf->SetFont('Helvetica', 'I', 7);
        $pdf->SetXY($vectorTotalX[$r]+52,$vectorTotalY[$r]);
        $pdf->Cell(40,4,number_format( ($vectorTotal[$r]/ $sumatotalagrupacion)*100,2) . " %", 0, 1, "R", 0, '', 0);
        $pdf->SetFont('Helvetica', '', 7);
    }


    if ($ciclo>1){
        $pdf->SetXY($X-5,$Y-18);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Line($X+40, $Y-18, $X+85, $Y-18);
        $pdf->Cell(40,4, strtoupper("Total ". $nombreestructura),0,1,"L",0,'',0); 
        $pdf->SetXY($X+20,$Y-18);
        $pdf->Cell(40,4,number_format($sumatotalagrupacion,2), 0, 1, "R", 0, '', 0);
        $pdf->SetXY($X+75,$Y-18);
        $pdf->Cell(15, 3,"100 %", 0, 1, 'C', 0, '', 0);
    }
    if ($ciclo==$maxestructura) {
        $pdf->Line($X-60, $Y-2, $X-15 , $Y-2);
        $pdf->Line($X-60, $Y+6, $X-15 , $Y+6);
        $pdf->SetXY($X-105,$Y);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(40,4, strtoupper("Total Activo"),0,1,"L",0,'',0); 
        $pdf->SetXY($X-80,$Y);
        $pdf->Cell(40,4,number_format($sumacapital,2), 0, 1, "R", 0, '', 0);
        $pdf->SetXY($X-25,$Y);
        $pdf->Cell(15, 3,"100 %", 0, 1, 'C', 0, '', 0);

        
        $pdf->Line($X+40, $Y-2, $X+85 , $Y-2);
        $pdf->Line($X+40, $Y+6, $X+85 , $Y+6);
        $pdf->SetXY($X-5,$Y);
        $pdf->Cell(40,4, strtoupper("Total Pasivo y Capital"),0,1,"L",0,'',0); 
        $pdf->SetXY($X+20,$Y);
        $pdf->Cell(40,4,number_format($sumapasivocapital,2), 0, 1, "R", 0, '', 0);
        $pdf->SetXY($X+75,$Y);
        $pdf->Cell(15, 3,"100 %", 0, 1, 'C', 0, '', 0);
    }
    $pdf->SetFont('Helvetica', '', 7);
}

function crearEncabezadoPorcentajes ($pdf){
    $pdf->SetFont('Helvetica', '', 6);
    $pdf->SetXY(75, 40);
    $pdf->Cell(15, 3,"Porcientos", 0, 1, 'C', 0, '', 0); 
    $pdf->SetXY(75, 42);
    $pdf->Cell(15, 3,"integrales", 0, 1, 'C', 0, '', 0);
    $pdf->SetXY(75, 44);
    $pdf->Cell(15, 3,"de activos", 0, 1, 'C', 0, '', 0);
    
    $pdf->SetXY(90, 40);
    $pdf->Cell(15, 3,"Porcientos", 0, 1, 'C', 0, '', 0); 
    $pdf->SetXY(90, 42);
    $pdf->Cell(15, 3,"integrales", 0, 1, 'C', 0, '', 0);
    $pdf->SetXY(90, 44);
    $pdf->Cell(15, 3,"en ralación", 0, 1, 'C', 0, '', 0);    
    
    $pdf->SetXY(175, 40);
    $pdf->Cell(15, 3,"Porcientos", 0, 1, 'C', 0, '', 0); 
    $pdf->SetXY(175, 42);
    $pdf->Cell(15, 3,"integrales", 0, 1, 'C', 0, '', 0);
    $pdf->SetXY(175, 44);
    $pdf->Cell(15, 3,"de pasivos", 0, 1, 'C', 0, '', 0);
    
    $pdf->SetXY(190, 40);
    $pdf->Cell(15, 3,"Porcientos", 0, 1, 'C', 0, '', 0); 
    $pdf->SetXY(190, 42);
    $pdf->Cell(15, 3,"integrales", 0, 1, 'C', 0, '', 0);
    $pdf->SetXY(190, 44);
    $pdf->Cell(15, 3,"en ralación", 0, 1, 'C', 0, '', 0);     
}

$pdf->Output('Listado Empresas.pdf', 'I');
?>
