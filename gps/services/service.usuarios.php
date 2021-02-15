<?php

require_once '../pdo/class.usuarios.php';
$tabla = 'usuarios';

// Get job (and id)
$accion = '';
$id  = '';
$cUsuarios = Usuarios::singleton();
// Acciones
if (isset($_GET['accion'])){
  $accion = $_GET['accion'];
  if ($accion == 'get_usuarios' ||
      $accion == 'get_usuario'   ||
      $accion == 'add_usuario'   ||
      $accion == 'edit_usuario'  ||
      $accion == 'delete_usuario'){
    if (isset($_GET['id'])){
      $id = $_GET['id'];
      if (!is_numeric($id)){
        $id = '';
      }
    } else if (isset($_GET['idusuario'])){
      $id = $_GET['idusuario'];
      if (!is_numeric($id)){
        $id = '';
      }
    } else if (isset($_GET['cedula'])){
      $id = $_GET['cedula'];
      /*if (!is_numeric($id)){
        $id = '';
      }*/
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
  
  // Accion lista de Usuarios
  if ($accion == 'get_usuarios'){
	  $listUsuarios = $cUsuarios->get_usuarios();
	  //print_r($listUsuarios);
    if (count($listUsuarios) <= 0){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';

	   foreach($listUsuarios as $filas) {
        $functions  = '<div class="function_buttons"><ul>';
        $functions .= '<li class="function_edit"><a data-id="'.$filas['idusuario'].'" data-name="'.$filas['nombres']. '"><span>Edit</span></a></li>';
        $functions .= '<li class="function_delete"><a data-id="' . $filas['idusuario'] . '" data-name="' . $filas['nombres'] . '"><span>Delete</span></a></li>';
        $functions .= '</ul></div>';
        $datos[] = array(
          "cedula" => $filas['cedula'],
          "nombres" => $filas['nombres'],
          "apellidos" => $filas['apellidos'],
          "fecharegistro" => $filas['fecharegistro'],
          "email" => $filas['email'],
          "functions" => $functions
        );
      }
    }
    
  } elseif ($accion == 'get_usuario'){

    if ($id == ''){
      $result  = 'error';
      $message = 'Cedula missing';
    } else {
		$listUsuarios = $cUsuarios->get_usuario($id);
		 
      if (count($listUsuarios) <= 0){
        $result  = 'error';
        $message = 'query error';
      } else {
        $result  = 'success';
        $message = 'query success';
		$filas = $listUsuarios[0];
		
          $datos[] = array(
          "cedula"  => $filas['cedula'],
          "nombres"  => $filas['nombres'],
          "apellidos"    => $filas['apellidos'],
		  "foto" => $filas['foto'],
		  "ciudad" => $filas['ciudad'],
		  "estado" => $filas['estado'],
		  "direccion" => $filas['direccion'],
          "email"   => $filas['email'],
		  "celular" => $filas['celular'],
		  "celular2" => $filas['celular2'],
		  "celular3" => $filas['celular3'],
		  "telefono" => $filas['telefono'],
          );
      }
    }
  
  } elseif ($accion == 'add_usuario'){
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
  
  }
  
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