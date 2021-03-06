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
			ofertas();
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
		
		if($action == "eliminaUna"){
			eliminaUna();
		}
		
		if($action == "listaDeAsignadas"){
			listaDeAsignadas();
		}
		
		if($action == "eliminarPanelEE"){
			eliminarPanelEE();
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
		
		if(isset($_POST["asunto"])&isset($_POST["destinatario"])&isset($_POST["mensaje"])){
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

function eliminarPanelEE(){
	$idPractica = $_GET["id"];
	$p = new PracticasTutorEstudiante();
	$boolean = $p->deleteById($idPractica);
	$msg = "Asignacion de estudiante eliminada.";
	listaDeAsignadas2($msg);
}

function listaDeAsignadas2($msgx){
	$e = new PracticasTutorEstudiante();
	$toret = $e->selectAsignadas();
	if($toret == false){
		$msg = "Asignacion eliminada.No hay practicas asignadas a estudiantes en este momento.";
		header("Location: ../views/coordinador/listaDeAsignadas.php?msg=$msg");
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
		header("Location: ../views/coordinador/listaDeAsignadas.php?datos=$datos&msg=$msgx");
	}
}
	
function eliminaUna(){
	$id = $_GET["id"];
	$p = new PracticasTutorEstudiante();
	$boolean = $p->deleteById($id);
	$msg = "Asignacion de estudiante eliminada.";
	listarPracticasAsignadas2($msg);
}	
	
function ofertas(){
	$p = new Practicas();
	$boolean = $p->selectAll();
	$e = new Empresa();
	$empresas = $e->selectAll();
	if($empresas == false){
		$msg = "No se pueden crear practicas si no hay empresas dadas de alta en el sistema. Por favor, registre las empresas primero.";
		header("Location: ../views/coordinador/ofertasErr.php?msg=$msg");
	}else{
		if($boolean == false){
			$msg = "No hay practicas dadas de alta actualmente en el sistema.";
			$infoEmpresas = json_encode($empresas);
			header("Location: ../views/coordinador/ofertas.php?msg=$msg&infoEmpresas=$infoEmpresas");
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
			$infoEmpresas = json_encode($empresas);
			$datos = json_encode($boolean);
			header("Location: ../views/coordinador/ofertas.php?datos=$datos&infoEmpresas=$infoEmpresas");
		}	
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
		$msg = "Asignacion realizada.";
		listarPracticasAsignadas2($msg);
	}
}

function listarPracticasAsignadas2($msgx){
	$e = new PracticasTutorEstudiante();
	$toret = $e->selectAsignadas();
	$t = new Tutor();
	$tutores = $t->selectAll();
	if($toret == false){
		$msg = "No hay practicas pendientes de asignar a tutores";
		header("Location: ../views/coordinador/asignacionTutores.php?msg=$msg&msgx=$msgx");
	}else if($tutores == false){
		$msg = "No hay tutores registrados en la aplicacion.";
		header("Location: ../views/coordinador/asignacionTutores.php?msg=$msg&msgx=$msgx");			
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
		header("Location: ../views/coordinador/asignacionTutores.php?datos=$datos&datos2=$datos2&msgx=$msgx");		
	}
}
	
function eliminarAsignaciones(){
	$e = new PracticasTutorEstudiante();
	$e->truncate();
	$msg="Se han eliminado todas las asignaciones de practicas.";
	verSolicitantes2($msg);
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
	
	while(!empty($estudiantes)){
		if(empty($practicas)){
			break;
		}
		$notaMax = -1;
		$i = 0;
		foreach($estudiantes as $estudiante){
			$nota = $estudiante["mediaExpediente"];
			if($nota >= $notaMax){
				$notaMax = $nota;
				$estudianteSeleccionado = $estudiante;
				$estudiantePosicion = $i;
			}
			$i=$i+1;
		}
		
		$prioridad = 1;
		while($prioridad <=5){
			$idEstudianteSeleccionado = $estudianteSeleccionado["id"];
			$s = new PracticasHasEstudiante();
			$solicitud = $s->selectPri($idEstudianteSeleccionado,$prioridad);
			$idSolicitud = $solicitud[0]["Practicas_idPracticas"];
			
			$j=0;
			$encuentra = false;
			foreach($practicas as $practica){
				$idPractica = $practica["idPracticas"];
				if($idPractica == $idSolicitud){
					$practicaPosicion = $j;
					$encuentra = true;
					break;
				}
				$j=$j+1;
			}
			if($encuentra == true){
				break;
			}
			$prioridad=$prioridad+1;;
		}
		
		if($encuentra == true){
			$idEmpresa = $solicitud[0]["Practicas_Empresa_idEmpresa"];
			//Asignamos la practica
			$e = new PracticasTutorEstudiante();
			$e->set(-1,$idSolicitud,$idEmpresa,$idEstudianteSeleccionado);
			$boolean = $e->insert();
				
			//Borramos estudiante y practica de la lista de disponibles
			unset($estudiantes[$estudiantePosicion]);
			unset($practicas[$practicaPosicion]);
			$k=0;
			$aux=array();
			foreach($estudiantes as $estudiante){
				$aux[$k] = $estudiante;
				$k++;
			}
			$estudiantes = $aux;
			$k=0;
			unset($aux);
			$aux=array();
			foreach($practicas as $practica){
				$aux[$k] = $practica;
				$k++;
			}
			$practicas = $aux;
			unset($aux);
		}else{
			unset($estudiantes[$estudiantePosicion]);
			$k=0;
			$aux=array();
			foreach($estudiantes as $estudiante){
				$aux[$k] = $estudiante;
				$k++;
			}
			$estudiantes = $aux;
			unset($aux);
		}
		
	}
	mensajeAsignacion();
}

function mensajeAsignacion(){
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
		$msg = "Se han realizado las asignaciones automaticas a los solicitantes.";
		header("Location: ../views/coordinador/mensajeAsignacion.php?msg=$msg");
	}else{
		$msg = "Se han realizado las asignaciones automaticas a los solicitantes. Hay estudiantes cuya solicitud no ha sido aceptada porque las practicas elegidas
		fueron asignadas a otros con mayor prioridad.\n Revise el listado de solicitantes y contacte con ellos para la asignacion manual.";
		header("Location: ../views/coordinador/mensajeAsignacion.php?msg=$msg");
	}	
}



function listaDeAsignadas(){
	$e = new PracticasTutorEstudiante();
	$toret = $e->selectAsignadas();
	if($toret == false){
		$msg = "No hay practicas asignadas a estudiantes en este momento.";
		header("Location: ../views/coordinador/listaDeAsignadas.php?msg=$msg");
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
		header("Location: ../views/coordinador/listaDeAsignadas.php?datos=$datos");
	}
}

function verSolicitantes3(){
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
		$msgx = "Se han realizado las asignaciones de practicas a estudiantes. Si desea asignar los tutores, acceda al panel de Asignacion de tutores.";
		header("Location: ../views/coordinador/asignacionEE.php?msg=$msg&msgx=$msgx");
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
		$msgx = "Se han asignado las practicas. Los alumnos que quedan por asignar han escogido practicas ya asignadas a otros mas prioritarios. Contacte con ellos para realizar la asignacion manual.";
		header("Location: ../views/coordinador/asignacionEE.php?datos=$datos&msgx=$msgx");
	}
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
		header("Location: ../views/coordinador/asignacionEE.php?datos=$datos&msgx=$message");
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
					$x = new PracticasTutorEstudiante();
					$bool = $x->selectByIdEs($idEstudiante);
					if($bool == false){
						$a= new PracticasTutorEstudiante();
						$a->set(-1,$idPractica,$idEmpresa,$idEstudiante);
						$boolean = $a->insert();
						if($boolean == false){
							$msg = "Error de insercion. Intentelo de nuevo";
							verSolicitantes2($msg);
						}else{
							$msg = "La practica ha sido asignada correctamente al estudiante especificado.";
							//verSolicitantes2($msg);//Asignada correctamente
							header("Location: ../views/coordinador/mensajeAsignacion.php?msg=$msg");
						}						
					}else{
						$msg = "El estudiante introducido ya tiene practica asignada. Consulte las practicas asignadas.";
						//verSolicitantes2($msg);
						header("Location: ../views/coordinador/mensajeAsignacion.php?msg=$msg");
					}							
				}else{
						$msg = "La practica ya ha sido asignada a otro estudiante anteriormente. Consulte las practicas asignadas.";
						//verSolicitantes2($msg);
						header("Location: ../views/coordinador/mensajeAsignacion.php?msg=$msg");						
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
	$correo->Subject = utf8_decode($asunto);

	//Contenido del mensaje
	$correo->IsHTML(false);
	$correo->Body = utf8_decode($mensaje);
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
	$destinatario = $_POST["destinatario"];
	$mensaje = $_POST["mensaje"];
	
	session_start();
	$login = $_SESSION["name"];
	$c = new Coordinador();
	$coordinador = $c->select($login);	
	$remitente = $coordinador[0]["email"];
	
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
			$msg = "La practica se ha eliminado correctamente.";
			ofertas2($msg);
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
				$msg = "La informacion de la practica $titulo ha sido actualizada.";
				ofertas2($msg);
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
		//Comprobamos que la practica no existe ya
		$p = new Practicas();
		$boolean = $p->select($titulo);
		if($boolean == false){
			$p = new Practicas();
			$p->set($titulo,$descripcion,$idEmpresa,$periodo,$titulacion,$inicio,$fin,$horario,$pformativo);
			$boolean = $p->insert();
			if($boolean == false){
				$msg = "Error al crear la practica. Intentelo de nuevo";
				header("Location: ../views/coordinador/ofertas.php?msg2=$msg");			
			}else{
				$msg = "La practica se ha publicado correctamente.";
				ofertas2($msg);
			}			
		}else{
			$msg = "Ya existe una practica con el titulo $titulo. Elija otro nombre para la practica";
			$array = array();
			$array["descripcion"] = $descripcion;
			$array["inicio"] = $inicio;
			$array["fin"] = $fin;
			$array["horario"] = $horario;
			$array["pformativo"] = $pformativo;
			$array["nombreEmpresa"] = $nEmpresa;
			$array["periodo"] = $periodo;
			$array["titulacion"] = $titulacion;
			ofertas3($msg,$array);
		}		
	}
}

