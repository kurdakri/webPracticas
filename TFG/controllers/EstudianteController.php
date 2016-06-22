<?php
require_once("../models/estudiante.php");
require_once("../models/eventos.php");
require_once("../models/practicas.php");
require_once("../models/empresa.php");
require_once("../models/coordinador.php");
require_once("../models/tutor.php");
require_once("../models/practicasTutorEstudiante.php");
require_once("../models/practicasHasEstudiante.php");
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
	
	if($action == "listarPracticas"){
		listarPracticas();
	}
	
	if($action == "asignaciones"){
		consultarAsignaciones();
	}
	
	if($action == "eliminarSolicitud"){
		eliminarSolicitud();
	}
}else{
	if(isset($_POST["login"])&isset($_POST["clave1"])&isset($_POST["nombre"])&isset($_POST["apellidos"])&isset($_POST["email"])&isset($_POST["telefono"])
		&isset($_POST["dni"])&isset($_POST["fNac"])&isset($_POST["campus"])&isset($_POST["facultad"])&isset($_POST["titulacion"])&isset($_POST["curso"])
	&isset($_POST["clave1"])&isset($_POST["aInicio"])&isset($_POST["pAntesYear"])){
		modificarPerfil();
	}
	
	if(isset($_POST["asunto"])&isset($_POST["remitente"])&isset($_POST["destinatario"])&isset($_POST["mensaje"])){
		enviarMensaje();
	}
	
	if(isset($_POST["alumno"])&isset($_POST["emp1"])&isset($_POST["emp2"])&isset($_POST["emp3"])&isset($_POST["emp4"])&isset($_POST["emp5"])){
		solicitarPracticas();
	}
	
	if(isset($_POST["motivo"])){
		solicitarAnulacion();
	}
	
	if(isset($_POST["mot"])){
		solicitarCambio();
	}
	
	if(isset($_POST["enviarInforme"])){
		enviarInforme();
	}
	
	if(isset($_POST["formularioSolicitud1"])){
		procesarSolicitud1();
	}
	
	
	if(isset($_POST["formularioSolicitud2"])){
		procesarSolicitud2();
	}
	
	
	if(isset($_POST["formularioSolicitud3"])){
		procesarSolicitud3();
	}
	
	
	if(isset($_POST["formularioSolicitud4"])){
		procesarSolicitud4();
	}
	
	
	if(isset($_POST["formularioSolicitud5"])){
		procesarSolicitud5();
	}
	
}

function procesarSolicitud1(){
	$p1 = $_POST["p1"];
	
	$p = new Practicas();
	$practica = $p->select($p1);
	$idp1 = $practica[0]["idPracticas"];
	$ide1 = $practica[0]["Empresa_idEmpresa"];
	
	session_start();
	$loginEstudiante = $_SESSION["name"];
	$e = new Estudiante();
	$estudiante = $e->select($loginEstudiante);
	$idest = $estudiante[0]["idEstudiante"];
	
	$p = new PracticasHasEstudiante();
	$boolean = $p->select($idest); //Recoge las practicas del estudiante
	
	if($boolean == false){//Tiene en cuenta un intento de insercion sin recargar la pagina
		$b = new PracticasHasEstudiante();
		$b->set($idp1,$ide1,$idest,1);
		$boolean1 = $b->insert();//Realiza la insercion
		
		$p = new PracticasHasEstudiante();
		$boolean = $p->select($idest); //Recoge las practicas del estudiante
		$array = array();
		$i = 0;
		foreach($boolean as $practica){
			$idPractica = $practica["Practicas_idPracticas"];
			$prioridad = $practica["Prioridad"];
			$p = new Practicas();
			$prac = $p->selectById($idPractica);
			$array[$i]["titulo"] = $prac[0]["titulo"];
			$array[$i]["prioridad"] = $prioridad;
			$i++;
		}
		$arraySend = json_encode($array);
		header("Location: ../views/estudiante/solicitudes.php?array=$arraySend");					
	}else{
		$array = array();
		$i = 0;
		foreach($boolean as $practica){
			$idPractica = $practica["Practicas_idPracticas"];
			$prioridad = $practica["Prioridad"];
			$p = new Practicas();
			$prac = $p->selectById($idPractica);
			$array[$i]["titulo"] = $prac[0]["titulo"];
			$array[$i]["prioridad"] = $prioridad;
			$i++;
		}
		$arraySend = json_encode($array);
		$sivarita = "Ya envio una solicitud anteriormente. Elimine la solicitud si quiere enviar otra en su lugar.";
		header("Location: ../views/estudiante/solicitudes.php?array=$arraySend&sivarita=$sivarita");		
	}		
}

