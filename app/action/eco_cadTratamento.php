<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$dsTratamento 	= strtoupper($_POST['dsTratamento']);
$cdEmpresa 		= $_SESSION['cdEmpresa'];

$tratamento   	= new cTratamento(null, $dsTratamento);
$notificacao  	= new Notificacao;

$snTratamentoExistente = $tratamento->returnCodigo();

if($snTratamentoExistente == 0){

	$snTratamento = $tratamento->Cadastrar();

	switch ($snTratamento) {

		case $snTratamento > 0:

		$notificacao->viewSwalNotificacao("Sucesso!", "Tratamento cadastrado com sucesso.", "single", "success");

		break;

		case 'N':
		$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um tratamento com este nome. Por favor, escolha outro.", "single", "warning");
		break;

		case 'E':
		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cadastrar o tratamento. Por favor, contate o administrador do sistema.", "single", "error");
		break;

		default:
		$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
		break;
	}

}else{

	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um tratamento com este nome. Por favor, escolha outro.", "single", "warning");

}
?>