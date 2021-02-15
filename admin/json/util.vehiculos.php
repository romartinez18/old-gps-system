<?php

//echo json_encode($_POST);

if (isset($_POST['idUser']) && !empty($_POST['idUser']) && is_numeric($_POST['idUser']) && $_POST['idUser'] > 0) {
   if (isset($_POST['accion']) && !empty($_POST['accion']) && strncmp($_POST['accion'], "listaUser", 9) == 0) { 
     include_once('../class/class.config.php');
     require_once('../class/class.rb.php');
      R::setup('mysql:host='.SERVIDOR.';dbname='.DATABASE, USUARIOBD, CLAVEBD);
	  
	  $id = $_POST['idUser'];
		
		$vehSel = R::getAll('SELECT vu.idvehiculo as idveh, gps.serial, veh.placa, veh.foto, veh.activo as actVeh, veh.descripcion, gps.celular1 as celulargps, gps.activo as actGPS FROM vehiculos_user as vu INNER JOIN vehiculos as veh ON vu.idvehiculo = veh.id INNER JOIN gps ON veh.idgps = gps.id WHERE idusuario = :iduser', 
			array(':iduser'=>$id) 
		);
		
		//if (isset($vehSel) && count($vehSel) > 0) {
		  echo json_encode($vehSel);	
		//} else {
		//  echo json_encode(array("codigo"=>"FAIL", "mensaje"=>"Sin Vehiculos Asociados"));	
		//}
		
	} else {
	  echo json_encode(array("codigo"=>"FAIL", "mensaje"=>"Acción no definida"));
	}
} else {
	echo json_encode(array("codigo"=>"FAIL", "mensaje"=>"Usuario no Identificado"));
}

?> 
