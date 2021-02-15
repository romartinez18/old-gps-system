<?php

require_once('../class/class.login.php');

$login = Login::singleton_login('login');

  if (isset($_POST['vUser']) && isset($_POST['vPass'])) {
    $Usuario = htmlspecialchars(strip_tags(trim($_POST['vUser'])));
    $Clave = htmlspecialchars(strip_tags(trim($_POST['vPass'])));

	 if (strlen($Usuario) < 6 || strlen($Clave) < 8) {
	   echo "El usuario y la clave debe tener minimo 6 caracteres";
	 } else {
	  $Sesion = $login->login_usuarios($Usuario,$Clave);
    $nivel = 0;
	$activo = 0;
	
     if (isset($_SESSION['nivel']) && !empty($_SESSION['nivel']))  {
	     $nivel = base64_decode($_SESSION['nivel']);
     }
	 
	 if (isset($_SESSION['activo']) && !empty($_SESSION['activo']) && $_SESSION['activo'] == 1)  {
	    $activo = 1;
     }

		if ($Sesion && isset($nivel) && $nivel > 1) {
			if ($activo === 1) {
			  echo 'ok';
			} else {
			  echo 'Usuario Inactivo. Contacte con el Administrador';
			}
		} else if ($Sesion && isset($nivel) && $nivel == 1) {
		  echo 'admin';
		} else {
		  echo "Usuario o Clave Incorrecta";
		}
	 }


  } else {
    echo "Debe ingresar un Usuario y una Clave";
  }

?>
