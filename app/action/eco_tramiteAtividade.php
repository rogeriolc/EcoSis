<?php
session_start();

include '../conf/showErros.php';
include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$cdAtividade = isset($_POST['cdAtividade']) ? $_POST['cdAtividade'] : null;

$notificacao = new Notificacao;

//instancia a classe de atividade
$atv 		 = new cAtividade($cdAtividade);
$snReaberto  = $atv->EmTramite();

(is_int($snReaberto)) ? $notificacao->viewSwalNotificacao("Sucesso!", "Atividade em trâmite.", "single", "success") : $notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao colocar a atividade em trâmite. Por favor, contate o administrador do sistema [".$snReaberto."].", "single", "error");
?>