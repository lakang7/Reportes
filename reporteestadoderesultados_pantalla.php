
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
        <title>Generar | Reporte Estado de Resultados Mensualizado</title>
        <script type="text/javascript" language="JavaScript" >
            function redirigir(direccion){
                location.href=direccion;
            }		
        </script>        
        
        <?php
            header('Content-Type: text/html; charset=UTF-8');        
            require_once("funciones/funciones.php");
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
                    <form method="post" id="form_CREARfambotanica" action="recursos/acciones.php?tarea=11">
                        <div class="col-md-12 titulopagina" style="margin-top: 125px;">Reporte Estado de Resultados Mensualizado</div>
                        <div class="col-md-12 subtitulopagina">Generar Reporte</div>
                    
                        <div class="col-md-7 contiene_entrada" style="padding: 0px">
                            <div class="col-md-12 titulo_entrada">Nombre de la Empresa</div>
                            <div class="col-md-12">
                            <select id="empresa" name="empresa" class="selectpicker show-tick" data-live-search="true" data-width="100%" required="required">
                                <?php
                                    $con=Conexion();
                                    $sql_listaEMPRESA="select * from empresa order by nombre";
                                    $result_listaEMPRESA=mysql_query($sql_listaEMPRESA,$con) or die(mysql_error());
                                    if(mysql_num_rows($result_listaEMPRESA)>0){
                                        while ($fila = mysql_fetch_assoc($result_listaEMPRESA)) {
                                            if($_GET["empresa"]==$fila["idempresa"]){
                                                echo "<option selected='selected' value='".$fila["idempresa"]."'>".$fila["nombre"]."</option>";
                                            }else{
                                                echo "<option value='".$fila["idempresa"]."'>".$fila["nombre"]."</option>";
                                            }                                                                                                                                    
                                        }
                                    }
                                    mysql_close($con);
                                ?>
                            </select>                                                        
                            </div>
                        </div>        
                        
                        <div class="col-md-2 contiene_entrada" style="padding: 0px; margin-left: -15px">
                            <div class="col-md-12 titulo_entrada">Año</div>
                            <div class="col-md-12">
                                <select id="anno" name="anno" class="selectpicker show-tick" data-live-search="true" data-width="100%" required="required">
                                <?php
                                    $periodos=array();
                                    $periodos[0]=2014;
                                    $periodos[1]=2015;
                                    $periodos[2]=2016;
                                    $periodos[3]=2017;
                                   // print_r($_GET);
                                    if(empty($_GET["anno"])){
                                        //echo "no existe";
                                        for($i=0;$i<count($periodos);$i++){
                                            $hoy = getdate();                                            
                                            if($hoy["year"]==$periodos[$i]){
                                                echo "<option selected='selected' value='".$periodos[$i]."'>".$periodos[$i]."</option>"; 
                                            }else{
                                                echo "<option value='".$periodos[$i]."'>".$periodos[$i]."</option>";
                                            }  
                                        }
                                    }else{
                                        //echo "existe";
                                        for($i=0;$i<count($periodos);$i++){
                                            if($_GET["anno"]==$periodos[$i]){
                                                echo "<option selected='selected' value='".$periodos[$i]."'>".$periodos[$i]."</option>"; 
                                            }else{
                                                echo "<option value='".$periodos[$i]."'>".$periodos[$i]."</option>";
                                            }                                       
                                        }                                        
                                    }
                                ?>
                                </select>                                                         
                            </div>
                        </div> 
                        
                        <div class="col-md-2 contiene_entrada" style="padding: 0px; margin-left: -15px">
                            <div class="col-md-12 titulo_entrada">Mes Limite</div>
                            <div class="col-md-12">
                            <?php 
                                $meses=array();
                                $meses[1]="Enero";
                                $meses[2]="Febrero";
                                $meses[3]="Marzo";
                                $meses[4]="Abril";
                                $meses[5]="Mayo";
                                $meses[6]="Junio";
                                $meses[7]="Julio";
                                $meses[8]="Agosto";
                                $meses[9]="Septiembre";
                                $meses[10]="Octubre";
                                $meses[11]="Noviembre";
                                $meses[12]="Diciembre";                                                                                            
                            ?>
                            <select id="mes" name="mes" class="selectpicker show-tick" data-live-search="true" data-width="100%" required="required">                                
                                <?php
                                    for($i=1;$i<13;$i++){
                                        if($_GET["mes"]==$i){
                                            echo "<option selected='selected' value='".$i."'>".$meses[$i]."</option>";
                                        }else{
                                            echo "<option value='".$i."'>".$meses[$i]."</option>";
                                        }
                                        
                                    }
                                
                                ?>                                                               
                            </select>                                                        
                            </div>
                        </div>                        
                        
                        <div class="col-md-1 contiene_entrada" style="margin-left: -15px">
                            <div class="col-md-12 titulo_entrada" style="color: #ffffff">h</div>
                            <button type="submit" class="btn btn-default">Submit</button>                       
                        </div>                        
                        
                    </form>
                </div>
            </div>
        </div>                 
    </body>
</html>
