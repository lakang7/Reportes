<?php
$idestadofinanciero = 1;

    require_once('recursos/tcpdf/tcpdf.php');
    require_once("funciones/funciones.php");

/*Consultas de Base de Datos*/
$con = Conexion();
$sqlselect="select * from empresa where idempresa = ". $_GET["empresa"];
$resultselect=mysql_query($sqlselect,$con) or die(mysql_error());

/*Datos de la empresa*/
$fila = mysql_fetch_assoc($resultselect);
$idempresa = $fila["idempresa"];
$nombre = $fila["nombre"];
$logo = $fila["logo"];

    
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
 
    $buscaempresa = $_GET["empresa"];
    $buscaano = $_GET["anno"];
    $buscames = $_GET["mes"];
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
    $extension=explode(".",$Empresa["logo"]);
    $pdf->Image('logos/'.$Empresa["logo"], 170, 10, 30, 12.8,$extension[1], '', '', true, 150, '', false, false, 0, false, false, false);
    $pdf->Line(10, 25, 200, 25);
    $pdf->SetFont('Helvetica', '', 10);
    $pdf->Text(9, 26, 'Estado de Situación Financiera '.$Empresa["nombre"]." ".$meses[($buscames-1)].' '.$buscaano);  
    $pdf->SetFont('Helvetica', '', 7);
    
$X=10;
$Y=50;
$vectorSaldo[]=0;
$vectorY[]=0;
$vectorX[]=0;

$vectorTotalX[]=0;
$vectorTotalY[]=0;
$vectorTotal[]=0;

$sumatotalagrupacion=0;
$sumapasivocapital=0;
$sumacapital=0;
$maxestructura = fncmaxestructestadofinan($idestadofinanciero);

crearEncabezadoPorcentajes($pdf);


for ($ciclo=1;$ciclo<=$maxestructura;$ciclo++){
    $sumatotalagrupacion=0;
    $contador=0;
    $nombreestructura = fncnombreestructura($ciclo,$idestadofinanciero);
    if ($ciclo==2){ $X=110; $Y=50; };
    crearEncabezado($pdf, $nombreestructura,$X,$Y);
    $Y=crearEstructura($pdf,$idempresa,$ciclo,$X,$Y);
    if ($ciclo==1){$sumacapital=$sumatotalagrupacion;} else {$sumapasivocapital+=$sumatotalagrupacion;} 
    totalesxcuerpo($pdf, $X+5, $Y+30,$ciclo, $sumatotalagrupacion,$sumacapital,$sumapasivocapital, $nombreestructura);
    $Y+=25;
}    

