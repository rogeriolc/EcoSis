<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

$token   = (isset($_POST['token'])) ? $_POST['token'] : null;
$approve = (isset($_POST['approve'])) ? boolval($_POST['approve']) : null;

$notificacao = new Notificacao;

if (is_null($token) || is_null($approve)) {
	$notificacao->viewSwalNotificacao("Essa não!", "Não foi possível realizar esta operação. Por favor entre contato com o administrador do sistema", "single", "error");
} else {
	$updated = cProposta::approvePropostaByToken($token, $approve);

	if ($updated > 0) {
		$notificacao->viewSwalNotificacao("Perfeito!", "Já estamos cientes da sua confirmação, assim que possível entraremos em contato com você.", "single", "success");
	} else {
		$notificacao->viewSwalNotificacao("Essa não!", "Não foi possível alterar o status de aprovação da sua proposta. Por favor entre contato com o conosco assim que possível", "single", "error");
	}
}