function ofertas3($msgx,$array){
	$p = new Practicas();
	$boolean = $p->selectAll();
	$e = new Empresa();
	$empresas = $e->selectAll();
	if($empresas == false){
		$msg = "No se pueden crear practicas si no hay empresas dadas de alta en el sistema. Por favor, registre las empresas primero.";
		header("Location: ../views/coordinador/ofertasErr.php?msg=$msg");
	}else{
		if($boolean == false){
			$msg = "No hay practicas dadas de alta actualmente en el sistema.";
			$infoEmpresas = json_encode($empresas);
			header("Location: ../views/coordinador/ofertas.php?msg=$msg&infoEmpresas=$infoEmpresas");
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
			$infoEmpresas = json_encode($empresas);
			$datos = json_encode($boolean);
			$infoFormulario = json_encode($array);
			header("Location: ../views/coordinador/ofertas.php?datos=$datos&infoEmpresas=$infoEmpresas&msg2=$msgx&infoFormulario=$infoFormulario");
		}	
	}
}

function ofertas2($msgx){
	$p = new Practicas();
	$boolean = $p->selectAll();
	$e = new Empresa();
	$empresas = $e->selectAll();
	if($empresas == false){
		$msg = "No se pueden crear practicas si no hay empresas dadas de alta en el sistema. Por favor, registre las empresas primero.";
		header("Location: ../views/coordinador/ofertasErr.php?msg=$msg");
	}else{
		if($boolean == false){
			$msg = "No hay practicas dadas de alta actualmente en el sistema.";
			$infoEmpresas = json_encode($empresas);
			header("Location: ../views/coordinador/ofertas.php?msg=$msg&infoEmpresas=$infoEmpresas&msg2=$msgx");
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
			$infoEmpresas = json_encode($empresas);
			$datos = json_encode($boolean);
			header("Location: ../views/coordinador/ofertas.php?datos=$datos&infoEmpresas=$infoEmpresas&msg2=$msgx");
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
	$msg="Evento borrado.";
	listarEventos2($msg);
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
		$msg = "Se ha creado el evento.";
		listarEventos2($msg);
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

function listarEventos2($msgx){
	$e = new Eventos();
	$boolean = $e->selectAll();
	if($boolean == false){
		$msg = "No hay ningún evento actualmente.";
		header("Location: ../views/coordinador/calendario.php?msg=$msgx");
	}else{
		$datos = serialize($boolean);
		header("Location: ../views/coordinador/calendario.php?datos=$datos&msg=$msgx");
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