<?php

    function Conexion(){       
        $conexion = mysql_connect("localhost", "root", "");
	mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $conexion);
        mysql_select_db("razonesfinancieras", $conexion);	        
	return $conexion;
    }
    
    function Menu(){
        echo "<div class='col-md-12 itemMenu' onclick=redirigir('recursos/acciones.php?tarea=12')>Cerrar Sessión</div>";
        echo "<div class='col-md-12 itemMenu' onclick=redirigir('listarempresa.php')>Empresas</div>";
        echo "<div class='col-md-12 itemMenu' onclick=redirigir('listaremails.php')>Receptores de Correo</div>";
        echo "<div class='col-md-12 itemMenu' onclick=redirigir('reportebalancegeneral.php') >Reporte Balance General</div>";
        echo "<div class='col-md-12 itemMenu' onclick=redirigir('reportebalancegeneralcomp_pantalla.php') >Reporte Balance General Comparativo</div>";
        echo "<div class='col-md-12 itemMenu' onclick=redirigir('reporteestadoderesultados_pantalla.php') >Reporte Estado de Resultado Mensualizado</div>";
        echo "<div class='col-md-12 itemMenu' onclick=redirigir('reporteestadoderesultados_pantalla_anual.php') >Reporte Estado de Resultados Anualizado</div>";
        echo "<div class='col-md-12 itemMenu' onclick=redirigir('reporterazonesfinancieras.php') >Reporte Razones Financieras</div>";
        echo "<div class='col-md-12 itemMenu' onclick=redirigir('reporteestadoderesultados.php') >Reporte Estado de Resultados</div>";
        echo "<div class='col-md-12 itemMenu' onclick=redirigir('notasfinancierasgenerales.php') >Configuraciones Generales Notas Financieras</div>";
        echo "<div class='col-md-12 itemMenu' onclick=redirigir('PantallaNotasFinancieras.php') style='border-bottom: 1px solid #CCCCCC' >Reporte Notas Financieras</div>";        
    }        
    function retornames($busca){
        $mes[1]="Enero";
        $mes[2]="Febrero";
        $mes[3]="Marzo";
        $mes[4]="Abril";
        $mes[5]="Mayo";
        $mes[6]="Junio";
        $mes[7]="Julio";
        $mes[8]="Agosto";
        $mes[9]="Septiembre";
        $mes[10]="Octubre";
        $mes[11]="Noviembre";
        $mes[12]="Diciembre";
        return $mes[$busca];
    }
    
    function fnctxtActividades($idempresa){
        $con = Conexion();
        $sqlselect="select txtActividades from informacionempresa where idempresa = " . $idempresa;
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        return $fila["txtActividades"];        
    }
    
    function fnctxtConstitucion($idempresa){
        $con = Conexion();
        $sqlselect="select txtConstitucion from informacionempresa where idempresa = " . $idempresa;
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        return $fila["txtConstitucion"];        
    }    
    
    function fnctxtBasePresentacion($idempresa){
        $con = Conexion();
        $sqlselect="select txtBasePresentacion from informacionempresa where idempresa = " . $idempresa;
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        if (strlen($fila["txtBasePresentacion"])==0){
            $sqlselect="select txtBasePresentacion from informacionempresa where idempresa = 0";
            $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
            $fila = mysql_fetch_assoc($resultselect);           
        }
        return $fila["txtBasePresentacion"];        
    }      

    function fnctxtPoliticasContables($idempresa){
        $con = Conexion();
        $sqlselect="select txtPoliticasContables from informacionempresa where idempresa = " . $idempresa;
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        if (strlen($fila["txtPoliticasContables"])==0){
            $sqlselect="select txtPoliticasContables from informacionempresa where idempresa = 0";
            $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
            $fila = mysql_fetch_assoc($resultselect);           
        }
        return $fila["txtPoliticasContables"];        
    }      
    
    function fncCapital($idempresa, $anodate){
        $resultado = "<table>
                            <tr>
                                 <td><strong> Tipo Capital </strong></td>
                                 <td><strong> Serie </strong></td>
                                 <td><strong> N° Acciones " . $anodate . "</strong></td>
                                 <td><strong> Importe " . $anodate . "</strong></td>
                                 <td><strong> N° Acciones " . ((int)$anodate-1) . "</strong></td>
                                 <td><strong> Importe " . ((int)$anodate-1) . "</strong></td>
                             </tr>";                
        $con = Conexion();
        $sqlselect="select * from informacioncapital where idempresa= " . $idempresa . " and ejercicio = " . $anodate . " order by tipoCapital, tipoSerie";                         
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        if(mysql_num_rows($resultselect)>0){
            do{ 

                $resultado = $resultado . "
                    <tr>
                         <td> " . $fila["tipoCapital"]  . "</td>
                         <td> " . $fila["tipoSerie"]    . "</td>
                         <td> " . $fila["nuAcciones"]    . "</td>
                         <td> " . $fila["nuImporte"]    . "</td>
                         <td> " . fncAcciones($idempresa, ((int)$anodate-1), $fila["tipoCapital"], $fila["tipoSerie"])    . "</td>
                         <td> " . fncImporte($idempresa, ((int)$anodate-1), $fila["tipoCapital"], $fila["tipoSerie"])    . "</td>
                     </tr>";                

                
                
            } while ($fila = mysql_fetch_assoc($resultselect));
        }      
        $resultado = $resultado . " </table>";                
        
        return $resultado;        
    }
    
    function fncAcciones($idempresa, $anodate, $tipoCapital, $tipoSerie){
        $con = Conexion();
        $sqlselect="select nuAcciones from informacioncapital where idempresa = " . $idempresa . " and ejercicio = " . $anodate . " and tipoCapital = '" . $tipoCapital . "' and tipoSerie = '" . $tipoSerie ."'";
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        return $fila["nuAcciones"];        
       
    }

    function fncImporte($idempresa, $anodate, $tipoCapital, $tipoSerie){
        $con = Conexion();
        $sqlselect="select nuImporte from informacioncapital where idempresa = " . $idempresa . " and ejercicio = " . $anodate . " and tipoCapital = '" . $tipoCapital . "' and tipoSerie = '" . $tipoSerie ."'";
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        return $fila["nuImporte"];        
       
    }
    
    
    function fnctxtCapitalContable($idempresa){
        $con = Conexion();
        $sqlselect="select txtCapitalContable from informacionempresa where idempresa = " . $idempresa;
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        if (strlen($fila["txtCapitalContable"])==0){
            $sqlselect="select txtCapitalContable from informacionempresa where idempresa = 0";
            $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
            $fila = mysql_fetch_assoc($resultselect);           
        }
        return $fila["txtCapitalContable"];        
    }        
    
    
    function fnctxtImpuestosUtilidad($idempresa){
        $con = Conexion();
        $sqlselect="select txtImpuestosUtilidad from informacionempresa where idempresa = " . $idempresa;
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        if (strlen($fila["txtImpuestosUtilidad"])==0){
            $sqlselect="select txtImpuestosUtilidad from informacionempresa where idempresa = 0";
            $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
            $fila = mysql_fetch_assoc($resultselect);           
        }
        return $fila["txtImpuestosUtilidad"];        
    }
  
    function fnctxtPronunciamientosContables($idempresa){
        $con = Conexion();
        $sqlselect="select txtPronunciamientosContables from informacionempresa where idempresa = " . $idempresa;
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        if (strlen($fila["txtPronunciamientosContables"])==0){
            $sqlselect="select txtPronunciamientosContables from informacionempresa where idempresa = 0";
            $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
            $fila = mysql_fetch_assoc($resultselect);           
        }
        return $fila["txtPronunciamientosContables"];        
    }     

    function fncbuscasaldocta($idempresa, $idcuenta, $idejercicio,$mes,$tipo)
    {
        $con = Conexion();
        $sqlselect="select * from saldo where idempresa= '" . $idempresa . "' and idcuenta = '" . $idcuenta . "' and ejercicio = '" . $idejercicio . "' and tipo= '" . $tipo."'";                         
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        $saldo=0;
        switch ($mes)
        {
            case 1: $saldo = $fila["importes1"]; break;
            case 2: $saldo = $fila["importes2"]; break;
            case 3: $saldo = $fila["importes3"]; break;
            case 4: $saldo = $fila["importes4"]; break;
            case 5: $saldo = $fila["importes5"]; break;
            case 6: $saldo = $fila["importes6"]; break;
            case 7: $saldo = $fila["importes7"]; break;
            case 8: $saldo = $fila["importes8"]; break;
            case 9: $saldo = $fila["importes9"]; break;
            case 10: $saldo = $fila["importes10"]; break;
            case 11: $saldo = $fila["importes11"]; break;
            case 12: $saldo = $fila["importes12"]; break;
        }
        if ($tipo==4){
            $saldo = fncbuscasaldocta($idempresa, $idcuenta, $idejercicio, $mes, 2) - fncbuscasaldocta($idempresa, $idcuenta, $idejercicio, $mes, 3);
        }elseif ($tipo == 5){
            $saldo = fncbuscasaldocta($idempresa, $idcuenta, $idejercicio, $mes, 2) - fncbuscasaldocta($idempresa, $idcuenta, $idejercicio, $mes, 1);
        }elseif ($tipo==6){
            $saldo = fncbuscasaldocta($idempresa, $idcuenta, $idejercicio, $mes, 3) - fncbuscasaldocta($idempresa, $idcuenta, $idejercicio, $mes, 1);
        }elseif ($tipo==7){
            $saldo = fncbuscasaldocta($idempresa, $idcuenta, $idejercicio, $mes, 3) - fncbuscasaldocta($idempresa, $idcuenta, $idejercicio, $mes, 2);
        }        
        if (empty($saldo)){return 0;}else{return $saldo;};
    }
    
    
    function fncbuscaridejercicio($idempresa, $anno)
    {
        $con = Conexion();
        $sqlselect="select idejercicio from ejercicio where idempresa = " . $idempresa . " and ejercicio= " . $anno;
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        return $fila["idejercicio"];
    }
    function fncbuscaridcuenta($idempresa,$codigocuenta)
    {
        $con = Conexion();
        $sqlselect="select idcuenta from cuenta where idempresa = " . $idempresa . " and codigo= '" . $codigocuenta."'";
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        return $fila["idcuenta"];
    }
  
    function fncnombrecuenta($idcuenta)
    {
        $con = Conexion();
        $sqlselect="select nombre from cuenta where idcuenta= " . $idcuenta;
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        return $fila["nombre"];
     }
    function fncnombresubagrupacionest ($IdAgrupacionEst)
    {
        $con = Conexion();
        $sqlselect="select nombre from agrupacionest where IdAgrupacionEst= " . $IdAgrupacionEst;
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        return $fila["nombre"];
    }
     
    function fncnombresubagrupacion ($posicion)
    {
        $con = Conexion();
        $sqlselect="select nombre from tipoagrupacion where posicion= " . $posicion;
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        return $fila["nombre"];
    }
    function fncnombreagrupacion($idagrupacion){
        $con = Conexion();
        $sqlselect="select nombre from agrupacion where idagrupacion= " . $idagrupacion;
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        return $fila["nombre"];        
    }
    
    function fncnombreagrupacion_nf($idagrupacion){
        $con = Conexion();
        $sqlselect="select nombre from agrupacion_nf where idagrupacion= " . $idagrupacion;
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        return $fila["nombre"];        
    }    
    
    function fncnombreagrupacionest($idagrupacion){
        $con = Conexion();
        $sqlselect="select nombre from agrupacionest where idagrupacionest= " . $idagrupacion;
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        return $fila["nombre"];        
    }
    function fncnombreestadofinanciero($idestadofinanciero){
        $con = Conexion();
        $sqlselect="select nombre from estadosfinancieros where idestadofinanciero= " . $idestadofinanciero;
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        return $fila["nombre"];        
    }
    function fncmaxestructestadofinan($idestadofinanciero){
        $con = Conexion();
        $sqlselect="select max(idestructura) maximo from estructuraestadofinanciero where idestadofinanciero= '" . $idestadofinanciero."'";
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        return $fila["maximo"];        
    }
    
    function fncmaxidagrupacion_nf($idempresa){
        $con = Conexion();
        $sqlselect="select max(idagrupacion) maximo from agrupacion_nf where idempresa =  " . $idempresa;
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        return $fila["maximo"];        
    }
    
     function fncminidagrupacion_nf($idempresa){
        $con = Conexion();
        $sqlselect="select min(idagrupacion) minima from agrupacion_nf where idempresa =   " . $idempresa;
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        return $fila["minima"];        
    }
    
     function fncmaxagrupacion_x_estadofinan($idempresa){
        $con = Conexion();
        $sqlselect="select max(idtipoagrupacion) maximo from agrupacion where idempresa =  " . $idempresa;
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        return $fila["maximo"];        
    }
    
     function fncminagrupacion_x_estadofinan($idempresa){
        $con = Conexion();
        $sqlselect="select min(idtipoagrupacion) minima from agrupacion where idempresa =   " . $idempresa;
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        return $fila["minima"];        
    }    

    function fncminestructestadofinan($idestadofinanciero){
        $con = Conexion();
        $sqlselect="select min(idestructura) minimo from estructuraestadofinanciero where idestadofinanciero= '" . $idestadofinanciero."'";
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        return $fila["minimo"];        
    }
    
    function fncmaxestructuraagrupacion($idestructura){
        $con = Conexion();
        $sqlselect="select max(idtipoagrupacion) maximo from tipoagrupacion where idestructura= " . $idestructura;
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        return $fila["maximo"];        
    }
    
    function fncminestructuraagrupacion($idestructura){
        $con = Conexion();        
        $sqlselect="select min(idtipoagrupacion)  minimo from  tipoagrupacion where idestructura= " . $idestructura;
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        return $fila["minimo"];        
    }  
    function fncnombreestructura($idestructura,$idestadofinanciero){
        $con = Conexion();
        $sqlselect="select nombre from estructuraestadofinanciero where idestructura= " . $idestructura . " and idestadofinanciero= " . $idestadofinanciero;
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        return $fila["nombre"];        
    }
    
    function fncIdTipoAgrupacionEst($idestructura){
        $con = Conexion();
        $sqlselect="select idtipoagrupacionest from tipoagrupacionest where idestructura= " . $idestructura;
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        return $fila["idtipoagrupacionest"];        
    }
        
    function fncmaxestructaasociacion($idtipoagrupacionest){
        $con = Conexion();
        $sqlselect="select max(idagrupacionest) maximo from enasociacioner where idtipoagrupacionest= '" . $idtipoagrupacionest."'";
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        return $fila["maximo"];        
    }

    function fncminestructaasociacion($idtipoagrupacionest){
        $con = Conexion();
        $sqlselect="select min(idagrupacionest) minimo from enasociacioner where idtipoagrupacionest= '" . $idtipoagrupacionest."'";
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        return $fila["minimo"];        
    }   

    function fncNombreTipoAgrupacionEst($idtipoagrupacionest){
        $con = Conexion();
        $sqlselect="select nombre from tipoagrupacionest where idtipoagrupacionest= " . $idtipoagrupacionest;
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        return $fila["nombre"];        
    }
 
    function fnctipoparcial($idagrupacioncuentasest){
        $con = Conexion();
        $sqlselect="select tipop from agrupacioncuentasest where idagrupacioncuentasest= " . $idagrupacioncuentasest;
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        return $fila["tipop"];        
    }
    function fncformatonumero($numero,$decimales){
        return round(number_format($numero,$decimales),0);
    }
    
    
    function fncTieneSaldolaCategoria($idempresa,$idtipoagrupacion){
        $con = Conexion();
        $tienesaldo=false;
        $sqlselect="select * from enasociacion where idempresa= " . $idempresa . " and idtipoagrupacion = " . $idtipoagrupacion . " order by posicion";                         
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        if(mysql_num_rows($resultselect)>0){
            do{
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
                            if ($saldocuenta<>0) {$tienesaldo=true;}
                        } while ($filaagrup = mysql_fetch_assoc($resultselectagrup));
                    }                          
                }elseif (($tipoelemento == "c")){
                    $codigocuenta = $fila["codigocuenta"];
                    $saldocuenta = fncbuscasaldocta($idempresa,fncbuscaridcuenta($idempresa,$codigocuenta),fncbuscaridejercicio($idempresa, $_GET["anno"]),$_GET["mes"],1) * $fila["signo"];
                        if ($saldocuenta<>0) {$tienesaldo=true;}
                }                
            } while ($fila = mysql_fetch_assoc($resultselect));
        }
        return $tienesaldo;
    }

    function elimina_acentos($text)
    {
        $text = htmlentities($text, ENT_QUOTES, 'UTF-8');
        $text = strtolower($text);
        $patron = array (
            // Espacios, puntos y comas por guion
            //'/[\., ]+/' => ' ',
 
            // Vocales
            '/\+/' => '',
            '/&agrave;/' => 'a',
            '/&egrave;/' => 'e',
            '/&igrave;/' => 'i',
            '/&ograve;/' => 'o',
            '/&ugrave;/' => 'u',
 
            '/&aacute;/' => 'a',
            '/&eacute;/' => 'e',
            '/&iacute;/' => 'i',
            '/&oacute;/' => 'o',
            '/&uacute;/' => 'u',
 
            '/&acirc;/' => 'a',
            '/&ecirc;/' => 'e',
            '/&icirc;/' => 'i',
            '/&ocirc;/' => 'o',
            '/&ucirc;/' => 'u',
 
            '/&atilde;/' => 'a',
            '/&etilde;/' => 'e',
            '/&itilde;/' => 'i',
            '/&otilde;/' => 'o',
            '/&utilde;/' => 'u',
 
            '/&auml;/' => 'a',
            '/&euml;/' => 'e',
            '/&iuml;/' => 'i',
            '/&ouml;/' => 'o',
            '/&uuml;/' => 'u',
 
            '/&auml;/' => 'a',
            '/&euml;/' => 'e',
            '/&iuml;/' => 'i',
            '/&ouml;/' => 'o',
            '/&uuml;/' => 'u',
 
            // Otras letras y caracteres especiales
            '/&aring;/' => 'a',
            '/&ntilde;/' => 'n',
 
            // Agregar aqui mas caracteres si es necesario
 
        );
 
        $text = preg_replace(array_keys($patron),array_values($patron),$text);
        return $text;
    }    
    function fncPrimeramayuscula($cadena){
        return ucfirst(strtolower(elimina_acentos($cadena)));
    }    
          
    function fncDevuelveXf($CantidadMeses){
        switch($CantidadMeses){
            case 1: $Xf=105; break;
            case 2: $Xf=120;  break;
            case 3: $Xf=136;  break;
            case 4: $Xf=151;  break;
            case 5: $Xf=165;  break;
            case 6: $Xf=179; break;
            case 7: $Xf=193 ; break;
            case 8: $Xf=207; break;
            case 9: $Xf=223; break;
            case 10: $Xf=243; break;
            case 11: $Xf=259; break;
            case 12: $Xf=278; break;     
            }
        return $Xf;
    }
            
 ?>