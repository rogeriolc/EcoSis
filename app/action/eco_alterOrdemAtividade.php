<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$atividades = (isset($_POST['cdAtividade'])) ? $_POST['cdAtividade'] : null;

if (is_null($atividades)) {
	exit();
}

foreach ($atividades as $key => $value ) {
	cAtividade::alterarOrdemAtividade($value, $key);
}