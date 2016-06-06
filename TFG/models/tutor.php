<?php
require_once("database.php");

class Tutor{
	private $db;
	private $con;
	private $nombre;
	private $apellidos;
	private $dni;
	private $telefono;
	private $email;
	private $login;
	private $password;
	private $departamento;
	private $centro;
	
	//Constructor de la clase
	public function __construct(){
        $this->db = new Database();		
	}

	public function set($nom,$ape,$dn,$tel,$ema,$log,$pas,$dep,$cen){
		$this->nombre = $nom;
		$this->apellidos = $ape;
		$this->dni = $dn;
		$this->telefono = $tel;
		$this->email = $ema;
		$this->login = $log;
		$this->password = $pas;
		$this->departamento = $dep;
		$this->centro = $cen;
	}
	
	public function select($login){
		$this->con = $this->db->getConnection();
		$sql = 'select * from tutor where login="' . $login . '"';
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
	
	public function selectByDNI($id){
		$this->con = $this->db->getConnection();
		$sql = 'select * from tutor where dni="' . $id . '"';
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
	
	public function selectById($id){
		$this->con = $this->db->getConnection();
		$sql = 'select * from tutor where idTutor="' . $id . '"';
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
	
	public function selectAll(){
		$this->con = $this->db->getConnection();
		$sql = 'select * from tutor';
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
		$sql = 'insert into tutor(nombre,apellidos,dni,telefono,email,login,password,departamento,centro) values("' . $this->nombre . '","' .$this->apellidos. '","' .$this->dni. '","' .$this->telefono. '","' .$this->email. '","' .$this->login. '","' .$this->password. '","' .$this->departamento. '","' .$this->centro. '")';
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
		$sql = 'update tutor set nombre="' . $this->nombre . '",apellidos="' . $this->apellidos . '",dni="' . $this->dni . '",telefono="' . $this->telefono . '",email="' . $this->email . '",login="' . $this->login . '",password="' . $this->password . '",departamento="' . $this->departamento . '",centro="' . $this->centro . '" where login="' . $id.'"';
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
		$sql = 'delete from tutor where login="'.$id.'"';
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