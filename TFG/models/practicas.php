<?php
require_once("database.php");

class Practicas{
	private $db;
	private $con;
	private $titulo;
	private $descripcion;
	private $fkidEmpresa;
	private $periodo;
	private $titulacion;
	private $inicio;
	private $fin;
	private $horario;
	private $pformativo;
	
	//Constructor de la clase
	public function __construct(){
        $this->db = new Database();		
	}

	public function set($tit,$des,$idE,$per,$ttn,$ini,$fn,$hor,$pfo){
		$this->titulo = $tit;
		$this->descripcion = $des;
		$this->fkidEmpresa = $idE;
		$this->periodo = $per;
		$this->titulacion = $ttn;
		$this->inicio = $ini;
		$this->fin = $fn;
		$this->horario = $hor;
		$this->pformativo = $pfo;
	}
	
	public function selectAll(){
		$this->con = $this->db->getConnection();
		$sql = 'select * from practicas';
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
		$sql = 'select * from practicas where idPracticas="' . $id . '"';
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
		$sql = 'select * from practicas where titulo="' . $id . '"';
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
		$sql = 'insert into practicas(titulo,descripcion,Empresa_idEmpresa,periodo,titulacion,inicio,fin,horario,pformativo) values("' . $this->titulo . '","' .$this->descripcion. '","' .$this->fkidEmpresa. '","' .$this->periodo. '","' .$this->titulacion. '","' .$this->inicio. '","' .$this->fin. '","' .$this->horario. '","' .$this->pformativo. '")';
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
		$sql = 'update practicas set titulo="' . $this->titulo . '",descripcion="' . $this->descripcion . '",periodo="' . $this->periodo . '",titulacion="' . $this->titulacion . '",inicio="' . $this->inicio . '",fin="' . $this->fin . '",horario="' . $this->horario . '",pformativo="' . $this->pformativo . '" where titulo="' . $id.'"';
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
		$sql = 'delete from practicas where idPracticas="'.$id.'"';
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