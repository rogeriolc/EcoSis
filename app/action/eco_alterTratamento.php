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

$cdTratamento 	= $_POST['cdTratamento'];
$dsTratamento 	= strtoupper($_POST['dsTratamento']);
$snAtivo 		= strtoupper($_POST['snAtivo']);
$cdEmpresa 		= $_SESSION['cdEmpresa'];

$tratamento 	= new cTratamento($cdTratamento, $dsTratamento, $snAtivo);
$notificacao  	= new Notificacao;

$snTratamentoExistente = $tratamento->returnCodigo($cdTratamento);

if($snTratamentoExistente == 0){

	$snTratamento = $tratamento->Alterar();

	switch (gettype($snTratamento)) {

		case 'integer':
		if($snTratamento > 0){

			$notificacao->viewSwalNotificacao("Sucesso!", "Tratamento atualizado com sucesso.", "single", "success");

		}else{

			$notificacao->viewSwalNotificacao("Sem alterações", "Não há dados para alterar.", "single", "info");
		}

		break;

		case 'string':
		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao atualizar o cadastro do tratamento. Por favor, contate o administrador do sistema.", "single", "error");
		break;

		default:
		$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
		break;
	}

}else{

	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um tratamento com este nome. Por favor, escolha outro.", "single", "warning");

}
?>