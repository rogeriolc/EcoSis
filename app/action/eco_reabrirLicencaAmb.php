<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdLicencaAmbiental 	= isset($_POST['cdLicencaAmbiental'])  ? $_POST['cdLicencaAmbiental']  : null;
$dsMotivoReabertura 	= isset($_GET['dsMotivoReabertura'])   ? $_GET['dsMotivoReabertura'] : null;

$lic = new cLicencaAmbiental;
$lic->setCdLicencaAmbiental($cdLicencaAmbiental);

$snLicencaReaberta = $lic->Reabrir($dsMotivoReabertura);

$notificacao = new Notificacao;

switch (gettype($snLicencaReaberta)) {

	case 'string':
	$notificacao->viewSwalNotificacao("Erro!", "Erro inesperado, contate o adminstrador do sistema. [".$snLicencaReaberta."]", "single", "error");
	break;

	case 'boolean':


	if($snLicencaReaberta){

		$notificacao->viewSwalNotificacao("Sucesso!", "Licença reaberta com sucesso!", "single", "success");

	}else if(!$snLicencaReaberta){

		$notificacao->viewSwalNotificacao("Informativo!", "Não há nada para alterar", "single", "info");

	}else{

		$notificacao->viewSwalNotificacao("Erro!", "Erro inesperado, contate o adminstrador do sistema.", "single", "error");

	}
	break;

	default:
	$notificacao->viewSwalNotificacao("Erro!", "Erro inesperado, contate o adminstrador do sistema.", "single", "error");
	break;
}

?>