function procesarSolicitud2(){
	$p1 = $_POST["p1"];
	$p2 = $_POST["p2"];
	
	$p = new Practicas();
	$practica = $p->select($p1);
	$idp1 = $practica[0]["idPracticas"];
	$ide1 = $practica[0]["Empresa_idEmpresa"];
	
	$p = new Practicas();
	$practica = $p->select($p2);
	$idp2 = $practica[0]["idPracticas"];
	$ide2 = $practica[0]["Empresa_idEmpresa"];
	
	session_start();
	$loginEstudiante = $_SESSION["name"];
	$e = new Estudiante();
	$estudiante = $e->select($loginEstudiante);
	$idest = $estudiante[0]["idEstudiante"];
	
	$p = new PracticasHasEstudiante();
	$boolean = $p->select($idest); //Recoge las practicas del estudiante
	
	if($boolean == false){
		$b = new PracticasHasEstudiante();
		$b->set($idp1,$ide1,$idest,1);
		$boolean1 = $b->insert();//Realiza la insercion 1

		$b = new PracticasHasEstudiante();
		$b->set($idp2,$ide2,$idest,2);
		$boolean2 = $b->insert();//Realiza la insercion 2

		$p = new PracticasHasEstudiante();
		$boolean = $p->select($idest); //Recoge las practicas del estudiante
		$array = array();
		$i = 0;
		foreach($boolean as $practica){
			$idPractica = $practica["Practicas_idPracticas"];
			$prioridad = $practica["Prioridad"];
			$p = new Practicas();
			$prac = $p->selectById($idPractica);
			$array[$i]["titulo"] = $prac[0]["titulo"];
			$array[$i]["prioridad"] = $prioridad;
			$i++;
		}
		$arraySend = json_encode($array);
		header("Location: ../views/estudiante/solicitudes.php?array=$arraySend");	
		
		
	}else{
		$array = array();
		$i = 0;
		foreach($boolean as $practica){
			$idPractica = $practica["Practicas_idPracticas"];
			$prioridad = $practica["Prioridad"];
			$p = new Practicas();
			$prac = $p->selectById($idPractica);
			$array[$i]["titulo"] = $prac[0]["titulo"];
			$array[$i]["prioridad"] = $prioridad;
			$i++;
		}
		$arraySend = json_encode($array);
		$sivarita = "Ya envio una solicitud anteriormente. Elimine la solicitud si quiere enviar otra en su lugar.";
		header("Location: ../views/estudiante/solicitudes.php?array=$arraySend&sivarita=$sivarita");			
	}	
}

