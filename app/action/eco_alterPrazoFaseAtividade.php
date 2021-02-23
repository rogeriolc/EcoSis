<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$notificacao 		= new Notificacao;

$cdUsuarioSessao 	= $_SESSION['cdUsuario'];

$cdAtividadeFase 	= isset($_POST['cdAtividadeFase']) ? base64_decode($_POST['cdAtividadeFase']) : null;
$dtPrazo 			= isset($_POST['dtPrazo']) ? $_POST['dtPrazo'] : null;

$dadosFase 			= cAtividade::dadosFase($cdAtividadeFase);
$dsAtividade 		= $dadosFase->ds_fase_atividade;
$dtPrazoAntigo 		= !is_null($dadosFase->dt_prazo) ? date("d/m/Y", strtotime($dadosFase->dt_prazo)) : null;

$dtPrazo			= implode("-",array_reverse(explode("/", $dtPrazo)));

$alterado = cAtividade::alterarPrazoFase($cdAtividadeFase, $dtPrazo);

if($alterado){

	$resposaveis = cAtividade::getResponsaveisFase($cdAtividadeFase);

	foreach ($resposaveis as $responsavel) {
		$user = new cUsuario($responsavel->cd_usuario_responsavel);
		$user->Dados();

		$nmUsuarioDestinatario 	= $user->nmPessoa;
		$dsEmailDestino 		= $user->dsEmail;

		$userSessao = new cUsuario($cdUsuarioSessao);
		$userSessao->Dados();

		$nmRemetente			= $userSessao->nmPessoa;
		$dsAssunto				= 'Alteração de Prazo';

		$dsTitulo				= 'Calango | Alteração de Prazo';

		if (is_null($dtPrazoAntigo)) {
			$dsCorpoMensagem		= '
			<p align="justify">O prazo da atividade <strong>'.$dsAtividade.'</strong> foi definida para o dia <strong class="mdc-text-amber">'.$dtPrazo.'</strong>.</p>
			<br>
			<p align="justify">Esta atividade foi enviada por <strong>'.$nmRemetente.'</strong>.</p>
			';
		} else {
			$dsCorpoMensagem		= '
			<p align="justify">O prazo da atividade <strong>'.$dsAtividade.'</strong> foi alterado do dia <strong>'.$dtPrazoAntigo.'</strong> para o dia <strong class="mdc-text-amber">'.$dtPrazo.'</strong>.</p>
			<br>
			<p align="justify">Esta atividade foi enviada por <strong>'.$nmRemetente.'</strong>.</p>
			';
		}

		$dsMensagemFinal		= '<h4>Calango Meio Ambiente</h4>';

		$snEnviaEmail = $notificacao->enviaEmail('info@ecosis.boeckmann.com.br', 'Calango Meio Ambiente', $dsEmailDestino, $nmUsuarioDestinatario, $dsAssunto, $dsTitulo, $dsCorpoMensagem, $dsMensagemFinal);

		// var_dump($snEnviaEmail);

	}

}

?>