<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

//1 - Permissao para cadastros simples
// $cdPermissao 		 = 1;
// cPermissao::validarPermissao($cdPermissao);

$cdPropostaLicenca = base64_decode(base64_decode(base64_decode($_POST['cdPropostaLicenca'])));

$proposta = new cPropostaLicencaAmb($cdPropostaLicenca);
$proposta->Dados();

$cdProposta = (!is_null($proposta->cdPropostaPai)) ? $proposta->cdPropostaPai : $cdPropostaLicenca;

$proposta->setCdPropostaLicenca($cdProposta);

$removido = $proposta->Remover();

if ($removido > 0) {
	$notificacao = new Notificacao;
	$notificacao->viewSwalNotificacao("Sucesso!", "Proposta removida", "single", "success");
} else {
	$notificacao = new Notificacao;
	$notificacao->viewSwalNotificacao("Erro", "Ocorreu um problema ao remover a proposta. Contate o administrador do sistema.", "single", "error");
}