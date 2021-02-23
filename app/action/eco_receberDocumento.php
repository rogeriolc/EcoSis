<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$notificacao 	= new Notificacao();

$cdDocServico 	= base64_decode($_POST['cdDocServico']);
$recebido 		= ($_POST['recebido'] == 'true') ? true : false;

$docRecebido 	= cServico::receberDocumento($cdDocServico, $recebido);
?>