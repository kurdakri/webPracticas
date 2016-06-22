<?php
require_once("../models/empresa.php");
require_once("../models/coordinador.php");
require_once("../models/eventos.php");
require_once("../models/estudiante.php");
require_once("../models/tutor.php");
require_once("../models/practicas.php");
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
		listarAsignaciones();
	}
	
	if($action == "getEmpresa"){
		getEmpresa();
	}
	
}else{
	if(isset($_POST["login"])&isset($_POST["clave1"])&isset($_POST["nombre"])&isset($_POST["email"])&isset($_POST["telefono"])&isset($_POST["centro"])&isset($_POST["localidad"])&isset($_POST["provincia"])&isset($_POST["calle"])&isset($_POST["nTutor"])&isset($_POST["cTutor"])&isset($_POST["tareas"])){
		modificarPerfil();
	}
	
	if(isset($_POST["asunto"])&isset($_POST["remitente"])&isset($_POST["destinatario"])&isset($_POST["mensaje"])){
		enviarMensaje();
	}
	
	if(isset($_POST["titulo"])&isset($_POST["descripcion"])&isset($_POST["nombreEmpresa"])&isset($_POST["periodo"])&isset($_POST["titulacion"])&isset($_POST["inicio"])&isset($_POST["fin"])&isset($_POST["horario"])&isset($_POST["pformativo"])){
		enviarOferta();
	}
	
	if(isset($_POST["enviarInforme"])){
		enviarInforme();
	}
}

function getEmpresa(){
	session_start();
	$loginEmpresa = $_SESSION["name"];
	$e = new Empresa();
	$empresa = $e->select($loginEmpresa);
	$nombreEmpresa = $empresa[0]["nombre"];
	header("Location: ../views/empresa/gestionOfertas.php?empresa=$nombreEmpresa");
}

function enviarInforme(){
	$destinatario = $_POST["cTutor"];
	$file = $_FILES["archivo"];
	session_start();
	$loginEmpresa = $_SESSION["name"];
	$e = new Empresa();
	$empresa = $e->select($loginEmpresa);
	$nombreEmpresa = $empresa[0]["nombre"];
	$remitente = $empresa[0]["email"];
	
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
	$correo->Subject = "Informe de practicas del tutor de la empresa: ";

	//Contenido del mensaje
	$correo->IsHTML(false);
	$correo->Body = " ";
	

	//ARCHIVOS ADJUNTOS
	$correo->AddAttachment($file["tmp_name"],$file["name"]);

	if(!$correo->Send()){
		$msg="Error:".$correo->ErrorInfo;
		header("Location: ../views/empresa/enviarFormulario.php?msg=$msg");
	}else{
		$msg="Mensaje enviado exitosamente.";
		header("Location: ../views/empresa/enviarFormulario.php?msg=$msg");
	}
}

