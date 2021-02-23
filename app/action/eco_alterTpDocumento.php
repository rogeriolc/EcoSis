<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

//13 - Permissao para alterar cadastros simples
$cdPermissao 	= 13;
$autorizado 	= cPermissao::validarPermissao($cdPermissao);

if (!$autorizado) {
	exit();
}

$cdTpDocumento 	= $_POST['cdTpDocumento'];
$dsTpDocumento 	= strtoupper($_POST['dsTpDocumento']);
$snAtivo 		= strtoupper($_POST['snAtivo']);
$cdEmpresa		= $_SESSION['cdEmpresa'];

$tpDocumento 	= new cTpDocumento($cdTpDocumento, $dsTpDocumento, null, $snAtivo);
$notificacao 	= new Notificacao;

$snTpDocumentoExistente = $tpDocumento->returnCodigo($cdTpDocumento);

if($snTpDocumentoExistente == 0){

	$snCadTpDocumento = $tpDocumento->Alterar();

	switch ($snCadTpDocumento) {

		case 'S':
		$notificacao->viewSwalNotificacao("Sucesso!", "Tipo de documento atualizado com sucesso.", "single", "success");
		break;

		case 'N':
		$notificacao->viewSwalNotificacao("Sem alterações", "Não há dados para alterar.", "single", "info");
		break;

		case 'E':
		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao atualizar o cadastro do tipo de documento. Por favor, contate o administrador do sistema.", "single", "error");
		break;

		default:
			$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
		break;
	}

}else{

	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um tipo de documento com este nome. Por favor, escolha outro.", "single", "warning");

}
?>