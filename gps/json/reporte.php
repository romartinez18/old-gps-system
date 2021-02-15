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

error_reporting(E_ALL);
include_once('../class/class.conexion.php');

// verificar sesion
if (isset($_SESSION['cedula'])) { $Cedula = base64_decode($_SESSION['cedula']); } else { $Cedula = 0; }
if (isset($_SESSION['id'])) { $Id = base64_decode($_SESSION['id']); } else { $Id = 0; }
if (isset($_SESSION['nivel'])) { $Nivel = base64_decode($_SESSION['nivel']); } else { $Nivel = 0; }
if (isset($_SESSION['tipo'])) { $Tipo = base64_decode($_SESSION['tipo']); } else { $Tipo = 0; }
if (isset($_SESSION['usuario'])) { $Usuario = base64_decode($_SESSION['usuario']); } else { $Usuario = ""; }

//$_POST = array_map('mysql_real_escape_string',$_POST);
 
//echo $_POST["idvehiculo"]."  finicio:".$_POST["finicio"]."  ffin:".$_POST["ffin"].".";
 
if (isset($_POST["idvehiculo"]) && isset($_POST["finicio"]) && isset($_POST["ffin"])) {
 $dbh = Conexion::singleton_conexion();
 
 $idVehiculo = $_POST["idvehiculo"];
 $fInicio = $_POST["finicio"];
 $fFin = $_POST["ffin"];
 
  $sql = "SELECT * from usuarios WHERE (id = ".$Id." AND cedula = '".$Cedula."')";
  $query = $dbh->prepare($sql);
  $query->execute();
  $filas = $query->fetchAll(PDO::FETCH_ASSOC);
  $tfilas = $query->rowCount();

   //si existe el usuario
 if ($tfilas > 0) {
 
  $Sql2 = "SELECT fechahoraserver, latitud, longitud, velocidad FROM `tramagps` inner join gps on gps.serial = tramagps.idvehiculo inner join vehiculos on gps.id = vehiculos.idgps 
    WHERE (`fechahoraserver` between '".$fInicio."' AND '".$fFin."') AND  (vehiculos.id = ".$idVehiculo.") order by fechahoraserver ASC";
    
   $query2 = $dbh->prepare($Sql2);
   $query2->execute();
 
   $json = array();
 
    $i=0;
    $parada = -1;
    $save = false;
    
   foreach ($query2->fetchAll(PDO::FETCH_ASSOC) as $fila) {
   
      if ($parada == -1) {
        $parada = $fila['velocidad'];
        $save = true;
      } else if ($parada == 0 && $fila['velocidad'] == 0) {
        $save = false;
      } else {
        $parada = $fila['velocidad'];
        $save = true;
      }
      
       if ($save) {
        $json[$i] = $fila;
        $i++;
       }
   }
   
   if ($i == 0) {
    echo "[]Sin Registros";//$Sql2;
   } else {
    echo json_encode($json);
   }
    
  } else {
    echo "[]Sin Usuario";
  }
  
  
} else {
  echo "[]Sin POST";
} // Si POST


?>