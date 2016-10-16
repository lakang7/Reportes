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
        
        <title>Editar | Empresas Registradas</title>
        <script type="text/javascript" language="JavaScript" >
            function redirigir(direccion){
                location.href=direccion;
            }		
        </script>        
        
        <?php
            header('Content-Type: text/html; charset=UTF-8');        
            require_once("funciones/funciones.php");
            $con = Conexion();
            $sqlAgrupacion="select * from agrupacion_nf where idagrupacion='".$_GET["id"]."'";
            $resultAgrupacion=mysql_query($sqlAgrupacion,$con) or die(mysql_error());
            $agrupacion = mysql_fetch_assoc($resultAgrupacion);
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
                    <form method="post" id="form_CREARfambotanica" action="recursos/acciones.php?tarea=55&id=<?php echo $_GET["id"]; ?>">
                        
                        <div class="col-md-12 titulopagina" style="margin-top: 125px;">Agrupación de Cuentas para Notas Financieras</div>
                        <div class="col-md-12">
                            <div class="btn-group" role="group" aria-label="...">
                            <button type="button" onclick=redirigir("insertagrupacionnf.php?idempresa=<?php echo $agrupacion["idempresa"]; ?>") class="btn btn-default boton">Crear Nuevo Elemento +</button>
                            <button type="button" onclick=redirigir("listaragrupacionnf.php?id=<?php echo $agrupacion["idempresa"]; ?>")  class="btn btn-default boton">Listar Elementos</button>
                            </div>
                        </div>
                        <div class="col-md-12 subtitulopagina">
                            Crear Nuevo Elemento +
                            <input type="hidden" id="empresa" name="empresa" value="<?php echo $agrupacion["idempresa"] ?>" />
                            <?php
                            
                                $sqlcuentasasociadas="select * from agrupacioncuentas_nf where idagrupacion='".$_GET["id"]."'";
                                $resultcuentasasociadas=mysql_query($sqlcuentasasociadas,$con) or die(mysql_error());
                                $concatena="";
                                if(mysql_num_rows($resultcuentasasociadas)>0){
                                    while ($sele = mysql_fetch_assoc($resultcuentasasociadas)) {
                                        $sqlCuenta="select * from cuenta where codigo='".$sele["codigocuenta"]."' and idempresa='".$agrupacion["idempresa"]."'";
                                        $resultCuenta=mysql_query($sqlCuenta,$con) or die(mysql_error());
                                        $conta=mysql_fetch_assoc($resultCuenta);
                                        $signo=1;
                                        if($sele["signo"]==-1){
                                            $signo=2;
                                        }
                                        $concatena=$concatena."_".$conta["idcuenta"]."-".$sele["tipo"]."-".$signo;
                                    }           
                                }                            
                            
                            ?>
                            
                            <input type="hidden" id="seleccionados" name="seleccionados" value="<?php echo $concatena; ?>" />
                        </div>
                        
                    <div class="col-md-12 contiene_entrada" style="padding: 0px">
                        <div class="col-md-12 titulo_entrada">Nombre de la Agrupación</div>
                        <div class="col-md-12"><input value="<?php echo $agrupacion["nombre"]; ?>" style="width: 92%" type="text" class="form-control"  id="nombre" name="nombre" maxlength="60" required="required" /></div>
                    </div>                        
                                                                                           
                        <div class="col-md-12 contiene_entrada">
                            <div class="col-md-12 titulo_entrada" style="padding: 0px">Seleccione las Cuentas dentro de esta agrupación</div>
                            <div class="col-md-5 cajaseleccion" id="cajaizquierda">
                                <?php 
                                    $con=Conexion();
                                    $sql_listaCuenta="select * from cuenta where idempresa='".$agrupacion["idempresa"]."' order by trim(nombre)";
                                    $result_listaCuenta=mysql_query($sql_listaCuenta,$con) or die(mysql_error());                                                                                 
                                    if(mysql_num_rows($result_listaCuenta)>0){
                                        while ($cuenta = mysql_fetch_assoc($result_listaCuenta)) {
                                            $sqlcuentasasociadas="select * from agrupacioncuentas_nf where idagrupacion='".$_GET["id"]."' and codigocuenta='".$cuenta["codigo"]."'";
                                            $resultcuentasasociadas=mysql_query($sqlcuentasasociadas,$con) or die(mysql_error());
                                            if(mysql_num_rows($resultcuentasasociadas)==0){
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
                                    }
                                ?>                             
                            </div>
                            <div class="col-md-1">
                                
                            </div>
                            <div class="col-md-5 cajaseleccionderecha" id="cajaderecha">
                             <?php
                             
                            $listaSeleccionados= explode("_",$concatena);
                            for($i=0;$i<count($listaSeleccionados);$i++){
                                if($listaSeleccionados[$i]!=""){                
                                    $aux=explode("-",$listaSeleccionados[$i]);
                                    $sqlCuenta="select * from cuenta where idempresa='".$agrupacion["idempresa"]."' and idcuenta='".$aux[0]."'";
                                    $resultCuenta=mysql_query($sqlCuenta,$con) or die(mysql_error());
                                    $cuenta = mysql_fetch_assoc($resultCuenta); 
                                    if($cuenta["ctamayor"]==1){
                                        echo "<div class='col-md-12 elementoseleccionmayor' id='der".$cuenta["idcuenta"]."' name='der".$cuenta["idcuenta"]."'>";
                                        echo "<div class='col-md-12 lineapequena' >".$cuenta["codigo"]."</div>";
                                        echo "<div class='col-md-12 lineagrande' >".$cuenta["nombre"]."</div>";
                                        echo "<div class='col-md-12 lineapequena'>";
                                        echo "<div class='col-md-1' style='padding:0px; margin-right:15px'>";
                                        if($aux[2]==1){
                                            echo "<select onchange=cambiasigno(".$cuenta["idcuenta"].") name='signo".$cuenta["idcuenta"]."' id='signo".$cuenta["idcuenta"]."' style='float:left'><option value='1' selected>+</option><option value='2'>-</option></select>";
                                        }
                                        if($aux[2]==2){
                                            echo "<select onchange=cambiasigno(".$cuenta["idcuenta"].") name='signo".$cuenta["idcuenta"]."' id='signo".$cuenta["idcuenta"]."' style='float:left'><option value='1' >+</option><option value='2' selected>-</option></select>";
                                        }                        
                                        echo "</div>";
                                        echo "<div class='col-md-3' style='padding:0px'>";
                                        if($aux[1]==1){
                                            echo "<select onchange=cambiatipo(".$cuenta["idcuenta"].") name='tipo".$cuenta["idcuenta"]."' id='tipo".$cuenta["idcuenta"]."' style='float:left'><option value='1' selected>Cargos</option><option value='2'>Abonos</option><option value='3'>Saldos</option></select>";
                                        }
                                        if($aux[1]==2){
                                            echo "<select onchange=cambiatipo(".$cuenta["idcuenta"].") name='tipo".$cuenta["idcuenta"]."' id='tipo".$cuenta["idcuenta"]."' style='float:left'><option value='1'>Cargos</option><option value='2' selected>Abonos</option><option value='3'>Saldos</option></select>";
                                        }
                                        if($aux[1]==3){
                                            echo "<select onchange=cambiatipo(".$cuenta["idcuenta"].") name='tipo".$cuenta["idcuenta"]."' id='tipo".$cuenta["idcuenta"]."' style='float:left'><option value='1'>Cargos</option><option value='2'>Abonos</option><option value='3' selected>Saldos</option></select>";
                                        }                                        
                                        echo "</div>";
                                        echo "<div class='col-md-2' style=' padding:0px; margin-left:5px' onclick=subir(".$cuenta["idcuenta"].")>SUBIR</div>";
                                        echo "<div class='col-md-2' style=' padding:0px; margin-left:5px' onclick=bajar(".$cuenta["idcuenta"].")>BAJAR</div>";
                                        echo "<div class='col-md-2' style=' padding:0px; margin-left:5px' onclick=seleccionader(".$cuenta["idcuenta"].")>ElIMINAR</div>";
                                        echo "</div>";                    
                                        echo "</div>";                                                 
                                    }else{
                                        echo "<div class='col-md-12 elementoseleccion' id='der".$cuenta["idcuenta"]."' name='der".$cuenta["idcuenta"]."'>";
                                        echo "<div class='col-md-12 lineapequena' >".$cuenta["codigo"]."</div>";
                                        echo "<div class='col-md-12 lineagrande' >".$cuenta["nombre"]."</div>";
                                        echo "<div class='col-md-12 lineapequena'>";
                                        echo "<div class='col-md-1' style='padding:0px; margin-right:15px'>";
                                        if($aux[2]==1){
                                            echo "<select onchange=cambiasigno(".$cuenta["idcuenta"].") name='signo".$cuenta["idcuenta"]."' id='signo".$cuenta["idcuenta"]."' style='float:left'><option value='1' selected>+</option><option value='2'>-</option></select>";
                                        }
                                        if($aux[2]==2){
                                            echo "<select onchange=cambiasigno(".$cuenta["idcuenta"].") name='signo".$cuenta["idcuenta"]."' id='signo".$cuenta["idcuenta"]."' style='float:left'><option value='1' >+</option><option value='2' selected>-</option></select>";
                                        }                         
                                        echo "</div>";                    
                                        echo "<div class='col-md-3' style='padding:0px'>";
                                        if($aux[1]==1){
                                            echo "<select onchange=cambiatipo(".$cuenta["idcuenta"].") name='tipo".$cuenta["idcuenta"]."' id='tipo".$cuenta["idcuenta"]."' style='float:left'><option value='1' selected>Cargos</option><option value='2'>Abonos</option><option value='3'>Saldos</option></select>";
                                        }
                                        if($aux[1]==2){
                                            echo "<select onchange=cambiatipo(".$cuenta["idcuenta"].") name='tipo".$cuenta["idcuenta"]."' id='tipo".$cuenta["idcuenta"]."' style='float:left'><option value='1'>Cargos</option><option value='2' selected>Abonos</option><option value='3'>Saldos</option></select>";
                                        }
                                        if($aux[1]==3){
                                            echo "<select onchange=cambiatipo(".$cuenta["idcuenta"].") name='tipo".$cuenta["idcuenta"]."' id='tipo".$cuenta["idcuenta"]."' style='float:left'><option value='1'>Cargos</option><option value='2'>Abonos</option><option value='3' selected>Saldos</option></select>";
                                        }                                        
                                        echo "</div>";
                                        echo "<div class='col-md-2' style=' padding:0px; margin-left:5px' onclick=subir(".$cuenta["idcuenta"].")>SUBIR</div>";
                                        echo "<div class='col-md-2' style=' padding:0px; margin-left:5px' onclick=bajar(".$cuenta["idcuenta"].")>BAJAR</div>";
                                        echo "<div class='col-md-2' style=' padding:0px; margin-left:5px' onclick=seleccionader(".$cuenta["idcuenta"].")>ELIMINAR</div>";
                                        echo "</div>";                     
                                        echo "</div>";                                                
                                    }
                                }            
                            }                             
                             
                             ?>
                            </div>
                        </div>
                        
                        <div class="col-md-12 contiene_entrada" style="padding: 0px">
                            <div class="col-md-12"><button type="submit" class="btn btn-default">Submit</button></div>                        
                        </div>                        
                        
                        <script type="text/javascript">
                            function seleccionaizq(id){
                                var valor=document.getElementById("seleccionados").value;
                                document.getElementById("seleccionados").value=valor+"_"+id+"-1-1";
                                $("#cajaderecha").load("recursos/ajax.php", {tarea:1, seleccionados: document.getElementById("seleccionados").value , empresa: document.getElementById("empresa").value}, function(){
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
                            
                            function cambiatipo(idcuenta){
                                var selecciono = document.getElementById("tipo"+idcuenta).value;
                                var signo = document.getElementById("signo"+idcuenta).value;
                                var valor=document.getElementById("seleccionados").value;
                                var lista = valor.split("_");
                                var nuevovalor="";
                                for(var i=0;i<lista.length;i++){
                                    if(lista[i]!=""){
                                        var aux = lista[i].split("-");
                                        if(aux[0]==idcuenta){  
                                            nuevovalor=nuevovalor+"_"+aux[0]+"-"+selecciono+"-"+signo;                                            
                                        }else{
                                            nuevovalor=nuevovalor+"_"+lista[i];
                                        }
                                    }
                                }                                 
                                document.getElementById("seleccionados").value=nuevovalor;                                                                                                                                
                            }
                            
                            function cambiasigno(idcuenta){
                                var selecciono = document.getElementById("tipo"+idcuenta).value;
                                var signo = document.getElementById("signo"+idcuenta).value;
                                var valor=document.getElementById("seleccionados").value;
                                var lista = valor.split("_");
                                var nuevovalor="";
                                for(var i=0;i<lista.length;i++){
                                    if(lista[i]!=""){
                                        var aux = lista[i].split("-");
                                        if(aux[0]==idcuenta){  
                                            nuevovalor=nuevovalor+"_"+aux[0]+"-"+selecciono+"-"+signo;                                            
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
                                $("#cajaderecha").load("recursos/ajax.php", {tarea:1, seleccionados: document.getElementById("seleccionados").value , empresa: document.getElementById("empresa").value}, function(){});
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
                                
                                $("#cajaderecha").load("recursos/ajax.php", {tarea:1, seleccionados: document.getElementById("seleccionados").value , empresa: document.getElementById("empresa").value}, function(){});                                
                            }
                            
                        </script>
                    </form>
                </div>
            </div>
        </div>                 
    </body>
</html>
