<?php

require_once '../pdo/class.listado.php';
$tabla = 'listado_vehiculos';

// Get job (and id)
$accion = '';
$id  = '';
$cListado = Listado::singleton();
// Acciones
if (isset($_GET['accion'])){
  $accion = $_GET['accion'];
  if ($accion == 'get_listado' ||
      $accion == 'get_listadousuario'){
    if (isset($_GET['id'])){
      $id = $_GET['id'];
      if (!is_numeric($id)){
        $id = '';
      }
    } else if (isset($_GET['iduser'])){
      $id = $_GET['iduser'];
      if (!is_numeric($id)){
        $id = '';
      }
    }
  } else {
    $accion = '';
  }
}

// Prepare array
$datos = array();
$result = '';
$message = '';

// Valid action found
if ($accion != ''){

  // Accion listado
  if ($accion == 'get_listado'){
	  $list = $cListado->get_listado();
    if (count($list) <= 0){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';

	   foreach($list as $filas) {
        $functions  = '<div class="function_buttons"><ul>';
        $functions .= '<li class="function_edit"><a data-id="'.$filas['iduser'].'" data-name="'.$filas['name']. '"><span>Edit</span></a></li>';
        $functions .= '<li class="function_delete"><a data-id="' . $filas['iduser'] . '" data-name="' . $filas['name'] . '"><span>Delete</span></a></li>';
        $functions .= '</ul></div>';
          $datos[] = array(
          "iduser"  => $filas['iduser'],
          "idveh"  => $filas['idveh'],
          "idgps"    => $filas['idgps'],
		  //"nick" => $filas['nick'],
      "nick" => $filas['name'],
		  "cedula" => $filas['cedula'],
		  "name" => $filas['name'],
		  "lastname" => $filas['lastname'],
		  "serial" => $filas['serial'],
          "celgps"   => $filas['celgps'],
		  "mail"   => $filas['mail'],
		  "cel" => $filas['cel'],
		  "auth" => $filas['auth'],
		  "activo" => $filas['activo'],
		  "registro" => $filas['registro'],
		  "placa" => $filas['placa'],
		  "descrip" => $filas['descrip'],
		  "tipo" => $filas['tipo'],
		  "activogps" => $filas['activogps'],
		  "diacorte" => $filas['diacorte'],
		  "registrogps" => $filas['registrogps'],
		  "functions" => $functions
          );
      }
    }

  } elseif ($accion == 'get_listadousuario'){

    if ($id == ''){
      $result  = 'error';
      $message = 'Id Usuario sin encontrar';
    } else {
		$list = $cListado->get_listadousuario($id);

      if (count($list) <= 0){
        $result  = 'error';
        $message = 'query error';
      } else {
        $result  = 'success';
        $message = 'query success';
		$filas = $listUsuarios[0];

          $datos[] = array(
          "iduser"  => $filas['iduser'],
          "idveh"  => $filas['idveh'],
          "idgps"    => $filas['idgps'],
		  "nick" => $filas['nick'],
		  "cedula" => $filas['cedula'],
		  "name" => $filas['name'],
		  "lastname" => $filas['lastname'],
		  "serial" => $filas['serial'],
          "celgps"   => $filas['celgps'],
		  "mail"   => $filas['mail'],
		  "cel" => $filas['cel'],
		  "auth" => $filas['auth'],
		  "activo" => $filas['activo'],
		  "registro" => $filas['registro'],
		  "placa" => $filas['placa'],
		  "descrip" => $filas['descrip'],
		  "tipo" => $filas['tipo'],
		  "activogps" => $filas['activogps'],
		  "diacorte" => $filas['diacorte'],
		  "registrogps" => $filas['registrogps'],
		  "functions" => $functions
          );
      }
    }

  }/* elseif ($accion == 'add_usuario'){
	 if ($id == ''){
      $result  = 'error';
      $message = 'Cedula missing';
     } else {
		$ced = $_GET['cedula'];
		  if (!isset($ced) || empty($ced)) {$ced = "0";}
		$nombre = $_GET['nombres'];
		  if (!isset($nombre) || empty($nombre)) {$nombre = " ";}
		$apellido = $_GET['apellidos'];
		  if (!isset($apellido) || empty($apellido)) {$apellido = " ";}
		$foto = $_GET['foto'];
		  if (!isset($foto) || empty($foto)) {$foto = " ";}
		$city = $_GET['ciudad'];
		  if (!isset($city) || empty($city)) {$city = 64;}
		$state = $_GET['estado'];
		  if (!isset($state) || empty($state)) {$state = 4;}
		$dir = $_GET['direccion'];
		  if (!isset($dir) || empty($dir)) {$dir = " ";}
		$email = $_GET['email'];
		  if (!isset($email) || empty($email)) {$email = " ";}
		$cel1 = $_GET['celular'];
		  if (!isset($cel1) || empty($cel1)) {$cel1 = " ";}
		$cel2 = $_GET['celular2'];
		  if (!isset($cel2) || empty($cel2)) {$cel2 = " ";}
		$cel3 = $_GET['celular3'];
		  if (!isset($cel3) || empty($cel3)) {$cel3 = " ";}
		$tel = $_GET['telefono'];
		  if (!isset($tel) || empty($tel)) {$tel = " ";}

		$arr = array(
		  "cedula" => $ced,
		  "nombres" => $nombre,
		  "apellidos" => $apellido,
		  "foto" => $foto,
		  "tipo" => 2,
		  "distribuidor" => 0,
		  "cantidadusuario" => 0,
		  "ciudad" => $city,
		  "estado" => $state,
		  "direccion" => $dir,
		  "email" => $email,
		  "celular" => $cel1,
		  "celular2" => $cel2,
		  "celular3" => $cel3,
		  "telefono" => $tel,
		  "nivelautorizacion" => 5,
		  "activo" => 1,
		  "fecharegistro" => date("Y-m-d")
		);

		//$ok = $cUsuarios->add_usuario($arr, $id);
		$ok = $cUsuarios->add_usuario($arr);

			if (!$ok){
			  $result  = 'error';
			  $message = 'query error';
			} else {
			  $result  = 'success';
			  $message = 'query success';
			}
	 }
  } elseif ($accion == 'edit_usuario') {

    // Edit
    if ($id == ''){
      $result  = 'error';
      $message = 'Cedula missing';
    } else {
		$ced = $_GET['cedula'];
		  if (!isset($ced) || empty($ced)) {$ced = "";}
		$nombre = $_GET['nombres'];
		  if (!isset($nombre) || empty($nombre)) {$nombre = "";}
		$apellido = $_GET['apellidos'];
		  if (!isset($apellido) || empty($apellido)) {$apellido = "";}
		$foto = $_GET['foto'];
		  if (!isset($foto) || empty($foto)) {$foto = "";}
		$city = $_GET['ciudad'];
		  if (!isset($city) || empty($city)) {$city = 64;}
		$state = $_GET['estado'];
		  if (!isset($state) || empty($state)) {$state = 4;}
		$dir = $_GET['direccion'];
		  if (!isset($dir) || empty($dir)) {$dir = "";}
		$email = $_GET['email'];
		  if (!isset($email) || empty($email)) {$email = "";}
		$cel1 = $_GET['celular'];
		  if (!isset($cel1) || empty($cel1)) {$cel1 = "";}
		$cel2 = $_GET['celular2'];
		  if (!isset($cel2) || empty($cel2)) {$cel2 = "";}
		$cel3 = $_GET['celular3'];
		  if (!isset($cel3) || empty($cel3)) {$cel3 = "";}
		$tel = $_GET['telefono'];
		  if (!isset($tel) || empty($tel)) {$tel = "";}

		$arr = array(
		  "cedula" => $ced,
		  "nombres" => $nombre,
		  "apellidos" => $apellido,
		  "foto" => $foto,
		  "tipo" => 2,
		  "distribuidor" => 0,
		  "cantidadusuario" => 0,
		  "ciudad" => $city,
		  "estado" => $state,
		  "direccion" => $dir,
		  "email" => $email,
		  "celular" => $cel1,
		  "celular2" => $cel2,
		  "celular3" => $cel3,
		  "telefono" => $tel,
		  "nivelautorizacion" => 5,
		  "activo" => 1,
		  "fecharegistro" => date("Y-m-d")
		);


		$ok = $cUsuarios->update_usuario($arr, $id);

		   if (!$ok){
			 $result  = 'error';
			 $message = 'query error';
		   } else {
			 $result  = 'success';
			 $message = 'query success';
		   }
    }

  } elseif ($accion == 'delete_usuario'){

    // Delete
    if ($id == ''){
      $result  = 'error';
      $message = 'Cedula missing';
    } else {
	  $listUsuarios = $cUsuarios->delete_usuario($id);

      if (!$listUsuarios){
        $result  = 'error';
        $message = 'query error';
      } else {
        $result  = 'success';
        $message = 'query success';
      }
    }

  }*/

}

// Prepare data
$data = array(
  "result"  => $result,
  "message" => $message,
  "data"    => $datos
);

// Convert PHP array to JSON array
$json_data = json_encode($data);
print $json_data;

?>
