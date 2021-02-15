<?php

session_start();


 $a = session_id();
      if(empty($a)){session_start();}


// verificar sesion
if (!isset($_SESSION['id'])) { 
?>
<script>
window.location="index.php";
</script>
<?php
} else if (!isset($_SESSION['nivel'])) { 
?>
<script>
window.location="index.php";
</script>
<?php
}


include_once('../class/class.conexion.php');

// verificar sesion
if (isset($_SESSION['cedula'])) { $Cedula = trim(base64_decode($_SESSION['cedula'])); } else { $Cedula = 0; }
if (isset($_SESSION['id'])) { $Id = trim(base64_encode($_SESSION['id'])); } else { $Id = 0; }
if (isset($_SESSION['nivel'])) { $Nivel = trim(base64_encode($_SESSION['nivel'])); } else { $Nivel = 0; }

$dbh = Conexion::singleton_conexion();
 
$sql = "SELECT * from usuarios WHERE (id = ".$Id." AND cedula = ".$Cedula.")";
$query = $dbh->prepare($sql);
$query->execute();
$dbh = null;
 
  //si existe el usuario
  if($query->rowCount() == 1) {
   $_SESSION['cedula'] = null;
   $_SESSION['id'] = null;
   $_SESSION['nivel'] = null;
  } 
  
session_destroy();

echo 'ok';
//header('Location: index.php');
   
?>
