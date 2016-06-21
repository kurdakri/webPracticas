<?php
require_once("../models/estudiante.php");
require_once("../models/empresa.php");
require_once("../models/tutor.php");
if(isset($_GET["tipo"])){
	$tipo = $_GET["tipo"];
	if($tipo == "Estudiante"){
		bajaEstudiante();
	}else if($tipo == "Empresa"){
		bajaEmpresa();
	}else{
		bajaTutor();
	}
}else{
	$action = $_GET["action"];
	if($action == "Empresas"){
		listaEmpresas();
	}else if($action == "Estudiantes"){
		listaEstudiantes();
	}else{
		listaTutores();
	}
}

function bajaEstudiante(){
	$login = $_GET["login"];
	$est = new Estudiante();
	$boolean = $est->delete($login);
	if($boolean == false){
		$msg2 = "El estudiante no se ha borrado";
		header("Location: ../views/coordinador/bajaEstudiantes.php?msg2=$msg2");
	}else{
		$msg2 = "El estudiante se ha borrado correctamente";
		$e = new Estudiante();
		$boolean = $e->selectAll();
		if($boolean == false){
			$msg = "Sin estudiantes registrados.";
			header("Location: ../views/coordinador/bajaEstudiantes.php?msg=$msg&msg2=$msg2");
		}else{
			$datos = serialize($boolean);
			header("Location: ../views/coordinador/bajaEstudiantes.php?datos=$datos&msg2=$msg2");
		}
	}
}

function bajaEmpresa(){
	$login = $_GET["login"];
	$emp = new Empresa();
	$boolean = $emp->delete($login);
	if($boolean == false){
		$msg2 = "La empresa no se ha borrado";
		header("Location: ../views/coordinador/bajaEmpresas.php?msg2=$msg2");
	}else{
		$msg2 = "La empresa se ha borrado correctamente";	
		$e = new Empresa();
		$boolean = $e->selectAll();
		if($boolean == false){
			$msg = "Ninguna empresa registrada.";
			header("Location: ../views/coordinador/bajaEmpresas.php?msg=$msg&msg2=$msg2");
		}else{
			$datos = serialize($boolean);
			header("Location: ../views/coordinador/bajaEmpresas.php?datos=$datos&msg2=$msg2");
		}
	}	
}

function bajaTutor(){
	$login = $_GET["login"];
	$tut = new Tutor();
	$boolean = $tut->delete($login);
	if($boolean == false){
		$msg2 = "El tutor no se ha borrado";
		header("Location: ../views/coordinador/bajaTutores.php?msg2=$msg2");
	}else{
		$msg2 = "El tutor se ha borrado correctamente";
		$t = new Tutor();
		$boolean = $t->selectAll();
		if($boolean == false){
			$msg = "Sin tutores registrados.";
			header("Location: ../views/coordinador/bajaTutores.php?msg=$msg&msg2=$msg2");
		}else{
			$datos = serialize($boolean);
			header("Location: ../views/coordinador/bajaTutores.php?datos=$datos&msg2=$msg2");
		}
	}		
}

function listaEmpresas(){
	$e = new Empresa();
	$boolean = $e->selectAll();
	if($boolean == false){
		$msg = "Ninguna empresa registrada.";
		header("Location: ../views/coordinador/bajaEmpresas.php?msg=$msg");
	}else{
		$datos = serialize($boolean);
		header("Location: ../views/coordinador/bajaEmpresas.php?datos=$datos");
	}
}

function listaTutores(){
	$t = new Tutor();
	$boolean = $t->selectAll();
	if($boolean == false){
		$msg = "Ning&uacute;n tutor registrado.";
		header("Location: ../views/coordinador/bajaTutores.php?msg=$msg");
	}else{
		$datos = serialize($boolean);
		header("Location: ../views/coordinador/bajaTutores.php?datos=$datos");
	}
}

function listaEstudiantes(){
	$e = new Estudiante();
	$boolean = $e->selectAll();
	if($boolean == false){
		$msg = "Ning&uacute;n estudiante registrado.";
		header("Location: ../views/coordinador/bajaEstudiantes.php?msg=$msg");
	}else{
		$datos = serialize($boolean);
		header("Location: ../views/coordinador/bajaEstudiantes.php?datos=$datos");
	}
}
?>