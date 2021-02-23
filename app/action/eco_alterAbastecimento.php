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

$cdAbastecimento 	= $_POST['cdAbastecimento'];
$dsAbastecimento 	= strtoupper($_POST['dsAbastecimento']);
$snAtivo 		  	= strtoupper($_POST['snAtivo']);
$cdEmpresa 			= $_SESSION['cdEmpresa'];

$abastecimento = new cAbastecimento($cdAbastecimento, $dsAbastecimento, $snAtivo);
$notificacao  = new Notificacao;

$snAbastecimentoExistente = $abastecimento->returnCodigo($cdAbastecimento);

if($snAbastecimentoExistente == 0){

	$snAbastecimento = $abastecimento->Alterar();

	switch (gettype($snAbastecimento)) {

		case 'integer':
		if($snAbastecimento > 0){

			$notificacao->viewSwalNotificacao("Sucesso!", "Abastecimento atualizado com sucesso.", "single", "success");

		}else{

			$notificacao->viewSwalNotificacao("Sem alterações", "Não há dados para alterar.", "single", "info");
		}

		break;

		case 'string':
		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao atualizar o cadastro do abastecimento. Por favor, contate o administrador do sistema.", "single", "error");
		break;

		default:
		$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
		break;
	}

}else{

	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um abastecimento com este nome. Por favor, escolha outro.", "single", "warning");

}
?>