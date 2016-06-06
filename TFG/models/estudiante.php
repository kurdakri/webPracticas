<?php
require_once("database.php");

class Estudiante{
	private $db;
	private $con;
	private $nombre;
	private $apellidos;
	private $dni;
	private $fechaNac;
	private $email;
	private $telefono;
	private $login;
	private $password;
	private $campus;
	private $facultad;
	private $titulacion;
	private $curso;
	private $inicioTitulacion;
	private $pAntes;
	private $pAntesYear;
	
	//Constructor de la clase
	public function __construct(){
        $this->db = new Database();		
	}

	public function set($nom,$ape,$dn,$feN,$ema,$tel,$log,$pas,$cam,$fac,$tit,$cur,$ini,$pan,$pay){
		$this->nombre = $nom;
		$this->apellidos = $ape;
		$this->dni = $dn;
		$this->fechaNac = $feN;
		$this->email = $ema;
		$this->telefono = $tel;
		$this->login = $log;
		$this->password = $pas;
		$this->campus = $cam;
		$this->facultad = $fac;
		$this->titulacion = $tit;
		$this->curso = $cur;
		$this->inicioTitulacion = $ini;
		$this->pAntes = $pan;
		$this->pAntesYear = $pay;
	}
	
	public function select($login){
		$this->con = $this->db->getConnection();
		$sql = 'select * from estudiante where login="' . $login . '"';
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
		$sql = 'select * from estudiante where dni="' . $id . '"';
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
		$sql = 'select * from estudiante where idEstudiante="' . $id . '"';
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
		$sql = 'select * from estudiante';
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
		$sql = 'insert into estudiante(nombre,apellidos,dni,fechaNac,email,telefono,login,password,campus,facultad,titulacion,curso,inicioTitulacion,pAntes,pAntesYear) values("' . $this->nombre . '","' .$this->apellidos. '","' .$this->dni. '","' .$this->fechaNac. '","' .$this->email. '","' .$this->telefono. '","' .$this->login. '","' .$this->password. '","' .$this->campus. '","' .$this->facultad. '","' .$this->titulacion. '","' .$this->curso. '","' .$this->inicioTitulacion. '","' .$this->pAntes. '","' .$this->pAntesYear. '")';
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
		$sql = 'update estudiante set nombre="' . $this->nombre . '",apellidos="' . $this->apellidos . '",dni="' . $this->dni . '",fechaNac="' . $this->fechaNac . '",email="' . $this->email . '",telefono="' . $this->telefono . '",login="' . $this->login . '",password="' . $this->password . '",campus="' . $this->campus . '",facultad="' . $this->facultad . '",titulacion="' . $this->titulacion . '",curso="' . $this->curso . '",inicioTitulacion="' . $this->inicioTitulacion . '",pAntes="' . $this->pAntes . '",pAntesYear="' . $this->pAntesYear . '" where login="' . $id.'"';
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
		$sql = 'delete from estudiante where login="'.$id.'"';
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