function crearEstructura($pdf,$idempresa,$idestructura,$X,$Y){
    $con = Conexion();
    global $vectorTotalX;           
    global $vectorTotalY;
    global $vectorTotal;
    global $vectorY;           
    global $vectorX;
    global $vectorSaldo;
    global $sumatotalagrupacion;
    $sumatotal =0;
    $maxestructuraagrupacion=fncmaxestructuraagrupacion($idestructura);
    $minestructuraagrupacion=fncminestructuraagrupacion($idestructura);
    global $contador;
    for ($idtipoagrupacion=$minestructuraagrupacion;$idtipoagrupacion<=$maxestructuraagrupacion;$idtipoagrupacion++){
        $tienesaldo=fncTieneSaldolaCategoria($idempresa,$idtipoagrupacion);
        if ($tienesaldo==true)
        {            
            $i = 0;
            $sumatotal =0;
            $suma=0;
            $Y+=10;
            $pdf->SetFont('Helvetica', 'I', 7);
            $pdf->SetXY($X,$Y);
            $pdf->Cell(40, 4,fncPrimeramayuscula(fncnombresubagrupacion ($idtipoagrupacion)), 0, 1, "L", 0, '', 0);
            $pdf->SetFont('Helvetica', '', 7);
            $sqlselect="select * from enasociacion where idempresa= " . $idempresa . " and idtipoagrupacion = " . $idtipoagrupacion . " order by posicion";                         
            $resultselect=mysql_query($sqlselect,$con) or die(mysql_error());
            $fila = mysql_fetch_assoc($resultselect);
            if(mysql_num_rows($resultselect)>0){
                do{
                    $sumaxagrupacion=0;
                    $tipoelemento = $fila["tipoelemento"];
                    $idagrupacion = $fila["idagrupacion"];
                    if ($tipoelemento == "a"){
                        $sqlselectagrup="select * from agrupacioncuentas where idempresa= " . $idempresa . " and idagrupacion = " . $idagrupacion;                         
                        $resultselectagrup=mysql_query($sqlselectagrup,$con) or die(mysql_error());
                        $filaagrup = mysql_fetch_assoc($resultselectagrup);                            
                        if(mysql_num_rows($resultselectagrup)>0){
                            do{                            
                                $codigocuenta = $filaagrup["codigocuenta"];
                                $saldocuenta = 0;
                                $saldocuenta = fncbuscasaldocta($idempresa,fncbuscaridcuenta($idempresa,$codigocuenta),fncbuscaridejercicio($idempresa, $_GET["anno"]),$_GET["mes"],1) * $filaagrup["signo"];
                                if ($saldocuenta<>0) {
                                    $sumaxagrupacion+=$saldocuenta;
                                    $suma+=$saldocuenta;
                                    $sumatotalagrupacion+=$saldocuenta;
                                }
                            } while ($filaagrup = mysql_fetch_assoc($resultselectagrup));
                            if ($sumaxagrupacion>0){
                                $Y+=5;
                                $pdf->SetXY($X+5,$Y);
                                $pdf->Cell(30, 4,fncPrimeramayuscula(fncnombreagrupacion($idagrupacion)) , 0, 1, "L", 0, '', 0);
                                $pdf->SetXY($X+35,$Y);                            
                                $pdf->Cell(30, 4,number_format($sumaxagrupacion,2), 0, 1, "R", 0, '', 0);
                                $i+=1;
                                $vectorSaldo[$i] = $sumaxagrupacion;
                                $vectorY[$i]     = $Y;
                                $vectorX[$i]    = $X+55;
                                $pdf->SetXY(120,$i*5);
                            }
                        }                          
                    }elseif (($tipoelemento == "c")){
                        $codigocuenta = $fila["codigocuenta"];
                        $saldocuenta = fncbuscasaldocta($idempresa,fncbuscaridcuenta($idempresa,$codigocuenta),fncbuscaridejercicio($idempresa, $_GET["anno"]),$_GET["mes"],1) * $fila["signo"];
                            if ($saldocuenta<>0) {
                            $Y+=5;
                            $i+=1;
                            $sumatotalagrupacion+=$saldocuenta;
                            $vectorSaldo[$i] = $saldocuenta;
                            $vectorX[$i] = $X+55;
                            $vectorY[$i] = $Y;
                            $pdf->SetXY($X+5,$Y);
                            $variable = fncPrimeramayuscula(fncnombrecuenta(fncbuscaridcuenta($idempresa,$codigocuenta)));
                            $pdf->Cell(30, 4, $variable , 0, 1, "L", 0, '', 0);
                            $pdf->SetXY($X+35,$Y);                            
                            $pdf->Cell(30, 4,number_format($saldocuenta,2), 0, 1, "R", 0, '', 0);
                            $suma+=$saldocuenta;
                        }
                    }                
                } while ($fila = mysql_fetch_assoc($resultselect));
            }
            $Y+=8;
            $pdf->Line($X+45, $Y, $X+90, $Y);
            $Y+=2;
            $pdf->SetFont('Helvetica', 'I', 7);
            $pdf->SetXY($X,$Y);
            $pdf->Cell(30, 4, "Total " . fncPrimeramayuscula(fncnombresubagrupacion  ($idtipoagrupacion)), 0, 1, "L", 0, '', 0);
            $pdf->SetXY($X+35,$Y);
            $pdf->Cell(30, 4, number_format($suma,2), 0, 1, "R", 0, '', 0);
            $pdf->SetFont('Helvetica', '', 7);  
            $contador++;              
            $vectorTotalX[$contador]=$X;
            $vectorTotalY[$contador]=$Y;
            $vectorTotal[$contador]=$suma;

            for($j=1;$j<=$i;$j++){
                $sumatotal+=$vectorSaldo[$j];
            }       
            for($r=1;$r<=$i;$r++){
                $pdf->SetXY($vectorX[$r]+20,$vectorY[$r]-18);
                $pdf->Cell(3,40,number_format(($vectorSaldo[$r]/$sumatotal)*100,2). " %" , 0, 1, "R", 0, '', 0);
                if ($r==$i){
                  $pdf->SetFont('Helvetica', 'I', 7);  
                  $pdf->SetXY($vectorX[$r]-7,$vectorY[$r]+10);
                  $pdf->Cell(30, 4, "100.00 %" , 0, 1, "R", 0, '', 0);
                  $pdf->SetFont('Helvetica', '', 7);
                }
            }
        }   
    }    
    return $Y;
}


function crearEncabezado($pdf, $nombreestructura,$X,$Y){
    $pdf->SetFont('Helvetica', 'B', 8);
    $pdf->SetXY($X,$Y);
    $pdf->Cell(40, 4,strtoupper($nombreestructura), 0, 1, "L", 0, '', 0);
    $pdf->SetFont('Helvetica', '', 7);
}

function totalesxcuerpo($pdf,$X,$Y,$ciclo,$sumatotalagrupacion,$sumacapital,$sumapasivocapital,$nombreestructura){
    global $maxestructura;
    global $contador;
    global $vectorTotal;
    global $vectorTotalX;
    global $vectorTotalY;

    for($r=1;$r<=$contador;$r++){
        $pdf->SetFont('Helvetica', 'I', 7);
        $pdf->SetXY($vectorTotalX[$r]+52,$vectorTotalY[$r]);
        $pdf->Cell(40,4,number_format( ($vectorTotal[$r]/ $sumatotalagrupacion)*100,2) . " %", 0, 1, "R", 0, '', 0);
        $pdf->SetFont('Helvetica', '', 7);
    }


    if ($ciclo>1){
        $pdf->SetXY($X-5,$Y-18);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Line($X+40, $Y-18, $X+85, $Y-18);
        $pdf->Cell(40,4, strtoupper("Total ". $nombreestructura),0,1,"L",0,'',0); 
        $pdf->SetXY($X+20,$Y-18);
        $pdf->Cell(40,4,number_format($sumatotalagrupacion,2), 0, 1, "R", 0, '', 0);
        $pdf->SetXY($X+75,$Y-18);
        $pdf->Cell(15, 3,"100 %", 0, 1, 'C', 0, '', 0);
    }
    if ($ciclo==$maxestructura) {
        $pdf->Line($X-60, $Y-2, $X-15 , $Y-2);
        $pdf->Line($X-60, $Y+6, $X-15 , $Y+6);
        $pdf->SetXY($X-105,$Y);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(40,4, strtoupper("Total Activo"),0,1,"L",0,'',0); 
        $pdf->SetXY($X-80,$Y);
        $pdf->Cell(40,4,number_format($sumacapital,2), 0, 1, "R", 0, '', 0);
        $pdf->SetXY($X-25,$Y);
        $pdf->Cell(15, 3,"100 %", 0, 1, 'C', 0, '', 0);

        
        $pdf->Line($X+40, $Y-2, $X+85 , $Y-2);
        $pdf->Line($X+40, $Y+6, $X+85 , $Y+6);
        $pdf->SetXY($X-5,$Y);
        $pdf->Cell(40,4, strtoupper("Total Pasivo y Capital"),0,1,"L",0,'',0); 
        $pdf->SetXY($X+20,$Y);
        $pdf->Cell(40,4,number_format($sumapasivocapital,2), 0, 1, "R", 0, '', 0);
        $pdf->SetXY($X+75,$Y);
        $pdf->Cell(15, 3,"100 %", 0, 1, 'C', 0, '', 0);
    }
    $pdf->SetFont('Helvetica', '', 7);
}

