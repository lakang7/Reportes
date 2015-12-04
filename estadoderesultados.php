<?php
    require_once('recursos/tcpdf/tcpdf.php');
    require_once("funciones/funciones.php");
    
    $con = Conexion();
    $buscaempresa = $_GET["empresa"];
    $buscaano = $_GET["anno"];
    
    $buscaejercicio = "";        
    
    $sqlEmpresa="select * from empresa where idempresa='".$buscaempresa."'";
    $resutlEmpresa=mysql_query($sqlEmpresa,$con) or die(mysql_error());
    $Empresa=mysql_fetch_assoc($resutlEmpresa);
    
    $sqlEjercicio="select * from ejercicio where idempresa='".$buscaempresa."' and ejercicio='".$buscaano."';";
    $resutlEjercicio=mysql_query($sqlEjercicio,$con) or die(mysql_error());
    while ($ejercicio=mysql_fetch_assoc($resutlEjercicio)) {
        $buscaejercicio = $ejercicio["idejercicio"];
    }    
    
    $estadoNom=array();
    $estadoPer1=array();
    $estadoPer2=array();
    $estadoPer3=array();
    $estadoPer4=array();
    $estadoPer5=array();
    $estadoPer6=array();
    $estadoPer7=array();
    $estadoPer8=array();
    $estadoPer9=array();
    $estadoPer10=array();
    $estadoPer11=array();
    $estadoPer12=array();
    
    $indice=0;
    
    $estadoNom[$indice]="Ventas Netas"; 
    
    $estadoPer1[$indice]=0;
    $estadoPer2[$indice]=0;
    $estadoPer3[$indice]=0;
    $estadoPer4[$indice]=0;
    $estadoPer5[$indice]=0;
    $estadoPer6[$indice]=0;
    $estadoPer7[$indice]=0;
    $estadoPer8[$indice]=0;
    $estadoPer9[$indice]=0;
    $estadoPer10[$indice]=0;
    $estadoPer11[$indice]=0;
    $estadoPer12[$indice]=0;
               
    $sqlEst01="select * from asociaclave where idempresa='".$buscaempresa."' and idclave=9";
    $resultEst01=mysql_query($sqlEst01,$con) or die(mysql_error()); 
    
    while ($est01=mysql_fetch_assoc($resultEst01)) {  
        $sqlEst04="select * from cuenta where idempresa='".$buscaempresa."' and codigo='".$est01["codigo"]."' ";
        $resultEst04=mysql_query($sqlEst04,$con) or die(mysql_error());
        $est04=mysql_fetch_assoc($resultEst04);
        
        $sqlEst02="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=3 ";
        $resultEst02=mysql_query($sqlEst02,$con) or die(mysql_error());
        $est02=mysql_fetch_assoc($resultEst02); 
        
        $estadoPer1[$indice]+=$est02["importes1"];   
        $estadoPer2[$indice]+=$est02["importes2"]; 
        $estadoPer3[$indice]+=$est02["importes3"]; 
        $estadoPer4[$indice]+=$est02["importes4"];
        $estadoPer5[$indice]+=$est02["importes5"];
        $estadoPer6[$indice]+=$est02["importes6"]; 
        $estadoPer7[$indice]+=$est02["importes7"];
        $estadoPer8[$indice]+=$est02["importes8"]; 
        $estadoPer9[$indice]+=$est02["importes9"]; 
        $estadoPer10[$indice]+=$est02["importes10"];
        $estadoPer11[$indice]+=$est02["importes11"];
        $estadoPer12[$indice]+=$est02["importes12"];                
    }    

    $indice++;
    
    $estadoNom[$indice]="Costo de Ventas"; 
    
    $estadoPer1[$indice]=0;
    $estadoPer2[$indice]=0;
    $estadoPer3[$indice]=0;
    $estadoPer4[$indice]=0;
    $estadoPer5[$indice]=0;
    $estadoPer6[$indice]=0;
    $estadoPer7[$indice]=0;
    $estadoPer8[$indice]=0;
    $estadoPer9[$indice]=0;
    $estadoPer10[$indice]=0;
    $estadoPer11[$indice]=0;
    $estadoPer12[$indice]=0;
               
    $sqlEst01="select * from asociaclave where idempresa='".$buscaempresa."' and idclave=12";
    $resultEst01=mysql_query($sqlEst01,$con) or die(mysql_error()); 
    
    while ($est01=mysql_fetch_assoc($resultEst01)) {  
        $sqlEst04="select * from cuenta where idempresa='".$buscaempresa."' and codigo='".$est01["codigo"]."' ";
        $resultEst04=mysql_query($sqlEst04,$con) or die(mysql_error());
        $est04=mysql_fetch_assoc($resultEst04);
        
        $sqlEst02="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=2 ";
        $resultEst02=mysql_query($sqlEst02,$con) or die(mysql_error());
        $est02=mysql_fetch_assoc($resultEst02);        
        
        $estadoPer1[$indice]+=$est02["importes1"];   
        $estadoPer2[$indice]+=$est02["importes2"]; 
        $estadoPer3[$indice]+=$est02["importes3"]; 
        $estadoPer4[$indice]+=$est02["importes4"];
        $estadoPer5[$indice]+=$est02["importes5"];
        $estadoPer6[$indice]+=$est02["importes6"]; 
        $estadoPer7[$indice]+=$est02["importes7"];
        $estadoPer8[$indice]+=$est02["importes8"]; 
        $estadoPer9[$indice]+=$est02["importes9"]; 
        $estadoPer10[$indice]+=$est02["importes10"];
        $estadoPer11[$indice]+=$est02["importes11"];
        $estadoPer12[$indice]+=$est02["importes12"];                
    }    

    $indice++;    
    
    $estadoNom[$indice]="Descuento Sobre Compras"; 
    
    $estadoPer1[$indice]=0;
    $estadoPer2[$indice]=0;
    $estadoPer3[$indice]=0;
    $estadoPer4[$indice]=0;
    $estadoPer5[$indice]=0;
    $estadoPer6[$indice]=0;
    $estadoPer7[$indice]=0;
    $estadoPer8[$indice]=0;
    $estadoPer9[$indice]=0;
    $estadoPer10[$indice]=0;
    $estadoPer11[$indice]=0;
    $estadoPer12[$indice]=0;
               
    $sqlEst01="select * from asociaclave where idempresa='".$buscaempresa."' and idclave=18";
    $resultEst01=mysql_query($sqlEst01,$con) or die(mysql_error()); 
    
    while ($est01=mysql_fetch_assoc($resultEst01)) {  
        $sqlEst04="select * from cuenta where idempresa='".$buscaempresa."' and codigo='".$est01["codigo"]."' ";
        $resultEst04=mysql_query($sqlEst04,$con) or die(mysql_error());
        $est04=mysql_fetch_assoc($resultEst04);
        
        $sqlEst02="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=2 ";
        $resultEst02=mysql_query($sqlEst02,$con) or die(mysql_error());
        $est02=mysql_fetch_assoc($resultEst02);
        
        $estadoPer1[$indice]+=$est02["importes1"];   
        $estadoPer2[$indice]+=$est02["importes2"]; 
        $estadoPer3[$indice]+=$est02["importes3"]; 
        $estadoPer4[$indice]+=$est02["importes4"];
        $estadoPer5[$indice]+=$est02["importes5"];
        $estadoPer6[$indice]+=$est02["importes6"]; 
        $estadoPer7[$indice]+=$est02["importes7"];
        $estadoPer8[$indice]+=$est02["importes8"]; 
        $estadoPer9[$indice]+=$est02["importes9"]; 
        $estadoPer10[$indice]+=$est02["importes10"];
        $estadoPer11[$indice]+=$est02["importes11"];
        $estadoPer12[$indice]+=$est02["importes12"];                
    }
    
    $indice++;
    
    $estadoNom[$indice]="Gastos de Ventas"; 
    
    $estadoPer1[$indice]=0;
    $estadoPer2[$indice]=0;
    $estadoPer3[$indice]=0;
    $estadoPer4[$indice]=0;
    $estadoPer5[$indice]=0;
    $estadoPer6[$indice]=0;
    $estadoPer7[$indice]=0;
    $estadoPer8[$indice]=0;
    $estadoPer9[$indice]=0;
    $estadoPer10[$indice]=0;
    $estadoPer11[$indice]=0;
    $estadoPer12[$indice]=0;
               
    $sqlEst01="select * from asociaclave where idempresa='".$buscaempresa."' and idclave=23";
    $resultEst01=mysql_query($sqlEst01,$con) or die(mysql_error()); 
    
    while ($est01=mysql_fetch_assoc($resultEst01)) {  
        $sqlEst04="select * from cuenta where idempresa='".$buscaempresa."' and codigo='".$est01["codigo"]."' ";
        $resultEst04=mysql_query($sqlEst04,$con) or die(mysql_error());
        $est04=mysql_fetch_assoc($resultEst04);
        
        $sqlEst02="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=2 ";
        $resultEst02=mysql_query($sqlEst02,$con) or die(mysql_error());
        $est02=mysql_fetch_assoc($resultEst02);        
        
        $estadoPer1[$indice]+=$est02["importes1"];   
        $estadoPer2[$indice]+=$est02["importes2"]; 
        $estadoPer3[$indice]+=$est02["importes3"]; 
        $estadoPer4[$indice]+=$est02["importes4"];
        $estadoPer5[$indice]+=$est02["importes5"];
        $estadoPer6[$indice]+=$est02["importes6"]; 
        $estadoPer7[$indice]+=$est02["importes7"];
        $estadoPer8[$indice]+=$est02["importes8"]; 
        $estadoPer9[$indice]+=$est02["importes9"]; 
        $estadoPer10[$indice]+=$est02["importes10"];
        $estadoPer11[$indice]+=$est02["importes11"];
        $estadoPer12[$indice]+=$est02["importes12"];                
    }    

    $indice++;   
    
    $estadoNom[$indice]="Gastos de Administracion"; 
    
    $estadoPer1[$indice]=0;
    $estadoPer2[$indice]=0;
    $estadoPer3[$indice]=0;
    $estadoPer4[$indice]=0;
    $estadoPer5[$indice]=0;
    $estadoPer6[$indice]=0;
    $estadoPer7[$indice]=0;
    $estadoPer8[$indice]=0;
    $estadoPer9[$indice]=0;
    $estadoPer10[$indice]=0;
    $estadoPer11[$indice]=0;
    $estadoPer12[$indice]=0;
               
    $sqlEst01="select * from asociaclave where idempresa='".$buscaempresa."' and idclave=19";
    $resultEst01=mysql_query($sqlEst01,$con) or die(mysql_error()); 
    
    while ($est01=mysql_fetch_assoc($resultEst01)) {  
        $sqlEst04="select * from cuenta where idempresa='".$buscaempresa."' and codigo='".$est01["codigo"]."' ";
        $resultEst04=mysql_query($sqlEst04,$con) or die(mysql_error());
        $est04=mysql_fetch_assoc($resultEst04);
        
        $sqlEst02="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=2 ";
        $resultEst02=mysql_query($sqlEst02,$con) or die(mysql_error());
        $est02=mysql_fetch_assoc($resultEst02);        
        
        $estadoPer1[$indice]+=$est02["importes1"];   
        $estadoPer2[$indice]+=$est02["importes2"]; 
        $estadoPer3[$indice]+=$est02["importes3"]; 
        $estadoPer4[$indice]+=$est02["importes4"];
        $estadoPer5[$indice]+=$est02["importes5"];
        $estadoPer6[$indice]+=$est02["importes6"]; 
        $estadoPer7[$indice]+=$est02["importes7"];
        $estadoPer8[$indice]+=$est02["importes8"]; 
        $estadoPer9[$indice]+=$est02["importes9"]; 
        $estadoPer10[$indice]+=$est02["importes10"];
        $estadoPer11[$indice]+=$est02["importes11"];
        $estadoPer12[$indice]+=$est02["importes12"];                
    }
    
    $indice++;
    
    $estadoNom[$indice]="Otros Ingresos y Gastos Netos"; 
    
    $estadoPer1[$indice]=0;
    $estadoPer2[$indice]=0;
    $estadoPer3[$indice]=0;
    $estadoPer4[$indice]=0;
    $estadoPer5[$indice]=0;
    $estadoPer6[$indice]=0;
    $estadoPer7[$indice]=0;
    $estadoPer8[$indice]=0;
    $estadoPer9[$indice]=0;
    $estadoPer10[$indice]=0;
    $estadoPer11[$indice]=0;
    $estadoPer12[$indice]=0;
               
    $sqlEst01="select * from asociaclave where idempresa='".$buscaempresa."' and idclave=17";
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
        
        $estadoPer1[$indice]+=($est02["importes1"]-$est05["importes1"]);   
        $estadoPer2[$indice]+=($est02["importes2"]-$est05["importes2"]);
        $estadoPer3[$indice]+=($est02["importes3"]-$est05["importes3"]); 
        $estadoPer4[$indice]+=($est02["importes4"]-$est05["importes4"]);
        $estadoPer5[$indice]+=($est02["importes5"]-$est05["importes5"]);
        $estadoPer6[$indice]+=($est02["importes6"]-$est05["importes6"]); 
        $estadoPer7[$indice]+=($est02["importes7"]-$est05["importes7"]);
        $estadoPer8[$indice]+=($est02["importes8"]-$est05["importes8"]); 
        $estadoPer9[$indice]+=($est02["importes9"]-$est05["importes9"]); 
        $estadoPer10[$indice]+=($est02["importes10"]-$est05["importes10"]);
        $estadoPer11[$indice]+=($est02["importes11"]-$est05["importes11"]);
        $estadoPer12[$indice]+=($est02["importes12"]-$est05["importes12"]);                
    }
    
    $indice++;
    
    $estadoNom[$indice]="Resultado Integral de Financiamiento"; 
    
    $estadoPer1[$indice]=0;
    $estadoPer2[$indice]=0;
    $estadoPer3[$indice]=0;
    $estadoPer4[$indice]=0;
    $estadoPer5[$indice]=0;
    $estadoPer6[$indice]=0;
    $estadoPer7[$indice]=0;
    $estadoPer8[$indice]=0;
    $estadoPer9[$indice]=0;
    $estadoPer10[$indice]=0;
    $estadoPer11[$indice]=0;
    $estadoPer12[$indice]=0;
               
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
        
        $estadoPer1[$indice]+=($est02["importes1"]-$est05["importes1"]);   
        $estadoPer2[$indice]+=($est02["importes2"]-$est05["importes2"]);
        $estadoPer3[$indice]+=($est02["importes3"]-$est05["importes3"]); 
        $estadoPer4[$indice]+=($est02["importes4"]-$est05["importes4"]);
        $estadoPer5[$indice]+=($est02["importes5"]-$est05["importes5"]);
        $estadoPer6[$indice]+=($est02["importes6"]-$est05["importes6"]); 
        $estadoPer7[$indice]+=($est02["importes7"]-$est05["importes7"]);
        $estadoPer8[$indice]+=($est02["importes8"]-$est05["importes8"]); 
        $estadoPer9[$indice]+=($est02["importes9"]-$est05["importes9"]); 
        $estadoPer10[$indice]+=($est02["importes10"]-$est05["importes10"]);
        $estadoPer11[$indice]+=($est02["importes11"]-$est05["importes11"]);
        $estadoPer12[$indice]+=($est02["importes12"]-$est05["importes12"]);                
    }
    
    $indice++;   
    
    $estadoNom[$indice]="Participación en Subsidiarias y Asociadas"; 
    
    $estadoPer1[$indice]=0;
    $estadoPer2[$indice]=0;
    $estadoPer3[$indice]=0;
    $estadoPer4[$indice]=0;
    $estadoPer5[$indice]=0;
    $estadoPer6[$indice]=0;
    $estadoPer7[$indice]=0;
    $estadoPer8[$indice]=0;
    $estadoPer9[$indice]=0;
    $estadoPer10[$indice]=0;
    $estadoPer11[$indice]=0;
    $estadoPer12[$indice]=0;
               
    $sqlEst01="select * from asociaclave where idempresa='".$buscaempresa."' and idclave=24";
    $resultEst01=mysql_query($sqlEst01,$con) or die(mysql_error()); 
    
    while ($est01=mysql_fetch_assoc($resultEst01)) {  
        $sqlEst04="select * from cuenta where idempresa='".$buscaempresa."' and codigo='".$est01["codigo"]."' ";
        $resultEst04=mysql_query($sqlEst04,$con) or die(mysql_error());
        $est04=mysql_fetch_assoc($resultEst04);
        
        $sqlEst02="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=2 ";
        $resultEst02=mysql_query($sqlEst02,$con) or die(mysql_error());
        $est02=mysql_fetch_assoc($resultEst02);        
        
        $estadoPer1[$indice]+=$est02["importes1"];   
        $estadoPer2[$indice]+=$est02["importes2"]; 
        $estadoPer3[$indice]+=$est02["importes3"]; 
        $estadoPer4[$indice]+=$est02["importes4"];
        $estadoPer5[$indice]+=$est02["importes5"];
        $estadoPer6[$indice]+=$est02["importes6"]; 
        $estadoPer7[$indice]+=$est02["importes7"];
        $estadoPer8[$indice]+=$est02["importes8"]; 
        $estadoPer9[$indice]+=$est02["importes9"]; 
        $estadoPer10[$indice]+=$est02["importes10"];
        $estadoPer11[$indice]+=$est02["importes11"];
        $estadoPer12[$indice]+=$est02["importes12"];                
    }    
    
    
    $indice++;   
    
    $estadoNom[$indice]="Partidas no Ordinarias"; 
    
    $estadoPer1[$indice]=0;
    $estadoPer2[$indice]=0;
    $estadoPer3[$indice]=0;
    $estadoPer4[$indice]=0;
    $estadoPer5[$indice]=0;
    $estadoPer6[$indice]=0;
    $estadoPer7[$indice]=0;
    $estadoPer8[$indice]=0;
    $estadoPer9[$indice]=0;
    $estadoPer10[$indice]=0;
    $estadoPer11[$indice]=0;
    $estadoPer12[$indice]=0;
               
    $sqlEst01="select * from asociaclave where idempresa='".$buscaempresa."' and idclave=25";
    $resultEst01=mysql_query($sqlEst01,$con) or die(mysql_error()); 
    
    while ($est01=mysql_fetch_assoc($resultEst01)) {  
        $sqlEst04="select * from cuenta where idempresa='".$buscaempresa."' and codigo='".$est01["codigo"]."' ";
        $resultEst04=mysql_query($sqlEst04,$con) or die(mysql_error());
        $est04=mysql_fetch_assoc($resultEst04);
        
        $sqlEst02="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=2 ";
        $resultEst02=mysql_query($sqlEst02,$con) or die(mysql_error());
        $est02=mysql_fetch_assoc($resultEst02);        
        
        $estadoPer1[$indice]+=$est02["importes1"];   
        $estadoPer2[$indice]+=$est02["importes2"]; 
        $estadoPer3[$indice]+=$est02["importes3"]; 
        $estadoPer4[$indice]+=$est02["importes4"];
        $estadoPer5[$indice]+=$est02["importes5"];
        $estadoPer6[$indice]+=$est02["importes6"]; 
        $estadoPer7[$indice]+=$est02["importes7"];
        $estadoPer8[$indice]+=$est02["importes8"]; 
        $estadoPer9[$indice]+=$est02["importes9"]; 
        $estadoPer10[$indice]+=$est02["importes10"];
        $estadoPer11[$indice]+=$est02["importes11"];
        $estadoPer12[$indice]+=$est02["importes12"];                
    }     
    
    
    $indice++;   
    
    $estadoNom[$indice]="Impuestos a la Utilidad"; 
    
    $estadoPer1[$indice]=0;
    $estadoPer2[$indice]=0;
    $estadoPer3[$indice]=0;
    $estadoPer4[$indice]=0;
    $estadoPer5[$indice]=0;
    $estadoPer6[$indice]=0;
    $estadoPer7[$indice]=0;
    $estadoPer8[$indice]=0;
    $estadoPer9[$indice]=0;
    $estadoPer10[$indice]=0;
    $estadoPer11[$indice]=0;
    $estadoPer12[$indice]=0;
               
    $sqlEst01="select * from asociaclave where idempresa='".$buscaempresa."' and idclave=22";
    $resultEst01=mysql_query($sqlEst01,$con) or die(mysql_error()); 
    
    while ($est01=mysql_fetch_assoc($resultEst01)) {  
        $sqlEst04="select * from cuenta where idempresa='".$buscaempresa."' and codigo='".$est01["codigo"]."' ";
        $resultEst04=mysql_query($sqlEst04,$con) or die(mysql_error());
        $est04=mysql_fetch_assoc($resultEst04);
        
        $sqlEst02="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=2 ";
        $resultEst02=mysql_query($sqlEst02,$con) or die(mysql_error());
        $est02=mysql_fetch_assoc($resultEst02);        
        
        $estadoPer1[$indice]+=$est02["importes1"];   
        $estadoPer2[$indice]+=$est02["importes2"]; 
        $estadoPer3[$indice]+=$est02["importes3"]; 
        $estadoPer4[$indice]+=$est02["importes4"];
        $estadoPer5[$indice]+=$est02["importes5"];
        $estadoPer6[$indice]+=$est02["importes6"]; 
        $estadoPer7[$indice]+=$est02["importes7"];
        $estadoPer8[$indice]+=$est02["importes8"]; 
        $estadoPer9[$indice]+=$est02["importes9"]; 
        $estadoPer10[$indice]+=$est02["importes10"];
        $estadoPer11[$indice]+=$est02["importes11"];
        $estadoPer12[$indice]+=$est02["importes12"];                
    } 
    
    $indice++;
    
    
    $estadoNom[$indice]="Operaciones Discontinuadas"; 
    
    $estadoPer1[$indice]=0;
    $estadoPer2[$indice]=0;
    $estadoPer3[$indice]=0;
    $estadoPer4[$indice]=0;
    $estadoPer5[$indice]=0;
    $estadoPer6[$indice]=0;
    $estadoPer7[$indice]=0;
    $estadoPer8[$indice]=0;
    $estadoPer9[$indice]=0;
    $estadoPer10[$indice]=0;
    $estadoPer11[$indice]=0;
    $estadoPer12[$indice]=0;
               
    $sqlEst01="select * from asociaclave where idempresa='".$buscaempresa."' and idclave=26";
    $resultEst01=mysql_query($sqlEst01,$con) or die(mysql_error()); 
    
    while ($est01=mysql_fetch_assoc($resultEst01)) {  
        $sqlEst04="select * from cuenta where idempresa='".$buscaempresa."' and codigo='".$est01["codigo"]."' ";
        $resultEst04=mysql_query($sqlEst04,$con) or die(mysql_error());
        $est04=mysql_fetch_assoc($resultEst04);
        
        $sqlEst02="select * from saldo where idempresa='".$buscaempresa."' and ejercicio='".$buscaejercicio."' and idcuenta='".$est04["idcuenta"]."' and tipo=2 ";
        $resultEst02=mysql_query($sqlEst02,$con) or die(mysql_error());
        $est02=mysql_fetch_assoc($resultEst02);        
        
        $estadoPer1[$indice]+=$est02["importes1"];   
        $estadoPer2[$indice]+=$est02["importes2"]; 
        $estadoPer3[$indice]+=$est02["importes3"]; 
        $estadoPer4[$indice]+=$est02["importes4"];
        $estadoPer5[$indice]+=$est02["importes5"];
        $estadoPer6[$indice]+=$est02["importes6"]; 
        $estadoPer7[$indice]+=$est02["importes7"];
        $estadoPer8[$indice]+=$est02["importes8"]; 
        $estadoPer9[$indice]+=$est02["importes9"]; 
        $estadoPer10[$indice]+=$est02["importes10"];
        $estadoPer11[$indice]+=$est02["importes11"];
        $estadoPer12[$indice]+=$est02["importes12"];                
    } 
    
    $indice++;
    
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
    
    
    for($i=0;$i<$indice;$i++){
       // echo "</br>".$estadoNom[$i]." ".$estadoPer1[$i]." ".$estadoPer2[$i]." ".$estadoPer3[$i]." ".$estadoPer4[$i]." ".$estadoPer5[$i]." ".$estadoPer6[$i]." ".$estadoPer7[$i]." ".$estadoPer8[$i]." ".$estadoPer9[$i]." ".$estadoPer10[$i]." ".$estadoPer11[$i]." ".$estadoPer12[$i];
        
    }

    
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
    
    $pdf->AddPage('L', 'A4'); 
    $pdf->Image('recursos/logo300px.jpg', 110, 70, 75, 32, 'JPG', 'http://www.gaagdesarrolloempresarial.com', '', true, 150, '', false, false, 0, false, false, false);
    $pdf->Line(90, 110, 200, 110);
    $pdf->SetFont('Helvetica', '', 16);
    $pdf->Text(115, 113, 'Estado de Resultados');
    $pdf->Line(90, 123, 200, 123);
    
    $pdf->SetFont('Helvetica', '', 8);    
    $pdf->SetTextColor(126,130,109);
    $pdf->Text(110, 130, 'Preparado por');    
    $pdf->SetFont('Helvetica', '', 12);        
    $pdf->SetTextColor(0,0,0);
    $pdf->Text(110, 135, 'GAAG Desarrollo Empresarial');    
    
    $pdf->SetFont('Helvetica', '', 8);    
    $pdf->SetTextColor(126,130,109);
    $pdf->Text(110, 144, 'Para');    
    $pdf->SetFont('Helvetica', '', 12);        
    $pdf->SetTextColor(0,0,0);
    $pdf->Text(110, 149,$Empresa["nombre"]);
    
    $pdf->SetFont('Helvetica', '', 8);
    $pdf->SetTextColor(126,130,109);    
    $pdf->Text(110, 157, 'Periodo en Revisión');    
    $pdf->SetFont('Helvetica', '', 12);
    $pdf->SetTextColor(0,0,0);    
    $pdf->Text(110, 162,$buscaano);      
    
    $pdf->SetFont('Helvetica', '', 8);
    $pdf->SetTextColor(126,130,109);    
    $pdf->Text(110, 170, 'Creado el');     
    $pdf->SetFont('Helvetica', '', 9);
    $pdf->SetTextColor(0,0,0);  
    $dia=date("d");
    $mes=date("m");
    $ano=date("Y");
    $pdf->Text(110, 175,$dia." de ".$meses[($mes-1)]." de ".$ano); 
    
    $pdf->AddPage('L', 'A4');   
    $pdf->Image('recursos/logo300px.jpg', 10, 10, 30, 12.8, 'JPG', 'http://www.gaagdesarrolloempresarial.com', '', true, 150, '', false, false, 0, false, false, false);
    $pdf->Line(10, 25, 285, 25);   
    $pdf->SetFont('Helvetica', '', 10);
    $pdf->Text(9, 26, 'Estado de Resultados '.$Empresa["nombre"]." | ".$buscaano);  
    $pdf->SetFont('Helvetica', '', 7);    
    
    function columna($pdf,$fila,$texto01,$texto02,$texto03,$texto04,$texto05,$texto06,$texto07,$texto08,$texto09,$texto10,$texto11,$texto12,$texto13,$inicial){
        $ali="R";
        if($inicial==1){
            $ali="C";
        }
        $filab=35+(($fila*5)-5);
        $ancho=70;
        $margen=10;
        $pdf->SetXY($margen, $filab);
        $pdf->Cell($ancho, 5,$texto01, 0, 1, 'L', 0, '', 0); 
        $margen+=$ancho;
        $ancho=17;
        $pdf->SetXY($margen, $filab);
        $pdf->Cell($ancho, 5,$texto02, 0, 1, $ali, 0, '', 0);
        $margen+=$ancho;
        $pdf->SetXY($margen, $filab);
        $pdf->Cell($ancho, 5,$texto03, 0, 1, $ali, 0, '', 0); 
        $margen+=$ancho;
        $pdf->SetXY($margen, $filab);
        $pdf->Cell($ancho, 5,$texto04, 0, 1, $ali, 0, '', 0);
        $margen+=$ancho;
        $pdf->SetXY($margen, $filab);
        $pdf->Cell($ancho, 5,$texto05, 0, 1, $ali, 0, '', 0);
        $margen+=$ancho;
        $pdf->SetXY($margen, $filab);
        $pdf->Cell($ancho, 5,$texto06, 0, 1, $ali, 0, '', 0);
        $margen+=$ancho;
        $pdf->SetXY($margen, $filab);
        $pdf->Cell($ancho, 5,$texto07, 0, 1, $ali, 0, '', 0);
        $margen+=$ancho;
        $pdf->SetXY($margen, $filab);
        $pdf->Cell($ancho, 5,$texto08, 0, 1, $ali, 0, '', 0);
        $margen+=$ancho;
        $pdf->SetXY($margen, $filab);
        $pdf->Cell($ancho, 5,$texto09, 0, 1, $ali, 0, '', 0);
        $margen+=$ancho;
        $pdf->SetXY($margen, $filab);
        $pdf->Cell($ancho, 5,$texto10, 0, 1, $ali, 0, '', 0);
        $margen+=$ancho;
        $pdf->SetXY($margen, $filab);
        $pdf->Cell($ancho, 5,$texto11, 0, 1, $ali, 0, '', 0);
        $margen+=$ancho;
        $pdf->SetXY($margen, $filab);
        $pdf->Cell($ancho, 5,$texto12, 0, 1, $ali, 0, '', 0);
        $margen+=$ancho;
        $pdf->SetXY($margen, $filab);
        $pdf->Cell($ancho, 5,$texto13, 0, 1, $ali, 0, '', 0);             
    } 
    
    $acumes1=$estadoPer1[0];
    $acumes2=$estadoPer2[0];
    $acumes3=$estadoPer3[0];
    $acumes4=$estadoPer4[0];
    $acumes5=$estadoPer5[0];
    $acumes6=$estadoPer6[0];
    $acumes7=$estadoPer7[0];
    $acumes8=$estadoPer8[0];
    $acumes9=$estadoPer9[0];
    $acumes10=$estadoPer10[0];
    $acumes11=$estadoPer11[0];
    $acumes12=$estadoPer12[0];
    
    $fila=4;
    $pdf->SetFont('Helvetica', 'B', 8);
    columna($pdf,$fila,"","Enero" ,"Febrero","Marzo", "Abril","Mayo","Junio","julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre", 1);
    $pdf->SetFont('Helvetica', '', 7);
    $fila++;
    $fila++;
    columna($pdf,$fila,$estadoNom[0],number_format($estadoPer1[0],2),number_format($estadoPer2[0],2),number_format($estadoPer3[0],2),number_format($estadoPer4[0],2),number_format($estadoPer5[0],2),number_format($estadoPer6[0],2),number_format($estadoPer7[0],2),number_format($estadoPer8[0],2),number_format($estadoPer9[0],2),number_format($estadoPer10[0],2),number_format($estadoPer11[0],2),number_format($estadoPer12[0],2), 0);
    $fila++;
    columna($pdf,$fila,$estadoNom[1],number_format($estadoPer1[1],2),number_format($estadoPer2[1],2),number_format($estadoPer3[1],2),number_format($estadoPer4[1],2),number_format($estadoPer5[1],2),number_format($estadoPer6[1],2),number_format($estadoPer7[1],2),number_format($estadoPer8[1],2),number_format($estadoPer9[1],2),number_format($estadoPer10[1],2),number_format($estadoPer11[1],2),number_format($estadoPer12[1],2), 0);
    $fila++;
    columna($pdf,$fila,$estadoNom[2],number_format($estadoPer1[2],2),number_format($estadoPer2[2],2),number_format($estadoPer3[2],2),number_format($estadoPer4[2],2),number_format($estadoPer5[2],2),number_format($estadoPer6[2],2),number_format($estadoPer7[2],2),number_format($estadoPer8[2],2),number_format($estadoPer9[2],2),number_format($estadoPer10[2],2),number_format($estadoPer11[2],2),number_format($estadoPer12[2],2), 0);    
    
    $acumes1=($acumes1-$estadoPer1[1]-$estadoPer1[2]);
    $acumes2=($acumes2-$estadoPer2[1]-$estadoPer2[2]);
    $acumes3=($acumes3-$estadoPer3[1]-$estadoPer3[2]);
    $acumes4=($acumes4-$estadoPer4[1]-$estadoPer4[2]);
    $acumes5=($acumes5-$estadoPer5[1]-$estadoPer5[2]);
    $acumes6=($acumes6-$estadoPer6[1]-$estadoPer6[2]);
    $acumes7=($acumes7-$estadoPer7[1]-$estadoPer7[2]);
    $acumes8=($acumes8-$estadoPer8[1]-$estadoPer8[2]);
    $acumes9=($acumes9-$estadoPer9[1]-$estadoPer9[2]);
    $acumes10=($acumes10-$estadoPer10[1]-$estadoPer10[2]);
    $acumes11=($acumes11-$estadoPer11[1]-$estadoPer11[2]);
    $acumes12=($acumes12-$estadoPer12[1]-$estadoPer12[2]);
    $pdf->SetFont('Helvetica', 'B', 7);
    $fila++;
    $pdf->Line(81,(35+(($fila*5)-5)), 283,(35+(($fila*5)-5)));
    columna($pdf,$fila,"Utilidad o Pérdida Bruta",number_format($acumes1,2),number_format($acumes2,2),number_format($acumes3,2),number_format($acumes4,2),number_format($acumes5,2),number_format($acumes6,2),number_format($acumes7,2),number_format($acumes8,2),number_format($acumes9,2),number_format($acumes10,2),number_format($acumes11,2),number_format($acumes12,2), 0);    
    $fila++;
    $fila++;
    $pdf->SetFont('Helvetica','', 7);
    columna($pdf,$fila,$estadoNom[3],number_format($estadoPer1[3],2),number_format($estadoPer2[3],2),number_format($estadoPer3[3],2),number_format($estadoPer4[3],2),number_format($estadoPer5[3],2),number_format($estadoPer6[3],2),number_format($estadoPer7[3],2),number_format($estadoPer8[3],2),number_format($estadoPer9[3],2),number_format($estadoPer10[3],2),number_format($estadoPer11[3],2),number_format($estadoPer12[3],2), 0);    
    $fila++;
    columna($pdf,$fila,$estadoNom[4],number_format($estadoPer1[4],2),number_format($estadoPer2[4],2),number_format($estadoPer3[4],2),number_format($estadoPer4[4],2),number_format($estadoPer5[4],2),number_format($estadoPer6[4],2),number_format($estadoPer7[4],2),number_format($estadoPer8[4],2),number_format($estadoPer9[4],2),number_format($estadoPer10[4],2),number_format($estadoPer11[4],2),number_format($estadoPer12[4],2), 0);        
    $acumes1=($acumes1-$estadoPer1[3]-$estadoPer1[4]);
    $acumes2=($acumes2-$estadoPer2[3]-$estadoPer2[4]);
    $acumes3=($acumes3-$estadoPer3[3]-$estadoPer3[4]);
    $acumes4=($acumes4-$estadoPer4[3]-$estadoPer4[4]);
    $acumes5=($acumes5-$estadoPer5[3]-$estadoPer5[4]);
    $acumes6=($acumes6-$estadoPer6[3]-$estadoPer6[4]);
    $acumes7=($acumes7-$estadoPer7[3]-$estadoPer7[4]);
    $acumes8=($acumes8-$estadoPer8[3]-$estadoPer8[4]);
    $acumes9=($acumes9-$estadoPer9[3]-$estadoPer9[4]);
    $acumes10=($acumes10-$estadoPer10[3]-$estadoPer10[4]);
    $acumes11=($acumes11-$estadoPer11[3]-$estadoPer11[4]);
    $acumes12=($acumes12-$estadoPer12[3]-$estadoPer12[4]);    
    $fila++;
    $pdf->SetFont('Helvetica', 'B', 7);
    $pdf->Line(81,(35+(($fila*5)-5)), 283,(35+(($fila*5)-5)));
    columna($pdf,$fila,"Utilidad o Pérdida en Operación",number_format($acumes1,2),number_format($acumes2,2),number_format($acumes3,2),number_format($acumes4,2),number_format($acumes5,2),number_format($acumes6,2),number_format($acumes7,2),number_format($acumes8,2),number_format($acumes9,2),number_format($acumes10,2),number_format($acumes11,2),number_format($acumes12,2), 0);        
    $pdf->SetFont('Helvetica','', 7);
    
    $fila++;
    $fila++;
    columna($pdf,$fila,$estadoNom[5],number_format($estadoPer1[5],2),number_format($estadoPer2[5],2),number_format($estadoPer3[5],2),number_format($estadoPer4[5],2),number_format($estadoPer5[5],2),number_format($estadoPer6[5],2),number_format($estadoPer7[5],2),number_format($estadoPer8[5],2),number_format($estadoPer9[5],2),number_format($estadoPer10[5],2),number_format($estadoPer11[5],2),number_format($estadoPer12[5],2), 0);            
    $fila++;
    columna($pdf,$fila,$estadoNom[6],number_format($estadoPer1[6],2),number_format($estadoPer2[6],2),number_format($estadoPer3[6],2),number_format($estadoPer4[6],2),number_format($estadoPer5[6],2),number_format($estadoPer6[6],2),number_format($estadoPer7[6],2),number_format($estadoPer8[6],2),number_format($estadoPer9[6],2),number_format($estadoPer10[6],2),number_format($estadoPer11[6],2),number_format($estadoPer12[6],2), 0);            
    $fila++;
    columna($pdf,$fila,$estadoNom[7],number_format($estadoPer1[7],2),number_format($estadoPer2[7],2),number_format($estadoPer3[7],2),number_format($estadoPer4[7],2),number_format($estadoPer5[7],2),number_format($estadoPer6[7],2),number_format($estadoPer7[7],2),number_format($estadoPer8[7],2),number_format($estadoPer9[7],2),number_format($estadoPer10[7],2),number_format($estadoPer11[7],2),number_format($estadoPer12[7],2), 0);            
    $fila++;
    columna($pdf,$fila,$estadoNom[8],number_format($estadoPer1[8],2),number_format($estadoPer2[8],2),number_format($estadoPer3[8],2),number_format($estadoPer4[8],2),number_format($estadoPer5[8],2),number_format($estadoPer6[8],2),number_format($estadoPer7[8],2),number_format($estadoPer8[8],2),number_format($estadoPer9[8],2),number_format($estadoPer10[8],2),number_format($estadoPer11[8],2),number_format($estadoPer12[8],2), 0);                
    
    $acumes1=($acumes1-$estadoPer1[5]-$estadoPer1[6]-$estadoPer1[7]-$estadoPer1[8]);
    $acumes2=($acumes2-$estadoPer2[5]-$estadoPer2[6]-$estadoPer2[7]-$estadoPer2[8]);
    $acumes3=($acumes3-$estadoPer3[5]-$estadoPer3[6]-$estadoPer3[7]-$estadoPer3[8]);
    $acumes4=($acumes4-$estadoPer4[5]-$estadoPer4[6]-$estadoPer4[7]-$estadoPer4[8]);
    $acumes5=($acumes5-$estadoPer5[5]-$estadoPer5[6]-$estadoPer5[7]-$estadoPer5[8]);
    $acumes6=($acumes6-$estadoPer6[5]-$estadoPer6[6]-$estadoPer6[7]-$estadoPer6[8]);
    $acumes7=($acumes7-$estadoPer7[5]-$estadoPer7[6]-$estadoPer7[7]-$estadoPer7[8]);
    $acumes8=($acumes8-$estadoPer8[5]-$estadoPer8[6]-$estadoPer8[7]-$estadoPer8[8]);
    $acumes9=($acumes9-$estadoPer9[5]-$estadoPer9[6]-$estadoPer9[7]-$estadoPer9[8]);
    $acumes10=($acumes10-$estadoPer10[5]-$estadoPer10[6]-$estadoPer10[7]-$estadoPer10[8]);
    $acumes11=($acumes11-$estadoPer11[5]-$estadoPer11[6]-$estadoPer11[7]-$estadoPer11[8]);
    $acumes12=($acumes12-$estadoPer12[5]-$estadoPer12[6]-$estadoPer12[7]-$estadoPer12[8]);    
    
    $fila++;
    $pdf->SetFont('Helvetica', 'B', 7);
    $pdf->Line(81,(35+(($fila*5)-5)), 283,(35+(($fila*5)-5)));
    columna($pdf,$fila,"Utilidad o pérdida antes de impuestos a la utilidad",number_format($acumes1,2),number_format($acumes2,2),number_format($acumes3,2),number_format($acumes4,2),number_format($acumes5,2),number_format($acumes6,2),number_format($acumes7,2),number_format($acumes8,2),number_format($acumes9,2),number_format($acumes10,2),number_format($acumes11,2),number_format($acumes12,2), 0);        
    $pdf->SetFont('Helvetica','', 7);    
    
    $fila++;
    $fila++;
    columna($pdf,$fila,$estadoNom[9],number_format($estadoPer1[9],2),number_format($estadoPer2[9],2),number_format($estadoPer3[9],2),number_format($estadoPer4[9],2),number_format($estadoPer5[9],2),number_format($estadoPer6[9],2),number_format($estadoPer7[9],2),number_format($estadoPer8[9],2),number_format($estadoPer9[9],2),number_format($estadoPer10[9],2),number_format($estadoPer11[9],2),number_format($estadoPer12[9],2), 0);                    
    
    $acumes1=($acumes1-$estadoPer1[9]);
    $acumes2=($acumes2-$estadoPer2[9]);
    $acumes3=($acumes3-$estadoPer3[9]);
    $acumes4=($acumes4-$estadoPer4[9]);
    $acumes5=($acumes5-$estadoPer5[9]);
    $acumes6=($acumes6-$estadoPer6[9]);
    $acumes7=($acumes7-$estadoPer7[9]);
    $acumes8=($acumes8-$estadoPer8[9]);
    $acumes9=($acumes9-$estadoPer9[9]);
    $acumes10=($acumes10-$estadoPer10[9]);
    $acumes11=($acumes11-$estadoPer11[9]);
    $acumes12=($acumes12-$estadoPer12[9]);
    $fila++;
    $pdf->SetFont('Helvetica', 'B', 7);
    $pdf->Line(81,(35+(($fila*5)-5)), 283,(35+(($fila*5)-5)));
    columna($pdf,$fila,"Utilidad o pérdida antes de las operaciones discontinuadas",number_format($acumes1,2),number_format($acumes2,2),number_format($acumes3,2),number_format($acumes4,2),number_format($acumes5,2),number_format($acumes6,2),number_format($acumes7,2),number_format($acumes8,2),number_format($acumes9,2),number_format($acumes10,2),number_format($acumes11,2),number_format($acumes12,2), 0);        
    $pdf->SetFont('Helvetica','', 7); 
    
    $fila++;
    $fila++;
    columna($pdf,$fila,$estadoNom[10],number_format($estadoPer1[10],2),number_format($estadoPer2[10],2),number_format($estadoPer3[10],2),number_format($estadoPer4[10],2),number_format($estadoPer5[10],2),number_format($estadoPer6[10],2),number_format($estadoPer7[10],2),number_format($estadoPer8[10],2),number_format($estadoPer9[10],2),number_format($estadoPer10[10],2),number_format($estadoPer11[10],2),number_format($estadoPer12[10],2), 0);                        
    
    $acumes1=($acumes1-$estadoPer1[10]);
    $acumes2=($acumes2-$estadoPer2[10]);
    $acumes3=($acumes3-$estadoPer3[10]);
    $acumes4=($acumes4-$estadoPer4[10]);
    $acumes5=($acumes5-$estadoPer5[10]);
    $acumes6=($acumes6-$estadoPer6[10]);
    $acumes7=($acumes7-$estadoPer7[10]);
    $acumes8=($acumes8-$estadoPer8[10]);
    $acumes9=($acumes9-$estadoPer9[10]);
    $acumes10=($acumes10-$estadoPer10[10]);
    $acumes11=($acumes11-$estadoPer11[10]);
    $acumes12=($acumes12-$estadoPer12[10]);
    $fila++;
    $pdf->SetFont('Helvetica', 'B', 7);
    $pdf->Line(81,(35+(($fila*5)-5)), 283,(35+(($fila*5)-5)));
    columna($pdf,$fila,"Utilidad o pérdida neta",number_format($acumes1,2),number_format($acumes2,2),number_format($acumes3,2),number_format($acumes4,2),number_format($acumes5,2),number_format($acumes6,2),number_format($acumes7,2),number_format($acumes8,2),number_format($acumes9,2),number_format($acumes10,2),number_format($acumes11,2),number_format($acumes12,2), 0);            
    
    
    $pdf->SetFont('Helvetica', '', 7);
    $pdf->SetTextColor(126,130,109);
    $pdf->Text(10,190,"Creado por GAAG Desarrollo Empresarial");
    $pdf->SetTextColor(0,0,0);
    $pdf->Text(10,194,$Empresa["nombre"]." | ".$buscaano); 
    $pdf->Text(10,198,"Página 02");    
    
    $pdf->Output('Estado de Resultados.pdf', 'I');  
    
    
?>