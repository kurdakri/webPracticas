<?php
require_once("../models/tutor.php");
require_once("../models/coordinador.php");
require_once("../models/eventos.php");
require_once("../models/practicas.php");
require_once("../models/estudiante.php");
require_once("../models/empresa.php");
require_once("../models/practicasTutorEstudiante.php");
require_once('phpmailer/class.phpmailer.php');
require_once('phpmailer/class.smtp.php');
if(isset($_GET["action"])){
	$action = $_GET["action"];
	
	if($action == "logout"){
			logout();
	}
	
	if($action == "verPerfil"){
			verPerfil();
	}
	
	if($action == "formularios"){
		listarFormularios();
	}
	
	if($action == "asignaciones"){
		consultarAsignaciones();
	}
	
}else{
	if(isset($_POST["login"])&isset($_POST["clave1"])&isset($_POST["nombre"])&isset($_POST["apellidos"])&isset($_POST["email"])
		&isset($_POST["telefono"])&isset($_POST["dni"])&isset($_POST["centro"])&isset($_POST["departamento"])){
			modificarPerfil();
		}
	
	if(isset($_POST["asunto"])&isset($_POST["remitente"])&isset($_POST["destinatario"])&isset($_POST["mensaje"])){
		enviarMensaje();
	}
	
	if(isset($_POST["comentario"])){
		solicitarTutorizacion();
	}
	
	if(isset($_POST["destinatario"])){
		enviarInforme();
	}
}

function enviarInforme(){
	$destinatario = $_POST["destinatario"];
	$file = $_FILES["archivo"];
	session_start();
	$loginTutor = $_SESSION["name"];
	$e = new Tutor();
	$tutor = $e->select($loginEmpresa);
	$nombreTutor = $tutor[0]["nombre"];
	$remitente = $tutor[0]["email"];
	
	$correo = new PHPMailer();
	$smtp = new SMTP();

	$correo->IsSMTP();
	$correo->SMTPAuth=true;
	$correo->SMTPSecure='tls';
	$correo->Host="smtp.gmail.com";
	$correo->Port=587;
	$correo->Username="tfgpracticasesei@gmail.com";
	$correo->Password="tfgpracticas";
	$correo->Timeout=30;
	$correo->Charset='UTF-8';
	
	
	//Decimos quien envía el correo
	$correo->SetFrom("tfgpracticasesei@gmail.com","");

	//Para indicar a quien tiene que responder el correo
	$correo->AddReplyTo($remitente,"");

	//Destinatario del correo
	$correo->AddAddress($destinatario,"");

	//Asunto del mensaje
	$correo->Subject = "Informe de practicas del tutor academico: ";

	//Contenido del mensaje
	$correo->IsHTML(false);
	$correo->Body = " ";
	

	//ARCHIVOS ADJUNTOS
	$correo->AddAttachment($file["tmp_name"],$file["name"]);

	if(!$correo->Send()){
		$msg="Error:".$correo->ErrorInfo;
		header("Location: ../views/tutor/enviarFormulario.php?msg=$msg");
	}else{
		$msg="Informe enviado exitosamente.";
		header("Location: ../views/tutor/enviarFormulario.php?msg=$msg");
	}
}

