<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

// $cdAtividade = $_POST['cdAtv'];
$cdItAtividade = $_POST['cdItAtv'];

// $atv = new cAtividade($cdAtividade);
$atv = new cAtividade();
$atv->setCdItAtividade($cdItAtividade);

$atv->ListarAnexos();
?>