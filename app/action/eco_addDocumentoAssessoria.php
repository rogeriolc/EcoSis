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

function tirarAcentos($string){

	return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);

}

$nmArquivo  = basename($_FILES['dsAnexo']['name']);

$cdDocAssessoria = cAtividade::cadastrarProdutoAssessoria($cdItAtividade, $dsDocumento, $dtEmissao, $dtValidade, $nmArquivo);

var_dump($cdDocAssessoria);

if ($cdDocAssessoria > 0) {

	$ds 			= DIRECTORY_SEPARATOR;

	// Nas versões do PHP que antecedem a versão 4.1.0, é preciso usar o $HTTP_POST_FILES em vez do $_FILESS.
	$uploaddir  	= "..".$ds."repo".$ds."eco".$ds."assessoria".$ds.$cdItAtividade.$ds.$cdDocAssessoria.$ds;

	if(!is_dir($uploaddir)){
		mkdir($uploaddir);
	}


	$uploadfile = $uploaddir . $nmArquivo;

	if (move_uploaded_file($_FILES['dsAnexo']['tmp_name'], $uploadfile)) {
		echo "O arquivo é valido e foi carregado com sucesso.\n";
	} else {
		echo "Algo está errado aqui!\n";
	}

}

?>