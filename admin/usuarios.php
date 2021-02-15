<?php

include_once('modules/header.php');
require_once('validation/class.usuarios.php');
include_once('helpers/helper.utils.php');

$url = "usuarios.php";
$dataForm = array("cedula"=>"", "nombres"=>"", "apellidos"=>"", "foto"=>"", "tipo"=>2, "distribuidor"=>0, "cantidadusuario"=>0, "ciudad"=>0, "estado"=>0, "direccion"=>"", "email"=>"", "celular"=>"", "celular2"=>"", "celular3"=>"", "telefono"=>"", "nivelautorizacion"=>"5", "activo"=>1, "usuario"=>"", "clave"=>"", "rclave"=>"");
$editar = false;
$validID = false;

 if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {$id = htmlspecialchars($_GET['id']);$validID = true;}

if (isset($_POST['actualizar'])) { // ACTUALIZAR USUARIO
    if ($validID) {
		$usuEdit = R::load('usuarios', $id);
		  if (isset($usuEdit) && $usuEdit->id > 1) {
			$errores = validarPOSTUsuario($_POST,$usuEdit);

			 if (!isset($errores) || count($errores) <= 0) {
			  $lastID = R::store($usuEdit);
				if ($lastID > 0) {
				  echo "<script>alert('Usuario Actualizado Correctamente');</script>";
				} else {
				  echo "<script>alert('Hubo un Error al Actualizar al Usuario');</script>";
				}
			  echo "<script>window.location='".$url."';</script>";
			 }
		} else {
		  echo "<script>alert('El usuario con el id ".$id." no se ha encontrado');window.location='".$url."';</script>";
		}
    }
  } else if (isset($_POST['guardar'])) {
	$nUsu = R::dispense('usuarios');
	$errores = validarPOSTUsuario($_POST,$nUsu);

	 if (!isset($errores) || count($errores) <= 0) {
		 $lastID = R::store($nUsu);
	   if ($lastID > 0) {
		 echo "<script>alert('Usuario Agregado Correctamente');</script>";
	   } else {
		 echo "<script>alert('Hubo un Error al Agregar al Usuario');</script>";
	   }
	 }

} else if (isset($_GET['accion']) && !empty($_GET['accion'])) {
  $acc = htmlspecialchars($_GET['accion']);

  if (strcasecmp($acc,"editar") == 0 && $validID) { // EDITAR
    if ($validID) {
		$usuEdit = R::load('usuarios', $id);
		$loginEdit = R::load('usuarios', $id);
		  if ((isset($usuEdit) && $usuEdit->id > 1)&&(isset($loginEdit) && $loginEdit->id > 1)) {
			$editar = true;
			$dataForm = array("cedula"=>$usuEdit->cedula, "nombres"=>$usuEdit->nombres, "apellidos"=>$usuEdit->apellidos, "foto"=>$usuEdit->foto, "tipo"=>$usuEdit->tipo, "distribuidor"=>$usuEdit->distribuidor, "cantidadusuario"=>$usuEdit->cantidadusuario, "ciudad"=>$usuEdit->ciudad, "estado"=>$usuEdit->estado, "direccion"=>$usuEdit->direccion, "email"=>$usuEdit->email, "celular"=>$usuEdit->celular, "celular2"=>$usuEdit->celular2, "celular3"=>$usuEdit->celular3, "telefono"=>$usuEdit->telefono, "nivelautorizacion"=>$usuEdit->nivelautorizacion, "activo"=>$usuEdit->activo, "usuario"=>$loginEdit->usuario);
		  } else {
			echo "<script>alert('El usuario con el id ".$id." no se ha encontrado');window.location='".$url."';</script>";
		  }
	}
  } else if (strcasecmp($acc, "borrar") == 0) { // DESACTIVAR USUARIO
    if ($validID) {
	   $usuEdit = R::load('usuarios', $id);

		if (isset($usuEdit) && $usuEdit->id > 1) {
			if ($usuEdit->activo == 1) {
			  $usuEdit->activo = 0;
			} else {
			  $usuEdit->activo = 1;
			}
		  R::store($usuEdit);
		  echo "<script>window.location='".$url."';</script>";
		} else {
			echo "<script>alert('El usuario con el id ".$id." no se ha encontrado');window.location='".$url."';</script>";
		}
    }
  }

}

