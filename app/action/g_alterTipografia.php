<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

//13 - Permissao para alterar cadastros simples
$cdPermissao 			= 13;
$autorizado 			= cPermissao::validarPermissao($cdPermissao);

if (!$autorizado) {
	exit();
}

$cdTipografia 	= $_POST['cdTipografia'];
$dsTipografia 	= strtoupper($_POST['dsTipografia']);
$snAtivo 		= strtoupper($_POST['snAtivo']);
$cdEmpresa 		= $_SESSION['cdEmpresa'];

$tipografia 	= new cTipografia($cdTipografia, $dsTipografia, $snAtivo);
$notificacao   	= new Notificacao;

$snTipografiaExistente = $tipografia->returnCodigo($cdTipografia);

if($snTipografiaExistente == 0){

	$snTipografia = $tipografia->Alterar();

	switch (gettype($snTipografia)) {

		case 'integer':
		if($snTipografia > 0){

			$notificacao->viewSwalNotificacao("Sucesso!", "Tipografia atualizado com sucesso.", "single", "success");

		}else{

			$notificacao->viewSwalNotificacao("Sem alterações", "Não há dados para alterar.", "single", "info");
		}

		break;

		case 'string':
		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao atualizar o cadastro do tipografia. Por favor, contate o administrador do sistema.", "single", "error");
		break;

		default:
		$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
		break;
	}

}else{

	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um tipografia com este nome. Por favor, escolha outro.", "single", "warning");

}
?>