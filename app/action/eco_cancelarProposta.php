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

$proposta 				= new cProposta($cdProposta);
$dadosProposta			= $proposta->getData($cdProposta);

if (count($dadosProposta) > 0) {
	$propostaCancelada 		= $proposta->cancelarProposta();
	
	if (!$propostaCancelada) {
		$notificacao->viewSwalNotificacao("Erro!", "Não foi possivel cancelar a proposta. Contate o administrador do sistema.", "single", "error");
		exit();
	} else {
		$servicosSuspensos = cProposta::suspenderServicos($cdProposta);
		$notificacao->viewSwalNotificacao("Sucesso!", "A proposta foi cancelada.", "single", "success");
	}
}