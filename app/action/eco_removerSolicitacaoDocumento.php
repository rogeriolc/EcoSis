<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$notificacao  	= new Notificacao;
$serv 			= new cServico();

$cdDocServico = isset($_POST['cdDocServico']) ? $_POST['cdDocServico'] : null;

if (is_null($cdDocServico)) {
	$notificacao->viewSwalNotificacao("Essa não!", "Não foi possível obter a lista de documentos solicitados. Por favor entre contato com o administrador do sistema", "single", "error");
	exit();
}

// Pega os dados do documento antes dele ser excluido
$doc = $serv->getSolDoc($cdDocServico);

$snRemove = $serv->removerSolDoc($cdDocServico);

switch (gettype($snRemove)) {

	case 'integer':

	if ($snRemove > 0) {

		if (count($doc) > 0) {
			$doc = $doc[0];

			$docData = json_decode($doc->file_data, true);

			if (isset($docData['id'])) {
				$dropbox = new cDropbox();
				$dropbox->delete($docData['id']);
			}
		}

		$notificacao->viewSwalNotificacao("Sucesso!", "Solicitação removida com sucesso.", "single", "success");
	} else {
		$notificacao->viewSwalNotificacao("Erro!", "Não foi possível remover a solicitação.", "single", "error");
	}


	break;

	default:
	$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
	break;
}
?>