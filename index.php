<?php session_start(); ?>
<html>
    <head>
        <meta charset="UTF-8">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script type="text/javascript" src="../bootstrap/js/bootstrap.js"></script>
        <script type="text/javascript" src="../bootstrap/js/bootstrap.min.js"></script>   
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link href='https://fonts.googleapis.com/css?family=Yanone+Kaffeesatz' rel='stylesheet' type='text/css'>
        <link href="../administracion/recursos/administracion.css" rel='stylesheet' type='text/css'>        
        
        <title>Iniciar Sesión Administracion</title>
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
    		<div class="col-md-4" ></div>
    		<div class="col-md-4" style="text-align: center">
    		<div class="row">
    			<div class="col-md-12" style="height: 80px;" ></div>
                        <div class="col-md-12" style="height: 150px; display: table" ><div style="display: table-cell;"><img src="recursos/logo300px.png" /></div></div>
    		</div>
      		<form class="form-signin formulario" method="post" role="form" style="margin-top: 10px;" action="recursos/acciones.php?tarea=1">
        		<h3 class="form-signin-heading" style="text-align: center">Inicio Sesión</h3>
        		<input name="email" id="email" type="email" class="form-control" placeholder="Email address" style="margin-bottom: 15px;" required autofocus>
        		<input name="passw" id="passw" type="password" class="form-control" placeholder="Password" style="margin-bottom: 15px;" required>
        		<button type="submit" class="btn btn-success">Iniciar Sesión</button>
      		</form>    			        			    			
    		</div>    		    		
    	</div>
    </div> 
    </body>
</html>

