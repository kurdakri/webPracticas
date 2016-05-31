<?php
require_once("database.php");

class Empresa{
	private $db;
	private $con;
	private $centro;
	private $telefono;
	private $localidad;
	private $provincia;
	private $email;
	private $calle;
	private $nombre;
	private $password;
	private $login;
	private $nombreTutor;
	private $cargoTutor;
	private $tareas;
	
	//Constructor de la clase
	public function __construct(){
        $this->db = new Database();		
	}

	public function set($cen,$tel,$loc,$pro,$ema,$cal,$nom,$pas,$log,$noT,$caT,$tar){
		$this->centro = $cen;
		$this->telefono = $tel;
		$this->localidad = $loc;
		$this->provincia = $pro;
		$this->email = $ema;
		$this->calle = $cal;
		$this->nombre = $nom;
		$this->password = $pas;
		$this->login = $log;
		$this->nombreTutor = $noT;
		$this->cargoTutor = $caT;
		$this->tareas = $tar;
	}
	
	public function selectAll(){
		$this->con = $this->db->getConnection();
		$sql = 'select * from empresa';
        $result = mysql_query($sql, $this->con);
        if (mysql_num_rows($result) == 0) {
			$this->db->endConnection();
            return false;
        } else {
            $toret = array();
            while ($row = mysql_fetch_assoc($result)) {
                $toret[] = $row;
            }
			$this->db->endConnection();
            return $toret;
        }		
	}
	
	public function select($login){
		$this->con = $this->db->getConnection();
		$sql = 'select * from empresa where login="' . $login . '"';
        $result = mysql_query($sql, $this->con);
        if (mysql_num_rows($result) == 0) {
			$this->db->endConnection();
            return false;
        } else {
            $toret = array();
            while ($row = mysql_fetch_assoc($result)) {
                $toret[] = $row;
            }
			$this->db->endConnection();
            return $toret;
        }
	}
	
	public function insert(){
        $this->con = $this->db->getConnection();
		$sql = 'insert into empresa(centro,telefono,localidad,provincia,email,calle,nombre,password,login,nombreTutor,cargoTutor,tareas) values("' . $this->centro . '","' .$this->telefono. '","' .$this->localidad. '","' .$this->provincia. '","' .$this->email. '","' .$this->calle. '","' .$this->nombre. '","' .$this->password. '","' .$this->login. '","' .$this->nombreTutor. '","' .$this->cargoTutor. '","' .$this->tareas. '")';
        $result = mysql_query($sql, $this->con);
        if ($result == true) {
			$this->db->endConnection();
            return true;
        } else {
            echo(mysql_error());
			$this->db->endConnection();
            return false;
        }
	}
	
	public function update($id){
		$this->con = $this->db->getConnection();
		$sql = 'update empresa set centro="' . $this->centro . '",telefono="' . $this->telefono . '",localidad="' . $this->localidad . '",provincia="' . $this->provincia . '",email="' . $this->email . '",calle="' . $this->calle . '",nombre="' . $this->nombre . '",password="' . $this->password . '",login="' . $this->login . '",nombreTutor="' . $this->nombreTutor . '",cargoTutor="' . $this->cargoTutor . '",tareas="' . $this->tareas . '" where login="' . $id.'"';
        $result = mysql_query($sql, $this->con);
        if ($result == true) {
			$this->db->endConnection();
            return true;
        } else {
            echo(mysql_error());
			$this->db->endConnection();
            return false;
        }
	}
	
	public function delete($id){
		$this->con = $this->db->getConnection();
		$sql = 'delete from empresa where login="'.$id.'"';
	    $result = mysql_query($sql,$this->con);
	    if($result == true){
			$this->db->endConnection();	
			return true;
	    }else{
			echo("Error");
			$this->db->endConnection();
			return false;
	    }
	}
}
?>