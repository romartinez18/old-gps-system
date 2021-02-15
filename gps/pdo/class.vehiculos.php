<?php

include_once('../class/class.conexion.php');

class Vehiculos {
	
    private static $instancia;
    private $dbh;
	private $tabla = "vehiculos";
	
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

    public function get_vehiculos() {
        try {
            $query = $this->dbh->prepare('select * from '.$this->tabla);
            $query->execute();
            return $query->fetchAll();
            //$this->dbh = null;
        }catch (PDOException $e) {
            $e->getMessage();
        }
    }
	
    public function get_vehiculo($id) {
        try {
            $query = $this->dbh->prepare('select * from '.$this->tabla.' where idvehiculo = ?');
			$query->bindParam(1, $id);
            $query->execute();
            return $query->fetchAll();
            //$this->dbh = null;
        }catch (PDOException $e) {
            $e->getMessage();
        }
    }

    public function delete_vehiculo($idVeh)
    {
        try {
            $query = $this->dbh->prepare('delete from '.$this->tabla.' where idvehiculo = ?');
            $query->bindParam(1, $idVeh);
            return $query->execute();
            //$this->dbh = null;
        } catch (PDOException $e) {
            $e->getMessage();
        }
    }

    public function add_vehiculo($placa,$descrip,$tipo,$idgps,$limVel,$foto)
    {
        try {
            $query = $this->dbh->prepare('insert into '.$this->tabla.'(placa, descripcion, tipo, idgps, limitevel, foto) values(?,?,?,?,?,?)');
            $query->bindParam(1, $placa);
			$query->bindParam(2, $descrip);
			$query->bindParam(3, $tipo);
            $query->bindParam(4, $idgps);
            $query->bindParam(5, $limVel);
			$query->bindParam(6, $foto);
            return $query->execute();
            //$this->dbh = null;
        } catch (PDOException $e) {
            $e->getMessage();
        }
    }

    public function update_vehiculo($idVeh,$placa,$descrip,$tipo,$idgps,$limVel,$foto)
    {
        try {
            $query = $this->dbh->prepare('update '.$this->tabla.' SET placa = ?, descripcion = ?, tipo = ?,
			idgps = ?, limitevel = ?, foto = ? WHERE idvehiculo = ?');
            $query->bindParam(1, $placa);
			$query->bindParam(2, $descrip);
            $query->bindParam(3, $tipo);
            $query->bindParam(4, $idgps);
            $query->bindParam(5, $limVel);
			$query->bindParam(6, $foto);
			$query->bindParam(7, $idVeh);
            return $query->execute();
            //$this->dbh = null;
        } catch (PDOException $e) {
            $e->getMessage();
        }
    }

    public function __clone()
    {
        trigger_error('La clonación no es permitida!.', E_USER_ERROR);
    }
}
?>