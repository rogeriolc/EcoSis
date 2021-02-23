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

$dsPorteEmpreendimento 	= strtoupper($_POST['dsPorteEmpreendimento']);
$cdEmpresa 				= $_SESSION['cdEmpresa'];

$porteEmpre 	= new cPorteEmpreendimento(null, $dsPorteEmpreendimento);
$notificacao 	= new Notificacao;

$snPorteExistente = $porteEmpre->returnCodigo();

if($snPorteExistente == 0){

	$snCadPorte = $porteEmpre->Cadastro();

	switch (gettype($snCadPorte)) {

		case 'boolean':
		($snCadPorte == 'S') ? $notificacao->viewSwalNotificacao("Sucesso!", "Porte do empreendimento cadastrado com sucesso.", "single", "success") : $notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um porte do empreendimento com este nome. Por favor, escolha outro.", "single", "warning");
		break;

		case 'string':
		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cadastrar o porte do empreendimento. Por favor, contate o administrador do sistema. ´[".$snCadPorte."] ", "single", "error");
		break;

		default:
			$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
		break;
	}

}else{

	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um Potencial Poluidor com este nome. Por favor, escolha outro.", "single", "warning");

}
?>