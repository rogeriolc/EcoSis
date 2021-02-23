<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$notificacao 			= new Notificacao();

$cdProposta 			= isset($_POST['cdPropostaLicenca']) ? base64_decode($_POST['cdPropostaLicenca']) : null;

if (is_null($cdProposta)) {
	$notificacao->viewSwalNotificacao("Erro!", "Parâmentros para cancelamento incorretos", "single", "error");
	exit();
}

$proposta 				= new cPropostaLicencaAmb($cdProposta);
$dadosProposta			= $proposta->Dados();

$propostaCancelada 		= $proposta->cancelarProposta();

if (!$propostaCancelada) {
	$notificacao->viewSwalNotificacao("Erro!", "Não foi possivel cancelar a proposta. Contate o administrador do sistema.", "single", "error");
	exit();
}

$cdServico				= $proposta->getServico($cdProposta);
$cdServico				= $cdServico[0]->cd_servico;

if ($cdServico > 0) {
	$dadosItensProposta		= $proposta->DadosItensProposta();

	$servico 				= new cServico($cdServico);
	$dadosServico 			= $servico->Dados();

	$servicoCancelado 		= $servico->Suspender();

	$notificacao->viewSwalNotificacao("Sucesso!", "A proposta foi cancelada.", "single", "success");
} else {
	$notificacao->viewSwalNotificacao("Erro!", "Não foi possivel cancelar o serviço ligado a proposta. Contate o administrador do sistema.", "error");
	exit();
}