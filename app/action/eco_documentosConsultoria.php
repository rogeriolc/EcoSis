<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdServico = (isset($_POST['cdServico'])) ? $_POST['cdServico'] : null;

if (is_null($cdServico)) {
	exit();
}

$docs = cAtividade::listarDocConsultoria($cdServico);

header('Content-Type: application/json');

echo json_encode($docs);
?>