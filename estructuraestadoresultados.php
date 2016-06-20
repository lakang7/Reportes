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
        
        <link rel="stylesheet" href="recursos/dist/css/bootstrap-select.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <script src="recursos/dist/js/bootstrap-select.js"></script>        
        
        <title>Insertar | Empresas Registradas</title>
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
                    <form method="post" id="form_CREARfambotanica" action="recursos/acciones.php?tarea=18">
                        
                        <div class="col-md-12 titulopagina" style="margin-top: 125px;">Estructura Estado de Resultados</div>

                        <div class="col-md-12 subtitulopagina" style="padding-bottom: 20px">
                            <input type="text" id="empresa" name="empresa" value="<?php echo $_GET["id"] ?>" />
                            <?php
                            $con = Conexion();
                            $sqlTipos="select * from tipoagrupacionest order by idtipoagrupacionest";
                            $resultTipos=mysql_query($sqlTipos,$con) or die(mysql_error()); 
                            if(mysql_num_rows($resultTipos)>0){
                                while ($tipo = mysql_fetch_assoc($resultTipos)) {
                                    echo "<input type='text' id='seleccionados".$tipo["idtipoagrupacionest"]."' name='seleccionados".$tipo["idtipoagrupacionest"]."' value=''/>";
                                }                            
                            }                            
                            ?>
                            
                        </div>                                                                                                                    
                        
                        <?php
                            
                            $sqlTipos="select * from tipoagrupacionest order by idtipoagrupacionest";
                            $resultTipos=mysql_query($sqlTipos,$con) or die(mysql_error()); 
                            if(mysql_num_rows($resultTipos)>0){
                                while ($tipo = mysql_fetch_assoc($resultTipos)) {                                   
                                    echo "<div class='col-md-12 contiene_entrada' style='margin-bottom: 50px;'> ";
                                    echo "<div class='col-md-12 titulo_entrada' style='padding: 0px; font-size:4ex;'>".$tipo["nombre"]."</div>";
                                    echo "<div class='col-md-5 caja'>";
                                    echo "<div class='col-md-12 titulo_entrada' style='padding: 0px'>Seleccione las Cuentas dentro de esta Sección</div>";
                                    echo "<div class='col-md-12 cajaseleccion' id='cajaseleccion".$tipo["idtipoagrupacionest"]."'>";
                                    
                                    $sql_listaAgrupaciones="select * from agrupacionest where idempresa='".$_GET["id"]."' and idtipoagrupacionest='".$tipo["idtipoagrupacionest"]."' order by trim(nombre)";
                                    $result_listaAgrupacion=mysql_query($sql_listaAgrupaciones,$con) or die(mysql_error());                                                                                 
                                    if(mysql_num_rows($result_listaAgrupacion)>0){
                                        while ($agrupacion = mysql_fetch_assoc($result_listaAgrupacion)) {
                                            echo "<div class='col-md-12 elementoseleccion' id='izqc-".$tipo["idtipoagrupacionest"]."-".$agrupacion["idagrupacionest"]."' name='izqc-".$tipo["idtipoagrupacionest"]."-".$agrupacion["idagrupacionest"]."' onclick=seleccionaizqc(".$agrupacion["idagrupacionest"].",".$tipo["idtipoagrupacionest"].")>";
                                            echo "<div class='col-md-12 lineapequena'>AGRUPACION</div>";
                                            echo "<div class='col-md-12 lineagrande'>".$agrupacion["nombre"]."</div>";
                                            echo "</div>";                                                                                                                                           
                                        }   
                                    }                                    
                                                                        
                                    echo "</div>";                                                                       
                                    echo "</div>";
                                    echo "<div class='col-md-1'>";                                
                                    echo "</div>";
                                    echo "<div class='col-md-5 cajaselecciond' id='cajaderecha".$tipo["idtipoagrupacionest"]."'>";
                                    echo "</div>";
                                    echo "</div>";                                                                                                          
                                }
                            }
                        ?>
                                                
                        <div class="col-md-12 contiene_entrada" style="padding: 0px">
                            <div class="col-md-12"><button type="submit" class="btn btn-default">Submit</button></div>                        
                        </div>                        
                        
                        <script type="text/javascript">
                            function seleccionaizqc(id,tipo){
                                var valor=document.getElementById("seleccionados"+tipo).value;
                                document.getElementById("seleccionados"+tipo).value=valor+"_"+id;
                                $("#cajaderecha"+tipo).load("recursos/ajax.php", {tarea:7, seleccionados: document.getElementById("seleccionados"+tipo).value , empresa: document.getElementById("empresa").value, panel: tipo}, function(){
                                    $("#izqc-"+tipo+"-"+id).remove();
                                });
                            }                            
                            
                            function seleccionaderc(id,tipo){
                                var valor=document.getElementById("seleccionados"+tipo).value;
                                var lista = valor.split("_");                               
                                var nuevovalor="";
                                for(var i=0;i<lista.length;i++){
                                    if(lista[i]!=""){
                                        if(lista[i]!=id){  
                                            nuevovalor=nuevovalor+"_"+lista[i];                                            
                                        }                                                                                 
                                    }                                                                                                            
                                }
                                document.getElementById("seleccionados"+tipo).value=nuevovalor;
                                $("#cajaseleccion"+tipo).load("recursos/ajax.php", {tarea:8, seleccionados: document.getElementById("seleccionados"+tipo).value , empresa: document.getElementById("empresa").value, panel:tipo}, function(){
                                    $("#der-"+tipo+"-"+id).remove();
                                });                              
                            }                                                     
                            
                            function subir(clave,tipo){
                                var indice=-1;
                                var valor=document.getElementById("seleccionados"+tipo).value;
                                var lista = valor.split("_");
                                var nuevovalor="";
                                for(var i=0;i<lista.length;i++){
                                    if(lista[i]!=""){
                                        if(lista[i]==clave){
                                            indice=i;
                                        }                                                                                                                        
                                    }
                                }
                                if(indice>0){
                                    var temporal = lista[indice];
                                    lista[indice] = lista[(indice-1)];
                                    lista[(indice-1)] = temporal;
                                    for(var i=0;i<lista.length;i++){
                                        if(lista[i]!=""){
                                            nuevovalor=nuevovalor+"_"+lista[i];
                                        }                                        
                                    }
                                }
                                document.getElementById("seleccionados"+tipo).value=nuevovalor;
                                $("#cajaderecha"+tipo).load("recursos/ajax.php", {tarea:7, seleccionados: document.getElementById("seleccionados"+tipo).value , empresa: document.getElementById("empresa").value, panel: tipo}, function(){});                            
                            }
                            
                            function bajar(clave,tipo){
                                var indice=-1;
                                var valor=document.getElementById("seleccionados"+tipo).value;
                                var lista = valor.split("_");
                                var nuevovalor="";
                                for(var i=0;i<lista.length;i++){
                                    if(lista[i]!=""){
                                        if(lista[i]==clave){
                                            indice=i;
                                        }                                                                                                                        
                                    }
                                }                                
                                if(indice<(lista.length-1)){
                                    var temporal = lista[indice];
                                    lista[indice] = lista[(indice+1)];
                                    lista[(indice+1)] = temporal;
                                    for(var i=0;i<lista.length;i++){
                                        if(lista[i]!=""){
                                            nuevovalor=nuevovalor+"_"+lista[i];
                                        }                                        
                                    }
                                    document.getElementById("seleccionados"+tipo).value=nuevovalor;
                                }
                                
                                $("#cajaderecha"+tipo).load("recursos/ajax.php", {tarea:7, seleccionados: document.getElementById("seleccionados"+tipo).value , empresa: document.getElementById("empresa").value, panel: tipo}, function(){});                                                               
                            }
                            
                        </script>
                    </form>
                </div>
            </div>
        </div>                 
    </body>
</html>
