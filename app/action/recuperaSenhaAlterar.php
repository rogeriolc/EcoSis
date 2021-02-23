<?php
include '../conf/autoLoad.php';

$token 	 		= isset($_POST['token']) ? $_POST['token'] : null;
$dsSenha 		= isset($_POST['dsSenha']) ? $_POST['dsSenha'] : null;
$dsSenhaConfirm = isset($_POST['dsSenhaConfirm']) ? $_POST['dsSenhaConfirm'] : null;

if (is_null($token)){
	echo "Token inválido!";
	exit();
}

$cdUsuario = cUsuario::validarToken($token);

if (!isset($cdUsuario) || $cdUsuario == 0 || is_null($cdUsuario)) {

	header("Location: ../../recuperacao-de-senha/?t=".$token."&e=".base64_encode("Token inválido!"));

} else {

	if (is_null($dsSenha) || is_null($dsSenhaConfirm)){
		header("Location: ../../recuperacao-de-senha/?t=".$token."&e=".base64_encode("Digite a senha e a confirmação de senha!"));
		exit();
	}


	if ($dsSenha != $dsSenhaConfirm){
		header("Location: ../../recuperacao-de-senha/?t=".$token."&e=".base64_encode("Senhas não conferem!"));
		exit();
	}

	$dsSenha = base64_encode($dsSenha);

	$usuario = new cUsuario($cdUsuario);
	$usuario->setDsSenha($dsSenha);

	$snAlterSenha = $usuario->updateSenhaUsuario();

	if($snAlterSenha == 1){
		header("Location: ../../?s=".base64_encode("Senha alterada com sucesso!"));
	}else{
		var_dump($snAlterSenha);
	}

}