<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$mysql 		= MysqlConexao::getInstance();

$cdUsuario		= $_SESSION['cdUsuario'];
$nmUsuario 		= $_SESSION['nmUsuario'];
// $cdAtividade 	= $_POST['cdAtividade'];
$cdItAtividade 	= $_POST['cdItAtividade'];

$ds = DIRECTORY_SEPARATOR;

// Nas versões do PHP que antecedem a versão 4.1.0, é preciso usar o $HTTP_POST_FILES em vez do $_FILES.
$uploaddir  = "..".$ds."repo".$ds."eco".$ds."protocoloAnexo".$ds.$cdItAtividade.$ds;

if(!is_dir($uploaddir)){
	mkdir($uploaddir);
}

function tirarAcentos($string){

	return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);

}

$nmArquivo  = basename($_FILES['file']['name']);

$uploadfile = $uploaddir . $nmArquivo;

echo "<pre>";
if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
	echo "O arquivo é valido e foi carregado com sucesso.\n";
} else {
	echo "Algo está errado aqui!\n";
}

echo "Aqui estão algumas informações de depuração para você:";
print_r($_FILES);

print "</pre>";
?>