<?php
error_reporting(E_ALL);
$archivo = "config.cfg";

 if (file_exists($archivo)) {
    $file = fopen($archivo,"r");
	 
	 if ($file === FALSE) {
		echo "Error al abrir el archivo ".$archivo;
		break;
	 }
	  
	 $line = array();
	 $datos = array();
	 
	 while(!feof($file)) {
	  $linea = trim(stream_get_line($file,1024,"\n"));
	  $line = $linea;
		
		if (!empty($linea)) {
		 $x1 = strcmp($line[0],"<");
		 $x2 = strcmp($line[1],">");
		
		  if ($x1 != 0) {
			if ($x2 != 0) {
			  $datos = explode(" ",$linea);

		       echo $datos[1];
			   echo "<br/>";
		    } // if $x2
		  } // if $x1
		  
		} // if empty 
	 } // While
	 
	 fclose($file);
 }

?> 
