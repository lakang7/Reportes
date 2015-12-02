<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>

<?php

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


    require_once("../funciones/funciones.php");
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
            
            if($conttotal==0){
               // echo $razon["idrazonfinanciera"]." - ".$razon["nombre"]."</br>";
                
                $matematica="";
                mysql_data_seek($resutlCalculo,0);
                while ($calculo=mysql_fetch_assoc($resutlCalculo)) {
                    if($calculo["tipo"]==1){ /*Clave*/
                        $sqlAsocia = "select * from asociaclave where idempresa='".$buscaempresa."' and idclave='".$calculo["idclave"]."' ";
                        $resultAsocia=mysql_query($sqlAsocia,$con) or die(mysql_error());
                        $auxasocia=mysql_fetch_assoc($resultAsocia);
                        $sqlcuenta="select * from cuenta where codigo='".$auxasocia["codigo"]."' and idempresa='".$buscaempresa."'";
                        $resultcuenta=mysql_query($sqlcuenta,$con) or die(mysql_error());
                        $cuenta=mysql_fetch_assoc($resultcuenta);
                        $sqlvalor="select * from saldo where tipo='".$auxasocia["indice"]."' and ejercicio='".$buscaejercicio."' and idempresa='".$buscaempresa."' and idcuenta='".$cuenta["idcuenta"]."' ";
                        $resultvalor=mysql_query($sqlvalor,$con) or die(mysql_error());
                        $valor=mysql_fetch_assoc($resultvalor);
                        $val="";
                        if($buscames==1){
                            $val="(".$valor["importes1"].")";
                        }
                        if($buscames==2){
                            $val="(".$valor["importes2"].")";
                        }
                        if($buscames==3){
                            $val="(".$valor["importes3"].")";
                        }
                        if($buscames==4){
                            $val="(".$valor["importes4"].")";
                        }
                        if($buscames==5){
                            $val="(".$valor["importes5"].")";
                        }
                        if($buscames==6){
                            $val="(".$valor["importes6"].")";
                        }
                        if($buscames==7){
                            $val="(".$valor["importes7"].")";
                        }
                        if($buscames==8){
                            $val="(".$valor["importes8"].")";
                        }
                        if($buscames==9){
                            $val="(".$valor["importes9"].")";
                        }
                        if($buscames==10){
                            $val="(".$valor["importes10"].")";
                        }
                        if($buscames==11){
                            $val="(".$valor["importes11"].")";
                        }
                        if($buscames==12){
                            $val="(".$valor["importes12"].")";
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
        
       // echo "Resultados</br>";
        for($i=0;$i<$cont;$i++){
           // echo $categoriasID[$i]." - ";
        }
        //echo "</br>"; 
        $totalcategorias=0;
        $totalrazones=0;
        for($i=0;$i<$cont;$i++){
            if($categoriasVa[$i]>0){
                $totalcategorias++;
                $totalrazones+=$categoriasVa[$i];
            }
            //echo $categoriasVa[$i]." ";
        } 
                       
        for($i=0;$i<$cont;$i++){
           // echo "</br>".$razonesporca[$i];
        }     
       // echo "</br>";
        for($i=0;$i<$cont;$i++){
           // echo "</br>".$valoresporra[$i];
        } 
                
        $resultado=array();
        $resultado[0]=$razonesporca;
        $resultado[1]=$valoresporra;
        return $resultado;
 }
    
    
    
    $buscaempresa = 6;
    $buscaano = 2015;
    $buscames = 6;             
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
            echo $razon["idrazonfinanciera"]." - ".$razon["nombre"]."</br>";

            $matematica="";
            mysql_data_seek($resutlCalculo,0);
            while ($calculo=mysql_fetch_assoc($resutlCalculo)) {
                if($calculo["tipo"]==1){ /*Clave*/
                    echo "xxxxxxxxxx: ".$calculo["promedio"]."</br>";
                    $val=0;
                    $cont01=0;
                    $sqlAsocia = "select * from asociaclave where idempresa='".$buscaempresa."' and idclave='".$calculo["idclave"]."' ";
                    $resultAsocia=mysql_query($sqlAsocia,$con) or die(mysql_error());
                    $aux01=mysql_num_rows($resultAsocia);                                       
                    if($aux01==2){
                        $val="(";
                    }
                    while ($auxasocia=mysql_fetch_assoc($resultAsocia)) {
                                            
                        $sqlcuenta="select * from cuenta where codigo='".$auxasocia["codigo"]."' and idempresa='".$buscaempresa."'";;
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
                                    $val=$val."(".$valor["importes1"].")";
                                }else{
                                    $val=$val."(0)";  
                                }                                                                                                 
                            }else{                                                                                                
                                if($valor["importes1"]!="0" && $valor["importes1"]!=""){
                                    $val="(".$valor["importes1"].")";
                                }else{
                                    $val="(0)";  
                                }                                                                                                 
                            }                            
                        }
                        if($buscames==2){
                            if($aux01>1){                                
                                if($valor["importes2"]!="0" && $valor["importes2"]!=""){
                                    $val=$val."(".$valor["importes2"].")";
                                }else{
                                    $val=$val."(0)";  
                                }                                                                                                 
                            }else{                                                                                                
                                if($valor["importes2"]!="0" && $valor["importes2"]!=""){
                                    $val="(".$valor["importes2"].")";
                                }else{
                                    $val="(0)";  
                                }                                                                                                 
                            }                                                                                   
                        }
                        if($buscames==3){
                            if($aux01>1){                                
                                if($valor["importes3"]!="0" && $valor["importes3"]!=""){
                                    $val=$val."(".$valor["importes3"].")";
                                }else{
                                    $val=$val."(0)";  
                                }                                                                                                 
                            }else{                                                                                                
                                if($valor["importes3"]!="0" && $valor["importes3"]!=""){
                                    $val="(".$valor["importes3"].")";
                                }else{
                                    $val="(0)";  
                                }                                                                                                 
                            } 
                        }
                        if($buscames==4){
                            if($aux01>1){                                
                                if($valor["importes4"]!="0" && $valor["importes4"]!=""){
                                    $val=$val."(".$valor["importes4"].")";
                                }else{
                                    $val=$val."(0)";  
                                }                                                                                                 
                            }else{                                                                                                
                                if($valor["importes4"]!="0" && $valor["importes4"]!=""){
                                    $val="(".$valor["importes4"].")";
                                }else{
                                    $val="(0)";  
                                }                                                                                                 
                            } 
                        }
                        if($buscames==5){
                            if($aux01>1){                                
                                if($valor["importes5"]!="0" && $valor["importes5"]!=""){
                                    $val=$val."(".$valor["importes5"].")";
                                }else{
                                    $val=$val."(0)";  
                                }                                                                                                 
                            }else{                                                                                                
                                if($valor["importes5"]!="0" && $valor["importes5"]!=""){
                                    $val="(".$valor["importes5"].")";
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
                                    $val=$val."(".$valor["importes7"].")";
                                }else{
                                    $val=$val."(0)";  
                                }                                                                                                 
                            }else{                                                                                                
                                if($valor["importes7"]!="0" && $valor["importes7"]!=""){
                                    $val="(".$valor["importes7"].")";
                                }else{
                                    $val="(0)";  
                                }                                                                                                 
                            } 
                        }
                        if($buscames==8){
                            if($aux01>1){                                
                                if($valor["importes8"]!="0" && $valor["importes8"]!=""){
                                    $val=$val."(".$valor["importes8"].")";
                                }else{
                                    $val=$val."(0)";  
                                }                                                                                                 
                            }else{                                                                                                
                                if($valor["importes8"]!="0" && $valor["importes8"]!=""){
                                    $val="(".$valor["importes8"].")";
                                }else{
                                    $val="(0)";  
                                }                                                                                                 
                            } 
                        }
                        if($buscames==9){
                            if($aux01>1){                                
                                if($valor["importes9"]!="0" && $valor["importes9"]!=""){
                                    $val=$val."(".$valor["importes9"].")";
                                }else{
                                    $val=$val."(0)";  
                                }                                                                                                 
                            }else{                                                                                                
                                if($valor["importes9"]!="0" && $valor["importes9"]!=""){
                                    $val="(".$valor["importes9"].")";
                                }else{
                                    $val="(0)";  
                                }                                                                                                 
                            } 
                        }
                        if($buscames==10){
                            if($aux01>1){                                
                                if($valor["importes10"]!="0" && $valor["importes10"]!=""){
                                    $val=$val."(".$valor["importes10"].")";
                                }else{
                                    $val=$val."(0)";  
                                }                                                                                                 
                            }else{                                                                                                
                                if($valor["importes10"]!="0" && $valor["importes10"]!=""){
                                    $val="(".$valor["importes10"].")";
                                }else{
                                    $val="(0)";  
                                }                                                                                                 
                            } 
                        }
                        if($buscames==11){
                            if($aux01>1){                                
                                if($valor["importes11"]!="0" && $valor["importes11"]!=""){
                                    $val=$val."(".$valor["importes11"].")";
                                }else{
                                    $val=$val."(0)";  
                                }                                                                                                 
                            }else{                                                                                                
                                if($valor["importes11"]!="0" && $valor["importes11"]!=""){
                                    $val="(".$valor["importes11"].")";
                                }else{
                                    $val="(0)";  
                                }                                                                                                 
                            } 
                        }
                        if($buscames==12){
                            if($aux01>1){                                
                                if($valor["importes12"]!="0" && $valor["importes12"]!=""){
                                    $val=$val."(".$valor["importes12"].")";
                                }else{
                                    $val=$val."(0)";  
                                }                                                                                                 
                            }else{                                                                                                
                                if($valor["importes12"]!="0" && $valor["importes12"]!=""){
                                    $val="(".$valor["importes12"].")";
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
            echo "-------->".$var."<---</br>";
            

            
            echo $matematica."</br>";
            echo round($var,2)."</br></br>";            
            
            for($i=0;$i<count($categoriasID);$i++){
                if($categoriasID[$i]==$razon["idcategoriarazon"]){
                    $categoriasVa[$i]=$categoriasVa[$i]+1;
                    $razonesporca[$i]=$razonesporca[$i].$razon["idrazonfinanciera"]."-";
                    $valoresporra[$i]=$valoresporra[$i].round($var,2).";";
                }
            }            
            
        }

    }
    
    echo "Resultados</br>";
    for($i=0;$i<$cont;$i++){
        echo $categoriasID[$i]." - ";
    }
    echo "</br>"; 
    $totalcategorias=0;
    $totalrazones=0;
    for($i=0;$i<$cont;$i++){
        if($categoriasVa[$i]>0){
            $totalcategorias++;
            $totalrazones+=$categoriasVa[$i];
        }
        echo $categoriasVa[$i]." ";
    } 
    
   
    $separacion=  round(((50-$totalrazones)/$totalcategorias),0,PHP_ROUND_HALF_DOWN);
    echo "</br>El total de categorias es: ".$totalcategorias;
    echo "</br>El total de razones es: ".$totalrazones;
    echo "</br>La separación entre categorias es: ".$separacion;
    
    for($i=0;$i<$cont;$i++){
        echo "</br>".$razonesporca[$i];
    }     
    echo "</br>";
    for($i=0;$i<$cont;$i++){
        echo "</br>".$valoresporra[$i];
    }
    
    echo "</br>";
    for($i=0;$i<$totalcategorias;$i++){
        $listarazones = explode("-",$razonesporca[$i]);
        $listavalores = explode(";",$valoresporra[$i]);
        for($j=0;$j<count($listarazones);$j++){
            echo $listarazones[$j]." ";
        }
        for($k=0;$k<$separacion;$k++){
            echo "_ ";
        }
    }
    
    
    
    echo "</br>----------------------------------------</br>";
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
        echo $letras[$i]." ".$Cat["nombre"]."</br>";
        $lisaux = explode("-",$razonesporca[$i]);
        $lisval = explode(";",$valoresporra[$i]);
        for($j=0;$j<(count($lisaux)-1);$j++){
            $sqlRaz="select * from razonfinanciera where idrazonfinanciera='".$lisaux[$j]."'";
            $resutlRaz=mysql_query($sqlRaz,$con) or die(mysql_error());
            $Raz = mysql_fetch_assoc($resutlRaz);            
            echo $Raz["nombre"]." ".$lisval[$j]."</br>";
        }
    }
    
    
    $comparaano=0;
    $comparames=0;    
    if($buscames>1){
        $comparames=$buscames-1;
        $comparaano=$buscaano;
    }else if($buscames==1){
        $comparames=12;
        $comparaano=$comparaano-1;
    }    
    echo "</br>".$comparaano." ".$comparames."</br>";
    $resultado = calcula($con,$buscaempresa,$comparaano,$comparames);
    echo "</br>----------------------------------------------------</br>";
    

    $arreglo01 = $resultado[0];
    $arreglo02 = $resultado[1];
    
    for($x=0;$x<count($arreglo01);$x++){
        echo $arreglo01[$x]."</br>";
    }
    
    for($x=0;$x<count($arreglo02);$x++){
        echo $arreglo02[$x]."</br>";
    }   
    
    echo "</br>----------------------------------------</br>";
    function consultacuenta($conexion,$bempresa,$bcuenta,$bano,$bmes){
        $con=$conexion;
        $sqlEje="select * from ejercicio where idempresa='".$bempresa."' and ejercicio='".$bano."'";
        $resultEje=mysql_query($sqlEje,$con) or die(mysql_error());
        $Eje = mysql_fetch_assoc($resultEje); 
        
        $sqlCue="select * from cuenta where idempresa='".$bempresa."' and codigo='".$bcuenta."' ";
        $resutlCue=mysql_query($sqlCue,$con) or die(mysql_error());
        $Cuenta = mysql_fetch_assoc($resutlCue); 
        
        $sqlSal="select * from saldo where idempresa='".$bempresa."' and ejercicio ='".$Eje["idejercicio"]."' and idcuenta='".$Cuenta["idcuenta"]."' and tipo=3";
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
    
    function graficar($conexion){
        $con=$conexion;
        $uno=consultacuenta($con,1,"4000000",2014,6);
        $dos=consultacuenta($con,1,"4000000",2015,6);
        
        $limitemayor = max($uno);
        if(max($dos)>$limitemayor){
            $limitemayor=max($dos);
        }
        
        $limitemenor = min($uno);
        if(min($dos)<$limitemenor){
            $limitemenor=min($dos);
        }    
    
        echo round($limitemayor)."</br>";
        echo round($limitemenor)."</br>";
    
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
    
        echo "</br>El rango debe ser de :".$porango[$i];        
                
    }
    
    graficar($con);
    
    echo "</br>--------------------------------------------------------------------------------------------</br>";
    
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
    
    
    for($i=0;$i<count($NomActCir);$i++){
        echo $NomActCir[$i]." ".$SalActCir[$i]."</br>";
    }
    
    echo "------------------------------</br>";
    
    for($i=0;$i<count($NomActFij);$i++){
        echo $NomActFij[$i]." ".$SalActFij[$i]."</br>";
    }  
    
    echo "------------------------------</br>";
    
    for($i=0;$i<count($NomActDif);$i++){
        echo $NomActDif[$i]." ".$SalActDif[$i]."</br>";
    }    
    
    echo "------------------------------</br>";
    
    for($i=0;$i<count($NomPasCir);$i++){
        echo $NomPasCir[$i]." ".$SalPasCir[$i]."</br>";
    }  
    
    echo "------------------------------</br>";
    
    for($i=0;$i<count($NomPasFij);$i++){
        echo $NomPasFij[$i]." ".$SalPasFij[$i]."</br>";
    }     
    
    echo "------------------------------</br>";
    
    for($i=0;$i<count($NomCapital);$i++){
        echo $NomCapital[$i]." ".$SalCapital[$i]."</br>";
    }    
    
    echo "------------------------------</br>";
    
    for($i=0;$i<count($NomUtilidad);$i++){
        echo $NomUtilidad[$i]." ".$SalUtilidad[$i]."</br>";
    }  
    
    echo "------------------------------------------------------------------------------------------</br>";
    
    $estadoNom=array();
    $estadoPer=array();
    $estadoAcu=array();
    $indice=0;
    $estadoNom[$indice]="Ventas Netas";
    $estadoPer[$indice]=0;
    $estadoAcu[$indice]=0;
    
    $sqlEst01="select * from asociaclave where idempresa='".$buscaempresa."' and idclave=9";
    $resultEst01=mysql_query($sqlEst01,$con) or die(mysql_error());    
    while ($est01=mysql_fetch_assoc($resultEst01)) {       
        $sqlEst04="select * from cuenta where idempresa='".$buscaempresa."' and codigo='".$est01["codigo"]."' ";
        $resultEst04=mysql_query($sqlEst04,$con) or die(mysql_error());
        $est04=mysql_fetch_assoc($resultEst04);
        
        $sqlEst02="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=3 ";
        $resultEst02=mysql_query($sqlEst02,$con) or die(mysql_error());
        $est02=mysql_fetch_assoc($resultEst02);
        
        $sqlEst03="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=1 ";
        $resultEst03=mysql_query($sqlEst03,$con) or die(mysql_error());
        $est03=mysql_fetch_assoc($resultEst03); 
        
        if($buscames==1){ $estadoAcu[$indice]+=$est03["importes1"]; $estadoPer[$indice]+=$est02["importes1"];}
        if($buscames==2){ $estadoAcu[$indice]+=$est03["importes2"]; $estadoPer[$indice]+=$est02["importes2"];}
        if($buscames==3){ $estadoAcu[$indice]+=$est03["importes3"]; $estadoPer[$indice]+=$est02["importes3"];}
        if($buscames==4){ $estadoAcu[$indice]+=$est03["importes4"]; $estadoPer[$indice]+=$est02["importes4"];}
        if($buscames==5){ $estadoAcu[$indice]+=$est03["importes5"]; $estadoPer[$indice]+=$est02["importes5"];}
        if($buscames==6){ $estadoAcu[$indice]+=$est03["importes6"]; $estadoPer[$indice]+=$est02["importes6"];}
        if($buscames==7){ $estadoAcu[$indice]+=$est03["importes7"]; $estadoPer[$indice]+=$est02["importes7"];}
        if($buscames==8){ $estadoAcu[$indice]+=$est03["importes8"]; $estadoPer[$indice]+=$est02["importes8"];}
        if($buscames==9){ $estadoAcu[$indice]+=$est03["importes9"]; $estadoPer[$indice]+=$est02["importes9"];}
        if($buscames==10){ $estadoAcu[$indice]+=$est03["importes10"]; $estadoPer[$indice]+=$est02["importes10"];}
        if($buscames==11){ $estadoAcu[$indice]+=$est03["importes11"]; $estadoPer[$indice]+=$est02["importes11"];}
        if($buscames==12){ $estadoAcu[$indice]+=$est03["importes12"]; $estadoPer[$indice]+=$est02["importes12"];}        
    }
    
    $indice++;
    $estadoNom[$indice]="Costo de Ventas";
    $estadoPer[$indice]=0;
    $estadoAcu[$indice]=0;        
    
    $sqlEst01="select * from asociaclave where idempresa='".$buscaempresa."' and idclave=12";
    $resultEst01=mysql_query($sqlEst01,$con) or die(mysql_error());    
    while ($est01=mysql_fetch_assoc($resultEst01)) {       
        $sqlEst04="select * from cuenta where idempresa='".$buscaempresa."' and codigo='".$est01["codigo"]."' ";
        $resultEst04=mysql_query($sqlEst04,$con) or die(mysql_error());
        $est04=mysql_fetch_assoc($resultEst04);
        
        $sqlEst02="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=2 ";
        $resultEst02=mysql_query($sqlEst02,$con) or die(mysql_error());
        $est02=mysql_fetch_assoc($resultEst02);
        
        $sqlEst03="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=1 ";
        $resultEst03=mysql_query($sqlEst03,$con) or die(mysql_error());
        $est03=mysql_fetch_assoc($resultEst03); 
        
        if($buscames==1){ $estadoAcu[$indice]+=$est03["importes1"]; $estadoPer[$indice]+=$est02["importes1"];}
        if($buscames==2){ $estadoAcu[$indice]+=$est03["importes2"]; $estadoPer[$indice]+=$est02["importes2"];}
        if($buscames==3){ $estadoAcu[$indice]+=$est03["importes3"]; $estadoPer[$indice]+=$est02["importes3"];}
        if($buscames==4){ $estadoAcu[$indice]+=$est03["importes4"]; $estadoPer[$indice]+=$est02["importes4"];}
        if($buscames==5){ $estadoAcu[$indice]+=$est03["importes5"]; $estadoPer[$indice]+=$est02["importes5"];}
        if($buscames==6){ $estadoAcu[$indice]+=$est03["importes6"]; $estadoPer[$indice]+=$est02["importes6"];}
        if($buscames==7){ $estadoAcu[$indice]+=$est03["importes7"]; $estadoPer[$indice]+=$est02["importes7"];}
        if($buscames==8){ $estadoAcu[$indice]+=$est03["importes8"]; $estadoPer[$indice]+=$est02["importes8"];}
        if($buscames==9){ $estadoAcu[$indice]+=$est03["importes9"]; $estadoPer[$indice]+=$est02["importes9"];}
        if($buscames==10){ $estadoAcu[$indice]+=$est03["importes10"]; $estadoPer[$indice]+=$est02["importes10"];}
        if($buscames==11){ $estadoAcu[$indice]+=$est03["importes11"]; $estadoPer[$indice]+=$est02["importes11"];}
        if($buscames==12){ $estadoAcu[$indice]+=$est03["importes12"]; $estadoPer[$indice]+=$est02["importes12"];}        
    }    
    
    $indice++;
    $estadoNom[$indice]="Gastos de Ventas";
    $estadoPer[$indice]=0;
    $estadoAcu[$indice]=0;        
    
    $sqlEst01="select * from asociaclave where idempresa='".$buscaempresa."' and idclave=23";
    $resultEst01=mysql_query($sqlEst01,$con) or die(mysql_error());    
    while ($est01=mysql_fetch_assoc($resultEst01)) { 
        $sqlEst04="select * from cuenta where idempresa='".$buscaempresa."' and codigo='".$est01["codigo"]."' ";
        $resultEst04=mysql_query($sqlEst04,$con) or die(mysql_error());
        $est04=mysql_fetch_assoc($resultEst04);
        
        $sqlEst02="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=2 ";
        $resultEst02=mysql_query($sqlEst02,$con) or die(mysql_error());
        $est02=mysql_fetch_assoc($resultEst02);
        
        $sqlEst03="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=1 ";
        $resultEst03=mysql_query($sqlEst03,$con) or die(mysql_error());
        $est03=mysql_fetch_assoc($resultEst03); 
        
        if($buscames==1){ $estadoAcu[$indice]+=$est03["importes1"]; $estadoPer[$indice]+=$est02["importes1"];}
        if($buscames==2){ $estadoAcu[$indice]+=$est03["importes2"]; $estadoPer[$indice]+=$est02["importes2"];}
        if($buscames==3){ $estadoAcu[$indice]+=$est03["importes3"]; $estadoPer[$indice]+=$est02["importes3"];}
        if($buscames==4){ $estadoAcu[$indice]+=$est03["importes4"]; $estadoPer[$indice]+=$est02["importes4"];}
        if($buscames==5){ $estadoAcu[$indice]+=$est03["importes5"]; $estadoPer[$indice]+=$est02["importes5"];}
        if($buscames==6){ $estadoAcu[$indice]+=$est03["importes6"]; $estadoPer[$indice]+=$est02["importes6"];}
        if($buscames==7){ $estadoAcu[$indice]+=$est03["importes7"]; $estadoPer[$indice]+=$est02["importes7"];}
        if($buscames==8){ $estadoAcu[$indice]+=$est03["importes8"]; $estadoPer[$indice]+=$est02["importes8"];}
        if($buscames==9){ $estadoAcu[$indice]+=$est03["importes9"]; $estadoPer[$indice]+=$est02["importes9"];}
        if($buscames==10){ $estadoAcu[$indice]+=$est03["importes10"]; $estadoPer[$indice]+=$est02["importes10"];}
        if($buscames==11){ $estadoAcu[$indice]+=$est03["importes11"]; $estadoPer[$indice]+=$est02["importes11"];}
        if($buscames==12){ $estadoAcu[$indice]+=$est03["importes12"]; $estadoPer[$indice]+=$est02["importes12"];}        
    }  
    
    
    $indice++;
    $estadoNom[$indice]="Gastos de Administración";
    $estadoPer[$indice]=0;
    $estadoAcu[$indice]=0;        
    
    $sqlEst01="select * from asociaclave where idempresa='".$buscaempresa."' and idclave=19";
    $resultEst01=mysql_query($sqlEst01,$con) or die(mysql_error());    
    while ($est01=mysql_fetch_assoc($resultEst01)) { 
        $sqlEst04="select * from cuenta where idempresa='".$buscaempresa."' and codigo='".$est01["codigo"]."' ";
        $resultEst04=mysql_query($sqlEst04,$con) or die(mysql_error());
        $est04=mysql_fetch_assoc($resultEst04);
        
        $sqlEst02="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=2 ";
        $resultEst02=mysql_query($sqlEst02,$con) or die(mysql_error());
        $est02=mysql_fetch_assoc($resultEst02);
        
        $sqlEst03="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=1 ";
        $resultEst03=mysql_query($sqlEst03,$con) or die(mysql_error());
        $est03=mysql_fetch_assoc($resultEst03); 
        
        if($buscames==1){ $estadoAcu[$indice]+=$est03["importes1"]; $estadoPer[$indice]+=$est02["importes1"];}
        if($buscames==2){ $estadoAcu[$indice]+=$est03["importes2"]; $estadoPer[$indice]+=$est02["importes2"];}
        if($buscames==3){ $estadoAcu[$indice]+=$est03["importes3"]; $estadoPer[$indice]+=$est02["importes3"];}
        if($buscames==4){ $estadoAcu[$indice]+=$est03["importes4"]; $estadoPer[$indice]+=$est02["importes4"];}
        if($buscames==5){ $estadoAcu[$indice]+=$est03["importes5"]; $estadoPer[$indice]+=$est02["importes5"];}
        if($buscames==6){ $estadoAcu[$indice]+=$est03["importes6"]; $estadoPer[$indice]+=$est02["importes6"];}
        if($buscames==7){ $estadoAcu[$indice]+=$est03["importes7"]; $estadoPer[$indice]+=$est02["importes7"];}
        if($buscames==8){ $estadoAcu[$indice]+=$est03["importes8"]; $estadoPer[$indice]+=$est02["importes8"];}
        if($buscames==9){ $estadoAcu[$indice]+=$est03["importes9"]; $estadoPer[$indice]+=$est02["importes9"];}
        if($buscames==10){ $estadoAcu[$indice]+=$est03["importes10"]; $estadoPer[$indice]+=$est02["importes10"];}
        if($buscames==11){ $estadoAcu[$indice]+=$est03["importes11"]; $estadoPer[$indice]+=$est02["importes11"];}
        if($buscames==12){ $estadoAcu[$indice]+=$est03["importes12"]; $estadoPer[$indice]+=$est02["importes12"];}        
    }     
    
    
    $indice++;
    $estadoNom[$indice]="Otros ingresos y gastos netos";
    $estadoPer[$indice]=0;
    $estadoAcu[$indice]=0;        
    
    $sqlEst01="select * from asociaclave where idempresa='".$buscaempresa."' and idclave=17";
    $resultEst01=mysql_query($sqlEst01,$con) or die(mysql_error());    
    while ($est01=mysql_fetch_assoc($resultEst01)) { 
        $sqlEst04="select * from cuenta where idempresa='".$buscaempresa."' and codigo='".$est01["codigo"]."' ";
        $resultEst04=mysql_query($sqlEst04,$con) or die(mysql_error());
        $est04=mysql_fetch_assoc($resultEst04);
        
        $sqlEst02="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=2 ";
        $resultEst02=mysql_query($sqlEst02,$con) or die(mysql_error());
        $est02=mysql_fetch_assoc($resultEst02);
        
        $sqlEst03="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=1 ";
        $resultEst03=mysql_query($sqlEst03,$con) or die(mysql_error());
        $est03=mysql_fetch_assoc($resultEst03); 
        
        if($buscames==1){ $estadoAcu[$indice]+=$est03["importes1"]; $estadoPer[$indice]+=$est02["importes1"];}
        if($buscames==2){ $estadoAcu[$indice]+=$est03["importes2"]; $estadoPer[$indice]+=$est02["importes2"];}
        if($buscames==3){ $estadoAcu[$indice]+=$est03["importes3"]; $estadoPer[$indice]+=$est02["importes3"];}
        if($buscames==4){ $estadoAcu[$indice]+=$est03["importes4"]; $estadoPer[$indice]+=$est02["importes4"];}
        if($buscames==5){ $estadoAcu[$indice]+=$est03["importes5"]; $estadoPer[$indice]+=$est02["importes5"];}
        if($buscames==6){ $estadoAcu[$indice]+=$est03["importes6"]; $estadoPer[$indice]+=$est02["importes6"];}
        if($buscames==7){ $estadoAcu[$indice]+=$est03["importes7"]; $estadoPer[$indice]+=$est02["importes7"];}
        if($buscames==8){ $estadoAcu[$indice]+=$est03["importes8"]; $estadoPer[$indice]+=$est02["importes8"];}
        if($buscames==9){ $estadoAcu[$indice]+=$est03["importes9"]; $estadoPer[$indice]+=$est02["importes9"];}
        if($buscames==10){ $estadoAcu[$indice]+=$est03["importes10"]; $estadoPer[$indice]+=$est02["importes10"];}
        if($buscames==11){ $estadoAcu[$indice]+=$est03["importes11"]; $estadoPer[$indice]+=$est02["importes11"];}
        if($buscames==12){ $estadoAcu[$indice]+=$est03["importes12"]; $estadoPer[$indice]+=$est02["importes12"];}        
    }     
    
    $indice++;
    $estadoNom[$indice]="Resultado integral de financiamiento";
    $estadoPer[$indice]=0;
    $estadoAcu[$indice]=0;        
    
    $sqlEst01="select * from asociaclave where idempresa='".$buscaempresa."' and idclave=27";
    $resultEst01=mysql_query($sqlEst01,$con) or die(mysql_error());    
    while ($est01=mysql_fetch_assoc($resultEst01)) { 
        $sqlEst04="select * from cuenta where idempresa='".$buscaempresa."' and codigo='".$est01["codigo"]."' ";
        $resultEst04=mysql_query($sqlEst04,$con) or die(mysql_error());
        $est04=mysql_fetch_assoc($resultEst04);
        
        $sqlEst02="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=2 ";
        $resultEst02=mysql_query($sqlEst02,$con) or die(mysql_error());
        $est02=mysql_fetch_assoc($resultEst02);
        
        $sqlEst05="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=3 ";
        $resultEst05=mysql_query($sqlEst05,$con) or die(mysql_error());
        $est05=mysql_fetch_assoc($resultEst05);        
        
        $sqlEst03="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=1 ";
        $resultEst03=mysql_query($sqlEst03,$con) or die(mysql_error());
        $est03=mysql_fetch_assoc($resultEst03); 
        
        if($buscames==1){ $estadoAcu[$indice]+=$est03["importes1"]; $estadoPer[$indice]+=($est05["importes1"]-$est02["importes1"]);}
        if($buscames==2){ $estadoAcu[$indice]+=$est03["importes2"]; $estadoPer[$indice]+=($est05["importes2"]-$est02["importes2"]);}
        if($buscames==3){ $estadoAcu[$indice]+=$est03["importes3"]; $estadoPer[$indice]+=($est05["importes3"]-$est02["importes3"]);}
        if($buscames==4){ $estadoAcu[$indice]+=$est03["importes4"]; $estadoPer[$indice]+=($est05["importes4"]-$est02["importes4"]);}
        if($buscames==5){ $estadoAcu[$indice]+=$est03["importes5"]; $estadoPer[$indice]+=($est05["importes5"]-$est02["importes5"]);}
        if($buscames==6){ $estadoAcu[$indice]+=$est03["importes6"]; $estadoPer[$indice]+=($est05["importes6"]-$est02["importes6"]);}
        if($buscames==7){ $estadoAcu[$indice]+=$est03["importes7"]; $estadoPer[$indice]+=($est05["importes7"]-$est02["importes7"]);}
        if($buscames==8){ $estadoAcu[$indice]+=$est03["importes8"]; $estadoPer[$indice]+=($est05["importes8"]-$est02["importes8"]);}
        if($buscames==9){ $estadoAcu[$indice]+=$est03["importes9"]; $estadoPer[$indice]+=($est05["importes9"]-$est02["importes9"]);}
        if($buscames==10){ $estadoAcu[$indice]+=$est03["importes10"]; $estadoPer[$indice]+=($est05["importes10"]-$est02["importes10"]);}
        if($buscames==11){ $estadoAcu[$indice]+=$est03["importes11"]; $estadoPer[$indice]+=($est05["importes11"]-$est02["importes11"]);}
        if($buscames==12){ $estadoAcu[$indice]+=$est03["importes12"]; $estadoPer[$indice]+=($est05["importes12"]-$est02["importes12"]);}        
    }      
    
    $indice++;
    $estadoNom[$indice]="Participación en subsidiarias y asociadas";
    $estadoPer[$indice]=0;
    $estadoAcu[$indice]=0;        
    
    $sqlEst01="select * from asociaclave where idempresa='".$buscaempresa."' and idclave=24";
    $resultEst01=mysql_query($sqlEst01,$con) or die(mysql_error());    
    while ($est01=mysql_fetch_assoc($resultEst01)) { 
        $sqlEst04="select * from cuenta where idempresa='".$buscaempresa."' and codigo='".$est01["codigo"]."' ";
        $resultEst04=mysql_query($sqlEst04,$con) or die(mysql_error());
        $est04=mysql_fetch_assoc($resultEst04);
        
        $sqlEst02="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=2 ";
        $resultEst02=mysql_query($sqlEst02,$con) or die(mysql_error());
        $est02=mysql_fetch_assoc($resultEst02);
        
        $sqlEst05="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=3 ";
        $resultEst05=mysql_query($sqlEst05,$con) or die(mysql_error());
        $est05=mysql_fetch_assoc($resultEst05);        
        
        $sqlEst03="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=1 ";
        $resultEst03=mysql_query($sqlEst03,$con) or die(mysql_error());
        $est03=mysql_fetch_assoc($resultEst03); 
        
        if($buscames==1){ $estadoAcu[$indice]+=$est03["importes1"]; $estadoPer[$indice]+=$est02["importes1"];}
        if($buscames==2){ $estadoAcu[$indice]+=$est03["importes2"]; $estadoPer[$indice]+=$est02["importes2"];}
        if($buscames==3){ $estadoAcu[$indice]+=$est03["importes3"]; $estadoPer[$indice]+=$est02["importes3"];}
        if($buscames==4){ $estadoAcu[$indice]+=$est03["importes4"]; $estadoPer[$indice]+=$est02["importes4"];}
        if($buscames==5){ $estadoAcu[$indice]+=$est03["importes5"]; $estadoPer[$indice]+=$est02["importes5"];}
        if($buscames==6){ $estadoAcu[$indice]+=$est03["importes6"]; $estadoPer[$indice]+=$est02["importes6"];}
        if($buscames==7){ $estadoAcu[$indice]+=$est03["importes7"]; $estadoPer[$indice]+=$est02["importes7"];}
        if($buscames==8){ $estadoAcu[$indice]+=$est03["importes8"]; $estadoPer[$indice]+=$est02["importes8"];}
        if($buscames==9){ $estadoAcu[$indice]+=$est03["importes9"]; $estadoPer[$indice]+=$est02["importes9"];}
        if($buscames==10){ $estadoAcu[$indice]+=$est03["importes10"]; $estadoPer[$indice]+=$est02["importes10"];}
        if($buscames==11){ $estadoAcu[$indice]+=$est03["importes11"]; $estadoPer[$indice]+=$est02["importes11"];}
        if($buscames==12){ $estadoAcu[$indice]+=$est03["importes12"]; $estadoPer[$indice]+=$est02["importes12"];}         
    }  
    
    $indice++;
    $estadoNom[$indice]="Partidas no ordinarias";
    $estadoPer[$indice]=0;
    $estadoAcu[$indice]=0;        
    
    $sqlEst01="select * from asociaclave where idempresa='".$buscaempresa."' and idclave=25";
    $resultEst01=mysql_query($sqlEst01,$con) or die(mysql_error());    
    while ($est01=mysql_fetch_assoc($resultEst01)) { 
        $sqlEst04="select * from cuenta where idempresa='".$buscaempresa."' and codigo='".$est01["codigo"]."' ";
        $resultEst04=mysql_query($sqlEst04,$con) or die(mysql_error());
        $est04=mysql_fetch_assoc($resultEst04);
        
        $sqlEst02="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=2 ";
        $resultEst02=mysql_query($sqlEst02,$con) or die(mysql_error());
        $est02=mysql_fetch_assoc($resultEst02);
        
        $sqlEst05="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=3 ";
        $resultEst05=mysql_query($sqlEst05,$con) or die(mysql_error());
        $est05=mysql_fetch_assoc($resultEst05);        
        
        $sqlEst03="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=1 ";
        $resultEst03=mysql_query($sqlEst03,$con) or die(mysql_error());
        $est03=mysql_fetch_assoc($resultEst03); 
        
        if($buscames==1){ $estadoAcu[$indice]+=$est03["importes1"]; $estadoPer[$indice]+=$est02["importes1"];}
        if($buscames==2){ $estadoAcu[$indice]+=$est03["importes2"]; $estadoPer[$indice]+=$est02["importes2"];}
        if($buscames==3){ $estadoAcu[$indice]+=$est03["importes3"]; $estadoPer[$indice]+=$est02["importes3"];}
        if($buscames==4){ $estadoAcu[$indice]+=$est03["importes4"]; $estadoPer[$indice]+=$est02["importes4"];}
        if($buscames==5){ $estadoAcu[$indice]+=$est03["importes5"]; $estadoPer[$indice]+=$est02["importes5"];}
        if($buscames==6){ $estadoAcu[$indice]+=$est03["importes6"]; $estadoPer[$indice]+=$est02["importes6"];}
        if($buscames==7){ $estadoAcu[$indice]+=$est03["importes7"]; $estadoPer[$indice]+=$est02["importes7"];}
        if($buscames==8){ $estadoAcu[$indice]+=$est03["importes8"]; $estadoPer[$indice]+=$est02["importes8"];}
        if($buscames==9){ $estadoAcu[$indice]+=$est03["importes9"]; $estadoPer[$indice]+=$est02["importes9"];}
        if($buscames==10){ $estadoAcu[$indice]+=$est03["importes10"]; $estadoPer[$indice]+=$est02["importes10"];}
        if($buscames==11){ $estadoAcu[$indice]+=$est03["importes11"]; $estadoPer[$indice]+=$est02["importes11"];}
        if($buscames==12){ $estadoAcu[$indice]+=$est03["importes12"]; $estadoPer[$indice]+=$est02["importes12"];}         
    }    
    
    $indice++;
    $estadoNom[$indice]="Impuestos a la utilidad";
    $estadoPer[$indice]=0;
    $estadoAcu[$indice]=0;        
    
    $sqlEst01="select * from asociaclave where idempresa='".$buscaempresa."' and idclave=22";
    $resultEst01=mysql_query($sqlEst01,$con) or die(mysql_error());    
    while ($est01=mysql_fetch_assoc($resultEst01)) { 
        $sqlEst04="select * from cuenta where idempresa='".$buscaempresa."' and codigo='".$est01["codigo"]."' ";
        $resultEst04=mysql_query($sqlEst04,$con) or die(mysql_error());
        $est04=mysql_fetch_assoc($resultEst04);
        
        $sqlEst02="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=2 ";
        $resultEst02=mysql_query($sqlEst02,$con) or die(mysql_error());
        $est02=mysql_fetch_assoc($resultEst02);
        
        $sqlEst05="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=3 ";
        $resultEst05=mysql_query($sqlEst05,$con) or die(mysql_error());
        $est05=mysql_fetch_assoc($resultEst05);        
        
        $sqlEst03="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=1 ";
        $resultEst03=mysql_query($sqlEst03,$con) or die(mysql_error());
        $est03=mysql_fetch_assoc($resultEst03); 
        
        if($buscames==1){ $estadoAcu[$indice]+=$est03["importes1"]; $estadoPer[$indice]+=$est02["importes1"];}
        if($buscames==2){ $estadoAcu[$indice]+=$est03["importes2"]; $estadoPer[$indice]+=$est02["importes2"];}
        if($buscames==3){ $estadoAcu[$indice]+=$est03["importes3"]; $estadoPer[$indice]+=$est02["importes3"];}
        if($buscames==4){ $estadoAcu[$indice]+=$est03["importes4"]; $estadoPer[$indice]+=$est02["importes4"];}
        if($buscames==5){ $estadoAcu[$indice]+=$est03["importes5"]; $estadoPer[$indice]+=$est02["importes5"];}
        if($buscames==6){ $estadoAcu[$indice]+=$est03["importes6"]; $estadoPer[$indice]+=$est02["importes6"];}
        if($buscames==7){ $estadoAcu[$indice]+=$est03["importes7"]; $estadoPer[$indice]+=$est02["importes7"];}
        if($buscames==8){ $estadoAcu[$indice]+=$est03["importes8"]; $estadoPer[$indice]+=$est02["importes8"];}
        if($buscames==9){ $estadoAcu[$indice]+=$est03["importes9"]; $estadoPer[$indice]+=$est02["importes9"];}
        if($buscames==10){ $estadoAcu[$indice]+=$est03["importes10"]; $estadoPer[$indice]+=$est02["importes10"];}
        if($buscames==11){ $estadoAcu[$indice]+=$est03["importes11"]; $estadoPer[$indice]+=$est02["importes11"];}
        if($buscames==12){ $estadoAcu[$indice]+=$est03["importes12"]; $estadoPer[$indice]+=$est02["importes12"];}         
    }    
    
    $indice++;
    $estadoNom[$indice]="Operaciones Discontinuadas";
    $estadoPer[$indice]=0;
    $estadoAcu[$indice]=0;        
    
    $sqlEst01="select * from asociaclave where idempresa='".$buscaempresa."' and idclave=26";
    $resultEst01=mysql_query($sqlEst01,$con) or die(mysql_error());    
    while ($est01=mysql_fetch_assoc($resultEst01)) { 
        $sqlEst04="select * from cuenta where idempresa='".$buscaempresa."' and codigo='".$est01["codigo"]."' ";
        $resultEst04=mysql_query($sqlEst04,$con) or die(mysql_error());
        $est04=mysql_fetch_assoc($resultEst04);
        
        $sqlEst02="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=2 ";
        $resultEst02=mysql_query($sqlEst02,$con) or die(mysql_error());
        $est02=mysql_fetch_assoc($resultEst02);
        
        $sqlEst05="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=3 ";
        $resultEst05=mysql_query($sqlEst05,$con) or die(mysql_error());
        $est05=mysql_fetch_assoc($resultEst05);        
        
        $sqlEst03="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=1 ";
        $resultEst03=mysql_query($sqlEst03,$con) or die(mysql_error());
        $est03=mysql_fetch_assoc($resultEst03); 
        
        if($buscames==1){ $estadoAcu[$indice]+=$est03["importes1"]; $estadoPer[$indice]+=$est02["importes1"];}
        if($buscames==2){ $estadoAcu[$indice]+=$est03["importes2"]; $estadoPer[$indice]+=$est02["importes2"];}
        if($buscames==3){ $estadoAcu[$indice]+=$est03["importes3"]; $estadoPer[$indice]+=$est02["importes3"];}
        if($buscames==4){ $estadoAcu[$indice]+=$est03["importes4"]; $estadoPer[$indice]+=$est02["importes4"];}
        if($buscames==5){ $estadoAcu[$indice]+=$est03["importes5"]; $estadoPer[$indice]+=$est02["importes5"];}
        if($buscames==6){ $estadoAcu[$indice]+=$est03["importes6"]; $estadoPer[$indice]+=$est02["importes6"];}
        if($buscames==7){ $estadoAcu[$indice]+=$est03["importes7"]; $estadoPer[$indice]+=$est02["importes7"];}
        if($buscames==8){ $estadoAcu[$indice]+=$est03["importes8"]; $estadoPer[$indice]+=$est02["importes8"];}
        if($buscames==9){ $estadoAcu[$indice]+=$est03["importes9"]; $estadoPer[$indice]+=$est02["importes9"];}
        if($buscames==10){ $estadoAcu[$indice]+=$est03["importes10"]; $estadoPer[$indice]+=$est02["importes10"];}
        if($buscames==11){ $estadoAcu[$indice]+=$est03["importes11"]; $estadoPer[$indice]+=$est02["importes11"];}
        if($buscames==12){ $estadoAcu[$indice]+=$est03["importes12"]; $estadoPer[$indice]+=$est02["importes12"];}         
    }     
    
    for($i=0;$i<count($estadoNom);$i++){
        echo $estadoNom[$i]." -- ".$estadoPer[$i]." -- ".$estadoAcu[$i]."</br>";
    }    
    
    
?>        
        
        
        
    </body>
</html>
