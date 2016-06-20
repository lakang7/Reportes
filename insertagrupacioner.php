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
                    <form method="post" id="form_CREARfambotanica" action="recursos/acciones.php?tarea=17">
                        
                        <div class="col-md-12 titulopagina" style="margin-top: 125px;">Agrupación de Cuentas Estado de Resultados</div>
                        <div class="col-md-12">
                            <div class="btn-group" role="group" aria-label="...">
                            <button type="button" onclick=redirigir("insertagrupacioner.php?idempresa=<?php echo $_GET["idempresa"]; ?>") class="btn btn-default boton">Crear Nuevo Elemento +</button>
                            <button type="button" onclick=redirigir("listaragrupacioner.php?id=<?php echo $_GET["idempresa"]; ?>")  class="btn btn-default boton">Listar Elementos</button>
                            </div>
                        </div>
                        <div class="col-md-12 subtitulopagina">
                            Crear Nuevo Elemento +
                            <input type="text" id="empresa" name="empresa" value="<?php echo $_GET["idempresa"] ?>" />
                            <input type="text" id="seleccionados" name="seleccionados" value="" />
                        </div>
                        
                    <div class="col-md-12 contiene_entrada" style="padding: 0px">
                        <div class="col-md-12 titulo_entrada">Nombre de la Agrupación</div>
                        <div class="col-md-12"><input style="width: 92%" type="text" class="form-control"  id="nombre" name="nombre" maxlength="60" required="required" /></div>
                    </div>                        
                        
                    <div class="col-md-12 contiene_entrada" style="padding: 0px">
                        <div class="col-md-12 titulo_entrada">Tipo de Agrupación</div>
                        <div class="col-md-12">
                            <select id="tipoagrupacion"  name="tipoagrupacion" class="selectpicker show-tick" data-live-search="true" data-width="92%" required="required">
                            <?php
                                $con=Conexion();
                                $sql_listaAGRUPACION="select * from tipoagrupacionest order by idtipoagrupacionest";
                                $result_listaAGRUPACION=mysql_query($sql_listaAGRUPACION,$con) or die(mysql_error());
                                if(mysql_num_rows($result_listaAGRUPACION)>0){
                                    while ($fila = mysql_fetch_assoc($result_listaAGRUPACION)) {
                                        echo "<option value='".$fila["idtipoagrupacionest"]."'>".$fila["nombre"]."</option>";
                                    }
                                }
                                mysql_close($con);
                            ?>
                        </select>                                                        
                        </div>
                    </div>                        
                        
                        
                        <div class="col-md-12 contiene_entrada">
                            <div class="col-md-12 titulo_entrada" style="padding: 0px">Seleccione las Cuentas dentro de esta agrupación</div>
                            <div class="col-md-5 cajaseleccion" id="cajaizquierda">
                                <?php 
                                    $con=Conexion();
                                    $sql_listaCuenta="select * from cuenta where idempresa='".$_GET["idempresa"]."' order by trim(nombre)";
                                    $result_listaCuenta=mysql_query($sql_listaCuenta,$con) or die(mysql_error());                                                                                 
                                    if(mysql_num_rows($result_listaCuenta)>0){
                                        while ($cuenta = mysql_fetch_assoc($result_listaCuenta)) {
                                            if($cuenta["ctamayor"]==1){
                                                echo "<div class='col-md-12 elementoseleccionmayor' id='izq".$cuenta["idcuenta"]."' name='izq".$cuenta["idcuenta"]."' onclick=seleccionaizq(".$cuenta["idcuenta"].")>";
                                                echo "<div class='col-md-12 lineapequena'>".$cuenta["codigo"]."</div>";
                                                echo "<div class='col-md-12 lineagrande'>".$cuenta["nombre"]."</div>";
                                                echo "</div>";                                                 
                                            }else{
                                                echo "<div class='col-md-12 elementoseleccion' id='izq".$cuenta["idcuenta"]."' name='izq".$cuenta["idcuenta"]."' onclick=seleccionaizq(".$cuenta["idcuenta"].")>";
                                                echo "<div class='col-md-12 lineapequena'>".$cuenta["codigo"]."</div>";
                                                echo "<div class='col-md-12 lineagrande'>".$cuenta["nombre"]."</div>";
                                                echo "</div>";                                                
                                            }                                               
                                        }   
                                    }
                                ?>                             
                            </div>
                            <div class="col-md-1">
                                
                            </div>
                            <div class="col-md-5 cajaseleccionderecha" id="cajaderecha">
                                
                            </div>
                        </div>
                        
                        <div class="col-md-12 contiene_entrada" style="padding: 0px">
                            <div class="col-md-12"><button type="submit" class="btn btn-default">Submit</button></div>                        
                        </div>                        
                        
                        <script type="text/javascript">
                            function seleccionaizq(id){
                                var valor=document.getElementById("seleccionados").value;
                                document.getElementById("seleccionados").value=valor+"_"+id+"-1-1-1";
                                $("#cajaderecha").load("recursos/ajax.php", {tarea:6, seleccionados: document.getElementById("seleccionados").value , empresa: document.getElementById("empresa").value}, function(){
                                    $("#izq"+id).remove();
                                });
                            }
                            
                            function seleccionader(id){
                                var valor=document.getElementById("seleccionados").value;
                                var lista = valor.split("_");
                                var nuevovalor="";
                                for(var i=0;i<lista.length;i++){
                                    if(lista[i]!=""){
                                        var aux = lista[i].split("-");
                                        if(aux[0]!=id){  
                                            nuevovalor=nuevovalor+"_"+lista[i];                                            
                                        }
                                    }
                                }                                
                                document.getElementById("seleccionados").value=nuevovalor;
                                $("#cajaizquierda").load("recursos/ajax.php", {tarea:2, seleccionados: document.getElementById("seleccionados").value , empresa: document.getElementById("empresa").value}, function(){
                                    $("#der"+id).remove();
                                });                                
                            }
                            
                            function cambiatipo01(idcuenta){
                                var seleccionop = document.getElementById("tipop"+idcuenta).value;
                                var seleccionoa = document.getElementById("tipoa"+idcuenta).value;
                                var signo = document.getElementById("signo"+idcuenta).value;
                                var valor=document.getElementById("seleccionados").value;
                                var lista = valor.split("_");
                                var nuevovalor="";
                                for(var i=0;i<lista.length;i++){
                                    if(lista[i]!=""){
                                        var aux = lista[i].split("-");
                                        if(aux[0]==idcuenta){  
                                            nuevovalor=nuevovalor+"_"+aux[0]+"-"+seleccionop+"-"+signo+"-"+seleccionoa;                                            
                                        }else{
                                            nuevovalor=nuevovalor+"_"+lista[i];
                                        }
                                    }
                                }                                 
                                document.getElementById("seleccionados").value=nuevovalor;                                                                                                                                
                            }
                            
                            function cambiatipo02(idcuenta){
                                var seleccionop = document.getElementById("tipop"+idcuenta).value;
                                var seleccionoa = document.getElementById("tipoa"+idcuenta).value;
                                var signo = document.getElementById("signo"+idcuenta).value;
                                var valor=document.getElementById("seleccionados").value;
                                var lista = valor.split("_");
                                var nuevovalor="";
                                for(var i=0;i<lista.length;i++){
                                    if(lista[i]!=""){
                                        var aux = lista[i].split("-");
                                        if(aux[0]==idcuenta){  
                                            nuevovalor=nuevovalor+"_"+aux[0]+"-"+seleccionop+"-"+signo+"-"+seleccionoa;                                            
                                        }else{
                                            nuevovalor=nuevovalor+"_"+lista[i];
                                        }
                                    }
                                }                                 
                                document.getElementById("seleccionados").value=nuevovalor;                                                                                                                                
                            }                            
                            
                            function cambiasigno(idcuenta){
                                var seleccionop = document.getElementById("tipop"+idcuenta).value;
                                var seleccionoa = document.getElementById("tipoa"+idcuenta).value;
                                var signo = document.getElementById("signo"+idcuenta).value;
                                var valor=document.getElementById("seleccionados").value;
                                var lista = valor.split("_");
                                var nuevovalor="";
                                for(var i=0;i<lista.length;i++){
                                    if(lista[i]!=""){
                                        var aux = lista[i].split("-");
                                        if(aux[0]==idcuenta){  
                                            nuevovalor=nuevovalor+"_"+aux[0]+"-"+seleccionop+"-"+signo+"-"+seleccionoa;                                            
                                        }else{
                                            nuevovalor=nuevovalor+"_"+lista[i];
                                        }
                                    }
                                }                                 
                                document.getElementById("seleccionados").value=nuevovalor;                                                                                                                                
                            }                            
                            
                            function subir(clave){
                                var indice=-1;
                                var valor=document.getElementById("seleccionados").value;
                                var lista = valor.split("_");
                                var nuevovalor="";
                                for(var i=0;i<lista.length;i++){
                                    if(lista[i]!=""){
                                        var aux = lista[i].split("-");
                                        if(aux[0]==clave){
                                            indice=(i);
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
                                document.getElementById("seleccionados").value=nuevovalor;
                                $("#cajaderecha").load("recursos/ajax.php", {tarea:6, seleccionados: document.getElementById("seleccionados").value , empresa: document.getElementById("empresa").value}, function(){});
                            }
                            
                            function bajar(clave){
                                var indice=-1;
                                var valor=document.getElementById("seleccionados").value;
                                var lista = valor.split("_");
                                var nuevovalor="";
                                for(var i=0;i<lista.length;i++){
                                    if(lista[i]!=""){
                                        var aux = lista[i].split("-");
                                        if(aux[0]==clave){
                                            indice=(i);
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
                                    document.getElementById("seleccionados").value=nuevovalor;
                                }
                                
                                $("#cajaderecha").load("recursos/ajax.php", {tarea:6, seleccionados: document.getElementById("seleccionados").value , empresa: document.getElementById("empresa").value}, function(){});                                
                            }
                            
                        </script>
                    </form>
                </div>
            </div>
        </div>                 
    </body>
</html>