function listarAsignaciones(){
	session_start();
	$loginEmpresa = $_SESSION["name"];
	$e = new Empresa();
	$empresa = $e->select($loginEmpresa);
	$idEmpresa = $empresa[0]["idEmpresa"];
	
	$e = new PracticasTutorEstudiante();
	$boolean = $e->selectByIdE($idEmpresa);
	if($boolean == false){
		$msg = "No tiene ninguna practica asignada actualmente";
		header("Location: ../views/empresa/asignaciones.php?msg=$msg");
	}else{
		$i=0;
		$toret = array();
		
		$encuentra=false;
		foreach($boolean as $asignada){
			$idPractica = $asignada["Practicas_idPracticas"];
			$idTutor = $asignada["Tutor_idTutor"];
			$idEstudiante = $asignada["Estudiante_idEstudiante"];
			
			if($idTutor == -1){
				
			}else{
				$encuentra=true;
				$e = new Practicas();
				$bool = $e->selectById($idPractica);
				$toret[$i]["nombrePractica"] = $bool[0]["titulo"];
				$toret[$i]["inicioPractica"] = $bool[0]["inicio"];
				$toret[$i]["finPractica"] = $bool[0]["fin"];
				$toret[$i]["horarioPractica"] = $bool[0]["horario"];

				$e = new Tutor();
				$bool = $e->selectById($idTutor);
				$toret[$i]["nombreTutor"] = $bool[0]["nombre"];
				$toret[$i]["apellidosTutor"] = $bool[0]["apellidos"];
				$toret[$i]["emailTutor"] = $bool[0]["email"];
				$toret[$i]["telefonoTutor"] = $bool[0]["telefono"];

				$e = new Estudiante();
				$bool = $e->selectById($idEstudiante);
				$toret[$i]["nombreEstudiante"] = $bool[0]["nombre"];
				$toret[$i]["apellidosEstudiante"] = $bool[0]["apellidos"];
				$toret[$i]["emailEstudiante"] = $bool[0]["email"];
				$toret[$i]["telefonoEstudiante"] = $bool[0]["telefono"];
				$toret[$i]["titulacionEstudiante"] = $bool[0]["titulacion"];				
			}


			
			$i = $i+1;
		}
		
		if($encuentra == true){
			$datos = json_encode($toret);
			header("Location: ../views/empresa/asignaciones.php?datos=$datos");			
		}else{
			$msg = "No tiene ninguna practica asignada actualmente";
			header("Location: ../views/empresa/asignaciones.php?msg=$msg");
		}
		
	}
}

function listarFormularios(){
	$e = new Eventos();
	$eventos = $e->selectByTipo("Empresa");
	if($eventos == false){
		$msg = "No hay formularios ha rellenar en este momento.";
		header("Location: ../views/empresa/formularios.php?msg=$msg");
	}else{
		$datos = json_encode($eventos);
		header("Location: ../views/empresa/formularios.php?datos=$datos");
	}
}

