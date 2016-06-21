<?php
session_start();
if($_SESSION["validated"] != "Coordinador"){
header("Location: ../mainRAC/acceso.php");
}
require_once("../../layouts/coordinador/ofertasErr.html");
?>