function crearEncabezadoPorcentajes ($pdf){
    $pdf->SetFont('Helvetica', '', 6);
    $pdf->SetXY(75, 40);
    $pdf->Cell(15, 3,"Porcientos", 0, 1, 'C', 0, '', 0); 
    $pdf->SetXY(75, 42);
    $pdf->Cell(15, 3,"integrales", 0, 1, 'C', 0, '', 0);
    $pdf->SetXY(75, 44);
    $pdf->Cell(15, 3,"de activos", 0, 1, 'C', 0, '', 0);
    
    $pdf->SetXY(90, 40);
    $pdf->Cell(15, 3,"Porcientos", 0, 1, 'C', 0, '', 0); 
    $pdf->SetXY(90, 42);
    $pdf->Cell(15, 3,"integrales", 0, 1, 'C', 0, '', 0);
    $pdf->SetXY(90, 44);
    $pdf->Cell(15, 3,"en ralación", 0, 1, 'C', 0, '', 0);    
    
    $pdf->SetXY(175, 40);
    $pdf->Cell(15, 3,"Porcientos", 0, 1, 'C', 0, '', 0); 
    $pdf->SetXY(175, 42);
    $pdf->Cell(15, 3,"integrales", 0, 1, 'C', 0, '', 0);
    $pdf->SetXY(175, 44);
    $pdf->Cell(15, 3,"de pasivos", 0, 1, 'C', 0, '', 0);
    
    $pdf->SetXY(190, 40);
    $pdf->Cell(15, 3,"Porcientos", 0, 1, 'C', 0, '', 0); 
    $pdf->SetXY(190, 42);
    $pdf->Cell(15, 3,"integrales", 0, 1, 'C', 0, '', 0);
    $pdf->SetXY(190, 44);
    $pdf->Cell(15, 3,"en ralación", 0, 1, 'C', 0, '', 0);     
}
    
    $pdf->SetFont('Helvetica', '', 8);    
    $pdf->Line(35,260,95,260);
    $pdf->SetXY(35, 261);   
    $pdf->Cell(60, 4," L.C. Nelly Galicia Aguilar", 0, 1, 'C', 0, '', 0);    
    $pdf->Line(115,260,175,260);
    $pdf->SetXY(115, 261);   
    $pdf->Cell(60, 4,$Empresa["representante"], 0, 1, 'C', 0, '', 0);  
    $pdf->SetXY(115, 265);     
    $pdf->Cell(60, 4,"Representante Legal", 0, 1, 'C', 0, '', 0); 
    
    $pdf->SetXY(35, 265);     
    $pdf->Cell(60, 4,"Los  Estados Financieros no ha sido dictaminados", 0, 1, 'C', 0, '', 0);    

    $pdf->SetFont('Helvetica', '', 7);
    $pdf->SetTextColor(126,130,109);
    $pdf->Text(10,277,"Creado por GAAG Desarrollo Empresarial");
    $pdf->SetTextColor(0,0,0);
    $pdf->Text(10,280,$Empresa["nombre"]." | ".$me[($buscames-1)].' '.$buscaano); 
    $pdf->Text(185,280,"Página 02");    
    
    
    $pdf->SetFont('Helvetica', '', 8);
    $pdf->AddPage('P', 'A4');
    
    $pdf->Image('recursos/logo300px.jpg', 10, 10, 30, 12.8, 'JPG', 'http://www.gaagdesarrolloempresarial.com', '', true, 150, '', false, false, 0, false, false, false);
    $pdf->Image('logos/'.$Empresa["logo"], 170, 10, 30, 12.8,$extension[1], '', '', true, 150, '', false, false, 0, false, false, false);
    $pdf->Line(10, 25, 200, 25);
    $pdf->SetFont('Helvetica', '', 10);
    $pdf->Text(9, 26, 'Estado de Resultados '.$Empresa["nombre"]." ".$meses[($buscames-1)].' '.$buscaano);
    $pdf->SetFont('Helvetica', '', 8);    
    
    
    //aqui
$idestadofinanciero = 2;
$maxestructura = fncmaxestructestadofinan($idestadofinanciero);
$minestructura = fncminestructestadofinan($idestadofinanciero);
$dif = $maxestructura-$minestructura;

$Y=40;
$X=10;

$pdf->SetXY($X+80,$Y-5);                            
$pdf->Cell(30, 4,"Periodo", 0, 1, "R", 0, '', 0); 
$pdf->SetXY($X+105,$Y-5);                            
$pdf->Cell(30, 4,"% Intregrales", 0, 1, "R", 0, '', 0); 
$pdf->SetXY($X+130,$Y-5);                            
$pdf->Cell(30, 4,"Acumulado", 0, 1, "R", 0, '', 0); 
$pdf->SetXY($X+155,$Y-5);                            
$pdf->Cell(30, 4,"% Integrales", 0, 1, "R", 0, '', 0);

