<?php

function validarPOSTUsuario($post,$nUsu) {
  $errores = array();
  
  //return $errores;
  /*$dataForm = array("cedula"=>"SIN CEDULA", "nombres"=>"", "apellidos"=>"", "foto"=>"", "tipo"=>2, "distribuidor"=>0, "cantidadusuario"=>0, "ciudad"=>64, "estado"=>4, "direccion"=>"", "email"=>"admin@teleinformatic.com.ve", "celular"=>"", "celular2"=>"", "celular3"=>"", "telefono"=>"", "nivelautorizacion"=>"5", "activo"=>1, "usuario"=>"");*/

if (!isset($post['tipo']) || empty($post['tipo'])) {
  $errores['tipo'] = 'Debe seleccionar un Tipo de Vehiculo';
} else {
   $tipo = $post['tipo'];
   if (strlen($tipo) < 1) {
   $errores['tipo'] = 'El campo tipo debe tener 1 o más caracteres';
   } else {
   $nUsu->tipo = $tipo;
   }
}

if (!isset($post['foto']) || empty($post['foto'])) {
  $errores['tipo'] = 'Debe seleccionar una Foto del Vehiculo';
} else {
   $foto = $post['foto'];
   if (strlen($foto) <= 2) {
   $errores['foto'] = 'El campo foto debe tener 2 o más caracteres';
   } else {
   $nUsu->foto = $foto;
   }
}

$placa = $post['placa'];
    if (!isset($placa) || empty($placa)) {$placa = "SIN PLACA";}
 $nUsu->placa = $placa;

$desc = $post['descripcion'];
    if (!isset($desc) || empty($desc)) {$desc = "";}
 $nUsu->descripcion = $desc;

$vel = $post['limite'];
    if (!isset($vel) || empty($vel)) {$vel = 80;}
 $nUsu->limitevel = $vel;

 
// Username
if (!isset($post['usuario']) || empty($post['usuario'])) {
  $errores['usuario'] = 'Debe colocar un username';
} else {
   $username = $post['usuario'];
   if (strlen($username) < 3) {
   $errores['usuario'] = 'El username debe tener más de 4 caracteres';
   } else {
    $lUser = R::findOne('login', "usuario = '".$username."'");
     if (isset($lUser->usuario) && !empty($lUser->usuario)) {
       $errores['usuario'] = 'Ya se encuentra un usuario registrado con el username '.$username;
     } else {
	   $lUser->usuario = $username;
     }
   }
}

  return $errores;
}




?>