?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Usuarios</title>

    <link href="css/bootstrap.min.css" rel="stylesheet"/>
	<link href="css/bootstrap-theme.min.css" rel="stylesheet"/>
	<link href="css/bootstrap-select.min.css" rel="stylesheet"/>
	<link href="css/jquery.dataTables.min.css" rel="stylesheet"/>
	<link href="css/custom.css" rel="stylesheet"/>
	<script type="text/javascript" src="js/jquery-3.1.0.min.js"></script>
	<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/bootstrap-select.js"></script>
	<script type="text/javascript" src="js/dataTables.responsive.min.js"></script>
	<script type="text/javascript" src="js/responsive.bootstrap.min.js"></script>
	<script type="text/javascript" src="js/funciones.js"></script>
	<script>

	  function limpiarForm() {
		  $('#cedula').val('');
		  $('#nombres').val('');
		  $('#apellidos').val('');
		  $('#direccion').val('');
		  cleanCombo('tipo');
		  cleanCombo('estado');
		  cleanCombo('ciudad');
		  cleanCombo('foto');
	  }
	  
	  function getStates() {
		  var id = $('#estado').val();
		  
		  $.ajax({
			type : 'POST',
			url : 'json/util.cities.php',
			data : "idState="+id,
			dataType : 'json',
			success: function(data, status, xhr) {
				//console.log(debugJSON(data));
			  //var x = data.datos;
				if (data.codigo == 'OK') {
				 //cleanCombo('ciudad');
				 var html = '';
				console.log(data.codigo+"  "+data.mensaje+"  ");

				  $.each(data.datos,function(k,v){
					 html += '<option value="'+k+'">'+v+'</option>';
					 console.log('Clave: '+k+'  Valor: '+v);
				  });
				  $('#ciudad').html(html);
				  $("#ciudad").selectpicker('refresh')
				}
			},
			error: function(xhr, status, error) {
				console.log('code: '+debugJSON(xhr)+"  error: "+debugJSON(error));
			}
		 });
	  }
	  
	 /* function limpiarFormModal() {
		  $('#serial').val('');
		  $('#celulargps').val('');
		  $('#diacorte').val('');
		  cleanCombo('marcagps');
		  cleanCombo('modelogps');
	  }*/

	  function saveFormModal() {
		//console.log($("#frmModal").serialize()+"&guardarGPS=1");
		var d = $("#frmModal").serialize();

		  if (d == '') {
			d = "guardarGPS=1";
		  } else {
			d = d+"&guardarGPS=1";
		  }
		  $.ajax({
			type : 'POST',
			url : 'validation/class.usuarios.php',
			data : d,
			dataType : 'json',
			success: function(data, status, xhr) {
				//console.log(debugJSON(data));
			  var x = data.datos;
				console.log(data.codigo+"  "+data.mensaje+"  ");
				try { if (x.serial != undefined) {$("#msjSerial").html(x.serial); $("#alertSerial").show();}else{ $("#alertSerial").hide();}  } catch(e){$("#alertSerial").hide();}finally{}
				try { if (x.celulargps != undefined) {$("#msjCelulargps").html(x.celulargps); $("#alertCelulargps").show();}else{ $("#alertCelulargps").hide();}  } catch(e){$("#alertCelulargps").hide();}finally{}
				try { if (x.diacorte != undefined) {$("#msjDiacorte").html(x.diacorte); $("#alertDiacorte").show();}else{ $("#alertDiacorte").hide();}  } catch(e){$("#alertDiacorte").hide();}finally{}
				try { if (x.marcagps != undefined) {$("#msjMarcagps").html(x.marcagps); $("#alertMarcagps").show();}else{ $("#alertMarcagps").hide();}  } catch(e){$("#alertMarcagps").hide();}finally{}
				try { if (x.modelogps != undefined) {$("#msjModelogps").html(x.modelogps); $("#alertModelogps").show();}else{ $("#alertModelogps").hide();}  } catch(e){$("#alertSerial").hide();}finally{}
				//console.log("Marca: "+x.marcagps+"  Modelo: "+x.modelogps+"  Serial: "+x.serial+"  Celular: "+x.celulargps);
			   if(data.codigo == "OK"){
				   alert("GPS AGREGADO");
				   window.location = "<?php echo $url; ?>";
			   }
			},
			error: function(xhr, status, error) {
				console.log('code: '+debugJSON(xhr)+"  error: "+debugJSON(error));
			}
		 });

	  }
	</script>
  </head>
  <body>

    <div class="container-fluid">
	<div class="row">

	<!-- MODAL -->
