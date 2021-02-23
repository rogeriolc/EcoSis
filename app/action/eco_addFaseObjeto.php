<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

echo $cdItLicencaAmbiental 	= (isset($_POST['cdItLicencaAmbiental'])) ? $_POST['cdItLicencaAmbiental'] : null;
$cdFase 				= (isset($_POST['cdFase'])) ? $_POST['cdFase'] : null;



if(is_null($cdItLicencaAmbiental) || is_null($cdFase)){

	$notificacao = new Notificacao;

	$notificacao->viewSwalNotificacao("Essa não!", "Não foi possível adicionar a fase [PARAMETROS INCORRETOS]. Por favor entre contato com o administrador do sistema", "single", "error");

}else{


	foreach ($cdFase as $fase) {
		$cFase = base64_decode($fase);

		$lic = new cLicencaAmbiental();
		$lic->addFaseObjetoLicenca($cdItLicencaAmbiental, $cFase);
	}

}