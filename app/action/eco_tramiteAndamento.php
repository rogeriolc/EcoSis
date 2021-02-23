<?php
session_start();

include '../conf/showErros.php';
include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$cdAndamento = isset($_POST['cdAndamento']) ? $_POST['cdAndamento'] : null;
$cdAtividade = isset($_POST['cdAtividade']) ? $_POST['cdAtividade'] : null;
$dsStatus 	 = 'T';

$notificacao = new Notificacao;

//instancia a classe de atividade
$atv 		 = new cAtividade($cdAtividade);
$atv->setCdItAtividade($cdAndamento);
$atv->setDsStatus($dsStatus);

$snConcluido = $atv->MovItAtividade();

($snConcluido) ? $notificacao->viewSwalNotificacao("Sucesso!", "Andamento colocado em trâmite com sucesso.", "single", "success") : $notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao colocar o andamento em trâmite. Por favor, contate o administrador do sistema [".$snConcluido."].", "single", "error");
?>