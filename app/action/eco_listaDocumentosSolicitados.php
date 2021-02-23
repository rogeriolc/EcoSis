<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();


$cdServico = (isset($_POST['cdServico'])) ? base64_decode($_POST['cdServico']) : null;

if (is_null($cdServico)) {
	$notificacao->viewSwalNotificacao("Essa não!", "Não foi possível obter a lista de documentos solicitados. Por favor entre contato com o administrador do sistema", "single", "error");
}

$serv = new cServico($cdServico);

$serv->listarTableDocumentosSolicitados();
?>