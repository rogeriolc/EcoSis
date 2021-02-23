<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$notificacao = new Notificacao;

$cdItLicencaFase = base64_decode($_POST['cdItLicencaFase']);

$lic = new cLicencaAmbiental();
$lic->setCdItLicencaFase($cdItLicencaFase);
$lic->dadosFaseObjeto();

//verifica o status da fase
switch ($lic->getTpStatus()) {
	case 'E':

	$snDeleta = $lic->removerFaseObjeto();

	switch (gettype($snDeleta)) {
		case 'boolean':

		if($snDeleta){
			$notificacao->viewSwalNotificacao("Sucesso!", "Fase removida com sucesso!", "single", "success");

			echo '<script>$("#panelFase'.md5($cdItLicencaFase).'").addClass("animated bounceOutLeft"); setTimeout(function(){$("#panelFase'.md5($cdItLicencaFase).'").remove();},1000);</script>';

		}else{
			$notificacao->viewSwalNotificacao("Essa não!", "Não foi possível remover a fase. Por favor entre contato com o administrador do sistema", "single", "error");
		}

		break;

		case 'string':

		$notificacao->viewSwalNotificacao("Essa não!", "Não foi possível remover a fase. Por favor entre contato com o administrador do sistema [".$snDeleta."]", "single", "error");

		break;

		default:
		# code...
		break;
	}

	break;

	case 'O':
	$notificacao->viewSwalNotificacao("Hmm...", "Você não pode remover esta fase, pois ela já foi concluída.", "single", "warning");
	break;

	case 'C':

	$notificacao->viewSwalNotificacao("Hmm...", "Você não pode remover esta fase, pois ela foi cancelada.", "single", "warning");

	break;

	default:

	break;
}

?>