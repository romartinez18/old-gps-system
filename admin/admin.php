<?php

include_once('modules/header.php');

$url = "admin.php";

?>
 <!doctype html>

<html lang="es">
<head>
  <meta charset="utf-8">

  <title>Admin GPS</title>
  <meta name="author" content="Ronald Martinez">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/bootstrap-theme.min.css" rel="stylesheet">
  <link href="css/custom.css" rel="stylesheet"/>
  <link href="css/admestilos.css" rel="stylesheet"/>
  <script type="text/javascript" src="js/jquery-3.1.0.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/responsive.bootstrap.min.js"></script>
  <script type="text/javascript" src="js/funciones.js"></script>
  <script>
   function cerrarSesion() {
	   if (confirm("\xBFEsta seguro de que desea Cerrar la Sesi\u00F3n?")) {
			$.ajax({
			  url: '../gps/php/csesion.php',
				success: function(data) {
				  window.location="../gps/index.php";
				},error: function (data, textStatus, xhr) { 
				  console.log("(" + xhr.responseText + ")");
				}
			});
	   }
    }
	$(document).ajaxStart(function(){
		$(".bgpreloader").show();
		$(".gpsload-thecube").show();
	});
	
	$(document).ajaxStop(function(){
		$(".bgpreloader").hide();
		$(".gpsload-thecube").hide();
	});
  </script>

  <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  <![endif]-->
</head>

<body>

	<div class="bgpreloader"></div>
	<div class="gpsload-thecube">
		<div class="gpsload-cube gpsload-c1"></div>
		<div class="gpsload-cube gpsload-c2"></div>
		<div class="gpsload-cube gpsload-c4"></div>
		<div class="gpsload-cube gpsload-c3"></div>
	</div>
	
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12" style="padding-top:5%;padding-bottom:15%;">
			<h3 class="text-center text-primary">
				Panel de Administraci&oacute;n GPS
			</h3>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						Informaci&oacute;n de Administraci&oacute;n 1
					</h3>
				</div>
				<div class="panel-body">
					Panel Informativo o de Publicidad 1
				</div>
			</div>
		</div>
		<div class="col-md-4">
			 
			<a href="usuarios.php" class="btn btn-block btn-success">Panel de Usuarios</a>
			<a href="vehiculos.php" class="btn btn-block btn-info">Panel de Vehiculos</a>
			<a href="#" class="btn btn-block btn-primary">Panel de GPS</a>
			<a href="../gps/listado.php" class="btn btn-block btn-warning">Listado General</a><br/><br/>
			<center><a href="#" class="btn btn-danger btn-flat" onclick="cerrarSesion();"><span class="fa fa-sign-out">&nbsp;</span>Cerrar Sesi&oacute;n</a></center>
		</div>
		<div class="col-md-4">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						Informaci&oacute;n de Administraci&oacute;n 2
					</h3>
				</div>
				<div class="panel-body">
					Panel Informativo o de Publicidad 2
				</div>
			</div>
		</div>
	</div><br/><br/>
	<div class="row">
		<div class="col-md-4" >
			<center><img alt="Logotipo" src="images/registration-icon.png" class="img-rounded" /></center>
		</div>
		<div class="col-md-4">
			<p>
				<center><strong>Panel de Administraci&oacute;n de GPS</strong><br/><br/> Copyright 2015 - 2016</center>
			</p>
		</div>
		<div class="col-md-4">
			 
			<address>
				 <strong>Teleinformatic</strong><br /> Centro Comercial Los Jardines Local Nro 1<br /> Maracay Edo. Aragua<br /> <abbr title="Phone">Telf:</abbr> (243) 123-4567
			</address>
		</div>
	</div>
</div>  
</body>
</html>
 
 
 
 
 
 