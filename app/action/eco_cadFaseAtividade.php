<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

//1 - Permissao para cadastros simples
$cdPermissao = 1;
$autorizado = cPermissao::validarPermissao($cdPermissao);

if (!$autorizado) {
	exit();
}

$dsFaseAtividade 	 	= strtoupper($_POST['dsFaseAtividade']);
$cdCatFaseAtividade 	= base64_decode($_POST['cdCatFaseAtividade']);
$cdEmpresa 		 		= $_SESSION['cdEmpresa'];

$faseAtividade   	 	= new cFaseAtividade(null, $dsFaseAtividade, $cdCatFaseAtividade);
$notificacao  	 		= new Notificacao;

$snFaseAtividadeExistente = $faseAtividade->returnCodigo();

if($snFaseAtividadeExistente == 0){

	$snFaseAtividade = $faseAtividade->Cadastro();

	switch ($snFaseAtividade) {

		case 'S':
		$notificacao->viewSwalNotificacao("Sucesso!", "Fase da atividade cadastrada com sucesso.", "single", "success");
		break;

		case 'N':
		$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um fase da atividade com este nome. Por favor, escolha outro.", "single", "warning");
		break;

		case 'E':
		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cadastrar o fase da atividade. Por favor, contate o administrador do sistema.", "single", "error");
		break;

		default:
			$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
		break;
	}

}else{

	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um fase de atividade com este nome. Por favor, escolha outro.", "single", "warning");

}
?>