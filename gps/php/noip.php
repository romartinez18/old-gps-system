<?php


if (isset($_SERVER['REMOTE_ADDR'])) {
	$IPTemp = isset($_SERVER['REMOTE_ADDR']);
	
	if ($IPTemp == "127.0.0.1" || $IPTemp == "localhost") {
	  if (isset($_GET['IPRemota'])) {
		$IPTemp = $_GET['IPRemota'];
	  } else {
		unset($IPTemp);
	  }
	  
	}
}


if (isset($IPTemp) && isset($_GET['secureid'])) {
 require_once('../class/class.conexion.php');
 //require_once('jsonp.php');
 
  date_default_timezone_set('America/Caracas');
  
 //$IPremota = trim($_SERVER['REMOTE_ADDR']);
 $IPremota = trim($IPTemp);
 $Id = htmlspecialchars(strip_tags(trim($_GET['secureid'])));
 $secureid = "k7172I651*3.7Ek";
 $SMSProcess = 0;
 
   if (strcmp($Id, $secureid) == 0) {
     if (filter_var($IPremota,FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)) {
        $Conexion = Conexion::singleton_conexion();
        	
	//echo $IPremota;
	
	$FechaHora= date("Y-m-d H:i:s");
	
      	$Sql = "SELECT * FROM noip WHERE Nombre = 'Servidor GPS'";
   	$Consulta = $Conexion->prepare($Sql);  
   	$Consulta->execute();
   	
   	  if ($Filas = $Consulta->fetchObject())
   	  {
   	    $Sql =  "UPDATE `noip` SET IP='".$IPremota."',FechaHora='".$FechaHora."' WHERE Nombre = 'Servidor GPS'";
   	     if (strcmp($Filas->IP,$IPremota) !== 0) {
   	       	 // Guardar mensaje y enviar
   	       	 $Sql2 = "SELECT * FROM gps WHERE activo = 1";
   	       	  $Consulta2 = $Conexion->prepare($Sql2);  
   		  $Consulta2->execute();
   		  $tfilas = $Consulta2->rowCount();
   		  $i = 1;
   		  $SqlSMS = "";
   		  
   		  foreach ($Consulta2->fetchAll(PDO::FETCH_ASSOC) as $filas2) {
   		     if ($i==1) {$SqlSMS = "INSERT INTO salida(celular,mensaje,estatus) VALUES ";}
   		     
   	  	     if ($i < $tfilas) {
   	  	      $SqlSMS .= "('".$filas2['celular1']."','adminip123456 ".$IPremota." 9800', 0),";
   	  	     } else {
   	  	      $SqlSMS .= "('".$filas2['celular1']."','adminip123456 ".$IPremota." 9800', 0);";
   	  	     }
   	  	     $i++;
   	  	  }
   	  	    
   	       	//$Sql = "INSERT INTO `salidasms`(`Nombre`,`IP`,`FechaHora`) VALUES ('Servidor GPS','".$IPremota."', '".$FechaHora."');";
   	       	if (strcmp($SqlSMS,"") !== 0) {
   		 $ConsultaSMS = $Conexion->prepare($SqlSMS);  
   		 $ConsultaSMS->execute();
   		 $SMSProcess = 1;
		  //echo $SqlSMS;
   		}
   	     }
   	  } else {
   	    $Sql = "INSERT INTO `noip`(`Nombre`,`IP`,`FechaHora`) VALUES ('Servidor GPS','".$IPremota."', '".$FechaHora."');";
   	  }
   	 $Consulta = $Conexion->prepare($Sql);  
   	 $Consulta->execute();
      } else {
        echo "No Encontrada";
      }
   }
   
   
   /*if ($SMSProcess >= 1) {
    //enviarSMS();
     //header('Location:jsonp.php');
     include_once('jsonp.php');
   }*/
  
}

?> 
