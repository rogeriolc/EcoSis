<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$dsTipografia 	= strtoupper($_POST['dsTipografia']);
$cdEmpresa 			= $_SESSION['cdEmpresa'];

$tipografia   		= new cTipografia(null, $dsTipografia);
$notificacao  		= new Notificacao;

$snTipografiaExistente = $tipografia->returnCodigo();

if($snTipografiaExistente == 0){

	$snTipografia = $tipografia->Cadastrar();

	switch ($snTipografia) {

		case $snTipografia > 0:

		$notificacao->viewSwalNotificacao("Sucesso!", "Tipografia cadastrado com sucesso.", "single", "success");

		break;

		case 'N':
		$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um tipografia com este nome. Por favor, escolha outro.", "single", "warning");
		break;

		case 'E':
		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cadastrar o tipografia. Por favor, contate o administrador do sistema.", "single", "error");
		break;

		default:
		$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
		break;
	}

}else{

	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um tipografia com este nome. Por favor, escolha outro.", "single", "warning");

}
?>