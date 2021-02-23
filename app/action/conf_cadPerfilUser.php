<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$dsPerfilUsuario = strtoupper($_POST['dsPerfilUsuario']);
$cdPermissao 	 = $_POST['cdPermissao'];
$cdUsuario 		 = $_SESSION['cdUsuario'];

$sqlInsPag 		 = "";

$pUsuario 		 = new cPerfilUsuario(null, $dsPerfilUsuario);

if(empty($dsPerfilUsuario)){
	$swalType       = 'warning';
	$swalTitle      = 'Atenção!';
	$swalMensagem   = 'O nome do perfil não pode ser vazio.';

	echo '<script>swal("'.$swalTitle.'", "'.$swalMensagem.'","'.$swalType.'");</script>';

	exit();

}

$cdPerfilUsuario = $pUsuario->Cadastrar();

if(!is_array($cdPerfilUsuario) && $cdPerfilUsuario > 0){

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
		$swalMensagem   = 'Perfil criado e permissões vinculadas com sucesso!';

		echo '<script>swal("'.$swalTitle.'", "'.$swalMensagem.'","'.$swalType.'");</script>';

	}else{

		$error 	 		= $stmt->errorInfo();
		$dsError 		= $error[2];
		$swalType       = 'warning';
		$swalTitle      = 'Atenção!';
		$swalMensagem   = 'O perfil foi criado com sucesso, porém as permissões não foram vinculadas ao mesmo. Erro: '.$dsError;

		echo '<script>swal("'.$swalTitle.'", "'.$swalMensagem.'","'.$swalType.'");</script>';

		exit();

	}

}else{

	if(is_array($cdPerfilUsuario)){

		$swalType       = $cdPerfilUsuario[0];
		$swalTitle      = $cdPerfilUsuario[1];
		$swalMensagem   = $cdPerfilUsuario[2];

		echo '<script>swal("'.$swalTitle.'", "'.$swalMensagem.'","'.$swalType.'");</script>';

	}else{

		echo 'parametros de notificação incorretos';

	}

	exit();

}

?>