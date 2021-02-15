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
    } else if (isset($_GET['cedula'])){
      $id = $_GET['cedula'];
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
        $functions .= '<li class="function_edit"><a data-id="'.$filas['cedula'].'" data-name="'.$filas['nombre']. '"><span>Edit</span></a></li>';
        $functions .= '<li class="function_delete"><a data-id="' . $filas['cedula'] . '" data-name="' . $filas['nombre'] . '"><span>Delete</span></a></li>';
        $functions .= '</ul></div>';
        $datos[] = array(
          "cedula" => $filas['cedula'],
          "nombre" => $filas['nombre'],
          "apellido" => $filas['apellido'],
          "fechaNac" => $filas['fechaNac'],
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
          "cedula"          => $filas['cedula'],
          "nombre"  => $filas['nombre'],
          "apellido"    => $filas['apellido'],
          "fechaNac"       => $filas['fechaNac'],
          "email"   => $filas['email']
          );
      }
    }
  
  } elseif ($accion == 'add_usuario'){
	 if ($id == ''){
      $result  = 'error';
      $message = 'Cedula missing';
     } else {
		$nombre = $_GET['nombre'];
		$apellido = $_GET['apellido'];
		$fNac = $_GET['fechaNac'];
		$email = $_GET['email'];
		$listUsuarios = $cUsuarios->add_usuario($id, $nombre, $apellido, $fNac, $email);
		
			if (!$listUsuarios){
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
		$nombre = $_GET['nombre'];
		$apellido = $_GET['apellido'];
		$fNac = $_GET['fechaNac'];
		$email = $_GET['email'];
		$listUsuarios = $cUsuarios->update_usuario($id, $nombre, $apellido, $fNac, $email);
	  
		   if (!$listUsuarios){
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