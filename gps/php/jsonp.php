<?php

//function enviarSMS() {

session_start();


 $a = session_id();
      if(empty($a)){session_start();}


//error_reporting(E_ALL);
include_once('../class/class.conexion.php');


$dbh = Conexion::singleton_conexion();
 
$sql = "SELECT * from salida where estatus = 0 order by id Asc";
$query = $dbh->prepare($sql);
$query->execute();
//$filas = $query->fetchAll(PDO::FETCH_ASSOC);
$tfilas = $query->rowCount();
$vIni = 0;
$vFin = 0;

 // Si hay mensajes
 if ($tfilas > 0) {
 
 $i = 1;
 $datos = "[";
  foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $filas) {
   if ($i == 1) {$vIni = $filas['id'];}
   
   if ($i < $tfilas) {
     $datos .= '{"telefono": "'.$filas['celular'].'","mensaje": "'.$filas['mensaje'].'"},';
   } else {
     $datos .= '{"telefono": "'.$filas['celular'].'","mensaje": "'.$filas['mensaje'].'"}';
     $vFin = $filas['id'];
   }
   $i++;
   //$datos = '[{"telefono": "04121765218","mensaje": "Mensaje 1"},{"telefono": "04243121387","mensaje": "Mensaje 2"}]';
   
  }
  
  
   $datos .= "]";
 
 $sql = "UPDATE salida SET estatus=1 WHERE (id >= ".$vIni." AND id <= ".$vFin.")";
 $query = $dbh->prepare($sql);
 $query->execute();

 echo $datos;





}

?>