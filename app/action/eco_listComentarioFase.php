<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdAtividadeFase = isset($_POST['cdAtividadeFase']) ? base64_decode($_POST['cdAtividadeFase']) : null;

$notificacao 	 = new Notificacao;

if(empty($cdAtividadeFase) || is_null($cdAtividadeFase)){
	$notificacao->viewSwalNotificacao("Erro!", "Parâmentro de fase incorreto. Contate o administrador do sistema.", "single","error");
	exit();
}

cAtividade::listComentarioFase($cdAtividadeFase);