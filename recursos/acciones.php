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
?>