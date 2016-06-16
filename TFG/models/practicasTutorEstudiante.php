<?php
require_once("database.php");

class PracticasTutorEstudiante{
	private $db;
	private $con;
	private $idP;
	private $fkidTutor;
	private $fkidPracticas;
	private $fkidEmpresa;
	private $fkidEstudiante;
	
	//Constructor de la clase
	public function __construct(){
        $this->db = new Database();		
	}

	public function set($idT,$idPs,$idEm,$idEs){
		$this->fkidTutor = $idT;
		$this->fkidPracticas = $idPs;
		$this->fkidEmpresa = $idEm;
		$this->fkidEstudiante = $idEs;
	}
	
	public function selectByIdP($id){
		$this->con = $this->db->getConnection();
		$sql = 'select * from practicas_tutor_estudiante where Practicas_idPracticas="' . $id . '"';
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
	
	public function selectAsignadas(){
		$this->con = $this->db->getConnection();
		$sql = 'select * from practicas_tutor_estudiante where Tutor_idTutor="-1"';
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
	
	public function selectNoAsignadas(){
		$this->con = $this->db->getConnection();
		$sql = 'select * from practicas_tutor_estudiante where Tutor_idTutor!="-1"';
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
	
	public function selectByIdE($id){
		$this->con = $this->db->getConnection();
		$sql = 'select * from practicas_tutor_estudiante where Practicas_Empresa_idEmpresa="' . $id . '"';
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

	public function selectByIdEs($id){
		$this->con = $this->db->getConnection();
		$sql = 'select * from practicas_tutor_estudiante where Estudiante_idEstudiante="' . $id . '"';
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
	

	public function selectByIdT($id){
		$this->con = $this->db->getConnection();
		$sql = 'select * from practicas_tutor_estudiante where Tutor_idTutor="' . $id . '"';
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
		$sql = 'select * from practicas_tutor_estudiante';
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
		$sql = 'select * from practicas_tutor_estudiante where idPracticas_Tutor_Estudiante="' . $id . '"';
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
		$sql = 'insert into practicas_tutor_estudiante(Tutor_idTutor,Practicas_idPracticas,Practicas_Empresa_idEmpresa,Estudiante_idEstudiante) values("' .$this->fkidTutor. '","' .$this->fkidPracticas. '","' .$this->fkidEmpresa. '","' .$this->fkidEstudiante. '")';
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
		$sql = 'update practicas_tutor_estudiante set Tutor_idTutor="' . $this->fkidTutor . '",Practicas_idPracticas="' . $this->fkidPracticas . '",Practicas_Empresa_idEmpresa="' . $this->fkidEmpresa . '",Estudiante_idEstudiante="' . $this->fkidEstudiante . '" where idPracticas_Tutor_Estudiante="' . $id.'"';
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
	
	public function updateTutor($idTutor,$idPractica){
		$this->con = $this->db->getConnection();
		$sql = 'update practicas_tutor_estudiante set Tutor_idTutor="' . $idTutor . '" where Practicas_idPracticas="' . $idPractica.'"';
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
		$sql = 'delete from practicas_tutor_estudiante where idPracticas_Tutor_Estudiante="'.$this->idP.'"';
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
	
	public function truncate(){
		$this->con = $this->db->getConnection();
		$sql = 'truncate practicas_tutor_estudiante';
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
		$sql = 'delete from practicas_tutor_estudiante where Practicas_idPracticas="'.$id.'"';
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