<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

//1 - Permissao para cadastros simples
$cdPermissao 		= 1;
$autorizado 		= cPermissao::validarPermissao($cdPermissao);

if (!$autorizado) {
	exit();
}

$dsFaseObjeto 	 = strtoupper($_POST['dsFaseObjeto']);
$cdCatFaseObjeto = base64_decode($_POST['cdCatFaseObjeto']);
$cdEmpresa 		 = $_SESSION['cdEmpresa'];

$faseObjeto   	 = new cFaseObjeto(null, $dsFaseObjeto, $cdCatFaseObjeto);
$notificacao  	 = new Notificacao;

$snFaseObjetoExistente = $faseObjeto->returnCodigo();

if($snFaseObjetoExistente == 0){

	$snFaseObjeto = $faseObjeto->Cadastro();

	switch ($snFaseObjeto) {

		case 'S':
		$notificacao->viewSwalNotificacao("Sucesso!", "Fase do objeto cadastrada com sucesso.", "single", "success");
		break;

		case 'N':
		$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um fase de objeto com este nome. Por favor, escolha outro.", "single", "warning");
		break;

		case 'E':
		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cadastrar o fase da objeto. Por favor, contate o administrador do sistema.", "single", "error");
		break;

		default:
			$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorretor. Por favor, contate o adminsitrador do sistema.", "single", "error");
		break;
	}

}else{

	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um fase de objeto com este nome. Por favor, escolha outro.", "single", "warning");

}
?>