<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();
$atv  = new cAtividade;

$cdAtividadeFase = isset($_POST['cdAtividadeFase']) ? $_POST['cdAtividadeFase'] : null;

$atv->removerFaseAtividade($cdAtividadeFase);

?>