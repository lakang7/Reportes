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
    /*$formula = "((2+5+3)*3)+3";
    eval("\$var = $formula;");
    echo $var;*/
    
    require_once("funciones/funciones.php");
    $con = Conexion();
    $buscaempresa = 1;
    $buscaano = 2015;
    $buscames = 5;
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
            echo $razon["idrazonfinanciera"]." - ".$razon["nombre"]."</br>";

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
                        $val=$valor["importes1"];
                    }
                    if($buscames==2){
                        $val=$valor["importes2"];
                    }
                    if($buscames==3){
                        $val=$valor["importes3"];
                    }
                    if($buscames==4){
                        $val=$valor["importes4"];
                    }
                    if($buscames==5){
                        $val=$valor["importes5"];
                    }
                    if($buscames==6){
                        $val=$valor["importes6"];
                    }
                    if($buscames==7){
                        $val=$valor["importes7"];
                    }
                    if($buscames==8){
                        $val=$valor["importes8"];
                    }
                    if($buscames==9){
                        $val=$valor["importes9"];
                    }
                    if($buscames==10){
                        $val=$valor["importes10"];
                    }
                    if($buscames==11){
                        $val=$valor["importes11"];
                    }
                    if($buscames==12){
                        $val=$valor["importes12"];
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
        echo $categoriasVa[$i]." - ";
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
    
?>        
        
        
        
    </body>
</html>