<div class="modal fade" tabindex="-1" role="dialog" id="vntModal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">VEHICULOS ASOCIADOS</h4>
      </div>

      <div class="modal-body">
		<form name="frmModal" id="frmModal">
			<div class="row">
			 <div class="col-md-12">
			    <!--<table class="table table-striped table-bordered dt-responsive nowrap" id="tablaAsoc">
				  <thead>
					<tr>
					  <th></th>
					  <th>Serial</th>
					  <th>Descripcion</th>
					  <th>Foto</th>
					  <th>Celular GPS</th>
					</tr>
				  </thead>
				  <tbody>-->
				    <div id="dataVeh"></div>
					<?php 
						/*$vehActivos = R::getAll( 'SELECT 
    book.title AS title, 
    author.name AS author, 
    GROUP_CONCAT(category.name) AS categories FROM book
    JOIN author ON author.id = book.author_id
    LEFT JOIN book_category ON book_category.book_id = book.id
    LEFT JOIN category ON book_category.category_id = category.id 
    GROUP BY book.id
    ' );
    foreach( $books as $book ) {
        echo $book['title'];
        echo $book['author'];
        echo $book['categories'];
    }*/
					  /* $vehActivos = R::findAll( 'vehiculos');
					    foreach ($vehActivos as $vAct) {
							echo "<tr>";
								echo "<td></td>";
								echo "<td></td>";
								echo "<td>".$vAct->descripcion."</td>";
								echo "<td><center><img width='32px' heigth='32px' src='images/veh/".$vAct->foto."'/></center></td>";
								echo "<td></td>";
							echo "</tr>";
						}*/
					?>
				  <!--</tbody>
				</table>-->
			 </div>
			</div><!-- FIN 1RA FILA MODAL -->
			
		</form>

      </div><!--  FIN MODAL BODY  -->

      <div class="modal-footer">
		<button type="button" class="btn btn-primary" id="btnSaveGPS" name="btnSaveGPS" onclick="saveFormModal();">Guardar</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
	<!-- FIN MODAL -->


		<div class="col-md-12">
		  <div class="page-header">
		    <div class="row">
			  <div class="col-md-4"><a href="admin.php" class="btn btn-lg btn-info"><b>Ir al Menú</b></a></div>
		      <div class="col-md-4"><center><h1><b>Usuarios</b></h1></center></div>
			  <div class="col-md-4">&nbsp;</div>
			</div>
		  </div><br/>


			<form class="form-inline" method="POST">
			<div class="row"><!--  FILA 1 -->
			 <div class="col-md-3">
				<div class="form-group">
					<label for="cedula" class="control-label">Cedula:</label>
					<input type="text" maxlength="15" class="form-control" placeholder="SIN CEDULA" id="cedula" name="cedula" value="<?php echo (isset($dataForm['cedula'])?$dataForm['cedula']:''); ?>" />
				  <?php if (isset($errores['cedula']) && !empty($errores['cedula'])) { ?>
				   <div class="alert alert-danger alert-dismissable" role="alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error:&nbsp;</strong> <?php echo $errores['cedula']; ?>.
				   </div>
				  <?php } ?>
				</div>&nbsp;&nbsp;&nbsp;
			 </div>
			  <div class="col-md-3">
				<div class="form-group">
				  <label for="nombres">Nombres:</label>
				  <input type="text" maxlength="250" class="form-control" placeholder="" id="nombres" name="nombres" value="<?php echo (isset($dataForm['nombres'])?$dataForm['nombres']:''); ?>" />
				  <?php if (isset($errores['nombres']) && !empty($errores['nombres'])) { ?>
				   <div class="alert alert-danger alert-dismissable" role="alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error:&nbsp;</strong> <?php echo $errores['nombres']; ?>.
				   </div>
				  <?php } ?>
				</div>
			   </div>
			  <div class="col-md-3">
				<div class="form-group">
				  <label for="apellidos">Apellidos:</label>
				  <input type="text" maxlength="250" class="form-control" placeholder="" id="apellidos" name="apellidos" value="<?php echo (isset($dataForm['apellidos'])?$dataForm['apellidos']:''); ?>" />
				  <?php if (isset($errores['apellidos']) && !empty($errores['apellidos'])) { ?>
				   <div class="alert alert-danger alert-dismissable" role="alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error:&nbsp;</strong> <?php echo $errores['apellidos']; ?>.
				   </div>
				  <?php } ?>
				</div>
			   </div>

			 <div class="col-md-3">
				<div class="form-group">
				  <label for="tipo">Tipo:</label>
				  <div class="input-group">
					<select class="form-control selectpicker" data-live-search="true" title="Seleccione un tipo" id="tipo" name="tipo" required="required">
						  <option value="2" selected="selected">Cliente</option>
						  <option value="1">Distribuidor</option>
					</select>
				  </div>
				  <?php if (isset($errores['tipo']) && !empty($errores['tipo'])) { ?>
				   <div class="alert alert-danger alert-dismissable" role="alert">
				    <button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error:&nbsp;</strong> <?php echo $errores['tipo']; ?>.
				   </div>
				  <?php } ?>
				</div>&nbsp;&nbsp;&nbsp;
			  </div>
			</div><br/><!--  FIN FILA 1 -->
			<div class="row"><!--  FILA 2 -->
			 <div class="col-md-3">
			    <div class="form-group">
				 <label for="estado">Estado:</label>
				  <div class="input-group">
					<select class="form-control selectpicker" data-live-search="true" title="Seleccione el estado" id="estado" name="estado" onchange="getStates();" required="required">
						<?php
							try {
								
								$lestado = R::findAll('estados'); 
									if (isset($lestado) && !empty($lestado)) {
									  foreach ($lestado as $est) {
										echo '<option value="'.$est->id.'">'.$est->estado.'</option>';
									  }
									}
							} catch(PDOException $e) {} finally {}
						 ?>
					</select>
				  </div>
				  <?php if (isset($errores['estado']) && !empty($errores['estado'])) { ?>
				   <div class="alert alert-danger alert-dismissable" role="alert">
				    <button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error:&nbsp;</strong> <?php echo $errores['estado']; ?>.
				   </div>
				  <?php } ?>
				</div>
			 </div>
			 <div class="col-md-3">
			    <div class="form-group">
				 <label for="ciudad">Ciudad:</label>
				  <div class="input-group">
					<select class="form-control selectpicker" data-live-search="true" title="Seleccione la ciudad" id="ciudad" name="ciudad" required="required">
						<?php
							try {
								if (isset($dataForm['ciudad']) && is_numeric($dataForm['ciudad']) && $dataForm['ciudad'] > 0) {
									$lciudad = R::find('ciudades', 'id = ?', [$dataForm['ciudad']]); 
									
									 if (isset($lciudad) && count($lciudad) > 0) {
									  foreach ($lciudad as $ciu) {
									    echo '<option value="'.$ciu->id.'" selected="selected">'.$ciu->ciudad.'</option>';
									  }
									 }
							    }
							} catch(PDOException $e) {} finally {}
						 ?>
					</select>
				  </div>
				  <?php if (isset($errores['ciudad']) && !empty($errores['ciudad'])) { ?>
				   <div class="alert alert-danger alert-dismissable" role="alert">
				    <button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error:&nbsp;</strong> <?php echo $errores['ciudad']; ?>.
				   </div>
				  <?php } ?>
				</div>
			 </div>
			 <div class="col-md-3">
				<div class="form-group">
					<label for="tipo">Foto Usuario:</label>
					<select class="form-control selectpicker" data-live-search="true" title="Seleccione una imagen" id="foto" name="foto" required="required">
						<?php
							$path = "images/usuarios/";
							$files = scandir($path);
								foreach ($files as $value) {
									
									$ext = pathinfo($value, PATHINFO_EXTENSION);
									
									if (($value != "." && $value != "..") && (strncmp($ext,"png",3) == 0 || strncmp($ext,"gif",3) == 0 ||strncmp($ext,"jpg",3) == 0)) {
										$txt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $value);
										$img = "<img width='32px' heigth='32px' src='".$path.$value."'/>&nbsp;&nbsp;&nbsp;<label>".strtoupper($txt)."</label>";
										//echo $txt."<br/>";
										echo '<option value="'.$value.'" data-content="'.$img.'">'.strtoupper($txt).'</option>';
									}
								}
						?>
					</select>
				</div>
			 </div>
			 <div class="col-md-3">
				<div class="form-group">
					<label for="direccion" class="control-label">Dirección:</label>
					<input type="text" maxlength="250" size="30" class="form-control" id="direccion" name="direccion" value="<?php echo (isset($dataForm['direccion'])?$dataForm['direccion']:''); ?>" />
				</div>&nbsp;&nbsp;&nbsp;
			 </div>
			</div><br/><!--  FIN FILA 2 -->
			<div class="row"><!--  FILA 3 -->
			 <div class="col-md-3">
				<div class="form-group">
					<label for="email" class="control-label">Email:</label>
					<input type="text" maxlength="150" class="form-control" placeholder="" id="email" name="email" value="<?php echo (isset($dataForm['email'])?$dataForm['email']:''); ?>" />
				  <?php if (isset($errores['email']) && !empty($errores['email'])) { ?>
				   <div class="alert alert-danger alert-dismissable" role="alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error:&nbsp;</strong> <?php echo $errores['email']; ?>.
				   </div>
				  <?php } ?>
				</div>&nbsp;&nbsp;&nbsp;
			 </div>
			  <div class="col-md-3">
				<div class="form-group">
				  <label for="celular">Celular 1:</label>
				  <input type="number" maxlength="15" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)"
					min="01111111111" max = "09999999999" class="form-control" placeholder="0000000000" id="celular" name="celular" value="<?php echo (isset($dataForm['celular'])?$dataForm['celular']:''); ?>" />
				  <?php if (isset($errores['celular']) && !empty($errores['celular'])) { ?>
				   <div class="alert alert-danger alert-dismissable" role="alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error:&nbsp;</strong> <?php echo $errores['celular']; ?>.
				   </div>
				  <?php } ?>
				</div>
			   </div>
			  <div class="col-md-3">
				<div class="form-group">
				  <label for="celular2">Celular 2:</label>
				  <input type="number" maxlength="15" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)"
					min="01111111111" max = "09999999999" class="form-control" placeholder="0000000000" id="celular2" name="celular2" value="<?php echo (isset($dataForm['celular2'])?$dataForm['celular2']:''); ?>" />
				  <?php if (isset($errores['celular2']) && !empty($errores['celular2'])) { ?>
				   <div class="alert alert-danger alert-dismissable" role="alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error:&nbsp;</strong> <?php echo $errores['celular2']; ?>.
				   </div>
				  <?php } ?>
				</div>
			   </div>
			  <div class="col-md-3">
				<div class="form-group">
				  <label for="celular3">Celular 3:</label>
				  <input type="number" maxlength="15" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)"
					min="01111111111" max = "09999999999" class="form-control" placeholder="0000000000" id="celular3" name="celular3" value="<?php echo (isset($dataForm['celular3'])?$dataForm['celular3']:''); ?>" />
				  <?php if (isset($errores['celular3']) && !empty($errores['celular3'])) { ?>
				   <div class="alert alert-danger alert-dismissable" role="alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error:&nbsp;</strong> <?php echo $errores['celular3']; ?>.
				   </div>
				  <?php } ?>
				</div>
			   </div>
			</div><br/><!--  FIN FILA 3 -->
			<div class="row"><!--  FILA 4 -->
			 <div class="col-md-3">
				<div class="form-group">
					<label for="usuario" class="control-label">Usuario:</label>
					<input type="text" maxlength="18" class="form-control" placeholder="" id="usuario" name="usuario" value="<?php echo (isset($dataForm['usuario'])?$dataForm['usuario']:''); ?>" />
				  <?php if (isset($errores['usuario']) && !empty($errores['usuario'])) { ?>
				   <div class="alert alert-danger alert-dismissable" role="alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error:&nbsp;</strong> <?php echo $errores['usuario']; ?>.
				   </div>
				  <?php } ?>
				</div>
			 </div>
			  <div class="col-md-3">
				<div class="form-group">
				  <label for="clave">Contraseña:</label>
				  <input type="password" maxlength="18" class="form-control" placeholder="" id="clave" name="clave" />
				  <?php if (isset($errores['clave']) && !empty($errores['clave'])) { ?>
				   <div class="alert alert-danger alert-dismissable" role="alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error:&nbsp;</strong> <?php echo $errores['clave']; ?>.
				   </div>
				  <?php } ?>
				</div>
			   </div>
			  <div class="col-md-4">
				<div class="form-group">
				  <label for="rclave">Repetir Contraseña:</label>
				  <input type="password" maxlength="18" class="form-control" placeholder="" id="rclave" name="rclave" />
				  <?php if (isset($errores['rclave']) && !empty($errores['rclave'])) { ?>
				   <div class="alert alert-danger alert-dismissable" role="alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error:&nbsp;</strong> <?php echo $errores['rclave']; ?>.
				   </div>
				  <?php } ?>
				</div>
			   </div>
			   <div class="col-md-2">&nbsp;</div>
			</div><!--  FIN FILA 4 -->
			   <br/><br/><br/>
				<center>
				<div class="form-group">
				   <?php if ($editar) { ?>
					<button type="submit" class="btn btn-success" name="actualizar" id="actualizar">Actualizar</button>
					<a href="<?php echo $url; ?>" class="btn btn-danger" name="cancelar" id="cancelar">Cancelar</a>
				   <?php } else { ?>
					<button type="submit" class="btn btn-success" name="guardar" id="guardar">Guardar</button>
					<button type="button" class="btn btn-primary" name="limpiar" id="limpiar" onclick="limpiarForm();">Limpiar</button>
				   <?php } ?>
				</div>
				</center>
			</form>


		</div><!-- col-md-12 -->
	</div><!-- row -->
	<div class="row">
		<div class="col-md-12">
			<table class="table table-striped table-bordered dt-responsive nowrap" id="tablausu">
				<thead>
					<tr>
						<th>ID</th>
						<th>Foto</th>
						<th>Usuario</th>
						<th>Nombres</th>
						<th>Apellidos</th>
						<th>Email</th>
						<th>Celular 1</th>
						<th>Asoc. Veh.</th>
						<th>Editar</th>
						<th>Estado</th>
					</tr>
				</thead>
				<tbody>
				
				
				<?php

						$Users = R::findAll('usuarios');

					    foreach ($Users as $u) {
							 //unset($gps);
							$nick = R::findOne('login', "id = ".$u->id);
							//$city = R::findOne('ciudades', "id = ".$u->ciudad);
							//$state = R::findOne('estados', "id = ".$u->estado);
							$uLogin = "<b> - </b>";
							//$uCity = "<b> - </b>";
							//$uState = "<b> - </b>";
							$fecha = date_format(date_create($u->fecharegistro), 'd/m/Y');
							 if (isset($nick->usuario) && strlen($nick->usuario) > 0) {$uLogin = $nick->usuario;}
							 //if (isset($city->ciudad) && strlen($city->ciudad) > 0) {$uCity = $city->ciudad;}
							 //if (isset($state->estado) && strlen($state->estado) > 0) {$uState = $state->estado;}
							 
							echo "<tr>";
							  echo "<td>".$u->id."</td>";
							  echo "<td><center><a href='images/usuarios/".$u->foto."' class='preview'>
											<img width='32px' heigth='32px' src='images/usuarios/".$u->foto."'/>
										</center></a></td>";
							  echo "<td><center>".$uLogin."</center></td>";
							  echo '<td><p class="animado" ><span data-tooltip= "'.$u->nombres.'">'.acortarTexto($u->nombres,15).'</span></p></td>';
							  echo '<td><p class="animado" ><span data-tooltip= "'.$u->apellidos.'">'.acortarTexto($u->apellidos,15).'</span></p></td>';
							  //echo "<td>".$u->nombres."</td>";
							  //echo "<td>".$u->apellidos."</td>";
							  //echo "<td>".$uCity."</td>";
							  //echo "<td>".$uState."</td>";
							  echo '<td><p class="animado" ><span data-tooltip= "'.$u->email.'">'.acortarTexto($u->email,15).'</span></p></td>';
							  //echo "<td>".$u->email."</td>";
							  echo "<td>".$u->celular."</td>";
							  //echo "<td>".$fecha."</td>";
							    /*if ($uLogin > 0) {
								  echo "<td><center><a href='".$url."?accion=nogps&id=".$k->id."'><img width='32px' heigth='32px' src='images/nogps.png'/></a></center></td>";
								} else {
								  echo "<td>&nbsp;</td>";
								}*/
							  echo "<td><center><a class='listaAsociacion' style='cursor:pointer;' data-toggle='modal' data-id='".$u->id."'><img width='32px' heigth='32px' src='images/listVeh.png'/></a></center></td>";
							  /*echo "<td><center><p style='cursor:pointer;' data-toggle='modal' data-target='#vntModal'><img width='32px' heigth='32px' src='images/listVeh.png'/></p></center></td>";*/
							  echo "<td><center><a href='".$url."?accion=editar&id=".$u->id."'><img width='32px' heigth='32px' src='images/edit.png'/></a></center></td>";
							    if ($u->activo == 0) {
								  echo "<td><center><a href='".$url."?accion=borrar&id=".$u->id."' onclick='return checkAct();'>
								  <img width='32px' heigth='32px' src='images/desactivado.png'/></a></center></td>";
								} else {
								  echo "<td><center><a href='".$url."?accion=borrar&id=".$u->id."' onclick='return checkDel();'>
								  <img width='32px' heigth='32px' src='images/activado.png'/></a></center></td>";
								}
							echo "</tr>";
						}
				 ?>
				</tbody>
			</table>
		</div>
	</div><br/><br/>
