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

$cdTpRevisao 	= $_POST['cdTpRevisao'];
$dsTpRevisao 	= strtoupper($_POST['dsTpRevisao']);
$snAtivo 		= (strtoupper($_POST['snAtivo']) == 'S') ? true : false;
$cdEmpresa		= $_SESSION['cdEmpresa'];

$tpRevisao 		= new cTpRevisao($cdTpRevisao, $dsTpRevisao, $snAtivo);
$notificacao 	= new Notificacao;

$snTpRevisaoExistente = $tpRevisao->returnCodigo($cdTpRevisao);

if($snTpRevisaoExistente == 0){

	$snCadTpRevisao = $tpRevisao->Alterar();

	switch ($snCadTpRevisao) {

		case 'S':
		$notificacao->viewSwalNotificacao("Sucesso!", "Tipo de revisão atualizado com sucesso.", "single", "success");
		break;

		case 'N':
		$notificacao->viewSwalNotificacao("Sem alterações", "Não há dados para alterar.", "single", "info");
		break;

		case 'E':
		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao atualizar o cadastro do tipo de revisão. Por favor, contate o administrador do sistema.", "single", "error");
		break;

		default:
			$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorretor. Por favor, contate o adminsitrador do sistema.", "single", "error");
		break;
	}

}else{

	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um tipo de revisão com este nome. Por favor, escolha outro.", "single", "warning");

}
?>