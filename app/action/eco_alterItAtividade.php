<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

//15 - Permissao para alterar andamento
$cdPermissao 			= 15;
$autorizado 			= cPermissao::validarPermissao($cdPermissao);

if (!$autorizado) {
	exit();
}

$cdItAtividade 	= (isset($_POST['cdItAtividade'])) 	? $_POST['cdItAtividade']											: NULL;
$dtProtocolo 	= (isset($_POST['dtProtocolo'])) 	? implode("-",array_reverse(explode("/",$_POST['dtProtocolo']))) 	: NULL;
$dtPrazo 			= isset($_POST['dtPrazo']) 			? implode("-",array_reverse(explode("/",$_POST['dtPrazo']))) : NULL;
$dsAndamento 	= (isset($_POST['dsAndamento'])) 	? $_POST['dsAndamento'] 											: NULL;
$cdResponsavel 	= (isset($_POST['cdResponsavel'])) 	? base64_decode($_POST['cdResponsavel']) 											: NULL;
$cdCliente 		= (isset($_POST['cdCliente'])) 	? base64_decode($_POST['cdCliente']) 											: NULL;
$cdOrgaoLicenciador 	= (isset($_POST['cdOrgaoLicenciador'])) 	? base64_decode($_POST['cdOrgaoLicenciador']) 											: NULL;

$notificacao 	= new Notificacao;
$usuarios 		= new cUsuario;
$atv 			= new cAtividade;

$atv->setCdItAtividade($cdItAtividade);
$atv->setDtProtocolo($dtProtocolo);
$atv->setDtPrazo($dtPrazo);
$atv->setDsAndamento($dsAndamento);
$atv->setCdResponsavel($cdResponsavel);
$atv->setCdCliente($cdCliente);
$atv->setCdOrgaoLicenciador($cdOrgaoLicenciador);

$snAlter 		= $atv->AlterarItAtividade();

switch (gettype($snAlter)) {
	case 'boolean':
	$notificacao->viewSwalNotificacao("Sucesso!", "Andamento atualizado com sucesso.", "single", "success");
	break;

	case 'string':
	$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao atualizar o andamento. Por favor, contate o administrador do sistema. ´[".$snCadPorte."] ", "single", "error");
	break;

	default:
	$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
	break;
}

?>