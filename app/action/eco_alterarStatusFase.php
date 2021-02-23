<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdItLicencaFase = (isset($_POST['cdItLicencaFase'])) ? base64_decode($_POST['cdItLicencaFase']) : base64_decode($_GET['cdItLicencaFase']);
$status 	 	 = (isset($_POST['status'])) 	  	  ? $_POST['status'] : null;

$notificacao = new Notificacao;

if(is_null($cdItLicencaFase) || is_null($status)){

	$notificacao->viewSwalNotificacao("Essa não!", "Não foi possível obter a lista de fase [PARAMETROS INCORRETOS]. Por favor entre contato com o administrador do sistema", "single", "error");

}else{

	$lic = new cLicencaAmbiental();
	$lic->setCdItLicencaFase($cdItLicencaFase);
	$snAlteraFase = $lic->alterarStatusFase($status);

	switch (gettype($snAlteraFase)) {
		case 'boolean':

			if($snAlteraFase){
				$notificacao->viewSwalNotificacao("Sucesso!", "Status da fase alterado com sucesso!", "single", "success");
			}else{
				$notificacao->viewSwalNotificacao("Informativo!", "Nada para alterar", "single", "info");
			}

			break;

			case 'string':
				$notificacao->viewSwalNotificacao("Essa não!", "Não foi possível alterar o status da fase [$snAlteraFase]. Por favor entre contato com o administrador do sistema", "single", "error");
			break;

		default:
			# code...
			break;
	}

}