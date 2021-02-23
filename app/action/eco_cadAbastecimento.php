<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$dsAbastecimento 	= strtoupper($_POST['dsAbastecimento']);
$cdEmpresa 			= $_SESSION['cdEmpresa'];

$abastecimento   		= new cAbastecimento(null, $dsAbastecimento);
$notificacao  		= new Notificacao;

$snAbastecimentoExistente = $abastecimento->returnCodigo();

if($snAbastecimentoExistente == 0){

	$snAbastecimento = $abastecimento->Cadastrar();

	switch ($snAbastecimento) {

		case $snAbastecimento > 0:

		$notificacao->viewSwalNotificacao("Sucesso!", "Abastecimento cadastrado com sucesso.", "single", "success");

		break;

		case 'N':
		$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um abastecimento com este nome. Por favor, escolha outro.", "single", "warning");
		break;

		case 'E':
		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cadastrar o abastecimento. Por favor, contate o administrador do sistema.", "single", "error");
		break;

		default:
		$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
		break;
	}

}else{

	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um abastecimento com este nome. Por favor, escolha outro.", "single", "warning");

}
?>