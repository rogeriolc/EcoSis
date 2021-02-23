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

$nmOrgaoLicenciado 	= strtoupper($_POST['nmOrgaoLicenciado']);
$cdEmpresa 			= $_SESSION['cdEmpresa'];

$orgaoLicenciado = new cOrgaoLicenciado(null, $nmOrgaoLicenciado);
$notificacao 	 = new Notificacao;

$snOrgaoExistente = $orgaoLicenciado->returnCodigo();

if($snOrgaoExistente == 0){

	$snCadOrgao = $orgaoLicenciado->Cadastro();

	switch ($snCadOrgao) {

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