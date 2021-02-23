<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$notificacao 	 	= new Notificacao;
$cdDocConsultoria 	= isset($_POST['cdDocPublicado']) ? $_POST['cdDocPublicado'] : null;

if(empty($cdDocConsultoria) || is_null($cdDocConsultoria)){
	$notificacao->viewSwalNotificacao("Erro!", "Parâmentros incorretos. Contate o administrador do sistema.", "single","error");
	exit();
}

$removido = cAtividade::removerPubDocConsultoria($cdDocConsultoria);

var_dump($removido);

switch (gettype($removido)) {
	case 'integer':

	if ($removido > 0) {
		$notificacao->viewSwalNotificacao("Sucesso!", "Documento excluido com sucesso!", "single", "success");
	} else {
		$notificacao->viewSwalNotificacao("Erro", "Não foi possível excluir o documento. Por favor, contate o administrador do sistema.", "single", "error");
	}
	break;

	case 'string':
	$notificacao->viewSwalNotificacao("Erro", "Não foi possível excluir o anexo: ".$removido.". Por favor, contate o administrador do sistema.", "single", "error");
	break;

	default:
		# code...
	break;
}

?>