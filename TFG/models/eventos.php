<?php
require_once("database.php");

class Eventos{
	private $db;
	private $con;
	private $tipoEvento;
	private $fecha;
	
	//Constructor de la clase
	public function __construct(){
        $this->db = new Database();		
	}

	public function set($tip,$fec){
		$this->tipoEvento = $tip;
		$this->fecha = $fec;
	}
	
	public function select($id){
		$this->con = $this->db->getConnection();
		$sql = 'select * from eventos where tipoEvento="' . $id . '"';
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
		$sql = 'insert into eventos(tipoEvento,Fecha) values("' . $this->tipoEvento . '","' .$this->fecha. '")';
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
		$sql = 'update eventos set tipoEvento="' . $this->tipoEvento . '",Fecha="' . $this->fecha . '" where login="' . $id.'"';
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
		$sql = 'delete from eventos where tipoEvento="'.$this->tipoEvento.'"';
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