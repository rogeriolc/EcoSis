<?php
session_start();

include '../conf/showErros.php';
include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$cdUsuarioSessao = $_SESSION['cdUsuario'];

$cdServico 	 	= isset($_POST['cdServico']) 		? $_POST['cdServico'] 	 	: null;
$tpAtividadeMsg = isset($_POST['tpAtividadeMsg']) 	? $_POST['tpAtividadeMsg']  : null;
$cdAtividade 	= isset($_POST['cdAtividade']) 		? $_POST['cdAtividade']  : null;
$dsComentario 	= isset($_POST['dsComentario']) 	? $_POST['dsComentario'] 	: null;
$snAtividade 	= isset($_POST['snAtividade']) 		? $_POST['snAtividade']  	: null;
$tpAtividade 	= isset($_POST['tpAtividade']) 		? $_POST['tpAtividade']  	: null;

$notificacao 	= new Notificacao;

if(!is_null($snAtividade)){

	//instancia a classe de atividade com os dados inseridos no formulário
	$atv = new cAtividade(null, $cdServico, $dsComentario, $tpAtividade, $cdUsuarioSessao, null);

	$snCadAtividade = $atv->Cadastrar();

	var_dump($snCadAtividade);

	switch (gettype($snCadAtividade)) {
		case 'string':
		$notificacao->viewSwalNotificacao("Erro", $snCadAtividade, "single", "error");
		exit();
		break;

		case 'integer':
		$cdAtividade = $snCadAtividade;
		break;

		default:
				# code...
		break;
	}

	$snAtividade = 'S';

}

if($cdServico > 0){

	//instancia a classe de atividade
	$serv 		 	= new cServico($cdServico);
	$snComentario  	= $serv->Comentar($cdAtividade, $dsComentario, $snAtividade);

	var_dump($snComentario);

	(is_int($snComentario)) ? $notificacao->viewSwalNotificacao("Sucesso!", "Comentario enviado com sucesso.", "single", "success") : $notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao reabrir a atividade. Por favor, contate o administrador do sistema [".$snComentario."].", "single", "error");

}else{
	 $notificacao->viewSwalNotificacao("Erro", "Atividade não foi selecionada", "single", "error");
}
?>