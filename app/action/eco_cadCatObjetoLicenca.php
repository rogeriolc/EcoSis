<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$dsCatObjetoLicenca 	= strtoupper($_POST['dsCatObjetoLicenca']);
$cdEmpresa 				= $_SESSION['cdEmpresa'];

$catObjLicenca = new cCatObjetoLicenca(null, $dsCatObjetoLicenca);
$notificacao   = new Notificacao;

$snCatObjetoLicencaExistente = $catObjLicenca->returnCodigo();

if($snCatObjetoLicencaExistente == 0){

	$snCatObjetoLicenca = $catObjLicenca->Cadastro();

	switch ($snCatObjetoLicenca) {

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