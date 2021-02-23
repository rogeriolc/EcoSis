<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$notificacao 		= new Notificacao;

$nmUsuario 			= strtoupper($_POST['nmUsuario']);
$login 				= strtoupper($_POST['login']);
$dsSenha 			= $_POST['dsSenha'];
$dsConfirmaSenha 	= $_POST['dsConfirmaSenha'];
$dsEmail 			= strtoupper($_POST['dsEmail']);
$cdEmpresa 			= $_SESSION['cdEmpresa'];

$cdPerfilUsuario	= base64_decode($_POST['cdPerfilUsuario']);
$cdPapel			= $_POST['cdPapel'];

//2 - Permissao para cadastrar usuario
$cdPermissao 		= 2;
$autorizado			= cPermissao::validarPermissao($cdPermissao);

if (!$autorizado) {
	exit();
}

if(is_null($cdPerfilUsuario)){
	$notificacao->viewSwalNotificacao("Erro!", "Perfil do usuário não selecionado.", "single", "error");
	exit();
}

if(count($cdPapel) == 0){
	$notificacao->viewSwalNotificacao("Erro!", "Nenhum papel foi selecionado.", "single", "error");
	exit();
}


if($dsSenha == $dsConfirmaSenha){

	$dsSenha = base64_encode($dsSenha);

	$usuario = new cUsuario(null, $nmUsuario, $login, $dsSenha);

	$usuario->setCdEmpresa($cdEmpresa);
	$usuario->setDsEmail($dsEmail);
	$usuario->setCdPerfilUsuario($cdPerfilUsuario);

	$snUsuarioExistente = $usuario->returnCdUsuario();

	if($snUsuarioExistente == 0){
		$snCadUsuario = $usuario->cadUsuario();

		switch ($snCadUsuario) {
			case 'S':
			$notificacao->viewSwalNotificacao("Sucesso!", "Usuário cadastrado com sucesso.", "single", "success");
			break;

			case 'N':
			$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um usuário com este nome. Por favor, escolha outro.", "single", "warning");
			break;

			case 'E':
			$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cadastrar o usuário. Por favor, contate o administrador do sistema.", "single", "error");
			break;

			default:
			$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
			break;
		}
	}else{
		$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um usuário com este nome. Por favor, escolha outro.", "single", "warning");
	}

}else{

	$notificacao->viewSwalNotificacao("Senhas não conferem!", "O campo senha e confirmação de senha devem ser iguais.", "single", "error");

}
?>