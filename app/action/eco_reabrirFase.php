<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$notificacao = new Notificacao;

$cdItLicencaFase = base64_decode($_POST['cdItLicencaFase']);

$lic = new cLicencaAmbiental;
$lic->setCdItLicencaFase($cdItLicencaFase);

$cdFase = $lic->returnCdFase();

$lic->alterarStatusFase($status);

$lic->movFaseLicenca($cdFase, "FASE REABERTA");