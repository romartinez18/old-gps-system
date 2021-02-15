<?php


if (isset($_POST['idState']) && !empty($_POST['idState']) && is_numeric($_POST['idState']) && $_POST['idState'] > 0) {
    include_once('../class/class.config.php');
    require_once('../class/class.rb.php');
      R::setup('mysql:host='.SERVIDOR.';dbname='.DATABASE, USUARIOBD, CLAVEBD);
	  
	  $id = $_POST['idState'];
	
	    $cities = R::find('ciudades', ' idestado = ? ', [$id]);

		if (isset($cities) && count($cities) > 0) {
		  $listCities = array();
		  
			foreach($cities as $k) {
			  $listCities[$k->id] = $k->ciudad;
			}
		  echo json_encode(array("codigo"=>"OK", "mensaje"=>"", "datos" => $listCities));	
		} else {
		  echo json_encode(array("codigo"=>"FAIL", "mensaje"=>"Ciudad no encontrada"));	
		}
}


?> 
