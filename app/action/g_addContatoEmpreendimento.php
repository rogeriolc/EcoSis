<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdEmpreendimento 	= isset($_POST['cdEmpreendimento']) ? $_POST['cdEmpreendimento'] : null;
$nmContato 			= $_POST['nmContato'];
$nrTelefone 		= $_POST['nrTelefone'];
$nmCargo 			= $_POST['nmCargo'];
$nmDepartamento 	= $_POST['nmDepartamento'];

if (is_null($cdEmpreendimento)) {
	$notificacao->viewSwalNotificacao("Erro!", "O código do empreendimento está inválido.", "single", "error");
	exit();
}

$empreendimento 	= new cEmpreendimento($cdEmpreendimento);
$cadastrado 		= $empreendimento->addContato($nmContato, $nrTelefone, $nmCargo, $nmDepartamento);

var_dump($cadastrado);