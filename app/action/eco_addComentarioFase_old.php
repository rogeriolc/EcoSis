<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdItLicencaFase = $_POST['cdItLicencaFase'];
$dsComentario 	 = $_POST['dsComentario'];

if(empty($cdItLicencaFase)){
	$notificacao = new Notificacao;
	$notificacao->viewSwalNotificacao("Erro!", "Parâmentro de fase incorreto. Contate o administrador do sistema.", "single","error");
}if(empty($dsComentario)){
	$notificacao = new Notificacao;
	$notificacao->viewSwalNotificacao("Atenção!", "Você não precisa digitar um comentário.", "single", "warning");
}else{
	$lic = new cLicencaAmbiental;
	$lic->setCdItLicencaFase($cdItLicencaFase);
	$lic->setDsComentario($dsComentario);

	$lic->addComentarioFaseObjeto();
}