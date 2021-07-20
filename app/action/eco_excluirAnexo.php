<?php
session_start();

// include '../conf/showErros.php';
include '../conf/autoLoad.php';

cSeguranca::validaSessao();

//19 - Permissao para excluir anexos
$cdPermissao 			= 19;
$autorizado 			= cPermissao::validarPermissao($cdPermissao);

if (!$autorizado) {
	exit();
}

$cdUsuario		= $_SESSION['cdUsuario'];
$nmUsuario 		= $_SESSION['nmUsuario'];

$fileId 		= base64_decode($_POST['file']);
$id 			= base64_decode($_POST['id']);

$excluido = cAtividade::removeAnexoItAtividade($id, $fileId);

$atv 			= new cAtividade();
$atv->setCdItAtividade($id);
$atv->renderAnexos();


