<?php session_start();

    require_once("../funciones/funciones.php");   
    /*Inicio de Sesión Administrador*/
    if($_GET["tarea"]==1){
        $con =  Conexion();
        $sqlUsuario = "select * from administrador where correo='".$_POST["email"]."' and contrasena='".$_POST["passw"]."'";
	$resultUsuario = mysql_query($sqlUsuario,$con) or die(mysql_error());
        mysql_close($con); 
        if(mysql_num_rows($resultUsuario)>0){
            $usuario = mysql_fetch_assoc($resultUsuario);
            $_SESSION['administrador']=$usuario["idadministrador"];
            
            ?>
                <script type="text/javascript" language="JavaScript" >                    
                    location.href="../listarempresa.php";
                </script>
            <?php           
        }else{
            ?>
                <script type="text/javascript" language="JavaScript" >
                    alert("Los datos que proporciono no son correctos, por favor veirifique su dirección de email y contraseña.");
                    location.href="../index.php";
                </script>
            <?php 
        }                
    } 
    
    /*Insertar Empresa*/
    if($_GET["tarea"]==2){
        $con =  Conexion();
        $sql_insertEmpresa = "insert into empresa (idempresa,nombre,basedatos,representante,extra1,extra2) values ('".$_POST["identificador"]."','".$_POST["nombre"]."','".$_POST["basedatos"]."','".$_POST["representante"]."','".$_POST["valor1"]."','".$_POST["valor2"]."');";
	$result_insertEmpresa = mysql_query($sql_insertEmpresa,$con) or die(mysql_error());	        
        mysql_close($con);  
        
	?>
            <script type="text/javascript" language="JavaScript" >
		alert("Registro Satisfactorio de Empresa");
		location.href="../listarempresa.php";
            </script>
	<?php          
    }
    
    /*Editar Empresa*/
    if($_GET["tarea"]==3){
        $con =  Conexion();
        $sql_updateEmpresa="update empresa set idempresa='".$_POST["identificador"]."', nombre='".$_POST["nombre"]."', basedatos='".$_POST["basedatos"]."', representante='".$_POST["representante"]."', extra1='".$_POST["valor1"]."', extra2='".$_POST["valor2"]."' where idempresa='".$_GET["id"]."'";
	$result_updateEmpresa = mysql_query($sql_updateEmpresa,$con) or die(mysql_error());	        
        mysql_close($con);
	?>
            <script type="text/javascript" language="JavaScript" >
		alert("Edicion Satisfactoria de Empresa");
		location.href="../listarempresa.php";
            </script>
	<?php                 
    }    

    /*Eliminación de Empresa*/
    if($_GET["tarea"]==4){
        $con =  Conexion();
        $sql_eliminaEmpresa="delete from empresa where idempresa='".$_GET["id"]."';";
	$result_eliminaEmpresa=mysql_query($sql_eliminaEmpresa,$con) or die(mysql_error());
        mysql_close($con);
	?>
            <script type="text/javascript" language="JavaScript" >
		alert("Eliminacion Satisfactoria de Empresa");
		location.href="../listarempresa.php";
            </script>
	<?php            
    }
    
    if($_GET["tarea"]==5){
        $con =  Conexion();    
       // echo "La empresa es: ".$_GET["empresa"]."</br>";
        
        $sqlBorra="delete from asociaclave where idempresa='".$_GET["empresa"]."'";
        $resultBorra=mysql_query($sqlBorra,$con) or die(mysql_error());        
                
        $sqlClaves="select * from clave order by idclave";
        $resultClaves=mysql_query($sqlClaves,$con) or die(mysql_error());
        if(mysql_num_rows($resultClaves)>0){
            while ($clave = mysql_fetch_assoc($resultClaves)) {
                for($i=0;$i<3;$i++){
                    if($_POST["codigo_".$_GET["empresa"]."_".$clave["idclave"]."_".$i]!=null){
                        //echo "-->".$_POST["codigo_".$_GET["empresa"]."_".$clave["idclave"]."_".$i]."</br>";
                        $sqlinsert="insert into asociaclave (idempresa,idclave,codigo,indice) values('".$_GET["empresa"]."','".$clave["idclave"]."','".$_POST["codigo_".$_GET["empresa"]."_".$clave["idclave"]."_".$i]."',1)";
                        $resultinsert=mysql_query($sqlinsert,$con) or die(mysql_error());                         
                    }                    
                }
               // echo "</br>";
            }                               
        }
        mysql_close($con);
	?>
            <script type="text/javascript" language="JavaScript" >
		alert("Actualizacion satisfactoria de cuentas.");
		location.href="../listarempresa.php";
            </script>
	<?php         
    }
    
    if($_GET["tarea"]==6){
        $con =  Conexion(); 
        $sqlEmpresa="select * from empresa where idempresa='".$_GET["id"]."'";
        $resultEmpresa=mysql_query($sqlEmpresa,$con) or die(mysql_error());
        if(mysql_num_rows($resultEmpresa)>0){
            $empresa = mysql_fetch_assoc($resultEmpresa);  
            $out = shell_exec('"C:\Users\Lakhsmi Angarita\Desktop\contadoras\jobs\jobrazones\jobrazones_run.bat" --context_param iporigen="localhost" --context_param puertoorigen="1433" --context_param usuarioorigen="sa" --context_param passwordorigen="Jcglobal2012" --context_param basedatosorigen="'.$empresa["basedatos"].'" --context_param ipdestino="localhost" --context_param puertodestino="3306" --context_param usuariodestino="root" --context_param passwordestino="" --context_param basedatosdestino="razonesfinancieras" --context_param iddestino="'.$empresa["idempresa"].'"'); 
            //$out = shell_exec('"C:\Users\Lakhsmi Angarita\Desktop\contadoras\jobs\jobrazones\jobrazones_run.bat" --context_param iporigen="192.168.1.253" --context_param puertoorigen="49172" --context_param usuarioorigen="nelly" --context_param passwordorigen="Jcglobal2015" --context_param basedatosorigen="'.$empresa["basedatos"].'" --context_param ipdestino="localhost" --context_param puertodestino="3306" --context_param usuariodestino="root" --context_param passwordestino="" --context_param basedatosdestino="razonesfinancieras" --context_param iddestino="'.$empresa["idempresa"].'"'); 
            //echo $out;            
        }
	?>
            <script type="text/javascript" language="JavaScript" >
		alert("Actualizacion Satisfactoria de la data");
		location.href="../listarempresa.php";
            </script>
	<?php         
    }
    
    /*Insertar Email*/
    if($_GET["tarea"]==7){
        $con =  Conexion();
        $sql_insertEmail = "insert into emails (idempresa,nombre,correo) values ('".$_POST["empresa"]."','".$_POST["nombre"]."','".$_POST["correo"]."');";
	$result_insertEmail = mysql_query($sql_insertEmail,$con) or die(mysql_error());	        
        mysql_close($con);  
        
	?>
            <script type="text/javascript" language="JavaScript" >
		alert("Registro Satisfactorio de Email");
		location.href="../listaremails.php";
            </script>
	<?php          
    }    
    
    /*Editar Email*/
    if($_GET["tarea"]==8){
        $con =  Conexion();
        $sql_updateEmail="update emails set idempresa='".$_POST["empresa"]."', nombre='".$_POST["nombre"]."', correo='".$_POST["correo"]."' where idemails='".$_GET["id"]."'";
	$result_updateEmail = mysql_query($sql_updateEmail,$con) or die(mysql_error());	        
        mysql_close($con);
	?>
            <script type="text/javascript" language="JavaScript" >
		alert("Edicion Satisfactoria de Email");
		location.href="../listaremails.php";
            </script>
	<?php                 
    }   
    
    /*Eliminación de Email*/
    if($_GET["tarea"]==9){
        $con =  Conexion();
        $sql_eliminaEmail="delete from emails where idemails='".$_GET["id"]."';";
	$result_eliminaEmail=mysql_query($sql_eliminaEmail,$con) or die(mysql_error());
        mysql_close($con);
	?>
            <script type="text/javascript" language="JavaScript" >
		alert("Eliminacion Satisfactoria de Email");
		location.href="../listaremails.php";
            </script>
	<?php            
    }
    
    /*Generación de Reporte Razones Financieras*/
    if($_GET["tarea"]==10){
        $mensaje="";       
        if($_POST["opcion"]==1){
            $mensaje="Generacion Satisfactoria de Reporte";
        }else{
            $mensaje="Envio Satisfactorio de Reporte";
        }
    ?>
        <script type="text/javascript">            
            window.open("../razonesfinancieras.php?empresa=<?php echo $_POST["empresa"]; ?>&anno=<?php echo $_POST["anno"] ?>&mes=<?php echo $_POST["mes"] ?>&opcion=<?php echo $_POST["opcion"] ?>",'_blank');            
            alert("<?php echo $mensaje; ?>");
            location.href="../reporterazonesfinancieras.php?empresa=<?php echo $_POST["empresa"]; ?>&anno=<?php echo $_POST["anno"] ?>&mes=<?php echo $_POST["mes"] ?>&opcion=<?php echo $_POST["opcion"] ?>";                
        </script>            
    <?php
    }    
    
    
    /*Generación de Reporte Estado de Resultados*/
    if($_GET["tarea"]==11){
        $mensaje="Reporte Generado Satisfactoriamente";       
    ?>
        <script type="text/javascript">            
            window.open("../estadoderesultados.php?empresa=<?php echo $_POST["empresa"]; ?>&anno=<?php echo $_POST["anno"] ?>&mes=<?php echo $_POST["mes"] ?>",'_blank');            
            alert("<?php echo $mensaje; ?>");
            location.href="../reporteestadoderesultados.php?empresa=<?php echo $_POST["empresa"]; ?>&anno=<?php echo $_POST["anno"] ?>&mes=<?php echo $_POST["mes"]; ?>";                
        </script>            
    <?php
    }     
    
    /*Cerrar Sesion*/
    if($_GET["tarea"]==12){
        session_destroy();
        ?>
            <script type="text/javascript" language="JavaScript" >                
                location.href="../index.php";
            </script>
        <?php        
    }
    
    
    /*Insertar Agrupacion*/
    if($_GET["tarea"]==13){
        $con =  Conexion();
        $sql_insertAgrupacion = "insert into agrupacion (idempresa,idtipoagrupacion,nombre) values ('".$_POST["empresa"]."','".$_POST["tipoagrupacion"]."','".$_POST["nombre"]."');";
	$result_insertAgrupacion = mysql_query($sql_insertAgrupacion,$con) or die(mysql_error());	        
                
        $sql_ultimaAGRUPACION = "SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'razonesfinancieras' AND TABLE_NAME = 'agrupacion';";
        $result_ultimaAGRUPACION = mysql_query($sql_ultimaAGRUPACION, $con) or die(mysql_error());
        $fila = mysql_fetch_assoc($result_ultimaAGRUPACION);
        $indice = intval($fila["AUTO_INCREMENT"]);
        $indice--; 
        
        $posicion=1;
        $lista01 = explode("_", $_POST["seleccionados"]);
        for($i=0;$i<count($lista01);$i++){
            if($lista01[$i]!=""){
                $lista02 = explode("-",$lista01[$i]);
                $sqlcuenta="select * from cuenta where idempresa='".$_POST["empresa"]."' and idcuenta='".$lista02[0]."'";
                $resultcuenta = mysql_query($sqlcuenta,$con) or die(mysql_error());
                $cuenta = mysql_fetch_assoc($resultcuenta);  
                if($lista02[2]==2){
                    $lista02[2]=-1;
                }
                $sql_insertRelacion="insert into agrupacioncuentas (idagrupacion,idempresa,codigocuenta,posicion,signo,tipo) values('".$indice."','".$_POST["empresa"]."','".$cuenta["codigo"]."',".$posicion.",".$lista02[2].",".$lista02[1].")";
                $result_insertRelacion = mysql_query($sql_insertRelacion, $con) or die(mysql_error());
                $posicion++;
            }
        }
        mysql_close($con);  
        ?>
            <script type="text/javascript" language="JavaScript" >
                alert("Agrupación Registrada Satisfactoriamente.");
                location.href="../listaragrupacion.php?id=<?php echo $_POST["empresa"]; ?>";
            </script>
        <?php                                  
    } 
    
    /*Eliminar Agrupacion*/
    if($_GET["tarea"]==14){
        $con =  Conexion();
        $sqlAgrupacion="select * from agrupacion where idagrupacion='".$_GET["id"]."'";
        $resultAgrupacion = mysql_query($sqlAgrupacion, $con) or die(mysql_error());
        $agrupacion = mysql_fetch_assoc($resultAgrupacion); 
        
        $sqlDeteleRelacion="delete from agrupacioncuentas where idagrupacion='".$_GET["id"]."'";
        $resultDeteleRelacion = mysql_query($sqlDeteleRelacion, $con) or die(mysql_error());
        
        $sqlDeleteAgrupacion="delete from agrupacion where idagrupacion='".$_GET["id"]."'";
        $resultDeteleAgrupacion = mysql_query($sqlDeleteAgrupacion, $con) or die(mysql_error());        
        mysql_close($con);
        ?>
            <script type="text/javascript" language="JavaScript" >
                alert("Agrupación Eliminada Satisfactoriamente.");
                location.href="../listaragrupacion.php?id=<?php echo $agrupacion["idempresa"]; ?>";
            </script>
        <?php                        
    }    
    
    /*Insertar Estructura balance generel*/
    if($_GET["tarea"]==15){
        $con = Conexion();
        $sqlElimina="delete from enasociacion where idempresa='".$_POST["empresa"]."'";
        $resultElimina=mysql_query($sqlElimina,$con) or die(mysql_error());
        
        $sqlTipos="select * from tipoagrupacion order by idtipoagrupacion";
        $resultTipos=mysql_query($sqlTipos,$con) or die(mysql_error()); 
        if(mysql_num_rows($resultTipos)>0){
            while ($tipo = mysql_fetch_assoc($resultTipos)) {
                $listaAUX = explode("_",$_POST["seleccionados".$tipo["idtipoagrupacion"]]);
                $posicion=1;
                for($i=0;$i<count($listaAUX);$i++){
                    if($listaAUX[$i]!=""){
                        $listaSUB = explode("-",$listaAUX[$i]);
                        $sqlinsertEstructura="";
                        if($listaSUB[0]=="c"){
                            $sqlcuenta="select * from cuenta where idempresa='".$_POST["empresa"]."' and idcuenta='".$listaSUB[1]."'";
                            //echo $sqlcuenta."</br>";
                            $resultcuenta = mysql_query($sqlcuenta,$con) or die(mysql_error());
                            $cuenta = mysql_fetch_assoc($resultcuenta);                            
                            
                            $sqlinsertEstructura="insert into enasociacion (idempresa,codigocuenta,idtipoagrupacion,posicion,signo,tipo,tipoelemento) values(".$_POST["empresa"].",'".$cuenta["codigo"]."',".$tipo["idtipoagrupacion"].",".$posicion.",1,".$listaSUB[2].",'c')";
                            $resultinsertEstructura = mysql_query($sqlinsertEstructura,$con) or die(mysql_error());
                            
                        }else if($listaSUB[0]=="a"){
                            
                            $sqlinsertEstructura="insert into enasociacion (idempresa,idagrupacion,idtipoagrupacion,posicion,signo,tipoelemento) values(".$_POST["empresa"].",".$listaSUB[1].",".$tipo["idtipoagrupacion"].",".$posicion.",1,'a')";
                            $resultinsertEstructura = mysql_query($sqlinsertEstructura,$con) or die(mysql_error());                            
                            
                        }
                        $posicion++;
                    }                                        
                }
            }                            
        }        
    } 
    
    
    /*Generación de Reporte Balance General*/
    if($_GET["tarea"]==16){
        $mensaje="";       
        if($_POST["opcion"]==1){
            $mensaje="Generacion Satisfactoria de Reporte";
        }else{
            $mensaje="Envio Satisfactorio de Reporte";
        }
    ?>
        <script type="text/javascript">            
            window.open("../balancegeneral.php?empresa=<?php echo $_POST["empresa"]; ?>&anno=<?php echo $_POST["anno"] ?>&mes=<?php echo $_POST["mes"] ?>&opcion=<?php echo $_POST["opcion"] ?>",'_blank');            
            alert("<?php echo $mensaje; ?>");
            location.href="../reportebalancegeneral.php?empresa=<?php echo $_POST["empresa"]; ?>&anno=<?php echo $_POST["anno"] ?>&mes=<?php echo $_POST["mes"] ?>&opcion=<?php echo $_POST["opcion"] ?>";                
        </script>            
    <?php
    }
    
    if($_GET["tarea"]==17){
        $con =  Conexion();
        $sql_insertAgrupacion = "insert into agrupacionest (idempresa,idtipoagrupacionest,nombre) values ('".$_POST["empresa"]."','".$_POST["tipoagrupacion"]."','".$_POST["nombre"]."');";
	$result_insertAgrupacion = mysql_query($sql_insertAgrupacion,$con) or die(mysql_error());	        
                
        $sql_ultimaAGRUPACION = "SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'razonesfinancieras' AND TABLE_NAME = 'agrupacionest';";
        $result_ultimaAGRUPACION = mysql_query($sql_ultimaAGRUPACION, $con) or die(mysql_error());
        $fila = mysql_fetch_assoc($result_ultimaAGRUPACION);
        $indice = intval($fila["AUTO_INCREMENT"]);
        $indice--; 
        
        $posicion=1;
        $lista01 = explode("_", $_POST["seleccionados"]);
        for($i=0;$i<count($lista01);$i++){
            if($lista01[$i]!=""){
                $lista02 = explode("-",$lista01[$i]);
                $sqlcuenta="select * from cuenta where idempresa='".$_POST["empresa"]."' and idcuenta='".$lista02[0]."'";
                $resultcuenta = mysql_query($sqlcuenta,$con) or die(mysql_error());
                $cuenta = mysql_fetch_assoc($resultcuenta);  
                if($lista02[2]==2){
                    $lista02[2]=-1;
                }
                $sql_insertRelacion="insert into agrupacioncuentasest (idagrupacionest,idempresa,codigocuenta,posicion,signo,tipop,tipoa) values(".$indice.",'".$_POST["empresa"]."','".$cuenta["codigo"]."','".$posicion."','".$lista02[2]."','".$lista02[1]."','".$lista02[3]."')";
                $result_insertRelacion = mysql_query($sql_insertRelacion, $con) or die(mysql_error());
                $posicion++;
            }
        }
        mysql_close($con);  
        ?>
            <script type="text/javascript" language="JavaScript" >
                alert("Agrupación Estado de Resultados Registrada Satisfactoriamente.");
                location.href="../listaragrupacioner.php?id=<?php echo $_POST["empresa"]; ?>";
            </script>
        <?php                                                 
    }
    
    /*Insertar Estructura Estado de Resultados*/
    if($_GET["tarea"]==18){
        $con = Conexion();
        $sqlTipos="select * from tipoagrupacionest order by idtipoagrupacionest";
        $resultTipos=mysql_query($sqlTipos,$con) or die(mysql_error()); 
        if(mysql_num_rows($resultTipos)>0){
            while ($tipo = mysql_fetch_assoc($resultTipos)) {
                $listaAUX = explode("_",$_POST["seleccionados".$tipo["idtipoagrupacionest"]]);                
                $posicion=1;
                for($i=0;$i<count($listaAUX);$i++){
                    if($listaAUX[$i]!=""){                        
                        $sqlinsertEstructura="insert into enasociacioner (idtipoagrupacionest,idagrupacionest,posicion,signo) values('".$tipo["idtipoagrupacionest"]."','".$listaAUX[$i]."','".$posicion."','-1')";
                        $resultinsertEstructura = mysql_query($sqlinsertEstructura,$con) or die(mysql_error());                                                
                        $posicion++;                        
                    }   
                }                
            }
        }
    }
    
    /*Editar Agrupacion*/
    if($_GET["tarea"]==19){
        $con =  Conexion();
        
        $sqlUpdateAgrupacion="update agrupacion set idtipoagrupacion='".$_POST["tipoagrupacion"]."', nombre='".$_POST["nombre"]."' where idagrupacion='".$_GET["id"]."'";        
	$resultUpdateAgrupacion = mysql_query($sqlUpdateAgrupacion,$con) or die(mysql_error());	                        
        
        $sqldeleteRelacion="delete from agrupacioncuentas where idagrupacion='".$_GET["id"]."'";
        $resultdeleteRelacion = mysql_query($sqldeleteRelacion,$con) or die(mysql_error());	
        
        $posicion=1;
        $lista01 = explode("_", $_POST["seleccionados"]);
        for($i=0;$i<count($lista01);$i++){
            if($lista01[$i]!=""){
                $lista02 = explode("-",$lista01[$i]);
                $sqlcuenta="select * from cuenta where idempresa='".$_POST["empresa"]."' and idcuenta='".$lista02[0]."'";
                $resultcuenta = mysql_query($sqlcuenta,$con) or die(mysql_error());
                $cuenta = mysql_fetch_assoc($resultcuenta);  
                if($lista02[2]==2){
                    $lista02[2]=-1;
                }
                $sql_insertRelacion="insert into agrupacioncuentas (idagrupacion,idempresa,codigocuenta,posicion,signo,tipo) values('".$_GET["id"]."','".$_POST["empresa"]."','".$cuenta["codigo"]."',".$posicion.",".$lista02[2].",".$lista02[1].")";
                $result_insertRelacion = mysql_query($sql_insertRelacion, $con) or die(mysql_error());
                $posicion++;
            }
        }
        mysql_close($con);  
        ?>
            <script type="text/javascript" language="JavaScript" >
                alert("Agrupación Editada Satisfactoriamente.");
                location.href="../listaragrupacion.php?id=<?php echo $_POST["empresa"]; ?>";
            </script>
        <?php                                 
    } 
    
    /*Eliminar Agrupacion Estado de Resultados*/
    if($_GET["tarea"]==20){
        $con =  Conexion();
        $sqlAgrupacion="select * from agrupacionest where idagrupacionest='".$_GET["id"]."'";
        $resultAgrupacion = mysql_query($sqlAgrupacion, $con) or die(mysql_error());
        $agrupacion = mysql_fetch_assoc($resultAgrupacion); 
        
        $sqlDeteleRelacion="delete from agrupacioncuentasest where idagrupacionest='".$_GET["id"]."'";
        $resultDeteleRelacion = mysql_query($sqlDeteleRelacion, $con) or die(mysql_error());
        
        $sqlDeleteAgrupacion="delete from agrupacionest where idagrupacionest='".$_GET["id"]."'";
        $resultDeteleAgrupacion = mysql_query($sqlDeleteAgrupacion, $con) or die(mysql_error());        
        mysql_close($con);
        ?>
            <script type="text/javascript" language="JavaScript" >
                alert("Agrupación Estado de Resultados Eliminada Satisfactoriamente.");
                location.href="../listaragrupacioner.php?id=<?php echo $agrupacion["idempresa"]; ?>";
            </script>
        <?php                        
    }    
    
    /*Editar agrupación Estado de Resultados*/
    if($_GET["tarea"]==21){        
        $con =  Conexion();        
        $sql_deleteasociaciones="delete from agrupacioncuentasest where idagrupacionest='".$_GET["id"]."'";
        $result_deleteasociaciones=mysql_query($sql_deleteasociaciones,$con) or die(mysql_error());        
        $sql_updateagrupacion="update agrupacionest set idtipoagrupacionest='".$_POST["tipoagrupacion"]."', nombre='".$_POST["nombre"]."' where idagrupacionest='".$_GET["id"]."'";
        $result_updateagrupacion = mysql_query($sql_updateagrupacion,$con) or die(mysql_error());	                               
        $posicion=1;
        
        $lista01 = explode("_", $_POST["seleccionados"]);
        for($i=0;$i<count($lista01);$i++){
            if($lista01[$i]!=""){
                $lista02 = explode("-",$lista01[$i]);
                $sqlcuenta="select * from cuenta where idempresa='".$_POST["empresa"]."' and idcuenta='".$lista02[0]."'";
                $resultcuenta = mysql_query($sqlcuenta,$con) or die(mysql_error());
                $cuenta = mysql_fetch_assoc($resultcuenta);  
                if($lista02[2]==2){
                    $lista02[2]=-1;
                }
                $sql_insertRelacion="insert into agrupacioncuentasest (idagrupacionest,idempresa,codigocuenta,posicion,signo,tipop,tipoa) values('".$_GET["id"]."','".$_POST["empresa"]."','".$cuenta["codigo"]."','".$posicion."','".$lista02[2]."','".$lista02[1]."','".$lista02[3]."')";
                //echo $sql_insertRelacion."</br>";
                $result_insertRelacion = mysql_query($sql_insertRelacion, $con) or die(mysql_error());
                $posicion++;
            }
        }
        mysql_close($con);  
        ?>
            <script type="text/javascript" language="JavaScript" >
                alert("Agrupación Estado de Resultados Editada Satisfactoriamente.");
                location.href="../listaragrupacioner.php?id=<?php echo $_POST["empresa"]; ?>";
            </script>
        <?php                                                
    } 
    
    
        /*Generación de Reporte Estado de Resultados*/
    if($_GET["tarea"]==22){
        $mensaje="Reporte Generado Satisfactoriamente";       
    ?>
        <script type="text/javascript">            
            window.open("../ReporteEstadodeResultados_Nuevo.php?empresa=<?php echo $_POST["empresa"]; ?>&anno=<?php echo $_POST["anno"] ?>&mes=<?php echo $_POST["mes"] ?>",'_blank');            
            alert("<?php echo $mensaje; ?>");
            location.href="../reporteestadoderesultados_pantalla.php?empresa=<?php echo $_POST["empresa"]; ?>&anno=<?php echo $_POST["anno"] ?>&mes=<?php echo $_POST["mes"]; ?>";                
        </script>            
    <?php
    }     
    /*Generación de Reporte Balance General Comparativo*/
    if($_GET["tarea"]==23){
        $mensaje="Reporte Generado Satisfactoriamente";       
    ?>
        <script type="text/javascript">            
            window.open("../balancegeneralcomparativo.php?empresa=<?php echo $_POST["empresa"]; ?>&anno2=<?php  echo $_POST["anno2"] ?>&anno=<?php echo $_POST["anno"] ?>&mes2=<?php echo $_POST["mes2"] ?>&mes=<?php echo $_POST["mes"] ?>",'_blank');            
            alert("<?php echo $mensaje; ?>");
            location.href="../reportebalancegeneralcomp_pantalla.php?empresa=<?php echo $_POST["empresa"]; ?>&anno2=<?php  echo $_POST["anno2"] ?>&anno=<?php echo $_POST["anno"] ?>&mes=<?php echo $_POST["mes2"] ?>&mes=<?php echo $_POST["mes"]; ?>";                
        </script>            
    <?php
    }  
    /*Generación de Reporte Estado de resultados anualizados*/
    if($_GET["tarea"]==24){
        $mensaje="Reporte Generado Satisfactoriamente";       
    ?>
        <script type="text/javascript">            
            window.open("../ReporteEstadodeResultados_Anual.php?empresa=<?php echo $_POST["empresa"]; ?>&anno=<?php echo $_POST["anno"] ?>&mes=<?php echo $_POST["mes"] ?>",'_blank');            
            alert("<?php echo $mensaje; ?>");
            location.href="../reporteestadoderesultados_pantalla_anual.php?empresa=<?php echo $_POST["empresa"]; ?>&anno=<?php echo $_POST["anno"] ?>&mes=<?php echo $_POST["mes"]; ?>";               
        </script>            
    <?php
    }  

    
?>