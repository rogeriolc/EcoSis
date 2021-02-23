<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$cdUsuario 	 	 = $_SESSION['cdUsuario'];

$cdPerfilUsuario = $_POST['cdPerfilUsuario'];
$dsPerfilUsuario = strtoupper($_POST['dsPerfilUsuario']);
$snAtivo 		 = $_POST['snAtivo'];
$cdPermissao 	 = $_POST['cdPermissao'];

$sqlInsPag       = "DELETE FROM g_permissao_perfil WHERE cd_perfil_usuario = $cdPerfilUsuario; ";

$pUsuario 		 = new cPerfilUsuario($cdPerfilUsuario, $dsPerfilUsuario, $snAtivo);

if(empty($dsPerfilUsuario)){

	$swalType       = 'warning';
	$swalTitle      = 'Atenção!';
	$swalMensagem   = 'O nome do perfil não pode ser vazio.';

	echo '<script>swal("'.$swalTitle.'", "'.$swalMensagem.'","'.$swalType.'");</script>';

	exit();

}

$snUpdPerfilUsuario = $pUsuario->Atualizar();

if(!is_array($snUpdPerfilUsuario) && $snUpdPerfilUsuario > 0){

	foreach ($cdPermissao as $cdPermissao) {

		$dcdPermissao = base64_decode($cdPermissao);
		$sqlInsPag .= "INSERT INTO g_permissao_perfil (cd_permissao_sis, cd_perfil_usuario, cd_usuario_registro) VALUES ($dcdPermissao, $cdPerfilUsuario, $cdUsuario); ";

	}

    $mysql 	= MysqlConexao::getInstance();
    $stmt 	= null;
	$stmt 	= $mysql->prepare($sqlInsPag);
	$result = $stmt->execute();

	if($result){

		$swalType       = 'success';
		$swalTitle      = 'Sucesso!';
		$swalMensagem   = 'Perfil e permissões alteradas com sucesso!';

		echo '<script>swal("'.$swalTitle.'", "'.$swalMensagem.'","'.$swalType.'");</script>';

	}else{

		$error 	 		= $stmt->errorInfo();
		$dsError 		= $error[2];

		$swalType       = 'warning';
		$swalTitle      = 'Atenção!';
		$swalMensagem   = 'O perfil foi alterado com sucesso, porém as permissões não foram atualizadas. Erro: '.$dsError;

		echo '<script>swal("'.$swalTitle.'", "'.$swalMensagem.'","'.$swalType.'");</script>';

		exit();

	}

}else{

	if(is_array($snUpdPerfilUsuario)){

		$swalType       = $snUpdPerfilUsuario[0];
		$swalTitle      = $snUpdPerfilUsuario[1];
		$swalMensagem   = $snUpdPerfilUsuario[2];

		echo '<script>swal("'.$swalTitle.'", "'.$swalMensagem.'","'.$swalType.'");</script>';

	}else{

		echo 'parametros de notificação incorretos';

	}


	exit();

}
?>