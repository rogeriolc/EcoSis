<?php
include '../conf/autoLoad.php';

$seguranca 	 = new cSeguranca;
$notificacao = new Notificacao;
$usuario 	 = new cUsuario;

$username  = $_POST['username'];
$ds_senha  = base64_encode($_POST['dsSenha']);
// $snLembrar = $_POST['snLembrar'];
// echo $cdEmpresa = base64_decode($_POST['cdEmpresa']);

$auth = $seguranca->login($username, $ds_senha);

switch ($auth) {
	case 'B':
	$notificacao->viewSwalNotificacao("Não foi possível efetuar o login", "Seu usuário está bloqueado.","single", "error");
	break;

	case 'S':
	$notificacao->viewSwalNotificacao("Autenticado!", "Você será direcionado para o sistema em instantes...","timer", "success",1000);

	session_start();
	$_SESSION['cdEmpresa'] = 1;
	$usuario->dadosUsuario(null, $username);

	if(isset($snLembrar)){
		setcookie("username", $username);
		setcookie("senha", $_POST['dsSenha']);
		// echo 'salvo!';
	}else{
		unset($_COOKIE['username']);
		unset($_COOKIE['senha']);
		// echo 'nao salvo!';
	}

	echo '<script>setTimeout(function(){window.location.href="../../app/";},1500);</script>';
	break;

	case 'U':
	$notificacao->viewSwalNotificacao("Não foi possível efetuar o login", "Usuário e/ou senha inválidos. Tente novamente.","single", "warning");
	break;

	default:
	echo 'opa :D';
	break;
}

?>