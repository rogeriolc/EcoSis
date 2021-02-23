<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdAtividadeFaseComentario 	= $_POST['cdAtividadeFaseComentario'];
$dsAnexo 					= $_POST['dsAnexo'];

$notificacao 	 			= new Notificacao;

$anexado = cAtividade::publicarDocConsultoria($cdAtividadeFaseComentario, $dsAnexo);

switch (gettype($anexado)) {
	case 'integer':
	$notificacao->viewSwalNotificacao("Sucesso!", "O documento foi publicado com sucesso!", "single", "success");
	break;

	case 'boolean':
	$notificacao->viewSwalNotificacao("Erro", "Não foi possível cadastrar a atividade. Por favor, contate o administrador do sistema.", "single", "error");
	break;

	case 'boolean':
	case 'string':
	$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cadastrar a atividade. Por favor, contate o administrador do sistema. ´[".$snCadComentario."] ", "single", "error");
	break;

	default:
	$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
	break;
}