<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

//13 - Permissao para alterar cadastros simples
$cdPermissao 	= 13;
$autorizado 	= cPermissao::validarPermissao($cdPermissao);

if (!$autorizado) {
	exit();
}

$cdTpArea 	= $_POST['cdTpArea'];
$dsTpArea 	= strtoupper($_POST['dsTpArea']);
$snAtivo 	= strtoupper($_POST['snAtivo']);
$cdEmpresa	= $_SESSION['cdEmpresa'];

$tpArea = new cTpArea($cdTpArea, $dsTpArea, null, $snAtivo);
$notificacao 	 = new Notificacao;

$snTpAreaExistente = $tpArea->returnCodigo($cdTpArea);

if($snTpAreaExistente == 0){

	$snCadTpArea = $tpArea->Alterar();

	switch ($snCadTpArea) {

		case 'S':
		$notificacao->viewSwalNotificacao("Sucesso!", "Tipo de área atualizado com sucesso.", "single", "success");
		break;

		case 'N':
		$notificacao->viewSwalNotificacao("Sem alterações", "Não há dados para alterar.", "single", "info");
		break;

		case 'E':
		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao atualizar o cadastro do tipo de área. Por favor, contate o administrador do sistema.", "single", "error");
		break;

		default:
			$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorretor. Por favor, contate o adminsitrador do sistema.", "single", "error");
		break;
	}

}else{

	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um tipo de área com este nome. Por favor, escolha outro.", "single", "warning");

}
?>