function procesarSolicitud3(){
	$p1 = $_POST["p1"];
	$p2 = $_POST["p2"];
	$p3 = $_POST["p3"];
	
	$p = new Practicas();
	$practica = $p->select($p1);
	$idp1 = $practica[0]["idPracticas"];
	$ide1 = $practica[0]["Empresa_idEmpresa"];
	
	$p = new Practicas();
	$practica = $p->select($p2);
	$idp2 = $practica[0]["idPracticas"];
	$ide2 = $practica[0]["Empresa_idEmpresa"];
	
	$p = new Practicas();
	$practica = $p->select($p3);
	$idp3 = $practica[0]["idPracticas"];
	$ide3 = $practica[0]["Empresa_idEmpresa"];
	
	session_start();
	$loginEstudiante = $_SESSION["name"];
	$e = new Estudiante();
	$estudiante = $e->select($loginEstudiante);
	$idest = $estudiante[0]["idEstudiante"];
	
	$p = new PracticasHasEstudiante();
	$boolean = $p->select($idest); //Recoge las practicas del estudiante
	
	if($boolean == false){
		$b = new PracticasHasEstudiante();
		$b->set($idp1,$ide1,$idest,1);
		$boolean1 = $b->insert();//Realiza la insercion 1

		$b = new PracticasHasEstudiante();
		$b->set($idp2,$ide2,$idest,2);
		$boolean2 = $b->insert();//Realiza la insercion 2
		
		$b = new PracticasHasEstudiante();
		$b->set($idp3,$ide3,$idest,3);
		$boolean3 = $b->insert(); //Realiza la insercion 3
		
		$p = new PracticasHasEstudiante();
		$boolean = $p->select($idest); //Recoge las practicas del estudiante
		$array = array();
		$i = 0;
		foreach($boolean as $practica){
			$idPractica = $practica["Practicas_idPracticas"];
			$prioridad = $practica["Prioridad"];
			$p = new Practicas();
			$prac = $p->selectById($idPractica);
			$array[$i]["titulo"] = $prac[0]["titulo"];
			$array[$i]["prioridad"] = $prioridad;
			$i++;
		}
		$arraySend = json_encode($array);
		header("Location: ../views/estudiante/solicitudes.php?array=$arraySend");		
	}else{
		$array = array();
		$i = 0;
		foreach($boolean as $practica){
			$idPractica = $practica["Practicas_idPracticas"];
			$prioridad = $practica["Prioridad"];
			$p = new Practicas();
			$prac = $p->selectById($idPractica);
			$array[$i]["titulo"] = $prac[0]["titulo"];
			$array[$i]["prioridad"] = $prioridad;
			$i++;
		}
		$arraySend = json_encode($array);
		$sivarita = "Ya envio una solicitud anteriormente. Elimine la solicitud si quiere enviar otra en su lugar.";
		header("Location: ../views/estudiante/solicitudes.php?array=$arraySend&sivarita=$sivarita");				
	}
}

