<?php
    require_once("../funciones/funciones.php");
    $con = Conexion();

    if($_POST["tarea"]==1){
        $listaSeleccionados= explode("_",$_POST["seleccionados"]);
        for($i=0;$i<count($listaSeleccionados);$i++){
            if($listaSeleccionados[$i]!=""){                
                $aux=explode("-",$listaSeleccionados[$i]);
                $sqlCuenta="select * from cuenta where idempresa='".$_POST["empresa"]."' and idcuenta='".$aux[0]."'";
                $resultCuenta=mysql_query($sqlCuenta,$con) or die(mysql_error());
                $cuenta = mysql_fetch_assoc($resultCuenta); 
                if($cuenta["ctamayor"]==1){
                    echo "<div class='col-md-12 elementoseleccionmayor' id='der".$cuenta["idcuenta"]."' name='der".$cuenta["idcuenta"]."'>";
                    echo "<div class='col-md-12 lineapequena' >".$cuenta["codigo"]."</div>";
                    echo "<div class='col-md-12 lineagrande' >".$cuenta["nombre"]."</div>";
                    echo "<div class='col-md-12 lineapequena'>";
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
    }
    
    if($_POST["tarea"]==2){
        $listaSeleccionados= explode("_",$_POST["seleccionados"]);
        $sql_listaCuenta="select * from cuenta where idempresa='".$_POST["empresa"]."' order by trim(nombre)";
        $result_listaCuenta=mysql_query($sql_listaCuenta,$con) or die(mysql_error());                                                                                 
        if(mysql_num_rows($result_listaCuenta)>0){
            while ($cuenta = mysql_fetch_assoc($result_listaCuenta)) {
                $band=0;
                for($i=0;$i<count($listaSeleccionados);$i++){
                    if($listaSeleccionados[$i]==$cuenta["idcuenta"]){
                        $band=1;
                    }
                }
                if($band==0){
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
    }
    
    if($_POST["tarea"]==3){
        $listaSeleccionados= explode("_",$_POST["seleccionados"]);
        for($i=0;$i<count($listaSeleccionados);$i++){
            if($listaSeleccionados[$i]!=""){                
                $aux=explode("-",$listaSeleccionados[$i]);
                if($aux[0]=="c"){                    
                    $sqlCuenta="select * from cuenta where idempresa='".$_POST["empresa"]."' and idcuenta='".$aux[1]."'";
                    $resultCuenta=mysql_query($sqlCuenta,$con) or die(mysql_error());
                    $cuenta = mysql_fetch_assoc($resultCuenta); 
                    if($cuenta["ctamayor"]==1){
                        echo "<div class='col-md-12 elementoseleccionmayor' id='der-".$_POST["panel"]."-".$cuenta["idcuenta"]."' name='der-".$_POST["panel"]."-".$cuenta["idcuenta"]."'>";
                        echo "<div class='col-md-12 lineapequena' >".$cuenta["codigo"]."</div>";
                        echo "<div class='col-md-12 lineagrande' >".$cuenta["nombre"]."</div>";
                        echo "<div class='col-md-12 lineapequena'>";
                        echo "<div class='col-md-3' style='padding:0px'>";
                        if($aux[2]==1){
                            echo "<select onchange=cambiatipo(".$cuenta["idcuenta"].",".$_POST["panel"].") name='tipo-".$_POST["panel"]."-".$cuenta["idcuenta"]."' id='tipo-".$_POST["panel"]."-".$cuenta["idcuenta"]."' style='float:left'><option value='1' selected>Cargos</option><option value='2'>Abonos</option><option value='3'>Saldos</option></select>";
                        }
                        if($aux[2]==2){
                            echo "<select onchange=cambiatipo(".$cuenta["idcuenta"].",".$_POST["panel"].") name='tipo-".$_POST["panel"]."-".$cuenta["idcuenta"]."' id='tipo-".$_POST["panel"]."-".$cuenta["idcuenta"]."' style='float:left'><option value='1'>Cargos</option><option value='2' selected>Abonos</option><option value='3'>Saldos</option></select>";
                        }
                        if($aux[2]==3){
                            echo "<select onchange=cambiatipo(".$cuenta["idcuenta"].",".$_POST["panel"].") name='tipo-".$_POST["panel"]."-".$cuenta["idcuenta"]."' id='tipo-".$_POST["panel"]."-".$cuenta["idcuenta"]."' style='float:left'><option value='1'>Cargos</option><option value='2'>Abonos</option><option value='3' selected>Saldos</option></select>";
                        }                                        
                        echo "</div>";
                        echo "<div class='col-md-2' style=' padding:0px; margin-left:5px' onclick=subir(".$cuenta["idcuenta"].",".$_POST["panel"].",'c')>SUBIR</div>";
                        echo "<div class='col-md-2' style=' padding:0px; margin-left:5px' onclick=bajar(".$cuenta["idcuenta"].",".$_POST["panel"].",'c')>BAJAR</div>";
                        echo "<div class='col-md-2' style=' padding:0px; margin-left:5px' onclick=seleccionaderc(".$cuenta["idcuenta"].",".$_POST["panel"].")>ElIMINAR</div>";
                        echo "</div>";                    
                        echo "</div>";                                                 
                    }else{
                        echo "<div class='col-md-12 elementoseleccion' id='der-".$_POST["panel"]."-".$cuenta["idcuenta"]."' name='der-".$_POST["panel"]."-".$cuenta["idcuenta"]."'>";
                        echo "<div class='col-md-12 lineapequena' >".$cuenta["codigo"]."</div>";
                        echo "<div class='col-md-12 lineagrande' >".$cuenta["nombre"]."</div>";
                        echo "<div class='col-md-12 lineapequena'>";
                        echo "<div class='col-md-3' style='padding:0px'>";
                        if($aux[2]==1){
                            echo "<select onchange=cambiatipo(".$cuenta["idcuenta"].",".$_POST["panel"].") name='tipo-".$_POST["panel"]."-".$cuenta["idcuenta"]."' id='tipo-".$_POST["panel"]."-".$cuenta["idcuenta"]."' style='float:left'><option value='1' selected>Cargos</option><option value='2'>Abonos</option><option value='3'>Saldos</option></select>";
                        }
                        if($aux[2]==2){
                            echo "<select onchange=cambiatipo(".$cuenta["idcuenta"].",".$_POST["panel"].") name='tipo-".$_POST["panel"]."-".$cuenta["idcuenta"]."' id='tipo-".$_POST["panel"]."-".$cuenta["idcuenta"]."' style='float:left'><option value='1'>Cargos</option><option value='2' selected>Abonos</option><option value='3'>Saldos</option></select>";
                        }
                        if($aux[2]==3){
                            echo "<select onchange=cambiatipo(".$cuenta["idcuenta"].",".$_POST["panel"].") name='tipo-".$_POST["panel"]."-".$cuenta["idcuenta"]."' id='tipo-".$_POST["panel"]."-".$cuenta["idcuenta"]."' style='float:left'><option value='1'>Cargos</option><option value='2'>Abonos</option><option value='3' selected>Saldos</option></select>";
                        }                                        
                        echo "</div>";
                        echo "<div class='col-md-2' style=' padding:0px; margin-left:5px' onclick=subir(".$cuenta["idcuenta"].",".$_POST["panel"].",'c')>SUBIR</div>";
                        echo "<div class='col-md-2' style=' padding:0px; margin-left:5px' onclick=bajar(".$cuenta["idcuenta"].",".$_POST["panel"].",'c')>BAJAR</div>";
                        echo "<div class='col-md-2' style=' padding:0px; margin-left:5px' onclick=seleccionaderc(".$cuenta["idcuenta"].",".$_POST["panel"].")>ELIMINAR</div>";
                        echo "</div>";                     
                        echo "</div>";                                                
                    }                    
                    
                    
                }else if($aux[0]=="a"){
                    $sqlAgrupacion="select * from agrupacion where idempresa='".$_POST["empresa"]."' and idagrupacion='".$aux[1]."'";
                    $resultAgrupacion=mysql_query($sqlAgrupacion,$con) or die(mysql_error());
                    $agrupacion = mysql_fetch_assoc($resultAgrupacion);                     
                    echo "<div class='col-md-12 elementoseleccion' id='der-".$_POST["panel"]."-".$agrupacion["idagrupacion"]."' name='der-".$_POST["panel"]."-".$agrupacion["idagrupacion"]."'>";
                    echo "<div class='col-md-12 lineapequena' >AGRUPACION</div>";
                    echo "<div class='col-md-12 lineagrande' >".$agrupacion["nombre"]."</div>";
                    echo "<div class='col-md-12 lineapequena'>";
                    echo "<div class='col-md-2' style=' padding:0px; margin-left:5px' onclick=subir(".$agrupacion["idagrupacion"].",".$_POST["panel"].",'a')>SUBIR</div>";
                    echo "<div class='col-md-2' style=' padding:0px; margin-left:5px' onclick=bajar(".$agrupacion["idagrupacion"].",".$_POST["panel"].",'a')>BAJAR</div>";
                    echo "<div class='col-md-2' style=' padding:0px; margin-left:5px' onclick=seleccionadera(".$agrupacion["idagrupacion"].",".$_POST["panel"].")>ELIMINAR</div>";
                    echo "</div>";                     
                    echo "</div>";                                                           
                }
                
                
                
            }
        }
    }
    
    if($_POST["tarea"]==4){
        $listaSeleccionados= explode("_",$_POST["seleccionados"]);
        $sql_listaCuenta="select * from cuenta where idempresa='".$_POST["empresa"]."' order by trim(nombre)";
        $result_listaCuenta=mysql_query($sql_listaCuenta,$con) or die(mysql_error());                                                                                 
        if(mysql_num_rows($result_listaCuenta)>0){
            while ($cuenta = mysql_fetch_assoc($result_listaCuenta)) {
                $band=0;
                for($i=0;$i<count($listaSeleccionados);$i++){
                    $aux=explode("-",$listaSeleccionados[$i]);
                    if($aux[0]=="c"){
                        if($aux[1]==$cuenta["idcuenta"]){
                            $band=1;
                        }                        
                    }

                }
                if($band==0){
                    if($cuenta["ctamayor"]==1){
                        echo "<div class='col-md-12 elementoseleccionmayor' id='izqc-".$_POST["panel"]."-".$cuenta["idcuenta"]."' name='izqc-".$_POST["panel"]."-".$cuenta["idcuenta"]."' onclick=seleccionaizqc(".$cuenta["idcuenta"].",".$_POST["panel"].")>";
                        echo "<div class='col-md-12 lineapequena'>".$cuenta["codigo"]."</div>";
                        echo "<div class='col-md-12 lineagrande'>".$cuenta["nombre"]."</div>";
                        echo "</div>";                                                 
                    }else{
                        echo "<div class='col-md-12 elementoseleccion' id='izqc-".$_POST["panel"]."-".$cuenta["idcuenta"]."' name='izqc-".$_POST["panel"]."-".$cuenta["idcuenta"]."' onclick=seleccionaizqc(".$cuenta["idcuenta"].",".$_POST["panel"].")>";
                        echo "<div class='col-md-12 lineapequena'>".$cuenta["codigo"]."</div>";
                        echo "<div class='col-md-12 lineagrande'>".$cuenta["nombre"]."</div>";
                        echo "</div>";                                                
                    }                                                            
                }
            }   
        }
    }  
    
    
    
    if($_POST["tarea"]==5){
        //echo $_POST["seleccionados"]."</br>";
        $listaSeleccionados= explode("_",$_POST["seleccionados"]);
        $sql_listaAgrupacion="select * from agrupacion where idempresa='".$_POST["empresa"]."' and idtipoagrupacion='".$_POST["panel"]."' order by trim(nombre)";
        $result_listaAgrupacion=mysql_query($sql_listaAgrupacion,$con) or die(mysql_error());                                                                                 
        if(mysql_num_rows($result_listaAgrupacion)>0){
            while ($agrupacion = mysql_fetch_assoc($result_listaAgrupacion)) {
                $band=0;
                for($j=0;$j<count($listaSeleccionados);$j++){
                    if($listaSeleccionados[$j]!=""){
                        $aux = explode("-",$listaSeleccionados[$j]);
                        if($aux[0]=="a"){
                            if($aux[1]==$agrupacion["idagrupacion"]){
                                $band=1;
                            }                            
                        }
                    }
                }

                if($band==0){
                    echo "<div class='col-md-12 elementoseleccion' id='izqa-".$_POST["panel"]."-".$agrupacion["idagrupacion"]."' name='izqa-".$_POST["panel"]."-".$agrupacion["idagrupacion"]."' onclick=seleccionaizqa(".$agrupacion["idagrupacion"].",".$_POST["panel"].")>";
                    echo "<div class='col-md-12 lineapequena'>AGRUPACION</div>";
                    echo "<div class='col-md-12 lineagrande'>".$agrupacion["nombre"]."</div>";
                    echo "</div>"; 
                }
            }                                    
        }                        
    }       
    
    mysql_close($con);

?>