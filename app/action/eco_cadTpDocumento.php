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

$dsTpDocumento 	= strtoupper($_POST['dsTpDocumento']);
$cdEmpresa 		= $_SESSION['cdEmpresa'];

$tpDocumento 	= new cTpDocumento(null, $dsTpDocumento);
$notificacao 	= new Notificacao;

$snDocumentoExistente = $tpDocumento->returnCodigo();

if($snDocumentoExistente == 0){

	$snCadDocumento = $tpDocumento->Cadastro();

	switch ($snCadDocumento) {

		case 'S':
		$notificacao->viewSwalNotificacao("Sucesso!", "Tipo de documento cadastrado com sucesso.", "single", "success");
		break;

		case 'N':
		$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um tipo de documento com este nome. Por favor, escolha outro.", "single", "warning");
		break;

		case 'E':
		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cadastrar o tipo de documento. Por favor, contate o administrador do sistema.", "single", "error");
		break;

		default:
			$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
		break;
	}

}else{

	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um tipo de documento com este nome. Por favor, escolha outro.", "single", "warning");

}
?>