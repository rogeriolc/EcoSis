<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$notificacao 		= new Notificacao;

//12 - Permissao para alterar o cadastro do usuario
$cdPermissao 		= 12;
$autorizado 		= cPermissao::validarPermissao($cdPermissao);

// if (!$autorizado) {
	// exit();
// }

// var_dump($_SESSION);

$cdUsuario 			= $_POST['cdUsuario'];
$nome 				= strtoupper($_POST['nmUsuario']);
$login 				= strtoupper($_POST['username']);
$dsEmail 			= strtoupper($_POST['dsEmail']);
$snAtivo 			= strtoupper($_POST['snAtivo']);
$cdEmpresa 			= $_SESSION['cdEmpresa'];

$cdPerfilUsuario	= base64_decode($_POST['cdPerfilUsuario']);
$cdPapel			= $_POST['cdPapel'];

// var_dump($_SESSION);

// exit();

if(is_null($cdPerfilUsuario)){
	$notificacao->viewSwalNotificacao("Erro!", "Perfil do usuário não selecionado.", "single", "error");
	exit();
}

if(count($cdPapel) == 0){
	$notificacao->viewSwalNotificacao("Erro!", "Nenhum papel foi selecionado.", "single", "error");
	exit();
}


$usuario 		= new cUsuario($cdUsuario, $nome, $login, null, $snAtivo, $dsEmail, $cdEmpresa);
$usuario->setCdPerfilUsuario($cdPerfilUsuario);

$snUsuarioExistente = $usuario->returnCdUsuario($cdUsuario);

if($snUsuarioExistente == 0){

	$snAlterUsuario = $usuario->alterUsuario();

	switch ($snAlterUsuario) {
		case 'S':
		$notificacao->viewSwalNotificacao("Sucesso!", "Usuário alterado com sucesso.", "single", "success");

		foreach ($cdPapel as $key => $value) {

			$insPapel = cPapel::addPapelUsuario($cdPapel, $cdUsuario);

			if(gettype($insPapel) == 'string') {
				echo 'Ocorreu um erro. '.$insPapel;
				break;
			}

		}

		break;

		case 'N':
		$notificacao->viewSwalNotificacao("Sem alterações", "Não há dados para alterar.", "single", "info");

		foreach ($cdPapel as $key => $value) {

			$cdPapelDecode  = base64_decode($value);

			$insPapel = cPapel::addPapelUsuario($cdPapelDecode, $cdUsuario);

			if(gettype($insPapel) == 'string') {
				echo 'Ocorreu um erro. '.$insPapel;
				break;
			}

		}

		break;

		case 'E':
		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao alterar os dados do usuário. Por favor, contate o administrador do sistema.", "single", "error");
		break;

		default:
		$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
		break;
	}

}else{

	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um usuário com este nome. Por favor, escolha outro.", "single", "warning");

}
?>