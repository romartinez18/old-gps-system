<?php

error_reporting(E_ALL);

include_once('class.config.php');
include_once('class.conexion.php');

class Converter {
	
	private function saveJsonFile($fecha, $nombre, $json, $divFolder) {
		  if (isset($fecha) && !empty($fecha))  {
			$f = strtotime($fecha);
			$newFecha = date('Y-m-d',$f);  
		  } else {
			$newFecha = date('Y-m-d');  
		  }
		  
		  if (!isset($nombre) || empty($nombre)) {$nombre = $newFecha;}
		  
		$arrFecha = explode("-",$newFecha);
		$pathDate = $arrFecha[0]."/".$arrFecha[1]."/".$arrFecha[2];
		$archivo = $nombre.'.json';
		$ruta = "./".PATH_BACKUP_GPS."/".$pathDate."/".$archivo;
		 
		 //echo $ruta."<br/><br/>";
		 
		 
		  $dirname = dirname($ruta);
			if (!is_dir($dirname)){mkdir($dirname, 0755, true);}
		  
		//file_put_contents($ruta, $json);
		  
		//write to json file
		$fp = fopen($ruta, 'w');
		fwrite($fp, $json);
		fclose($fp);
	}
	
	public function arrayToJson($array) {
	  return json_encode($array);
	}
	
	public function jsonToArray($json) {
	  return json_decode($json,true);
	}
	
	/**
	* @param array $col
	*  An string array with the name of columns. To include all columns put '*'.
	* @param array $tab
	*  An string array with the name of tables.
	* @param string $condiciones
	*  An string with the conditions in sql.
	* @param string $colFecha
	*  An string with the date of make the backup in files.
	* @param string $uniqueKey
	*  An string with the unique fields of the database to make the backup in files. 
	*  If it's empty use $colFecha.
	* @param array $opciones
	*  An string array with the options. The options are:
	*  $opciones = array('delete' => true or false, 'divideFolders' => true or false, 'keepTotalRows' => 10);
	* @retval string
	*  An output string.
	*/
	public function dbToJsonWithBackup($col, $tab, $condiciones, $colFecha, $uniqueKey, $opciones) {
	  $json = "";
	  $columnas = implode(",", $col);
	  $tablas = implode(",", $tab);
	  $where = "";
	  $datos = array();
	  $conexion = Conexion::singleton_conexion();
	  $delete = false;
	  $divideFolders = false;
	  $keepTotalRows = 10;
	  $distinctField = array();
	  $primaryKey = "";
	  $orden = "";
	  
	    set_time_limit(0);
		
		// CHECK OPTIONS
		if (array_key_exists('delete', $opciones)) {$delete = filter_var($opciones['delete'], FILTER_VALIDATE_BOOLEAN);}
		if (array_key_exists('divideFolders', $opciones)) {$divideFolders = filter_var($opciones['divideFolders'], FILTER_VALIDATE_BOOLEAN);}
		if (array_key_exists('primaryKey', $opciones)) {$primaryKey = $opciones['primaryKey'];}
		if (array_key_exists('keepTotalRows', $opciones) && (is_int($opciones['keepTotalRows']) && $opciones['keepTotalRows'] > 0)) {
		   $keepTotalRows = $opciones['keepTotalRows'];
		}
		
		if ($delete && (is_int($keepTotalRows) && $keepTotalRows <= 0)) {$keepTotalRows = 10;}
		
		if (isset($condiciones) && !empty($condiciones)) {$where = "WHERE ".$condiciones;}
		
		if (isset($colFecha) && !empty($colFecha)) {
		 $orden = "ORDER BY ".$colFecha." ASC";
		}
		
		$sqlDistinct = "SELECT DISTINCT(".$uniqueKey.") FROM ".$tablas." ".$where." ORDER BY ".$uniqueKey." ASC";
		$queryDistinct = $conexion->prepare($sqlDistinct);
		$queryDistinct->execute();
		
		 //echo $sqlDistinct;
		 
		   while($filaDistinct = $queryDistinct->fetch(PDO::FETCH_ASSOC)) {
			   //var_dump($filaDistinct);
			   //echo "<br/><br/>";
			 $distinctField[] = $filaDistinct[$uniqueKey];
		   }
			
		  //echo "Total de Datos Unicos a Procesar ".count($distinctField)."<br/><br/>";
			
			foreach($distinctField as $dField) {
			  $where2 = "";
			  
				if (isset($where) && !empty($where)) {
				  $where2 = " AND ".$uniqueKey." = '".$dField."'";
				} else {
				  $where2 = "WHERE ".$uniqueKey." = '".$dField."'";
				}
				
			    $sql = "SELECT ".$columnas." FROM ".$tablas." ".$where." ".$where2." ".$orden;
			    $query = $conexion->prepare($sql);
			    $query->execute();
				//echo $sql;
				
				$fechaVieja = "";
				$fechaNueva = "";//date('Y-m-d');  
				//echo "SQL ".$sql."<br/><br/>";
				//echo "PROCESANDO  '".$dField."'   Total Filas ".$query->rowCount()."<br/><br/>";
				
				while($fila = $query->fetch(PDO::FETCH_ASSOC)) {
				  //$fTime = strtotime($fila[$colFecha]);
				  $fechaNueva = date('Y-m-d',strtotime($fila[$colFecha]));  
				  //echo $fechaNueva."<br/><br/>";
					
					if (!isset($fechaVieja) || empty($fechaVieja)) {
					  $fechaVieja = $fechaNueva;
					} 
					
					//if ($fechaVieja == $fechaNueva) { 
					if (strcmp($fechaVieja,$fechaNueva) <> 0) { 
					  $json = json_encode($datos);
					   //echo "Data1  ".$fechaNueva."    '".$dField."'   Total ".count($datos)."<br/><br/>";
					  //echo "saveJSON1   ".$fechaNueva."  ".$dField.":<br/><br/>".$json."<br/><br/>";
					  $this->saveJsonFile($fechaVieja, $dField, $json, $divideFolders);
					  $datos = array(); 
					  $fechaVieja = $fechaNueva;
					} else if (strcmp($fechaVieja,$fechaNueva) == 0) {
					  //echo $fechaNueva."<br/><br/>";
					  $datos[] = $fila;
					}
				  
				}
			  
			    if (count($datos) > 0) {
				  $json = json_encode($datos);	
				  echo "Data2  ".$fechaNueva."    '".$dField."'   Total ".count($datos)."<br/><br/>";
				  //echo "saveJSON2   ".$fechaNueva."  ".$dField.":<br/><br/>".$json."<br/><br/>";
				  $this->saveJsonFile($fechaNueva, $dField, $json, $divideFolders);
				  $datos = array();
			    }
				
			    if ($delete) {
				  $fieldsSelDel = array();
				  $SqlDel = "";
					
				   if (isset($primaryKey) && !empty($primaryKey)) {
					$SqlSelDel = "SELECT ".$primaryKey." FROM ".$tablas." WHERE ".$uniqueKey." = '".$dField."' ORDER BY ".
					$colFecha." DESC LIMIT ".$keepTotalRows; 
					
					$querySelDel = $conexion->prepare($SqlSelDel);
					$querySelDel->execute();
					 
						while($filaSelDel = $querySelDel->fetch(PDO::FETCH_ASSOC)) {
							$fieldsSelDel[] = $filaSelDel[$primaryKey];
							//var_dump($filaSelDel[$primaryKey]);
							//echo "<br/><br/>";
						}
					
					  echo "<br/><br/>";
					  echo $SqlSelDel.".  Total Backup ".count($fieldsSelDel).".<br/><br/>";
					
					  // only id field in array
					   if (count($fieldsSelDel) > 0) {
						 $dataFromSelDel = implode(",",$fieldsSelDel);
						  $SqlDel = "DELETE FROM ".$tablas." WHERE ".$uniqueKey." = '".$dField."' AND ".
						  $primaryKey." NOT IN (".$dataFromSelDel.")";
					    } else {
						  $SqlDel = "DELETE FROM ".$tablas." WHERE ".$uniqueKey." = '".$dField."'"; 
						}
					} else {
					  $SqlDel = "DELETE FROM ".$tablas." WHERE ".$uniqueKey." = '".$dField."'"; 
					}
					 
					 //echo $SqlDel."<br/><br/>";
					 $querySelDel = $conexion->prepare($SqlDel);
					 $querySelDel->execute();
					
					//$keepTotalRows
				}
			  
		   } // foreach $dField
		
		$conexion = null;
	  
	  return true;
	}
	
