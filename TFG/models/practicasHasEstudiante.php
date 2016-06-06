<?php
require_once("database.php");

class PracticasHasEstudiante{
	private $db;
	private $con;
	private $fkidPracticas;
	private $fkidEmpresa;
	private $fkidEstudiante;
	private $prioridad;
	
	//Constructor de la clase
	public function __construct(){
        $this->db = new Database();		
	}

	public function set($idP,$idEm,$idEs,$pri){
		$this->fkidPracticas = $idP;
		$this->fkidEmpresa = $idEm;
		$this->fkidEstudiante = $idEs;
		$this->prioridad = $pri;
	}
	
	public function selectAll(){
		$this->con = $this->db->getConnection();
		$sql = 'select * from practicas_has_estudiante';
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
		$sql = 'select * from practicas_has_estudiante where Estudiante_idEstudiante="' . $id . '"';
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
	
	public function selectByIdP($id){
		$this->con = $this->db->getConnection();
		$sql = 'select * from practicas_has_estudiante where Practicas_idPracticas="' . $id . '"';
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
		$sql = 'insert into practicas_has_estudiante(Practicas_idPracticas,Practicas_Empresa_idEmpresa,Estudiante_idEstudiante,Prioridad) values("' . $this->fkidPracticas . '","' .$this->fkidEmpresa. '","' .$this->fkidEstudiante. '","' .$this->prioridad. '")';
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
	
	
	public function delete(){
		$this->con = $this->db->getConnection();
		$sql = 'delete from practicas_has_estudiante where Estudiante_idEstudiante="'.$this->fkidEstudiante.'"';
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

	public function deleteById($id){
		$this->con = $this->db->getConnection();
		$sql = 'delete from practicas_has_estudiante where Practicas_idPracticas="'.$id.'"';
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