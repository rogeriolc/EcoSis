<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdServico = isset($_POST['cdServico']) ? $_POST['cdServico'] : null;

if(is_null($cdServico)){
	echo "parametros incorretos";
	exit();
}

$serv = new cServico($cdServico);
$serv->ListarComentarios();
?>