<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$notificacao 	= new Notificacao();

$cdServico 		= (isset($_POST['cdServico'])) 		? $_POST['cdServico'] 												: null;
// $cdAtividade 	= (isset($_POST['cdAtividade'])) 	? $_POST['cdAtividade'] 	: null;
// $tpAtividade 	= 'C';
$cdTpAtividade 	= (isset($_POST['cdTpAtividade'])) 	? base64_decode($_POST['cdTpAtividade'])							: null;
$tpAtividade 	= (isset($_GET['tpAtividade'])) 	? $_GET['tpAtividade'] 												: null;
$dsAtividade 	= (isset($_POST['dsAtividade'])) 	? $_POST['dsAtividade'] 											: null;
$cdUsuario 		= (isset($_POST['cdUsuario'])) 		? base64_decode($_POST['cdUsuario']) 								: null;
$dtPrevEntrega 	= (isset($_POST['dtPrevEntrega'])) 	? implode("-",array_reverse(explode("/",$_POST['dtPrevEntrega']))) 	: null;

if(is_null($cdServico) || empty($cdServico) || !isset($cdServico)) {
	$notificacao->viewSwalNotificacao("Atenção", "Salve os dados do serviço antes de incluir uma atividade.", "single", "warning");
	exit();
}

if($tpAtividade != 'A' && $tpAtividade != 'C') {
	$notificacao->viewSwalNotificacao("Erro", "Não foi possível cadastrar a atividade pois o seu tipo não foi definido. Por favor, contate o administrador do sistema.", "single", "error");
	exit();
}

$atv 			= new cAtividade(null, $cdServico, $dsAtividade, $tpAtividade, $cdUsuario, $dtPrevEntrega, $cdTpAtividade);
$snCad 			= $atv->Cadastrar();

switch (gettype($snCad)) {
	case 'integer':
	$notificacao->viewSwalNotificacao("Sucesso!", "Atividade cadastrada com sucesso.", "single", "success");
	$atv->setCdAtividade($snCad);
	$atv->addFaseAtividade();
	break;

	case 'boolean':
	$notificacao->viewSwalNotificacao("Erro", "Não foi possível cadastrar a atividade. Por favor, contate o administrador do sistema.", "single", "error");
	break;

	case 'boolean':
	case 'string':
	$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cadastrar a atividade. Por favor, contate o administrador do sistema. ´[".$snCadPorte."] ", "single", "error");
	break;

	default:
	$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
	break;
}
?>