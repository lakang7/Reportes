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
        <title>Listar | Claves Empresa Registrada</title>        
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
                <div class="col-md-9" style="padding-bottom: 10px;">
                    <form method="post" id="form_CREARcomposicionquimica" action="recursos/acciones.php?tarea=5&empresa=<?php echo $_GET["id"]; ?>">
                        <div class="col-md-12 titulopagina" style="margin-top: 125px;">Empresas</div>
                    <div class="col-md-12">
                        <div class="btn-group" role="group" aria-label="...">
                            <button type="button" onclick=redirigir("insertempresa.php") class="btn btn-default boton">Crear Nuevo Elemento +</button>
                            <button type="button" onclick=redirigir("listarempresa.php")  class="btn btn-default boton">Listar Elementos</button>
                        </div>
                    </div>
                    <div class="col-md-12 subtitulopagina">
                        Cuentas claves para el calculo de las razones financieras
                    </div>
                    <div class="col-md-12">
                    <?php
                        $con=Conexion();
                        $sql_listaClave="select * from clave order by nombre";
			$result_listaClave=mysql_query($sql_listaClave,$con) or die(mysql_error());                                         
                                        
                        if(mysql_num_rows($result_listaClave)>0){
                            while ($fila = mysql_fetch_assoc($result_listaClave)) {
                                echo "<div class='col-md-12' style='border: 0px solid #CCCCCC; padding: 0px; margin-bottom: 5px;'>";
                                echo "<div class='col-md-4' style='padding: 0px'><input type='text' class='form-control' id='identificador' name='identificador' maxlength='30' value='".$fila["nombre"]."' disabled='enabled' /></div>";                                
                                $sqlCuenta="select * from asociaclave where idempresa='".$_GET["id"]."' and idclave='".$fila["idclave"]."'";
                                $resultCuenta=mysql_query($sqlCuenta,$con) or die(mysql_error());
                                $cont01=0;
                                if(mysql_num_rows($resultCuenta)>0){
                                    while ($cuenta = mysql_fetch_assoc($resultCuenta)) {                                        
                                        echo "<div class='col-md-2' style='padding: 0px;padding-left: 5px;'><input type='text' class='form-control' value='".$cuenta["codigo"]."'  id='codigo_".$_GET["id"]."_".$fila["idclave"]."_".$cont01."' name='codigo_".$_GET["id"]."_".$fila["idclave"]."_".$cont01."' maxlength='20' /></div>";
                                        $cont01++;
                                    }
                                }
                                if($cont01<3){
                                    for($i=$cont01;$i<4;$i++){
                                        echo "<div class='col-md-2' style='padding: 0px;padding-left: 5px;'><input type='text' class='form-control' id='codigo_".$_GET["id"]."_".$fila["idclave"]."_".$i."' name='codigo_".$_GET["id"]."_".$fila["idclave"]."_".$i."' maxlength='20' /></div>";
                                    }
                                }
                                
                                echo "</div>";
                            }                                               
                        }
                    ?>
                                                                                                              
                    </div>
                    <div class="col-md-12" style="padding-left: 0px; margin-top: 5px">
                        <div class="col-md-12"><button type="submit" class="btn btn-default">Actualizar</button></div>                        
                    </div>                        
                    </form>
                </div>
            </div>
        </div>                 
    </body>
    <script type="text/javascript">                    
        function confirmar(id){                
            $("#myModal"+id).modal('show');
        }   
        function eliminar(id){
            location.href="recursos/acciones.php?tarea=4&id="+id;
        }
    </script>     
</html>
