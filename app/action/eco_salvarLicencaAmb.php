<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdLicencaAmbiental = isset($_POST['cdLicencaAmbiental']) ? $_POST['cdLicencaAmbiental'] : null;
$cdCliente 			= isset($_POST['cdCliente']) ? base64_decode($_POST['cdCliente']) : null;
$cdEmpreendimento 	= isset($_POST['cdEmpreendimento']) ? base64_decode($_POST['cdEmpreendimento']) : null;
$nrProcesso 		= isset($_POST['nrProcesso']) ? $_POST['nrProcesso'] : null;
$cdOrgaoLicenciado 	= isset($_POST['cdOrgaoLicenciado']) ? base64_decode($_POST['cdOrgaoLicenciado']) : null;

$lic = new cLicencaAmbiental;
$lic->setCdLicencaAmbiental($cdLicencaAmbiental);
$lic->setCdCliente($cdCliente);
$lic->setCdEmpreendimento($cdEmpreendimento);
$lic->setNrProcesso($nrProcesso);
$lic->setCdOrgaoLicenciado($cdOrgaoLicenciado);

$snLicencaAlterada = $lic->Alterar();

$notificacao = new Notificacao;

switch (gettype($snLicencaAlterada)) {
	case 'string':
	$notificacao->viewSwalNotificacao("Erro!", "Erro inesperado, contate o adminstrador do sistema. [".$snLicencaAlterada."]", "single", "error");
	break;

	case 'boolean':


	if($snLicencaAlterada){

		$notificacao->viewSwalNotificacao("Sucesso!", "Licença salva com sucesso!", "single", "success");

	}else if(!$snLicencaAlterada){

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