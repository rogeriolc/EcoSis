<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

//1 - Permissao para cadastros simples
$cdPermissao 		= 1;
$autorizado 		= cPermissao::validarPermissao($cdPermissao);

if (!$autorizado) {
	exit();
}

$dsPotencialPoluidor 	= strtoupper($_POST['dsPotencialPoluidor']);
$cdEmpresa 				= $_SESSION['cdEmpresa'];

$pPoluidor 		= new cPotencialPoluidor(null, $dsPotencialPoluidor);
$notificacao 	= new Notificacao;

$snPotencialExistente = $pPoluidor->returnCodigo();

if($snPotencialExistente == 0){

	$snCadPotencial = $pPoluidor->Cadastro();

	switch ($snCadPotencial) {

		case 'S':
		$notificacao->viewSwalNotificacao("Sucesso!", "Potencial Poluidor cadastrado com sucesso.", "single", "success");
		break;

		case 'N':
		$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um potencial poluidor com este nome. Por favor, escolha outro.", "single", "warning");
		break;

		case 'E':
		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cadastrar o potencial poluidor. Por favor, contate o administrador do sistema.", "single", "error");
		break;

		default:
			$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
		break;
	}

}else{

	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um Potencial Poluidor com este nome. Por favor, escolha outro.", "single", "warning");

}
?>