$primeraFila="Si";
$primeraFilaAcu = "Si";
for ($idestructura=$minestructura;$idestructura<=$maxestructura;$idestructura++){
    $nombreestructura = fncnombreestructura($idestructura,$idestadofinanciero);
    $Y+=5;
    crearEstructura_er($pdf,$idempresa,$idestructura);
    $Y+=5;
    crearTotales($pdf,$idestructura-$dif+1,$X,$Y);
}   
$totalparcialxagrupacion=0;
$totalsaldoxagrupacio=0;
$restaxagrupacion=0;
$restahaberxagrupacion=0;
$sumaxagrupacioncuenta=0;
$sumahaberxagrupacioncuenta=0;
function crearEstructura_er($pdf,$idempresa,$idestructura)
{
    global $X;
    global $Y;
    global $con;
    global $restaxagrupacion;
    global $restahaberxagrupacion;
    global $sumaxagrupacioncuenta;
    global $sumahaberxagrupacioncuenta;
    global $totalsaldoxagrupacion;
    global $totalparcialxagrupacion;
    global $primeraFila;
    global $primeraFilaAcu;
    $IdTipoAgrupacionEst = fncIdTipoAgrupacionEst($idestructura);
    $minidtipoagrupacionest = fncminestructaasociacion($IdTipoAgrupacionEst);
    $maxidtipoagrupacionest = fncmaxestructaasociacion($IdTipoAgrupacionEst);
    $contador=0;
    for ($idagrupacionest=$minidtipoagrupacionest; $idagrupacionest<=$maxidtipoagrupacionest; $idagrupacionest++ ){
        $sumaxagrupacioncuenta=0;
        $sumahaberxagrupacioncuenta=0;
        $sqlselectagrup="select * from agrupacioncuentasest where idempresa= " . $idempresa . " and idagrupacionest = " . $idagrupacionest;                         
        $resultselectagrup=mysql_query($sqlselectagrup,$con) or die(mysql_error());
        $filaagrup = mysql_fetch_assoc($resultselectagrup);                            
        if(mysql_num_rows($resultselectagrup)>0){
            do{ 
                $contador++;
                $parcialcuenta=0;    
                $codigocuenta   = $filaagrup["codigocuenta"];
                $tipoa=$filaagrup["tipoa"];
                $tipop=$filaagrup["tipop"];
                $signo=$filaagrup["signo"];
                $saldocuenta    = fncbuscasaldocta($idempresa,fncbuscaridcuenta($idempresa,$codigocuenta),fncbuscaridejercicio($idempresa, $_GET["anno"]),$_GET["mes"],$tipoa) * $filaagrup["signo"];
                $parcialcuenta  = fncbuscasaldocta($idempresa,fncbuscaridcuenta($idempresa,$codigocuenta),fncbuscaridejercicio($idempresa, $_GET["anno"]),$_GET["mes"],$tipop) * $filaagrup["signo"];
                //echo " restaxagrupacion = " . $restaxagrupacion . "  / idagrupacionest " . $idagrupacionest . " idCuenta " . fncbuscaridcuenta($idempresa,$codigocuenta) . " Cuenta = " . $codigocuenta . " Saldo = " . $parcialcuenta . " - ";
                if ($primeraFila=="Si"){
                    $totalparcialxagrupacion = $parcialcuenta;
                    //echo "Saldo = " . $saldocuenta;
                    $totalsaldoxagrupacion   = $saldocuenta;
                    $primeraFila="No";
                }
                
                if ($sumaxagrupacioncuenta==0){$sumaxagrupacioncuenta=$saldocuenta;}
                else{$sumaxagrupacioncuenta=$sumaxagrupacioncuenta-$saldocuenta;}             
                if ($sumahaberxagrupacioncuenta==0){$sumahaberxagrupacioncuenta=$parcialcuenta;}
                else{
                    if ($signo==1){
                        $sumahaberxagrupacioncuenta=$sumahaberxagrupacioncuenta+$parcialcuenta;
                    }else{
                        $sumahaberxagrupacioncuenta=$sumahaberxagrupacioncuenta-$parcialcuenta;
                    }
                }                
                if ($restahaberxagrupacion==0){$restahaberxagrupacion=$parcialcuenta;}
                else{$restahaberxagrupacion=$restahaberxagrupacion-$parcialcuenta;}    
            } while ($filaagrup = mysql_fetch_assoc($resultselectagrup));
            
            if ($primeraFilaAcu=="Si"){
                //echo "Entro"; 
                $restaxagrupacion=$sumaxagrupacioncuenta;
                $primeraFilaAcu="No";
            }
            else{
                //echo " / restaxagrupacion = " . $restaxagrupacion . " - " . $sumaxagrupacioncuenta;
                $restaxagrupacion=$restaxagrupacion-$sumaxagrupacioncuenta;
            }    

        }
        $Y+=5;
        $pdf->SetXY($X,$Y);
        $pdf->Cell(30, 4,fncnombreagrupacionest($idagrupacionest) , 0, 1, "L", 0, '', 0);
        $pdf->SetXY($X+130,$Y);                            
        $pdf->Cell(30, 4,number_format($sumaxagrupacioncuenta,2), 0, 1, "R", 0, '', 0);
        $pdf->SetXY($X+80,$Y);                            
        $pdf->Cell(30, 4,number_format($sumahaberxagrupacioncuenta,2), 0, 1, "R", 0, '', 0);
        
        $pdf->SetXY($X+105,$Y);    
        $pdf->Cell(30, 4,number_format( ($sumahaberxagrupacioncuenta*100)/$totalparcialxagrupacion,2). " %", 0, 1, "R", 0, '', 0);
        $pdf->SetXY($X+155,$Y);                            
        $pdf->Cell(30, 4, number_format(($sumaxagrupacioncuenta*100)/$totalsaldoxagrupacion,2). " %", 0, 1, "R", 0, '', 0);
        
    }
}

