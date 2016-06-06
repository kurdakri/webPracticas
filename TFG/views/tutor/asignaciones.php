<?php
session_start();
if($_SESSION["validated"] != "Tutor"){
header("Location: ../mainRAC/acceso.php");
}
require_once("../../layouts/tutor/asignaciones.html");
?>