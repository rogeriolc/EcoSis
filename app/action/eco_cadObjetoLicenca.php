<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$dsObjetoLicenca 	= strtoupper($_POST['dsObjetoLicenca']);
$cdCatObjetoLicenca	= base64_decode($_POST['cdCatObjetoLicenca']);
$snPedirProtocolo	= $_POST['snPedirProtocolo'];
$cdFaseObj			= isset($_POST['cdFase']) ? $_POST['cdFase'] : null;
$cdEmpresa 			= $_SESSION['cdEmpresa'];

$objLicenca   		= new cObjetoLicenca(null, $dsObjetoLicenca, $cdCatObjetoLicenca, $snPedirProtocolo);
$notificacao  		= new Notificacao;

$snObjetoLicencaExistente = $objLicenca->returnCodigo();

if($snObjetoLicencaExistente == 0){

	$snObjetoLicenca = $objLicenca->Cadastro();

	switch ($snObjetoLicenca) {

		case $snObjetoLicenca > 0:

		$notificacao->viewSwalNotificacao("Sucesso!", "Objeto da licença cadastrado com sucesso.", "single", "success");

		$c = 0;

		foreach ($cdFaseObj as $fase) {
			$cdFase = base64_decode($fase);

			$fase = new cFaseObjeto($cdFase, null, $c);
			$fase->setCdObjetoLicenca($snObjetoLicenca);
			$fase->addFaseObjeto();

			$c++;
		}

		break;

		case 'N':
		$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um objeto de licença com este nome. Por favor, escolha outro.", "single", "warning");
		break;

		case 'E':
		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cadastrar o objeto da licença. Por favor, contate o administrador do sistema.", "single", "error");
		break;

		default:
		$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorretor. Por favor, contate o adminsitrador do sistema.", "single", "error");
		break;
	}

}else{

	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um objeto de licença com este nome. Por favor, escolha outro.", "single", "warning");

}
?>