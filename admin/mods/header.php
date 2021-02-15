<?php 

 $a = session_id();
 if(empty($a)){session_start();}
  
  //session_start();
 /* if (!isset($_SESSION["usuario"]) || empty($_SESSION["usuario"])) {header("Location:login.php");}*/
	  
include_once('class/class.config.php');
require_once('class/class.rb.php');
R::setup('mysql:host='.SERVIDOR.';dbname='.DATABASE, USUARIOBD, CLAVEBD);


?> 
