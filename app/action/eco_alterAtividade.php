<?php
session_start();

include '../conf/autoLoad.php';
// include '../conf/showErros.php';

cSeguranca::validaSessao();

//10 - Permissao para alterar dados dos serviço
$cdPermissao 			= 10;
$autorizado 			= cPermissao::validarPermissao($cdPermissao);

if (!$autorizado) {
	exit();
}

$cdServico 			= (isset($_POST['cdServico'])) 		? $_POST['cdServico'] 												: null;
$cdAtividade 		= (isset($_POST['cdAtividade'])) 	? $_POST['cdAtividade'] 											: null;
$tpAtividade 		= 'C';
$cdTpAtividade 		= (isset($_POST['cdTpAtividade'])) 	? base64_decode($_POST['cdTpAtividade'])							: null;
// $tpAtividade 	= (isset($_POST['tpAtividade'])) 	? $_POST['tpAtividade'] 	: null;
$cdOrgaoLicenciado 	= (isset($_POST['cdOrgaoLicenciado'])) ? base64_decode($_POST['cdOrgaoLicenciado']) : null;
$nrProcesso 		= (isset($_POST['nrProcesso'])) ? $_POST['nrProcesso'] : null;
$nrOrdem 			= (isset($_POST['nrOrdem'])) 		? $_POST['nrOrdem'] 												: null;
$dsAtividade 		= (isset($_POST['dsAtividade'])) 	? $_POST['dsAtividade'] 											: null;
$cdUsuarioResponsavel = (isset($_POST['cdUsuarioResponsavel'])) 		? base64_decode($_POST['cdUsuarioResponsavel']) 	: null;
$dtPrevEntrega 		= (isset($_POST['dtPrevEntrega'])) 	? implode("-",array_reverse(explode("/",$_POST['dtPrevEntrega']))) 	: null;
$dsJustificativa 	= (isset($_POST['dsJustificativa'])) 	? $_POST['dsJustificativa'] 									: null;

$atvAnterior 	= new cAtividade($cdAtividade);
$atvAnterior->Dados();

$dtAnterior 	= $atvAnterior->getDtPrevEntrega();

$atv 			= new cAtividade($cdAtividade, $cdServico, $dsAtividade, $tpAtividade, $cdUsuarioResponsavel, $dtPrevEntrega, $cdTpAtividade, $nrOrdem);
$atv->setCdOrgaoLicenciador($cdOrgaoLicenciado);
$atv->setNrProcesso($nrProcesso);
$snAlt 			= $atv->Alterar();

// var_dump($_SESSION);

$notificacao 	= new Notificacao();

switch (gettype($snAlt)) {
	case 'integer':
	$notificacao->viewSwalNotificacao("Sucesso!", "Atividade alterada com sucesso.", "single", "success");

	if((!is_null($dsJustificativa) && !empty($dsJustificativa)) && $dtAnterior != $dtPrevEntrega) {
		// echo "ishiduashd";
		$hist = $atv->setHistoricoAlteracaoData($dtAnterior, $dtPrevEntrega, $dsJustificativa);
	}

	break;

	case 'string':
	$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cadastrar o porte do empreendimento. Por favor, contate o administrador do sistema. ´[".$snCadPorte."] ", "single", "error");
	break;

	default:
	$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
	break;
}
?>