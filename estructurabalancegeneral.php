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
                    <form method="post" id="form_CREARfambotanica" action="recursos/acciones.php?tarea=15">
                        
                        <div class="col-md-12 titulopagina" style="margin-top: 125px;">Estructura Balance General</div>

                        <div class="col-md-12 subtitulopagina" style="padding-bottom: 20px">
                            <input type="hidden" id="empresa" name="empresa" value="<?php echo $_GET["id"] ?>" />
                            <?php
                            $con = Conexion();
                            $sqlTipos="select * from tipoagrupacion order by idtipoagrupacion";
                            $resultTipos=mysql_query($sqlTipos,$con) or die(mysql_error()); 
                            if(mysql_num_rows($resultTipos)>0){
                                while ($tipo = mysql_fetch_assoc($resultTipos)) {
                                    echo "<input type='hidden' id='seleccionados".$tipo["idtipoagrupacion"]."' name='seleccionados".$tipo["idtipoagrupacion"]."' value=''/>";
                                }                            
                            }                            
                            ?>
                            
                        </div>                                                                                                                    
                        
                        <?php
                            
                            $sqlTipos="select * from tipoagrupacion order by idtipoagrupacion";
                            $resultTipos=mysql_query($sqlTipos,$con) or die(mysql_error()); 
                            if(mysql_num_rows($resultTipos)>0){
                                while ($tipo = mysql_fetch_assoc($resultTipos)) {                                   
                                    echo "<div class='col-md-12 contiene_entrada' style='margin-bottom: 50px;'> ";
                                    echo "<div class='col-md-12 titulo_entrada' style='padding: 0px; font-size:4ex;'>".$tipo["nombre"]."</div>";
                                    echo "<div class='col-md-5 caja'>";
                                    echo "<div class='col-md-12 titulo_entrada' style='padding: 0px'>Seleccione las Cuentas dentro de esta Sección</div>";
                                    echo "<div class='col-md-12 cajaseleccionarriba' id='cajaseleccionarriba".$tipo["idtipoagrupacion"]."'>";
                                    
                                    $sql_listaCuenta="select * from cuenta where idempresa='".$_GET["id"]."' order by trim(nombre)";
                                    $result_listaCuenta=mysql_query($sql_listaCuenta,$con) or die(mysql_error());                                                                                 
                                    if(mysql_num_rows($result_listaCuenta)>0){
                                        while ($cuenta = mysql_fetch_assoc($result_listaCuenta)) {
                                            if($cuenta["ctamayor"]==1){
                                                echo "<div class='col-md-12 elementoseleccionmayor' id='izqc-".$tipo["idtipoagrupacion"]."-".$cuenta["idcuenta"]."' name='izqc-".$tipo["idtipoagrupacion"]."-".$cuenta["idcuenta"]."' onclick=seleccionaizqc(".$cuenta["idcuenta"].",".$tipo["idtipoagrupacion"].")>";
                                                echo "<div class='col-md-12 lineapequena'>".$cuenta["codigo"]."</div>";
                                                echo "<div class='col-md-12 lineagrande'>".$cuenta["nombre"]."</div>";
                                                echo "</div>";                                                 
                                            }else{
                                                echo "<div class='col-md-12 elementoseleccion' id='izqc-".$tipo["idtipoagrupacion"]."-".$cuenta["idcuenta"]."' name='izqc-".$tipo["idtipoagrupacion"]."-".$cuenta["idcuenta"]."' onclick=seleccionaizqc(".$cuenta["idcuenta"].",".$tipo["idtipoagrupacion"].")>";
                                                echo "<div class='col-md-12 lineapequena'>".$cuenta["codigo"]."</div>";
                                                echo "<div class='col-md-12 lineagrande'>".$cuenta["nombre"]."</div>";
                                                echo "</div>";                                                
                                            }                                               
                                        }   
                                    }                                    
                                                                        
                                    echo "</div>";
                                    echo "<div class='col-md-12 titulo_entrada' style='padding: 0px;margin-top: 10px;'>Seleccione las Agrupaciónes dentro de esta Sección</div>";
                                    echo "<div class='col-md-12 cajaseleccionabajo' id='cajaseleccionabajo".$tipo["idtipoagrupacion"]."'>";
                                    
                                    $sql_listaAgrupacion="select * from agrupacion where idempresa='".$_GET["id"]."' and idtipoagrupacion='".$tipo["idtipoagrupacion"]."' order by trim(nombre)";
                                    $result_listaAgrupacion=mysql_query($sql_listaAgrupacion,$con) or die(mysql_error());                                                                                 
                                    if(mysql_num_rows($result_listaAgrupacion)>0){
                                        while ($agrupacion = mysql_fetch_assoc($result_listaAgrupacion)) {
                                                echo "<div class='col-md-12 elementoseleccion' id='izqa-".$tipo["idtipoagrupacion"]."-".$agrupacion["idagrupacion"]."' name='izqa-".$tipo["idtipoagrupacion"]."-".$agrupacion["idagrupacion"]."' onclick=seleccionaizqa(".$agrupacion["idagrupacion"].",".$tipo["idtipoagrupacion"].")>";
                                                echo "<div class='col-md-12 lineapequena'>AGRUPACION</div>";
                                                echo "<div class='col-md-12 lineagrande'>".$agrupacion["nombre"]."</div>";
                                                echo "</div>";                                             
                                        }                                    
                                    }                                    
                                    
                                    echo "</div>";                                                                        
                                    echo "</div>";
                                    echo "<div class='col-md-1'>";                                
                                    echo "</div>";
                                    echo "<div class='col-md-5 cajaselecciond' id='cajaderecha".$tipo["idtipoagrupacion"]."'>";
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
                                document.getElementById("seleccionados"+tipo).value=valor+"_c-"+id+"-1";
                                $("#cajaderecha"+tipo).load("recursos/ajax.php", {tarea:3, seleccionados: document.getElementById("seleccionados"+tipo).value , empresa: document.getElementById("empresa").value, panel: tipo}, function(){
                                    $("#izqc-"+tipo+"-"+id).remove();
                                });
                            }
                            function seleccionaizqa(id,tipo){
                                var valor=document.getElementById("seleccionados"+tipo).value;
                                document.getElementById("seleccionados"+tipo).value=valor+"_a-"+id;
                                $("#cajaderecha"+tipo).load("recursos/ajax.php", {tarea:3, seleccionados: document.getElementById("seleccionados"+tipo).value , empresa: document.getElementById("empresa").value, panel: tipo}, function(){
                                    $("#izqa-"+tipo+"-"+id).remove();
                                });
                            }                            
                            
                            function seleccionaderc(id,tipo){
                                var valor=document.getElementById("seleccionados"+tipo).value;
                                var lista = valor.split("_");                               
                                var nuevovalor="";
                                for(var i=0;i<lista.length;i++){
                                    if(lista[i]!=""){
                                        var aux = lista[i].split("-");
                                        if(aux[0]=="c"){                                             
                                            if(aux[1]!=id){  
                                                nuevovalor=nuevovalor+"_"+lista[i];                                            
                                            }                                            
                                        }else{
                                            nuevovalor=nuevovalor+"_"+lista[i];
                                        }
                                    }
                                }
                                document.getElementById("seleccionados"+tipo).value=nuevovalor;
                                $("#cajaseleccionarriba"+tipo).load("recursos/ajax.php", {tarea:4, seleccionados: document.getElementById("seleccionados"+tipo).value , empresa: document.getElementById("empresa").value, panel:tipo}, function(){
                                    $("#der-"+tipo+"-"+id).remove();
                                });                               
                            }
                            
                            function seleccionadera(id,tipo){
                                var valor=document.getElementById("seleccionados"+tipo).value;
                                var lista = valor.split("_");                               
                                var nuevovalor="";
                                for(var i=0;i<lista.length;i++){
                                    if(lista[i]!=""){
                                        var aux = lista[i].split("-");
                                        if(aux[0]=="a"){                                             
                                            if(aux[1]!=id){  
                                                nuevovalor=nuevovalor+"_"+lista[i];                                            
                                            }                                            
                                        }else{
                                            nuevovalor=nuevovalor+"_"+lista[i];
                                        }
                                    }
                                }
                                document.getElementById("seleccionados"+tipo).value=nuevovalor;
                                $("#cajaseleccionabajo"+tipo).load("recursos/ajax.php", {tarea:5, seleccionados: document.getElementById("seleccionados"+tipo).value , empresa: document.getElementById("empresa").value, panel: tipo}, function(){
                                    $("#der-"+tipo+"-"+id).remove();
                                });                               
                            }                            
                            
                            function cambiatipo(idcuenta,tipo){
                                var selecciono = document.getElementById("tipo-"+tipo+"-"+idcuenta).value;
                                var valor=document.getElementById("seleccionados"+tipo).value;
                                var lista = valor.split("_");
                                var nuevovalor="";
                                for(var i=0;i<lista.length;i++){
                                    if(lista[i]!=""){
                                        var aux = lista[i].split("-");
                                        if(aux[0]=="c"){
                                           if(aux[1]==idcuenta){  
                                                nuevovalor=nuevovalor+"_c-"+aux[1]+"-"+selecciono;                                            
                                            }else{
                                                nuevovalor=nuevovalor+"_"+lista[i];
                                            }
                                        }else{
                                            nuevovalor=nuevovalor+"_"+lista[i];
                                        }
                                    }
                                }                                 
                                document.getElementById("seleccionados"+tipo).value=nuevovalor;                                                                                                                              
                            }
                            
                            function subir(clave,tipo,bandera){
                                var indice=-1;
                                var valor=document.getElementById("seleccionados"+tipo).value;
                                var lista = valor.split("_");
                                var nuevovalor="";
                                for(var i=0;i<lista.length;i++){
                                    if(lista[i]!=""){                                        
                                        var aux = lista[i].split("-");
                                        if(bandera=="c"){
                                            if(aux[1]==clave){
                                                indice=i;
                                            }
                                        }else if(bandera=="a"){
                                            if(aux[1]==clave){
                                                indice=i;
                                            }                                            
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
                                $("#cajaderecha"+tipo).load("recursos/ajax.php", {tarea:3, seleccionados: document.getElementById("seleccionados"+tipo).value , empresa: document.getElementById("empresa").value, panel: tipo}, function(){});
                            }
                            
                            function bajar(clave,tipo,bandera){
                                var indice=-1;
                                var valor=document.getElementById("seleccionados"+tipo).value;
                                var lista = valor.split("_");
                                var nuevovalor="";
                                for(var i=0;i<lista.length;i++){
                                    if(lista[i]!=""){                                        
                                        var aux = lista[i].split("-");
                                        if(bandera=="c"){
                                            if(aux[1]==clave){
                                                indice=i;
                                            }
                                        }else if(bandera=="a"){
                                            if(aux[1]==clave){
                                                indice=i;
                                            }                                            
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
                                
                                $("#cajaderecha"+tipo).load("recursos/ajax.php", {tarea:3, seleccionados: document.getElementById("seleccionados"+tipo).value , empresa: document.getElementById("empresa").value, panel: tipo}, function(){});                                
                            }
                            
                        </script>
                    </form>
                </div>
            </div>
        </div>                 
    </body>
</html>
