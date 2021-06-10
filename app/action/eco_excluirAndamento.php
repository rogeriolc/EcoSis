<?php
session_start();

include '../conf/showErros.php';
include '../conf/autoLoad.php';

cSeguranca::validaSessao();

//25 - Permissao para excluir andamento
$cdPermissao 			= 25;
$autorizado 			= cPermissao::validarPermissao($cdPermissao);

if (!$autorizado) {
	exit();
}

$cdAndamento = isset($_POST['cdAndamento']) ? $_POST['cdAndamento'] : null;

$notificacao = new Notificacao;

//instancia a classe de atividade
$atv 		 = new cAtividade();

var_dump($cdAndamento);

$snExcluido = $atv->excluirItAtividade($cdAndamento);

var_dump($snExcluido);

($snExcluido) ? $notificacao->viewSwalNotificacao("Sucesso!", "Andamento excluído com sucesso.", "single", "success") : $notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao excluir o andamento. Por favor, contate o administrador do sistema [".$snExcluido."].", "single", "error");
?>