function enviarOferta(){
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
	$correo->Charset='ISO-8859-1';

	$c = new Coordinador();
	$coord = $c->selectAll();
	$destinatario = $coord[0]["email"];
	
	//Decimos quien envía el correo
	$correo->SetFrom("tfgpracticasesei@gmail.com","");

	//Para indicar a quien tiene que responder el correo
	$correo->AddReplyTo("tfgpracticasesei@gmail.com","");

	//Destinatario del correo
	$correo->AddAddress($destinatario,"");

	//Asunto del mensaje
	$correo->Subject ="Solicitud de oferta de prácticas en empresa";

	//Contenido del mensaje
	//$correo->IsHTML(false);
	//$correo->Body = $mensaje;
	
	$correo->MsgHTML("<b>DATOS DE LA PRÁCTICA SOLICITADA</b><br><b>Titulo:</b>".utf8_decode($_POST["titulo"])."<br><b>Descripcion:</b>".utf8_decode($_POST["descripcion"])."<br><b>Nombre de la Empresa:</b>".utf8_decode($_POST["nombreEmpresa"])."<br><b>Período:</b>".utf8_decode($_POST["periodo"])."
	<br><b>Titulacion:</b>".utf8_decode($_POST["titulacion"])."<br><b>Fecha de Inicio:</b>".utf8_decode($_POST["inicio"])."<br><b>Fecha de fin:</b>".utf8_decode($_POST["fin"])."<br><b>Horario:</b>".$_POST["horario"]."<br><b>Proyecto Formativo:</b>".utf8_decode($_POST["pformativo"]));

	//ARCHIVOS ADJUNTOS
	//$correo->AddAttachment("ruta");

	if(!$correo->Send()){
		$msg="Error:".$correo->ErrorInfo;
		header("Location: ../views/empresa/gestionOfertas.php?msg=$msg");
	}else{
		$msg="Peticion enviada al coordinador de las practicas.";
		$empresa = $_POST["nombreEmpresa"];
		header("Location: ../views/empresa/gestionOfertas.php?msg=$msg&empresa=$empresa");
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
	$correo->Subject = utf8_decode($asunto);

	//Contenido del mensaje
	$correo->IsHTML(false);
	$correo->Body = utf8_decode($mensaje);
	//$correo->MsgHTML("<strong>Mensaje html</strong>");

	//ARCHIVOS ADJUNTOS
	//$correo->AddAttachment("ruta");

	if(!$correo->Send()){
		$msg="Error:".$correo->ErrorInfo;
		header("Location: ../views/empresa/mensajes.php?msg=$msg");
	}else{
		$msg="Mensaje enviado exitosamente.";
		header("Location: ../views/empresa/mensajes.php?msg=$msg");
	}	
}

function modificarPerfil(){
	session_start();
	$idActual = $_SESSION["name"];
	$cen = $_POST["centro"];
	$tel = $_POST["telefono"];
	$loc = $_POST["localidad"];
	$pro = $_POST["provincia"];
	$ema = $_POST["email"];
	$cal = $_POST["calle"];
	$nom = $_POST["nombre"];
	$pas = $_POST["clave1"];
	$log = $_POST["login"];
	$noT = $_POST["nTutor"];
	$caT = $_POST["cTutor"];
	$tar = $_POST["tareas"];
	$loginActual = $_GET["login"];
	$e = new Empresa();
	$empresa = $e->select($log);
	if($empresa == false){
		$e = new Empresa();
		$e->set($cen,$tel,$loc,$pro,$ema,$cal,$nom,$pas,$log,$noT,$caT,$tar);
		$boolean = $e->update($idActual);
		if($boolean == false){
			$msg = "Error modificando el perfil. Inténtelo de nuevo";
			header("Location: ../views/empresa/perfil.php?msg=$msg");
		}else{
			echo $idActual;
			$_SESSION["validated"] = "";
			session_destroy();
			$msg = "Datos del perfil modificados. Loguee de nuevo";
			header("Location: ../views/mainRAC/acceso.php?msg=$msg");
		}	
	}else{
		if($log == $loginActual){
			$e = new Empresa();
			$e->set($cen,$tel,$loc,$pro,$ema,$cal,$nom,$pas,$log,$noT,$caT,$tar);
			$boolean = $e->update($idActual);
			if($boolean == false){
				$msg = "Error modificando el perfil. Inténtelo de nuevo";
				header("Location: ../views/empresa/perfil.php?msg=$msg");
			}else{
				echo $idActual;
				$_SESSION["validated"] = "";
				session_destroy();
				$msg = "Datos del perfil modificados. Loguee de nuevo";
				header("Location: ../views/mainRAC/acceso.php?msg=$msg");
			}			
		}else{
			echo $loginActual;
			$msg = "Debe modificar el login de usuario por otro que no exista en el sistema.";
			$array = array();
			$array["nombre"] = $nom;
			$array["email"] = $ema;
			$array["telefono"] = $tel;
			$array["centro"] = $cen;
			$array["localidad"] = $loc;
			$array["provincia"] = $pro;
			$array["calle"] = $cal;
			$array["nombreTutor"] = $noT;
			$array["cargoTutor"] = $caT;
			$array["tareas"] = $tar;
			$info = json_encode($array);
			verPerfil2($msg,$info);			
		}		
	}
}

function verPerfil(){
	session_start();
	$login = $_SESSION["name"];
	$e = new Empresa();
	$boolean = $e->select($login);
	$datos = json_encode($boolean);
	header("Location: ../views/empresa/perfil.php?datos=$datos");	
}

function verPerfil2($message,$info){
	session_start();
	$login = $_SESSION["name"];
	$e = new Empresa();
	$boolean = $e->select($login);
	$datos = json_encode($boolean);
	header("Location: ../views/empresa/perfil.php?datos=$datos&msg=$message&info=$info");	
}

function logout(){
	session_start();
	$_SESSION["validated"] = "";
	session_destroy();
	header("Location: ../index.php");
}
?>