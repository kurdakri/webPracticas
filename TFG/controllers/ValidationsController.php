<?php
require_once("../models/coordinador.php");
require_once("../models/estudiante.php");
require_once("../models/empresa.php");
require_once("../models/tutor.php");

$userType = $_POST["userList"];

if($userType == "Estudiante"){
	accesoEstudiante();
}else if($userType == "Coordinador"){
	accesoCoordinador();
}else if($userType == "Empresa"){
	accesoEmpresa();
}else{
	accesoTutor();
}

function accesoEstudiante(){
	$login = $_POST["user"];
	$password = $_POST["password"];
	$e = new Estudiante();
	$boolean = $e->select($login);
	if($boolean == false){
		$msg = "No se encuentra el estudiante especificado. Verifica que los datos de acceso son correctos";
		header("Location: ../views/mainRAC/acceso.php?msg=$msg");
	}else{
		$passwordE = $boolean[0]["password"];
		if($password == $passwordE){
			session_start();
			$_SESSION["validated"] = "Estudiante";
			$_SESSION["name"] = $boolean[0]["login"];
			header("Location: ../views/estudiante/principal.php");
		}else{
			$msg = "La clave introducida no es correcta para el usuario especificado.";
			header("Location: ../views/mainRAC/acceso.php?msg=$msg");			
		}
	}
}

function accesoCoordinador(){
	$login = $_POST["user"];
	$password = $_POST["password"];
	$e = new Coordinador();
	$boolean = $e->select($login);
	if($boolean == false){
		$msg = "No se encuentra el nombre de coordinador especificado. Verifica que los datos de acceso son correctos";
		header("Location: ../views/mainRAC/acceso.php?msg=$msg");
	}else{
		$passwordE = $boolean[0]["password"];
		if($password == $passwordE){
			session_start();
			$_SESSION["validated"] = "Coordinador";
			$_SESSION["name"] = $boolean[0]["login"];
			header("Location: ../views/coordinador/principal.php");
		}else{
			$msg = "La clave introducida no es correcta para el usuario especificado.";
			header("Location: ../views/mainRAC/acceso.php?msg=$msg");			
		}
	}	
}

function accesoTutor(){
	$login = $_POST["user"];
	$password = $_POST["password"];
	$e = new Tutor();
	$boolean = $e->select($login);
	if($boolean == false){
		$msg = "No se encuentra el nombre de tutor especificado. Verifica que los datos de acceso son correctos";
		header("Location: ../views/mainRAC/acceso.php?msg=$msg");
	}else{
		$passwordE = $boolean[0]["password"];
		if($password == $passwordE){
			session_start();
			$_SESSION["validated"] = "Tutor";
			$_SESSION["name"] = $boolean[0]["login"];
			header("Location: ../views/tutor/principal.php");
		}else{
			$msg = "La clave introducida no es correcta para el usuario especificado.";
			header("Location: ../views/mainRAC/acceso.php?msg=$msg");			
		}
	}		
}

function accesoEmpresa(){
	$login = $_POST["user"];
	$password = $_POST["password"];
	$e = new Empresa();
	$boolean = $e->select($login);
	if($boolean == false){
		$msg = "No se encuentra el nombre de empresa especificado. Verifica que los datos de acceso son correctos";
		header("Location: ../views/mainRAC/acceso.php?msg=$msg");
	}else{
		$passwordE = $boolean[0]["password"];
		if($password == $passwordE){
			session_start();
			$_SESSION["validated"] = "Empresa";
			$_SESSION["name"] = $boolean[0]["login"];
			header("Location: ../views/empresa/principal.php");
		}else{
			$msg = "La clave introducida no es correcta para el usuario especificado.";
			header("Location: ../views/mainRAC/acceso.php?msg=$msg");			
		}
	}	
}

?>