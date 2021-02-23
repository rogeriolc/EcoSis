<?php
session_start();

include '../conf/autoLoad.php';

$cdPapel = $_GET['cdPapel'];

if(isset($cdPapel)){

	$papel = new cPapel;

	echo '<script>';
	$papel->setCdPapel($cdPapel);
	$papel->markCheckTable("#formAlterPapel");
	echo '</script>';

}

?>