<?php
require_once("../models/coordinador.php");
$c = new Coordinador();
$coordinador = $c->selectAll();
if($coordinador == false){
	header("Location: ../views/mainRAC/registro.php");
}else{
	$email = $coordinador[0]["email"];
	header("Location: ../views/mainRAC/registro.php?email=$email");
}
?>