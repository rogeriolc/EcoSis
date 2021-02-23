<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdAtividadeFase = $_POST['cdAtividadeFase'];

if(empty($cdAtividadeFase) || is_null($cdAtividadeFase)){
	$notificacao->viewSwalNotificacao("Erro!", "Parâmentro de fase incorreto. Contate o administrador do sistema.", "single","error");
	exit();
}

$atv  = new cAtividade;
$notificacao = new Notificacao;

$snConcluiFase = $atv->concluirAtividadeFase($cdAtividadeFase);

switch (gettype($snConcluiFase)) {
	case 'integer':
	$notificacao->viewSwalNotificacao("Sucesso!", "Fase concluída com sucesso!", "single", "success");
	break;

	case 'boolean':
	$notificacao->viewSwalNotificacao("Erro", "Não foi possível cadastrar a atividade. Por favor, contate o administrador do sistema.", "single", "error");
	break;

	case 'boolean':
	case 'string':
	$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cadastrar a atividade. Por favor, contate o administrador do sistema. ´[".$snConcluiFase."] ", "single", "error");
	break;

	default:
	$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
	break;
}