function consultarAsignaciones(){
	session_start();
	$loginTutor = $_SESSION["name"];
	$e = new Tutor();
	$tutor = $e->select($loginTutor);
	$idTutor = $tutor[0]["idTutor"];
	
	$e = new PracticasTutorEstudiante();
	$boolean = $e->selectByIdT($idTutor);
	if($boolean == false){
		$msg = "No tiene ninguna practica asignada actualmente";
		header("Location: ../views/tutor/asignaciones.php?msg=$msg");
	}else{
		$i=0;
		$toret = array();
		
		foreach($boolean as $asignada){
			$idPractica = $asignada["Practicas_idPracticas"];
			$idEstudiante = $asignada["Estudiante_idEstudiante"];
			$idEmpresa = $asignada["Practicas_Empresa_idEmpresa"];

			$e = new Practicas();
			$bool = $e->selectById($idPractica);
			$toret[$i]["nombrePractica"] = $bool[0]["titulo"];
			$toret[$i]["inicioPractica"] = $bool[0]["inicio"];
			$toret[$i]["finPractica"] = $bool[0]["fin"];
			$toret[$i]["horarioPractica"] = $bool[0]["horario"];

			$e = new Estudiante();
			$bool = $e->selectById($idEstudiante);
			$toret[$i]["nombreEstudiante"] = $bool[0]["nombre"];
			$toret[$i]["apellidosEstudiante"] = $bool[0]["apellidos"];
			$toret[$i]["emailEstudiante"] = $bool[0]["email"];
			$toret[$i]["telefonoEstudiante"] = $bool[0]["telefono"];

			$e = new Empresa();
			$bool = $e->selectById($idEmpresa);
			$toret[$i]["nombreEmpresa"] = $bool[0]["nombre"];
			$toret[$i]["nombreTutorE"] = $bool[0]["nombreTutor"];
			$toret[$i]["cargoTutorE"] = $bool[0]["cargoTutor"];
			$toret[$i]["telefonoEmpresa"] = $bool[0]["telefono"];
			$toret[$i]["emailEmpresa"] = $bool[0]["email"];
			$toret[$i]["calleEmpresa"] = $bool[0]["calle"];
			$toret[$i]["localidadEmpresa"] = $bool[0]["localidad"];
			$toret[$i]["provinciaEmpresa"] = $bool[0]["provincia"];
			
			$i = $i+1;
		}
		
		$datos = json_encode($toret);
		header("Location: ../views/tutor/asignaciones.php?datos=$datos");
	}	
}

function listarFormularios(){
	$e = new Eventos();
	$eventos = $e->selectByTipo("Tutor");
	if($eventos == false){
		$msg = "No hay formularios a rellenar en este momento.";
		header("Location: ../views/tutor/formularios.php?msg=$msg");
	}else{
		$datos = json_encode($eventos);
		header("Location: ../views/tutor/formularios.php?datos=$datos");
	}	
}

function solicitarTutorizacion(){

	$correo = new PHPMailer();
	$smtp = new SMTP();

	$correo->IsSMTP();
	$correo->SMTPAuth=true;
	$correo->SMTPSecure='tls';
	$correo->Host="smtp.gmail.com";
	$correo->Port=587;
	$correo->Username="tfgpracticasesei@gmail.com";
	$correo->Password="tfgpracticas";
	$correo->Timeout=30;
	$correo->Charset='UTF-8';

	$c = new Coordinador();
	$coord = $c->selectAll();
	$destinatario = $coord[0]["email"];
	
	session_start();
	$idTutor = $_SESSION["name"];
	$t = new Tutor();
	$tutor = $t->select($idTutor);
	$remitente = $tutor[0]["email"];
	$nombreTutor = $tutor[0]["nombre"];
	
	
	//Decimos quien envía el correo
	$correo->SetFrom("tfgpracticasesei@gmail.com","");

	//Para indicar a quien tiene que responder el correo
	$correo->AddReplyTo($remitente,"");

	//Destinatario del correo
	$correo->AddAddress($destinatario,"");

	//Asunto del mensaje
	$correo->Subject = "Solicitud de tutorización";

	//Contenido del mensaje
	//$correo->IsHTML(false);
	//$correo->Body = $mensaje;
	$correo->MsgHTML("<b>INFORMACIÓN DEL SOLICITANTE</b><br><b>Nombre del tutor:</b>".$nombreTutor."<br><b>Email:</b>".$remitente."<b>DESCRIPCION DE LA SOLICITUD:</b>".$_POST["comentario"]);

	//ARCHIVOS ADJUNTOS
	//$correo->AddAttachment("ruta");

	if(!$correo->Send()){
		$msg="Error:".$correo->ErrorInfo;
		header("Location: ../views/tutor/solicitudes.php?msg=$msg");
	}else{
		$msg="Peticion enviada al coordinador.";
		header("Location: ../views/tutor/solicitudes.php?msg=$msg");
	}		
}

