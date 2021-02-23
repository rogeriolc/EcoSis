<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$dsTpLicencaAmbiental 	= strtoupper($_POST['dsTpLicencaAmbiental']);
$cdEmpresa 				= $_SESSION['cdEmpresa'];

$tpLicencaAmb = new cTpLicencaAmbiental(null, $dsTpLicencaAmbiental);
$notificacao  = new Notificacao;

$snTpLicencaAmbientalExistente = $tpLicencaAmb->returnCodigo();

if($snTpLicencaAmbientalExistente == 0){

	$snCadTpLicencaAmbiental = $tpLicencaAmb->Cadastro();

	switch ($snCadTpLicencaAmbiental) {

		case 'S':
		$notificacao->viewSwalNotificacao("Sucesso!", "Orgão Licenciado cadastrado com sucesso.", "single", "success");
		break;

		case 'N':
		$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um orgão licenciado com este nome. Por favor, escolha outro.", "single", "warning");
		break;

		case 'E':
		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cadastrar o orgão licenciado. Por favor, contate o administrador do sistema.", "single", "error");
		break;

		default:
			$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorretor. Por favor, contate o adminsitrador do sistema.", "single", "error");
		break;
	}

}else{

	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um orgão licenciado com este nome. Por favor, escolha outro.", "single", "warning");

}
?>