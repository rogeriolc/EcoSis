<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$cdCliente = isset($_POST['cdCliente']) ? base64_decode($_POST['cdCliente']) : null;
$cdEmpreendimento = isset($_POST['cdEmpreendimento']) ? base64_decode($_POST['cdEmpreendimento']) : null;

$empre = new cEmpreendimento();

if(!is_null($cdCliente)){
	$empre->setCdCliente($cdCliente);
	// echo $empre->getCdCliente();
	$empre->listOption($cdEmpreendimento);
}

?>