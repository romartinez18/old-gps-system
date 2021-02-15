<?php

include_once('class.config.php');

class Conexion
{
    private static $instancia;
    private $dbh;
    private function __construct()
    {
        try {
		   //carga();
			//$Cadena = 'mysql:host='.$SERVIDOR.';dbname='.$DATABASE.'', $USUARIOBD, $CLAVEBD;
            $this->dbh = new PDO('mysql:host='.SERVIDOR.';dbname='.DATABASE, USUARIOBD, CLAVEBD);
            $this->dbh->exec("SET CHARACTER SET utf8");
 
        } catch (PDOException $e) {
 
            print "Error!: " . $e->getMessage();
 
            die();
        }
    }
 
    public function prepare($sql)
    {
 
        return $this->dbh->prepare($sql);
 
    }
    public static function singleton_conexion()
    {
 
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
 
        }
 
        return self::$instancia;
        
    }
 
 
     // Evita que el objeto se pueda clonar
    public function __clone()
    {
 
        trigger_error('La clonacion de este objeto no esta permitida', E_USER_ERROR);
 
    }
}