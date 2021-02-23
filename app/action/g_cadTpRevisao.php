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

$dsTpRevisao 	= strtoupper($_POST['dsTpRevisao']);
$cdEmpresa 	= $_SESSION['cdEmpresa'];

$tpRevisao 		= new cTpRevisao(null, $dsTpRevisao);
$notificacao 	= new Notificacao;

$snAreaExistente = $tpRevisao->returnCodigo();

if($snAreaExistente == 0){

	$snCadRevisao = $tpRevisao->Cadastro();

	switch ($snCadRevisao) {

		case 'S':
		$notificacao->viewSwalNotificacao("Sucesso!", "Tipo de revisão cadastrado com sucesso.", "single", "success");
		break;

		case 'N':
		$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um tipo de revisão com este nome. Por favor, escolha outro.", "single", "warning");
		break;

		case 'E':
		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cadastrar o tipo de revisão. Por favor, contate o administrador do sistema.", "single", "error");
		break;

		default:
			$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
		break;
	}

}else{

	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um tipo de revisão com este nome. Por favor, escolha outro.", "single", "warning");

}
?>