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

if (isset($_SESSION['id'])) { $IdUser = base64_decode($_SESSION['id']); } else { $IdUser = 0; }

if ($IdUser < 1 || $IdUser > 1) { 
?>
<script>
window.location="index.php";
</script>
<?php
}

//include_once('class/class.conexion.php');
include_once('class/class.converter.php');

$convertir = new Converter();
//$json = $convertir->dbToJson(array('*'),array('users'),"id = '1'");
//echo $json;

/*
$ok = $convertir->dbToJsonWithBackup(array('*'),array('users'),"",
"date_entered", "id", array('delete'=>true, 'divideFolders'=>true, 'keepTotalRows'=>10));
*/

/*$convertir->dbToJsonWithBackup(array('*'),array('tramagps'),"",
"fechahoraserver", "idvehiculo", array('delete'=>true, 'divideFolders'=>true, 'keepTotalRows'=>10, 'primaryKey'=>'idtramagps'));  */
$convertir->dbToJsonWithBackup(array('*'),array('tramagps'),"",
"fechahoraserver", "idvehiculo", array('delete'=>true, 'divideFolders'=>true, 'keepTotalRows'=>10, 'primaryKey'=>'id'));  


?> 
