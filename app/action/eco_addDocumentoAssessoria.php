<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdItAtividade 	= isset($_POST['cdItAtividade']) ? base64_decode($_POST['cdItAtividade']) : null;
$dsDocumento 	= null;
$dtEmissao 		= null;
$dtValidade 	= null;
$dsAnexo 		= isset($_FILES['dsAnexo']) ? $_FILES['dsAnexo'] : null;

if (is_null($dsAnexo)) {
	echo "voce precisa anexar um arquivo.";
	exit();
}

$cdDocAssessoria = cAtividade::cadastrarProdutoAssessoria($cdItAtividade, $dsDocumento, $dtEmissao, $dtValidade, $dsAnexo);
