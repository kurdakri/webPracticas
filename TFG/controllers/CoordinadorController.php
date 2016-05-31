<?php
require_once("../models/Coordinador.php");
	if(isset($_GET["action"])){
		$action = $_GET["action"];
		if($action == "logout"){
			logout();
		}
		
		if($action == "perfil"){
			perfil();
		}
	}else{
		if(isset($_POST["login"])&isset($_POST["clave1"])&isset($_POST["nombre"])&isset($_POST["email"])&isset($_POST["telefono"])){
			modificarPerfil();
		}
	}
	
function logout(){
	session_start();
	$_SESSION["validated"] = "";
	session_destroy();
	header("Location: ../index.php");
}

function perfil(){
	session_start();
	$login = $_SESSION["name"];
	$c = new Coordinador();
	$boolean = $c->select($login);
	$datos = serialize($boolean);
	header("Location: ../views/coordinador/perfil.php?datos=$datos");
}

function modificarPerfil(){
	session_start();
	$idActual = $_SESSION["name"];
	$n = $_POST["nombre"];
	$l = $_POST["login"];
	$p = $_POST["clave1"];
	$t = $_POST["telefono"];
	$e = $_POST["email"];
	$c = new Coordinador();
	$c->set($n,$l,$p,$t,$e);
	$boolean = $c->update($idActual);
	if($boolean == false){
		$msg = "Error modificando el perfil. Inténtelo de nuevo";
		header("Location: ../views/coordinador/perfil.php?msg=$msg");
	}else{
		echo $idActual;
		$_SESSION["validated"] = "";
		session_destroy();
		$msg = "Datos del perfil modificados. Loguee de nuevo";
		header("Location: ../views/mainRAC/acceso.php?msg=$msg");
	}
}
?>