	public function dbToJson($col, $tab, $condiciones) {
	  $json = "";
	  $columnas = implode(",", $col);
	  $tablas = implode(",", $tab);
	  $where = "";
	  $datos = array();
	  $conexion = Conexion::singleton_conexion();
		
		if (isset($condiciones) && !empty($condiciones)) {
			$where = "WHERE ".$condiciones;
		}
 
		$sql = "SELECT ".$columnas." from ".$tablas." ".$where;
		$query = $conexion->prepare($sql);
		$query->execute();
		
		  while($fila = $query->fetch(PDO::FETCH_ASSOC)) {
			 $datos[] = $fila;
		  }
		  
		$conexion = null;
		
	    $json = json_encode($datos);
	  
	  return $json;
	}
	
	public function dbToArray($col, $tab, $condiciones) {
	  $columnas = implode(",", $col);
	  $tablas = implode(",", $tab);
	  $where = "";
	  $datos = array();
	  $conexion = Conexion::singleton_conexion();
		
		if (isset($condiciones) && !empty($condiciones)) {
			$where = "WHERE ".$condiciones;
		}
 
		$sql = "SELECT ".$columnas." from ".$tablas." ".$where;
		$query = $conexion->prepare($sql);
		$query->execute();
		
		  while($fila = $query->fetch(PDO::FETCH_ASSOC)) {
			 $datos[] = $fila;
		  }
		  
		$query->free();
		$conexion = null;
		
	  return $datos;
	}
	
}