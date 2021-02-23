<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

//13 - Permissao para alterar cadastros simples
$cdPermissao 		= 13;
$autorizado 		= cPermissao::validarPermissao($cdPermissao);

if (!$autorizado) {
	exit();
}

$cdObjetoLicenca 	= $_POST['cdObjetoLicenca'];
$dsObjetoLicenca 	= strtoupper($_POST['dsObjetoLicenca']);
$cdCatObjetoLicenca	= base64_decode($_POST['cdCatObjetoLicenca']);
$snPedirProtocolo	= $_POST['snPedirProtocolo'];
$cdFaseObj			= isset($_POST['cdFase']) ? $_POST['cdFase'] : null;
$snAtivo 		  	= strtoupper($_POST['snAtivo']);
$cdEmpresa 			= $_SESSION['cdEmpresa'];

$ObjLicenca   = new cObjetoLicenca($cdObjetoLicenca, $dsObjetoLicenca, $cdCatObjetoLicenca, $snPedirProtocolo, $snAtivo);
$notificacao  = new Notificacao;

$snObjetoLicencaExistente = $ObjLicenca->returnCodigo($cdObjetoLicenca);

if($snObjetoLicencaExistente == 0){

	$snObjetoLicenca = $ObjLicenca->Alterar();

	switch ($snObjetoLicenca) {

		case 'S':
		$notificacao->viewSwalNotificacao("Sucesso!", "Objeto da licença atualzado com sucesso.", "single", "success");

		$c = 0;

		$fase = new cFaseObjeto();
		$fase->setCdObjetoLicenca($cdObjetoLicenca);
		$fase->removeFaseObjeto();

		foreach ($cdFaseObj as $fase) {
			$cdFase = base64_decode($fase);

			$fase = new cFaseObjeto($cdFase, null, $c);
			$fase->setCdObjetoLicenca($cdObjetoLicenca);
			$fase->addFaseObjeto();

			$c++;
		}

		break;

		case 'N':
		$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um objeto de licença com este nome. Por favor, escolha outro.", "single", "warning");
		break;

		case 'E':
		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao atualzado o cadastro do objeto da licença. Por favor, contate o administrador do sistema.", "single", "error");
		break;

		default:
			$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorretor. Por favor, contate o adminsitrador do sistema.", "single", "error");
		break;
	}

}else{

	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um orgão licenciado com este nome. Por favor, escolha outro.", "single", "warning");

}
?>