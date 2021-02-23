<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$cdUsuario 	= $_SESSION['cdUsuario'];


$cdPapel 	= $_POST['cdPapel'];
$dsPapel 	= $_POST['dsPapel'];
$snAtivo 	= $_POST['snAtivo'];
$cdPagina 	= $_POST['cdPagina'];

$sqlInsPag  = "DELETE FROM g_papel_pagina WHERE cd_papel = $cdPapel; ";

$papel 		= new cPapel($cdPapel, $dsPapel, $snAtivo);

if(empty($dsPapel)){

	$swalType       = 'warning';
	$swalTitle      = 'Atenção!';
	$swalMensagem   = 'O nome do papel não pode ser vazio.';

	echo '<script>swal("'.$swalTitle.'", "'.$swalMensagem.'","'.$swalType.'");</script>';

	exit();

}

$snUpdPapel = $papel->Atualizar();

if(!is_array($snUpdPapel) && $snUpdPapel > 0){

	foreach ($cdPagina as $cdPagina) {
		$dcdPagina = base64_decode($cdPagina);
		$sqlInsPag .= "INSERT INTO g_papel_pagina (cd_papel, cd_pagina, cd_usuario_registro) VALUES ($cdPapel, $dcdPagina, $cdUsuario); ";
	}

    $mysql = MysqlConexao::getInstance();
	$stmt = null;
	$stmt = $mysql->prepare($sqlInsPag);
	$result = $stmt->execute();

	if($result){

		$swalType       = 'success';
		$swalTitle      = 'Sucesso!';
		$swalMensagem   = 'papel e páginas alteradas com sucesso!';

		echo '<script>swal("'.$swalTitle.'", "'.$swalMensagem.'","'.$swalType.'");</script>';

	}else{

		$error 	 		= $stmt->errorInfo();
		$dsError 		= $error[2];
		$swalType       = 'warning';
		$swalTitle      = 'Atenção!';
		$swalMensagem   = 'O papel foi alterado com sucesso, porém as páginas não foram atualizadas. Erro: '.$dsError;

		echo '<script>swal("'.$swalTitle.'", "'.$swalMensagem.'","'.$swalType.'");</script>';

		exit();

	}

}else{

	if(is_array($snUpdPapel)){

		$swalType       = $snUpdPapel[0];
		$swalTitle      = $snUpdPapel[1];
		$swalMensagem   = $snUpdPapel[2];

		echo '<script>swal("'.$swalTitle.'", "'.$swalMensagem.'","'.$swalType.'");</script>';

	}else{

		echo 'parametros de notificação incorretos';

	}

	exit();

}



?>