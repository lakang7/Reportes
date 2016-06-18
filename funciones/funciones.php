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
        echo "<div class='col-md-12 itemMenu' onclick=redirigir('reporterazonesfinancieras.php') >Reporte Razones Financieras</div>";
        echo "<div class='col-md-12 itemMenu' onclick=redirigir('reporteestadoderesultados.php') style='border-bottom: 1px solid #CCCCCC' >Reporte Estado de Resultados</div>";
    }        


?>