<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$notificacao 	= new Notificacao;

$cdServico 		= isset($_POST['cdServico']) ? base64_decode($_POST['cdServico']) : null;
$cdTpDocumento 	= isset($_POST['cdTpDocumento']) ? base64_decode($_POST['cdTpDocumento']) : null;
$cdAtividade 	= isset($_POST['cdAtividade']) ? base64_decode($_POST['cdAtividade']) : null;

if (is_null($cdServico) || is_null($cdTpDocumento) || empty($cdServico) || empty($cdTpDocumento)) {
	$notificacao->viewSwalNotificacao("Erro!", "Parâmentros incorretos. Contate o administrador do sistema.", "single","error");
	exit();
}

$serv 			= new cServico($cdServico);
$snCadSolDoc  	= $serv->solicitarDocumento($cdTpDocumento, $cdAtividade);

switch ($snCadSolDoc) {

	case $snCadSolDoc > 0:

	$notificacao->viewSwalNotificacao("Sucesso!", "Documento solicitado com sucesso.", "single", "success");

	break;

	case 'N':
	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um abastecimento com este nome. Por favor, escolha outro.", "single", "warning");
	break;

	case 'E':
	$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao solicitar documento. Por favor, contate o administrador do sistema.", "single", "error");
	break;

	default:
	$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
	break;
}
