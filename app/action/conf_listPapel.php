<?php
session_start();

include '../conf/autoLoad.php';

$cdPapel = $_GET['cdPapel'];

if(isset($cdPapel)){

	$papel = new cPapel;

	echo '<script>';
	$pUser->setCdPapel($cdPapel);
	$pUser->listTableCheckPagina("#formAlterPapel");
	echo '</script>';

}
?>