<?php
session_start();

include '../conf/autoLoad.php';

$cdPerfilUsuario = $_GET['cdPerfilUsuario'];

if(isset($cdPerfilUsuario)){

	$pUser = new cPerfilUsuario;

	echo '<script>';
	$pUser->setCdPerfilUsuario($cdPerfilUsuario);
	$pUser->markCheckTable("#formAlterPerfilUser");
	echo '</script>';

}

?>