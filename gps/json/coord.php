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

	//error_reporting(E_ALL);
	//header("Access-Control-Allow-Origin: *");
	//header("Content-Type: application/json; charset=UTF-8");
	
        include_once('../class/class.conexion.php');

	$dbh = Conexion::singleton_conexion();
 		
	
	$limite = 4;
	$json = "[";
	  if (!empty($_SESSION['id'])) {
  	    $Id = base64_decode($_SESSION['id']);
  	  } else {
  	    $Id = 0;
  	  }
  	  
	/*$placa = array();
	$tipo = array();
	$descripcion = array();
	$foto = array();*/
	$serial = array();
	/*$fechaHora = array();
	$Vel = array();
	$lat = array();
	$long = array();*/
	
	if ($Id == 1) {
      $Sql = "SELECT v.placa as placa, v.descripcion as descripcion, v.tipo as tipo, v.foto as foto, g.serial as serial FROM vehiculos as v inner join gps as g on v.idgps = g.id inner join vehiculos_user as vu on vu.idvehiculo = v.id where g.activo=1";
	} else {
	  $Sql = "SELECT v.placa as placa, v.descripcion as descripcion, v.tipo as tipo, v.foto as foto, g.serial as serial FROM vehiculos as v inner join gps as g on v.idgps = g.id inner join vehiculos_user as vu on vu.idvehiculo = v.id where vu.idusuario = ".$Id." AND g.activo=1";	
	}
	
	
	//echo $Sql; 
	
	$query = $dbh->prepare($Sql);
	$query->execute();
	
	
	
	//$filas = $query->fetch(); 
	$i = 0; $j = 0; $cVel = 0;
		
	  //si hay vehiculos
	  $totalVeh = $query->rowCount();
	  $contVeh = 0;
	  
	  if($totalVeh >= 1) {

	    foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $filas) {
	    
                  // $placa[$i] = $filas['placa']; 
                  $json .= "{\"Placa\":\"".utf8_encode($filas['placa'])."\",";
		  // $descripcion[$i] = $filas['descripcion'];
		  $json .= "\"Descripci&oacute;n\":\"".utf8_encode($filas['descripcion'])."\",";
		  //$tipo[$i] = $filas['tipo']; 
		  $json .= "\"Tipo\":\"".utf8_encode($filas['tipo'])."\",";
		  //$foto[$i] = $filas['foto']; 
		  $json .= "\"foto\":\"".utf8_encode($filas['foto'])."\",";
		  $serial[$i] = $filas['serial']; 
		  $json .= "\"Serial\":\"".utf8_encode($filas['serial'])."\",";
			
		 //$Sql = "SELECT * FROM tramagps where idvehiculo = '".$serial[$i]."' order by fechahoraserver desc LIMIT 0, ".$limite;  
		 $Sql = "SELECT * FROM tramagps where idvehiculo = '".$serial[$i]."' order by id desc LIMIT 0, ".$limite;  
	         $query2 = $dbh->prepare($Sql);
	         $query2->execute();
	         //$totalTramas = $query2->rowCount();
			 
			 $SqlCount = "SELECT count(*) FROM tramagps where idvehiculo = '".$serial[$i]."' LIMIT 0, ".$limite; ; 
		     $resultCount = $dbh->prepare($SqlCount); 
			 $resultCount->execute(); 
			 $totalTramas = $resultCount->fetchColumn(); 
				
				if ($totalTramas > $limite) {
				  $totalTramas = $limite;
				}
				
			  $cjson  = "";
			  $cjson .= "\"Coord\": [";
			   
			   $j = 0;
			   $fHora = "";
			   $veloc = "";
			   //if ($totalTramas > 0) { 
				foreach ($query2->fetchAll(PDO::FETCH_ASSOC) as $filas2) {
					if ($j == 0) {
				     	 $fHora = strtotime($filas2['fechahoraserver']);	
				     	 $veloc = $filas2['velocidad'];
				   	}
					//if ($filas2['velocidad'] == 0) {  $cVel++; } else {  $cVel = 0;	}
					
					  $cjson .= "[\"".$filas2['latitud']."\",\"".$filas2['longitud']."\"]";      
					    //$lat[$i][$j] = $filas2['latitud'];  $long[$i][$j] = $filas2['longitud']; 
						  //if(($j + 1) < $totalTramas) {
						  if(($j + 1) < $totalTramas) {
						    $cjson .= ",";
						  } 					
					$j++;
					//if ($cVel >= 3) {break;}
				} // Por cada coordenada
			  //} // Si hay tramas
			 $cjson .= "]}";
			 	 
			 $contVeh++;
			 
			 if ($contVeh < $totalVeh) { $cjson .= ","; }
			 if ($fHora == "") {
 			   $fechaHora[$i] = date("d/m/Y H:i:s",time()); 
			   $fechaHora2[$i] = date("Y-m-d",time()); 
			 } else {
			   $fechaHora[$i] = date("d/m/Y H:i:s",$fHora);
			   $fechaHora2[$i] = date("Y-m-d",$fHora); 			   
			 }
			 $json .= "\"Fecha\":\"".$fechaHora[$i]."\",";
			 //$json .= "\"Fecha2\":\"".$fechaHora2[$i]."\",";
			 //$Vel[$i] = $veloc;//$filas['velocidad']; 
			 $json .= "\"Velocidad\":\"".$veloc." km\",";
			 
			 $json .= $cjson;
	       /*} else {
	         $json .= "\"Coord\": []}";
	       }*/ // Si hay tramas
		  $i++;
	     }  // Por cada vehiculo
					
	  }
	  
	  
	$json .= "]";
	
	//print_r($json);
	echo trim($json);
  //echo "----".$cjson."----";
?>