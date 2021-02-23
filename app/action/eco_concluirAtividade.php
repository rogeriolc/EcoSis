<?php
session_start();

include '../conf/showErros.php';
include '../conf/autoLoad.php';

cSeguranca::validaSessao();

//10 - Permissao para alterar os dados serviço
$cdPermissao 		= 10;
$autorizado 		= cPermissao::validarPermissao($cdPermissao);

if (!$autorizado) {
	exit();
}

$cdAtividade = isset($_POST['cdAtividade']) ? $_POST['cdAtividade'] : null;

$notificacao = new Notificacao;

//instancia a classe de atividade
$atv 		 = new cAtividade($cdAtividade);
$snConcluido = $atv->Concluir();

(is_int($snConcluido)) ? $notificacao->viewSwalNotificacao("Sucesso!", "Atividade concluída com sucesso.", "single", "success") : $notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao concluir a atividade. Por favor, contate o administrador do sistema [".$snConcluido."].", "single", "error");
?>