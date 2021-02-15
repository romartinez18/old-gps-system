<?php 

session_start();

include_once('class/class.conexion.php');
include_once('modules/header.php');
$url = "index.php";

// verificar sesion
if (isset($_SESSION['cedula']) && isset($_SESSION['id'])) { 
$dbh = Conexion::singleton_conexion();
 $Id = trim($_SESSION['id']);
 $Cedula = trim($_SESSION['cedula']);
 
$sql = "SELECT * from usuarios WHERE (id = ".$Id." AND cedula = '".$Cedula."' AND Activo = 1) LIMIT 1";
$query = $dbh->prepare($sql);
$query->execute();
$dbh = null;

 $filas = $query->fetchAll(PDO::FETCH_ASSOC);
//$filas = (int) $query->fetchColumn(); 
if (count($filas) > 0) {    
  //si existe el usuario
  //if($query->rowCount() >= 0) {
    //header('Location: http://teleinformatic.net/mapa.php');
	if ($filas['nivelautorizacion'] == 1) {
?> 
<script>
console.log('-<?php echo $filas['nivelautorizacion']; ?>-');
window.location="mapa.php";
</script>
<?php
	} else {
?> 
<script>
console.log('-<?php echo $filas['nivelautorizacion']; ?>-');
window.location="mapa.php";
</script> 
<?php
    }
	
  } else {
    $_SESSION['cedula'] = null;
    $_SESSION['id'] = null;   
    $_SESSION['nivel'] = null;
    session_destroy();     
  }

  
 } 
  
