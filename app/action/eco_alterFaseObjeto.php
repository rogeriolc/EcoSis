<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

//13 - Permissao para alterar cadastros simples
$cdPermissao 	= 13;
$autorizado 	= cPermissao::validarPermissao($cdPermissao);

if (!$autorizado) {
	exit();
}

$cdFaseObjeto 	= $_POST['cdFaseObjeto'];
$dsFaseObjeto 	= strtoupper($_POST['dsFaseObjeto']);
$snAtivo 		= strtoupper($_POST['snAtivo']);
$cdEmpresa 		= $_SESSION['cdEmpresa'];

$faseObjeto 	= new cFaseObjeto($cdFaseObjeto, $dsFaseObjeto, null, $snAtivo);
$notificacao  	= new Notificacao;

$snFaseObjetoExistente = $faseObjeto->returnCodigo($cdFaseObjeto);

if($snFaseObjetoExistente == 0){

	$snFaseObjeto = $faseObjeto->Alterar();

	switch ($snFaseObjeto) {

		case 'S':
		$notificacao->viewSwalNotificacao("Sucesso!", "Fase do objeto atualizado com sucesso.", "single", "success");
		break;

		case 'N':
		$notificacao->viewSwalNotificacao("Sem alterações", "Não há dados para alterar.", "single", "info");
		break;

		case 'E':
		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao atualizar o cadastro da fase do objeto. Por favor, contate o administrador do sistema.", "single", "error");
		break;

		default:
			$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
		break;
	}

}else{

	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe uma fase de objeto com este nome. Por favor, escolha outro.", "single", "warning");

}
?>