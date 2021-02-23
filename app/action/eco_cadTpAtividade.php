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

$dsTpAtividade 		= strtoupper($_POST['dsTpAtividade']);
$cdCatTpAtividade 	= isset($_POST['cdCatTpAtividade']) ? base64_decode($_POST['cdCatTpAtividade']) : null;
$cdFaseAtv			= isset($_POST['cdFase']) ? $_POST['cdFase'] : null;
$cdEmpresa 			= $_SESSION['cdEmpresa'];

$tpAtividade		= new cTpAtividade(null, $dsTpAtividade);
$tpAtividade->setCdCatTpAtividade($cdCatTpAtividade);
$notificacao 		= new Notificacao;

$snAreaExistente = $tpAtividade->returnCodigo();

if($snAreaExistente == 0){

	$snCadAtividade = $tpAtividade->Cadastro();

	var_dump($snCadAtividade);

	switch (gettype($snCadAtividade)) {

		case 'integer':

		$notificacao->viewSwalNotificacao("Sucesso!", "Tipo de atividade cadastrado com sucesso.", "single", "success");

		$c = 0;

		foreach ($cdFaseAtv as $fase) {
			$cdFase = base64_decode($fase);

			$fase = new cFaseAtividade($cdFase, null, $c);
			$fase->setCdFaseAtividade($snCadAtividade);
			$fase->addFaseAtividade();

			$c++;
		}

		break;

		case 'string':
		($snCadAtividade == 'N') ? $notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um tipo de atividade com este nome. Por favor, escolha outro.", "single", "warning") : $notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cadastrar o tipo de atividade. Por favor, contate o administrador do sistema.", "single", "error");
		break;

		default:
			$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
		break;
	}

}else{

	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um tipo de atividade com este nome. Por favor, escolha outro.", "single", "warning");

}
?>