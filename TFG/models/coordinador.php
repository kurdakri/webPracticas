<?php
require_once("database.php");

class Coordinador{
	private $db;
	private $con;
	private $login;
	private $password;
	private $nombre;
	private $telefono;
	private $email;
	
	//Constructor de la clase
	public function __construct(){
        $this->db = new Database();		
	}

	public function set($n,$l,$p,$t,$e){
		$this->nombre = $n;
		$this->login = $l;
		$this->password= $p;
		$this->telefono = $t;
		$this->email = $e;
	}
	
	public function selectAll(){
		$this->con = $this->db->getConnection();
		$sql = 'select * from coordinador';
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
	
	public function select($id){
		$this->con = $this->db->getConnection();
		$sql = 'select * from coordinador where login="'.$id.'"';
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
	
	//Inserta coordinador
	public function insert(){
        $this->con = $this->db->getConnection();
		$sql = 'insert into coordinador(login,password,nombre,email,telefono) values("' . $this->login . '","' .$this->password. '","' .$this->nombre. '","' .$this->email. '","' .$this->telefono. '")';
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
	
	//Actualiza coordinador
	public function update($id){
		$this->con = $this->db->getConnection();
		$sql = 'update coordinador set login="' . $this->login . '",password="' . $this->password . '",nombre="' . $this->nombre . '",email="' . $this->email . '",telefono="' . $this->telefono . '" where login="' . $id.'"';
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
	
	//Borra coordinador
	public function delete(){
		$this->con = $this->db->getConnection();
		$sql = 'delete from coordinador where login="'.$this->login.'"';
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