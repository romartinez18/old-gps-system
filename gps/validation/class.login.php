<?php

include_once('../class/class.config.php');
require_once('../class/class.rb.php');
include_once('../modules/crypto.php');
R::setup('mysql:host='.SERVIDOR.';dbname='.DATABASE, USUARIOBD, CLAVEBD);

function processPOSTJSONLOGIN($post,$nLogin) {

  $errores = array();
  $valid = false;
  
  
if (!isset($post['nEmail']) || empty($post['nEmail'])) {
  $errores['nEmail'] = 'Debe ingresar un serial';
} else {
  $email = $post['nEmail'];
   if (strlen($email) < 10) {
    $errores['nEmail'] = 'El email debe tener 10 o más caracteres';
   } else {
     $emailLog = R::findOne('usuarios', "email = '".$email."'");
     if ($emailLog->id <= 0) {
       $errores['nEmail'] = 'No se ha encontrado un usuario con el email '.$email;
     } else {
	   $valid = true;
     }
   }
}

if (!$valid) {
 if ((!isset($post['CodTelf']) || empty($post['CodTelf'])) && (!isset($post['nTelf']) || empty($post['nTelf']))) {
  $errores['telefono'] = 'Debe ingresar un número de celular';
 } else {
   $telefono = $post['CodTelf'].$post['nTelf'];
   if (!is_numeric($telefono)) {
    $errores['telefono'] = 'El celular debe ser númerico';
   } else if (strlen($telefono) < 11) {
    $errores['telefono'] = 'El celular debe tener 11 digitos';
   } else {
	  $celLog = R::getRow('SELECT * usuarios WHERE ((celular = ? OR celular2 = ? OR celular3 = ?) AND id = '.$nLogin.') LIMIT 1',
        array($telefono,$telefono,$telefono));
		if ($celLog > 0) {$valid = true;}
   }
 }
}

 return $errores; 
}

 if (isset($_POST['checkRecup'])) {
  $errores['usuario'] = "";
  
  if (!isset($_POST['nUser']) || empty($_POST['nUser'])) {
	$errores['usuario'] = "Debe ingresar un nombre de usuario";
	echo json_encode(array("codigo"=>"FAIL", "mensaje"=>"Fallo Validación al Recuperar Clave", "datos"=>$errores));
  } else if (strlen($_POST['nUser']) < 6) {
	$errores['usuario'] = "El nombre de usuario debe tener 6 o más caracteres";
  } else {
	$checkLogin  = R::findOne( 'login', ' usuario = ? ', $_POST['nUser']);
	
	if ($checkLogin->id <= 0) {
	 $errores['usuario'] = "Nombre de usuario no encontrado";
	} else {
	 unset($errores['usuario']);
	 $errores = processPOSTJSONLOGIN($_POST,$checkLogin);	
	}
	
	 if (isset($errores) && count($errores) > 0) {
	   echo json_encode(array("codigo"=>"FAIL", "mensaje"=>"Fallo Validación al Recuperar Clave"));
	 } else {
	   $nCode = createRandomCode();
	   $checkLogin->nCode = $nCode;
	   $lastID = R::store($checkLogin);
	   if ($lastID > 0) {
		 echo json_encode(array("codigo"=>"OK", "mensaje"=>"Codigo Enviado"));
	   } else {
		 echo json_encode(array("codigo"=>"FAIL", "mensaje"=>"Fallo al Recuperar Clave"));
	   }
	   
	 }
	 
  }
	 
 }



?>
