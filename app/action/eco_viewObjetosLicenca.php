<?php
session_start();

include '../conf/showErros.php';
include '../conf/autoLoad.php';

$cdLicencaAmbiental = $_POST['cdLicencaAmbiental'];

$lic->setCdLicencaAmbiental();
$lic = new cLicencaAmbiental;
$lic->listObjeto();
?>