<?php
session_start();

include '../conf/showErros.php';
include '../conf/autoLoad.php';

cSeguranca::validaSessao();

//18 - Permissao para concluir andamento
$cdPermissao 			= 18;
$autorizado 			= cPermissao::validarPermissao($cdPermissao);

if (!$autorizado) {
	exit();
}

$cdAndamento = isset($_POST['cdAndamento']) ? $_POST['cdAndamento'] : null;
$cdAtividade = isset($_POST['cdAtividade']) ? $_POST['cdAtividade'] : null;
$dsStatus 	 = 'O';

$notificacao = new Notificacao;

//instancia a classe de atividade
$atv 		 = new cAtividade($cdAtividade);
$atv->setCdItAtividade($cdAndamento);
$atv->setDsStatus($dsStatus);

$snConcluido = $atv->MovItAtividade();

($snConcluido) ? $notificacao->viewSwalNotificacao("Sucesso!", "Andamento concluído com sucesso.", "single", "success") : $notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao concluir a andamento. Por favor, contate o administrador do sistema [".$snConcluido."].", "single", "error");
?>