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

$cdTpLicencaAmbiental 	= $_POST['cdTpLicencaAmbiental'];
$dsTpLicencaAmbiental 	= strtoupper($_POST['dsTpLicencaAmbiental']);
$snAtivo 		  		= strtoupper($_POST['snAtivo']);
$cdEmpresa 				= $_SESSION['cdEmpresa'];

$tpLicencaAmb = new cTpLicencaAmbiental($cdTpLicencaAmbiental, $dsTpLicencaAmbiental, $snAtivo);
$notificacao  = new Notificacao;

$snTpLicencaAmbientalExistente = $tpLicencaAmb->returnCodigo($cdTpLicencaAmbiental);

if($snTpLicencaAmbientalExistente == 0){

	$snCadTpLicencaAmbiental = $tpLicencaAmb->Alterar();

	switch ($snCadTpLicencaAmbiental) {

		case 'S':
		$notificacao->viewSwalNotificacao("Sucesso!", "Orgão Licenciado atualizado com sucesso.", "single", "success");
		break;

		case 'N':
		$notificacao->viewSwalNotificacao("Sem alterações", "Não há dados para alterar.", "single", "info");
		break;

		case 'E':
		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao atualizar o cadastro do orgão licenciado. Por favor, contate o administrador do sistema.", "single", "error");
		break;

		default:
			$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorretor. Por favor, contate o adminsitrador do sistema.", "single", "error");
		break;
	}

}else{

	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um orgão licenciado com este nome. Por favor, escolha outro.", "single", "warning");

}
?>