<?php
session_start();
if($_SESSION["validated"] != "Empresa"){
header("Location: ../mainRAC/acceso.php");
}
require_once("../../layouts/empresa/gestionOfertas.html");
?>