function procesarSolicitud4(){
	$p1 = $_POST["p1"];
	$p2 = $_POST["p2"];
	$p3 = $_POST["p3"];
	$p4 = $_POST["p4"];

	$p = new Practicas();
	$practica = $p->select($p1);
	$idp1 = $practica[0]["idPracticas"];
	$ide1 = $practica[0]["Empresa_idEmpresa"];
	
	$p = new Practicas();
	$practica = $p->select($p2);
	$idp2 = $practica[0]["idPracticas"];
	$ide2 = $practica[0]["Empresa_idEmpresa"];
	
	$p = new Practicas();
	$practica = $p->select($p3);
	$idp3 = $practica[0]["idPracticas"];
	$ide3 = $practica[0]["Empresa_idEmpresa"];	
	
	$p = new Practicas();
	$practica = $p->select($p4);
	$idp4 = $practica[0]["idPracticas"];
	$ide4 = $practica[0]["Empresa_idEmpresa"];
	
	session_start();
	$loginEstudiante = $_SESSION["name"];
	$e = new Estudiante();
	$estudiante = $e->select($loginEstudiante);
	$idest = $estudiante[0]["idEstudiante"];
	
	$p = new PracticasHasEstudiante();
	$boolean = $p->select($idest); //Recoge las practicas del estudiante
	
	if($boolean == false){
		$b = new PracticasHasEstudiante();
		$b->set($idp1,$ide1,$idest,1);
		$boolean1 = $b->insert();//Realiza la insercion 1

		$b = new PracticasHasEstudiante();
		$b->set($idp2,$ide2,$idest,2);
		$boolean2 = $b->insert();//Realiza la insercion 2
		
		$b = new PracticasHasEstudiante();
		$b->set($idp3,$ide3,$idest,3);
		$boolean3 = $b->insert(); //Realiza la insercion 3
		
		$b = new PracticasHasEstudiante();
		$b->set($idp4,$ide4,$idest,4);
		$boolean4 = $b->insert(); //Realiza la insercion 4

		$p = new PracticasHasEstudiante();
		$boolean = $p->select($idest); //Recoge las practicas del estudiante
		$array = array();
		$i = 0;
		foreach($boolean as $practica){
			$idPractica = $practica["Practicas_idPracticas"];
			$prioridad = $practica["Prioridad"];
			$p = new Practicas();
			$prac = $p->selectById($idPractica);
			$array[$i]["titulo"] = $prac[0]["titulo"];
			$array[$i]["prioridad"] = $prioridad;
			$i++;
		}
		$arraySend = json_encode($array);
		header("Location: ../views/estudiante/solicitudes.php?array=$arraySend");
		
	}else{
		$array = array();
		$i = 0;
		foreach($boolean as $practica){
			$idPractica = $practica["Practicas_idPracticas"];
			$prioridad = $practica["Prioridad"];
			$p = new Practicas();
			$prac = $p->selectById($idPractica);
			$array[$i]["titulo"] = $prac[0]["titulo"];
			$array[$i]["prioridad"] = $prioridad;
			$i++;
		}
		$arraySend = json_encode($array);
		$sivarita = "Ya envio una solicitud anteriormente. Elimine la solicitud si quiere enviar otra en su lugar.";
		header("Location: ../views/estudiante/solicitudes.php?array=$arraySend&sivarita=$sivarita");
	}
}

function procesarSolicitud5(){
	$p1 = $_POST["p1"];
	$p2 = $_POST["p2"];
	$p3 = $_POST["p3"];
	$p4 = $_POST["p4"];
	$p5 = $_POST["p5"];
	
	$p = new Practicas();
	$practica = $p->select($p1);
	$idp1 = $practica[0]["idPracticas"];
	$ide1 = $practica[0]["Empresa_idEmpresa"];
	
	$p = new Practicas();
	$practica = $p->select($p2);
	$idp2 = $practica[0]["idPracticas"];
	$ide2 = $practica[0]["Empresa_idEmpresa"];

	$p = new Practicas();
	$practica = $p->select($p3);
	$idp3 = $practica[0]["idPracticas"];
	$ide3 = $practica[0]["Empresa_idEmpresa"];

	$p = new Practicas();
	$practica = $p->select($p4);
	$idp4 = $practica[0]["idPracticas"];
	$ide4 = $practica[0]["Empresa_idEmpresa"];

	$p = new Practicas();
	$practica = $p->select($p5);
	$idp5 = $practica[0]["idPracticas"];
	$ide5 = $practica[0]["Empresa_idEmpresa"];
	
	session_start();
	$loginEstudiante = $_SESSION["name"];
	$e = new Estudiante();
	$estudiante = $e->select($loginEstudiante);
	$idest = $estudiante[0]["idEstudiante"];
	
	$p = new PracticasHasEstudiante();
	$boolean = $p->select($idest); //Recoge las practicas del estudiante
	
	if($boolean == false){
		$b = new PracticasHasEstudiante();
		$b->set($idp1,$ide1,$idest,1);
		$boolean1 = $b->insert();//Realiza la insercion 1

		$b = new PracticasHasEstudiante();
		$b->set($idp2,$ide2,$idest,2);
		$boolean2 = $b->insert();//Realiza la insercion 2
		
		$b = new PracticasHasEstudiante();
		$b->set($idp3,$ide3,$idest,3);
		$boolean3 = $b->insert(); //Realiza la insercion 3
		
		$b = new PracticasHasEstudiante();
		$b->set($idp4,$ide4,$idest,4);
		$boolean4 = $b->insert(); //Realiza la insercion 4
		
		$b = new PracticasHasEstudiante();
		$b->set($idp5,$ide5,$idest,5);
		$boolean5 = $b->insert(); //Realiza la insercion 5
		
		$p = new PracticasHasEstudiante();
		$boolean = $p->select($idest); //Recoge las practicas del estudiante
		$array = array();
		$i = 0;
		foreach($boolean as $practica){
			$idPractica = $practica["Practicas_idPracticas"];
			$prioridad = $practica["Prioridad"];
			$p = new Practicas();
			$prac = $p->selectById($idPractica);
			$array[$i]["titulo"] = $prac[0]["titulo"];
			$array[$i]["prioridad"] = $prioridad;
			$i++;
		}
		$arraySend = json_encode($array);
		header("Location: ../views/estudiante/solicitudes.php?array=$arraySend");				
	}else{
		$array = array();
		$i = 0;
		foreach($boolean as $practica){
			$idPractica = $practica["Practicas_idPracticas"];
			$prioridad = $practica["Prioridad"];
			$p = new Practicas();
			$prac = $p->selectById($idPractica);
			$array[$i]["titulo"] = $prac[0]["titulo"];
			$array[$i]["prioridad"] = $prioridad;
			$i++;
		}
		$arraySend = json_encode($array);
		$sivarita = "Ya envio una solicitud anteriormente. Elimine la solicitud si quiere enviar otra en su lugar.";
		header("Location: ../views/estudiante/solicitudes.php?array=$arraySend&sivarita=$sivarita");
	}
}


