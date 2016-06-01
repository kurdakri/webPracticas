<?php
require_once("../models/coordinador.php");
require_once("../models/eventos.php");
require_once("../models/practicas.php");
require_once("../models/empresa.php");
require_once("../models/estudiante.php");
require_once("../models/tutor.php");
require_once('phpmailer/class.phpmailer.php');
require_once('phpmailer/class.smtp.php');

	if(isset($_GET["action"])){
		$action = $_GET["action"];
		if($action == "logout"){
			logout();
		}
		
		if($action == "perfil"){
			perfil();
		}
		
		if($action == "eventos"){
			listarEventos();
		}
		
		if($action == "borrarEvento"){
			borrarEvento();
		}
		
		if($action == "ofertasPracticas"){
			listarPracticas();
		}
		
		if($action == "eliminarPractica"){
			eliminarPractica();
		}
	}else{
		if(isset($_POST["login"])&isset($_POST["clave1"])&isset($_POST["nombre"])&isset($_POST["email"])&isset($_POST["telefono"])){
			modificarPerfil();
		}
		
		if(isset($_POST["tipoEvento"])&isset($_POST["fecha"])&isset($_POST["nombreEvento"])){
			crearEvento();
		}
		
		if(isset($_POST["tEv"])&isset($_POST["fca"])&isset($_POST["nEv"])){
			modificarEvento();
		}

		if(isset($_POST["titulo"])&isset($_POST["descripcion"])&isset($_POST["nombreEmpresa"])&isset($_POST["periodo"])&isset($_POST["titulacion"])&isset($_POST["inicio"])&isset($_POST["fin"])&isset($_POST["horario"])&isset($_POST["pformativo"])){
			publicarPractica();
		}
		
		if(isset($_POST["tit"])&isset($_POST["des"])&isset($_POST["nEm"])&isset($_POST["per"])&isset($_POST["tcn"])&isset($_POST["ini"])&isset($_POST["fn"])&isset($_POST["hor"])&isset($_POST["pfor"])){
			modificarPractica();
		}
		
		if(isset($_POST["asunto"])&isset($_POST["remitente"])&isset($_POST["destinatario"])&isset($_POST["mensaje"])){
			enviarMensaje();
		}
		
		if(isset($_POST["asunto"])&isset($_POST["mensajeMultiple"])){
			enviarMensajeMultiple();
		}
	}
	
function enviarMensajeMultiple(){
	$asunto = $_POST["asunto"];
	$mensaje = $_POST["mensajeMultiple"];
	
	$e = new Estudiante();
	$estudiantes = $e->selectAll();
	$t = new Tutor();
	$tutores = $t->selectAll();
	$emp = new Empresa();
	$empresas = $emp->selectAll();
	
	$destinatarios = array();
	$i=0;
	
	foreach($estudiantes as $estudiante){
		$destinatarios[$i] = $estudiante["email"];
		$i=$i+1;
	}
	
	foreach($tutores as $tutor){
		$destinatarios[$i] = $tutor["email"];
		$i=$i+1;
	}
	
	foreach($empresas as $empresa){
		$destinatarios[$i] = $empresa["email"];
		$i=$i+1;
	}
	
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
	$correo->AddReplyTo("tfgpracticasesei@gmail.com","");

	//Destinatarios del correo
	foreach($destinatarios as $destinatario){
		$correo->AddAddress($destinatario,"");
	}
	
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
		header("Location: ../views/coordinador/mensajes.php?msg=$msg");
	}else{
		$msg="Mensaje enviado con éxito";
		header("Location: ../views/coordinador/mensajes.php?msg=$msg");
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
		header("Location: ../views/coordinador/mensajes.php?msg=$msg");
	}else{
		$msg="Mensaje enviado con éxito";
		header("Location: ../views/coordinador/mensajes.php?msg=$msg");
	}	
}

function eliminarPractica(){
		$idP = $_GET["idP"];
		$p = new Practicas();
		$boolean = $p->delete($idP);
		if($boolean == false){
			$msg = "No se ha podido eliminar la práctica";
			header("Location: ../views/coordinador/ofertas.php?msg2=$msg");
		}else{
			listarPracticas();
		}
}	

