<?php

    require_once('recursos/tcpdf/tcpdf.php');
    require_once("funciones/funciones.php");
    
    function myFunctionErrorHandler($errno, $errstr, $errfile, $errline)
    {
        /* Según el típo de error, lo procesamos */
        switch ($errno) {
           case E_WARNING:
                    return true;
                    break;            
            case E_NOTICE:
                    return true;
                    break;            
            default:
                    return false;
                    break;
         }
    }
    
    set_error_handler("myFunctionErrorHandler", E_WARNING);
    
    $con = Conexion();
    
    function calcula($conexion,$em,$an,$me){
        
        $con=$conexion;
        $buscaempresa = $em;
        $buscaano = $an;
        $buscames = $me;
        
        $buscaejercicio = "";
        
        $categoriasID=array();
        $categoriasVa=array();
        $razonesporca=array();
        $valoresporra=array();
        
        $sqlCategoria="select * from categoriarazon;";
        $resutlCategoria=mysql_query($sqlCategoria,$con) or die(mysql_error());
        $cont=0;
        while ($categoria=mysql_fetch_assoc($resutlCategoria)) {
            $categoriasID[$cont]=$categoria["idcategoriarazon"];
            $categoriasVa[$cont]=0;
            $razonesporca[$cont]="";
            $valoresporra[$cont]="";
            $cont++;
        }    
    

    
        $sqlEjercicio="select * from ejercicio where idempresa='".$buscaempresa."' and ejercicio='".$buscaano."';";
        $resutlEjercicio=mysql_query($sqlEjercicio,$con) or die(mysql_error());
        while ($ejercicio=mysql_fetch_assoc($resutlEjercicio)) {
            $buscaejercicio = $ejercicio["idejercicio"];
        }
        
        $sqlRazones = "select * from razonfinanciera order by idrazonfinanciera;";
        $resutlRazones=mysql_query($sqlRazones,$con) or die(mysql_error());    
        while ($razon=mysql_fetch_assoc($resutlRazones)) {
            $conttotal=0;
            
            $sqlCalculo = "select * from calculo where idrazonfinanciera='".$razon["idrazonfinanciera"]."' order by idcalculo, posicion;";
            $resutlCalculo=mysql_query($sqlCalculo,$con) or die(mysql_error());        
            while ($calculo=mysql_fetch_assoc($resutlCalculo)) {            
                //echo "--> Calculo:".$calculo["posicion"]." ".$calculo["tipo"]." ".$calculo["idclave"]."</br>";
                if($calculo["idclave"]!=""){
                    $sqlAsocia = "select * from asociaclave where idempresa='".$buscaempresa."' and idclave='".$calculo["idclave"]."' ";
                    $resultAsocia=mysql_query($sqlAsocia,$con) or die(mysql_error());
                    $bandera=mysql_num_rows($resultAsocia);
                    if($bandera==1){
                        $asocia=mysql_fetch_assoc($resultAsocia);
                    }else{
                        $conttotal++;
                    }
                }                     
            }
            $conttotal=0;
            if($conttotal==0){
               // echo $razon["idrazonfinanciera"]." - ".$razon["nombre"]."</br>";
                
                $matematica="";
                mysql_data_seek($resutlCalculo,0);
                while ($calculo=mysql_fetch_assoc($resutlCalculo)) {
                    if($calculo["tipo"]==1){ /*Clave*/
                        $val=0;
                        $cont01=0;                          
                        $sqlAsocia = "select * from asociaclave where idempresa='".$buscaempresa."' and idclave='".$calculo["idclave"]."' ";
                        $resultAsocia=mysql_query($sqlAsocia,$con) or die(mysql_error());                        
                        $aux01=mysql_num_rows($resultAsocia);
                        
                        if($aux01==2){
                            $val="(";
                        }
                                                            
                        while ($auxasocia=mysql_fetch_assoc($resultAsocia)) {                         
                                                                                                                      
                            $sqlcuenta="select * from cuenta where codigo='".$auxasocia["codigo"]."' and idempresa='".$buscaempresa."'";
                            $resultcuenta=mysql_query($sqlcuenta,$con) or die(mysql_error());
                            $cuenta=mysql_fetch_assoc($resultcuenta);
                            $numberelements=mysql_num_rows($resultcuenta);
                            $sqlvalor="select * from saldo where tipo='".$auxasocia["indice"]."' and ejercicio='".$buscaejercicio."' and idempresa='".$buscaempresa."' and idcuenta='".$cuenta["idcuenta"]."' ";
                            $resultvalor=mysql_query($sqlvalor,$con) or die(mysql_error());
                            $valor=mysql_fetch_assoc($resultvalor);
                            
                            if($cont01>0){
                                $val=$val."+";
                            }

                        if($buscames==1){
                            if($aux01>1){                                
                                if($valor["importes1"]!="0" && $valor["importes1"]!=""){
                                    if($calculo["promedio"]==1){
                                        $val=$val."((".$valor["saldoinicial"]."+".$valor["importes1"].")/2)";
                                    }else{
                                        $val=$val."(".$valor["importes1"].")";
                                    }
                                        
                                }else{
                                    $val=$val."(0)";  
                                }                                                                                                 
                            }else{                                                                                                
                            if($valor["importes1"]!="0" && $valor["importes1"]!=""){
                                if($calculo["promedio"]==1){
                                    $val="((".$valor["saldoinicial"]."+".$valor["importes1"].")/2)";
                                }else{
                                    $val="(".$valor["importes1"].")";
                                }                                        
                            }else{
                                $val="(0)";  
                                }                                                                                                 
                            }                            
                        }
                            if($buscames==2){
                                if($aux01>1){                                
                                    if($valor["importes2"]!="0" && $valor["importes2"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val=$val."((".$valor["saldoinicial"]."+".$valor["importes2"].")/2)";
                                        }else{
                                            $val=$val."(".$valor["importes2"].")";
                                        }                                        
                                    }else{
                                        $val=$val."(0)";  
                                    }                                                                                                 
                                }else{                                                                                                
                                    if($valor["importes2"]!="0" && $valor["importes2"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val="((".$valor["saldoinicial"]."+".$valor["importes2"].")/2)";
                                        }else{
                                            $val="(".$valor["importes2"].")";
                                        }
                                        
                                    }else{
                                        $val="(0)";  
                                    }                                                                                                 
                                }                            
                            }
                            if($buscames==3){
                                if($aux01>1){                                
                                    if($valor["importes3"]!="0" && $valor["importes3"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val=$val."((".$valor["saldoinicial"]."+".$valor["importes3"].")/2)"; 
                                        }else{
                                            $val=$val."(".$valor["importes3"].")"; 
                                        }                                        
                                    }else{
                                        $val=$val."(0)";  
                                    }                                                                                                 
                                }else{                                                                                                
                                    if($valor["importes3"]!="0" && $valor["importes3"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val="((".$valor["saldoinicial"]."+".$valor["importes3"].")/2)";
                                        }else{
                                            $val="(".$valor["importes3"].")";
                                        }                                        
                                    }else{
                                        $val="(0)";  
                                    }                                                                                                 
                                }                           
                            }
                            if($buscames==4){
                                if($aux01>1){                                
                                    if($valor["importes4"]!="0" && $valor["importes4"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val=$val."((".$valor["saldoinicial"]."+".$valor["importes4"].")/2)";
                                        }else{
                                            $val=$val."(".$valor["importes4"].")";
                                        }                                        
                                    }else{
                                        $val=$val."(0)";  
                                    }                                                                                                 
                                }else{                                                                                                
                                    if($valor["importes4"]!="0" && $valor["importes4"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val="((".$valor["saldoinicial"]."+".$valor["importes4"].")/2)"; 
                                        }else{
                                            $val="(".$valor["importes4"].")";
                                        }                                        
                                    }else{
                                        $val="(0)";  
                                    }                                                                                                 
                                }                            
                            }
                            if($buscames==5){
                                if($aux01>1){                                
                                    if($valor["importes5"]!="0" && $valor["importes5"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val=$val."((".$valor["saldoinicial"]."+".$valor["importes5"].")/2)";
                                        }else{
                                            $val=$val."(".$valor["importes5"].")";
                                        }                                        
                                    }else{
                                        $val=$val."(0)";  
                                    }                                                                                                 
                                }else{                                                                                                
                                    if($valor["importes5"]!="0" && $valor["importes5"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val="((".$valor["saldoinicial"]."+".$valor["importes5"].")/2)";
                                        }else{
                                            $val="(".$valor["importes5"].")";
                                        }                                        
                                    }else{
                                        $val="(0)";  
                                    }                                                                                                 
                                }                            
                            }
                            if($buscames==6){
                                if($aux01>1){                                
                                    if($valor["importes6"]!="0" && $valor["importes6"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val=$val."((".$valor["saldoinicial"]."+".$valor["importes6"].")/2)";
                                        }else{
                                            $val=$val."(".$valor["importes6"].")";
                                        }
                                        
                                    }else{
                                        $val=$val."(0)";  
                                    }                                                                                                 
                                }else{                                                                                                
                                    if($valor["importes6"]!="0" && $valor["importes6"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val="((".$valor["saldoinicial"]."+".$valor["importes6"].")/2)";
                                        }else{
                                            $val="(".$valor["importes6"].")"; 
                                        }                                        
                                    }else{
                                        $val="(0)";  
                                    }                                                                                                 
                                }                            
                            }   
                            if($buscames==7){
                                if($aux01>1){                                
                                    if($valor["importes7"]!="0" && $valor["importes7"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val=$val."((".$valor["saldoinicial"]."+".$valor["importes7"].")/2)";
                                        }else{
                                            $val=$val."(".$valor["importes7"].")";                                            
                                        }
                                    }else{
                                        $val=$val."(0)";  
                                    }                                                                                                 
                                }else{                                                                                                
                                    if($valor["importes7"]!="0" && $valor["importes7"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val="((".$valor["saldoinicial"]."+".$valor["importes7"].")/2)";
                                        }else{
                                            $val="(".$valor["importes7"].")";
                                        }                                        
                                    }else{
                                        $val="(0)";  
                                    }                                                                                                 
                                }                             
                            }
                            if($buscames==8){
                                if($aux01>1){                                
                                    if($valor["importes8"]!="0" && $valor["importes8"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val=$val."((".$valor["saldoinicial"]."+".$valor["importes8"].")/2)";
                                        }else{
                                            $val=$val."(".$valor["importes8"].")";
                                        }
                                        
                                    }else{
                                        $val=$val."(0)";  
                                    }                                                                                                 
                                }else{                                                                                                
                                    if($valor["importes8"]!="0" && $valor["importes8"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val="((".$valor["saldoinicial"]."+".$valor["importes8"].")/2)";
                                        }else{
                                            $val="(".$valor["importes8"].")";
                                        }                                        
                                    }else{
                                        $val="(0)";  
                                    }                                                                                                 
                                }                            
                            }
                            if($buscames==9){
                                if($aux01>1){                                
                                    if($valor["importes9"]!="0" && $valor["importes9"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val=$val."((".$valor["saldoinicial"]."+".$valor["importes9"].")/2)";
                                        }else{
                                            $val=$val."(".$valor["importes9"].")";
                                        }                                        
                                    }else{
                                        $val=$val."(0)";  
                                    }                                                                                                 
                                }else{                                                                                                
                                    if($valor["importes9"]!="0" && $valor["importes9"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val="((".$valor["saldoinicial"]."+".$valor["importes9"].")/2)";
                                        }else{
                                            $val="(".$valor["importes9"].")";
                                        }                                        
                                    }else{
                                        $val="(0)";  
                                    }                                                                                                 
                                }                           
                            }
                            if($buscames==10){
                                if($aux01>1){                                
                                    if($valor["importes10"]!="0" && $valor["importes10"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val=$val."((".$valor["saldoinicial"]."+".$valor["importes10"].")/2)";
                                        }else{
                                            $val=$val."(".$valor["importes10"].")";
                                        }                                        
                                    }else{
                                        $val=$val."(0)";  
                                    }                                                                                                 
                                }else{                                                                                                
                                    if($valor["importes10"]!="0" && $valor["importes10"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val="((".$valor["saldoinicial"]."+".$valor["importes10"].")/2)";
                                        }else{
                                            $val="(".$valor["importes10"].")";
                                        }                                        
                                    }else{
                                        $val="(0)";  
                                    }                                                                                                 
                                }                             
                            }
                            if($buscames==11){
                                if($aux01>1){                                
                                    if($valor["importes11"]!="0" && $valor["importes11"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val=$val."((".$valor["saldoinicial"]."+".$valor["importes11"].")/2)";
                                        }else{
                                            $val=$val."(".$valor["importes11"].")";
                                        }                                        
                                    }else{
                                        $val=$val."(0)";  
                                    }                                                                                                 
                                }else{                                                                                                
                                    if($valor["importes11"]!="0" && $valor["importes11"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val="((".$valor["saldoinicial"]."+".$valor["importes11"].")/2)";
                                        }else{
                                            $val="(".$valor["importes11"].")";
                                        }                                        
                                    }else{
                                        $val="(0)";  
                                    }                                                                                                 
                                }                            
                            }
                            if($buscames==12){
                                if($aux01>1){                                
                                    if($valor["importes12"]!="0" && $valor["importes12"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val=$val."((".$valor["saldoinicial"]."+".$valor["importes12"].")/2)";
                                        }else{
                                            $val=$val."(".$valor["importes12"].")";
                                        }                                        
                                    }else{
                                        $val=$val."(0)";  
                                    }                                                                                                 
                                }else{                                                                                                
                                    if($valor["importes12"]!="0" && $valor["importes12"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val="((".$valor["saldoinicial"]."+".$valor["importes12"].")/2)";
                                        }else{
                                            $val="(".$valor["importes12"].")";
                                        }                                        
                                    }else{
                                        $val="(0)";  
                                    }                                                                                                 
                                }                             
                            }   
                        
                        if($numberelements==0){
                            $val=0;
                        } 
                        
                        $cont01++;
                    }
                    
                        if($aux01==2){
                            $val=$val.")";
                        }                    
                        
                        if($calculo["operacion"]==1){
                            $matematica=$matematica."+".$val;
                        }else if($calculo["operacion"]==2){
                            $matematica=$matematica."-".$val;
                        }else if($calculo["operacion"]==3){
                            $matematica=$matematica."*".$val;
                        }else if($calculo["operacion"]==4){
                            $matematica=$matematica."/".$val;                       
                        }                    
                        
                    } else if($calculo["tipo"]==2){ /*Constante*/
                        if($calculo["operacion"]==1){
                            $matematica=$matematica."+".$calculo["valorconstante"];
                        }else if($calculo["operacion"]==2){
                            $matematica=$matematica."-".$calculo["valorconstante"];
                        }else if($calculo["operacion"]==3){
                            $matematica=$matematica."*".$calculo["valorconstante"];
                        }else if($calculo["operacion"]==4){
                            $matematica=$matematica."/".$calculo["valorconstante"];                       
                        }
                        
                    } else if($calculo["tipo"]==3){ /*Parentisis*/
                        
                        $operacion="";
                        if($calculo["operacion"]==1){
                            $operacion="+";
                        }else if($calculo["operacion"]==2){
                            $operacion="-";
                        }else if($calculo["operacion"]==3){
                            $operacion="*";
                        }else if($calculo["operacion"]==4){
                            $operacion="/";
                        }
                        
                        if($calculo["parentesis"]==1){
                            $matematica=$matematica.$operacion."(";
                        }else if($calculo["parentesis"]==2){
                            $matematica=$matematica.$operacion.")";
                        }                                        
                    }                
                }
                //echo $matematica."</br>";
                eval("\$var = $matematica;");
                //echo $matematica."</br>";
                //echo round($var,2)."</br></br>";            
                
                for($i=0;$i<count($categoriasID);$i++){
                    if($categoriasID[$i]==$razon["idcategoriarazon"]){
                        $categoriasVa[$i]=$categoriasVa[$i]+1;
                        $razonesporca[$i]=$razonesporca[$i].$razon["idrazonfinanciera"]."-";
                        $valoresporra[$i]=$valoresporra[$i].round($var,2).";";
                    }
                }            
                
                
            }
            
        }
        
        $totalcategorias=0;
        $totalrazones=0;
        for($i=0;$i<$cont;$i++){
            if($categoriasVa[$i]>0){
                $totalcategorias++;
                $totalrazones+=$categoriasVa[$i];
            }
        }                        
                
        $resultado=array();
        $resultado[0]=$razonesporca;
        $resultado[1]=$valoresporra;
        return $resultado;
 }    
    
    
    
    $buscaempresa = 1;
    $buscaano = 2015;
    $buscames = 9;
    $buscaejercicio = "";
        
    $categoriasID=array();
    $categoriasVa=array();
    $razonesporca=array();
    $valoresporra=array();
    
    $sqlEmpresa="select * from empresa where idempresa='".$buscaempresa."'";
    $resutlEmpresa=mysql_query($sqlEmpresa,$con) or die(mysql_error());
    $Empresa=mysql_fetch_assoc($resutlEmpresa);
    
    $sqlCategoria="select * from categoriarazon;";
    $resutlCategoria=mysql_query($sqlCategoria,$con) or die(mysql_error());
    $cont=0;
    while ($categoria=mysql_fetch_assoc($resutlCategoria)) {
        $categoriasID[$cont]=$categoria["idcategoriarazon"];
        $categoriasVa[$cont]=0;
        $razonesporca[$cont]="";
        $valoresporra[$cont]="";
        $cont++;
    } 
    
    $sqlEjercicio="select * from ejercicio where idempresa='".$buscaempresa."' and ejercicio='".$buscaano."';";
    $resutlEjercicio=mysql_query($sqlEjercicio,$con) or die(mysql_error());
    while ($ejercicio=mysql_fetch_assoc($resutlEjercicio)) {
        $buscaejercicio = $ejercicio["idejercicio"];
    }
    
    $sqlRazones = "select * from razonfinanciera order by idrazonfinanciera;";
    $resutlRazones=mysql_query($sqlRazones,$con) or die(mysql_error());   
    $numeroRazones=mysql_num_rows($resutlRazones);
    while ($razon=mysql_fetch_assoc($resutlRazones)) {
        $conttotal=0;
        
        $sqlCalculo = "select * from calculo where idrazonfinanciera='".$razon["idrazonfinanciera"]."' order by idcalculo, posicion;";
        $resutlCalculo=mysql_query($sqlCalculo,$con) or die(mysql_error());        
        while ($calculo=mysql_fetch_assoc($resutlCalculo)) {            
            if($calculo["idclave"]!=""){
                $sqlAsocia = "select * from asociaclave where idempresa='".$buscaempresa."' and idclave='".$calculo["idclave"]."' ";
                $resultAsocia=mysql_query($sqlAsocia,$con) or die(mysql_error());
                $bandera=mysql_num_rows($resultAsocia);
                if($bandera==1){
                    $asocia=mysql_fetch_assoc($resultAsocia);
                }else{
                    $conttotal++;
                }
            }                     
        }
        $conttotal=0;
        if($conttotal==0){

            $matematica="";
            mysql_data_seek($resutlCalculo,0);
            while ($calculo=mysql_fetch_assoc($resutlCalculo)) {
                if($calculo["tipo"]==1){ /*Clave*/
                    $val=0;
                    $cont01=0;                    
                    $sqlAsocia = "select * from asociaclave where idempresa='".$buscaempresa."' and idclave='".$calculo["idclave"]."' ";
                    $resultAsocia=mysql_query($sqlAsocia,$con) or die(mysql_error());
                    $aux01=mysql_num_rows($resultAsocia);
                    
                    if($aux01==2){
                        $val="(";
                    }
                                                            
                   while ($auxasocia=mysql_fetch_assoc($resultAsocia)) {                   
                        $sqlcuenta="select * from cuenta where codigo='".$auxasocia["codigo"]."' and idempresa='".$buscaempresa."'";
                        $resultcuenta=mysql_query($sqlcuenta,$con) or die(mysql_error());
                        $cuenta=mysql_fetch_assoc($resultcuenta);
                        $numberelements=mysql_num_rows($resultcuenta);
                        $sqlvalor="select * from saldo where tipo='".$auxasocia["indice"]."' and ejercicio='".$buscaejercicio."' and idempresa='".$buscaempresa."' and idcuenta='".$cuenta["idcuenta"]."' ";
                        $resultvalor=mysql_query($sqlvalor,$con) or die(mysql_error());
                        $valor=mysql_fetch_assoc($resultvalor);

                            if($cont01>0){
                                $val=$val."+";
                            }
                        
                            if($buscames==1){
                                if($aux01>1){                                
                                    if($valor["importes1"]!="0" && $valor["importes1"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val=$val."((".$valor["saldoinicial"]."+".$valor["importes1"].")/2)";
                                        }else{
                                            $val=$val."(".$valor["importes1"].")";
                                        }
                                        
                                    }else{
                                        $val=$val."(0)";  
                                    }                                                                                                 
                                }else{                                                                                                
                                    if($valor["importes1"]!="0" && $valor["importes1"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val="((".$valor["saldoinicial"]."+".$valor["importes1"].")/2)";
                                        }else{
                                            $val="(".$valor["importes1"].")";
                                        }                                        
                                    }else{
                                        $val="(0)";  
                                    }                                                                                                 
                                }                            
                            }
                            if($buscames==2){
                                if($aux01>1){                                
                                    if($valor["importes2"]!="0" && $valor["importes2"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val=$val."((".$valor["saldoinicial"]."+".$valor["importes2"].")/2)";
                                        }else{
                                            $val=$val."(".$valor["importes2"].")";
                                        }                                        
                                    }else{
                                        $val=$val."(0)";  
                                    }                                                                                                 
                                }else{                                                                                                
                                    if($valor["importes2"]!="0" && $valor["importes2"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val="((".$valor["saldoinicial"]."+".$valor["importes2"].")/2)";
                                        }else{
                                            $val="(".$valor["importes2"].")";
                                        }
                                        
                                    }else{
                                        $val="(0)";  
                                    }                                                                                                 
                                }                            
                            }
                            if($buscames==3){
                                if($aux01>1){                                
                                    if($valor["importes3"]!="0" && $valor["importes3"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val=$val."((".$valor["saldoinicial"]."+".$valor["importes3"].")/2)"; 
                                        }else{
                                            $val=$val."(".$valor["importes3"].")"; 
                                        }                                        
                                    }else{
                                        $val=$val."(0)";  
                                    }                                                                                                 
                                }else{                                                                                                
                                    if($valor["importes3"]!="0" && $valor["importes3"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val="((".$valor["saldoinicial"]."+".$valor["importes3"].")/2)";
                                        }else{
                                            $val="(".$valor["importes3"].")";
                                        }                                        
                                    }else{
                                        $val="(0)";  
                                    }                                                                                                 
                                }                           
                            }
                            if($buscames==4){
                                if($aux01>1){                                
                                    if($valor["importes4"]!="0" && $valor["importes4"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val=$val."((".$valor["saldoinicial"]."+".$valor["importes4"].")/2)";
                                        }else{
                                            $val=$val."(".$valor["importes4"].")";
                                        }                                        
                                    }else{
                                        $val=$val."(0)";  
                                    }                                                                                                 
                                }else{                                                                                                
                                    if($valor["importes4"]!="0" && $valor["importes4"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val="((".$valor["saldoinicial"]."+".$valor["importes4"].")/2)"; 
                                        }else{
                                            $val="(".$valor["importes4"].")";
                                        }                                        
                                    }else{
                                        $val="(0)";  
                                    }                                                                                                 
                                }                            
                            }
                            if($buscames==5){
                                if($aux01>1){                                
                                    if($valor["importes5"]!="0" && $valor["importes5"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val=$val."((".$valor["saldoinicial"]."+".$valor["importes5"].")/2)";
                                        }else{
                                            $val=$val."(".$valor["importes5"].")";
                                        }                                        
                                    }else{
                                        $val=$val."(0)";  
                                    }                                                                                                 
                                }else{                                                                                                
                                    if($valor["importes5"]!="0" && $valor["importes5"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val="((".$valor["saldoinicial"]."+".$valor["importes5"].")/2)";
                                        }else{
                                            $val="(".$valor["importes5"].")";
                                        }                                        
                                    }else{
                                        $val="(0)";  
                                    }                                                                                                 
                                }                            
                            }
                            if($buscames==6){
                                if($aux01>1){                                
                                    if($valor["importes6"]!="0" && $valor["importes6"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val=$val."((".$valor["saldoinicial"]."+".$valor["importes6"].")/2)";
                                        }else{
                                            $val=$val."(".$valor["importes6"].")";
                                        }
                                        
                                    }else{
                                        $val=$val."(0)";  
                                    }                                                                                                 
                                }else{                                                                                                
                                    if($valor["importes6"]!="0" && $valor["importes6"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val="((".$valor["saldoinicial"]."+".$valor["importes6"].")/2)";
                                        }else{
                                            $val="(".$valor["importes6"].")"; 
                                        }                                        
                                    }else{
                                        $val="(0)";  
                                    }                                                                                                 
                                }                            
                            }   
                            if($buscames==7){
                                if($aux01>1){                                
                                    if($valor["importes7"]!="0" && $valor["importes7"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val=$val."((".$valor["saldoinicial"]."+".$valor["importes7"].")/2)";
                                        }else{
                                            $val=$val."(".$valor["importes7"].")";                                            
                                        }
                                    }else{
                                        $val=$val."(0)";  
                                    }                                                                                                 
                                }else{                                                                                                
                                    if($valor["importes7"]!="0" && $valor["importes7"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val="((".$valor["saldoinicial"]."+".$valor["importes7"].")/2)";
                                        }else{
                                            $val="(".$valor["importes7"].")";
                                        }                                        
                                    }else{
                                        $val="(0)";  
                                    }                                                                                                 
                                }                             
                            }
                            if($buscames==8){
                                if($aux01>1){                                
                                    if($valor["importes8"]!="0" && $valor["importes8"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val=$val."((".$valor["saldoinicial"]."+".$valor["importes8"].")/2)";
                                        }else{
                                            $val=$val."(".$valor["importes8"].")";
                                        }
                                        
                                    }else{
                                        $val=$val."(0)";  
                                    }                                                                                                 
                                }else{                                                                                                
                                    if($valor["importes8"]!="0" && $valor["importes8"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val="((".$valor["saldoinicial"]."+".$valor["importes8"].")/2)";
                                        }else{
                                            $val="(".$valor["importes8"].")";
                                        }                                        
                                    }else{
                                        $val="(0)";  
                                    }                                                                                                 
                                }                            
                            }
                            if($buscames==9){
                                if($aux01>1){                                
                                    if($valor["importes9"]!="0" && $valor["importes9"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val=$val."((".$valor["saldoinicial"]."+".$valor["importes9"].")/2)";
                                        }else{
                                            $val=$val."(".$valor["importes9"].")";
                                        }                                        
                                    }else{
                                        $val=$val."(0)";  
                                    }                                                                                                 
                                }else{                                                                                                
                                    if($valor["importes9"]!="0" && $valor["importes9"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val="((".$valor["saldoinicial"]."+".$valor["importes9"].")/2)";
                                        }else{
                                            $val="(".$valor["importes9"].")";
                                        }                                        
                                    }else{
                                        $val="(0)";  
                                    }                                                                                                 
                                }                           
                            }
                            if($buscames==10){
                                if($aux01>1){                                
                                    if($valor["importes10"]!="0" && $valor["importes10"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val=$val."((".$valor["saldoinicial"]."+".$valor["importes10"].")/2)";
                                        }else{
                                            $val=$val."(".$valor["importes10"].")";
                                        }                                        
                                    }else{
                                        $val=$val."(0)";  
                                    }                                                                                                 
                                }else{                                                                                                
                                    if($valor["importes10"]!="0" && $valor["importes10"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val="((".$valor["saldoinicial"]."+".$valor["importes10"].")/2)";
                                        }else{
                                            $val="(".$valor["importes10"].")";
                                        }                                        
                                    }else{
                                        $val="(0)";  
                                    }                                                                                                 
                                }                             
                            }
                            if($buscames==11){
                                if($aux01>1){                                
                                    if($valor["importes11"]!="0" && $valor["importes11"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val=$val."((".$valor["saldoinicial"]."+".$valor["importes11"].")/2)";
                                        }else{
                                            $val=$val."(".$valor["importes11"].")";
                                        }                                        
                                    }else{
                                        $val=$val."(0)";  
                                    }                                                                                                 
                                }else{                                                                                                
                                    if($valor["importes11"]!="0" && $valor["importes11"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val="((".$valor["saldoinicial"]."+".$valor["importes11"].")/2)";
                                        }else{
                                            $val="(".$valor["importes11"].")";
                                        }                                        
                                    }else{
                                        $val="(0)";  
                                    }                                                                                                 
                                }                            
                            }
                            if($buscames==12){
                                if($aux01>1){                                
                                    if($valor["importes12"]!="0" && $valor["importes12"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val=$val."((".$valor["saldoinicial"]."+".$valor["importes12"].")/2)";
                                        }else{
                                            $val=$val."(".$valor["importes12"].")";
                                        }                                        
                                    }else{
                                        $val=$val."(0)";  
                                    }                                                                                                 
                                }else{                                                                                                
                                    if($valor["importes12"]!="0" && $valor["importes12"]!=""){
                                        if($calculo["promedio"]==1){
                                            $val="((".$valor["saldoinicial"]."+".$valor["importes12"].")/2)";
                                        }else{
                                            $val="(".$valor["importes12"].")";
                                        }                                        
                                    }else{
                                        $val="(0)";  
                                    }                                                                                                 
                                }                             
                            } 
                        
                            if($numberelements==0){
                                $val=0;
                            }
                            
                            $cont01++;
                    }
                    
                    if($aux01==2){
                        $val=$val.")";
                    }                    
                    
                    if($calculo["operacion"]==1){
                        $matematica=$matematica."+".$val;
                    }else if($calculo["operacion"]==2){
                        $matematica=$matematica."-".$val;
                    }else if($calculo["operacion"]==3){
                        $matematica=$matematica."*".$val;
                    }else if($calculo["operacion"]==4){
                        $matematica=$matematica."/".$val;                       
                    }                    
                    
                } else if($calculo["tipo"]==2){ /*Constante*/
                    if($calculo["operacion"]==1){
                        $matematica=$matematica."+".$calculo["valorconstante"];
                    }else if($calculo["operacion"]==2){
                        $matematica=$matematica."-".$calculo["valorconstante"];
                    }else if($calculo["operacion"]==3){
                        $matematica=$matematica."*".$calculo["valorconstante"];
                    }else if($calculo["operacion"]==4){
                        $matematica=$matematica."/".$calculo["valorconstante"];                       
                    }
                                        
                } else if($calculo["tipo"]==3){ /*Parentisis*/
                    
                    $operacion="";
                    if($calculo["operacion"]==1){
                        $operacion="+";
                    }else if($calculo["operacion"]==2){
                        $operacion="-";
                    }else if($calculo["operacion"]==3){
                        $operacion="*";
                    }else if($calculo["operacion"]==4){
                        $operacion="/";
                    }
                                                            
                    if($calculo["parentesis"]==1){
                        $matematica=$matematica.$operacion."(";
                    }else if($calculo["parentesis"]==2){
                        $matematica=$matematica.$operacion.")";
                    }                                        
                }                
            }
            
            eval("\$var = $matematica;");            
            
            for($i=0;$i<count($categoriasID);$i++){
                if($categoriasID[$i]==$razon["idcategoriarazon"]){
                    $categoriasVa[$i]=$categoriasVa[$i]+1;
                    $razonesporca[$i]=$razonesporca[$i].$razon["idrazonfinanciera"]."-";
                    $valoresporra[$i]=$valoresporra[$i].round($var,2).";";
                }
            }            

            
        }

    }
    
 
    $totalcategorias=0;
    $totalrazones=0;
    for($i=0;$i<$cont;$i++){
        if($categoriasVa[$i]>0){
            $totalcategorias++;
            $totalrazones+=$categoriasVa[$i];
        }
    } 
    
    $separacion=  round(((50-$totalrazones)/$totalcategorias),0,PHP_ROUND_HALF_DOWN);
    

    
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
     
    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Gaag Desarrollo Empresarial');
    $pdf->SetTitle('Resumen de Indicadores Financieras');

    // disable header and footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, 0);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
        require_once(dirname(__FILE__).'/lang/eng.php');
        $pdf->setLanguageArray($l);
    }
           
    $meses=array();
    
    $meses[0]="Enero";
    $meses[1]="Febrero";
    $meses[2]="Marzo";
    $meses[3]="Abril";
    $meses[4]="Mayo";
    $meses[5]="Junio";
    $meses[6]="Julio";
    $meses[7]="Agosto";
    $meses[8]="Septiembre";
    $meses[9]="Octubre";
    $meses[10]="Noviembre";
    $meses[11]="Diciembre";
    
    $me[0]="Ene";
    $me[1]="Feb";
    $me[2]="Mar";
    $me[3]="Abr";
    $me[4]="May";
    $me[5]="Jun";
    $me[6]="Jul";
    $me[7]="Ago";
    $me[8]="Sep";
    $me[9]="Oct";
    $me[10]="Nov";
    $me[11]="Dic";
    
    
    
    $pdf->AddPage('P', 'A4'); 
    $style1 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '10,10', 'color' => array(50, 128, 100));    
    $pdf->Image('recursos/logo300px.jpg', 65, 100, 75, 32, 'JPG', 'http://www.gaagdesarrolloempresarial.com', '', true, 150, '', false, false, 0, false, false, false);
    $pdf->Line(30, 140, 180, 140);
    $pdf->SetFont('Helvetica', '', 16);
    $pdf->Text(30, 143, 'Resumen de Indicadores Financieros');
    $pdf->Line(30, 153, 180, 153);
    
    $pdf->SetFont('Helvetica', '', 8);    
    $pdf->SetTextColor(126,130,109);
    $pdf->Text(30, 160, 'Preparado por');    
    $pdf->SetFont('Helvetica', '', 12);        
    $pdf->SetTextColor(0,0,0);
    $pdf->Text(30, 165, 'GAAG Desarrollo Empresarial');
    
    $pdf->SetFont('Helvetica', '', 8);    
    $pdf->SetTextColor(126,130,109);
    $pdf->Text(30, 174, 'Para');    
    $pdf->SetFont('Helvetica', '', 12);        
    $pdf->SetTextColor(0,0,0);
    $pdf->Text(30, 179,$Empresa["nombre"]);    
    
    $pdf->SetFont('Helvetica', '', 8);
    $pdf->SetTextColor(126,130,109);    
    $pdf->Text(30, 189, 'Periodo en Revisión');    
    $pdf->SetFont('Helvetica', '', 12);
    $pdf->SetTextColor(0,0,0);    
    $pdf->Text(30, 194,$meses[($buscames-1)].' '.$buscaano);    
    
    
    $pdf->SetFont('Helvetica', '', 8);
    $pdf->SetTextColor(126,130,109);    
    $pdf->Text(30, 203, 'Creado el');     
    $pdf->SetFont('Helvetica', '', 9);
    $pdf->SetTextColor(0,0,0);  
    $dia=date("d");
    $mes=date("m");
    $ano=date("Y");
    $pdf->Text(30, 208,$dia." de ".$meses[($mes-1)]." de ".$ano);
    
    $pdf->AddPage('P', 'A4');   
    $pdf->Image('recursos/logo300px.jpg', 10, 10, 30, 12.8, 'JPG', 'http://www.gaagdesarrolloempresarial.com', '', true, 150, '', false, false, 0, false, false, false);
    $pdf->Line(10, 25, 200, 25);
    $pdf->SetFont('Helvetica', '', 10);
    $pdf->Text(9, 26, 'Balance General '.$Empresa["nombre"]);  
    $pdf->SetFont('Helvetica', '', 7);
    
    
    $NomActCir=array();
    $SalActCir=array();
    $NomActFij=array();
    $SalActFij=array();
    $NomActDif=array();
    $SalActDif=array();
    
    $NomPasCir=array();
    $SalPasCir=array();  
    $NomPasFij=array();
    $SalPasFij=array();    
    
    $NomCapital=array();
    $SalCapital=array(); 
    
    $NomUtilidad=array();
    $SalUtilidad=array();
    
    /*Activo Circulante*/
    $sqlBal01="select * from cuenta where idempresa='".$buscaempresa."' and tipo='A' and ctamayor=1 and (codigo like '10%' or codigo like '11%')";
    $resultBal01=mysql_query($sqlBal01,$con) or die(mysql_error());
    while ($bal01=mysql_fetch_assoc($resultBal01)) {
        $sqlBal02="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$bal01["idcuenta"]."' and tipo=1 ";
        $resultBal02=mysql_query($sqlBal02,$con) or die(mysql_error());
        $bal02=mysql_fetch_assoc($resultBal02);        
        if($buscames==1 && ($bal02["importes1"]!=0 && $bal02["importes1"]!=null)){
            $NomActCir[count($NomActCir)]=$bal01["nombre"];
            $SalActCir[count($SalActCir)]=$bal02["importes1"];
        }
        if($buscames==2 && ($bal02["importes2"]!=0 && $bal02["importes2"]!=null)){
            $NomActCir[count($NomActCir)]=$bal01["nombre"];
            $SalActCir[count($SalActCir)]=$bal02["importes2"];
        }
        if($buscames==3 && ($bal02["importes3"]!=0 && $bal02["importes3"]!=null)){
            $NomActCir[count($NomActCir)]=$bal01["nombre"];
            $SalActCir[count($SalActCir)]=$bal02["importes3"];
        }
        if($buscames==4 && ($bal02["importes4"]!=0 && $bal02["importes4"]!=null)){
            $NomActCir[count($NomActCir)]=$bal01["nombre"];
            $SalActCir[count($SalActCir)]=$bal02["importes4"];
        }
        if($buscames==5 && ($bal02["importes5"]!=0 && $bal02["importes5"]!=null)){
            $NomActCir[count($NomActCir)]=$bal01["nombre"];
            $SalActCir[count($SalActCir)]=$bal02["importes5"];
        }        
        if($buscames==6 && ($bal02["importes6"]!=0 && $bal02["importes6"]!=null)){
            $NomActCir[count($NomActCir)]=$bal01["nombre"];
            $SalActCir[count($SalActCir)]=$bal02["importes6"];
        }
        if($buscames==7 && ($bal02["importes7"]!=0 && $bal02["importes7"]!=null)){
            $NomActCir[count($NomActCir)]=$bal01["nombre"];
            $SalActCir[count($SalActCir)]=$bal02["importes7"];
        }
        if($buscames==8 && ($bal02["importes8"]!=0 && $bal02["importes8"]!=null)){
            $NomActCir[count($NomActCir)]=$bal01["nombre"];
            $SalActCir[count($SalActCir)]=$bal02["importes8"];
        }
        if($buscames==9 && ($bal02["importes9"]!=0 && $bal02["importes9"]!=null)){
            $NomActCir[count($NomActCir)]=$bal01["nombre"];
            $SalActCir[count($SalActCir)]=$bal02["importes9"];
        }
        if($buscames==10 && ($bal02["importes10"]!=0 && $bal02["importes10"]!=null)){
            $NomActCir[count($NomActCir)]=$bal01["nombre"];
            $SalActCir[count($SalActCir)]=$bal02["importes10"];
        }
        if($buscames==11 && ($bal02["importes11"]!=0 && $bal02["importes11"]!=null)){
            $NomActCir[count($NomActCir)]=$bal01["nombre"];
            $SalActCir[count($SalActCir)]=$bal02["importes11"];
        }
        if($buscames==12 && ($bal02["importes12"]!=0 && $bal02["importes12"]!=null)){
            $NomActCir[count($NomActCir)]=$bal01["nombre"];
            $SalActCir[count($SalActCir)]=$bal02["importes12"];
        }               
    }
        
    /*Activo Fijo*/
    $sqlBal01="select * from cuenta where idempresa='".$buscaempresa."' and (tipo='A' or tipo='B') and ctamayor=1 and (codigo like '12%' or codigo like '13%')";
    $resultBal01=mysql_query($sqlBal01,$con) or die(mysql_error());
    while ($bal01=mysql_fetch_assoc($resultBal01)) {
        $sqlBal02="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$bal01["idcuenta"]."' and tipo=1 ";
        $resultBal02=mysql_query($sqlBal02,$con) or die(mysql_error());
        $bal02=mysql_fetch_assoc($resultBal02);        
        if($buscames==1 && ($bal02["importes1"]!=0 && $bal02["importes1"]!=null)){
            $NomActFij[count($NomActFij)]=$bal01["nombre"];
            $SalActFij[count($SalActFij)]=$bal02["importes1"];
        }
        if($buscames==2 && ($bal02["importes2"]!=0 && $bal02["importes2"]!=null)){
            $NomActFij[count($NomActFij)]=$bal01["nombre"];
            $SalActFij[count($SalActFij)]=$bal02["importes2"];
        }
        if($buscames==3 && ($bal02["importes3"]!=0 && $bal02["importes3"]!=null)){
            $NomActFij[count($NomActFij)]=$bal01["nombre"];
            $SalActFij[count($SalActFij)]=$bal02["importes3"];
        }
        if($buscames==4 && ($bal02["importes4"]!=0 && $bal02["importes4"]!=null)){
            $NomActFij[count($NomActFij)]=$bal01["nombre"];
            $SalActFij[count($SalActFij)]=$bal02["importes4"];
        }
        if($buscames==5 && ($bal02["importes5"]!=0 && $bal02["importes5"]!=null)){
            $NomActFij[count($NomActFij)]=$bal01["nombre"];
            $SalActFij[count($SalActFij)]=$bal02["importes5"];
        }        
        if($buscames==6 && ($bal02["importes6"]!=0 && $bal02["importes6"]!=null)){
            $NomActFij[count($NomActFij)]=$bal01["nombre"];
            $SalActFij[count($SalActFij)]=$bal02["importes6"];
        }
        if($buscames==7 && ($bal02["importes7"]!=0 && $bal02["importes7"]!=null)){
            $NomActFij[count($NomActFij)]=$bal01["nombre"];
            $SalActFij[count($SalActFij)]=$bal02["importes7"];
        }
        if($buscames==8 && ($bal02["importes8"]!=0 && $bal02["importes8"]!=null)){
            $NomActFij[count($NomActFij)]=$bal01["nombre"];
            $SalActFij[count($SalActFij)]=$bal02["importes8"];
        }
        if($buscames==9 && ($bal02["importes9"]!=0 && $bal02["importes9"]!=null)){
            $NomActFij[count($NomActFij)]=$bal01["nombre"];
            $SalActFij[count($SalActFij)]=$bal02["importes9"];
        }
        if($buscames==10 && ($bal02["importes10"]!=0 && $bal02["importes10"]!=null)){
            $NomActFij[count($NomActFij)]=$bal01["nombre"];
            $SalActFij[count($SalActFij)]=$bal02["importes10"];
        }
        if($buscames==11 && ($bal02["importes11"]!=0 && $bal02["importes11"]!=null)){
            $NomActFij[count($NomActFij)]=$bal01["nombre"];
            $SalActFij[count($SalActFij)]=$bal02["importes11"];
        }
        if($buscames==12 && ($bal02["importes12"]!=0 && $bal02["importes12"]!=null)){
            $NomActFij[count($NomActFij)]=$bal01["nombre"];
            $SalActFij[count($SalActFij)]=$bal02["importes12"];
        }               
    }
    
    /*Activo Diferido*/
    $sqlBal01="select * from cuenta where idempresa='".$buscaempresa."' and tipo='A' and ctamayor=1 and afectable=0 and (codigo like '14%' or codigo like '15%')";
    $resultBal01=mysql_query($sqlBal01,$con) or die(mysql_error());
    while ($bal01=mysql_fetch_assoc($resultBal01)) {
        $sqlBal02="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$bal01["idcuenta"]."' and tipo=1 ";
        $resultBal02=mysql_query($sqlBal02,$con) or die(mysql_error());
        $bal02=mysql_fetch_assoc($resultBal02);        
        if($buscames==1 && ($bal02["importes1"]!=0 && $bal02["importes1"]!=null)){
            $NomActDif[count($NomActDif)]=$bal01["nombre"];
            $SalActDif[count($SalActDif)]=$bal02["importes1"];
        }
        if($buscames==2 && ($bal02["importes2"]!=0 && $bal02["importes2"]!=null)){
            $NomActDif[count($NomActDif)]=$bal01["nombre"];
            $SalActDif[count($SalActDif)]=$bal02["importes2"];
        }
        if($buscames==3 && ($bal02["importes3"]!=0 && $bal02["importes3"]!=null)){
            $NomActDif[count($NomActDif)]=$bal01["nombre"];
            $SalActDif[count($SalActDif)]=$bal02["importes3"];
        }
        if($buscames==4 && ($bal02["importes4"]!=0 && $bal02["importes4"]!=null)){
            $NomActDif[count($NomActDif)]=$bal01["nombre"];
            $SalActDif[count($SalActDif)]=$bal02["importes4"];
        }
        if($buscames==5 && ($bal02["importes5"]!=0 && $bal02["importes5"]!=null)){
            $NomActDif[count($NomActDif)]=$bal01["nombre"];
            $SalActDif[count($SalActDif)]=$bal02["importes5"];
        }        
        if($buscames==6 && ($bal02["importes6"]!=0 && $bal02["importes6"]!=null)){
            $NomActDif[count($NomActDif)]=$bal01["nombre"];
            $SalActDif[count($SalActDif)]=$bal02["importes6"];
        }
        if($buscames==7 && ($bal02["importes7"]!=0 && $bal02["importes7"]!=null)){
            $NomActDif[count($NomActDif)]=$bal01["nombre"];
            $SalActDif[count($SalActDif)]=$bal02["importes7"];
        }
        if($buscames==8 && ($bal02["importes8"]!=0 && $bal02["importes8"]!=null)){
            $NomActDif[count($NomActDif)]=$bal01["nombre"];
            $SalActDif[count($SalActDif)]=$bal02["importes8"];
        }
        if($buscames==9 && ($bal02["importes9"]!=0 && $bal02["importes9"]!=null)){
            $NomActDif[count($NomActDif)]=$bal01["nombre"];
            $SalActDif[count($SalActDif)]=$bal02["importes9"];
        }
        if($buscames==10 && ($bal02["importes10"]!=0 && $bal02["importes10"]!=null)){
            $NomActDif[count($NomActDif)]=$bal01["nombre"];
            $SalActDif[count($SalActDif)]=$bal02["importes10"];
        }
        if($buscames==11 && ($bal02["importes11"]!=0 && $bal02["importes11"]!=null)){
            $NomActDif[count($NomActDif)]=$bal01["nombre"];
            $SalActDif[count($SalActDif)]=$bal02["importes11"];
        }
        if($buscames==12 && ($bal02["importes12"]!=0 && $bal02["importes12"]!=null)){
            $NomActDif[count($NomActDif)]=$bal01["nombre"];
            $SalActDif[count($SalActDif)]=$bal02["importes12"];
        }               
    }    
    
    
    /*Pasivo Circulante*/
    $sqlBal01="select * from cuenta where idempresa='".$buscaempresa."' and tipo='D' and ctamayor=1 and (codigo like '20%')";
    $resultBal01=mysql_query($sqlBal01,$con) or die(mysql_error());
    while ($bal01=mysql_fetch_assoc($resultBal01)) {
        $sqlBal02="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$bal01["idcuenta"]."' and tipo=1 ";
        $resultBal02=mysql_query($sqlBal02,$con) or die(mysql_error());
        $bal02=mysql_fetch_assoc($resultBal02);        
        if($buscames==1 && ($bal02["importes1"]!=0 && $bal02["importes1"]!=null)){
            $NomPasCir[count($NomPasCir)]=$bal01["nombre"];
            $SalPasCir[count($SalPasCir)]=$bal02["importes1"];
        }
        if($buscames==2 && ($bal02["importes2"]!=0 && $bal02["importes2"]!=null)){
            $NomPasCir[count($NomPasCir)]=$bal01["nombre"];
            $SalPasCir[count($SalPasCir)]=$bal02["importes2"];
        }
        if($buscames==3 && ($bal02["importes3"]!=0 && $bal02["importes3"]!=null)){
            $NomPasCir[count($NomPasCir)]=$bal01["nombre"];
            $SalPasCir[count($SalPasCir)]=$bal02["importes3"];
        }
        if($buscames==4 && ($bal02["importes4"]!=0 && $bal02["importes4"]!=null)){
            $NomPasCir[count($NomPasCir)]=$bal01["nombre"];
            $SalPasCir[count($SalPasCir)]=$bal02["importes4"];
        }
        if($buscames==5 && ($bal02["importes5"]!=0 && $bal02["importes5"]!=null)){
            $NomPasCir[count($NomPasCir)]=$bal01["nombre"];
            $SalPasCir[count($SalPasCir)]=$bal02["importes5"];
        }        
        if($buscames==6 && ($bal02["importes6"]!=0 && $bal02["importes6"]!=null)){
            $NomPasCir[count($NomPasCir)]=$bal01["nombre"];
            $SalPasCir[count($SalPasCir)]=$bal02["importes6"];
        }
        if($buscames==7 && ($bal02["importes7"]!=0 && $bal02["importes7"]!=null)){
            $NomPasCir[count($NomPasCir)]=$bal01["nombre"];
            $SalPasCir[count($SalPasCir)]=$bal02["importes7"];
        }
        if($buscames==8 && ($bal02["importes8"]!=0 && $bal02["importes8"]!=null)){
            $NomPasCir[count($NomPasCir)]=$bal01["nombre"];
            $SalPasCir[count($SalPasCir)]=$bal02["importes8"];
        }
        if($buscames==9 && ($bal02["importes9"]!=0 && $bal02["importes9"]!=null)){
            $NomPasCir[count($NomPasCir)]=$bal01["nombre"];
            $SalPasCir[count($SalPasCir)]=$bal02["importes9"];
        }
        if($buscames==10 && ($bal02["importes10"]!=0 && $bal02["importes10"]!=null)){
            $NomPasCir[count($NomPasCir)]=$bal01["nombre"];
            $SalPasCir[count($SalPasCir)]=$bal02["importes10"];
        }
        if($buscames==11 && ($bal02["importes11"]!=0 && $bal02["importes11"]!=null)){
            $NomPasCir[count($NomPasCir)]=$bal01["nombre"];
            $SalPasCir[count($SalPasCir)]=$bal02["importes11"];
        }
        if($buscames==12 && ($bal02["importes12"]!=0 && $bal02["importes12"]!=null)){
            $NomPasCir[count($NomPasCir)]=$bal01["nombre"];
            $SalPasCir[count($SalPasCir)]=$bal02["importes12"];
        }               
    }    
    
    
    /*Pasivo Fijo o a largo plazo*/
    $sqlBal01="select * from cuenta where idempresa='".$buscaempresa."' and tipo='D' and ctamayor=1 and (codigo like '22%')";
    $resultBal01=mysql_query($sqlBal01,$con) or die(mysql_error());
    while ($bal01=mysql_fetch_assoc($resultBal01)) {
        $sqlBal02="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$bal01["idcuenta"]."' and tipo=1 ";
        $resultBal02=mysql_query($sqlBal02,$con) or die(mysql_error());
        $bal02=mysql_fetch_assoc($resultBal02);        
        if($buscames==1 && ($bal02["importes1"]!=0 && $bal02["importes1"]!=null)){
            $NomPasFij[count($NomPasFij)]=$bal01["nombre"];
            $SalPasFij[count($SalPasFij)]=$bal02["importes1"];
        }
        if($buscames==2 && ($bal02["importes2"]!=0 && $bal02["importes2"]!=null)){
            $NomPasFij[count($NomPasFij)]=$bal01["nombre"];
            $SalPasFij[count($SalPasFij)]=$bal02["importes2"];
        }
        if($buscames==3 && ($bal02["importes3"]!=0 && $bal02["importes3"]!=null)){
            $NomPasFij[count($NomPasFij)]=$bal01["nombre"];
            $SalPasFij[count($SalPasFij)]=$bal02["importes3"];
        }
        if($buscames==4 && ($bal02["importes4"]!=0 && $bal02["importes4"]!=null)){
            $NomPasFij[count($NomPasFij)]=$bal01["nombre"];
            $SalPasFij[count($SalPasFij)]=$bal02["importes4"];
        }
        if($buscames==5 && ($bal02["importes5"]!=0 && $bal02["importes5"]!=null)){
            $NomPasFij[count($NomPasFij)]=$bal01["nombre"];
            $SalPasFij[count($SalPasFij)]=$bal02["importes5"];
        }        
        if($buscames==6 && ($bal02["importes6"]!=0 && $bal02["importes6"]!=null)){
            $NomPasFij[count($NomPasFij)]=$bal01["nombre"];
            $SalPasFij[count($SalPasFij)]=$bal02["importes6"];
        }
        if($buscames==7 && ($bal02["importes7"]!=0 && $bal02["importes7"]!=null)){
            $NomPasFij[count($NomPasFij)]=$bal01["nombre"];
            $SalPasFij[count($SalPasFij)]=$bal02["importes7"];
        }
        if($buscames==8 && ($bal02["importes8"]!=0 && $bal02["importes8"]!=null)){
            $NomPasFij[count($NomPasFij)]=$bal01["nombre"];
            $SalPasFij[count($SalPasFij)]=$bal02["importes8"];
        }
        if($buscames==9 && ($bal02["importes9"]!=0 && $bal02["importes9"]!=null)){
            $NomPasFij[count($NomPasFij)]=$bal01["nombre"];
            $SalPasFij[count($SalPasFij)]=$bal02["importes9"];
        }
        if($buscames==10 && ($bal02["importes10"]!=0 && $bal02["importes10"]!=null)){
            $NomPasFij[count($NomPasFij)]=$bal01["nombre"];
            $SalPasFij[count($SalPasFij)]=$bal02["importes10"];
        }
        if($buscames==11 && ($bal02["importes11"]!=0 && $bal02["importes11"]!=null)){
            $NomPasFij[count($NomPasFij)]=$bal01["nombre"];
            $SalPasFij[count($SalPasFij)]=$bal02["importes11"];
        }
        if($buscames==12 && ($bal02["importes12"]!=0 && $bal02["importes12"]!=null)){
            $NomPasFij[count($NomPasFij)]=$bal01["nombre"];
            $SalPasFij[count($SalPasFij)]=$bal02["importes12"];
        }               
    }      
    
    
    /*Capital*/
    $sqlBal01="select * from cuenta where idempresa='".$buscaempresa."' and tipo='F' and ctamayor=1";
    $resultBal01=mysql_query($sqlBal01,$con) or die(mysql_error());
    while ($bal01=mysql_fetch_assoc($resultBal01)) {
        $sqlBal02="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$bal01["idcuenta"]."' and tipo=1 ";
        $resultBal02=mysql_query($sqlBal02,$con) or die(mysql_error());
        $bal02=mysql_fetch_assoc($resultBal02);        
        if($buscames==1 && ($bal02["importes1"]!=0 && $bal02["importes1"]!=null)){
            $NomCapital[count($NomCapital)]=$bal01["nombre"];
            $SalCapital[count($SalCapital)]=$bal02["importes1"];
        }
        if($buscames==2 && ($bal02["importes2"]!=0 && $bal02["importes2"]!=null)){
            $NomCapital[count($NomCapital)]=$bal01["nombre"];
            $SalCapital[count($SalCapital)]=$bal02["importes2"];
        }
        if($buscames==3 && ($bal02["importes3"]!=0 && $bal02["importes3"]!=null)){
            $NomCapital[count($NomCapital)]=$bal01["nombre"];
            $SalCapital[count($SalCapital)]=$bal02["importes3"];
        }
        if($buscames==4 && ($bal02["importes4"]!=0 && $bal02["importes4"]!=null)){
            $NomCapital[count($NomCapital)]=$bal01["nombre"];
            $SalCapital[count($SalCapital)]=$bal02["importes4"];
        }
        if($buscames==5 && ($bal02["importes5"]!=0 && $bal02["importes5"]!=null)){
            $NomCapital[count($NomCapital)]=$bal01["nombre"];
            $SalCapital[count($SalCapital)]=$bal02["importes5"];
        }        
        if($buscames==6 && ($bal02["importes6"]!=0 && $bal02["importes6"]!=null)){
            $NomCapital[count($NomCapital)]=$bal01["nombre"];
            $SalCapital[count($SalCapital)]=$bal02["importes6"];
        }
        if($buscames==7 && ($bal02["importes7"]!=0 && $bal02["importes7"]!=null)){
            $NomCapital[count($NomCapital)]=$bal01["nombre"];
            $SalCapital[count($SalCapital)]=$bal02["importes7"];
        }
        if($buscames==8 && ($bal02["importes8"]!=0 && $bal02["importes8"]!=null)){
            $NomCapital[count($NomCapital)]=$bal01["nombre"];
            $SalCapital[count($SalCapital)]=$bal02["importes8"];
        }
        if($buscames==9 && ($bal02["importes9"]!=0 && $bal02["importes9"]!=null)){
            $NomCapital[count($NomCapital)]=$bal01["nombre"];
            $SalCapital[count($SalCapital)]=$bal02["importes9"];
        }
        if($buscames==10 && ($bal02["importes10"]!=0 && $bal02["importes10"]!=null)){
            $NomCapital[count($NomCapital)]=$bal01["nombre"];
            $SalCapital[count($SalCapital)]=$bal02["importes10"];
        }
        if($buscames==11 && ($bal02["importes11"]!=0 && $bal02["importes11"]!=null)){
            $NomCapital[count($NomCapital)]=$bal01["nombre"];
            $SalCapital[count($SalCapital)]=$bal02["importes11"];
        }
        if($buscames==12 && ($bal02["importes12"]!=0 && $bal02["importes12"]!=null)){
            $NomCapital[count($NomCapital)]=$bal01["nombre"];
            $SalCapital[count($SalCapital)]=$bal02["importes12"];
        }               
    }    
    
    
    /*Utilidad*/
    $sqlBal01="select * from cuenta where idempresa='".$buscaempresa."' and codigo='_UTILIDAD'";
    $resultBal01=mysql_query($sqlBal01,$con) or die(mysql_error());
    while ($bal01=mysql_fetch_assoc($resultBal01)) {
        $sqlBal02="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$bal01["idcuenta"]."' and tipo=1 ";
        $resultBal02=mysql_query($sqlBal02,$con) or die(mysql_error());
        $bal02=mysql_fetch_assoc($resultBal02);        
        if($buscames==1 && ($bal02["importes1"]!=0 && $bal02["importes1"]!=null)){
            $NomUtilidad[count($NomUtilidad)]=$bal01["nombre"];
            $SalUtilidad[count($SalUtilidad)]=$bal02["importes1"];
        }
        if($buscames==2 && ($bal02["importes2"]!=0 && $bal02["importes2"]!=null)){
            $NomUtilidad[count($NomUtilidad)]=$bal01["nombre"];
            $SalUtilidad[count($SalUtilidad)]=$bal02["importes2"];
        }
        if($buscames==3 && ($bal02["importes3"]!=0 && $bal02["importes3"]!=null)){
            $NomUtilidad[count($NomUtilidad)]=$bal01["nombre"];
            $SalUtilidad[count($SalUtilidad)]=$bal02["importes3"];
        }
        if($buscames==4 && ($bal02["importes4"]!=0 && $bal02["importes4"]!=null)){
            $NomUtilidad[count($NomUtilidad)]=$bal01["nombre"];
            $SalUtilidad[count($SalUtilidad)]=$bal02["importes4"];
        }
        if($buscames==5 && ($bal02["importes5"]!=0 && $bal02["importes5"]!=null)){
            $NomUtilidad[count($NomUtilidad)]=$bal01["nombre"];
            $SalUtilidad[count($SalUtilidad)]=$bal02["importes5"];
        }        
        if($buscames==6 && ($bal02["importes6"]!=0 && $bal02["importes6"]!=null)){
            $NomUtilidad[count($NomUtilidad)]=$bal01["nombre"];
            $SalUtilidad[count($SalUtilidad)]=$bal02["importes6"];
        }
        if($buscames==7 && ($bal02["importes7"]!=0 && $bal02["importes7"]!=null)){
            $NomUtilidad[count($NomUtilidad)]=$bal01["nombre"];
            $SalUtilidad[count($SalUtilidad)]=$bal02["importes7"];
        }
        if($buscames==8 && ($bal02["importes8"]!=0 && $bal02["importes8"]!=null)){
            $NomUtilidad[count($NomUtilidad)]=$bal01["nombre"];
            $SalUtilidad[count($SalUtilidad)]=$bal02["importes8"];
        }
        if($buscames==9 && ($bal02["importes9"]!=0 && $bal02["importes9"]!=null)){
            $NomUtilidad[count($NomUtilidad)]=$bal01["nombre"];
            $SalUtilidad[count($SalUtilidad)]=$bal02["importes9"];
        }
        if($buscames==10 && ($bal02["importes10"]!=0 && $bal02["importes10"]!=null)){
            $NomUtilidad[count($NomUtilidad)]=$bal01["nombre"];
            $SalUtilidad[count($SalUtilidad)]=$bal02["importes10"];
        }
        if($buscames==11 && ($bal02["importes11"]!=0 && $bal02["importes11"]!=null)){
            $NomUtilidad[count($NomUtilidad)]=$bal01["nombre"];
            $SalUtilidad[count($SalUtilidad)]=$bal02["importes11"];
        }
        if($buscames==12 && ($bal02["importes12"]!=0 && $bal02["importes12"]!=null)){
            $NomUtilidad[count($NomUtilidad)]=$bal01["nombre"];
            $SalUtilidad[count($SalUtilidad)]=$bal02["importes12"];
        }               
    }    
    
    //Ajuste de depreciaciones
    for($i=0;$i<count($NomActFij);$i++){
        if(substr($NomActFij[$i],0,13)=="Depreciación" || substr($NomActFij[$i],0,13)=="Depreciaciòn" || substr($NomActFij[$i],0,13)=="Depreciacion "){
            $SalActFij[$i]=$SalActFij[$i]*-1;
        }
    }
    
    $acum01=0;
    $acum02=0;
    $acum03=0;
    $acum04=0;
    $acum05=0;
    $acum06=0;
    
    for($i=0;$i<count($NomActCir);$i++){
        $acum01+=$SalActCir[$i];  
    }    
    
    for($i=0;$i<count($NomActFij);$i++){
        $acum02+=$SalActFij[$i];  
    } 
    
    for($i=0;$i<count($NomActDif);$i++){
        $acum03+=$SalActDif[$i];  
    }  
    
    for($i=0;$i<count($NomPasCir);$i++){
        $acum04+=$SalPasCir[$i];  
    }  
    
    for($i=0;$i<count($NomPasFij);$i++){
        $acum05+=$SalPasFij[$i];  
    }    
    
    for($i=0;$i<count($NomCapital);$i++){
        $acum06+=$SalCapital[$i];  
    }    
    
    
    function columna1($pdf,$fila,$nombre,$saldo,$por01,$por02,$alinea){
        $filab=35+(($fila*5)-5);
        $pdf->SetXY(10, $filab);
        $pdf->Cell(45, 5,$nombre, 0, 1, $alinea, 0, '', 0); 
        $pdf->SetXY(55, $filab);
        $pdf->Cell(15, 5,$saldo, 0, 1, 'R', 0, '', 0);
        $pdf->SetXY(70, $filab);
        $pdf->Cell(15, 5,$por01, 0, 1, 'R', 0, '', 0); 
        $pdf->SetXY(85, $filab);
        $pdf->Cell(15, 5,$por02, 0, 1, 'R', 0, '', 0);         
    }
    
    function columna2($pdf,$fila,$nombre,$saldo,$por01,$por02,$alinea){
        $filab=35+(($fila*5)-5);
        $pdf->SetXY(110, $filab);
        $pdf->Cell(45, 5,$nombre, 0, 1, $alinea, 0, '', 0); 
        $pdf->SetXY(155, $filab);
        $pdf->Cell(15, 5,$saldo, 0, 1, 'R', 0, '', 0);
        $pdf->SetXY(170, $filab);
        $pdf->Cell(15, 5,$por01, 0, 1, 'R', 0, '', 0); 
        $pdf->SetXY(185, $filab);
        $pdf->Cell(15, 5,$por02, 0, 1, 'R', 0, '', 0);         
    }    

    $fila=1;
    $pdf->SetFont('Helvetica', 'B', 8);
    columna1($pdf,$fila,"ACTIVO",null,null,null,'C');$fila++;
    $pdf->SetFont('Helvetica', '', 7);
    columna1($pdf,$fila,null,null,null,null,null);$fila++;
    if(count($NomActCir)>0){
        $acumActCirculante=0;
        $pdf->SetFont('Helvetica', 'I', 7);
        columna1($pdf,$fila,"    CIRCULANTE",null,null,null,null);$fila++;
        $pdf->SetFont('Helvetica', '', 7);
        columna1($pdf,$fila,null,null,null,null,null);$fila++;
        for($i=0;$i<count($NomActCir);$i++){
            columna1($pdf,$fila,substr($NomActCir[$i],0,35),number_format($SalActCir[$i],2),round((($SalActCir[$i]*100)/$acum01),2)." %",null,null);$fila++;
            $acumActCirculante+=$SalActCir[$i];
        }
        columna1($pdf,$fila,null,null,null,null,null);$fila++;
        $pdf->SetFont('Helvetica', 'I', 7);
        $pdf->Line(53,(35+(($fila*5)-7)), 100,(35+(($fila*5)-7)));
        columna1($pdf,$fila,"    Total CIRCULANTE",  number_format($acumActCirculante,2),"100 %",round((($acumActCirculante*100)/($acum01+$acum02+$acum03)),2)." %",null);$fila++;
        $pdf->SetFont('Helvetica', '', 7);
        columna1($pdf,$fila,null,null,null,null,null);$fila++;
    }
    
    if(count($NomActFij)>0){
        $acumActFijo=0;
        $pdf->SetFont('Helvetica', 'I', 7);
        columna1($pdf,$fila,"    FIJO",null,null,null,null);$fila++;
        $pdf->SetFont('Helvetica', '', 7);
        columna1($pdf,$fila,null,null,null,null,null);$fila++;   
        for($i=0;$i<count($NomActFij);$i++){
            columna1($pdf,$fila,substr($NomActFij[$i],0,35),number_format($SalActFij[$i],2),round((($SalActFij[$i]*100)/$acum02),2)." %",null,null);$fila++;
            $acumActFijo+=$SalActFij[$i];
        }
        columna1($pdf,$fila,null,null,null,null,null);$fila++;
        $pdf->SetFont('Helvetica', 'I', 7);
        $pdf->Line(53,(35+(($fila*5)-7)), 100,(35+(($fila*5)-7)));
        columna1($pdf,$fila,"    Total FIJO",  number_format($acumActFijo,2),"100 %",round((($acumActFijo*100)/($acum01+$acum02+$acum03)),2)." %",null);$fila++;
        $pdf->SetFont('Helvetica', '', 7);
        columna1($pdf,$fila,null,null,null,null,null);$fila++;        
    }
    
    if(count($NomActDif)>0){
        $acumActDiferido=0;
        $pdf->SetFont('Helvetica', 'I', 7);
        columna1($pdf,$fila,"    DIFERIDO",null,null,null,null);$fila++;
        $pdf->SetFont('Helvetica', '', 7);
        columna1($pdf,$fila,null,null,null,null,null);$fila++;   
        for($i=0;$i<count($NomActDif);$i++){
            columna1($pdf,$fila,substr($NomActDif[$i],0,35),number_format($SalActDif[$i],2),round((($SalActDif[$i]*100)/$acum03),2)." %",null,null);$fila++;
            $acumActDiferido+=$SalActDif[$i];
        }
        columna1($pdf,$fila,null,null,null,null,null);$fila++;
        $pdf->SetFont('Helvetica', 'I', 7);
        $pdf->Line(53,(35+(($fila*5)-7)), 100,(35+(($fila*5)-7)));
        columna1($pdf,$fila,"    Total DIFERIDO",  number_format($acumActDiferido,2),"100 %",round((($acumActDiferido*100)/($acum01+$acum02+$acum03)),2)." %",null);$fila++;
        $pdf->SetFont('Helvetica', '', 7);
        columna1($pdf,$fila,null,null,null,null,null);$fila++;        
    } 
    
    $pdf->SetFont('Helvetica', 'B', 8);
    $maximoAct=$fila;
    //columna1($pdf,$fila,"SUMA DEL ACTIVO",  number_format(round(($acum01+$acum02+$acum03),2),2),null,"100%","C");$fila++;
    $pdf->SetFont('Helvetica', '', 7);
    
    $fila=1;
    $pdf->SetFont('Helvetica', 'B', 8);
    columna2($pdf,$fila,"PASIVO",null,null,null,'C');$fila++;
    $pdf->SetFont('Helvetica', '', 7);
    columna2($pdf,$fila,null,null,null,null,null);$fila++;    
    
    if(count($NomPasCir)>0){
        $acumPasCirculante=0;
        $pdf->SetFont('Helvetica', 'I', 7);
        columna2($pdf,$fila,"    CIRCULANTE",null,null,null,null);$fila++;
        $pdf->SetFont('Helvetica', '', 7);
        columna2($pdf,$fila,null,null,null,null,null);$fila++;
        for($i=0;$i<count($NomPasCir);$i++){
            columna2($pdf,$fila,substr($NomPasCir[$i],0,35),number_format($SalPasCir[$i],2),round((($SalPasCir[$i]*100)/($acum04+$acum05)),2)." %",null,null);$fila++;
            $acumPasCirculante+=$SalPasCir[$i];
        }
        columna2($pdf,$fila,null,null,null,null,null);$fila++;
        $pdf->SetFont('Helvetica', 'I', 7);
        $pdf->Line(153,(35+(($fila*5)-7)), 200,(35+(($fila*5)-7)));
        columna2($pdf,$fila,"    Total CIRCULANTE",  number_format($acumPasCirculante,2),"100 %",null,null);$fila++;
        $pdf->SetFont('Helvetica', '', 7);
        columna2($pdf,$fila,null,null,null,null,null);$fila++;
    } 
    
    if(count($NomPasFij)>0){
        $acumPasFijo=0;
        $pdf->SetFont('Helvetica', 'I', 7);
        columna2($pdf,$fila,"    FIJO",null,null,null,null);$fila++;
        $pdf->SetFont('Helvetica', '', 7);
        columna2($pdf,$fila,null,null,null,null,null);$fila++;
        for($i=0;$i<count($NomPasFij);$i++){
            columna2($pdf,$fila,substr($NomPasFij[$i],0,35),number_format($SalPasCir[$i],2),round((($SalPasCir[$i]*100)/($acum04+$acum05)),2)." %",null,null);$fila++;
            $acumPasFijo+=$SalPasCir[$i];
        }
        columna2($pdf,$fila,null,null,null,null,null);$fila++;
        $pdf->SetFont('Helvetica', 'I', 7);
        $pdf->Line(153,(35+(($fila*5)-7)), 200,(35+(($fila*5)-7)));
        columna2($pdf,$fila,"    Total FIJO",  number_format($acumPasFijo,2),"100 %",round((($acumPasFijo*100)/($acum04+$acum05)),2)." %",null);$fila++;
        $pdf->SetFont('Helvetica', '', 7);
        columna2($pdf,$fila,null,null,null,null,null);$fila++;
    }    
    
    $pdf->SetFont('Helvetica', 'B', 8);
    $pdf->Line(153,(35+(($fila*5)-7)), 200,(35+(($fila*5)-7)));
    columna2($pdf,$fila,"SUMA DEL PASIVO",  number_format(round(($acum04+$acum05),2),2),null,round(((($acum04+$acum05)*100)/($acum04+$acum05+$acum06+$SalUtilidad[0])),2)." %","C");$fila++;
    columna2($pdf,$fila,null,null,null,null,null);$fila++; 
    $pdf->SetFont('Helvetica', '', 7);  
    
    $pdf->SetFont('Helvetica', 'B', 8);
    columna2($pdf,$fila,"CAPITAL",null,null,null,'C');$fila++;
    $pdf->SetFont('Helvetica', '', 7);
    columna2($pdf,$fila,null,null,null,null,null);$fila++;     
    
    if(count($NomCapital)>0){
        $acumCapital=0;
        $pdf->SetFont('Helvetica', 'I', 7);
        columna2($pdf,$fila,"    CAPITAL",null,null,null,null);$fila++;
        $pdf->SetFont('Helvetica', '', 7);
        columna2($pdf,$fila,null,null,null,null,null);$fila++;
        for($i=0;$i<count($NomCapital);$i++){
            columna2($pdf,$fila,substr($NomCapital[$i],0,35),number_format($SalCapital[$i],2),round((($SalCapital[$i]*100)/($acum06+$SalUtilidad[0])),2)." %",null,null);$fila++;
            $acumCapital+=$SalCapital[$i];
        }
        columna2($pdf,$fila,null,null,null,null,null);$fila++;
        $pdf->SetFont('Helvetica', 'I', 7);
        $pdf->Line(153,(35+(($fila*5)-7)), 200,(35+(($fila*5)-7)));
        columna2($pdf,$fila,"    Total CAPITAL",  number_format($acumCapital,2),null,null,null);$fila++;
        $pdf->SetFont('Helvetica', '', 7);
        columna2($pdf,$fila,null,null,null,null,null);$fila++;
    }     
    
    columna2($pdf,$fila,substr($NomUtilidad[0],0,35),number_format($SalUtilidad[0],2),round((($SalUtilidad[0]*100)/($acum06+$SalUtilidad[0])),2)." %",null,null);$fila++;    
    columna2($pdf,$fila,null,null,null,null,null);$fila++;
    
    $pdf->SetFont('Helvetica', 'B', 8);
    $pdf->Line(153,(35+(($fila*5)-7)), 200,(35+(($fila*5)-7)));
    columna2($pdf,$fila,"SUMA DEL CAPITAL",  number_format(round(($acum06+$SalUtilidad[0]),2),2),null,round(((($acum06+$SalUtilidad[0])*100)/($acum04+$acum05+$acum06+$SalUtilidad[0])),2)." %","C");$fila++;
    columna2($pdf,$fila,null,null,null,null,null);$fila++; 
    $maximoPas=$fila;
    
    if($maximoAct>$maximoPas){
        $pdf->Line(153,(35+(($fila*5)-7)), 200,(35+(($fila*5)-7)));
        $pdf->Line(53,(35+(($fila*5)-7)), 100,(35+(($fila*5)-7)));        
        columna1($pdf,$maximoAct,"SUMA DEL ACTIVO",  number_format(round(($acum01+$acum02+$acum03),2),2),null,"100%","C");$fila++;
        columna2($pdf,$maximoAct,"PASIVO Y CAPITAL",number_format(round(($acum04+$acum05+$acum06+$SalUtilidad[0]),2),2),null,"100 %",'C');$fila++;    
        $pdf->Line(153,(35+(($fila*5)-7)), 200,(35+(($fila*5)-7)));
        $pdf->Line(53,(35+(($fila*5)-7)), 100,(35+(($fila*5)-7)));            
    }else{
        $pdf->Line(153,(35+(($fila*5)-7)), 200,(35+(($fila*5)-7)));
        $pdf->Line(53,(35+(($fila*5)-7)), 100,(35+(($fila*5)-7)));
        columna1($pdf,$maximoPas,"SUMA DEL ACTIVO",  number_format(round(($acum01+$acum02+$acum03),2),2),null,"100%","C");$fila++;        
        columna2($pdf,$maximoPas,"PASIVO Y CAPITAL",number_format(round(($acum04+$acum05+$acum06+$SalUtilidad[0]),2),2),null,"100 %",'C');$fila++;  
        $pdf->Line(153,(35+(($fila*5)-7)), 200,(35+(($fila*5)-7)));
        $pdf->Line(53,(35+(($fila*5)-7)), 100,(35+(($fila*5)-7)));         
    }
                                
    $pdf->SetFont('Helvetica', '', 8);
    $pdf->AddPage('P', 'A4');
    
    $pdf->Image('recursos/logo300px.jpg', 10, 10, 30, 12.8, 'JPG', 'http://www.gaagdesarrolloempresarial.com', '', true, 150, '', false, false, 0, false, false, false);
    $pdf->Line(10, 25, 200, 25);
    $pdf->SetFont('Helvetica', '', 10);
    $pdf->Text(9, 26, 'Resumen de Indicadores Financieros');
    $pdf->SetFont('Helvetica', '', 8);
    $pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(224,224,224)));      
    
    $pdf->Circle(105,135,55.9);
    $pdf->Circle(105,135,55.6);
    $pdf->Circle(105,135,55.3);  
    $pdf->Circle(105,135,55);
    $pdf->Circle(105,135,54.7);
    $pdf->Circle(105,135,54.4);
    $pdf->Circle(105,135,54.1);
    $cuentaOK=0;
    $cuentaFA=0;
    $angulo=0;
    for($i=0;$i<$totalcategorias;$i++){
        $pdf->SetFont('Helvetica', '', 8);
        $listarazones = explode("-",$razonesporca[$i]);
        $listavalores = explode(";",$valoresporra[$i]);
        for($j=0;$j<(count($listarazones)-1);$j++){
            
            $sqlRazon = "select * from razonfinanciera where idrazonfinanciera='".$listarazones[$j]."'";
            $resutlRazon=mysql_query($sqlRazon,$con) or die(mysql_error());             
            $raz=mysql_fetch_assoc($resutlRazon);            
            $pdf->StartTransform();
            $pdf->Rotate(($angulo*7.2), 105, 135);
            
            if($raz["evaluacion"]==0){
                if((double)$listavalores[$j]>=1){
                    $pdf->Polygon(array(156.715,129.565,157, 135,163, 135,162.682,128.937), 'F', array($style1), array(125, 190, 17));
                }else{
                    $pdf->Polygon(array(156.715,129.565,157, 135,163, 135,162.682,128.937), 'F', array($style1), array(208, 0, 10));
                }                 
            } 
            
            if($raz["evaluacion"]==1){
                if((double)$listavalores[$j]>0){
                    $pdf->Polygon(array(156.715,129.565,157, 135,163, 135,162.682,128.937), 'F', array($style1), array(125, 190, 17));
                }else{
                    $pdf->Polygon(array(156.715,129.565,157, 135,163, 135,162.682,128.937), 'F', array($style1), array(208, 0, 10));
                }                
            }
            
            if($raz["evaluacion"]==2){
                if((double)$listavalores[$j]<=1){
                    $pdf->Polygon(array(156.715,129.565,157, 135,163, 135,162.682,128.937), 'F', array($style1), array(125, 190, 17));
                }else{
                    $pdf->Polygon(array(156.715,129.565,157, 135,163, 135,162.682,128.937), 'F', array($style1), array(208, 0, 10));
                }                
            }
            
            if($raz["evaluacion"]==3){
                $pdf->Polygon(array(156.715,129.565,157, 135,163, 135,162.682,128.937), 'F', array($style1), array(125, 190, 17));                
            }            
            
           
            $pdf->StopTransform();                         
            
            $angulos=array();
            $angulos[0]=0; 
            $angulos[1]=7;
            $angulos[2]=15;
            $angulos[3]=22;
            $angulos[4]=29;
            $angulos[5]=36;
            $angulos[6]=43;
            $angulos[7]=51;
            $angulos[8]=58;
            $angulos[9]=65;
            $angulos[10]=79;
            $angulos[11]=72;
            $angulos[12]=87;            
            $angulos[13]=-86;
            $angulos[14]=-79;
            $angulos[15]=-72;
            $angulos[16]=-65;
            $angulos[17]=-58;
            $angulos[18]=-51;
            $angulos[19]=-43;
            $angulos[20]=-36;
            $angulos[21]=-29;
            $angulos[22]=-22;
            $angulos[23]=-15;
            $angulos[24]=-7;
            $angulos[25]=0;
            $angulos[26]=7;
            $angulos[27]=14;
            $angulos[28]=22;
            $angulos[29]=29;
            $angulos[30]=36;
            $angulos[31]=43;
            $angulos[32]=50;
            $angulos[33]=58;
            $angulos[34]=65;
            $angulos[35]=72;
            $angulos[36]=79;
            $angulos[37]=86;                        
            $angulos[38]=-86;
            $angulos[39]=-78;
            $angulos[40]=-71;
            $angulos[41]=-64;
            $angulos[42]=-57;
            $angulos[43]=-50;
            $angulos[44]=-43;
            $angulos[45]=-36;
            $angulos[46]=-29;
            $angulos[47]=-22;
            $angulos[48]=-14;
            $angulos[49]=-7;            

            if(($angulo>=0 && $angulo<=12) || ($angulo>=38 && $angulo<=49)){ /*Al derecho*/
                $pdf->StartTransform();
                $pdf->Rotate($angulos[$angulo], 105,135);    
                $pdf->SetXY(163, 130);
                $pdf->Cell(32, 5,$raz["nombre"], 0, 1, 'L', 0, '', 0);    
                $pdf->StopTransform();                
            }else
            if($angulo>=13 && $angulo<=37){ /*Al contrario*/
                $pdf->StartTransform();
                $pdf->Rotate($angulos[$angulo], 105,135);
                $pdf->SetX(0);
                $pdf->SetY(135.5);
                $pdf->Cell(32, 5,$raz["nombre"], 0, 1, 'R', 0, '', 0);
                $pdf->StopTransform();                 
            }                                                
            
            $pdf->SetTextColor(255,255,255);
            $pdf->SetFont('helvetica', '', 8);            
            $pdf->StartTransform();
            $pdf->Rotate((($angulo+1)*7.2)-2, 105,135);
            
            if($raz["evaluacion"]==0){
                if((double)$listavalores[$j]>=1){
                    $cuentaOK++;
                    $pdf->Text(105,135, '                                                                    O');
                }else{
                    $cuentaFA++;
                    $pdf->Text(105,135, '                                                                    X');
                }                
            }
            if($raz["evaluacion"]==1){
                if((double)$listavalores[$j]>0){
                    $cuentaOK++;
                    $pdf->Text(105,135, '                                                                    O');
                }else{
                    $cuentaFA++;
                    $pdf->Text(105,135, '                                                                    X');
                }                
            }
            if($raz["evaluacion"]==2){
                if((double)$listavalores[$j]<=1){
                    $cuentaOK++;
                    $pdf->Text(105,135, '                                                                    O');
                }else{
                    $cuentaFA++;
                    $pdf->Text(105,135, '                                                                    X');
                }                
            }
            if($raz["evaluacion"]==3){
                    $cuentaOK++;
                    $pdf->Text(105,135, '                                                                    O');                
            }            

            
            
            
            $pdf->SetTextColor(0,0,0);            
            $pdf->StopTransform();            
            
            $angulo++;
        }
        for($k=0;$k<$separacion;$k++){
            $angulo++;
        }        
    }
    
    $porcentaje=(($cuentaOK*100)/($cuentaOK+$cuentaFA));
    $angcalcula=(($porcentaje*360)/100);
    
    $pdf->SetFont('Helvetica', 'B', 16);
    $pdf->Text(92,105,$me[($buscames-1)].' '.$buscaano);    
    
    $pdf->SetFont('Helvetica', '', 20);
    $pdf->Text(97,131,round($porcentaje,0)."%");
    
    $pdf->SetFont('Helvetica', '', 20);    
    $pdf->Text(65,131,round($cuentaOK,0));
    $pdf->Text(138,131,round($cuentaFA,0));
    
    $pdf->SetFont('Helvetica', '', 7);
    $pdf->Text(60,140,"BUEN ESTADO");
    $pdf->SetFont('Helvetica', '', 10);
    $pdf->Text(66,144,"O");
    $pdf->SetFont('Helvetica', '', 7);    
    $pdf->Text(128,140,"ESTADO DE ALERTA");
    $pdf->SetFont('Helvetica', '', 10);
    $pdf->Text(139,144,"X");    
    
    $pdf->SetFont('Helvetica', '', 9);
    $pdf->Text(82,160,$numeroRazones." Indicadores (".($numeroRazones-($cuentaOK+$cuentaFA))." n/a resultados)");
    
    $pdf->SetFont('Helvetica', '', 8);
    $pdf->SetLineStyle(array('color' => array(125, 190, 17)));
    $pdf->Circle(105,135,18,0,$angcalcula);
    $pdf->Circle(105,135,18.1,0,$angcalcula);
    $pdf->Circle(105,135,18.2,0,$angcalcula);
    $pdf->Circle(105,135,18.3,0,$angcalcula);
    $pdf->Circle(105,135,18.4,0,$angcalcula);
    $pdf->Circle(105,135,18.5,0,$angcalcula);
    $pdf->Circle(105,135,18.6,0,$angcalcula);
    $pdf->Circle(105,135,18.7,0,$angcalcula);
    $pdf->Circle(105,135,18.8,0,$angcalcula);
    $pdf->Circle(105,135,18.9,0,$angcalcula);
    $pdf->Circle(105,135,19,0,$angcalcula);
    
    $pdf->SetLineStyle(array('color' => array(208, 0, 10)));
    $pdf->Circle(105,135,18,$angcalcula,360);
    $pdf->Circle(105,135,18.1,$angcalcula,360);
    $pdf->Circle(105,135,18.2,$angcalcula,360);
    $pdf->Circle(105,135,18.3,$angcalcula,360);
    $pdf->Circle(105,135,18.4,$angcalcula,360);
    $pdf->Circle(105,135,18.5,$angcalcula,360);
    $pdf->Circle(105,135,18.6,$angcalcula,360);
    $pdf->Circle(105,135,18.7,$angcalcula,360);
    $pdf->Circle(105,135,18.8,$angcalcula,360);
    $pdf->Circle(105,135,18.9,$angcalcula,360);
    $pdf->Circle(105,135,19,$angcalcula,360);
    
    
    $pdf->SetFont('Helvetica', '', 7);
    $pdf->SetTextColor(126,130,109);
    $pdf->Text(10,277,"Creado por GAAG Desarrollo Empresarial");
    $pdf->SetTextColor(0,0,0);
    $pdf->Text(10,280,$Empresa["nombre"]." | ".$me[($buscames-1)].' '.$buscaano); 
    $pdf->Text(185,280,"Página 02");
    
    $pdf->AddPage('P', 'A4');    
    $pdf->Image('recursos/logo300px.jpg', 10, 10, 30, 12.8, 'JPG', 'http://www.gaagdesarrolloempresarial.com', '', true, 150, '', false, false, 0, false, false, false);
    $pdf->SetLineStyle(array('color' => array(0,0,0)));
    $pdf->Line(10, 25, 200, 25);
    $pdf->SetFont('Helvetica', '', 10);
    $pdf->Text(9, 26, 'Resumen de Indicadores Financieros');
    $pdf->SetFont('Helvetica', '', 8);
    
    
    $comparaano=0;
    $comparames=0;    
    if($buscames>1){
        $comparames=$buscames-1;
        $comparaano=$buscaano;
    }else if($buscames==1){
        $comparames=12;
        $comparaano=((int)$buscaano)-1;
    }   
    
    //echo $comparaano."    ".$comparames;
    
    $resultado = calcula($con,$buscaempresa,$comparaano,$comparames); 
    $arreglo01 = $resultado[0];
    $arreglo02 = $resultado[1];
    
    
    
    $posiciony=34;
    $pdf->SetLineStyle(array('color' => array(148,148,148)));
    $pdf->Line(10, $posiciony, 200, $posiciony);
    $pdf->Text(124, 36, 'Resultado');
    
    $pdf->Text(146, 36, 'Objetivo');
    $pdf->Text(162, 36, 'Estado');
    $pdf->Text(180, 36, 'Tendencia');
    
    $posiciony+=7;
    $letras=array();    
    $letras[0]="A";
    $letras[1]="B";
    $letras[2]="C";
    $letras[3]="D";
    $letras[4]="E";
    $letras[5]="F";
    $letras[6]="G";
    
    for($i=0;$i<$cont;$i++){
        $sqlCat="select * from categoriarazon where idcategoriarazon='".$categoriasID[$i]."'";
        $resutlCat=mysql_query($sqlCat,$con) or die(mysql_error());
        $Cat = mysql_fetch_assoc($resutlCat);        
        //echo $letras[$i]." ".$Cat["nombre"]."</br>";
        
        $pdf->SetFillColor(244, 244, 244);
        $pdf->Line(10, $posiciony, 200, $posiciony);
        $pdf->Rect(10, $posiciony, 190, 5,'F');     
        $pdf->Text(10,$posiciony+1,$letras[$i].". ".$Cat["nombre"]);
        $posiciony+=5;
        $pdf->Line(10, $posiciony, 200, $posiciony);
        $lisaux = explode("-",$razonesporca[$i]);
        $lisval = explode(";",$valoresporra[$i]);
        $liscom = explode(";",$arreglo02[$i]);
        for($j=0;$j<(count($lisaux)-1);$j++){
            $sqlRaz="select * from razonfinanciera where idrazonfinanciera='".$lisaux[$j]."'";
            $resutlRaz=mysql_query($sqlRaz,$con) or die(mysql_error());
            $Raz = mysql_fetch_assoc($resutlRaz);   
            $pdf->Line(10, $posiciony, 200, $posiciony);
            
            $pdf->Text(14,$posiciony+1,$Raz["nombre"]);
            $pdf->SetFont('Helvetica', '', 6);          
            $pdf->SetXY(9, ($posiciony+4.5));
            $pdf->SetTextColor(75,75,75);
            $pdf->MultiCell(110, 5,$Raz["descripcion"], 0, 'L', 0, 0, '', '', true);            
            
            $pdf->SetTextColor(0,0,0);
            
            $pdf->SetFont('Helvetica', '', 8);
            $uni="";
            if($Raz["unidad"]==1){
                $uni=" %";
            }
            if($Raz["unidad"]==2){
                $uni=" veces";
            }
            if($Raz["unidad"]==3){
                $uni=" meses";
            }
            if($Raz["unidad"]==4){
                $uni=" dias";
            }            
            
            $pdf->SetX(119);
            if($Raz["tipo"]==1){
                $lisval[$j]=$lisval[$j]*100;
            }
            
            $pdf->Cell(25, 0,$lisval[$j].$uni, 0, 1, 'C', 0, '', 1);            
            
            if($Raz["evaluacion"]==0){
                $pdf->Text(148,$posiciony+4,">= 1");
            }
            if($Raz["evaluacion"]==1){
                $pdf->Text(148,$posiciony+4,"> 0");
            }
            if($Raz["evaluacion"]==2){
                $pdf->Text(148,$posiciony+4,"<= 1");
            }
            if($Raz["evaluacion"]==3){
                $pdf->Text(148,$posiciony+4,"n/a");
            }            
            
            $pdf->SetX(175);
            if($Raz["tipo"]==1){
                $liscom[$j]=$liscom[$j]*100;
            }            
            $pdf->Cell(25, 0,$liscom[$j].$uni, 0, 1, 'C', 0, '', 1);
            
            
            if($lisval[$j]>=$liscom[$j]){
                $pdf->Polygon(array(10,$posiciony+4,13,$posiciony+4,11.5,$posiciony+2), 'F', array($style1), array(125, 190, 17));
                
            }else{
                $pdf->Polygon(array(10,$posiciony+2,13,$posiciony+2,11.5,$posiciony+4), 'F', array($style1), array(208, 0, 10));
            }
            
            
            
            if($Raz["evaluacion"]==0){
                if($lisval[$j]>=1){
                    $pdf->SetFillColor(125, 190, 17);
                }else{
                    $pdf->SetFillColor(208, 0, 10);
                }                                
            }
            if($Raz["evaluacion"]==1){
                if($lisval[$j]>0){
                    $pdf->SetFillColor(125, 190, 17);
                }else{
                    $pdf->SetFillColor(208, 0, 10);
                }                               
            }
            if($Raz["evaluacion"]==2){
                if($lisval[$j]<=1){
                    $pdf->SetFillColor(125, 190, 17);
                }else{
                    $pdf->SetFillColor(208, 0, 10);
                }                                
            }
            if($Raz["evaluacion"]==3){
                $pdf->SetFillColor(125, 190, 17);                
            }              
            

                       
            $pdf->Circle(167,($posiciony+6),1,0,360,'F',array('color'=>array(125, 190, 17)));
           
           
            
            $posiciony+=11;            
        }
    }            
    
    $pdf->Text(124, 42,$me[($buscames-1)].' '.$buscaano);
    $pdf->Text(178, 42,'vs '.$me[($comparames-1)].' '.$comparaano);
    $pdf->Line(10, $posiciony, 200, $posiciony);
    $pdf->Line(144, 34, 144,$posiciony);
    $pdf->Line(160, 34, 160,$posiciony);
    $pdf->Line(175, 34, 175,$posiciony);
    
    
    
    $pdf->SetFont('Helvetica', '', 7);
    $pdf->SetTextColor(126,130,109);
    $pdf->Text(10,277,"Creado por GAAG Desarrollo Empresarial");
    $pdf->SetTextColor(0,0,0);
    $pdf->Text(10,280,$Empresa["nombre"]." | ".$me[($buscames-1)].' '.$buscaano); 
    $pdf->Text(185,280,"Página 03");    
   
    $pdf->AddPage('P', 'A4');   
    $pdf->Image('recursos/logo300px.jpg', 10, 10, 30, 12.8, 'JPG', 'http://www.gaagdesarrolloempresarial.com', '', true, 150, '', false, false, 0, false, false, false);
    $pdf->Line(10, 25, 200, 25);
    $pdf->SetFont('Helvetica', '', 10);
    $pdf->Text(9, 26, 'Resumen de Indicadores Financieros');  
         
    
    function consultacuenta($conexion,$bempresa,$bcuenta,$bano,$tipo){
        $con=$conexion;
        $sqlEje="select * from ejercicio where idempresa='".$bempresa."' and ejercicio='".$bano."'";
        $resultEje=mysql_query($sqlEje,$con) or die(mysql_error());
        $Eje = mysql_fetch_assoc($resultEje); 
        
        $sqlCue="select * from cuenta where idempresa='".$bempresa."' and codigo='".$bcuenta."' ";
        $resutlCue=mysql_query($sqlCue,$con) or die(mysql_error());
        $Cuenta = mysql_fetch_assoc($resutlCue); 
        
        $sqlSal="select * from saldo where idempresa='".$bempresa."' and ejercicio ='".$Eje["idejercicio"]."' and idcuenta='".$Cuenta["idcuenta"]."' and tipo='".$tipo."'";
        $resutlSal=mysql_query($sqlSal,$con) or die(mysql_error());
        $Saldo = mysql_fetch_assoc($resutlSal);
        
        $meses = array();
        $meses[0] = $Saldo["importes1"];
        $meses[1] = $Saldo["importes2"];
        $meses[2] = $Saldo["importes3"];
        $meses[3] = $Saldo["importes4"];
        $meses[4] = $Saldo["importes5"];
        $meses[5] = $Saldo["importes6"];
        $meses[6] = $Saldo["importes7"];
        $meses[7] = $Saldo["importes8"];
        $meses[8] = $Saldo["importes9"];
        $meses[9] = $Saldo["importes10"];
        $meses[10] = $Saldo["importes11"];
        $meses[11] = $Saldo["importes12"];
        return $meses;        
    }
    
    function graficarcompara($conexion,$pdf,$mescalculando,$anocalculado,$empresa,$cuenta01,$cuenta02,$titulo,$tipo01,$tipo02,$nombre01,$nombre02,$reducido01,$reducido02){
        $pdf->SetFont('Helvetica', '', 8);
        $con=$conexion;        
        $uno=consultacuenta($con,$empresa,$cuenta01,$anocalculado,$tipo01);
        $dos=consultacuenta($con,$empresa,$cuenta02,$anocalculado,$tipo02);        
        
        $limitemayor = max($uno);
        if(max($dos)>$limitemayor){
            $limitemayor=max($dos);
        }
        
        $limitemenor = min($uno);
        if(min($dos)<$limitemenor){
            $limitemenor=min($dos);
        }    
    
        $porango=array();
        $porango[0]=10000;
        $porango[1]=20000;
        $porango[2]=50000;
        $porango[3]=100000;
        $porango[4]=200000;
        $porango[5]=500000;
        $porango[6]=1000000;
        $porango[7]=2000000;
        $porango[8]=5000000;
        
        $rango=0;
    
        for($i=0;$i<count($porango);$i++){
            $acum=0;
            for($j=0;$j<10;$j++){
                $acum=$acum+$porango[$i];
            }
            if($acum>$limitemayor){
                $rango=$i;
                break;
            }
        }
        
        $meses=array();
        $meses[0]="Enero";
        $meses[1]="Febrero";
        $meses[2]="Marzo";
        $meses[3]="Abril";
        $meses[4]="Mayo";
        $meses[5]="Junio";
        $meses[6]="Julio";
        $meses[7]="Agosto";
        $meses[8]="Septiembre";
        $meses[9]="Octubre";
        $meses[10]="Noviembre";
        $meses[11]="Diciembre";
        
        $me=array();
        $me[0]="Ene";
        $me[1]="Feb";
        $me[2]="Mar";
        $me[3]="Abr";
        $me[4]="May";
        $me[5]="Jun";
        $me[6]="Jul";
        $me[7]="Ago";
        $me[8]="Sep";
        $me[9]="Oct";
        $me[10]="Nov";
        $me[11]="Dic";        
        
        $pdf->Line(25, 185, 200, 185);
        $ejex=39.5;
        
        /*Pinto el eje de las x*/
        for($i=0;$i<12;$i++){
            $pdf->Line($ejex, 184, $ejex,186);            
            $pdf->StartTransform();
            $pdf->Rotate(40, ($ejex-17), 198);        
            $pdf->SetXY(($ejex-17),198);                
            $pdf->Cell(20,5,$meses[$i], 0, 1, 'R', 0, '', 1); 
            $pdf->StopTransform();
            
            $pdf->SetLineStyle(array('width' => 0.4,'color' => array(29,219,0)));
            $puntoy=(185-($uno[$i]*((1*14.5)/$porango[$rango])));
            //$pdf->Line($ejex,$puntoy, $ejex,($puntoy+1));
            
            if($i<($mescalculando-1)){                
                if($i<11){  
                    $puntoy2=(185-($uno[($i+1)]*((1*14.5)/$porango[$rango])));
                    $pdf->Line($ejex,$puntoy, ($ejex+14.5),($puntoy2));                     
                }                                                   
            }    
            if($i<$mescalculando){
                $pdf->SetFillColor(29,219,0);           
                $pdf->Circle($ejex,$puntoy,1,0,360,'F',array('color'=>array(29,219,0)));                 
            }
            
            if($i<$mescalculando){
                $pdf->SetLineStyle(array('width' => 0.4,'color' => array(255,206,1)));
                $puntoy=(185-($dos[$i]*((1*14.5)/$porango[$rango])));
                $pdf->Line($ejex,($puntoy-0.25), $ejex,($puntoy+0.25));
                $pdf->SetFillColor(255,206,1);           
                $pdf->Circle($ejex,$puntoy,1,0,360,'F',array('color'=>array(29,219,0)));                 
            }
            
            if($i<($mescalculando-1)){                
                $puntoy=(185-($dos[$i]*((1*14.5)/$porango[$rango])));
                //$pdf->Line($ejex,$puntoy, $ejex,($puntoy+1));
                if($i<11){
                    $puntoy2=(185-($dos[($i+1)]*((1*14.5)/$porango[$rango])));
                    $pdf->Line($ejex,$puntoy, ($ejex+14.5),($puntoy2)); 
                }                 
            }
            
            
                       
            $pdf->SetLineStyle(array('width' => 0.25,'color' => array(148,148,148)));                        
            $ejex+=14.5;
        }  
                
       // $pdf->Line(25, 39, 25, 185);
        $ejey=170.5;
        $acum=$porango[$rango];
        /*Pinto el eje de las y*/
        for($i=0;$i<10;$i++){
            $pdf->Line(24,$ejey,200,$ejey);
            $pdf->Text(9,$ejey-2,number_format($acum));
            $ejey-=14.5;
            $acum+=$porango[$rango];
        }
        
        $pdf->SetLineStyle(array('width' => 0.4,'color' => array(0,160,0)));
        $pdf->Line(63,206.5,68,206.5); 
        $pdf->Text(70,205,$nombre01);
        
        $pdf->SetLineStyle(array('width' => 0.4,'color' => array(255,206,1)));
        $pdf->Line(113,206.5,118,206.5);
        $pdf->Text(120,205,$nombre02);
        $pdf->SetLineStyle(array('width' => 0.25,'color' => array(148,148,148)));
        
        $pdf->Line(10,230,200,230);
        $pdf->Line(10,236,200,236);
        $pdf->Line(10,242,200,242);
        
        $columna=25;
        $pdf->SetFont('Helvetica', '', 7);
        for($i=0;$i<12;$i++){
            $pdf->Line($columna,224,$columna,242);                      
            $pdf->SetXY($columna,224);                
            $pdf->Cell(14.5,6,$me[$i], 0, 1, 'C', 0, '', 1);

            if($i<$mescalculando){
                $pdf->SetXY($columna,230);
                $pdf->Cell(14.5,6,number_format(round($uno[$i],0)), 0, 1, 'C', 0, '', 1);                
                $pdf->SetXY($columna,236);
                $pdf->Cell(14.5,6,number_format(round($dos[$i],0)), 0, 1, 'C', 0, '', 1);                
            }
            
            $columna+=14.5;
        }
        
        $pdf->SetXY(10,230);
        $pdf->Cell(14.5,6,$reducido01,0, 1, 'C', 0, '', 1);     
        $pdf->SetXY(10,236);
        $pdf->Cell(14.5,6,$reducido02,0, 1, 'C', 0, '', 1);        
        
                                        
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetXY(90,30);                
        $pdf->Cell(50,5,$titulo, 1, 1, 'C', 0, '', 1);     
        $pdf->SetFont('Helvetica', '', 8);        
        
    }
    
    function graficar($conexion,$pdf,$mescalculando,$anocalculado,$empresa,$cuenta,$titulo,$tipo){
        $pdf->SetFont('Helvetica', '', 8);
        $con=$conexion;
        $uno=consultacuenta($con,$empresa,$cuenta,($anocalculado-1),$tipo);
        $dos=consultacuenta($con,$empresa,$cuenta,$anocalculado,$tipo);
        
        $limitemayor = max($uno);
        if(max($dos)>$limitemayor){
            $limitemayor=max($dos);
        }
        
        $limitemenor = min($uno);
        if(min($dos)<$limitemenor){
            $limitemenor=min($dos);
        }    
    
        $porango=array();
        $porango[0]=10000;
        $porango[1]=20000;
        $porango[2]=50000;
        $porango[3]=100000;
        $porango[4]=200000;
        $porango[5]=500000;
        $porango[6]=1000000;
        $porango[7]=2000000;
        $porango[8]=5000000;
        
        $rango=0;
    
        for($i=0;$i<count($porango);$i++){
            $acum=0;
            for($j=0;$j<10;$j++){
                $acum=$acum+$porango[$i];
            }
            if($acum>$limitemayor){
                $rango=$i;
                break;
            }
        }
        
        $meses=array();
        $meses[0]="Enero";
        $meses[1]="Febrero";
        $meses[2]="Marzo";
        $meses[3]="Abril";
        $meses[4]="Mayo";
        $meses[5]="Junio";
        $meses[6]="Julio";
        $meses[7]="Agosto";
        $meses[8]="Septiembre";
        $meses[9]="Octubre";
        $meses[10]="Noviembre";
        $meses[11]="Diciembre";
        
        $me=array();
        $me[0]="Ene";
        $me[1]="Feb";
        $me[2]="Mar";
        $me[3]="Abr";
        $me[4]="May";
        $me[5]="Jun";
        $me[6]="Jul";
        $me[7]="Ago";
        $me[8]="Sep";
        $me[9]="Oct";
        $me[10]="Nov";
        $me[11]="Dic";        
        
        $pdf->Line(25, 185, 200, 185);
        $ejex=39.5;
        
        /*Pinto el eje de las x*/
        for($i=0;$i<12;$i++){
            $pdf->Line($ejex, 184, $ejex,186);            
            $pdf->StartTransform();
            $pdf->Rotate(40, ($ejex-17), 198);        
            $pdf->SetXY(($ejex-17),198);                
            $pdf->Cell(20,5,$meses[$i], 0, 1, 'R', 0, '', 1); 
            $pdf->StopTransform();
            
            $pdf->SetLineStyle(array('width' => 0.4,'color' => array(29,219,0)));
            $puntoy=(185-($uno[$i]*((1*14.5)/$porango[$rango])));
            //$pdf->Line($ejex,$puntoy, $ejex,($puntoy+1));
            if($i<11){
                $puntoy2=(185-($uno[($i+1)]*((1*14.5)/$porango[$rango])));
                $pdf->Line($ejex,$puntoy, ($ejex+14.5),($puntoy2)); 

            }
            
                $pdf->SetFillColor(29,219,0);           
                $pdf->Circle($ejex,$puntoy,1,0,360,'F',array('color'=>array(29,219,0)));            
            
            if($i<$mescalculando){
                $pdf->SetLineStyle(array('width' => 0.4,'color' => array(255,206,1)));
                $puntoy=(185-($dos[$i]*((1*14.5)/$porango[$rango])));
                $pdf->Line($ejex,($puntoy-0.25), $ejex,($puntoy+0.25));
                $pdf->SetFillColor(255,206,1);           
                $pdf->Circle($ejex,$puntoy,1,0,360,'F',array('color'=>array(29,219,0)));                 
            }
            
            if($i<($mescalculando-1)){                
                $puntoy=(185-($dos[$i]*((1*14.5)/$porango[$rango])));
                //$pdf->Line($ejex,$puntoy, $ejex,($puntoy+1));
                if($i<11){
                    $puntoy2=(185-($dos[($i+1)]*((1*14.5)/$porango[$rango])));
                    $pdf->Line($ejex,$puntoy, ($ejex+14.5),($puntoy2)); 
                }                 
            }
            
            
                       
            $pdf->SetLineStyle(array('width' => 0.25,'color' => array(148,148,148)));                        
            $ejex+=14.5;
        }  
                
       // $pdf->Line(25, 39, 25, 185);
        $ejey=170.5;
        $acum=$porango[$rango];
        /*Pinto el eje de las y*/
        for($i=0;$i<10;$i++){
            $pdf->Line(24,$ejey,200,$ejey);
            $pdf->Text(9,$ejey-2,number_format($acum));
            $ejey-=14.5;
            $acum+=$porango[$rango];
        }
        
        $pdf->SetLineStyle(array('width' => 0.4,'color' => array(0,160,0)));
        $pdf->Line(83,206.5,88,206.5); 
        $pdf->Text(90,205,($anocalculado-1));
        
        $pdf->SetLineStyle(array('width' => 0.4,'color' => array(255,206,1)));
        $pdf->Line(133,206.5,138,206.5);
        $pdf->Text(140,205,$anocalculado);
        $pdf->SetLineStyle(array('width' => 0.25,'color' => array(148,148,148)));
        
        $pdf->Line(10,230,200,230);
        $pdf->Line(10,236,200,236);
        $pdf->Line(10,242,200,242);
        
        $columna=25;
        $pdf->SetFont('Helvetica', '', 7);
        for($i=0;$i<12;$i++){
            $pdf->Line($columna,224,$columna,242);                      
            $pdf->SetXY($columna,224);                
            $pdf->Cell(14.5,6,$me[$i], 0, 1, 'C', 0, '', 1);
            $pdf->SetXY($columna,230);
            $pdf->Cell(14.5,6,number_format(round($uno[$i],0)), 0, 1, 'C', 0, '', 1);
            if($i<$mescalculando){
                $pdf->SetXY($columna,236);
                $pdf->Cell(14.5,6,number_format(round($dos[$i],0)), 0, 1, 'C', 0, '', 1);                
            }
            
            $columna+=14.5;
        }
        
        $pdf->SetXY(10,230);
        $pdf->Cell(14.5,6,($anocalculado-1),0, 1, 'C', 0, '', 1);     
        $pdf->SetXY(10,236);
        $pdf->Cell(14.5,6,($anocalculado),0, 1, 'C', 0, '', 1);        
        
                                        
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetXY(90,30);                
        $pdf->Cell(50,5,$titulo, 1, 1, 'C', 0, '', 1);     
        $pdf->SetFont('Helvetica', '', 8);
    }
    
    
    $sqlVentas = "select * from asociaclave where idempresa='".$buscaempresa."' and idclave=9";
    $resultVentas=mysql_query($sqlVentas,$con) or die(mysql_error());    
    $clave=mysql_fetch_assoc($resultVentas);
    /*mes-año-empresa-cuenta*/    
    graficar($con,$pdf,$buscames,$buscaano,$buscaempresa,$clave["codigo"],"Resumen de Ventas",3);  
    
    $pdf->SetFont('Helvetica', '', 7);
    $pdf->SetTextColor(126,130,109);
    $pdf->Text(10,277,"Creado por GAAG Desarrollo Empresarial");
    $pdf->SetTextColor(0,0,0);
    $pdf->Text(10,280,$Empresa["nombre"]." | ".$me[($buscames-1)].' '.$buscaano); 
    $pdf->Text(185,280,"Página 04"); 
    
    $pdf->AddPage('P', 'A4');   
    $pdf->Image('recursos/logo300px.jpg', 10, 10, 30, 12.8, 'JPG', 'http://www.gaagdesarrolloempresarial.com', '', true, 150, '', false, false, 0, false, false, false);
    $pdf->Line(10, 25, 200, 25);
    $pdf->SetFont('Helvetica', '', 10);
    $pdf->Text(9, 26, 'Resumen de Indicadores Financieros');        
    $sqlCostos = "select * from asociaclave where idempresa='".$buscaempresa."' and idclave=12";
    $resultCostos=mysql_query($sqlCostos,$con) or die(mysql_error());    
    $costos=mysql_fetch_assoc($resultCostos);
    /*mes-año-empresa-cuenta*/    
    graficar($con,$pdf,$buscames,$buscaano,$buscaempresa,$costos["codigo"],"Resumen Costos de Ventas",2);  
    $pdf->SetFont('Helvetica', '', 7);
    $pdf->SetTextColor(126,130,109);
    $pdf->Text(10,277,"Creado por GAAG Desarrollo Empresarial");
    $pdf->SetTextColor(0,0,0);
    $pdf->Text(10,280,$Empresa["nombre"]." | ".$me[($buscames-1)].' '.$buscaano); 
    $pdf->Text(185,280,"Página 05");  
    
    $pdf->AddPage('P', 'A4');   
    $pdf->Image('recursos/logo300px.jpg', 10, 10, 30, 12.8, 'JPG', 'http://www.gaagdesarrolloempresarial.com', '', true, 150, '', false, false, 0, false, false, false);
    $pdf->Line(10, 25, 200, 25);
    $pdf->SetFont('Helvetica', '', 10);
    $pdf->Text(9, 26, 'Resumen de Indicadores Financieros');        
    $sqlGastosA = "select * from asociaclave where idempresa='".$buscaempresa."' and idclave=19";
    $resultGastosA=mysql_query($sqlGastosA,$con) or die(mysql_error());    
    $gastos=mysql_fetch_assoc($resultGastosA);   
    graficar($con,$pdf,$buscames,$buscaano,$buscaempresa,$gastos["codigo"],"Resumen Gastos de Administración",2);  
    $pdf->SetFont('Helvetica', '', 7);
    $pdf->SetTextColor(126,130,109);
    $pdf->Text(10,277,"Creado por GAAG Desarrollo Empresarial");
    $pdf->SetTextColor(0,0,0);
    $pdf->Text(10,280,$Empresa["nombre"]." | ".$me[($buscames-1)].' '.$buscaano); 
    $pdf->Text(185,280,"Página 06");    
    
    $pdf->AddPage('P', 'A4');   
    $pdf->Image('recursos/logo300px.jpg', 10, 10, 30, 12.8, 'JPG', 'http://www.gaagdesarrolloempresarial.com', '', true, 150, '', false, false, 0, false, false, false);
    $pdf->Line(10, 25, 200, 25);
    $pdf->SetFont('Helvetica', '', 10);
    $pdf->Text(9, 26, 'Resumen de Indicadores Financieros');        
    $sqlGastosA = "select * from asociaclave where idempresa='".$buscaempresa."' and idclave=23";
    $resultGastosA=mysql_query($sqlGastosA,$con) or die(mysql_error());    
    $gastos=mysql_fetch_assoc($resultGastosA);   
    graficar($con,$pdf,$buscames,$buscaano,$buscaempresa,$gastos["codigo"],"Resumen Gastos de de Venta",2);  
    $pdf->SetFont('Helvetica', '', 7);
    $pdf->SetTextColor(126,130,109);
    $pdf->Text(10,277,"Creado por GAAG Desarrollo Empresarial");
    $pdf->SetTextColor(0,0,0);
    $pdf->Text(10,280,$Empresa["nombre"]." | ".$me[($buscames-1)].' '.$buscaano); 
    $pdf->Text(185,280,"Página 07");  
    
    $pdf->AddPage('P', 'A4');   
    $pdf->Image('recursos/logo300px.jpg', 10, 10, 30, 12.8, 'JPG', 'http://www.gaagdesarrolloempresarial.com', '', true, 150, '', false, false, 0, false, false, false);
    $pdf->Line(10, 25, 200, 25);
    $pdf->SetFont('Helvetica', '', 10);
    $pdf->Text(9, 26, 'Resumen de Indicadores Financieros');        
    $sqlGastosA01 = "select * from asociaclave where idempresa='".$buscaempresa."' and idclave=9";
    $resultGastosA01=mysql_query($sqlGastosA01,$con) or die(mysql_error());    
    $gastos01=mysql_fetch_assoc($resultGastosA01);      
    
    $sqlGastosA02 = "select * from asociaclave where idempresa='".$buscaempresa."' and idclave=12";
    $resultGastosA02=mysql_query($sqlGastosA02,$con) or die(mysql_error());    
    $gastos02=mysql_fetch_assoc($resultGastosA02);        
    
    graficarcompara($con,$pdf,$buscames,$buscaano,$buscaempresa,$gastos01["codigo"],$gastos02["codigo"],"Ventas vs Costo de Ventas",3,2,"Ventas","Costo de Ventas","Ventas","Costo Venta");  
    $pdf->SetFont('Helvetica', '', 7);
    $pdf->SetTextColor(126,130,109);
    $pdf->Text(10,277,"Creado por GAAG Desarrollo Empresarial");
    $pdf->SetTextColor(0,0,0);
    $pdf->Text(10,280,$Empresa["nombre"]." | ".$me[($buscames-1)].' '.$buscaano); 
    $pdf->Text(185,280,"Página 08");      
    
    $pdf->Output('example_012.pdf', 'I');       
    
?>        
