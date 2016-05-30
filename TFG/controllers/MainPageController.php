<?php
require_once("../models/coordinador.php");
$c = new Coordinador();
$coordinador = $c->select();
if($coordinador == false){
	header("Location: ../index.php");
}else{
	$email = $coordinador[0]["email"];
	header("Location: ../views/mainRAC/registro.php?email=$email");
}
?>