function crearTotales($pdf,$idtipoagrupacionest,$X,$Y){
    global $totalsaldoxagrupacion;
    global $totalparcialxagrupacion;
    global $restaxagrupacion;
    global $restahaberxagrupacion;
    $pdf->SetFont('Helvetica', 'B', 8);
    $pdf->Line($X+90, $Y-1, $X+190 , $Y-1);
    $pdf->SetXY($X+5,$Y);
    $pdf->Cell(30, 4,fncNombreTipoAgrupacionEst($idtipoagrupacionest) , 0, 1, "L", 0, '', 0);
    $pdf->SetXY($X+130,$Y);                            
    $pdf->Cell(30, 4,number_format($restaxagrupacion,2), 0, 1, "R", 0, '', 0);
    $pdf->SetXY($X+80,$Y);                            
    $pdf->Cell(30, 4,number_format($restahaberxagrupacion,2), 0, 1, "R", 0, '', 0);
      
    $pdf->SetXY($X+105,$Y);          
    $pdf->Cell(30, 4,number_format( ($restahaberxagrupacion*100)/$totalparcialxagrupacion,2). " %", 0, 1, "R", 0, '', 0);
    
    $pdf->SetXY($X+155,$Y);                            
    $pdf->Cell(30, 4,number_format(($restaxagrupacion*100)/$totalsaldoxagrupacion,2)." %", 0, 1, "R", 0, '', 0);
    $pdf->SetFont('Helvetica', '', 7);
}
    
    
    //aqui  
    
    $pdf->SetFont('Helvetica', '', 8);
    $pdf->Line(35,260,95,260);
    $pdf->SetXY(35, 261);   
    $pdf->Cell(60, 4,"L.C. Nelly Galicia Aguilar", 0, 1, 'C', 0, '', 0);    
    $pdf->Line(115,260,175,260);
    $pdf->SetXY(115, 261);   
    $pdf->Cell(60, 4,$Empresa["representante"], 0, 1, 'C', 0, '', 0);  
    $pdf->SetXY(115, 265); 
    $pdf->Cell(60, 4,"Representante Legal", 0, 1, 'C', 0, '', 0);    
    
    $pdf->SetXY(35, 265);     
    $pdf->Cell(60, 4,"Los  Estados Financieros no ha sido dictaminados", 0, 1, 'C', 0, '', 0);     
    
    $pdf->SetFont('Helvetica', '', 7);
    $pdf->SetTextColor(126,130,109);
    $pdf->Text(10,277,"Creado por GAAG Desarrollo Empresarial");
    $pdf->SetTextColor(0,0,0);
    $pdf->Text(10,280,$Empresa["nombre"]." | ".$me[($buscames-1)].' '.$buscaano); 
    $pdf->Text(185,280,"Página 03");      
    

    
    
    
    
    $pdf->SetFont('Helvetica', '', 8);
    $pdf->AddPage('P', 'A4');
    
    $pdf->Image('recursos/logo300px.jpg', 10, 10, 30, 12.8, 'JPG', 'http://www.gaagdesarrolloempresarial.com', '', true, 150, '', false, false, 0, false, false, false);
    $pdf->Image('logos/'.$Empresa["logo"], 170, 10, 30, 12.8,$extension[1], '', '', true, 150, '', false, false, 0, false, false, false);    
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
    
   /* print_r($razonesporca);
    echo "</br>";
    print_r($valoresporra);
    echo "</br></br>Nuevas Listas";*/
    
    $nuevaLista1 = array();
    $nuevaLista2 = array();
    
    for($i=0;$i<$totalcategorias;$i++){
        $nuevaLista1[$i]="";
        $nuevaLista2[$i]="";
        $temporal1= explode("-",$razonesporca[$i]);
        $temporal2= explode(";",$valoresporra[$i]);
        for($j=0;$j<count($temporal1);$j++){
            if($temporal2[$j]!=0 && $temporal1[$j]!=10 && $temporal1[$j]!=15){
                $nuevaLista1[$i]=$nuevaLista1[$i].$temporal1[$j]."-";
                $nuevaLista2[$i]=$nuevaLista2[$i].$temporal2[$j].";";
            }
        }
    }
    
    /*print_r($nuevaLista1);
    echo "</br>";
    print_r($nuevaLista2);  */  
    
    for($i=0;$i<$totalcategorias;$i++){
        $pdf->SetFont('Helvetica', '', 8);
        //$listarazones = explode("-",$razonesporca[$i]);
        //$listavalores = explode(";",$valoresporra[$i]);
        $listarazones = explode("-",$nuevaLista1[$i]);
        $listavalores = explode(";",$nuevaLista2[$i]);        
        for($j=0;$j<(count($listarazones)-1);$j++){
            
            $sqlRazon = "select * from razonfinanciera where idrazonfinanciera='".$listarazones[$j]."'";
            $resutlRazon=mysql_query($sqlRazon,$con) or die(mysql_error());             
            $raz=mysql_fetch_assoc($resutlRazon);            
            $pdf->StartTransform();
            $pdf->Rotate(($angulo*7.2), 105, 135);
            
            if($raz["tipo"]==1){
                $listavalores[$j]=$listavalores[$j]*100;
            }            
            
            if($raz["evaluacion"]==0){
                if((double)$listavalores[$j]>=1){
                    $pdf->Polygon(array(156.715,129.565,157, 135,163, 135,162.682,128.937), 'F', array($style1), array(125, 190, 17));
                }else{
                    $pdf->Polygon(array(156.715,129.565,157, 135,163, 135,162.682,128.937), 'F', array($style1), array(208, 0, 10));
                }                 
            } 
            
            if($raz["evaluacion"]==4){
                if((double)$listavalores[$j]>1){
                    $pdf->Polygon(array(156.715,129.565,157, 135,163, 135,162.682,128.937), 'F', array($style1), array(125, 190, 17));
                }else{
                    $pdf->Polygon(array(156.715,129.565,157, 135,163, 135,162.682,128.937), 'F', array($style1), array(208, 0, 10));
                }                 
            } 
            
            if($raz["evaluacion"]==5){
                if((double)$listavalores[$j]>30){
                    $pdf->Polygon(array(156.715,129.565,157, 135,163, 135,162.682,128.937), 'F', array($style1), array(125, 190, 17));
                }else{
                    $pdf->Polygon(array(156.715,129.565,157, 135,163, 135,162.682,128.937), 'F', array($style1), array(208, 0, 10));
                }                 
            }  
            if($raz["evaluacion"]==6){
                if((double)$listavalores[$j]>1.5){
                    $pdf->Polygon(array(156.715,129.565,157, 135,163, 135,162.682,128.937), 'F', array($style1), array(125, 190, 17));
                }else{
                    $pdf->Polygon(array(156.715,129.565,157, 135,163, 135,162.682,128.937), 'F', array($style1), array(208, 0, 10));
                }                 
            }   
            if($raz["evaluacion"]==7){
                if((double)$listavalores[$j]<40){
                    $pdf->Polygon(array(156.715,129.565,157, 135,163, 135,162.682,128.937), 'F', array($style1), array(125, 190, 17));
                }else{
                    $pdf->Polygon(array(156.715,129.565,157, 135,163, 135,162.682,128.937), 'F', array($style1), array(208, 0, 10));
                }                 
            }    
            
            if($raz["evaluacion"]==8){
                if((double)$listavalores[$j]>3.5){
                    $pdf->Polygon(array(156.715,129.565,157, 135,163, 135,162.682,128.937), 'F', array($style1), array(125, 190, 17));
                }else{
                    $pdf->Polygon(array(156.715,129.565,157, 135,163, 135,162.682,128.937), 'F', array($style1), array(208, 0, 10));
                }                 
            }  
            
            if($raz["evaluacion"]==9){ 
                
                if((double)$listavalores[$j]==0){
                    
                    $pdf->Polygon(array(156.715,129.565,157, 135,163, 135,162.682,128.937), 'F', array($style1), array(125, 190, 17));
                }else{
                    if((double)$listavalores[$j]>= $Empresa["extra1"]){
                        $pdf->Polygon(array(156.715,129.565,157, 135,163, 135,162.682,128.937), 'F', array($style1), array(125, 190, 17));
                    }else{
                        $pdf->Polygon(array(156.715,129.565,157, 135,163, 135,162.682,128.937), 'F', array($style1), array(208, 0, 10));
                    }                     
                }                                                
            } 
            
            if($raz["evaluacion"]==10){
                if((double)$listavalores[$j]<= $Empresa["extra2"]){
                    $pdf->Polygon(array(156.715,129.565,157, 135,163, 135,162.682,128.937), 'F', array($style1), array(125, 190, 17));
                }else{
                    $pdf->Polygon(array(156.715,129.565,157, 135,163, 135,162.682,128.937), 'F', array($style1), array(208, 0, 10));
                }                 
            }  
            
            if($raz["evaluacion"]==11){
                if((double)$listavalores[$j]>=0 && (double)$listavalores[$j]<=1){
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
            if($raz["evaluacion"]==4){
                if((double)$listavalores[$j]>1){
                    $cuentaOK++;
                    $pdf->Text(105,135, '                                                                    O');
                }else{
                    $cuentaFA++;
                    $pdf->Text(105,135, '                                                                    X');
                }                
            }   
              if($raz["evaluacion"]==5){
                if((double)$listavalores[$j]>30){
                    $cuentaOK++;
                    $pdf->Text(105,135, '                                                                    O');
                }else{
                    $cuentaFA++;
                    $pdf->Text(105,135, '                                                                    X');
                }                
            }
            if($raz["evaluacion"]==6){
                if((double)$listavalores[$j]>1.5){
                    $cuentaOK++;
                    $pdf->Text(105,135, '                                                                    O');
                }else{
                    $cuentaFA++;
                    $pdf->Text(105,135, '                                                                    X');
                }                
            }        
            if($raz["evaluacion"]==7){
                if((double)$listavalores[$j]<40){
                    $cuentaOK++;
                    $pdf->Text(105,135, '                                                                    O');
                }else{
                    $cuentaFA++;
                    $pdf->Text(105,135, '                                                                    X');
                }                
            }   
            if($raz["evaluacion"]==8){
                if((double)$listavalores[$j]>3.5){
                    $cuentaOK++;
                    $pdf->Text(105,135, '                                                                    O');
                }else{
                    $cuentaFA++;
                    $pdf->Text(105,135, '                                                                    X');
                }                
            }   
            if($raz["evaluacion"]==9){
                
                if((double)$listavalores[$j]==0){
                    $cuentaOK++;
                    $pdf->Text(105,135, '                                                                    O');                    
                }else{
                    if((double)$listavalores[$j] >= $Empresa["extra1"]){
                        $cuentaOK++;
                        $pdf->Text(105,135, '                                                                    O');
                    }else{
                        $cuentaFA++;
                        $pdf->Text(105,135, '                                                                    X');
                    }                                         
                }               
            } 
            
            if($raz["evaluacion"]==10){
                if((double)$listavalores[$j] <= $Empresa["extra1"]){
                    $cuentaOK++;
                    $pdf->Text(105,135, '                                                                    O');
                }else{
                    $cuentaFA++;
                    $pdf->Text(105,135, '                                                                    X');
                }                
            }   
            
            if($raz["evaluacion"]==11){
                if((double)$listavalores[$j] >= 0 && (double)$listavalores[$j] <= 1){
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
    $pdf->Text(185,280,"Página 04");
    
    $pdf->AddPage('P', 'A4');    
    $pdf->Image('recursos/logo300px.jpg', 10, 10, 30, 12.8, 'JPG', 'http://www.gaagdesarrolloempresarial.com', '', true, 150, '', false, false, 0, false, false, false);
    $pdf->Image('logos/'.$Empresa["logo"], 170, 10, 30, 12.8,$extension[1], '', '', true, 150, '', false, false, 0, false, false, false);
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
            if($Raz["evaluacion"]==4){
                $pdf->Text(148,$posiciony+4,"> 1");
            }              
            if($Raz["evaluacion"]==5){
                $pdf->Text(148,$posiciony+4,"> 30 %");
            }       
            if($Raz["evaluacion"]==6){
                $pdf->Text(148,$posiciony+4,"> 1.5");
            }     
            if($Raz["evaluacion"]==7){
                $pdf->Text(148,$posiciony+4,"< 40 %");
            } 
            if($Raz["evaluacion"]==8){
                $pdf->Text(148,$posiciony+4,"> 3.5 %");
            }  
            if($Raz["evaluacion"]==9){
                $pdf->Text(148,$posiciony+4,">= ".$Empresa["extra1"]);
            }  
            if($Raz["evaluacion"]==10){
                $pdf->Text(148,$posiciony+4,"<= ".$Empresa["extra2"]);
            } 
            if($Raz["evaluacion"]==11){
                $pdf->Text(144.5,$posiciony+4,">=0 & <=1");
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
            if($Raz["evaluacion"]==4){
                if($lisval[$j]>1){
                    $pdf->SetFillColor(125, 190, 17);
                }else{
                    $pdf->SetFillColor(208, 0, 10);
                }                                
            }  
            if($Raz["evaluacion"]==5){
                if($lisval[$j]>30){
                    $pdf->SetFillColor(125, 190, 17);
                }else{
                    $pdf->SetFillColor(208, 0, 10);
                }                                
            }   
            if($Raz["evaluacion"]==6){
                if($lisval[$j]>1.5){
                    $pdf->SetFillColor(125, 190, 17);
                }else{
                    $pdf->SetFillColor(208, 0, 10);
                }                                
            }   
            if($Raz["evaluacion"]==7){
                if($lisval[$j]<40){
                    $pdf->SetFillColor(125, 190, 17);
                }else{
                    $pdf->SetFillColor(208, 0, 10);
                }                                
            }  
            if($Raz["evaluacion"]==8){
                if($lisval[$j]>3.5){
                    $pdf->SetFillColor(125, 190, 17);
                }else{
                    $pdf->SetFillColor(208, 0, 10);
                }                                
            }  
            if($Raz["evaluacion"]==9){
                if((double)$lisval[$j]==0){
                    $pdf->SetFillColor(125, 190, 17);
                }else{
                    if($lisval[$j]>= $Empresa["extra1"]){
                        $pdf->SetFillColor(125, 190, 17);
                    }else{
                        $pdf->SetFillColor(208, 0, 10);
                    }                    
                }
                
                                
            }  
            if($Raz["evaluacion"]==10){
                if($lisval[$j]<= $Empresa["extra2"]){
                    $pdf->SetFillColor(125, 190, 17);
                }else{
                    $pdf->SetFillColor(208, 0, 10);
                }                                
            }  
            if($Raz["evaluacion"]==11){
                if($lisval[$j]>= 0 && $lisval[$j]<= 1){
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
    $pdf->Text(185,280,"Página 05");    
   
    $pdf->AddPage('P', 'A4');   
    $pdf->Image('recursos/logo300px.jpg', 10, 10, 30, 12.8, 'JPG', 'http://www.gaagdesarrolloempresarial.com', '', true, 150, '', false, false, 0, false, false, false);
    $pdf->Image('logos/'.$Empresa["logo"], 170, 10, 30, 12.8,$extension[1], '', '', true, 150, '', false, false, 0, false, false, false);
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
        
        $acum01=0;
        $acum02=0;
        $columna=25;
        $pdf->SetFont('Helvetica', '', 7);
        for($i=0;$i<12;$i++){
            $pdf->Line($columna,224,$columna,242);                      
            $pdf->SetXY($columna,224);                
            $pdf->Cell(14.5,6,$me[$i], 0, 1, 'C', 0, '', 1);

            if($i<$mescalculando){
                $pdf->SetXY($columna,230);
                $pdf->Cell(13.3,6,number_format(round($uno[$i],0)), 0, 1, 'C', 0, '', 1);  
                $acum01+=$uno[$i];
                $pdf->SetXY($columna,236);
                $pdf->Cell(13.3,6,number_format(round($dos[$i],0)), 0, 1, 'C', 0, '', 1);
                $acum02+=$dos[$i];
            }
            
            $columna+=13.3;
        }
        
        $pdf->Line($columna,224,$columna,242);
        $pdf->SetXY($columna,224);
        $pdf->Cell(13.3,6,"Total", 0, 1, 'C', 0, '', 1);
        $pdf->SetXY($columna,230);
        $pdf->Cell(13.3,6,number_format(round($acum01,0)), 0, 1, 'C', 0, '', 1);
        $pdf->SetXY($columna,236);
        $pdf->Cell(13.3,6,number_format(round($acum02,0)), 0, 1, 'C', 0, '', 1);        
        
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
        
        $acum01=0;
        $acum02=0;
        $columna=25;
        $pdf->SetFont('Helvetica', '', 7);
        for($i=0;$i<12;$i++){
                $pdf->Line($columna,224,$columna,242);            
                $pdf->SetXY($columna,224);
                $pdf->Cell(13.3,6,$me[$i], 0, 1, 'C', 0, '', 1);                                
                $pdf->SetXY($columna,230);
                $pdf->Cell(13.3,6,number_format(round($uno[$i],0)), 0, 1, 'C', 0, '', 1);
                $acum01+=$uno[$i];
                if($i<$mescalculando){
                    $pdf->SetXY($columna,236);                    
                    $pdf->Cell(13.3,6,number_format(round($dos[$i],0)), 0, 1, 'C', 0, '', 1); 
                    $acum02+=$dos[$i];
                }                                                                          
            $columna+=13.3;
        }
               
        $pdf->Line($columna,224,$columna,242);
        $pdf->SetXY($columna,224);
        $pdf->Cell(13.3,6,"Total", 0, 1, 'C', 0, '', 1);
        $pdf->SetXY($columna,230);
        $pdf->Cell(13.3,6,number_format(round($acum01,0)), 0, 1, 'C', 0, '', 1);
        $pdf->SetXY($columna,236);
        $pdf->Cell(13.3,6,number_format(round($acum02,0)), 0, 1, 'C', 0, '', 1); 
                    
                        
        
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
    $pdf->Text(185,280,"Página 06"); 
    
    $pdf->AddPage('P', 'A4');   
    $pdf->Image('recursos/logo300px.jpg', 10, 10, 30, 12.8, 'JPG', 'http://www.gaagdesarrolloempresarial.com', '', true, 150, '', false, false, 0, false, false, false);
    $pdf->Image('logos/'.$Empresa["logo"], 170, 10, 30, 12.8,$extension[1], '', '', true, 150, '', false, false, 0, false, false, false);    
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
    $pdf->Text(185,280,"Página 07");  
    
    $pdf->AddPage('P', 'A4');   
    $pdf->Image('recursos/logo300px.jpg', 10, 10, 30, 12.8, 'JPG', 'http://www.gaagdesarrolloempresarial.com', '', true, 150, '', false, false, 0, false, false, false);
    $pdf->Image('logos/'.$Empresa["logo"], 170, 10, 30, 12.8,$extension[1], '', '', true, 150, '', false, false, 0, false, false, false);    
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
    $pdf->Text(185,280,"Página 08");    
    
    $pdf->AddPage('P', 'A4');   
    $pdf->Image('recursos/logo300px.jpg', 10, 10, 30, 12.8, 'JPG', 'http://www.gaagdesarrolloempresarial.com', '', true, 150, '', false, false, 0, false, false, false);
    $pdf->Image('logos/'.$Empresa["logo"], 170, 10, 30, 12.8,$extension[1], '', '', true, 150, '', false, false, 0, false, false, false);    
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
    $pdf->Text(185,280,"Página 09");  
    
    $pdf->AddPage('P', 'A4');   
    $pdf->Image('recursos/logo300px.jpg', 10, 10, 30, 12.8, 'JPG', 'http://www.gaagdesarrolloempresarial.com', '', true, 150, '', false, false, 0, false, false, false);
    $pdf->Image('logos/'.$Empresa["logo"], 170, 10, 30, 12.8,$extension[1], '', '', true, 150, '', false, false, 0, false, false, false);    
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
    $pdf->Text(185,280,"Página 10");      
    
    if($_GET["opcion"]==1){
        $pdf->Output('Razones Financieras '.$Empresa["nombre"].'-'.$meses[($buscames-1)].'-'.$buscaano.'.pdf', 'I');  
    }else{
        ob_clean();
        $pdf->Output('C:\xampp\htdocs\Reportes\temporal\Razones Financieras '.$Empresa["nombre"].'-'.$meses[($buscames-1)].'-'.$buscaano.'.pdf', 'F');
        
        require_once "Mail.php";
        include 'Mail/mime.php' ;

        $from = '<contacto@gaagdesarrolloempresarial.com>';
        $sqlCorreos="select * from emails where idempresa='".$buscaempresa."';";
        $resutlCorreos=mysql_query($sqlCorreos,$con) or die(mysql_error());
        $listamails="";
        $band=0;
        while ($correo=mysql_fetch_assoc($resutlCorreos)) {
            $correo = $correo["correo"];
            if($band==0){
                $listamails=$listamails.$correo;
            }else{
                $listamails=$listamails.",".$correo;
            }
            
            $band=1;
        }        
        $listamails=$listamails."";
        //echo $listamails;
        $to = $listamails;
        $subject = 'Reporte de Indicadores Financieros '.$meses[$buscames].' de '.$buscaano.' '.$Empresa["nombre"];

        $headers = array(
            'From' => $from,
            'To' => $to,
            'Subject' => $subject
        ); 
        
        $mime = new Mail_mime();
        $mime -> setHTMLBody("Buenas Tardes, Te enviamos el reporte para el mes de ".$meses[$buscames]." con el calculo de las razones financieras que te pueden indicar el estatus financiero de tu empresa.\n");
        $mime -> addAttachment('C:\xampp\htdocs\Reportes\temporal\Razones Financieras '.$Empresa["nombre"].'-'.$meses[($buscames-1)].'-'.$buscaano.'.pdf','pdf');
        $body = $mime->get();
        $headers = $mime -> headers($headers);        
        
        $smtp = Mail::factory('smtp', array(
            'host' => 'mail.gaagdesarrolloempresarial.com',
            'port' => '26',
            'auth' => true,
            'username' => 'contacto@gaagdesarrolloempresarial.com',
            'password' => 'gaag2014'
        ));

        $mail = $smtp->send($to, $headers, $body);

        if (PEAR::isError($mail)) {
            echo('<p>' . $mail->getMessage() . '</p>');
        } else {
            echo('<p>Message successfully sent!</p>');
        }        
        
        ?>
            <script type="text/javascript" >
                close();
            </script>
        <?php
    }
         
    
?>        