function enviarMensaje(){
	$correo = new PHPMailer();
	$smtp = new SMTP();

	$correo->IsSMTP();
	$correo->SMTPAuth=true;
	$correo->SMTPSecure='tls';
	$correo->Host="smtp.gmail.com";
	$correo->Port=587;
	$correo->Username="tfgpracticasesei@gmail.com";
	$correo->Password="tfgpracticas";
	$correo->Timeout=30;
	$correo->Charset='UTF-8';

	$asunto = $_POST["asunto"];
	$remitente = $_POST["remitente"];
	$destinatario = $_POST["destinatario"];
	$mensaje = $_POST["mensaje"];
	
	//Decimos quien envía el correo
	$correo->SetFrom("tfgpracticasesei@gmail.com","");

	//Para indicar a quien tiene que responder el correo
	$correo->AddReplyTo($remitente,"");

	//Destinatario del correo
	$correo->AddAddress($destinatario,"");

	//Asunto del mensaje
	$correo->Subject = $asunto;

	//Contenido del mensaje
	$correo->IsHTML(false);
	$correo->Body = $mensaje;
	//$correo->MsgHTML("<strong>Mensaje html</strong>");

	//ARCHIVOS ADJUNTOS
	//$correo->AddAttachment("ruta");

	if(!$correo->Send()){
		$msg="Error:".$correo->ErrorInfo;
		header("Location: ../views/tutor/mensajes.php?msg=$msg");
	}else{
		$msg="Mensaje enviado exitosamente.";
		header("Location: ../views/tutor/mensajes.php?msg=$msg");
	}		
}

function modificarPerfil(){
	session_start();
	$idActual = $_SESSION["name"];
	$nom = $_POST["nombre"];
	$ape = $_POST["apellidos"];
	$dn = $_POST["dni"];
	$tel = $_POST["telefono"];
	$ema = $_POST["email"];
	$log = $_POST["login"];
	$pas = $_POST["clave1"];
	$dep = $_POST["departamento"];
	$cen = $_POST["centro"];
	$loginActual = $_POST["loginActual"];
	$t = new Tutor();
	$tutor = $t->select($log);
	if($tutor == false){
		$t = new Tutor();
		$t->set($nom,$ape,$dn,$tel,$ema,$log,$pas,$dep,$cen);
		$boolean = $t->update($idActual);
		if($boolean == false){
			$msg = "Error modificando el perfil. Inténtelo de nuevo";
			header("Location: ../views/tutor/perfil.php?msg=$msg");
		}else{
			echo $idActual;
			$_SESSION["validated"] = "";
			session_destroy();
			$msg = "Datos del perfil modificados. Loguee de nuevo";
			header("Location: ../views/mainRAC/acceso.php?msg=$msg");
		}
	}else{
			if($log == $loginActual){	
				$t = new Tutor();
				$t->set($nom,$ape,$dn,$tel,$ema,$log,$pas,$dep,$cen);
				$boolean = $t->update($idActual);
				if($boolean == false){
					$msg = "Error modificando el perfil. Inténtelo de nuevo";
					header("Location: ../views/tutor/perfil.php?msg=$msg");
				}else{
					echo $idActual;
					$_SESSION["validated"] = "";
					session_destroy();
					$msg = "Datos del perfil modificados. Loguee de nuevo";
					header("Location: ../views/mainRAC/acceso.php?msg=$msg");
				}
			}else{
				$msg = "El login de usuario $log ya existe en el sistema. Introduzca otro login de usuario";
				$array = array();
				$array["nombre"] = $nom;
				$array["apellidos"] = $ape;
				$array["email"] = $ema;
				$array["telefono"] = $tel;
				$array["dni"] = $dn;
				$array["centro"] = $cen;
				$array["departamento"] = $dep;
				$arraySend = json_encode($array);
				verPerfil2($msg,$arraySend);					
			}	
	}
	
	
}

function verPerfil2($message,$array){
	session_start();
	$login = $_SESSION["name"];
	$t = new Tutor();
	$boolean = $t->select($login);
	$datos = json_encode($boolean);
	header("Location: ../views/tutor/perfil.php?datos=$datos&msg=$message&array=$array");		
}

function verPerfil(){
	session_start();
	$login = $_SESSION["name"];
	$t = new Tutor();
	$boolean = $t->select($login);
	$datos = json_encode($boolean);
	header("Location: ../views/tutor/perfil.php?datos=$datos");		
}

function logout(){
	session_start();
	$_SESSION["validated"] = "";
	session_destroy();
	header("Location: ../index.php");	
}
?>