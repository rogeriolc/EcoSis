<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdProposta = $_POST['cdProposta'];

cPropostaLicencaAmb::addCliente($cdProposta);