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
$snExcluido  = $atv->Excluir();

(is_int($snExcluido)) ? $notificacao->viewSwalNotificacao("Sucesso!", "Atividade excluída com sucesso.", "single", "success") : $notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao excluir a atividade. Por favor, contate o administrador do sistema [".$snExcluido."].", "single", "error");
?>