function eliminarSolicitud(){
	$e = new Estudiante();
	session_start();
	$id = $_SESSION["name"];
	$estudiante = $e->select($id);
	$idEstudiante = $estudiante[0]["idEstudiante"];
	$p = new PracticasHasEstudiante();
	$boolean = $p->deleteEstudiante($idEstudiante);
	$p = new Practicas();
	$practicas = $p->selectAll();
	if($practicas == false){
		$msg = "Solicitud eliminada. No hay propuestas de practicas en este momento";
		header("Location: ../views/estudiante/solicitudes.php?msg=$msg");
	}else{
		$i=0;
		foreach($practicas as $practica){
			$e = new Empresa();
			$id = $practica["Empresa_idEmpresa"];
			$empresa = $e->selectById($id);
			$nombreEmpresa = $empresa[0]["nombre"];
			$tutorEmpresa = $empresa[0]["nombreTutor"];
			$practicas[$i]["nombreEmpresa"] = $nombreEmpresa;
			$practicas[$i]["tutorEmpresa"] = $tutorEmpresa;
			$i=$i+1;
		}
		$datos = json_encode($practicas);
		$msg = "Solicitud eliminada.Puede solicitar practicas nuevamente.";
		header("Location: ../views/estudiante/solicitudes.php?datos=$datos&msg=$msg");
	}
}


function enviarInforme(){
	$destinatario = $_POST["cTutor"];
	$file = $_FILES["archivo"];
	session_start();
	$loginEstudiante = $_SESSION["name"];
	$e = new Estudiante();
	$estudiante = $e->select($loginEstudiante);
	$nombreEstudiante = $estudiante[0]["nombre"];
	$remitente = $estudiante[0]["email"];
	
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
	$correo->Subject = "Informe de realizacion de practicas del estudiante: ";

	//Contenido del mensaje
	$correo->IsHTML(false);
	$correo->Body = " ";
	

	//ARCHIVOS ADJUNTOS
	$correo->AddAttachment($file["tmp_name"],$file["name"]);

	if(!$correo->Send()){
		$msg="Error:".$correo->ErrorInfo;
		header("Location: ../views/estudiante/enviarFormulario.php?msg=$msg");
	}else{
		$msg="Mensaje enviado exitosamente";
		header("Location: ../views/estudiante/enviarFormulario.php?msg=$msg");
	}
}

