<?php

include_once('../class/class.conexion.php');

class Listado {
	
    private static $instancia;
    private $dbh;
	private $tabla = "listado_vehiculos";
	
    private function __construct()
    {
       $this->dbh = Conexion::singleton_conexion();
    }
	
    public static function singleton() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function get_listado() {
        try {
            $query = $this->dbh->prepare('select * from '.$this->tabla);
            $query->execute();
            return $query->fetchAll();
            //$this->dbh = null;
        }catch (PDOException $e) {
            $e->getMessage();
        }
    }
	
    public function get_listadousuario($id) {
        try {
            $query = $this->dbh->prepare('select * from '.$this->tabla.' where idusser = ?');
			$query->bindParam(1, $id);
            $query->execute();
            return $query->fetchAll();
            //$this->dbh = null;
        }catch (PDOException $e) {
            $e->getMessage();
        }
    }

/*    public function delete_usuario($id)
    {
        try {
            $query = $this->dbh->prepare('delete from '.$this->tabla.' where idusuario = ?');
            $query->bindParam(1, $id);
            return $query->execute();
            //$this->dbh = null;
        } catch (PDOException $e) {
            $e->getMessage();
        }
    }

    //public function add_usuario($ced,$nombre,$apellido,$fecha,$email)
	public function add_usuario($arr)
    {  
        try {
            $query = $this->dbh->prepare('insert into '.$this->tabla.'(cedula, nombres, apellidos, foto, tipo, distribuidor, cantidadusuario, ciudad, estado, direccion, email, celular, celular2, celular3, telefono, nivelautorizacion, activo, fecharegistro) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
			$i = 1;
			
			 foreach ($arr as $key =>$val) {
				 echo "Key(".$i."): ".$key."  Val: ".$val;
			  $query->bindParam($i, $val);
			  $i++;
			 }
            /*$query->bindParam(1, $id);
			$query->bindParam(2, $nombre);
			$query->bindParam(3, $apellido);
            $query->bindParam(4, $fecha);
            $query->bindParam(5, $email);
            $query->bindParam(6, $id);
			$query->bindParam(7, $nombre);
			$query->bindParam(8, $apellido);
            $query->bindParam(9, $fecha);
            $query->bindParam(10, $email);
            $query->bindParam(11, $id);
			$query->bindParam(12, $nombre);
			$query->bindParam(13, $apellido);
            $query->bindParam(14, $fecha);
            $query->bindParam(15, $email);
            return $query->execute();
            //$this->dbh = null;
        } catch (PDOException $e) {
            $e->getMessage();
        }
    }

    public function update_usuario($arr, $id)
    {
        try {
			$query = $this->dbh->prepare('update '.$this->tabla.' SET cedula = ?, nombres = ?, apellidos = ?, foto = ?, tipo = ?, distribuidor = ?, cantidadusuario = ?, ciudad = ?, estado = ?, direccion = ?, email = ?, celular = ?, celular2 = ?, celular3 = ?, telefono = ?, nivelautorizacion = ?, activo = ?, fecharegistro = ? WHERE idusuario = ?');
			$i = 1;
			
			 foreach ($arr as $key =>$val) {
			  $query->bindParam($i, $key[$val]);
			  $i++;
			 }
			 
			$query->bindParam($i, $id);
            /*$query->bindParam(1, $nombre);
			$query->bindParam(2, $apellido);
            $query->bindParam(3, $fecha);
            $query->bindParam(4, $email);
            $query->bindParam(5, $id);
            return $query->execute();
            //$this->dbh = null;
        } catch (PDOException $e) {
            $e->getMessage();
        }
    }*/

    public function __clone()
    {
        trigger_error('La clonación no es permitida!.', E_USER_ERROR);
    }
}
?>