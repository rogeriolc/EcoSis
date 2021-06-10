<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$dropbox 		 = new cDropbox();

$cdAtividadeFase = isset($_POST['cdAtividadeFase']) ? base64_decode($_POST['cdAtividadeFase']) : null;
$dsComentario 	 = $_POST['dsComentario'];
$dsAnexo 		 = isset($_FILES['dsAnexo']) ? $_FILES['dsAnexo'] : null;

$notificacao 	 = new Notificacao;

if (empty($cdAtividadeFase) || is_null($cdAtividadeFase)) {
	$notificacao->viewSwalNotificacao("Erro!", "Parâmentro de fase incorreto. Contate o administrador do sistema.", "single", "error");
}
if (empty($dsComentario)) {
	$notificacao->viewSwalNotificacao("Atenção!", "Você precisa digitar um comentário.", "single", "warning");
} else {

	$snCadComentario = cAtividade::addComentarioFase($cdAtividadeFase, $dsComentario, $dsAnexo);

	switch (gettype($snCadComentario)) {
		case 'integer':
			$dadosFase = cAtividade::dadosFase($cdAtividadeFase);

			$cdComentarioFase = $snCadComentario;

			if ($dsAnexo['size'] > 0) {

				// $dsCaminho = '..' . DIRECTORY_SEPARATOR . 'repo' . DIRECTORY_SEPARATOR . 'eco' . DIRECTORY_SEPARATOR . 'chat' . DIRECTORY_SEPARATOR . $cdAtividadeFase . DIRECTORY_SEPARATOR . $cdComentarioFase;

				// $anexo = new Anexo($dsAnexo, $dsCaminho);

				$servico = cServico::getServicoByAtividadeFase($cdAtividadeFase);
				$folder  = trim($servico->nm_cliente)."/".trim($servico->nm_empreendimento)."/Proposta - $servico->nr_protocolo.$servico->competencia";

				$dropBoxUpload = $dropbox->upload($dsAnexo, $folder);

				// $response = $anexo->getMessage();

				if ($dropBoxUpload->id) {
					cAtividade::addAnexoComentarioFase($cdComentarioFase, $dsAnexo['name'], json_encode($dropBoxUpload));
				} else {
					$notificacao->viewSwalNotificacao("Erro", "Não foi possível enviar o anexo. Por favor, contate o administrador do sistema.", "single", "error");
				}
			}

			/**
			 * Envia email para os responsáveis da fase
			 */
			$responsaveis 	 = cAtividade::getResponsaveisFase($cdAtividadeFase);

			foreach ($responsaveis as $key => $responsavel) {
				$userData = new cUsuario($responsavel->cd_usuario_responsavel);
				$userData->Dados();

				$nmUsuarioDestinatario 	= $userData->nmPessoa;
				$dsEmailDestino 		= $userData->dsEmail;

				$nmRemetente			= $userData->nmPessoa;
				$dsAssunto				= 'Nova Mensagem';

				$dsTitulo				= 'Calango | Nova Mensagem';
				$dsCorpoMensagem		= '
				<p align="justify">Uma nova mensagem foi enviada você e todos os responsáveis pela fase <strong>'. $dadosFase->ds_fase_atividade .'</strong>, da atividade <strong>'. $dadosFase->ds_tp_atividade .'</strong>.</p>
				<br>
				';
				$dsMensagemFinal		= '<h4>Calango Meio Ambiente</h4>';

				$notificacao->enviaEmail('ecosis@calango.eng.br', 'Calango Meio Ambiente', $dsEmailDestino, $nmUsuarioDestinatario, $dsAssunto, $dsTitulo, $dsCorpoMensagem, $dsMensagemFinal);
			}

			break;

		case 'boolean':
			$notificacao->viewSwalNotificacao("Erro", "Não foi possível cadastrar a atividade. Por favor, contate o administrador do sistema.", "single", "error");
			break;

		case 'boolean':
		case 'string':
			$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cadastrar a atividade. Por favor, contate o administrador do sistema. ´[" . $snCadComentario . "] ", "single", "error");
			break;

		default:
			$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
			break;
	}
}
