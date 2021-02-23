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

$cdPorteEmpreendimento 	= $_POST['cdPorteEmpreendimento'];
$dsPorteEmpreendimento 	= strtoupper($_POST['dsPorteEmpreendimento']);
$snAtivo 		  		= strtoupper($_POST['snAtivo']);
$cdEmpresa 				= $_SESSION['cdEmpresa'];

$porteEmpre 	= new cPorteEmpreendimento($cdPorteEmpreendimento, $dsPorteEmpreendimento, $snAtivo);
$notificacao 	= new Notificacao;

$snPorteExistente = $porteEmpre->returnCodigo($cdPorteEmpreendimento);

if($snPorteExistente == 0){

	$snCadPorte = $porteEmpre->Alterar();

	switch ($snCadPorte) {

		case 'S':
		$notificacao->viewSwalNotificacao("Sucesso!", "Porte do empreendimento alterado com sucesso.", "single", "success");
		break;

		case 'N':
		$notificacao->viewSwalNotificacao("Sem alterações", "Não há dados para alterar.", "single", "info");
		break;

		case 'E':
		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao alterar os dados do porte do empreendimento. Por favor, contate o administrador do sistema.", "single", "error");
		break;

		default:
			$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorretor. Por favor, contate o adminsitrador do sistema.", "single", "error");
		break;
	}

}else{

	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um empreendimento com este nome. Por favor, escolha outro.", "single", "warning");

}
?>