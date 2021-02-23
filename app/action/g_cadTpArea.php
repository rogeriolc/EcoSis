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

$dsTpArea 	= strtoupper($_POST['dsTpArea']);
$cdEmpresa 	= $_SESSION['cdEmpresa'];

$tpArea 		= new cTpArea(null, $dsTpArea);
$notificacao 	= new Notificacao;

$snAreaExistente = $tpArea->returnCodigo();

if($snAreaExistente == 0){

	$snCadArea = $tpArea->Cadastro();

	switch ($snCadArea) {

		case 'S':
		$notificacao->viewSwalNotificacao("Sucesso!", "Tipo de área cadastrado com sucesso.", "single", "success");
		break;

		case 'N':
		$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um tipo de área com este nome. Por favor, escolha outro.", "single", "warning");
		break;

		case 'E':
		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cadastrar o tipo de área. Por favor, contate o administrador do sistema.", "single", "error");
		break;

		default:
			$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
		break;
	}

}else{

	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um tipo de área com este nome. Por favor, escolha outro.", "single", "warning");

}
?>