<?php
require_once('recursos/tcpdf/tcpdf.php');
require_once("funciones/funciones.php");


/*Captura de Parametros*/
$anno = $_GET["anno"];
$mes  = $_GET["mes"];
$anno2 = $_GET["anno2"];
$mes2  = $_GET["mes2"];
$empresa = $_GET["empresa"];


/*Consultas de Base de Datos*/
$con = Conexion();
$sqlselect="select * from empresa where idempresa = ". $empresa;
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
$pdf->Text(30, 143, 'Notas Financieras');
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
$pdf->Text(30, 194,retornames((int)$mes).' '.$anno);

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
$pdf->Text(9, 26, 'Notas Financieras '.$nombre." a: " . retornames($mes). ' ' .$anno);  
$pdf->SetFont('Helvetica', '', 7);

$idestadofinanciero = 1;
$Y=40;
$X=10;
$pdf->SetFont('Helvetica', 'B', 8);
$pdf->SetXY($X,$Y);                            
$pdf->Cell(30, 4,"1. Actividades", 0, 1, "L", 0, '', 0); 
$pdf->SetFont('Helvetica', ' ', 8);
$Y+=5;
$pdf->SetXY($X,$Y);
$pdf->WriteHTML('<P ALIGN="left">' . fnctxtActividades($idempresa) . '</P>'); 
$Y+=25;
$pdf->SetFont('Helvetica', 'B', 8);
$pdf->SetXY($X,$Y);   
$pdf->Cell(30, 4,"2. Fecha de constitución  y  Operación", 0, 1, "L", 0, '', 0); 
$Y+=5;
$pdf->SetFont('Helvetica', ' ', 8);
$pdf->SetXY($X,$Y);
$pdf->WriteHTML('<P ALIGN="left">' . fnctxtConstitucion($idempresa) . '</P>'); 
$Y+=30;
$pdf->SetFont('Helvetica', 'B', 8);
$pdf->SetXY($X,$Y);   
$pdf->Cell(30, 4,"3.	Bases de presentación", 0, 1, "L", 0, '', 0); 
$Y+=5;
$pdf->SetFont('Helvetica', ' ', 8);
$pdf->SetXY($X,$Y);

$pdf->WriteHTML('<P ALIGN="left">' . str_replace("X_FECHA_X",retornames($mes) . " de " . $anno ,fnctxtBasePresentacion($idempresa)) . '</P>'); 
        
//$pdf->WriteHTML('<P ALIGN="left">' . substr_replace("X_FECHA_X",retornames($mes) . " de " . $anno ,fnctxtBasePresentacion($idempresa)) . '</P>'); 
$Y+=50;
$pdf->SetFont('Helvetica', 'B', 8);
$pdf->SetXY($X,$Y);   
$pdf->Cell(30, 4,"4.	Resumen de las principales políticas contables", 0, 1, "L", 0, '', 0); 
$Y+=2;
$pdf->SetFont('Helvetica', ' ', 8);
$pdf->SetXY($X,$Y);
$pdf->WriteHTML('<P ALIGN="left">' . fnctxtPoliticasContables($idempresa) . '</P>');


$idtipoagrupacion = 1;
//echo $idtipoagrupacion . " " . $idestadofinanciero;
$Y+=15;
    

