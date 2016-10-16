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
$pdf->Text(30, 143, 'Reporte de Presupuesto');
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
$pdf->Text(9, 26, 'Reporte de Presupuesto '.$nombre." ".retornames($_GET["mes"]).' '.$_GET["anno"]);  
$pdf->SetFont('Helvetica', '', 7);

function calcularagrupacionL($con,$idagrupacion,$mes,$ejercicio,$idempresa,$frecuencia){
    $sqlselectagrupacion="select * from agrupacioncuentasest where idagrupacionest='".$idagrupacion."' and idempresa='".$idempresa."'";
    $resultAgrupacion=mysql_query($sqlselectagrupacion,$con) or die(mysql_error());
    $totalizacion=0;
    while ($agrupacioncuenta=mysql_fetch_assoc($resultAgrupacion)) { 
        $sqlcuenta="select * from cuenta where codigo='".$agrupacioncuenta["codigocuenta"]."' and idempresa='".$idempresa."'";
        //echo $sqlcuenta."</br>";
        $resultCuenta=mysql_query($sqlcuenta,$con) or die(mysql_error());
        $cuenta=mysql_fetch_assoc($resultCuenta);
        $tipo=$agrupacioncuenta[$frecuencia];

        if($tipo==1 ||$tipo==2 ||$tipo==3 ){
            $sqlSaldo="select * from saldo where idempresa='".$idempresa."' and ejercicio='".$ejercicio."' and idcuenta='".$cuenta["idcuenta"]."' and tipo='".$tipo."'";
            $resultSaldo=mysql_query($sqlSaldo,$con) or die(mysql_error());
            $saldo=mysql_fetch_assoc($resultSaldo);  
            //echo $sqlSaldo."</br>";
            if($agrupacioncuenta["signo"]==1){
                $totalizacion+=$saldo["importes".$mes];
            }else if($agrupacioncuenta["signo"]==-1) {
                $totalizacion-=$saldo["importes".$mes];
            }            
        }
        
        if($tipo==4){
            $sqlSaldo1="select * from saldo where idempresa='".$idempresa."' and ejercicio='".$ejercicio."' and idcuenta='".$cuenta["idcuenta"]."' and tipo=2";
            $resultSaldo1=mysql_query($sqlSaldo1,$con) or die(mysql_error());
            $saldo1=mysql_fetch_assoc($resultSaldo1);
        
            $sqlSaldo2="select * from saldo where idempresa='".$idempresa."' and ejercicio='".$ejercicio."' and idcuenta='".$cuenta["idcuenta"]."' and tipo=3";
            $resultSaldo2=mysql_query($sqlSaldo2,$con) or die(mysql_error());
            $saldo2=mysql_fetch_assoc($resultSaldo2); 
            
            if($agrupacioncuenta["signo"]==1){
                $totalizacion+=($saldo1["importes".$mes]-$saldo2["importes".$mes]);
            }else if($agrupacioncuenta["signo"]==-1) {
                $totalizacion-=($saldo1["importes".$mes]-$saldo2["importes".$mes]);
            }             
        } 
        
        if($tipo==5){
            $sqlSaldo1="select * from saldo where idempresa='".$idempresa."' and ejercicio='".$ejercicio."' and idcuenta='".$cuenta["idcuenta"]."' and tipo=2";
            $resultSaldo1=mysql_query($sqlSaldo1,$con) or die(mysql_error());
            $saldo1=mysql_fetch_assoc($resultSaldo1);
        
            $sqlSaldo2="select * from saldo where idempresa='".$idempresa."' and ejercicio='".$ejercicio."' and idcuenta='".$cuenta["idcuenta"]."' and tipo=1";
            $resultSaldo2=mysql_query($sqlSaldo2,$con) or die(mysql_error());
            $saldo2=mysql_fetch_assoc($resultSaldo2); 
            
            if($agrupacioncuenta["signo"]==1){
                $totalizacion+=($saldo1["importes".$mes]-$saldo2["importes".$mes]);
            }else if($agrupacioncuenta["signo"]==-1) {
                $totalizacion-=($saldo1["importes".$mes]-$saldo2["importes".$mes]);
            }             
        } 
        
        if($tipo==6){
            $sqlSaldo1="select * from saldo where idempresa='".$idempresa."' and ejercicio='".$ejercicio."' and idcuenta='".$cuenta["idcuenta"]."' and tipo=3";
            $resultSaldo1=mysql_query($sqlSaldo1,$con) or die(mysql_error());
            $saldo1=mysql_fetch_assoc($resultSaldo1);
        
            $sqlSaldo2="select * from saldo where idempresa='".$idempresa."' and ejercicio='".$ejercicio."' and idcuenta='".$cuenta["idcuenta"]."' and tipo=1";
            $resultSaldo2=mysql_query($sqlSaldo2,$con) or die(mysql_error());
            $saldo2=mysql_fetch_assoc($resultSaldo2); 
            
            if($agrupacioncuenta["signo"]==1){
                $totalizacion+=($saldo1["importes".$mes]-$saldo2["importes".$mes]);
            }else if($agrupacioncuenta["signo"]==-1) {
                $totalizacion-=($saldo1["importes".$mes]-$saldo2["importes".$mes]);
            }             
        }
        
        if($tipo==7){
            $sqlSaldo1="select * from saldo where idempresa='".$idempresa."' and ejercicio='".$ejercicio."' and idcuenta='".$cuenta["idcuenta"]."' and tipo=3";
            $resultSaldo1=mysql_query($sqlSaldo1,$con) or die(mysql_error());
            $saldo1=mysql_fetch_assoc($resultSaldo1);
        
            $sqlSaldo2="select * from saldo where idempresa='".$idempresa."' and ejercicio='".$ejercicio."' and idcuenta='".$cuenta["idcuenta"]."' and tipo=2";
            $resultSaldo2=mysql_query($sqlSaldo2,$con) or die(mysql_error());
            $saldo2=mysql_fetch_assoc($resultSaldo2); 
            
            if($agrupacioncuenta["signo"]==1){
                $totalizacion+=($saldo1["importes".$mes]-$saldo2["importes".$mes]);
            }else if($agrupacioncuenta["signo"]==-1) {
                $totalizacion-=($saldo1["importes".$mes]-$saldo2["importes".$mes]);
            }             
        }        
    }
   // echo $totalizacion." ";
    return $totalizacion;
}
$altura=35;
$pdf->SetFont('Helvetica', '', 7);
$pdf->SetXY(10, $altura);
$pdf->Cell(70, 4,"Real", 0, 1, 'C', 0, '', 0);
$pdf->SetXY(80, $altura);
$pdf->Cell(20, 4,"Real", 0, 1, 'C', 0, '', 0);
$pdf->SetXY(100, $altura);
$pdf->Cell(20, 4,"% Integrales", 0, 1, 'C', 0, '', 0);
$pdf->SetXY(120, $altura);
$pdf->Cell(20, 4,"Presupuesto", 0, 1, 'C', 0, '', 0);
$pdf->SetXY(140, $altura);
$pdf->Cell(20, 4,"% Integrales", 0, 1, 'C', 0, '', 0);
$pdf->SetXY(160, $altura);
$pdf->Cell(20, 4,"Diferencia", 0, 1, 'C', 0, '', 0);
$pdf->SetXY(180, $altura);
$pdf->Cell(20, 4,"% Integrales", 0, 1, 'C', 0, '', 0);
$altura+=10;
$pdf->SetXY(10, $altura);

