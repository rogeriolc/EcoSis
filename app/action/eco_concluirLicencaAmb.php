<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdLicencaAmbiental 	= isset($_POST['cdLicencaAmbiental'])  ? $_POST['cdLicencaAmbiental']  : null;

$lic = new cLicencaAmbiental;
$lic->setCdLicencaAmbiental($cdLicencaAmbiental);

$snLicencaConcluida = $lic->Concluir();

$notificacao = new Notificacao;

switch (gettype($snLicencaConcluida)) {

	case 'string':
	$notificacao->viewSwalNotificacao("Erro!", "Erro inesperado, contate o adminstrador do sistema. [".$snLicencaConcluida."]", "single", "error");
	break;

	case 'boolean':


	if($snLicencaConcluida){

		$notificacao->viewSwalNotificacao("Sucesso!", "Licença concluída com sucesso!", "single", "success");

	}else if(!$snLicencaConcluida){

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