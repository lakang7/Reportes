<?php session_start(); 
    if (!isset($_SESSION['administrador'])){
        ?>
            <script type="text/javascript" language="JavaScript" >                
                location.href="index.php";
            </script>
        <?php        
    }
?>
<html>
    <head>
        <meta charset="UTF-8">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
        <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>   
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link href='https://fonts.googleapis.com/css?family=Yanone+Kaffeesatz' rel='stylesheet' type='text/css'>
        <link href="recursos/administracion.css" rel='stylesheet' type='text/css'>
        <title>Listar | Agrupaciones</title>        
        <script type="text/javascript" language="JavaScript" >
            function redirigir(direccion){
                location.href=direccion;
            }		
        </script>        
        <?php
            header('Content-Type: text/html; charset=UTF-8');        
            require_once("funciones/funciones.php");
        ?>            
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-3" style="border-right: 0px solid #CCCCCC">                    
                    <div class="col-md-12" style="margin-bottom: 20px;"><img class="img-responsive center-block" src="recursos/logo300px.png"></div>
                    <?php Menu(); ?>
                </div>
                <div class="col-md-9">
                    <form method="post" id="form_CREARcomposicionquimica" action="recursos/acciones.php?tarea=1">
                        <div class="col-md-12 titulopagina" style="margin-top: 125px;">Agrupaciones Notas Financieras</div>                                                                                                                                                
                    <div class="col-md-12">
                        <div class="btn-group" role="group" aria-label="...">
                            <button type="button" onclick=redirigir("insertagrupacionnf.php?idempresa=<?php echo $_GET["id"]; ?>") class="btn btn-default boton">Crear Nuevo Elemento +</button>
                            <button type="button" onclick=redirigir("listaragrupacionnf.php?id=<?php echo $_GET["id"]; ?>")  class="btn btn-default boton">Listar Elementos</button>
                        </div>
                    </div>
                    <div class="col-md-12 subtitulopagina">
                        Lista de Elementos
                    </div>
                    <div class="col-md-12">
                    
                        <div class="panel panel-default">
                            <table class="table table-striped" style="font-family: 'Yanone Kaffeesatz', sans-serif; font-size: 16px;">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">ID</th>
                                        <th style="width: 57%">Nombre</th>
                                        <th style="width: 33%">Acciones</th>
                                    </tr>
                                </thead>
    
                                <tbody style="border-top: 0px;">
                                    <?php
                                        $con=Conexion();
                                        $sql_listaAgrupacion="select agrupacion_nf.idagrupacion, agrupacion_nf.nombre as agrupa from agrupacion_nf where idempresa='".$_GET["id"]."' order by agrupacion_nf.idagrupacion";
					$result_listaAgrupacion=mysql_query($sql_listaAgrupacion,$con) or die(mysql_error()); 
                                        
                                        
                                        if(mysql_num_rows($result_listaAgrupacion)>0){
                                            while ($fila = mysql_fetch_assoc($result_listaAgrupacion)) {
                                                echo "<div id=\"myModal".$fila["idagrupacion"]."\" class=\"modal fade\"><div class=\"modal-dialog\"><div class=\"modal-content\"><div class=\"modal-header\"><button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button><h4 class=\"modal-title\">Confimación de Elminación</h4></div><div class=\"modal-body\"><p>¿Esta seguro de que desea elminar el elemento ".trim($fila["agrupa"])."?</p></div><div class=\"modal-footer\"><button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Cancelar</button><button type=\"button\" class=\"btn btn-primary\" onclick=eliminar(\"".$fila["idagrupacion"]."\")>Eliminar Elemento</button></div></div></div></div>";
                                                echo "<tr>";
                                                echo "<td style='width:  5%'>".$fila["idagrupacion"]."</td>";
                                                echo "<td style='width: 57%'>".$fila["agrupa"]."</td>";
                                                echo "<td style='width: 33%'>";
                                                
                                                echo "<div class='dropdown'>";
                                                echo "<button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>";
                                                echo "Acciones";
                                                echo "<span class='caret'></span>";
                                                echo "</button>";
                                                echo "<ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>";
                                                echo "<li><a href='editaragrupacionnf.php?id=".$fila["idagrupacion"]."'><span class='glyphicon glyphicon-pencil' aria-hidden='true' style='margin-right: 5px;'></span>Editar</a></li>";
                                                echo "<li onclick=confirmar(\"".trim($fila["idagrupacion"])."\")><a href='#'><span class='glyphicon glyphicon-remove' aria-hidden='true' style='margin-right: 5px;'></span>Eliminar</a></li>";
                                                echo "</ul>";
                                                echo "</div>";                                                                                                              
                                                echo "</td>";       
                                                echo "</tr>";                                                    
                                            }
					}	                                                                                
                                    ?>
                                </tbody>    
                            </table>
                        </div>
                        
                     
                                            
                    </div>

                    </form>
                </div>
            </div>
        </div>                 
    </body>
    <script type="text/javascript">  
        $('.dropdown-toggle').dropdown();
        function confirmar(id){                
            $("#myModal"+id).modal('show');
        }   
        function eliminar(id){
            location.href="recursos/acciones.php?tarea=53&id="+id;
        }
    </script>     
</html>