</div>
<script>
 var actClicListVeh = false; 
 
$(document).ready(function(){
 
 //function drawTable() {
	 
	imagePreview();
    $('#tablausu').dataTable( {
       "language": {
         "url": "js/spanish.json"
       }
    });
    $('#tablaAsoc').dataTable( {
        columnDefs: [ {
            orderable: false,
            className: 'select-checkbox',
            targets:   0
        } ],
       "language": {
         "url": "js/spanish.json"
       }
    });
	
 //}
	/*
	    $('#example').DataTable( {

        select: {
            style:    'os',
            selector: 'td:first-child'
        },
        order: [[ 1, 'asc' ]]
    } );*/

	
});

function checkDel() {
	if (!confirm("¿Esta seguro que desea Desactivar a este Usuario?")) {
		return false;
	}
  return true;
}

function checkAct() {
	if (!confirm("¿Esta seguro que desea Activar a este Usuario?")) {
		return false;
	}
  return true;
}

$(".listaAsociacion").click(function() { // Click to only happen on announce links
  var idUsuario = $(this).data('id');
  
  
   if (!actClicListVeh && idUsuario > 0) {
	   //var datos = '{"accion": "listaUser","idUser": '+idUsuario+'}]';
	   //var datos = "accion": "listaUser&idUser": '+idUser+';
	   actClicListVeh = true;
	   
	   
	   $.ajax({
		  url : 'json/util.vehiculos.php', 
		  //data : { contenidoMensaje : json},
		  data : { accion: "listaUser", idUser: idUsuario},
		  type : 'POST',
		  dataType : 'json'
		}).done(function(json) {
			var tablaVeh = "";
			var btnAct = '<center><img width="32px" heigth="32px" src="images/activado.png" /></center>';
			var btnDes = '<center><img width="32px" heigth="32px" src="images/desactivado.png" /></center>';
			
			console.log(json);
			actClicListVeh = false;
			
			tablaVeh = tablaVeh +  "<table class='table table-striped table-bordered dt-responsive nowrap' id='tablaAsoc'>";
			  tablaVeh = tablaVeh + "<thead><tr>";
			  tablaVeh = tablaVeh +  "<th></th>";
				tablaVeh = tablaVeh +  "<th>Serial</th>";
				tablaVeh = tablaVeh +  "<th>Descripcion</th>";
				tablaVeh = tablaVeh +  "<th>Foto</th>";
				tablaVeh = tablaVeh +  "<th>Celular GPS</th>";
				tablaVeh = tablaVeh +  "<th>Status GPS</th>";
				tablaVeh = tablaVeh +  "<th>Status Veh</th>";
				tablaVeh = tablaVeh +  "</tr></thead><tbody>";
				
			$.each(json,function(k, val){
				//for (var veh in json) {
			  tablaVeh = tablaVeh +  "<tr>";
			    tablaVeh = tablaVeh +  "<td><center><input type='checkbox' checked='checked' /></center></td>";
				tablaVeh = tablaVeh +  "<td>"+val.serial+"</td>";
				tablaVeh = tablaVeh +  "<td>"+val.descripcion+"</td>";
				tablaVeh = tablaVeh +  "<td><center><img width='32px' heigth='32px' src='images/veh/"+val.foto+"'/></center></td>";
				tablaVeh = tablaVeh +  "<td>"+val.celulargps+"</td>";
				  if (val.actGPS == "1") {
					tablaVeh = tablaVeh +  "<td>"+btnAct+"</td>";  
				  } else {
					tablaVeh = tablaVeh +  "<td>"+btnDes+"</td>";  
				  }
				  if (val.actVeh == "1") {
					tablaVeh = tablaVeh +  "<td>"+btnAct+"</td>";  
				  } else {
					tablaVeh = tablaVeh +  "<td>"+btnDes+"</td>";  
				  }
				
				//tablaVeh = tablaVeh +  "<td>"+val.actVeh+"</td>";
			    /*tablaVeh = tablaVeh +  "<td>"+veh.serial+"</td>";
				tablaVeh = tablaVeh +  "<td>"+veh.descripcion+"</td>";
				tablaVeh = tablaVeh +  "<td>"+veh.placa+"</td>";
				tablaVeh = tablaVeh +  "<td>"+veh.celulargps+"</td>";*/
			  tablaVeh = tablaVeh +  "<tr>";
			});
			
			tablaVeh = tablaVeh +  "</tbody>";
			tablaVeh = tablaVeh +  "</table>";
			
			//console.log(tablaVeh);
			/*for each (var item in json) {
			  sum += item;
			}*/
			
			//json = JSON.parse(json);
			/*if (json[0].Codigo == 0) {
			  alert('Mensaje Enviado Correctamente');
			} else {
			  alert('Error al enviar el Comando. '+json[0].Mensaje);
			}*/
		  $('#dataVeh').html(tablaVeh);
		  //drawTable();
		  $('#vntModal').modal('show');
		  console.log("Datos: "+JSON.stringify(json));
		  
		}).fail(function(xhr, status) {
		  console.log('Hubo un error al enviar el mensaje. '+status+'  '+xhr.responseText);
		  actClicListVeh = false;
		});
		 
   }
     
     
});
</script>
  <?php R::close(); ?>
  </body>
</html>
