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
    
    
    
    $buscaempresa = 1;
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
                    $sqlAsocia = "select * from asociaclave where idempresa='".$buscaempresa."' and idclave='".$calculo["idclave"]."' ";
                    $resultAsocia=mysql_query($sqlAsocia,$con) or die(mysql_error());
                    $auxasocia=mysql_fetch_assoc($resultAsocia);
                    $sqlcuenta="select * from cuenta where codigo='".$auxasocia["codigo"]."' and idempresa='".$buscaempresa."'";;
                    $resultcuenta=mysql_query($sqlcuenta,$con) or die(mysql_error());
                    $cuenta=mysql_fetch_assoc($resultcuenta);
                    $numberelements=mysql_num_rows($resultcuenta);                    
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
                    
                    if($numberelements==0){
                        $val=1;
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
    

   
?>        
        
        
        
    </body>
</html>
