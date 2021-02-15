<?php

include_once('../class/class.config.php');
require_once('../class/class.rb.php');
R::setup('mysql:host='.SERVIDOR.';dbname='.DATABASE, USUARIOBD, CLAVEBD);

function processPOSTJSONGPS($post,$nGPS) {

  $errores = array();

if (!isset($post['serial']) || empty($post['serial'])) {
  $errores['serial'] = 'Debe ingresar un serial';
} else {
  $serial = $post['serial'];
   if (!is_numeric($serial)) {
    $errores['serial'] = 'El serial debe ser númerico';
   } else if (strlen($serial) < 15) {
    $errores['serial'] = 'El serial debe tener 15 digitos';
   } else {
    $serGPS = R::findOne('gps', "serial = '".$serial."'");
     if (isset($serGPS->serial) && !empty($serGPS->serial)) {
       $errores['serial'] = 'El Serial '.$serial.' ya se encuentra registrado ';
     } else {
       $nGPS->serial = $serial;
     }
   }
}

if (!isset($post['celulargps']) || empty($post['celulargps'])) {
  $errores['celulargps'] = 'Debe ingresar un número de celular';
} else {
   $celulargps = $post['celulargps'];
   if (!is_numeric($celulargps)) {
    $errores['celulargps'] = 'El celular debe ser númerico';
   } else if (strlen($celulargps) < 11) {
    $errores['celulargps'] = 'El celular debe tener 11 digitos';
   } else {
    $nGPS->celular1 = $celulargps;
   }
}

if (!isset($post['diacorte']) || empty($post['diacorte'])) {
  $errores['diacorte'] = 'Debe ingresar el dia de corte';
} else {
   $diacorte = $post['diacorte'];
   if (!is_numeric($diacorte)) {
    $errores['diacorte'] = 'El dia de corte debe ser númerico';
   } else {
    $nGPS->diacorte = $diacorte;
   }
}

if (!isset($post['marcagps']) || empty($post['marcagps'])) {
  $errores['marcagps'] = 'Debe seleccionar la marca del gps';
} else {
   $marcagps = $post['marcagps'];
   if (!is_numeric($marcagps)) {
    $errores['marcagps'] = 'El marca del gps debe ser númerico';
   } else {
    $nGPS->idmarca = $marcagps;
   }
}

if (!isset($post['modelogps']) || empty($post['modelogps'])) {
  $errores['modelogps'] = 'Debe seleccionar el modelo del gps';
} else {
   $modelogps = $post['modelogps'];
   if (!is_numeric($modelogps)) {
    $errores['modelogps'] = 'El modelo del gps debe ser númerico';
   } else {
    $nGPS->idmodelo = $modelogps;
   }
}

 $nGPS->fecharegistro = date('Y-m-d'); 

 return $errores; 
}

 if (isset($_POST['guardarGPS'])) {
	$newGPS = R::dispense('gps');
	$errores = processPOSTJSONGPS($_POST,$newGPS);
//echo json_encode(array("codigo"=>"FAIL", "mensaje"=>"Fallo al Crear el GPS", "datos" => $errores));
	 if (isset($errores) && count($errores) > 0) {
	   echo json_encode(array("codigo"=>"FAIL", "mensaje"=>"Fallo al Crear el GPS1", "datos" => $errores));
	 } else {
	   $lastID = R::store($newGPS);
	   if ($lastID > 0) {
		 echo json_encode(array("codigo"=>"OK", "mensaje"=>"GPS Agregado", "lastID"=>$lastID));
	   } else {
		 echo json_encode(array("codigo"=>"FAIL", "mensaje"=>"Fallo al Crear el GPS2"));
	   }
	   //echo json_encode(array("codigo"=>"FAIL", "mensaje"=>"Fallo al Crear el GPS"));
	 }
	 
 }



?>
