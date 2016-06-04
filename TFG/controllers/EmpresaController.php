<?php
require_once("../models/empresa.php");
require_once("../models/coordinador.php");
require_once("../models/eventos.php");
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
	$correo->Charset='UTF-8';

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
	$correo->Subject = "Solicitud de oferta de prácticas en empresa";

	//Contenido del mensaje
	//$correo->IsHTML(false);
	//$correo->Body = $mensaje;
	$correo->MsgHTML("<b>DATOS DE LA PRÁCTICA SOLICITADA</b><br><b>Titulo:</b>".$_POST["titulo"]."<br><b>Descripcion:</b>".$_POST["descripcion"]."<br><b>Nombre de la Empresa:</b>".$_POST["nombreEmpresa"]."<br><b>Período:</b>".$_POST["periodo"]."
	<br><b>Titulacion:</b>".$_POST["titulacion"]."<br><b>Fecha de Inicio:</b>".$_POST["inicio"]."<br><b>Fecha de fin:</b>".$_POST["fin"]."<br><b>Horario:</b>".$_POST["horario"]."<br><b>Proyecto Formativo:</b>".$_POST["pformativo"]);

	//ARCHIVOS ADJUNTOS
	//$correo->AddAttachment("ruta");

	if(!$correo->Send()){
		$msg="Error:".$correo->ErrorInfo;
		header("Location: ../views/empresa/gestionOfertas.php?msg=$msg");
	}else{
		$msg="Peticion enviada. Espere la respuesta del coordinador de la pagina.";
		header("Location: ../views/empresa/gestionOfertas.php?msg=$msg");
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
		header("Location: ../views/empresa/mensajes.php?msg=$msg");
	}else{
		$msg="Mensaje enviado con éxito";
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
}

function verPerfil(){
	session_start();
	$login = $_SESSION["name"];
	$e = new Empresa();
	$boolean = $e->select($login);
	$datos = json_encode($boolean);
	header("Location: ../views/empresa/perfil.php?datos=$datos");	
}

function logout(){
	session_start();
	$_SESSION["validated"] = "";
	session_destroy();
	header("Location: ../index.php");
}
?>