<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$cdEmpreendimento = $_POST['cdEmpreendimento'];

$empreendimento = new cEmpreendimento($cdEmpreendimento);
$empreendimento->ListarTpRevisao();

?>