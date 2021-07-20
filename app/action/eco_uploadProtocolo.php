<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$mysql 		= MysqlConexao::getInstance();

$cdUsuario		= $_SESSION['cdUsuario'];
$nmUsuario 		= $_SESSION['nmUsuario'];
// $cdAtividade 	= $_POST['cdAtividade'];
$cdItAtividade 	= $_POST['cdItAtividade'];
$anexo 			= $_FILES['file'];

$anexado = cAtividade::addAnexoItAtividade($cdItAtividade, $anexo);