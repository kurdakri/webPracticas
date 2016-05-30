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

	public function set($id,$idT,$idPs,$idEm,$idEs){
		$this->idP = $id;
		$this->fkidTutor = $idT;
		$this->fkidPracticas = $idPs;
		$this->fkidEmpresa = $idEm;
		$this->fkidEstudiante = $idEs;
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
		$sql = 'insert into practicas_tutor_estudiante(idPracticas_Tutor_Estudiante,Tutor_idTutor,Practicas_idPracticas,Practicas_Empresa_idEmpresa,Estudiante_idEstudiante) values("' . $this->idP . '","' .$this->fkidTutor. '","' .$this->fkidPracticas. '","' .$this->fkidEmpresa. '","' .$this->fkidEstudiante. '")';
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
}
?>