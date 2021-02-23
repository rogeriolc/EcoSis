<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

echo $cdItLicencaFase 	= isset($_POST['cdItLicencaFase']) ? base64_decode($_POST['cdItLicencaFase']) : null;
$dsFase 				= $_POST['dsFase'];
$dtPrevEntrega 			= implode("-",array_reverse(explode("/",$_POST['dtPrevEntrega'])));
$cdResponsavel 			= $_POST['cdResponsavel'];

if (is_null($cdItLicencaFase)) {



}else{

	$lic = new cLicencaAmbiental;
	$lic->setCdItLicencaFase($cdItLicencaFase);
	$lic->setDsFase($dsFase);
	$lic->setDtPrevEntrega($dtPrevEntrega);
	$lic->setCdResponsavel($cdResponsavel);

	$snCadastro = $lic->salvarFaseObjeto();

	$snAddResp  = $lic->addResponsavelFaseObjeto();

	$notificacao = new Notificacao;

	if($snCadastro == true && $snAddResp == true){

		$notificacao->viewSwalNotificacao("Sucesso!", "Fase salva com sucesso!", "single", "success");

	}else if(!$snCadastro){

		$notificacao->viewSwalNotificacao("Informativo!", "Não há nada para alterar", "single", "info");

	}else if(!$snAddResp){

		$notificacao->viewSwalNotificacao("Informativo!", "Não há nada para alterar", "single", "info");

	}else{
		$notificacao->viewSwalNotificacao("Erro!", "Erro inesperado, contate o adminstrador do sistema.", "single", "error");
	}

}
?>