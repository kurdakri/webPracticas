<?php
require_once("../models/coordinador.php");
require_once("../models/eventos.php");
require_once("../models/practicas.php");
require_once("../models/empresa.php");
require_once("../models/estudiante.php");
require_once("../models/tutor.php");
require_once("../models/practicasHasEstudiante.php");
require_once("../models/practicasTutorEstudiante.php");
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
		
		if($action == "listarPracticas"){
			listarPracticas2();
		}
		
		if($action == "listarPracticasAsignadas"){
			listarPracticasAsignadas();
		}
		
		if($action == "verPracticasCompleto"){
			verPracticasCompleto();
		}
		
		if($action == "eliminarAsignacionTutor"){
			eliminarAsignacionTutor();
		}
		
		if($action == "eliminarAsignacionEstudiante"){
			eliminarAsignacionEstudiante();
		}
		
		if($action == "verSolicitantes"){
			verSolicitantes();
		}
		
		if($action == "realizarAsignaciones"){
			realizarAsignaciones($_GET["datos"]);
		}
		
		if($action == "eliminarAsignaciones"){
			eliminarAsignaciones();
		}
		
		
	}else{
		if(isset($_POST["login"])&isset($_POST["clave1"])&isset($_POST["nombre"])&isset($_POST["email"])&isset($_POST["telefono"])){
			modificarPerfil();
		}
		
		if(isset($_POST["tipoEvento"])&isset($_POST["fecha"])&isset($_POST["nombreEvento"])){
			crearEvento();
		}
		
		if(isset($_POST["fca"])&isset($_POST["nEv"])){
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
		
		if(isset($_POST["nPractica"])&isset($_POST["dni"])){
			asignarEE();
		}
		
		if(isset($_POST["nPractica"])&isset($_POST["dniTutor"])){
			asignarTutorPractica();
		}
		
		if(isset($_POST["seleccionar"])&isset($_POST["idPractica"])){
			asignarTutor();
		}
		
	}
	
function asignarTutor(){
	$idTutor = $_POST["seleccionar"];
	$idPractica = $_POST["idPractica"];
	$p = new PracticasTutorEstudiante();
	$boolean = $p->updateTutor($idTutor,$idPractica);
	if($boolean == false){
		$msg = "No se ha asignado";
		header("Location: ../views/coordinador/asignacionTutores.php?msg=$msg");
	}else{
		listarPracticasAsignadas();
	}
}
	
function eliminarAsignaciones(){
	$e = new PracticasTutorEstudiante();
	$e->truncate();
	verSolicitantes();
}
	
function realizarAsignaciones($array){
	$estudiantes = json_decode($array,true);//estudiantes que requieren asignaciones
	$p = new Practicas();
	$practicas = $p->selectAll();
	$p = new PracticasTutorEstudiante();
	$pAsignadas = $p->selectAll();
	if($pAsignadas == false){
		
	}else{
		foreach($pAsignadas as $pAsignada){
			$id = $pAsignada["Practicas_idPracticas"];
			$i=0;
			foreach($practicas as $practica){
				if($practica["idPracticas"] == $id){
					unset($practicas[$i]);
				}
				$i++;
			}
		}
	}//$practicas contiene practicas que no han sido asignadas todavia al llamar a la funcion
	
	$auxiliar = false;
	//El bucle siguiente se repite mientras queden estudiantes
	while(count($estudiantes) > 0){
		if(count($practicas) == 0){
			$msg = "Se han asignado todas las practicas disponibles. Quedan estudiantes sin asignar.";
			header("Location: ../views/coordinador/asignacionEE.php?msg=$msg");
		}else{
			$notaMax = -1;
			$iselect = -1;
			$j=0;
			$estudianteSeleccionado = null;
			foreach($estudiantes as $estudiante){
				$nota = $estudiante["mediaExpediente"];
				if($nota > $notaMax){
					$estudianteSeleccionado = $estudiante;
					$notaMax = $nota;
					$iselect = $j;
				}
				$j++;
			}
			$notaMax = -1;
			//Ahora mismo $estudianteSeleccionado contiene al estudiante con mejor nota
			$z=1;
			$completa = false;
			while($z<=5){
				$idEstudianteSeleccionado = $estudianteSeleccionado["id"];
				$s = new PracticasHasEstudiante();
				$solicitud = $s->selectPri($idEstudianteSeleccionado,$z);
				$idSolicitud = $solicitud[0]["Practicas_idPracticas"];
				$aux = false;
				$j=0;
				$jselect=-1;
				foreach($practicas as $practica){
					if($idSolicitud == $practica["idPracticas"]){
						$aux = true;
						$jselect=$j;
					}else{
						
					}
					$j++;
				}
				
				if($aux == true){
					//practica solicitada disponible
					$idEmpresa = $solicitud[0]["Practicas_Empresa_idEmpresa"];
					//Asignamos la practica
					$e = new PracticasTutorEstudiante();
					$e->set(-1,$idSolicitud,$idEmpresa,$idEstudianteSeleccionado);
					$boolean = $e->insert();
					if($boolean == false){
						echo "No se hizo la insercion";
						break;
					}else{
						//Se ha insertado el estudiante, lo borramos de la lista de estudiantes pendientes de asignacion
						unset($estudiantes[$iselect]);
						$iselect = -1;
						$estudianteSeleccionado = null;
						
						//Se ha seleccionado la practica, la borramos de la lista de practicas que quedan por asignar
						unset($practicas[$jselect]);
						$jselect=-1;
						$completa = true;
						break;
					}
				}else{
					
				}
				$z++;
			}
			
			//Evita problemas
			if(count($estudiantes) == 1 & $auxiliar == true){
				unset($estudiantes);
				$estudiantes = array();
				break;
			}
			
			if(count($estudiantes) == 1){
				$auxiliar = true;
			}
			
			if($completa == false){
				//Ninguna de las practicas elegidas por el estudiante estan disponibles para el
				//Seguimos con el siguiente
						unset($estudiantes[$iselect]);
						$iselect = -1;
						$estudianteSeleccionado = null;				
			}
		}
	}
	
	verSolicitantes();
}

	
function verSolicitantes(){
	$e = new PracticasHasEstudiante();
	$solicitantes = $e->selectAll();
	$toret = array();
	foreach($solicitantes as $solicitante){
		$idSolicitante = $solicitante["Estudiante_idEstudiante"];
		if(in_array($idSolicitante,$toret)){
			
		}else{
			array_push($toret,$idSolicitante);
		}
	}
		
	$i=0;
	foreach($toret as $id){
		$pte = new PracticasTutorEstudiante();
		$boolean = $pte->selectByIdEs($id);
		if($boolean == false){
			
		}else{
			unset($toret[$i]);
		}
		$i=$i+1;
	}
	
	if(count($toret) == 0){
		$msg = "No hay ninguna solicitud sin tramitar actualmente.";
		header("Location: ../views/coordinador/asignacionEE.php?msg=$msg");
	}else{
		$i=0;
		$toret2 = array();
		foreach($toret as $est){
			$e = new Estudiante();
			$estudiante = $e->selectById($est);
			$toret2[$i]["id"] = $est;
			$toret2[$i]["nombre"] = $estudiante[0]["nombre"];
			$toret2[$i]["apellidos"] = $estudiante[0]["apellidos"];
			$toret2[$i]["dni"] = $estudiante[0]["dni"];
			$toret2[$i]["email"] = $estudiante[0]["email"];
			$toret2[$i]["mediaExpediente"] = $estudiante[0]["mediaExpediente"];
			$i++;
		}
		$datos = json_encode($toret2);
		header("Location: ../views/coordinador/asignacionEE.php?datos=$datos");
	}
}

function verSolicitantes2($message){
	$e = new PracticasHasEstudiante();
	$solicitantes = $e->selectAll();
	$toret = array();
	foreach($solicitantes as $solicitante){
		$idSolicitante = $solicitante["Estudiante_idEstudiante"];
		if(in_array($idSolicitante,$toret)){
			
		}else{
			array_push($toret,$idSolicitante);
		}
	}
		
	$i=0;
	foreach($toret as $id){
		$pte = new PracticasTutorEstudiante();
		$boolean = $pte->selectByIdEs($id);
		if($boolean == false){
			
		}else{
			unset($toret[$i]);
		}
		$i=$i+1;
	}
	
	if(count($toret) == 0){
		$msg = "No hay ninguna solicitud sin tramitar actualmente.";
		header("Location: ../views/coordinador/asignacionEE.php?msg=$msg");
	}else{
		$i=0;
		$toret2 = array();
		foreach($toret as $est){
			$e = new Estudiante();
			$estudiante = $e->selectById($est);
			$toret2[$i]["id"] = $est;
			$toret2[$i]["nombre"] = $estudiante[0]["nombre"];
			$toret2[$i]["apellidos"] = $estudiante[0]["apellidos"];
			$toret2[$i]["dni"] = $estudiante[0]["dni"];
			$toret2[$i]["email"] = $estudiante[0]["email"];
			$toret2[$i]["mediaExpediente"] = $estudiante[0]["mediaExpediente"];
			$i++;
		}
		$datos = json_encode($toret2);
		header("Location: ../views/coordinador/asignacionEE.php?datos=$datos&msg=$message");
	}
}

function eliminarAsignacionEstudiante(){
	$id = $_GET["id"];
	$p = new PracticasHasEstudiante();
	$boolean = $p->deleteById($id);
	if($boolean == false){
		$msg = "No se ha podido eliminar la asignacion. Intentelo de nuevo.";
		header("Location: ../views/coordinador/asignacionTutores.php?msg=$msg");	
	}else{
		listarPracticasAsignadas();
	}	
}	

function eliminarAsignacionTutor(){
	$id = $_GET["id"];
	$p = new PracticasTutorEstudiante();
	$boolean = $p->updateTutor(-1,$id);
	if($boolean == false){
		$msg = "No se ha podido eliminar la asignacion. Intentelo de nuevo.";
		header("Location: ../views/coordinador/asignacionesRealizadas.php?msg=$msg");	
	}else{
		verPracticasCompleto();
	}
}

	
function verPracticasCompleto(){
	$p = new PracticasTutorEstudiante();
	$practicas = $p->selectNoAsignadas();
	if($practicas == false){
		$msg="Ninguna practica asignada actualmente";
		header("Location: ../views/coordinador/asignacionesRealizadas.php?msg=$msg");		
	}else{
		$i=0;
		$toret = array();
		foreach($practicas as $practica){
			$idTutor = $practica["Tutor_idTutor"];
			$idPracticas = $practica["Practicas_idPracticas"];
			$idEmpresa = $practica["Practicas_Empresa_idEmpresa"];
			$idEstudiante = $practica["Estudiante_idEstudiante"];
			
			$p = new Practicas();
			$pra = $p->selectById($idPracticas);
			$toret[$i]["practica"]=$pra[0]["titulo"];
			
			$p = new Tutor();
			$pra = $p->selectById($idTutor);
			$toret[$i]["tutorN"]=$pra[0]["nombre"];
			$toret[$i]["tutorA"]=$pra[0]["apellidos"];
			
			$p = new Estudiante();
			$pra = $p->selectById($idEstudiante);
			$toret[$i]["estudianteN"]=$pra[0]["nombre"];
			$toret[$i]["estudianteA"]=$pra[0]["apellidos"];
			
			$p = new Empresa();
			$pra = $p->selectById($idEmpresa);
			$toret[$i]["empresa"] = $pra[0]["nombre"];
			$toret[$i]["empresaTutor"] = $pra[0]["nombreTutor"];
			
			$toret[$i]["idPractica"] = $idPracticas;
			
			$i = $i+1;
		}
		
		$datos = json_encode($toret);
		header("Location: ../views/coordinador/asignacionesRealizadas.php?datos=$datos");
	}
}
	
function asignarTutorPractica(){
	$nombrePractica = $_POST["nPractica"];
	$dniTutor = $_POST["dniTutor"];
	$array = listado();
	if(empty($array)){
		$msg="No se puede asignar un tutor si no hay practicas que lo requieran.";
		header("Location: ../views/coordinador/asignacionTutores.php?msg=$msg");
	}else{
		$t = new Tutor();
		$tutor = $t->selectByDNI($dniTutor);
		if($tutor == false){
			$msg="El DNI introducido no se corresponde a ningun tutor.";
			$datos = json_encode($array);
			header("Location: ../views/coordinador/asignacionTutores.php?msg=$msg&datos=$datos");
		}else{
			$idTutor = $tutor[0]["idTutor"];
			
			$p = new Practicas();
			$practica = $p->select($nombrePractica);
			if($practica == false){
				$msg="La practica introducida no existe.";
				$datos = json_encode($array);
				header("Location: ../views/coordinador/asignacionTutores.php?msg=$msg&datos=$datos");				
			}else{
				$aux=0;
				foreach($array as $a){
					$name = $a["nombrePractica"];
					if($name == $nombrePractica){
						$aux=1;
						$idEstudiante = $a["Estudiante_idEstudiante"];
					}
				}
				if($aux == 0){
					$msg="No se puede asignar un tutor a la practica introducida.";
					$datos = json_encode($array);
					header("Location: ../views/coordinador/asignacionTutores.php?msg=$msg&datos=$datos");					
				}else{
					$idPractica = $practica[0]["idPracticas"];
					$idEmpresa = $practica[0]["Empresa_idEmpresa"];
					
					$p = new PracticasTutorEstudiante();
					$p->set($idTutor,$idPractica,$idEmpresa,$idEstudiante);
					$boolean = $p->insert();
					if($boolean == false){
						$msg="No se ha podido realizar la asignacion. Intentelo de nuevo.";
						$datos = json_encode($array);
						header("Location: ../views/coordinador/asignacionTutores.php?msg=$msg&datos=$datos");						
					}else{
						$msg="Asignacion realizada.";
						$array = listado();
						$datos= json_encode($array);
						header("Location: ../views/coordinador/asignacionTutores.php?msg=$msg&datos=$datos");
					}
				}
			}
		}
	}
}

function listado(){
	$e = new PracticasHasEstudiante();
	$practicas = $e->selectAll();
	$i=0;
	$toret = array();
	foreach($practicas as $practica){
		$id = $practica["Practicas_idPracticas"];
		$p = new PracticasTutorEstudiante();
		$boolean = $p->selectByIdP($id);
		if($boolean == false){
				$toret[$i]=$practica;
				$i = $i+1;
		}
	}
	
	$i=0;
	foreach($toret as $array){
		$idPractica = $array["Practicas_idPracticas"];
		$idEstudiante = $array["Estudiante_idEstudiante"];
		$p = new Practicas();
		$practica = $p->selectById($idPractica);
		$nombrePractica = $practica[0]["titulo"];
		$toret[$i]["nombrePractica"] = $nombrePractica;
		$e = new Estudiante();
		$estudiante = $e->selectById($idEstudiante);
		$nombreEstudiante = $estudiante[0]["nombre"];
		$toret[$i]["nombreEstudiante"] = $nombreEstudiante;
		$i=$i+1;
	}
	return $toret;		
}

	
function listarPracticasAsignadas(){
	$e = new PracticasTutorEstudiante();
	$toret = $e->selectAsignadas();
	$t = new Tutor();
	$tutores = $t->selectAll();
	if($toret == false){
		$msg = "No hay practicas pendientes de asignar a tutores";
		header("Location: ../views/coordinador/asignacionTutores.php?msg=$msg");
	}else if($tutores == false){
		$msg = "No hay tutores registrados en la aplicacion.";
		header("Location: ../views/coordinador/asignacionTutores.php?msg=$msg");			
	}else{
		$i=0;
		foreach($toret as $array){
			$idPractica = $array["Practicas_idPracticas"];
			$idEstudiante = $array["Estudiante_idEstudiante"];
			$p = new Practicas();
			$practica = $p->selectById($idPractica);
			$nombrePractica = $practica[0]["titulo"];
			$toret[$i]["nombrePractica"] = $nombrePractica;
			$e = new Estudiante();
			$estudiante = $e->selectById($idEstudiante);
			$nombreEstudiante = $estudiante[0]["nombre"];
			$toret[$i]["nombreEstudiante"] = $nombreEstudiante;
			$i=$i+1;
		}
		$datos = json_encode($toret);
		$datos2 = json_encode($tutores);
		header("Location: ../views/coordinador/asignacionTutores.php?datos=$datos&datos2=$datos2");		
	}
}
	
function asignarEE(){
		$dni = $_POST["dni"];
		$nombrePractica = $_POST["nPractica"];
		
		$e = new Estudiante();
		$estudiante = $e->selectByDNI($dni);
		if($estudiante == false){
			$msg = "El estudiante no existe en el sistema. Revise que el estudiante esta registrado.";
			verSolicitantes2($msg);
		}else{
			$idEstudiante = $estudiante[0]["idEstudiante"];
			
			$p = new Practicas();
			$practica = $p->select($nombrePractica);
			if($practica == false){
				$msg = "La practica no existe en el sistema. Revise que el nombre es correcto";
				verSolicitantes2($msg);
			}else{
				$idPractica = $practica[0]["idPracticas"];
				$idEmpresa = $practica[0]["Empresa_idEmpresa"];
				
				$a = new PracticasTutorEstudiante();
				$boolean = $a->selectByIdP($idPractica);
				if($boolean == false){
					$a= new PracticasTutorEstudiante();
					$a->set(-1,$idPractica,$idEmpresa,$idEstudiante);
					$boolean = $a->insert();
					if($boolean == false){
						$msg = "Error de insercion. Intentelo de nuevo";
						verSolicitantes2($msg);
					}else{
						verSolicitantes();//Asignada correctamente
					}							
				}else{
						$msg = "La practica ya ha sido asignada a otro estudiante anteriormente. Intentelo con otra.";
						verSolicitantes2($msg);					
				}
			}
		}
}
			
function listarPracticas3($message){
	$p = new Practicas();
	$boolean = $p->selectAll();
	if($boolean == false){
		$msg = "No hay practicas dadas de alta actualmente en el sistema.";
		header("Location: ../views/coordinador/asignacionEE.php?msg=$msg");
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
		header("Location: ../views/coordinador/asignacionEE.php?datos=$datos&msg=$message");
	}	
}
	
function listarPracticas2(){
	$p = new Practicas();
	$boolean = $p->selectAll();
	if($boolean == false){
		$msg = "No hay practicas dadas de alta actualmente en el sistema.";
		header("Location: ../views/coordinador/asignacionEE.php?msg=$msg");
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
		header("Location: ../views/coordinador/asignacionEE.php?datos=$datos");
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
			$msg = "No se ha podido eliminar la práctica porque ya ha sido asignada.";
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