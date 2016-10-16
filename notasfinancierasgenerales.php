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
        <script type="text/javascript" src="../bootstrap/js/bootstrap.js"></script>
        <script type="text/javascript" src="../bootstrap/js/bootstrap.min.js"></script>   
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link href='https://fonts.googleapis.com/css?family=Yanone+Kaffeesatz' rel='stylesheet' type='text/css'>
        <link href="recursos/administracion.css" rel='stylesheet' type='text/css'>
        <title>Insertar | Empresas Registradas</title>
        <script type="text/javascript" language="JavaScript" >
            function redirigir(direccion){
                location.href=direccion;
            }		
        </script>        
        <script src="recursos/ckeditor/ckeditor.js"></script>
        
        
        <?php
            header('Content-Type: text/html; charset=UTF-8');        
            require_once("funciones/funciones.php");
            $con= Conexion();
            $sqlGENERAL="SELECT * FROM informacionempresa where idEmpresa='0'";
            $resultGENERAL=mysql_query($sqlGENERAL,$con) or die(mysql_error());
            if(mysql_num_rows($resultGENERAL)>0){        
                $GENERAL=mysql_fetch_assoc($resultGENERAL); 
            }
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
                    <form method="post" id="form_updateEspecifico" action="recursos/acciones.php?tarea=52&id=0">
                        <div class="col-md-12 titulopagina" style="margin-top: 125px;">Notas Financieras</div>
                    <div class="col-md-12 subtitulopagina">
                        Definición de las Notas Financieras
                    </div>
                    <div class="col-md-12 contiene_entrada">
                        <div class="col-md-12 titulo_entrada">Base de Presentación</div>
                        <div class="col-md-12"><textarea style="width: 100%" rows="3" id="basep" name="basep" ><?php echo $GENERAL["txtBasePresentacion"] ?></textarea></div>
                    </div>                        
                    <div class="col-md-12 contiene_entrada">
                        <div class="col-md-12 titulo_entrada">Politicas Contables</div>
                        <div class="col-md-12"><textarea style="width: 100%" rows="3" id="politicas" name="politicas" ><?php echo $GENERAL["txtPoliticasContables"] ?></textarea></div>
                    </div>    
                    <div class="col-md-12 contiene_entrada">
                        <div class="col-md-12 titulo_entrada">Impuestos Utilidad</div>
                        <div class="col-md-12"><textarea style="width: 100%" rows="3" id="impuestos" name="impuestos"><?php echo $GENERAL["txtImpuestosUtilidad"] ?></textarea></div>
                    </div>         
                    <div class="col-md-12 contiene_entrada">
                        <div class="col-md-12 titulo_entrada">Pronunciamientos Contables</div>
                        <div class="col-md-12"><textarea style="width: 100%" rows="3" id="pronunciamientos" name="pronunciamientos"><?php echo $GENERAL["txtPronunciamientosContables"] ?></textarea></div>
                    </div>                         
                    <div class="col-md-12 contiene_entrada">
                        <div class="col-md-12"><button type="submit" class="btn btn-default">Submit</button></div>                        
                    </div>
                    </form>
                </div>
            </div>
        </div>                 
    </body>
            <script>
                CKEDITOR.replace( 'basep' );
                CKEDITOR.replace( 'politicas' );
                CKEDITOR.replace( 'impuestos' );
                CKEDITOR.replace( 'pronunciamientos' );
            </script>        
</html>