$sqlNivel01="select * from tipoagrupacionest order by posicion";
$resultNivel01=mysql_query($sqlNivel01,$con) or die(mysql_error());
$restando=0;
$cuenta=0;
$band=0;
$band1=0;
$principalreal=0;
$principalpresupuesto=0;
$restandoPresupuesto=0;

while ($Nivel01=mysql_fetch_assoc($resultNivel01)){
    $sqlNivel02="select * from agrupacionest where idtipoagrupacionest='".$Nivel01["idtipoagrupacionest"]."' and idempresa='".$_GET["empresa"]."'";
    $resultNivel02=mysql_query($sqlNivel02,$con) or die(mysql_error());
    
    
    while ($Nivel02=mysql_fetch_assoc($resultNivel02)){        
        $pdf->SetXY(10,$altura);
        $pdf->Cell(70, 4,$Nivel02["nombre"], 0, 1, 'L', 0, '', 0);
        $pdf->SetXY(80, $altura);
        
        if($band==0){
            $band=1;
            $principalreal=calcularagrupacionL($con,$Nivel02["idagrupacionest"],$_GET["mes"],$idejercicio,$_GET["empresa"],"tipop");
        }
        $pdf->Cell(20, 4,  number_format(calcularagrupacionL($con,$Nivel02["idagrupacionest"],$_GET["mes"],$idejercicio,$_GET["empresa"],"tipop"),2), 0, 1, 'R', 0, '', 0);                
        
        $pdf->SetXY(100, $altura);
        $calculo=(calcularagrupacionL($con,$Nivel02["idagrupacionest"],$_GET["mes"],$idejercicio,$_GET["empresa"],"tipop")*100)/$principalreal;
        $pdf->Cell(20, 4,  number_format($calculo,2)." %", 0, 1, 'R', 0, '', 0);
        
        
       
        
        $sqlBUSCA="SELECT * FROM agrupacioncuentasest where idagrupacionest='".$Nivel02["idagrupacionest"]."' order by posicion";
        $resultBUSCA=mysql_query($sqlBUSCA,$con) or die(mysql_error());
        $temporalPresupuesto=0;
        if(mysql_num_rows($resultBUSCA)>0){        
            $cuentaBUSCA=mysql_fetch_assoc($resultBUSCA);  
            $sqlCuenta="select * from cuenta where codigo='".$cuentaBUSCA["codigocuenta"]."' and idempresa='".$_GET["empresa"]."'";
            $resultCuenta=mysql_query($sqlCuenta,$con) or die(mysql_error());
            $cuentaEncontrada=mysql_fetch_assoc($resultCuenta);             
            $sqlPresupuesto="select * from presupuesto where idempresa='".$_GET["empresa"]."' and ejercicio='".$idejercicio."' and idcuenta='".$cuentaEncontrada["idcuenta"]."'";            
            $resultPresupuesto=mysql_query($sqlPresupuesto,$con) or die(mysql_error());
            if(mysql_num_rows($resultPresupuesto)>0){
                $presupuesto=mysql_fetch_assoc($resultPresupuesto); 
                $pdf->SetXY(120, $altura);
                $pdf->Cell(20, 4,  number_format($presupuesto["importes".$_GET["mes"]],2), 0, 1, 'R', 0, '', 0); 
                $temporalPresupuesto=$presupuesto["importes".$_GET["mes"]];
                if($band1==0){
                    $principalpresupuesto=$presupuesto["importes".$_GET["mes"]];
                    $band1=1;                    
                }
            }else{
                $pdf->SetXY(120, $altura);
                $pdf->Cell(20, 4,"0.00", 0, 1, 'R', 0, '', 0);  
                $temporalPresupuesto=0;
            }
           
        }else{
            $pdf->SetXY(120, $altura);
            $pdf->Cell(20, 4,"0.00", 0, 1, 'R', 0, '', 0); 
            $temporalPresupuesto=0;
        }
        
        $pdf->SetXY(140, $altura);
        $calculo2=($temporalPresupuesto*100)/$principalpresupuesto;
        $pdf->Cell(20, 4,  number_format($calculo2,2), 0, 1, 'R', 0, '', 0); 
        
        $pdf->SetXY(160, $altura);
        $pdf->Cell(20, 4,  number_format((calcularagrupacionL($con,$Nivel02["idagrupacionest"],$_GET["mes"],$idejercicio,$_GET["empresa"],"tipop")-$temporalPresupuesto),2), 0, 1, 'R', 0, '', 0);        
      
        
        if($cuenta==0){
            $restando=calcularagrupacionL($con,$Nivel02["idagrupacionest"],$_GET["mes"],$idejercicio,$_GET["empresa"],"tipop");
            $restandoPresupuesto=$temporalPresupuesto;
            
        }else{
            $restando-=calcularagrupacionL($con,$Nivel02["idagrupacionest"],$_GET["mes"],$idejercicio,$_GET["empresa"],"tipop");
            $restandoPresupuesto-=$temporalPresupuesto;
        }
                
        $altura+=4.4;
        $cuenta++;
    }
    $pdf->SetXY(10,$altura);
    $pdf->SetFont('Helvetica', 'B', 7);
    $pdf->Cell(70, 4,"     ".$Nivel01["nombre"], 0, 1, 'L', 0, '', 0); 
    $pdf->SetXY(80, $altura);    
    $pdf->Cell(20, 4,  number_format($restando,2), 0, 1, 'R', 0, '', 0);
    $pdf->SetXY(100, $altura);
    $calculo=($restando*100)/$principalreal;
    $pdf->Cell(20, 4,  number_format($calculo,2)." %", 0, 1, 'R', 0, '', 0);  
    $pdf->SetXY(120, $altura);
    $pdf->Cell(20, 4,$restandoPresupuesto, 0, 1, 'R', 0, '', 0);     
    $pdf->SetXY(140, $altura);
    $calculo2=($restandoPresupuesto*100)/$principalpresupuesto;
    $pdf->Cell(20, 4,  number_format($calculo2,2)." %", 0, 1, 'R', 0, '', 0);    
    
    $pdf->SetXY(160, $altura);
    $pdf->Cell(20, 4,  number_format(($restando-$restandoPresupuesto),2), 0, 1, 'R', 0, '', 0);      
   $pdf->SetFont('Helvetica', '', 7);
    $altura+=10;
}


