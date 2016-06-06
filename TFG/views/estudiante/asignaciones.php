<?php
session_start();
if($_SESSION["validated"] != "Estudiante"){
header("Location: ../mainRAC/acceso.php");
}
require_once("../../layouts/estudiante/asignaciones.html");
?>