for ($idagrupacion=fncminidagrupacion_nf($idempresa);$idagrupacion<=fncmaxidagrupacion_nf($idempresa);$idagrupacion++){
    if ($Y >= 250) {
        $pdf->AddPage();
        $Y=25;
    };
    $Y+=15;
    
    $pdf->SetFont('Helvetica', 'B', 8);
    $pdf->SetXY($X,$Y);     
    $pdf->Cell(30, 4, $idagrupacion + 4 . ". ". fncnombreagrupacion_nf($idagrupacion) , 0, 1, "L", 0, '', 0); 
    
    $pdf->SetXY($X+100,$Y);                     
    $pdf->Cell(30, 4,retornames((int)$mes).' '.$anno, 0, 1, "R", 0, '', 0);

    $pdf->SetXY($X+140,$Y);                     
    $pdf->Cell(30, 4,retornames((int)$mes2).' '.$anno2, 0, 1, "R", 0, '', 0);

    
    $Y+=5;
    $pdf->SetFont('Helvetica', ' ', 8);
    
    $sqlselect="select distinct(idtipoagrupacion) idtipoagrupacion from agrupacion_nf where idempresa= " . $idempresa . " and idagrupacion = " . $idagrupacion . " order by idtipoagrupacion";                         
    //echo $sqlselect;
    $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
    $fila = mysql_fetch_assoc($resultselect);
    $idtipoagrupacion = $fila["idtipoagrupacion"];

    $sqlselect="select * from enasociacion_nf where idempresa= " . $idempresa . " and idtipoagrupacion = " . $idtipoagrupacion . " and idagrupacion = " . $idagrupacion . " order by posicion";                         
    
    
    //echo $sqlselect;
    $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
    $fila = mysql_fetch_assoc($resultselect);
    if(mysql_num_rows($resultselect)>0){
        do{ 
            $sumaxagrupacion=0;
            $sumaxagrupacion_2=0;
            $tipoelemento = $fila["tipoelemento"];
            $idagrupacion = $fila["idagrupacion"];
            if ($tipoelemento == "a"){
                $sqlselectagrup="select * from agrupacioncuentas_nf where idempresa= " . $idempresa . " and idagrupacion = " . $idagrupacion;                         
                $resultselectagrup=mysql_query($sqlselectagrup,$con) or die(mysql_error());
                $filaagrup = mysql_fetch_assoc($resultselectagrup);                            
                if(mysql_num_rows($resultselectagrup)>0){
                    do{                            
                        $codigocuenta = $filaagrup["codigocuenta"];
                        $saldocuenta = 0;
                        $saldocuenta_2 = 0;
                        
                        $saldocuenta   = fncbuscasaldocta($idempresa,fncbuscaridcuenta($idempresa,$codigocuenta),fncbuscaridejercicio($idempresa, $_GET["anno"]),$_GET["mes"],1) * $filaagrup["signo"];
                        $saldocuenta_2 = fncbuscasaldocta($idempresa,fncbuscaridcuenta($idempresa,$codigocuenta),fncbuscaridejercicio($idempresa, $_GET["anno2"]),$_GET["mes2"],1) * $filaagrup["signo"];
                        
                        $Y+=5;    
                        $pdf->SetXY($X,$Y);
                        $pdf->Cell(30, 4,fncnombrecuenta(fncbuscaridcuenta($idempresa,$codigocuenta)) , 0, 1, "L", 0, '', 0);
                        $pdf->SetXY($X+100,$Y);                     
                        $pdf->Cell(30, 4,number_format($saldocuenta,2), 0, 1, "R", 0, '', 0);

                        $pdf->SetXY($X+140,$Y);                     
                        $pdf->Cell(30, 4,number_format($saldocuenta_2,2), 0, 1, "R", 0, '', 0);
                        
                        
                        if ($saldocuenta<>0) {
                            $sumaxagrupacion+=$saldocuenta;
                            $sumaxagrupacion_2+=$saldocuenta_2;
                        }
                    } while ($filaagrup = mysql_fetch_assoc($resultselectagrup));
                    if ($sumaxagrupacion>0){
                        $Y+=5;
                        $pdf->SetXY($X+5,$Y);

                        $pdf->Cell(30, 4,fncPrimeramayuscula(fncnombreagrupacion_nf($idagrupacion)) , 0, 1, "L", 0, '', 0);
                        $pdf->SetXY($X+100,$Y);                            
                        $pdf->Cell(30, 4,number_format($sumaxagrupacion,2), 0, 1, "R", 0, '', 0);
                        $pdf->SetXY($X+140,$Y);                            
                        $pdf->Cell(30, 4,number_format($sumaxagrupacion_2,2), 0, 1, "R", 0, '', 0);
                    }
                }                          
            }elseif (($tipoelemento == "c")){
                $codigocuenta = $fila["codigocuenta"];
                $saldocuenta = fncbuscasaldocta($idempresa,fncbuscaridcuenta($idempresa,$codigocuenta),fncbuscaridejercicio($idempresa, $_GET["anno"]),$_GET["mes"],1) * $fila["signo"];
                    if ($saldocuenta<>0) {
                    $Y+=5;
                    $i+=1;
                    $pdf->SetXY($X+5,$Y);
                    $variable = fncPrimeramayuscula(fncnombrecuenta(fncbuscaridcuenta($idempresa,$codigocuenta)));
                    $pdf->Cell(30, 4, $variable , 0, 1, "L", 0, '', 0);
                    $pdf->SetXY($X+35,$Y);                            
                    $pdf->Cell(30, 4,number_format($saldocuenta,2), 0, 1, "R", 0, '', 0);
                }
            }                
        } while ($fila = mysql_fetch_assoc($resultselect));
    }
}

$Y+=15;

$pdf->SetFont('Helvetica', 'B', 8);
$pdf->SetXY($X,$Y);   
$pdf->Cell(30, 4, fncmaxidagrupacion_nf($idempresa) + 5 . ". ". " Capital Contable", 0, 1, "L", 0, '', 0); 
$Y+=5;
$pdf->SetFont('Helvetica', ' ', 8);
$pdf->SetXY($X,$Y);
$pdf->WriteHTML('<P ALIGN="left">' . str_replace("X_FECHA_X",retornames($mes) . " de " . $anno , 
                    str_replace("X_CAPITAL_X", fncCapital($idempresa,$anodate), fnctxtCapitalContable($idempresa))) . '</P>'); 


$Y+=75;

$pdf->SetFont('Helvetica', 'B', 8);
$pdf->SetXY($X,$Y);   
$pdf->Cell(30, 4, fncmaxidagrupacion_nf($idempresa) + 6 . ". ". " Impuestos a la utilidad", 0, 1, "L", 0, '', 0); 
$Y+=5;
$pdf->SetFont('Helvetica', ' ', 8);
$pdf->SetXY($X,$Y);
$pdf->WriteHTML('<P ALIGN="left">' . str_replace("X_FECHA_X",retornames($mes) . " de " . $anno ,fnctxtImpuestosUtilidad($idempresa)) . '</P>'); 


$pdf->AddPage();

$Y=40;

$pdf->SetFont('Helvetica', 'B', 8);
$pdf->SetXY($X,$Y);   
$pdf->Cell(30, 4, fncmaxidagrupacion_nf($idempresa) + 7 . ". ". " Nuevos pronunciamientos contables", 0, 1, "L", 0, '', 0); 
$Y+=5;
$pdf->SetFont('Helvetica', ' ', 8);
$pdf->SetXY($X,$Y);
$pdf->WriteHTML('<P ALIGN="left">' . str_replace("X_FECHA_X",retornames($mes) . " de " . $anno ,fnctxtPronunciamientosContables($idempresa)) . '</P>'); 




$pdf->Output('Listado Empresas.pdf', 'I');
?>