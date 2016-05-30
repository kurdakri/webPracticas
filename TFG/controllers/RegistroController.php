<?php
require_once("../models/estudiante.php");
require_once("../models/empresa.php");
require_once("../models/tutor.php");
$tipo=$_GET["tipo"];
if($tipo == "Estudiante"){
	registroEstudiantes();
}else if($tipo == "Tutor"){
	registroTutores();
}else if($tipo == "Empresa"){
	registroEmpresas();
}else if($tipo == "EstudianteC"){
	registroEstudiantesC();
}else{
	echo "Esto no debería suceder";
}

function registroEstudiantes(){
	$e = new Estudiante();
	$nom = $_POST["nombre"];
	$ape = $_POST["apellidos"];
	$dn = $_POST["dni"];
	$feN = $_POST["fNac"];
	$ema= $_POST["email"];
	$tel = $_POST["telefono"];
	$log = $_POST["login"];
	$pas = $_POST["clave1"];
	$cam = $_POST["campus"];
	$fac = $_POST["facultad"];
	$tit = $_POST["titulacion"];
	$cur = $_POST["curso"];
	$ini = $_POST["aInicio"];
	$pay = $_POST["pAntesYear"];
	if($pay == ""){
		$pan = 0;
	}else{
		$pan = 1;
	}
	$e->set($nom,$ape,$dn,$feN,$ema,$tel,$log,$pas,$cam,$fac,$tit,$cur,$ini,$pan,$pay);
	$boolean = $e->insert();
	if($boolean == false){
		?>
		<script>
			alert("Error de registro. Inténtelo más tarde");
		</script>
		<?php
		header("Location: ../views/mainRAC/registroEstudiante.php");
	}else{
		?>
		<script>
			alert("Registrado con éxito.");
		</script>
		<?php
		header("Location: ../views/mainRAC/acceso.php");
	}
}

function registroEstudiantesC(){
	$e = new Estudiante();
	$nom = $_POST["nombre"];
	$ape = $_POST["apellidos"];
	$dn = $_POST["dni"];
	$feN = $_POST["fNac"];
	$ema= $_POST["email"];
	$tel = $_POST["telefono"];
	$log = $_POST["login"];
	$pas = $_POST["clave1"];
	$cam = $_POST["campus"];
	$fac = $_POST["facultad"];
	$tit = $_POST["titulacion"];
	$cur = $_POST["curso"];
	$ini = $_POST["aInicio"];
	$pay = $_POST["pAntesYear"];
	if($pay == ""){
		$pan = 0;
	}else{
		$pan = 1;
	}
	$e->set($nom,$ape,$dn,$feN,$ema,$tel,$log,$pas,$cam,$fac,$tit,$cur,$ini,$pan,$pay);
	$boolean = $e->insert();
	if($boolean == false){
		$msg="Error de registro. Inténtelo más tarde";
		header("Location: ../views/coordinador/usuarios.php?msg=$msg");
	}else{
		$msg="Registrado con éxito.";
		header("Location: ../views/coordinador/usuarios.php?msg=$msg");
	}	
}

function registroTutores(){
	$t = new Tutor();
	$nom = $_POST["nombre"];
	$ape = $_POST["apellidos"];
	$dn = $_POST["dni"];
	$tel = $_POST["telefono"];
	$ema = $_POST["email"];
	$log = $_POST["login"];
	$pas = $_POST["clave1"];
	$dep = $_POST["departamento"];
	$cen = $_POST["centro"];
	$t->set($nom,$ape,$dn,$tel,$ema,$log,$pas,$dep,$cen);
	$boolean = $t->insert();
	if($boolean == false){
		$msg="Error de registro. Inténtelo más tarde";
		header("Location: ../views/coordinador/usuarios.php?msg=$msg");
	}else{
		$msg="Registrado con éxito.";
		header("Location: ../views/coordinador/usuarios.php?msg=$msg");
	}		
}

function registroEmpresas(){
	$e = new Empresa();
	$cen = $_POST["centro"];
	$tel = $_POST["telefono"];
	$loc = $_POST["localidad"];
	$pro = $_POST["provincia"];
	$ema = $_POST["email"];
	$cal = $_POST["calle"];
	$nom = $_POST["nombre"];
	$pas = $_POST["clave1"];
	$log = $_POST["login"];
	$noT = $_POST["nombreTutor"];
	$caT = $_POST["cargoTutor"];
	$tar = $_POST["tareas"];
	$e->set($cen,$tel,$loc,$pro,$ema,$cal,$nom,$pas,$log,$noT,$caT,$tar);
	$boolean = $e->insert();
	if($boolean == false){
		$msg="Error de registro. Inténtelo más tarde";
		header("Location: ../views/coordinador/usuarios.php?msg=$msg");
	}else{
		$msg="Registrado con éxito.";
		header("Location: ../views/coordinador/usuarios.php?msg=$msg");
	}	
}
?>