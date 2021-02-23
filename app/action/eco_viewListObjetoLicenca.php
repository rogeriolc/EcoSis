<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdCatObjetoLicenca = (isset($_POST['cdCatObjetoLicenca'])) ? $_POST['cdCatObjetoLicenca'] : $_GET['cdCatObjetoLicenca'];
$cdItLicencaFase 	= (isset($_POST['cdItLicencaFase'])) ? base64_decode($_POST['cdItLicencaFase']) : base64_decode($_GET['cdItLicencaFase']);

$lic = new cLicencaAmbiental;
$cdItLicencaAmbiental   = $lic->returnCdItLicenca($cdItLicencaFase);
$cdLicencaAmbiental 	= $lic->returnCdLicencaAmbiental($cdItLicencaAmbiental);
$lic->setCdLicencaAmbiental($cdLicencaAmbiental);
$lic->listObjetoLicenca($cdCatObjetoLicenca, $cdItLicencaFase);