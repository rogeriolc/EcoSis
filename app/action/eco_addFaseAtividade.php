<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();
$atv  = new cAtividade;

$cdFase 		= isset($_POST['cdFase']) ? base64_decode($_POST['cdFase']) : null;
$cdAtividade 	= isset($_POST['cdAtividade']) ? base64_decode($_POST['cdAtividade']) : null;

$atv->setCdAtividade($cdAtividade);

$atv->addFaseAtividadeServico($cdFase);

?>