<?php

include_once('modules/header.php');
require_once('validation/class.vehiculos.php');
include_once('helpers/helper.utils.php');
//$listTipo = $GLOBALS['listas']['vehiculo_tipo'];
 $valTipo = array(
	'750' => '750',
	'350' => '350',
	'AMBULANCIA' => 'AMBULANCIA',
	'CARGA' => 'CARGA',
	'COUPE' => 'COUPE',
	'MOTO' => 'MOTO',
	'NPR' => 'NPR',
	'PARTICULAR' => 'PARTICULAR',
	'PICKUP' => 'PICKUP',
	'SEDAN' => 'SEDAN'
 );
 
$url = "vehiculos.php";
$dataForm = array("gps"=>0, "tipo"=>"", "foto"=>"", "placa"=>"", "descripcion"=>"", "limite"=>0);
$editar = false;
$validID = false;

 if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {$id = htmlspecialchars($_GET['id']);$validID = true;}
 
if (isset($_POST['actualizar'])) { // ACTUALIZAR VEHICULO
    if ($validID) {
		$vehEdit = R::load('vehiculos', $id);
		  if (isset($vehEdit) && $vehEdit->id > 0) {
			$errores = validarPOSTVehiculo($_POST,$vehEdit);

			 if (!isset($errores) || count($errores) <= 0) {
			  $lastID = R::store($vehEdit);
				if ($lastID > 0) {
				  echo "<script>alert('Vehiculo Actualizado Correctamente');</script>";
				  echo "<script>window.location='".$url."';</script>";
				} else {
				  echo "<script>alert('Hubo un Error al Actualizar el Vehiculo');</script>";
				}
			 } else {
			   print_r($errores); 
			 }
		} else {
		  echo "<script>alert('El vehiculo con el id ".$id." no se ha encontrado');window.location='".$url."';</script>";
		}
    }
  } else if (isset($_POST['guardar'])) {
	$nVeh = R::dispense('vehiculos');
	$errores = validarPOSTVehiculo($_POST,$nVeh);

	 if (!isset($errores) || count($errores) <= 0) {
		 $lastID = R::store($nVeh);
	   if ($lastID > 0) {
		 echo "<script>alert('Vehiculo Agregado Correctamente');</script>";
	   } else {
		 echo "<script>alert('Hubo un Error al Agregar el Vehiculo');</script>";
	   }
	 }

} else if (isset($_GET['accion']) && !empty($_GET['accion'])) {
  $acc = htmlspecialchars($_GET['accion']);
  
  if (strcasecmp($acc,"editar") == 0 && $validID) { // EDITAR
    if ($validID) {
		$vehEdit = R::load('vehiculos', $id);
		  if (isset($vehEdit) && $vehEdit->id > 0) {
			$editar = true;
			$dataForm = array("gps"=>$vehEdit->idgps, "tipo"=>$vehEdit->tipo, "foto"=>$vehEdit->foto, "placa"=>$vehEdit->placa, "descripcion"=>$vehEdit->descripcion, "limite"=>$vehEdit->limitevel);
		  } else {
			echo "<script>alert('El vehiculo con el id ".$id." no se ha encontrado');window.location='".$url."';</script>";
		  }
	}
  } else if (strcasecmp($acc, "nogps") == 0) { // DESACTIVAR GPS
    if ($validID) {
		$vehEdit = R::load('vehiculos', $id);
		if (isset($vehEdit) && $vehEdit->id > 0) {
		  $vehEdit->idgps = 0;
		  R::store($vehEdit);
		  echo "<script>window.location='".$url."';</script>";
		} else {
		  echo "<script>alert('El vehiculo con el id ".$id." no se ha encontrado');window.location='".$url."';</script>";
		}
    }
  } else if (strcasecmp($acc, "borrar") == 0) { // DESACTIVAR VEHICULO
    if ($validID) {
	   $vehEdit = R::load('vehiculos', $id);
	   
		if (isset($vehEdit) && $vehEdit->id > 0) {
			if ($vehEdit->activo == 1) {
			  $vehEdit->activo = 0;
			} else {
			  $vehEdit->activo = 1;
			}
		  R::store($vehEdit);
		  echo "<script>window.location='".$url."';</script>";
		} else {
			echo "<script>alert('El vehiculo con el id ".$id." no se ha encontrado');window.location='".$url."';</script>";
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
    <title>Vehiculos</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/bootstrap-theme.min.css" rel="stylesheet">
	<link href="css/bootstrap-select.min.css" rel="stylesheet">
	<link href="css/jquery.dataTables.min.css" rel="stylesheet">
	<link href="css/select.dataTables.min.css" rel="stylesheet">
	<link href="css/custom.css" rel="stylesheet"/>
	<script type="text/javascript" src="js/jquery-3.1.0.min.js"></script>
	<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/bootstrap-select.js"></script>
	<script type="text/javascript" src="js/dataTables.responsive.min.js"></script>
	<script type="text/javascript" src="js/responsive.bootstrap.min.js"></script>
	<script type="text/javascript" src="js/dataTables.select.min.js"></script>
	<script type="text/javascript" src="js/funciones.js"></script>
	
	<script>

	  function limpiarForm() {
		  $('#placa').val('');
		  $('#limite').val('');
		  $('#descripcion').val('');
		  cleanCombo('tipo');
		  cleanCombo('gps');
		  cleanCombo('foto');
	  }
	  
	  function limpiarFormModal() {
		  $('#serial').val('');
		  $('#celulargps').val('');
		  $('#diacorte').val('');
		  cleanCombo('marcagps');
		  cleanCombo('modelogps');
	  }
	  
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
			url : 'validation/class.gps.php',
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
        <h4 class="modal-title">AGREGAR GPS</h4>
      </div>
	  
      <div class="modal-body">
		<form name="frmModal" id="frmModal">
			<div class="row">
			 <div class="col-md-6">
				<div class="form-group">
					<label for="serial" class="control-label">Serial:</label>
					<input type="number" maxlength="15" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)"
					min = "000000000000000" max = "999999999999999" class="form-control" placeholder="000000000000000" id="serial" name="serial" value="" />
				  
				   <div class="alert alert-danger alert-dismissable" id="alertSerial" style="display:none;" role="alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error:&nbsp;</strong> <div id="msjSerial"></div>.
				   </div>
				  
				</div>&nbsp;&nbsp;&nbsp;
			 </div>
			 <div class="col-md-4">
				<div class="form-group">
					<label for="celulargps" class="control-label">Nro Celular:</label>
					<input type="number" maxlength="15" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)"
					min="01111111111" max = "09999999999" class="form-control" placeholder="0412000000" id="celulargps" name="celulargps" value="" />

				   <div class="alert alert-danger alert-dismissable" id="alertCelulargps" style="display:none;"  role="alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error:&nbsp;</strong> <div id="msjCelulargps"></div>.
				   </div>

				</div>&nbsp;&nbsp;&nbsp;
			 </div>
			 <div class="col-md-2">
				<div class="form-group">
					<label for="diacorte" class="control-label">Dia Corte:</label>
					<input type="number" maxlength="2" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)"
					min = "1" max = "30" class="form-control" placeholder="0" id="diacorte" name="diacorte" value="15" />
				   <div class="alert alert-danger alert-dismissable" id="alertDiacorte" style="display:none;" role="alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error:&nbsp;</strong> <div id="msjDiacorte"></div>.
				   </div>
				</div>&nbsp;&nbsp;&nbsp;
			 </div>
			</div><!-- FIN 1RA FILA MODAL -->
			
		  
			<div class="row">
			 <div class="col-md-6">
				<div class="form-group">
					<label for="marcagps" class="control-label">Marca del GPS:</label>
					<select class="form-control selectpicker" data-live-search="true" title="Seleccione una marca" id="marcagps" name="marcagps" required="required">
						<?php
							try {
							 $marcagps = R::findAll('marcagps');
							 
							   foreach ($marcagps as $mgps) {
								   echo '<option value="'.$mgps->id.'">'.$mgps->nombre.'</option>';
							   }
							} catch(PDOException $e) {} finally {}
						 ?>
					</select>

				   <div class="alert alert-danger alert-dismissable" id="alertMarcagps" style="display:none;" role="alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error:&nbsp;</strong> <div id="msjMarcagps"></div>.
				   </div>
				</div>&nbsp;&nbsp;&nbsp;
			 </div>
			 <div class="col-md-6">
				<div class="form-group">
					<label for="modelogps" class="control-label">Modelo del GPS:</label>
					<select class="form-control selectpicker" data-live-search="true" title="Seleccione un modelo" id="modelogps" name="modelogps" required="required">
						<?php
							try {
							 $modelogps = R::findAll('modelogps');
							 
							   foreach ($modelogps as $mogps) {
								   echo '<option value="'.$mogps->id.'">'.$mogps->nombre.'</option>';
							   }
							} catch(PDOException $e) {} finally {}
						 ?>
					</select>
				   <div class="alert alert-danger alert-dismissable" id="alertModelogps" style="display:none;" role="alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error:&nbsp;</strong> <div id="msjModelogps"></div>.
				   </div>
				</div>&nbsp;&nbsp;&nbsp;
			 </div>
			</div><!-- FIN 2DA FILA MODAL -->
		</form>
			 
      </div><!--  FIN MODAL BODY  -->
	  
      <div class="modal-footer">
		<button type="button" class="btn btn-primary" id="btnSaveGPS" name="btnSaveGPS" onclick="saveFormModal();">Guardar</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiarFormModal();">Cancelar</button>
      </div>
	  
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
	<!-- FIN MODAL -->
	

		<div class="col-md-12">
		  <div class="page-header">
		    <div class="row">
			  <div class="col-md-4"><a href="admin.php" class="btn btn-lg btn-info"><b>Ir al Menú</b></a></div>
		      <div class="col-md-4"><center><h1><b>Vehiculos</b></h1></center></div>
			  <div class="col-md-4">&nbsp;</div>
			</div>
		  </div><br/>


			<form class="form-inline" method="POST">
			<div class="row">
			 <div class="col-md-3">
				<div class="form-group">
					<label for="placa" class="control-label">Placa:</label>
					<input type="text" maxlength="10" class="form-control" placeholder="SIN PLACA" id="placa" name="placa" value="<?php echo (isset($dataForm['placa'])?$dataForm['placa']:''); ?>" />
				  <?php if (isset($errores['placa']) && !empty($errores['placa'])) { ?>
				   <div class="alert alert-danger alert-dismissable" role="alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error:&nbsp;</strong> <?php echo $errores['placa']; ?>.
				   </div>
				  <?php } ?>
				</div>&nbsp;&nbsp;&nbsp;
			 </div>
			  <div class="col-md-5">
				<div class="form-group">
				  <label for="gps">IMEI GPS:</label>
				  <div class="input-group">
					<select class="form-control selectpicker" data-live-search="true" title="Seleccione un gps" id="gps" name="gps" required="required">
						<?php
							try {
							 $selectedCheck = false;
							 $valid = false;
							
							if (isset($dataForm['gps']) && !empty($dataForm['gps'])) {
								if (is_numeric($dataForm['gps']) && $dataForm['gps'] > 0) {
								  $valid = true;
								}
							}
							 $gps_activo = R::findAll('gps_activo');

              /*  if (isset($dataForm['placa']) && !empty($dataForm['placa'])) {
                  echo '<option value="'.$gps->id.'">'.$gps->serial.'</option>';
                }*/
							  if (isset($gps_activo) && !empty($gps_activo)) {
							     foreach ($gps_activo as $gps) {
									if ($valid && strcmp($dataForm['gps'], $gps->id) == 0) {
									  $selectedCheck = true;
									  echo '<option value="'.$gps->id.'" selected="selected">'.$gps->serial.'</option>';
									} else {
								      echo '<option value="'.$gps->id.'">'.$gps->serial.'</option>';
									}
							     }
							  }
							   
							   if ($valid && !$selectedCheck) {
								  $gpsSel  = R::findOne( 'gps', 'id = ?', [ $dataForm['gps'] ]);
									if (isset($gpsSel) && !empty($gpsSel) && $gpsSel != null) {
									  echo '<option value="'.$gpsSel->id.'" selected="selected">'.$gpsSel->serial.'</option>';  
									}							   
							   }
							} catch(PDOException $e) {

							} finally {}
						 ?>
					</select>
					<span class="input-group-btn">
						<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#vntModal"><span class="glyphicon glyphicon-plus"></span></button>
					</span>
				  </div>
				  <?php if (isset($errores['gps']) && !empty($errores['gps'])) { ?>
				   <div class="alert alert-danger alert-dismissable" role="alert">
				    <button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error:&nbsp;</strong> <?php echo $errores['gps']; ?>.
				   </div>
				  <?php } ?>
				</div>
			 </div>
			 <div class="col-md-4">
				<div class="form-group">
				  <label for="tipo">Tipo:</label>
				  <div class="input-group">
					<select class="form-control selectpicker" data-live-search="true" title="Seleccione un tipo" id="tipo" name="tipo" required="required">
						<?php
							$selectedCheck = false;
							 $valid = false;
							
							if (isset($dataForm['tipo']) && !empty($dataForm['tipo'])) {
								if (strlen($dataForm['tipo']) > 0 && strlen($dataForm['tipo']) < 180) {
								  $valid = true;
								}
							}
							
						   if (isset($valTipo) && count($valTipo) > 0) {
						      foreach($valTipo as $k=>$v) {
								  if ($valid && strcmp($dataForm['tipo'], $k) == 0) {
									echo '<option value="'.$k.'" selected="selected">'.$v.'</option>';  
								  } else {
									echo '<option value="'.$k.'">'.$v.'</option>';
								  }
						      }
						   }
						   
						?>
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
			</div>
				<br/><br/><br/>
			<div class="row">
			 <div class="col-md-3">
				<div class="form-group">
					<label for="limite" class="control-label">Limite Velocidad:</label>
					<input type="number" class="form-control" placeholder="80" value="<?php echo (isset($dataForm['limite'])?$dataForm['limite']:0); ?>" id="limite" name="limite" />
				</div>
			 </div>
			 <div class="col-md-5">
				<div class="form-group">
					<label for="foto">Foto Vehiculo:</label>
					<select class="form-control selectpicker" data-live-search="true" title="Seleccione una imagen" id="foto" name="foto" required="required">
						<?php
						  try {
							  
							$path = "images/veh/";
							$files = scandir($path);
							$selectedCheck = false;
							$valid = false;
							
							if (isset($dataForm['foto']) && !empty($dataForm['foto'])) {
								if (strlen($dataForm['foto']) > 0 && strlen($dataForm['foto']) < 255) {
								  $valid = true;
								}
							}
							
								foreach ($files as $value) {
									if ($value != "." && $value != "..") {
										$txt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $value);
										$img = "<img width='32px' heigth='32px' src='images/veh/".
											$value."'/>&nbsp;&nbsp;&nbsp;<label>".strtoupper($txt)."</label>";
										//echo $txt."<br/>";
										
										if ($valid && strcmp($dataForm['foto'],$value) == 0) {
										  $selectedCheck = true;
										  echo '<option value="'.$value.'" data-content="'.$img.'" selected="selected">'.strtoupper($txt).'</option>';
										} else {
										  echo '<option value="'.$value.'" data-content="'.$img.'">'.strtoupper($txt).'</option>';
										}
									}
								}
								
								if ($valid && !$selectedCheck) {
								   $txt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $dataForm['foto']);
								   $img = "<img width='32px' heigth='32px' src='".$path.
								   $dataForm['foto']."'/>&nbsp;&nbsp;&nbsp;<label>".strtoupper($txt)."</label>";
									 echo '<option value="'.$dataForm['foto'].'" data-content="'.$img.'" selected="selected">'.strtoupper($txt).'</option>';
								}
								
							  
							} catch(PDOException $e) {

							} finally {}
						?>
					</select>
				</div>
			 </div>
			 <div class="col-md-4">
				<div class="form-group">
					<label for="descripcion" class="control-label">Descripción:</label>
					<input type="text" maxlength="250" size="30" class="form-control" id="descripcion" name="descripcion" value="<?php echo (isset($dataForm['descripcion'])?$dataForm['descripcion']:''); ?>" />
				</div>&nbsp;&nbsp;&nbsp;
			 </div>
			</div>
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
			<table class="table table-striped table-bordered dt-responsive nowrap" id="tablaveh">
				<thead>
					<tr>
						<th>ID</th>
						<th>Placa</th>
						<th>Tipo</th>
						<th>IMEI</th>
						<th>Descripción</th>
						<th>Vel.</th>
						<th>Foto</th>
						<th>No GPS</th>
						<th>Editar</th>
						<th>Estado</th>
					</tr>
				</thead>
				<tbody>
				<?php

						$veh = R::findAll('vehiculos');

					    foreach ($veh as $k) {
							 //unset($gps);
							$gps = R::findOne('gps', "id = ".$k->idgps);
							$codGPS = "<b>SIN GPS</b>";

							  if (isset($gps->serial) && $gps->serial > 0) {$codGPS = $gps->serial;}
							echo "<tr>";
							  echo "<td>".$k->id."</td>";
							  echo "<td>".$k->placa."</td>";
							  echo "<td>".$k->tipo."</td>";
							  echo "<td><center>".$codGPS."</center></td>";
							  //echo "<td>".$k->descripcion."</td>";
							  echo '<td><p class="animado" ><span data-tooltip= "'.$k->descripcion.'">'.acortarTexto($k->descripcion,10).'</span></p></td>';
							  echo "<td>".$k->limitevel."</td>";
							  echo "<td><center><a href='images/veh/".$k->foto."' class='preview'><img width='32px' heigth='32px' src='images/veh/".$k->foto."'/></a></center></td>";
							    if ($codGPS > 0) {
								  echo "<td><center><a href='".$url."?accion=nogps&id=".$k->id."'><img width='32px' heigth='32px' src='images/nogps.png'/></a></center></td>";
								} else {
								  echo "<td>&nbsp;</td>";
								}
							  echo "<td><center><a href='".$url."?accion=editar&id=".$k->id."'><img width='32px' heigth='32px' src='images/edit.png'/></a></center></td>";
							    if ($k->activo == 0) {
								  echo "<td><center><a href='".$url."?accion=borrar&id=".$k->id."' onclick='return checkAct();'>
								  <img width='32px' heigth='32px' src='images/desactivado.png'/></a></center></td>";
								} else {
								  echo "<td><center><a href='".$url."?accion=borrar&id=".$k->id."' onclick='return checkDel();'>
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
$(document).ready(function(){
  imagePreview();
  
    $('#tablaveh').dataTable( {
       "language": {
         "url": "js/spanish.json"
       }
    });
	


});

function checkDel() {
	if (!confirm("¿Esta seguro que desea Desactivar este Vehiculo?")) {
		return false;
	}
  return true;
}

function checkAct() {
	if (!confirm("¿Esta seguro que desea Activar este Vehiculo?")) {
		return false;
	}
  return true;
}
</script>
  <?php R::close(); ?>
  </body>
</html>
