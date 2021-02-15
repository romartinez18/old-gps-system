<?php 


 $a = session_id();
      if(empty($a)){session_start();}



	//session_start();
    if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {header("Location:../gps/index.php");} 
	if (!isset($_SESSION["nivel"]) || empty($_SESSION["nivel"])) {header("Location:../gps/index.php");} 
 
 
if (isset($_SESSION['id'])) { $IdUser = base64_decode($_SESSION['id']); } else { $IdUser = 0; }
//if (isset($_SESSION['nivel'])) { $Nivel = base64_decode($_SESSION['nivel']); } else { $Nivel = 0; }



include_once('class/class.config.php');
require_once('class/class.rb.php');
R::setup('mysql:host='.SERVIDOR.';dbname='.DATABASE, USUARIOBD, CLAVEBD);

$loginUser = R::load('usuarios', $IdUser);

if (isset($loginUser) && $loginUser->id > 0 && $loginUser->nivelautorizacion == 1) {
	// Usuario Admin
} else {
  header("Location:../gps/index.php");
}



?> 
