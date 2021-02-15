<?php

include_once('../class/class.conexion.php');

class Gps {
	
    private static $instancia;
    private $dbh;
	private $tabla = "gps";
	
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

    public function get_listgps() {
        try {
            $query = $this->dbh->prepare('select * from '.$this->tabla);
            $query->execute();
            return $query->fetchAll();
            //$this->dbh = null;
        }catch (PDOException $e) {
            $e->getMessage();
        }
    }
	
    public function get_gps($id) {
        try {
            $query = $this->dbh->prepare('select * from '.$this->tabla.' where idgps = ?');
			$query->bindParam(1, $id);
            $query->execute();
            return $query->fetchAll();
            //$this->dbh = null;
        }catch (PDOException $e) {
            $e->getMessage();
        }
    }

    public function delete_gps($id)
    {
        try {
            $query = $this->dbh->prepare('delete from '.$this->tabla.' where idgps = ?');
            $query->bindParam(1, $id);
            return $query->execute();
            //$this->dbh = null;
        } catch (PDOException $e) {
            $e->getMessage();
        }
    }

    public function add_gps($marca,$modelo,$serial,$cel,$activo,$diacorte,$fecharegistro)
    {
        try {
            $query = $this->dbh->prepare('insert into '.$this->tabla.'(idmarca, idmodelo, serial, celular1, activo, diacorte, fecharegistro) values(?,?,?,?,?,?,?,?)');
            $query->bindParam(1, $marca);
			$query->bindParam(2, $modelo);
			$query->bindParam(3, $serial);
            $query->bindParam(4, $cel);
            $query->bindParam(5, $activo);
			$query->bindParam(6, $diacorte);
			$query->bindParam(7, $fecharegistro);
            return $query->execute();
            //$this->dbh = null;
        } catch (PDOException $e) {
            $e->getMessage();
        }
    }

    public function update_gps($id,$marca,$modelo,$serial,$cel,$activo,$diacorte,$fecharegistro)
    {
        try {
            $query = $this->dbh->prepare('update '.$this->tabla.' SET idmarca = ?, idmodelo = ?,
			serial = ?, celular1 = ?, activo = ?, diacorte = ?, fecharegistro = ? WHERE idgps = ?');
            $query->bindParam(1, $marca);
			$query->bindParam(2, $modelo);
			$query->bindParam(3, $serial);
            $query->bindParam(4, $cel);
            $query->bindParam(5, $activo);
			$query->bindParam(6, $diacorte);
			$query->bindParam(7, $fecharegistro);
			$query->bindParam(8, $id);
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