?> 
<!DOCTYPE html>
<html lang="es">
  <head><meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
    
    <title>Localizaci&oacute;n Sat&eacute;lital GPS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Localizacion Satelital GPS Con Sistemas AVL">
    <meta name="author" content="Ronald Martinez">
	
	<link rel="shortcut icon" href="images/favicon.png">
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/estilos.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
	
  </head>
 <style type="text/css">
    body {
	background-color: #99CCFF;
}
    </style>
  <body>
  
	<div class="bgpreloader"></div>
	<div class="gpsload-thecube">
		<div class="gpsload-cube gpsload-c1"></div>
		<div class="gpsload-cube gpsload-c2"></div>
		<div class="gpsload-cube gpsload-c4"></div>
		<div class="gpsload-cube gpsload-c3"></div>
	</div>
	
    <div class="container">
     <form class="form-horizontal" name="fSesion" id="fSesion" role="form">
	 
      <div class="row"><!--  Fila 1  -->
		<div class="col-md-12">
		  <br/><br/><br/><br/>
		</div>
      </div><!--  Fin Fila 1  -->
	  
	  
      <div class="row"><!--  Fila 2  -->
	  
	  
	   <div class="col-md-12"><!--  Col Central  -->
		
		<div class="col-md-4"></div><!--  Col 1  -->
		
		<div class="col-md-4" id="center" style="vertical-align:middle;"><!--  Col 2  -->
		<div align="center"><img src="images/logo.png" style="max-width:100%; height:auto;" /></div>
			<div class="panel panel-primary" style="box-shadow:1px 1px 5px #88D;"><!--  Panel  -->
			  <div class="panel-heading" align="center"><h3>Iniciar Sesi&oacute;n</h3></div>
			  <div class="panel-body" style="background:#DDD;height:200px;padding:20px 20px 0px 20px;overflow:hidden;">
				  <label class="control-label">Usuario</label>
				  <input type="text" class="form-control" name="usuario" id="usuario" maxlength="15" placeholder="Nombre de Usuario" /><br/>
				  <label class="control-label">Contrase&ntilde;a</label>
				  <input type="password" class="form-control" name="clave" id="clave" maxlength="15" placeholder="Contrase&ntilde;a" /><br/><br/><br/>
			  </div>
			  <div class="panel-footer" style="background:#FFF;">
			   <div class="form-group" style="padding:0px 15px 0px 15px;">
			    <button type="button" class="btn btn-info btn-block" id="bsesion" name="bsesion" onclick="iniciarSesion();">Iniciar Sesi&oacute;n</button><br/>
				<a href="#" data-toggle='modal' data-target='#vModal'>&iquest;Olvido su Contrase&ntilde;a?</a>
			   </div>
			  </div>
			</div> <!--  Fin Panel  -->
		</div><!--  Fin Col 2  -->
		
		<div class="col-md-4"></div><!--  Col 3  -->
		
	   
	   </div><!--  Fin Col Central  -->
		
		
		<div class="modal fade" id="vModal" role="dialog" aria-labelledby="vModalTitulo"><!--  Ventana Modal  -->
		  <div class="modal-dialog" style="margin-top:10%;">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-close"></span></button>
				<h4 class="modal-title text-center" id="vModalTitulo">&iquest;Olvido su Contrase&ntilde;a?</h4>
			  </div>
			  <div class="modal-body">
			   <form name="frmRecup" id="frmRecup">
				<div class="row">
				  <div class="col-md-2">&nbsp;</div><!-- F1 COL 1 -->
				  <div class="col-md-8">
				  
				   <div class="form-group">
					<label for="nUser" class="control-label">Usuario:</label>
					<input type="text" maxlength="15" class="form-control" placeholder="Nombre de Usuario" id="nUser" name="nUser" value="" />
				  
				    <div class="alert alert-danger alert-dismissable" id="alertnUser" style="display:none;" role="alert">
					 <button type="button" class="close" data-dismiss="alert">&times;</button>
					 <strong>Error:&nbsp;</strong> <div id="msjnUser"></div>.
				    </div>
				  
				   </div>&nbsp;&nbsp;&nbsp;
				   
					
				  </div><!-- F1 COL 2 -->
				  <div class="col-md-2">&nbsp;</div><!-- F1 COL 3 -->
				</div><!--  FILA 1 -->
			  				
			    <div class="row">
				  <h5 style="text-align:justify;">
				    &nbsp;&nbsp;<b>Opci&oacute;n 1:</b><br/><br/>
				    &nbsp;&nbsp;&nbsp;Coloque el Correo Electr&oacute;nico Asociado a su Cuenta para Recuperar su Contrase&ntilde;a:
				  </h5>
			      <div class="col-md-2">&nbsp;</div><!-- F2 COL 1 -->
				  <div class="col-md-8">
				   <div class="input-group">
					<label for="nEmail" class="control-label">Correo Electr&oacute;nico:</label>
					<input type="email" maxlength="160" class="form-control" placeholder="Correo Electr&oacute;nico" id="nEmail" name="nEmail" value="" />
				  
				   <div class="alert alert-danger alert-dismissable" id="alertnEmail" style="display:none;" role="alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error:&nbsp;</strong> <div id="msjnEmail"></div>.
				   </div>
				  </div>&nbsp;&nbsp;&nbsp;
				  
				  </div><!-- F2 COL 1 -->
				  <div class="col-md-2">&nbsp;</div><!-- F2 COL 3 -->
			    </div><!--  FILA 2 -->
			  
				  <h5 style="text-align:justify;">
				    <b>Opci&oacute;n 2:</b><br/><br/>
				    &nbsp;Coloque el N&uacute;mero Tel&eacute;fonico Asociado a su Cuenta para Recuperar su Contrase&ntilde;a:
				  </h5>
			    <div class="input-group">
			      <label class="text-info">N&uacute;mero Tel&eacute;fonico:&nbsp;</label>
				  <select name="CodTelf" id="CodTelf">
					<option value="0412">0412</option>
					<option value="0414">0414</option>
					<option value="0416">0416</option>
					<option value="0424">0424</option>
				  </select>
				  <input type="text" name="nTelf" id="nTelf" value="" maxlength="8" placeholder="N&uacute;mero Tel&eacute;fonico" />
				  <span class="fa fa-phone"></span><br/><br/><br/>
				</div>
				
				</form>
			  </div><!-- modal body -->
			  <div class="modal-footer">
				<button type="button" class="btn btn-success" onclick="checkRecupClave();"><span class="fa fa-check"></span>&nbsp;Enviar Codigo</button>
				<button type="button" class="btn btn-danger" title="Cancelar" id="cerrarModal" data-dismiss="modal">
				  <span class="fa fa-times-circle-o"></span>&nbsp;Cancelar</button>
			  </div>
			</div>
		  </div>
		</div><!--  Fin Ventana Modal  -->
		
		
		 
	  </div><!--  Fin Fila 2  -->
		
	
	 </form>
	  
    </div><br/> <!-- /container -->
	
    <footer style="text-align:center;">
	  <a href="#">Desarrollado Por Ronald Martinez</a>
	</footer>
	
	<script src="js/jquery.min.js" ></script>
	<script src="js/bootstrap.min.js" ></script>
	<script src="js/bootstrap-hover.min.js" ></script>
	<script src="js/jquery.maskedinput.min.js" ></script>
	<script src="js/livevalidation_standalone.js" ></script>
	<script src="js/funciones.js" ></script>
	<script language="javascript">
	  var usuario = new LiveValidation('usuario');
	  var clave = new LiveValidation('clave');
	  
	   jQuery(function($){
		  $("#nTelf").mask("999-9999");
	   });
		//var email = new LiveValidation('nEmail',{onlyOnSubmit: true });
		//var telefono = new LiveValidation('nTelf');
		
		usuario.add(Validate.Length,{minimum:6,maximum:15});
		clave.add(Validate.Length,{minimum:6,maximum:15});
		//email.add(Validate.Email);
		//telefono.add(Validate.Numericality);
		//telefono.add(Validate.Length,{is:8});
	  function checkRecupClave() {
		//console.log($("#frmModal").serialize()+"&guardarGPS=1");
		var d = $("#frmRecup").serialize();
		  if (d == '') {
		    d = "checkRecup=1";
		  } else {
			d = d+"&checkRecup=1"
		  }
		  console.log($("#frmRecup").serialize()+"  "+$('#nUser').val());
		  console.log(d);
		  $.ajax({
			type : 'POST',
			url : 'validation/class.login.php',
			data : d,
			dataType : 'json',
			success: function(data, status, xhr) {
			  var x = data.datos;
			  
				console.log(data.codigo+"  "+data.mensaje+"  "+JSON.stringify(x));
				
				try { if (x.usuario != undefined) {$("#msjnUser").html(x.usuario); $("#alertnUser").show();}else{ $("#alertnUser").hide();}  } catch(e){$("#alertnUser").hide();}finally{}
				
			   if(data.codigo == "OK"){
				   alert("Codigo Enviado");
				   window.location = "<?php echo $url; ?>";
			   } else {
				  alert(data.mensaje); 
			   }
			},
			error: function(xhr, status, error) {
				console.log('code: '+xhr+"  error: "+error);
			}
		 });
	
	  }
	  
		$(document).ready(function() {
		  $("#usuario").focus();
		  
		  $('#usuario').keyup(function(e) {
    		    if (e.which == 13) {  
	              $('#clave').focus();
	            }
    		  });
		  $('#clave').keyup(function(e) {
    		    if (e.which == 13) {  
	              $('#bsesion').click();
	            }
    	  });
		  
		  

			$(document).ajaxStart(function(){
			  $(".bgpreloader").show();
			  $(".gpsload-thecube").show();
			});
	
			$(document).ajaxStop(function(){
			  $(".bgpreloader").hide();
			  $(".gpsload-thecube").hide();
			});
			  
    
		});

$(function(){
    $('#slider div:gt(0)').hide();
    setInterval(function(){
      $('#slider div:first-child').fadeOut(0)
         .next('div').fadeIn(1500)
         .end().appendTo('#slider');}, 3000);
});

</script>
	
  </body>
</html>