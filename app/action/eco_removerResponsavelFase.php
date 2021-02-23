<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdAtividadeFase 	= base64_decode($_POST['cdAtividadeFase']);
$cdUsuarioResp 		= base64_decode($_POST['cdUsuarioResp']);

$notificacao 		= new Notificacao;

if(empty($cdAtividadeFase) || is_null($cdAtividadeFase) || empty($cdUsuarioResp) || is_null($cdUsuarioResp)){
	$notificacao->viewSwalNotificacao("Erro!", "Parâmentro de fase incorreto. Contate o administrador do sistema.", "single","error");
	exit();
}

$snAtualiza = cAtividade::removerResponsavelAtividade($cdAtividadeFase, $cdUsuarioResp);


switch (gettype($snAtualiza)) {

	case 'boolean':

	if ($snAtualiza) {

		$user = new cUsuario($cdUsuarioResp);
		$user->Dados();
		$nmUsuario = $user->nmPessoa;

		$dsComentario	 = "O usuário <strong>$nmUsuario</strong> foi removido da conversa.";

		$snCadComentario = cAtividade::addComentarioFase($cdAtividadeFase, $dsComentario, null);

		switch (gettype($snCadComentario)) {
			case 'integer':

			break;

			case 'boolean':
			case 'string':
			$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao remover o usuário da fase. Por favor, contate o administrador do sistema. ´[".$snCadComentario."] ", "single", "error");
			break;

			default:
			$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
			break;
		}

	} else {
		$notificacao->viewSwalNotificacao("Atenção!", "O usuário não está mais presente na fase.", "single", "warning");
	}

	break;

	default:
		# code...
	break;
}
?>