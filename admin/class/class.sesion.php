<?php

function session_is_registered($x)
{
    if (isset($_SESSION['$x']))
    return true;
    else
    return false;
}

class sesion {

 private $clavex = '';
 private $k = '';
 
 // Crea un ID de sesion relacionado a una llave y a la IP del usuario
 public function __construct($clave=NULL,$tiempo) {
 
   if(is_null($clave)){
	self::limpiar();
	print_r("Autorizaci&oacute;n no v&aacute;lida. Acceso denegado.");
	exit();
   }elseif(!is_null($clave)){
		
		if(!isset($_SESSION)){session_start();}
		
		 $ip=$this->getIP();
		 $md5=(md5($clave)+md5($ip));
			if($tiempo==0){
			  $this->tiempo=$this->encriptar(time());
			  $this->md5s=$this->encriptar(md5($clave)+md5($ip));
			  $this->clavex=$clave;
			//}elseif ((md5($clave)+md5($ip)==$this->desencriptar($this->md5s))and
			//  ((time()-$this->desencriptar($this->tiempo))<$tiempo)){
			}elseif ($tiempo>=0) {
			    $this->tiempo=$this->encriptar($tiempo);
				$this->md5s=$this->encriptar(md5($clave)+md5($ip));
				$this->clavex=$clave;
			}else{
			    self::limpiar();
				print_r('Autorizaci&oacute;n no v&aacute;lida. Acceso denegado..');
				exit;
			}
	} else {
	  self::limpiar();
	  print_r('Autorizaci&oacute;n no v&aacute;lida. Acceso denegado.');
	  exit;
	}
 }
 
 // _SET: es para agregarle un valor a una sesion, de no estar registrada la registra
 public function __set($variable,$valor) {
	
	//if(!isset($_SESSION[md5($variable.$this->clavex)])
	  //$this->registrar(md5($variable.$this->clavex));
	
	$_SESSION[md5($variable.$this->clavex)]=$this->encriptar($valor);
 }
 
 // _GET: Obtiene el valor de una sesion, de no estar registrada regresa false
 public function __get($variable) {

	if(isset($_SESSION[md5($variable.$this->clavex)]))
	 return $this->desencriptar($_SESSION[md5($variable.$this->clavex)]);
	else
	 return false;
 }
 
 private function getIP(){
 
  if(filter_var(getenv("HTTP_CLIENT_IP"),FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)&&
  strcasecmp(getenv("HTTP_CLIENT_IP"),"0.0.0.0")){
    $ip=getenv("HTTP_CLIENT_IP");
  }elseif(filter_var(getenv("HTTP_X_FORWARDED_FOR"),FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)&&
  strcasecmp(getenv("HTTP_X_FORWARDED_FOR"),"0.0.0.0")){
    $ip=getenv("HTTP_X_FORWARDED_FOR");
  }elseif(filter_var(getenv("REMOTE_ADDR"),FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)&&
  strcasecmp(getenv("REMOTE_ADDR"), "0.0.0.0")){
    $ip=getenv("REMOTE_ADDR");
  } elseif(isset($_SERVER['REMOTE_ADDR'])&&filter_var(getenv($_SERVER['REMOTE_ADDR']),
  FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)&&strcasecmp($_SERVER['REMOTE_ADDR'],"0.0.0.0")){
    $ip=$_SERVER['REMOTE_ADDR'];
  }else{
    error('Autorización no válida. Acceso denegado');
    exit;
  }
  
 return $ip;
}
 
 // Registra la variable como sesion
 private function registrar($variable) {
 
	if(!$_SESSION[md5($variable.$this->clavex)])
	session_register(md5($variable.$this->clavex));
 }
 
 // Borra y destruye una sesion
 public function borrar($variable) {

    $_SESSION[md5($variable.$this->clavex)]='';
	unset($_SESSION[md5($variable.$this->clavex)]);
 }
 
 // Destruye por completo todas las sesiones
 public function limpiar() {
	session_unset();
 }
 
 // Funcion para saber cuantos
 public function usuarioEnlinea() {
 $count=0;
 $handle=opendir(session_save_path());

  if($handle==false) return-1;
  
	while (($file = readdir($handle))!=false)
		if (ereg("^sess",$file))
			if(time()-fileatime(session_save_path().'/'.$file)<120) // 120 secs = 2 minutes session
			 $count++;
  
  closedir($handle);
  
  return $count;
 }
 
 //Funciones para encriptamiento propietario de las sesiones
 private function ed($t) {
 
  $r = md5($this->k);
  $c=0;
  $v = "";
  
	for ($i=0;$i<strlen($t);$i++) {
	  if ($c==strlen($r)) $c=0;
	    $v.= substr($t,$i,1) ^ substr($r,$c,1);
	  $c++;
	}
	
  return $v;
 }
 
 
 public function encriptar($t) {
	
	if(!is_array($t)){
	  $t=urlencode($t);
	  srand((double)microtime()*1000000);
	  $r = md5(rand(0,32000));
	  $c=0;
	  $v = "";
		
		for ($i=0;$i<strlen($t);$i++){
		
		  if ($c==strlen($r)) $c=0;
		    $v.= substr($r,$c,1) .(substr($t,$i,1) ^ substr($r,$c,1));
			
	     $c++;
	    }
	
	 $v = base64_encode($this->ed($v));
	 $v = str_replace('+','AbCdE',$v);
	
     return $v;
	}else{
		foreach($t as $x=>$y)
		  $s[$x] = $this->encriptar($y);
	 return $s;
	}
 }
 
 public function desencriptar($t) {

	if(!is_array($t)){
	 $t = str_replace('AbCdE','+',$t);
	 $t = $this->ed(base64_decode($t));
	 $v = "";
		for ($i=0;$i<strlen($t);$i++){
		 $md5 = substr($t,$i,1);
		 $i++;
		 $v.= (substr($t,$i,1) ^ $md5);
		}
		
	 return urldecode($v);
	}else{
	
	  foreach($t as $x=>$y)
	   $s[$x] = $this->desencriptar($y);
	   
	 return $s;
	}
 }
}

?> 
