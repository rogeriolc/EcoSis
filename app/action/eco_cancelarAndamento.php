<?php
session_start();

include '../conf/showErros.php';
include '../conf/autoLoad.php';

cSeguranca::validaSessao();

//9 - Permissao para cancelar andamento
$cdPermissao 			= 9;
$autorizado 			= cPermissao::validarPermissao($cdPermissao);

if (!$autorizado) {
	exit();
}

$cdAndamento = isset($_POST['cdAndamento']) ? $_POST['cdAndamento'] : null;
$cdAtividade = isset($_POST['cdAtividade']) ? $_POST['cdAtividade'] : null;
$dsStatus 	 = 'C';

$notificacao = new Notificacao;

//instancia a classe de atividade
$atv 		 = new cAtividade($cdAtividade);
$atv->setCdItAtividade($cdAndamento);
$atv->setDsStatus($dsStatus);

$snConcluido = $atv->MovItAtividade();

($snConcluido) ? $notificacao->viewSwalNotificacao("Sucesso!", "Andamento cancelado com sucesso.", "single", "success") : $notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cancelar o andamento. Por favor, contate o administrador do sistema [".$snConcluido."].", "single", "error");
?>