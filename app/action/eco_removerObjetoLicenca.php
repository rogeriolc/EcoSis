<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$notificacao = new Notificacao;

$cdItLicencaAmbiental 	= base64_decode($_POST['cdItLicencaAmbiental']);
$objPosition 			= $_POST['objPosition'];

$lic = new cLicencaAmbiental;
$lic->setCdItLicencaAmbiental($cdItLicencaAmbiental);

$snRemove = $lic->removerObjetoLicenca();

switch (gettype($snRemove)) {

	case 'boolean':

	if($snRemove){
		$notificacao->viewSwalNotificacao("Sucesso!", "Objeto e suas fases foram removidos!", "single", "success");
		echo '
		<script>$("#panel'.md5($cdItLicencaAmbiental).'").addClass("animated bounceOutLeft");</script>
		';
	}else{
		$notificacao->viewSwalNotificacao("Informativo!", "Nada para alterar", "single", "info");
	}
	break;

	case 'string':
	$notificacao->viewSwalNotificacao("Essa não!", "Não foi possível remover o objeto [$snRemove]. Por favor entre contato com o administrador do sistema", "single", "error");
	break;

	default:
		# code...
	break;
}