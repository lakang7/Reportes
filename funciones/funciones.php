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
        echo "<div class='col-md-12 itemMenu' onclick=redirigir('reporteestadoderesultados_pantalla.php') >Reporte Estado de Resultado Mensualizado</div>";
        echo "<div class='col-md-12 itemMenu' onclick=redirigir('reporterazonesfinancieras.php') >Reporte Razones Financieras</div>";
        echo "<div class='col-md-12 itemMenu' onclick=redirigir('reporteestadoderesultados.php') style='border-bottom: 1px solid #CCCCCC' >Reporte Estado de Resultados</div>";
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

    function fncbuscasaldocta($idempresa, $idcuenta, $idejercicio,$mes,$tipo)
    {
        $con = Conexion();
        $sqlselect="select * from saldo where idempresa= '" . $idempresa . "' and idcuenta = '" . $idcuenta . "' and ejercicio = '" . $idejercicio . "' and tipo= '" . $tipo."'";                         
        //echo $sqlselect."</br>";
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
            
        return $saldo;
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
    function fncmaxestructuraagrupacion($idestructura){
        $con = Conexion();
        $sqlselect="select max(idtipoagrupacion) maximo from tipoagrupacion where idestructura= " . $idestructura;
        $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultselect);
        return $fila["maximo"];        
    }
    function fncminestructuraagrupacion($idestructura){
        $con = Conexion();
        $sqlselect="select min(idtipoagrupacion) minimo from tipoagrupacion where idestructura= " . $idestructura;
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
        
?>