function consultarAsignaciones(){
	session_start();
	$loginEstudiante = $_SESSION["name"];
	$e = new Estudiante();
	$estudiante = $e->select($loginEstudiante);
	$idEstudiante = $estudiante[0]["idEstudiante"];
	
	$e = new PracticasTutorEstudiante();
	$boolean = $e->selectByIdEs($idEstudiante);
	if($boolean == false){
		$msg = "No tiene ninguna practica asignada actualmente";
		header("Location: ../views/estudiante/asignaciones.php?msg=$msg");
	}else{
		$i=0;
		$toret = array();
		$encuentra = false;
		foreach($boolean as $asignada){
			$idPractica = $asignada["Practicas_idPracticas"];
			$idTutor = $asignada["Tutor_idTutor"];
			$idEmpresa = $asignada["Practicas_Empresa_idEmpresa"];
			
			if($idTutor == -1){
				
			}else{
				$encuentra = true;
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
			}
			$i = $i+1;
		}
		
		if($encuentra == true){
			$datos = json_encode($toret);
			header("Location: ../views/estudiante/asignaciones.php?datos=$datos");			
		}else{
			$msg = "No tiene ninguna practica asignada actualmente";
			header("Location: ../views/estudiante/asignaciones.php?msg=$msg");			
		}
		

	}	
}

