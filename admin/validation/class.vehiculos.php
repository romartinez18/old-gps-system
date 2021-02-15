<?php

function validarPOSTVehiculo($post,$nVeh) {
  $errores = array();

if (!isset($post['gps']) || empty($post['gps'])) {
  $errores['gps'] = 'Debe seleccionar un gps';
} else {
   $idgps = $post['gps'];
   if (!is_numeric($idgps)) {
   $errores['gps'] = 'El campo gps debe ser númerico';
   } else {
   $fGPS = R::findOne('gps', "id = ? ", [$idgps]);
     if (isset($fGPS) && !empty($fGPS) && $fGPS != null) {
       $nVeh->idgps = $idgps;
     } else {
	   $errores['gps'] = 'No se ha encontrado un gps con el id '.$idgps;
     }
   }
}

if (!isset($post['tipo']) || empty($post['tipo'])) {
  $errores['tipo'] = 'Debe seleccionar un Tipo de Vehiculo';
} else {
   $tipo = $post['tipo'];
   if (strlen($tipo) <= 2) {
   $errores['tipo'] = 'El campo tipo debe tener 2 o más caracteres';
   } else {
   $nVeh->tipo = $tipo;
   }
}

if (!isset($post['foto']) || empty($post['foto'])) {
  $errores['tipo'] = 'Debe seleccionar una Foto del Vehiculo';
} else {
   $foto = $post['foto'];
   if (strlen($foto) <= 2) {
   $errores['foto'] = 'El campo foto debe tener 2 o más caracteres';
   } else {
   $nVeh->foto = $foto;
   }
}

$placa = $post['placa'];
    if (!isset($placa) || empty($placa)) {$placa = "SIN PLACA";}
 $nVeh->placa = $placa;

$desc = $post['descripcion'];
    if (!isset($desc) || empty($desc)) {$desc = "";}
 $nVeh->descripcion = $desc;

$vel = $post['limite'];
    if (!isset($vel) || empty($vel)) {$vel = 80;}
 $nVeh->limitevel = $vel;

  return $errores;
}




?>
