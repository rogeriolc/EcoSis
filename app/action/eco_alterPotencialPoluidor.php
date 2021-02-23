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

$cdPotencialPoluidor 	= $_POST['cdPotencialPoluidor'];
$dsPotencialPoluidor 	= strtoupper($_POST['dsPotencialPoluidor']);
$snAtivo 		  		= strtoupper($_POST['snAtivo']);
$cdEmpresa 				= $_SESSION['cdEmpresa'];

$pPoluidor 		= new cPotencialPoluidor($cdPotencialPoluidor, $dsPotencialPoluidor, $snAtivo);
$notificacao 	= new Notificacao;

$snPotencialExistente = $pPoluidor->returnCodigo($cdPotencialPoluidor);

if($snPotencialExistente == 0){

	$snAlterEmpreendimento = $pPoluidor->Alterar();

	switch ($snAlterEmpreendimento) {

		case 'S':
		$notificacao->viewSwalNotificacao("Sucesso!", "Potencial poluidor alterado com sucesso.", "single", "success");
		break;

		case 'N':
		$notificacao->viewSwalNotificacao("Sem alterações", "Não há dados para alterar.", "single", "info");
		break;

		case 'E':
		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao alterar os dados do potencial poluidor. Por favor, contate o administrador do sistema.", "single", "error");
		break;

		default:
			$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorretor. Por favor, contate o adminsitrador do sistema.", "single", "error");
		break;
	}

}else{

	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um empreendimento com este nome. Por favor, escolha outro.", "single", "warning");

}
?>