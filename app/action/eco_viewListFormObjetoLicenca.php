<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$cdObjetoLicenca = $_POST['cdObjetoLicenca'];

if(!isset($cdObjetoLicenca)){
	echo "Parametro incorreto";
	exit();
}

$fase = new cFaseObjeto();

$fase->listForm($cdObjetoLicenca);

?>