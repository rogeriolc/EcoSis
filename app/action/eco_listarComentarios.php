<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdAtividade = isset($_POST['cdAtividade']) ? $_POST['cdAtividade'] : null;

if(is_null($cdAtividade)){
	echo "parametros incorretos";
	exit();
}

$atv = new cAtividade($cdAtividade);
$atv->ListarComentarios();
?>