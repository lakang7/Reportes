<?php

    require_once('recursos/tcpdf/tcpdf.php');
    require_once("funciones/funciones.php");
    
    $con = Conexion();
    $buscaempresa = 1;
    $buscaano = 2015;
    $buscames = 6;
    $buscaejercicio = "";
        
    $categoriasID=array();
    $categoriasVa=array();
    $razonesporca=array();
    $valoresporra=array();
    
    $sqlEmpresa="select * from empresa where idempresa='".$buscaempresa."'";
    $resutlEmpresa=mysql_query($sqlEmpresa,$con) or die(mysql_error());
    $Empresa=mysql_fetch_assoc($resutlEmpresa);
    
    $sqlCategoria="select * from categoriarazon;";
    $resutlCategoria=mysql_query($sqlCategoria,$con) or die(mysql_error());
    $cont=0;
    while ($categoria=mysql_fetch_assoc($resutlCategoria)) {
        $categoriasID[$cont]=$categoria["idcategoriarazon"];
        $categoriasVa[$cont]=0;
        $razonesporca[$cont]="";
        $valoresporra[$cont]="";
        $cont++;
    } 
    
    $sqlEjercicio="select * from ejercicio where idempresa='".$buscaempresa."' and ejercicio='".$buscaano."';";
    $resutlEjercicio=mysql_query($sqlEjercicio,$con) or die(mysql_error());
    while ($ejercicio=mysql_fetch_assoc($resutlEjercicio)) {
        $buscaejercicio = $ejercicio["idejercicio"];
    }
    
    $sqlRazones = "select * from razonfinanciera order by idrazonfinanciera;";
    $resutlRazones=mysql_query($sqlRazones,$con) or die(mysql_error());   
    $numeroRazones=mysql_num_rows($resutlRazones);
    while ($razon=mysql_fetch_assoc($resutlRazones)) {
        $conttotal=0;
        
        $sqlCalculo = "select * from calculo where idrazonfinanciera='".$razon["idrazonfinanciera"]."' order by idcalculo, posicion;";
        $resutlCalculo=mysql_query($sqlCalculo,$con) or die(mysql_error());        
        while ($calculo=mysql_fetch_assoc($resutlCalculo)) {            
            if($calculo["idclave"]!=""){
                $sqlAsocia = "select * from asociaclave where idempresa='".$buscaempresa."' and idclave='".$calculo["idclave"]."' ";
                $resultAsocia=mysql_query($sqlAsocia,$con) or die(mysql_error());
                $bandera=mysql_num_rows($resultAsocia);
                if($bandera==1){
                    $asocia=mysql_fetch_assoc($resultAsocia);
                }else{
                    $conttotal++;
                }
            }                     
        }

        if($conttotal==0){

            $matematica="";
            mysql_data_seek($resutlCalculo,0);
            while ($calculo=mysql_fetch_assoc($resutlCalculo)) {
                if($calculo["tipo"]==1){ /*Clave*/
                    $sqlAsocia = "select * from asociaclave where idempresa='".$buscaempresa."' and idclave='".$calculo["idclave"]."' ";
                    $resultAsocia=mysql_query($sqlAsocia,$con) or die(mysql_error());
                    $auxasocia=mysql_fetch_assoc($resultAsocia);
                    $sqlcuenta="select * from cuenta where codigo='".$auxasocia["codigo"]."' and idempresa='".$buscaempresa."'";
                    $resultcuenta=mysql_query($sqlcuenta,$con) or die(mysql_error());
                    $cuenta=mysql_fetch_assoc($resultcuenta);
                    $sqlvalor="select * from saldo where tipo='".$auxasocia["indice"]."' and ejercicio='".$buscaejercicio."' and idempresa='".$buscaempresa."' and idcuenta='".$cuenta["idcuenta"]."' ";
                    $resultvalor=mysql_query($sqlvalor,$con) or die(mysql_error());
                    $valor=mysql_fetch_assoc($resultvalor);
                    $val="";
                    if($buscames==1){
                        $val=$valor["importes1"];
                    }
                    if($buscames==2){
                        $val=$valor["importes2"];
                    }
                    if($buscames==3){
                        $val=$valor["importes3"];
                    }
                    if($buscames==4){
                        $val=$valor["importes4"];
                    }
                    if($buscames==5){
                        $val=$valor["importes5"];
                    }
                    if($buscames==6){
                        $val=$valor["importes6"];
                    }
                    if($buscames==7){
                        $val=$valor["importes7"];
                    }
                    if($buscames==8){
                        $val=$valor["importes8"];
                    }
                    if($buscames==9){
                        $val=$valor["importes9"];
                    }
                    if($buscames==10){
                        $val=$valor["importes10"];
                    }
                    if($buscames==11){
                        $val=$valor["importes11"];
                    }
                    if($buscames==12){
                        $val=$valor["importes12"];
                    }                    
                    
                    if($calculo["operacion"]==1){
                        $matematica=$matematica."+".$val;
                    }else if($calculo["operacion"]==2){
                        $matematica=$matematica."-".$val;
                    }else if($calculo["operacion"]==3){
                        $matematica=$matematica."*".$val;
                    }else if($calculo["operacion"]==4){
                        $matematica=$matematica."/".$val;                       
                    }                    
                    
                } else if($calculo["tipo"]==2){ /*Constante*/
                    if($calculo["operacion"]==1){
                        $matematica=$matematica."+".$calculo["valorconstante"];
                    }else if($calculo["operacion"]==2){
                        $matematica=$matematica."-".$calculo["valorconstante"];
                    }else if($calculo["operacion"]==3){
                        $matematica=$matematica."*".$calculo["valorconstante"];
                    }else if($calculo["operacion"]==4){
                        $matematica=$matematica."/".$calculo["valorconstante"];                       
                    }
                                        
                } else if($calculo["tipo"]==3){ /*Parentisis*/
                    
                    $operacion="";
                    if($calculo["operacion"]==1){
                        $operacion="+";
                    }else if($calculo["operacion"]==2){
                        $operacion="-";
                    }else if($calculo["operacion"]==3){
                        $operacion="*";
                    }else if($calculo["operacion"]==4){
                        $operacion="/";
                    }
                                                            
                    if($calculo["parentesis"]==1){
                        $matematica=$matematica.$operacion."(";
                    }else if($calculo["parentesis"]==2){
                        $matematica=$matematica.$operacion.")";
                    }                                        
                }                
            }
            
            eval("\$var = $matematica;");            
            
            for($i=0;$i<count($categoriasID);$i++){
                if($categoriasID[$i]==$razon["idcategoriarazon"]){
                    $categoriasVa[$i]=$categoriasVa[$i]+1;
                    $razonesporca[$i]=$razonesporca[$i].$razon["idrazonfinanciera"]."-";
                    $valoresporra[$i]=$valoresporra[$i].round($var,2).";";
                }
            }            

            
        }

    }
    
 
    $totalcategorias=0;
    $totalrazones=0;
    for($i=0;$i<$cont;$i++){
        if($categoriasVa[$i]>0){
            $totalcategorias++;
            $totalrazones+=$categoriasVa[$i];
        }
    } 
    
    $separacion=  round(((50-$totalrazones)/$totalcategorias),0,PHP_ROUND_HALF_DOWN);
    

    
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
     
    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Gaag Desarrollo Empresarial');
    $pdf->SetTitle('Resumen de Indicadores Financieras');

    // disable header and footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, 0);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
        require_once(dirname(__FILE__).'/lang/eng.php');
        $pdf->setLanguageArray($l);
    }
    
    $meses=array();
    
    $meses[0]="Enero";
    $meses[1]="Febrero";
    $meses[2]="Marzo";
    $meses[3]="Abril";
    $meses[4]="Mayo";
    $meses[5]="Junio";
    $meses[6]="Julio";
    $meses[7]="Agosto";
    $meses[8]="Septiembre";
    $meses[9]="Octubre";
    $meses[10]="Noviembre";
    $meses[11]="Diciembre";
    
    $me[0]="Ene";
    $me[1]="Feb";
    $me[2]="Mar";
    $me[3]="Abr";
    $me[4]="May";
    $me[5]="Jun";
    $me[6]="Jul";
    $me[7]="Ago";
    $me[8]="Sep";
    $me[9]="Oct";
    $me[10]="Nov";
    $me[11]="Dic";
    
    
    
    $pdf->AddPage('P', 'A4'); 
    $style1 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '10,10', 'color' => array(50, 128, 100));    
    $pdf->Image('recursos/logo300px.jpg', 65, 100, 75, 32, 'JPG', 'http://www.gaagdesarrolloempresarial.com', '', true, 150, '', false, false, 0, false, false, false);
    $pdf->Line(30, 140, 180, 140);
    $pdf->SetFont('Helvetica', '', 16);
    $pdf->Text(30, 143, 'Resumen de Indicadores Financieros');
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
    $pdf->Text(30, 179,$Empresa["nombre"]);    
    
    $pdf->SetFont('Helvetica', '', 8);
    $pdf->SetTextColor(126,130,109);    
    $pdf->Text(30, 189, 'Periodo en Revisión');    
    $pdf->SetFont('Helvetica', '', 12);
    $pdf->SetTextColor(0,0,0);    
    $pdf->Text(30, 194,$meses[($buscames-1)].' '.$buscaano);    
    
    
    $pdf->SetFont('Helvetica', '', 8);
    $pdf->SetTextColor(126,130,109);    
    $pdf->Text(30, 203, 'Creado el');     
    $pdf->SetFont('Helvetica', '', 9);
    $pdf->SetTextColor(0,0,0);  
    $dia=date("d");
    $mes=date("m");
    $ano=date("Y");
    $pdf->Text(30, 208,$dia." de ".$meses[($mes-1)]." de ".$ano);
    
    $pdf->SetFont('Helvetica', '', 8);
    $pdf->AddPage('P', 'A4');
    
    $pdf->Image('recursos/logo300px.jpg', 10, 10, 30, 12.8, 'JPG', 'http://www.gaagdesarrolloempresarial.com', '', true, 150, '', false, false, 0, false, false, false);
    $pdf->Line(10, 25, 200, 25);
    $pdf->SetFont('Helvetica', '', 10);
    $pdf->Text(9, 26, 'Resumen de Indicadores Financieros');
    $pdf->SetFont('Helvetica', '', 8);
    $pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(224,224,224)));      
    
    $pdf->Circle(105,135,55.9);
    $pdf->Circle(105,135,55.6);
    $pdf->Circle(105,135,55.3);  
    $pdf->Circle(105,135,55);
    $pdf->Circle(105,135,54.7);
    $pdf->Circle(105,135,54.4);
    $pdf->Circle(105,135,54.1);
    $cuentaOK=0;
    $cuentaFA=0;
    $angulo=0;
    for($i=0;$i<$totalcategorias;$i++){
        $pdf->SetFont('Helvetica', '', 8);
        $listarazones = explode("-",$razonesporca[$i]);
        $listavalores = explode(";",$valoresporra[$i]);
        for($j=0;$j<(count($listarazones)-1);$j++){
            
            $sqlRazon = "select * from razonfinanciera where idrazonfinanciera='".$listarazones[$j]."'";
            $resutlRazon=mysql_query($sqlRazon,$con) or die(mysql_error());             
            $raz=mysql_fetch_assoc($resutlRazon);            
            $pdf->StartTransform();
            $pdf->Rotate(($angulo*7.2), 105, 135);
            if((double)$listavalores[$j]>=1){
                $pdf->Polygon(array(156.715,129.565,157, 135,163, 135,162.682,128.937), 'F', array($style1), array(125, 190, 17));
            }else{
                $pdf->Polygon(array(156.715,129.565,157, 135,163, 135,162.682,128.937), 'F', array($style1), array(208, 0, 10));
            }            
            $pdf->StopTransform();                         
            
            $angulos=array();
            $angulos[0]=0; 
            $angulos[1]=7;
            $angulos[2]=15;
            $angulos[3]=22;
            $angulos[4]=29;
            $angulos[5]=36;
            $angulos[6]=43;
            $angulos[7]=51;
            $angulos[8]=58;
            $angulos[9]=65;
            $angulos[10]=79;
            $angulos[11]=72;
            $angulos[12]=87;            
            $angulos[13]=-86;
            $angulos[14]=-79;
            $angulos[15]=-72;
            $angulos[16]=-65;
            $angulos[17]=-58;
            $angulos[18]=-51;
            $angulos[19]=-43;
            $angulos[20]=-36;
            $angulos[21]=-29;
            $angulos[22]=-22;
            $angulos[23]=-15;
            $angulos[24]=-7;
            $angulos[25]=0;
            $angulos[26]=7;
            $angulos[27]=14;
            $angulos[28]=22;
            $angulos[29]=29;
            $angulos[30]=36;
            $angulos[31]=43;
            $angulos[32]=50;
            $angulos[33]=58;
            $angulos[34]=65;
            $angulos[35]=72;
            $angulos[36]=79;
            $angulos[37]=86;                        
            $angulos[38]=-86;
            $angulos[39]=-78;
            $angulos[40]=-71;
            $angulos[41]=-64;
            $angulos[42]=-57;
            $angulos[43]=-50;
            $angulos[44]=-43;
            $angulos[45]=-36;
            $angulos[46]=-29;
            $angulos[47]=-22;
            $angulos[48]=-14;
            $angulos[49]=-7;            

            if(($angulo>=0 && $angulo<=12) || ($angulo>=38 && $angulo<=49)){ /*Al derecho*/
                $pdf->StartTransform();
                $pdf->Rotate($angulos[$angulo], 105,135);    
                $pdf->SetXY(163, 130);
                $pdf->Cell(32, 5,$raz["nombre"], 0, 1, 'L', 0, '', 0);    
                $pdf->StopTransform();                
            }else
            if($angulo>=13 && $angulo<=37){ /*Al contrario*/
                $pdf->StartTransform();
                $pdf->Rotate($angulos[$angulo], 105,135);
                $pdf->SetX(0);
                $pdf->SetY(135.5);
                $pdf->Cell(32, 5,$raz["nombre"], 0, 1, 'R', 0, '', 0);
                $pdf->StopTransform();                 
            }                                                
            
            $pdf->SetTextColor(255,255,255);
            $pdf->SetFont('helvetica', '', 8);            
            $pdf->StartTransform();
            $pdf->Rotate((($angulo+1)*7.2)-2, 105,135);
            if((double)$listavalores[$j]>=1){
                $cuentaOK++;
                $pdf->Text(105,135, '                                                                    O');
            }else{
                $cuentaFA++;
                $pdf->Text(105,135, '                                                                    X');
            }
            $pdf->SetTextColor(0,0,0);            
            $pdf->StopTransform();            
            
            $angulo++;
        }
        for($k=0;$k<$separacion;$k++){
            $angulo++;
        }        
    }
    
    $porcentaje=(($cuentaOK*100)/($cuentaOK+$cuentaFA));
    $angcalcula=(($porcentaje*360)/100);
    
    $pdf->SetFont('Helvetica', 'B', 16);
    $pdf->Text(92,105,$me[($buscames-1)].' '.$buscaano);    
    
    $pdf->SetFont('Helvetica', '', 20);
    $pdf->Text(97,131,round($porcentaje,0)."%");
    
    $pdf->SetFont('Helvetica', '', 20);    
    $pdf->Text(65,131,round($cuentaOK,0));
    $pdf->Text(138,131,round($cuentaFA,0));
    
    $pdf->SetFont('Helvetica', '', 7);
    $pdf->Text(60,140,"BUEN ESTADO");
    $pdf->SetFont('Helvetica', '', 10);
    $pdf->Text(66,144,"O");
    $pdf->SetFont('Helvetica', '', 7);    
    $pdf->Text(128,140,"ESTADO DE ALERTA");
    $pdf->SetFont('Helvetica', '', 10);
    $pdf->Text(139,144,"X");    
    
    $pdf->SetFont('Helvetica', '', 9);
    $pdf->Text(82,160,$numeroRazones." Indicadores (".($numeroRazones-($cuentaOK+$cuentaFA))." n/a resultados)");
    
    $pdf->SetFont('Helvetica', '', 8);
    $pdf->SetLineStyle(array('color' => array(125, 190, 17)));
    $pdf->Circle(105,135,18,0,$angcalcula);
    $pdf->Circle(105,135,18.1,0,$angcalcula);
    $pdf->Circle(105,135,18.2,0,$angcalcula);
    $pdf->Circle(105,135,18.3,0,$angcalcula);
    $pdf->Circle(105,135,18.4,0,$angcalcula);
    $pdf->Circle(105,135,18.5,0,$angcalcula);
    $pdf->Circle(105,135,18.6,0,$angcalcula);
    $pdf->Circle(105,135,18.7,0,$angcalcula);
    $pdf->Circle(105,135,18.8,0,$angcalcula);
    $pdf->Circle(105,135,18.9,0,$angcalcula);
    $pdf->Circle(105,135,19,0,$angcalcula);
    
    $pdf->SetLineStyle(array('color' => array(208, 0, 10)));
    $pdf->Circle(105,135,18,$angcalcula,360);
    $pdf->Circle(105,135,18.1,$angcalcula,360);
    $pdf->Circle(105,135,18.2,$angcalcula,360);
    $pdf->Circle(105,135,18.3,$angcalcula,360);
    $pdf->Circle(105,135,18.4,$angcalcula,360);
    $pdf->Circle(105,135,18.5,$angcalcula,360);
    $pdf->Circle(105,135,18.6,$angcalcula,360);
    $pdf->Circle(105,135,18.7,$angcalcula,360);
    $pdf->Circle(105,135,18.8,$angcalcula,360);
    $pdf->Circle(105,135,18.9,$angcalcula,360);
    $pdf->Circle(105,135,19,$angcalcula,360);
    
    
    $pdf->SetFont('Helvetica', '', 7);
    $pdf->SetTextColor(126,130,109);
    $pdf->Text(10,277,"Creado por GAAG Desarrollo Empresarial");
    $pdf->SetTextColor(0,0,0);
    $pdf->Text(10,280,$Empresa["nombre"]." | ".$me[($buscames-1)].' '.$buscaano); 
    $pdf->Text(185,280,"Página 02");
    
    $pdf->AddPage('P', 'A4');    
    $pdf->Image('recursos/logo300px.jpg', 10, 10, 30, 12.8, 'JPG', 'http://www.gaagdesarrolloempresarial.com', '', true, 150, '', false, false, 0, false, false, false);
    $pdf->SetLineStyle(array('color' => array(0,0,0)));
    $pdf->Line(10, 25, 200, 25);
    $pdf->SetFont('Helvetica', '', 10);
    $pdf->Text(9, 26, 'Resumen de Indicadores Financieros');
    $pdf->SetFont('Helvetica', '', 8);
    
    
    $posiciony=34;
    $pdf->SetLineStyle(array('color' => array(148,148,148)));
    $pdf->Line(10, $posiciony, 200, $posiciony);
    $pdf->Text(100, 36, 'Resultado');
    $pdf->Text(120, 36, 'Objetivo');
    $posiciony+=7;
    $letras=array();    
    $letras[0]="A";
    $letras[1]="B";
    $letras[2]="C";
    $letras[3]="D";
    $letras[4]="E";
    $letras[5]="F";
    $letras[6]="G";
    
    for($i=0;$i<$cont;$i++){
        $sqlCat="select * from categoriarazon where idcategoriarazon='".$categoriasID[$i]."'";
        $resutlCat=mysql_query($sqlCat,$con) or die(mysql_error());
        $Cat = mysql_fetch_assoc($resutlCat);        
        //echo $letras[$i]." ".$Cat["nombre"]."</br>";
        
        $pdf->SetFillColor(244, 244, 244);
        $pdf->Line(10, $posiciony, 200, $posiciony);
        $pdf->Rect(10, $posiciony, 190, 7,'F');     
        $pdf->Text(10,$posiciony+2,$letras[$i].". ".$Cat["nombre"]);
        $posiciony+=7;
        $pdf->Line(10, $posiciony, 200, $posiciony);
        $lisaux = explode("-",$razonesporca[$i]);
        $lisval = explode(";",$valoresporra[$i]);
        for($j=0;$j<(count($lisaux)-1);$j++){
            $sqlRaz="select * from razonfinanciera where idrazonfinanciera='".$lisaux[$j]."'";
            $resutlRaz=mysql_query($sqlRaz,$con) or die(mysql_error());
            $Raz = mysql_fetch_assoc($resutlRaz);   
            $pdf->Line(10, $posiciony, 200, $posiciony);
            $pdf->Text(10,$posiciony+2,$Raz["nombre"]);
            $posiciony+=7;
            
            //echo $Raz["nombre"]." ".$lisval[$j]."</br>";
        }
    }    
    
        
   

    $pdf->Line(10, $posiciony, 200, $posiciony);
    
    $pdf->Output('example_012.pdf', 'I');       
    
?>        
