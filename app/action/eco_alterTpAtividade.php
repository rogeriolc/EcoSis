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

$cdTpAtividade 		= $_POST['cdTpAtividade'];
$dsTpAtividade 		= strtoupper($_POST['dsTpAtividade']);
$cdCatTpAtividade 	= isset($_POST['cdCatTpAtividade']) ? base64_decode($_POST['cdCatTpAtividade']) : null;
$cdFaseAtv			= isset($_POST['cdFase']) ? $_POST['cdFase'] : null;
$cdDocumentoAtv		= isset($_POST['cdDocumento']) ? $_POST['cdDocumento'] : null;
$snAtivo 			= strtoupper($_POST['snAtivo']);
$cdEmpresa			= $_SESSION['cdEmpresa'];

$tpAtividade	= new cTpAtividade($cdTpAtividade, $dsTpAtividade, $snAtivo);
$tpAtividade->setCdCatTpAtividade($cdCatTpAtividade);
$notificacao 	= new Notificacao;

$snTpAtividadeExistente = $tpAtividade->returnCodigo($cdTpAtividade);

if($snTpAtividadeExistente == 0){

	$snCadtpAtividade = $tpAtividade->Alterar();

	switch ($snCadtpAtividade) {

		case 'S':
		case 'N':
		$notificacao->viewSwalNotificacao("Sucesso!", "Tipo de atividade atualizado com sucesso.", "single", "success");

		$c = 0;

		$fase = new cFaseAtividade();
		$fase->setCdTpAtividade($cdTpAtividade);
		$fase->removeFaseAtividade();

		foreach ($cdFaseAtv as $fase) {
			$cdFase = base64_decode($fase);

			$fase = new cFaseAtividade($cdFase, null, $c);
			$fase->setCdTpAtividade($cdTpAtividade);

			$snCadFase = $fase->addFaseAtividade();

			$c++;
		}

		$i = 0;

		$documento = new cTpDocumento();
		$documento->setCdTpAtividade($cdTpAtividade);
		$documento->removeDocumentoAtividade();
		
		foreach ($cdDocumentoAtv as $doc) {
			$cdDocumento = base64_decode($doc);

			$documento = new cTpDocumento($cdDocumento, null, $i);
			$documento->setCdTpAtividade($cdTpAtividade);

			$snCadFase = $documento->addDocumentoAtividade();

			$i++;
		}

		break;

		case 'E':
		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao atualizar o cadastro do tipo de atividade. Por favor, contate o administrador do sistema.", "single", "error");
		break;

		default:
			$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorretor. Por favor, contate o adminsitrador do sistema.", "single", "error");
		break;
	}

}else{

	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um tipo de atividade com este nome. Por favor, escolha outro.", "single", "warning");

}
?>