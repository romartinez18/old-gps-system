<?php
 
require_once 'class.conexion.php';
include_once('class.config.php');
session_start();

class Login
{
 
    private static $instancia;
    private $dbh;
	private $tabla;
	
    private function __construct($tLogin)
    {
	$this->tabla = $tLogin;
        $this->dbh = Conexion::singleton_conexion();
 
    }
	
    public static function singleton_login($tLogin)
    {
 
        if (!isset(self::$instancia)) {
 
            $miclase = __CLASS__;
            self::$instancia = new $miclase($tLogin);
 
        }
 
        return self::$instancia;
 
    }
    
    public function login_usuarios($nick,$password)
    {   
	$password = $password.SEMILLA;
        $clave = hash('sha512',$password);
        try {
            //echo $clave;
            //$sql = "SELECT * from ".$this->tabla." WHERE (usuario = ? AND clave = ?)";
	    $sql = "SELECT * from ".$this->tabla." WHERE (usuario = '".$nick."' AND clave = '".$clave."')";
            $query = $this->dbh->prepare($sql);
            $query->execute();
            //$this->dbh = null;
 
            //si existe el usuario
            if($query->rowCount() == 1)
            {
                 $fila  = $query->fetch();
                 $_SESSION['id'] = base64_encode($fila['idusuario']); 
                 $_SESSION['usuario'] = base64_encode($fila['usuario']);
                 
                 $sql = "SELECT * from usuarios WHERE (idusuario = ".$fila['idusuario'].")";
            	 $query = $this->dbh->prepare($sql);
                 $query->execute();
           	 $this->dbh = null;
           	 
                 $fila  = $query->fetch();
                 $_SESSION['cedula'] = base64_encode($fila['cedula']);
                 $_SESSION['nivel'] = base64_encode($fila['nivelautorizacion']); 
                 $_SESSION['tipo'] = base64_encode($fila['tipo']);
                 $_SESSION['distribuidor'] = base64_encode($fila['distribuidor']); 
                 $_SESSION['fregistro'] = base64_encode($fila['fecharegistro']);
                        
               return TRUE;
            } 
            
        }catch(PDOException $e){
            
            print "Error!: " . $e->getMessage();
            
        }        
       return FALSE;
    }
    
 
     // Evita que el objeto se pueda clonar
    public function __clone()
    {
 
        trigger_error('La clonacion de este objeto no esta permitida', E_USER_ERROR);
 
    }
 
}