<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$notificacao 			= new Notificacao();

$cdEmpresa 				= $_SESSION['cdEmpresa'];

$cdPropostaLicenca 		= base64_decode($_POST['cdPropostaLicenca']);
$cdCliente 				= base64_decode($_POST['cdCliente']);
$cdEmpreendimento 		= base64_decode($_POST['cdEmpreendimento']);
// $cdTpLicencaAmbiental	= base64_decode($_POST['cdTpLicenca']);
$dtPrevConclusaoLicenca = implode("-",array_reverse(explode("/",$_POST['dtPrevConclusaoLicenca'])));
$dsObservacao 			= $_POST['dsObservacao'];

//instancia o objeto
$prop = new cPropostaLicencaAmb($cdPropostaLicenca, $cdCliente, $cdEmpreendimento, null, null, null, $dtPrevConclusaoLicenca, $dsObservacao);

// var_dump($prop);

$atualizado = $prop->AlterarSimples();

// var_dump($atualizado);

switch (gettype($atualizado)) {
	case 'string':
// 	if ($atualizado == 'S') {
		$notificacao->viewSwalNotificacao("Sucesso!", "Proposta salva com sucesso!", "single", "success");
// 	} else {
// 		$notificacao->viewSwalNotificacao("Informativo", "Nada para alterar.", "single", "info");
// 	}
	break;

	default:
	$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminstrador do sistema.", "single", "error");
	break;
}