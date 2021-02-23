<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdUsuarioSessao 	= $_SESSION['cdUsuario'];

$cdAtividadeFase 	= base64_decode($_POST['cdAtividadeFase']);
$cdUsuarioResp 		= base64_decode($_POST['cdUsuarioResp']);

$notificacao 		= new Notificacao;

if(empty($cdAtividadeFase) || is_null($cdAtividadeFase) || empty($cdUsuarioResp) || is_null($cdUsuarioResp)){
	$notificacao->viewSwalNotificacao("Erro!", "Parâmentro de fase incorreto. Contate o administrador do sistema.", "single","error");
	exit();
}

$snAtualiza = cAtividade::addResponsavelAtividade($cdAtividadeFase, $cdUsuarioResp);


switch (gettype($snAtualiza)) {

	case 'boolean':

	if ($snAtualiza) {

		$user = new cUsuario($cdUsuarioResp);
		$user->Dados();

		$nmUsuarioDestinatario 	= $user->nmPessoa;
		$dsEmailDestino 		= $user->dsEmail;

		$dadosFase = cAtividade::dadosFase($cdAtividadeFase);
		$dsAtividade = $dadosFase->ds_fase_atividade;

		$dtPrazo   = date("d/m/Y", strtotime($dadosFase->dt_prazo));

		$dsComentario	 = "O usuário <strong>$nmUsuarioDestinatario</strong> da conversa foi adicionado a conversa.";

		$snCadComentario = cAtividade::addComentarioFase($cdAtividadeFase, $dsComentario, null);

		switch (gettype($snCadComentario)) {
			case 'integer':

			$userSessao = new cUsuario($cdUsuarioSessao);
			$userSessao->Dados();

			$nmRemetente			= $userSessao->nmPessoa;
			$dsAssunto				= 'Nova Atividade';

			$dsTitulo				= 'Calango | Nova Atividade';
			$dsCorpoMensagem		= '
			<p align="justify">Você foi adicionado a uma atividade: <strong>'.$dsAtividade.'</strong>.</p>
			<br>
			<p align="justify">Esta atividade foi enviada por <strong>'.$nmRemetente.'</strong> com prazo até <strong class="mdc-text-amber">'.$dtPrazo.'</strong></p>
			';
			$dsMensagemFinal		= '<h4>Calango Meio Ambiente</h4>';

			$snEnviaEmail = $notificacao->enviaEmail('info@ecosis.boeckmann.com.br', 'Calango Meio Ambiente', $dsEmailDestino, $nmUsuarioDestinatario, $dsAssunto, $dsTitulo, $dsCorpoMensagem, $dsMensagemFinal);

			break;

			case 'boolean':
			case 'string':
			$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cadastrar a atividade. Por favor, contate o administrador do sistema. ´[".$snCadComentario."] ", "single", "error");
			break;

			default:
			$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
			break;
		}

	} else {
		$notificacao->viewSwalNotificacao("Atenção!", "O usuário já está responsável pela fase.", "single", "warning");
	}

	break;

	default:
		# code...
	break;
}
?>