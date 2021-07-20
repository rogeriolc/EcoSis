<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$cdTpAtividade = $_POST['cdTpAtividade'];

if(!isset($cdTpAtividade)){
	echo "Parametro incorreto";
	exit();
}

$documento = new cTpDocumento();

$documento->listForm($cdTpAtividade);

?>