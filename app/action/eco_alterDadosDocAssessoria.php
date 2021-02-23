<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdDocAssessoria 	= $_POST['cdDocAssessoria'];
$valor 				= $_POST['value'];
$nmColuna 			= $_POST['coluna'];

if ($nmColuna == 'dt_emissao' || $nmColuna == 'dt_validade') {
	$valor = implode("-",array_reverse(explode("/",$valor)));
}

$upd = cAtividade::updateProdutosAssessoria($cdDocAssessoria, $nmColuna, $valor);

if ($upd == 0) {
	$notificacao 	= new Notificacao();
	$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao atualizar os dados do produto. Por favor, contate o administrador do sistema.", "single", "error");
}