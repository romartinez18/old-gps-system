<?php
session_start();

/*
if (isset($_SESSION['nickname']) && isset($_SESSION['auth'])) {
header("Location: index.php");		
} else if (isset($_POST['login'])) {
	$usuario = htmlspecialchars($_POST['usuario']);
	$clave = htmlspecialchars($_POST['password']);
	
	if ($usuario == 'admin' && $clave == 'admin') {
     $_SESSION['nickname']= base64_encode($usuario);
     $_SESSION['auth']= 'ok';
	 header("Location: index.php");	
    }
}
*/

include_once('class/class.config.php');
include_once('class/class.pdo.php');

$connection = new PDO('mysql:host='.SERVIDOR.';dbname='.DATABASE, USUARIOBD, CLAVEBD);
$software = new NotORM($connection);

foreach ($software->application()->order("title") as $application) { // get all applications ordered by title
    echo "$application[title]\n"; // print application title
    echo $application->author["name"] . "\n"; // print name of the application author
    foreach ($application->application_tag() as $application_tag) { // get all tags of $application
        echo $application_tag->tag["name"] . "\n"; // print the tag name
    }
}

?>
<html>
<head>
<title>Iniciar Sesi&oacute;n</title>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.0.0.js"></script>
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/login.css">
</head>
	
<body onload="document.frmlogin.usuario.focus();">
<div class="container">
  
  <div class="row" id="pwd-container"><br/><br/><br/>
    <div class="col-md-4"></div>
    <div class="col-md-4">
      <section class="login-form">
        <form method="post" id="frmlogin" name="frmlogin" action="#" role="login">
          <img src="images/logo.png" class="img-responsive" alt="" />
          <input type="text" name="usuario" id="usuario" maxlength="15" placeholder="Nombre de Usuario" required class="form-control input-lg" value="" />
          <input type="password" class="form-control input-lg" maxlength="15" id="password" name="password" placeholder="Contrase&ntilde;a" required="" />
          <button type="submit" name="login" class="btn btn-lg btn-primary btn-block">Iniciar Sesi&oacute;n</button>
        </form>
      </section>  
      </div>
      
      <div class="col-md-4"></div>
  </div>
  
</div>
</body>
</html> 