function solicitarCambio(){
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
	
	$e = new Estudiante();
	session_start();
	$id = $_SESSION["name"];
	$estudiante = $e->select($id);
	$remitente = $estudiante[0]["email"];
	$nombre = $estudiante[0]["nombre"];
	$dni = $estudiante[0]["dni"];
	
	$c = new Coordinador();
	$coordinador = $c->selectAll();
	$destinatario = $coordinador[0]["email"];
	
	//Decimos quien envía el correo
	$correo->SetFrom("tfgpracticasesei@gmail.com","");

	//Para indicar a quien tiene que responder el correo
	$correo->AddReplyTo($remitente,"");

	//Destinatario del correo
	$correo->AddAddress($destinatario,"");

	//Asunto del mensaje
	$correo->Subject = "Solicitud de cambio de prácticas.";

	//Contenido del mensaje
	//$correo->IsHTML(false);
	//$correo->Body = $mensaje;
	
	$correo->MsgHTML("
	<b>SOLICITUD DE CAMBIO DE PRACTICAS</b>
	<br>
	<br>
	<b>Alumno:</b>".utf8_decode($nombre)."
	<br>
	<b>DNI:</b>".utf8_decode($dni)."
	<br>
	<b>Motivo:</b>".utf8_decode($_POST["mot"]));

	//ARCHIVOS ADJUNTOS
	//$correo->AddAttachment("ruta");

	if(!$correo->Send()){
		$msg="Error:".$correo->ErrorInfo;
		header("Location: ../views/estudiante/anulaciones.php?msg=$msg");
	}else{
		$msg="Mensaje enviado exitosamente";
		header("Location: ../views/estudiante/anulaciones.php?msg=$msg");
	}		
}

function solicitarAnulacion(){
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
	
	$e = new Estudiante();
	session_start();
	$id = $_SESSION["name"];
	$estudiante = $e->select($id);
	$remitente = $estudiante[0]["email"];
	$nombre = $estudiante[0]["nombre"];
	$dni = $estudiante[0]["dni"];
	
	$c = new Coordinador();
	$coordinador = $c->selectAll();
	$destinatario = $coordinador[0]["email"];
	
	//Decimos quien envía el correo
	$correo->SetFrom("tfgpracticasesei@gmail.com","");

	//Para indicar a quien tiene que responder el correo
	$correo->AddReplyTo($remitente,"");

	//Destinatario del correo
	$correo->AddAddress($destinatario,"");

	//Asunto del mensaje
	$correo->Subject = "Solicitud de anulacion de prácticas.";

	//Contenido del mensaje
	//$correo->IsHTML(false);
	//$correo->Body = $mensaje;
	
	$correo->MsgHTML("
	<b>SOLICITUD DE ANULACION DE PRACTICAS</b>
	<br>
	<br>
	<b>Alumno:</b>".utf8_decode($nombre)."
	<br>
	<b>DNI:</b>".utf8_decode($dni)."
	<br>
	<b>Motivo:</b>".utf8_decode($_POST["motivo"]));

	//ARCHIVOS ADJUNTOS
	//$correo->AddAttachment("ruta");

	if(!$correo->Send()){
		$msg="Error:".$correo->ErrorInfo;
		header("Location: ../views/estudiante/anulaciones.php?msg=$msg");
	}else{
		$msg="Mensaje enviado exitosamente";
		header("Location: ../views/estudiante/anulaciones.php?msg=$msg");
	}		
}


function listarPracticas(){
	$e = new Estudiante();
	session_start();
	$id = $_SESSION["name"];
	$estudiante = $e->select($id);
	$idEstudiante = $estudiante[0]["idEstudiante"];
	$p = new PracticasHasEstudiante();
	$boolean = $p->select($idEstudiante);
	if($boolean == false){
		//El estudiante no ha solicitado practicas con anterioridad
		$p = new Practicas();
		$practicas = $p->selectAll();
		if($practicas == false){
			$msg = "No hay ninguna oferta de practicas en este momento.";
			header("Location: ../views/estudiante/solicitudes.php?msg=$msg");
		}else{
			$i=0;
			foreach($practicas as $practica){
				$e = new Empresa();
				$id = $practica["Empresa_idEmpresa"];
				$empresa = $e->selectById($id);
				$nombreEmpresa = $empresa[0]["nombre"];
				$tutorEmpresa = $empresa[0]["nombreTutor"];
				$practicas[$i]["nombreEmpresa"] = $nombreEmpresa;
				$practicas[$i]["tutorEmpresa"] = $tutorEmpresa;
				$i=$i+1;
			}
			$datos = json_encode($practicas);
			header("Location: ../views/estudiante/solicitudes.php?datos=$datos");
		}
		
	}else{
		//El estudiante ya ha solicitado practicas anteriormente
		$array = array();
		$i=0;
		foreach($boolean as $practica){
			$idPractica = $practica["Practicas_idPracticas"];
			$prioridad = $practica["Prioridad"];
			$p = new Practicas();
			$prac = $p->selectById($idPractica);
			$array[$i]["titulo"] = $prac[0]["titulo"];
			$array[$i]["prioridad"] = $prioridad;
			$i++;
		}
		$arraySend = json_encode($array);
		header("Location: ../views/estudiante/solicitudes.php?array=$arraySend");
	}
}

function listarFormularios(){
	session_start();
	$idActual = $_SESSION["name"];
	$es = new Estudiante();
	$estudiante = $es->select($idActual);
	$tipo = $estudiante[0]["titulacion"];
	if($tipo == "Master"){
		$e = new Eventos();
		$eventos = $e->selectByTipo("Estudiante Master");
		if($eventos == false){
			$msg = "No hay formularios a rellenar en este momento.";
			header("Location: ../views/estudiante/formularios.php?msg=$msg");
		}else{
			$datos = json_encode($eventos);
			header("Location: ../views/estudiante/formularios.php?datos=$datos");
		}		
	}else if($tipo == "Grado"){
		$e = new Eventos();
		$eventos = $e->selectByTipo("Estudiante Grado");
		if($eventos == false){
			$msg = "No hay formularios a rellenar en este momento.";
			header("Location: ../views/estudiante/formularios.php?msg=$msg");
		}else{
			$datos = json_encode($eventos);
			header("Location: ../views/estudiante/formularios.php?datos=$datos");
		}		
	}else{
		print_r($estudiante);
		echo $tipo;
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
		header("Location: ../views/estudiante/mensajes.php?msg=$msg");
	}else{
		$msg="Mensaje enviado exitosamente";
		header("Location: ../views/estudiante/mensajes.php?msg=$msg");
	}		
}

function modificarPerfil(){
	session_start();
	$idActual = $_SESSION["name"];
	$nom = $_POST["nombre"];
	$ape = $_POST["apellidos"];
	$dn = $_POST["dni"];
	$feN = $_POST["fNac"];
	$ema = $_POST["email"];
	$tel = $_POST["telefono"];
	$log = $_POST["login"];
	$pas = $_POST["clave1"];
	$cam = $_POST["campus"];
	$fac = $_POST["facultad"];
	$tit = $_POST["titulacion"];
	$cur = $_POST["curso"];
	$ini = $_POST["aInicio"];
	$pay = $_POST["pAntesYear"];
	$expA = $_POST["expA"];
	$loginActual = $_POST["loginActual"];
	if($pay == ""){
		$pan = 0;
	}else{
		$pan = 1;
	}
	$e = new Estudiante();
	$estudiante = $e->select($log);
	if($estudiante == false){
		$e = new Estudiante();
		$e->set($nom,$ape,$dn,$feN,$ema,$tel,$log,$pas,$cam,$fac,$tit,$cur,$ini,$pan,$pay,$expA);
		$boolean = $e->update($idActual);
		if($boolean == false){
			$msg = "Error modificando el perfil. Inténtelo de nuevo";
			header("Location: ../views/estudiante/perfil.php?msg=$msg");
		}else{
			echo $idActual;
			$_SESSION["validated"] = "";
			session_destroy();
			$msg = "Datos del perfil modificados. Loguee de nuevo";
			header("Location: ../views/mainRAC/acceso.php?msg=$msg");
		}		
	}else{
			if($loginActual == $log){
				
				$e = new Estudiante();
				$e->set($nom,$ape,$dn,$feN,$ema,$tel,$log,$pas,$cam,$fac,$tit,$cur,$ini,$pan,$pay,$expA);
				$boolean = $e->update($idActual);
				if($boolean == false){
					$msg = "Error modificando el perfil. Inténtelo de nuevo";
					header("Location: ../views/estudiante/perfil.php?msg=$msg");
				}else{
					echo $idActual;
					$_SESSION["validated"] = "";
					session_destroy();
					$msg = "Datos del perfil modificados. Loguee de nuevo";
					header("Location: ../views/mainRAC/acceso.php?msg=$msg");
				}	
						
			}else{
				$msg = "El login de usuario $log ya existe en el sistema.";
				$array = array();
				$array["nombre"] = $nom;
				$array["apellidos"] = $ape;
				$array["email"] = $ema;
				$array["telefono"] = $tel;
				$array["dni"] = $dn;
				$array["fNac"] = $feN;
				$array["campus"] = $cam;
				$array["facultad"] = $fac;
				$array["titulacion"] = $tit;
				$array["curso"] = $cur;
				$array["expA"] = $expA;
				$array["aInicio"] = $ini;
				$array["pAntesYear"] = $pay;
				$arraySend = json_encode($array);
				verPerfil2($msg,$arraySend);				
			}			
	}	
}

function verPerfil2($message,$array){
	session_start();
	$login = $_SESSION["name"];
	$e = new Estudiante();
	$boolean = $e->select($login);
	$datos = json_encode($boolean);
	header("Location: ../views/estudiante/perfil.php?datos=$datos&msg=$message&array=$array");	
}

function verPerfil(){
	session_start();
	$login = $_SESSION["name"];
	$e = new Estudiante();
	$boolean = $e->select($login);
	$datos = json_encode($boolean);
	header("Location: ../views/estudiante/perfil.php?datos=$datos");	
}

function logout(){
	session_start();
	$_SESSION["validated"] = "";
	session_destroy();
	header("Location: ../index.php");	
}
?>