function modificarPractica(){
	$nEmpresa = $_POST["nEm"];
	$e = new Empresa();
	$boolean = $e->selectByName($nEmpresa);
	if($boolean == false){
		$msg = "No se ha modificado. La empresa introducida no existe en el sistema.";
		header("Location: ../views/coordinador/ofertas.php?msg2=$msg");
	}else{
		$titulo = $_POST["tit"];
		$descripcion = $_POST["des"];
		$idEmpresa = $boolean[0]["idEmpresa"];
		$periodo = $_POST["per"];
		$titulacion = $_POST["tcn"];
		$inicio = $_POST["ini"];
		$fin = $_POST["fn"];
		$horario = $_POST["hor"];
		$pformativo = $_POST["pfor"];
		$p = new Practicas();
		$boolean = $p->select($titulo);
		if($boolean == false){
			$msg = "El titulo de la practica a modificar no se encuentra en el sistema.";
			header("Location: ../views/coordinador/ofertas.php?msg2=$msg");
		}else{
			$p = new Practicas();
			$p->set($titulo,$descripcion,$idEmpresa,$periodo,$titulacion,$inicio,$fin,$horario,$pformativo);
			$boolean = $p->update($titulo);
			if($boolean == false){
				$msg = "Error al actualizar la practica. Intentelo de nuevo";
				header("Location: ../views/coordinador/ofertas.php?msg2=$msg");			
			}else{
				listarPracticas();
			}			
		}
	}	
}
	
function publicarPractica(){
	$nEmpresa = $_POST["nombreEmpresa"];
	$e = new Empresa();
	$boolean = $e->selectByName($nEmpresa);
	if($boolean == false){
		$msg = "La empresa introducida no existe en el sistema. Registre la empresa primero";
		header("Location: ../views/coordinador/ofertas.php?msg2=$msg");
	}else{
		$titulo = $_POST["titulo"];
		$descripcion = $_POST["descripcion"];
		$idEmpresa = $boolean[0]["idEmpresa"];
		$periodo = $_POST["periodo"];
		$titulacion = $_POST["titulacion"];
		$inicio = $_POST["inicio"];
		$fin = $_POST["fin"];
		$horario = $_POST["horario"];
		$pformativo = $_POST["pformativo"];
		$p = new Practicas();
		$p->set($titulo,$descripcion,$idEmpresa,$periodo,$titulacion,$inicio,$fin,$horario,$pformativo);
		$boolean = $p->insert();
		if($boolean == false){
			$msg = "Error al crear la practica. Intentelo de nuevo";
			header("Location: ../views/coordinador/ofertas.php?msg2=$msg");			
		}else{
			listarPracticas();
		}
	}
}
	
function listarPracticas(){
	$p = new Practicas();
	$boolean = $p->selectAll();
	if($boolean == false){
		$msg = "No hay practicas dadas de alta actualmente en el sistema.";
		header("Location: ../views/coordinador/ofertas.php?msg=$msg");
	}else{
		$i=0;
		foreach($boolean as $d){
			$e = new Empresa();
			$id = $d["Empresa_idEmpresa"];
			$empresa = $e->selectById($id);
			$nombreEmpresa = $empresa[0]["nombre"];
			$boolean[$i]["nombreEmpresa"] = $nombreEmpresa;
			$i=$i+1;
		}
		$datos = json_encode($boolean);
		header("Location: ../views/coordinador/ofertas.php?datos=$datos");
	}
}
	
function borrarEvento(){
	$id = $_GET["id"];
	$e = new Eventos();
	$e->delete($id);
	listarEventos();
}

function modificarEvento(){
	$nEvento = $_POST["nEv"];
	$tEvento = $_POST["tEv"];
	$fecha = $_POST["fca"];
	$e = new Eventos();
	$e->set($nEvento,$tEvento,$fecha);
	$boolean = $e->update($nEvento);
	if($boolean == false){
		$msg = "El nombre del evento no existe en el sistema. Verifique que el elemento a modificar existe.";
		header("Location: ../views/coordinador/calendario.php?msg=$msg");
	}else{
		listarEventos();
	}	
}
	
function crearEvento(){
	$nEvento = $_POST["nombreEvento"];
	$tEvento = $_POST["tipoEvento"];
	$fecha = $_POST["fecha"];
	$e = new Eventos();
	$e->set($nEvento,$tEvento,$fecha);
	$boolean = $e->insert();
	if($boolean == false){
		$msg = "No se ha podido crear el evento";
		header("Location: ../views/coordinador/calendario.php?msg=$msg");
	}else{
		listarEventos();
	}
}
	
function listarEventos(){
	$e = new Eventos();
	$boolean = $e->selectAll();
	if($boolean == false){
		$msg = "No hay ningún evento actualmente.";
		header("Location: ../views/coordinador/calendario.php?msg=$msg");
	}else{
		$datos = serialize($boolean);
		header("Location: ../views/coordinador/calendario.php?datos=$datos");
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