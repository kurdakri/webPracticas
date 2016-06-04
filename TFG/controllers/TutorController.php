<?php
require_once("../models/tutor.php");
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
	if(isset($_POST["login"])&isset($_POST["clave1"])&isset($_POST["nombre"])&isset($_POST["apellidos"])&isset($_POST["email"])
		&isset($_POST["telefono"])&isset($_POST["dni"])&isset($_POST["centro"])&isset($_POST["departamento"])){
			modificarPerfil();
		}
	
	if(isset($_POST["asunto"])&isset($_POST["remitente"])&isset($_POST["destinatario"])&isset($_POST["mensaje"])){
		enviarMensaje();
	}
	
	if(isset($_POST["solicitante"])&isset($_POST["comentario"])){
		solicitarTutorizacion();
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
	
	//Decimos quien env�a el correo
	$correo->SetFrom("tfgpracticasesei@gmail.com","");

	//Para indicar a quien tiene que responder el correo
	$correo->AddReplyTo("tfgpracticasesei@gmail.com","");

	//Destinatario del correo
	$correo->AddAddress($destinatario,"");

	//Asunto del mensaje
	$correo->Subject = "Solicitud de tutorizaci�n";

	//Contenido del mensaje
	//$correo->IsHTML(false);
	//$correo->Body = $mensaje;
	$correo->MsgHTML("<b>INFORMACI�N DEL SOLICITANTE</b><br><b>Nombre:</b>".$_POST["solicitante"]."<br><b>Descripcion:</b>".$_POST["comentario"]);

	//ARCHIVOS ADJUNTOS
	//$correo->AddAttachment("ruta");

	if(!$correo->Send()){
		$msg="Error:".$correo->ErrorInfo;
		header("Location: ../views/tutor/solicitudes.php?msg=$msg");
	}else{
		$msg="Peticion enviada. Espere la respuesta del coordinador de la pagina.";
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
	
	//Decimos quien env�a el correo
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
		$msg="Mensaje enviado con �xito";
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
	$t = new Tutor();
	$t->set($nom,$ape,$dn,$tel,$ema,$log,$pas,$dep,$cen);
	$boolean = $t->update($idActual);
	if($boolean == false){
		$msg = "Error modificando el perfil. Int�ntelo de nuevo";
		header("Location: ../views/tutor/perfil.php?msg=$msg");
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