<?php
session_start();

header("Access-Control-Allow-Origin: * ");
header("allow-control-access-origin: * ");
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdServico = (isset($_POST['cdServico'])) ? ($_POST['cdServico']) : null;

if (is_null($cdServico)) {
	$notificacao->viewSwalNotificacao("Essa não!", "Não foi possível obter a lista de documentos solicitados. Por favor entre contato com o administrador do sistema", "single", "error");
}

$documentos = cServico::getDocumentosServico(null, $cdServico);

echo json_encode($documentos);

?>