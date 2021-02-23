<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$cdUsuario 	 = $_SESSION['cdUsuario'];
$dsPapel  	 = strtoupper($_POST['dsPapel']);
$cdPagina 	 = $_POST['cdPagina'];
$arrayPagina = array();
$p 			 = 0;


// foreach ($cdPagina as $cdPagina) {
// 	$arrayPagina[$p] = base64_decode($cdPagina);
// 	$p++;
// }

// $inPagina = implode(",", array_filter($arrayPagina));

$papel = new cPapel(null, $dsPapel);
$cdPapel = $papel->Cadastrar();

if(!is_array($cdPapel) && $cdPapel > 0){

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
		$swalMensagem   = 'Papel criado e páginas vinculadas com sucesso!';

		echo '<script>swal("'.$swalTitle.'", "'.$swalMensagem.'","'.$swalType.'");</script>';

	}else{
		$error 	 		= $stmt->errorInfo();
		$dsError 		= $error[2];
		$swalType       = 'warning';
		$swalTitle      = 'Atenção!';
		$swalMensagem   = 'O papel foi criado com sucesso, porém as páginas não foram vinculadas ao mesmo. Erro: '.$dsError;

		echo '<script>swal("'.$swalTitle.'", "'.$swalMensagem.'","'.$swalType.'");</script>';
		exit();
	}

}else{

	if(is_array($cdPapel)){

		$swalType       = $cdPapel[0];
		$swalTitle      = $cdPapel[1];
		$swalMensagem   = $cdPapel[2];


		echo '<script>swal("'.$swalTitle.'", "'.$swalMensagem.'","'.$swalType.'");</script>';

	}else{

		echo 'parametros de notificação incorretos';

	}

	exit();
}

?>