<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdAtividadeFase 	= $_POST['cdAtividadeFase'];
$status 			= $_POST['status'];

$notificacao 		= new Notificacao;

if(empty($cdAtividadeFase) || is_null($cdAtividadeFase)){
	$notificacao->viewSwalNotificacao("Erro!", "Parâmentro de fase incorreto. Contate o administrador do sistema.", "single","error");
	exit();
}


if ($status == 'true') {

	$concluiMudanca = cAtividade::concluirAtividadeFase($cdAtividadeFase);

} elseif($status == 'false') {

	$concluiMudanca = cAtividade::reabrirAtividadeFase($cdAtividadeFase);

} else {

	$notificacao->viewSwalNotificacao("Erro!", "Parâmentro de fase incorreto. Contate o administrador do sistema.", "single","error");
	exit();

}

switch (gettype($concluiMudanca)) {
	case 'integer':

	break;

	case 'boolean':
	$notificacao->viewSwalNotificacao("Erro", "Não foi possível cadastrar a atividade. Por favor, contate o administrador do sistema.", "single", "error");
	break;

	case 'boolean':
	case 'string':
	$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cadastrar a atividade. Por favor, contate o administrador do sistema. ´[".$concluiMudanca."] ", "single", "error");
	break;

	default:
	$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
	break;
}