/*
$acumulado=array();
$acumulado[1]=0;
$acumulado[2]=0;
$acumulado[3]=0;
$acumulado[4]=0;
$acumulado[5]=0;
$acumulado[6]=0;
$acumulado[7]=0;
$acumulado[8]=0;
$acumulado[9]=0;
$acumulado[10]=0;
$acumulado[11]=0;
$acumulado[12]=0;

for($i=1;$i<13;$i++){
$sqlEstructura="SELECT * FROM enasociacioner where idempresa='".$_GET["empresa"]."' ORDER BY idenasociacioner ASC ";
$resultEstructura=mysql_query($sqlEstructura,$con) or die(mysql_error());
$cuenta=1;
$acumulatemp=0;
while ($estructura=mysql_fetch_assoc($resultEstructura)) { 
    if($cuenta==1){
        $acumulatemp=calcularagrupacionL($con,$estructura["idagrupacionest"],$i,13,$_GET["empresa"],"tipop");
    }else{
        $acumulatemp-=calcularagrupacionL($con,$estructura["idagrupacionest"],$i,13,$_GET["empresa"],"tipop"); 
    }
    $acumulado[$cuenta]+=calcularagrupacionL($con,$estructura["idagrupacionest"],$i,13,$_GET["empresa"],"tipop");
    $cuenta++;
    echo "</br>";
}

echo $acumulatemp."</br>";

echo "</br></br>";
}   

echo "-----------------------------------------------------------</br>";*/
$pdf->Output('Listado Empresas.pdf', 'I');
?>