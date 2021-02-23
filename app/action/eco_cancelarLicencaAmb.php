<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdLicencaAmbiental 	= isset($_POST['cdLicencaAmbiental'])  ? $_POST['cdLicencaAmbiental']  : null;
$dsMotivoCancelamento 	= isset($_GET['dsMotivoCancelamento']) ? $_GET['dsMotivoCancelamento'] : null;

$lic = new cLicencaAmbiental;
$lic->setCdLicencaAmbiental($cdLicencaAmbiental);

$snLicencaCancelada = $lic->Cancelar($dsMotivoCancelamento);

$notificacao = new Notificacao;

switch (gettype($snLicencaCancelada)) {
	case 'string':
	$notificacao->viewSwalNotificacao("Erro!", "Erro inesperado, contate o adminstrador do sistema. [".$snLicencaCancelada."]", "single", "error");
	break;

	case 'boolean':


	if($snLicencaCancelada){

		$notificacao->viewSwalNotificacao("Sucesso!", "Licença cancelada com sucesso!", "single", "success");

	}else if(!$snLicencaCancelada){

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