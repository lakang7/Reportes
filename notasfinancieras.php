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
            $sqlGENERAL="SELECT * FROM informacionempresa where idEmpresa='".$_GET["idempresa"]."'";
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
                    <form method="post" id="form_updateEspecifico" action="recursos/acciones.php?tarea=51&id=<?php echo $_GET["idempresa"]; ?>">
                        <div class="col-md-12 titulopagina" style="margin-top: 125px;">Notas Financieras</div>
                    <div class="col-md-12 subtitulopagina">
                        Definición de las Notas Financieras
                    </div>
                    <div class="col-md-12 contiene_entrada">
                        <div class="col-md-12 titulo_entrada">Actividades</div>
                        <div class="col-md-12"><textarea style="width: 100%" rows="3" id="actividades" name="actividades" ><?php echo $GENERAL["txtActividades"] ?></textarea></div>
                    </div>                        
                    <div class="col-md-12 contiene_entrada">
                        <div class="col-md-12 titulo_entrada">Fecha de Constitución y Operación</div>
                        <div class="col-md-12"><textarea style="width: 100%" rows="3" id="constitucion" name="constitucion" ><?php echo $GENERAL["txtConstitucion"] ?></textarea></div>
                    </div>    
                    <div class="col-md-12 contiene_entrada">
                        <div class="col-md-12 titulo_entrada">Capital Contable</div>
                        <div class="col-md-12"><textarea style="width: 100%" rows="3" id="capitalcontable" name="capitalcontable"><?php echo $GENERAL["txtCapitalContable"] ?></textarea></div>
                    </div> 
                        <div class="col-md-12 contiene_entrada" style="margin-left: 15px">
                        <table border="0" style="width: 97%; font-family: 'Yanone Kaffeesatz', sans-serif; font-size: 18px">
                            <tr>
                                <td>Ejercicio</td>
                                <td>Tipo de Capital</td>
                                <td>Tipo de Serie</td>
                                <td>Numero de Acciones</td>
                                <td>Importe</td>
                            </tr>
                            
                            <?php
                            $cuenta=1;
                                $sqlGENERAL="SELECT * FROM informacioncapital where idempresa='".$_GET["idempresa"]."'";
                                $resultGENERAL=mysql_query($sqlGENERAL,$con) or die(mysql_error());                                
                                if(mysql_num_rows($resultGENERAL)>0){
                                    while ($general = mysql_fetch_assoc($resultGENERAL)) {
                                        echo "<tr style='height: 35px'>";
                                        echo "<td>";
                                        echo "<select id='ejercicio".$cuenta."' name='ejercicio".$cuenta."' style='width: 90%'>";
                                        if($general["ejercicio"]=="2015"){
                                            echo "<option value='2015' selected='selected'>2015</option>";
                                        }else{
                                            echo "<option value='2015'>2015</option>";
                                        }
                                        if($general["ejercicio"]=="2016"){
                                            echo "<option value='2016' selected='selected'>2016</option>";
                                        }else{
                                            echo "<option value='2016'>2016</option>";
                                        }                                        
                                        if($general["ejercicio"]=="2017"){
                                            echo "<option value='2017' selected='selected'>2017</option>";
                                        }else{
                                            echo "<option value='2017'>2017</option>";
                                        }                                  
                                        if($general["ejercicio"]=="2018"){
                                            echo "<option value='2018' selected='selected'>2018</option>";
                                        }else{
                                            echo "<option value='2018'>2018</option>";
                                        }                                  
                                        if($general["ejercicio"]=="2019"){
                                            echo "<option value='2019' selected='selected'>2019</option>";
                                        }else{
                                            echo "<option value='2019'>2019</option>";
                                        }                                  
                                        if($general["ejercicio"]=="2020"){
                                            echo "<option value='2020' selected='selected'>2020</option>";
                                        }else{
                                            echo "<option value='2020'>2020</option>";
                                        }                                        
                                        echo "</select>";
                                        echo "</td>";
                                        echo "<td>";
                                        echo "<select id='tipocapital".$cuenta."' name='tipocapital".$cuenta."' style='width: 90%'>";
                                        if($general["tipoCapital"]=="Fijo"){
                                            echo "<option value='Fijo' selected='selected'>Fijo</option>";
                                            echo "<option value='Variable'>Variable</option>";                                            
                                        }else{
                                            echo "<option value='Fijo'>Fijo</option>";
                                            echo "<option value='Variable' selected='selected'>Variable</option>";                                            
                                        }

                                        echo "</select>";
                                        echo "</td>";
                                        echo "<td>";
                                        echo "<input id='tiposerie".$cuenta."' name='tiposerie".$cuenta."' type='text' style='width: 95%' value='".$general["tipoSerie"]."' />";
                                        echo "</td>";
                                        echo "<td>";
                                        echo "<input id='numeroacciones".$cuenta."' name='numeroacciones".$cuenta."' type='text' style='width: 95%' value='".$general["nuAcciones"]."' />";
                                        echo "</td>";
                                        echo "<td>";
                                        echo "<input id='importe".$cuenta."' name='importe".$cuenta."' type='text' style='width: 95%' value='".$general["nuImporte"]."' />";
                                        echo "</td>";                           
                                        echo "</tr>";
                                        $cuenta++;
                                    }   
                                }
                                for($i=$cuenta;$i<($cuenta+2);$i++){
                                        echo "<tr style='height: 35px'>";
                                        echo "<td>";
                                        echo "<select id='ejercicio".$i."' name='ejercicio".$i."' style='width: 90%'>";
                                        echo "<option value='2015'>2015</option>";
                                        echo "<option value='2016'>2016</option>";                                      
                                        echo "<option value='2017'>2017</option>";                                 
                                        echo "<option value='2018'>2018</option>";
                                        echo "<option value='2019'>2019</option>";                                                                       
                                        echo "<option value='2020'>2020</option>";                                      
                                        echo "</select>";
                                        echo "</td>";
                                        echo "<td>";
                                        echo "<select id='tipocapital".$i."' name='tipocapital".$i."' style='width: 90%'>";
                                        echo "<option value='Fijo' selected='selected'>Fijo</option>";
                                        echo "<option value='Variable'>Variable</option>";                                            
                                        echo "</select>";
                                        echo "</td>";
                                        echo "<td>";
                                        echo "<input id='tiposerie".$i."' name='tiposerie".$i."' type='text' style='width: 95%' />";
                                        echo "</td>";
                                        echo "<td>";
                                        echo "<input id='numeroacciones".$i."' name='numeroacciones".$i."' type='text' style='width: 95%' />";
                                        echo "</td>";
                                        echo "<td>";
                                        echo "<input id='importe".$i."' name='importe".$i."' type='text' style='width: 95%' />";
                                        echo "</td>";                           
                                        echo "</tr>";                                    
                                }
                                
                                
                            ?>                            
                        </table>
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
                CKEDITOR.replace( 'actividades' );
                CKEDITOR.replace( 'constitucion' );
                CKEDITOR.replace( 'capitalcontable' );
            </script>        
</html>
