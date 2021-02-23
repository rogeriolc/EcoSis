<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$notificacao 	= new Notificacao;

//14 - Permissao para cadastrar andamento
$cdPermissao 			= 14;
$autorizado 			= cPermissao::validarPermissao($cdPermissao);

if (!$autorizado) {
	exit();
}

$cdAtividade		= isset($_POST['cdAtividade']) 		? $_POST['cdAtividade'] : null;
$dtProtocolo 		= isset($_POST['dtProtocolo']) 		? implode("-",array_reverse(explode("/",$_POST['dtProtocolo']))) : null;
$dtPrazo 			= isset($_POST['dtPrazo']) 			? implode("-",array_reverse(explode("/",$_POST['dtPrazo']))) : null;
$cdResponsavel 		= isset($_POST['cdResponsavel']) 		? base64_decode($_POST['cdResponsavel']) : null;
$cdOrgaoLicenciador	= isset($_POST['cdOrgaoLicenciador']) 	? base64_decode($_POST['cdOrgaoLicenciador']) : null;
$cdCliente 			= isset($_POST['cdCliente']) 			? base64_decode($_POST['cdCliente']) : null;
$dsAndamento 		= isset($_POST['dsAndamento']) 			? $_POST['dsAndamento'] : null;

$atv = new cAtividade();
$atv->setCdAtividade($cdAtividade);
$atv->setDtProtocolo($dtProtocolo);
$atv->setDtPrazo($dtPrazo);
$atv->setCdResponsavel($cdResponsavel);
$atv->setDsAndamento($dsAndamento);
$atv->setCdCliente($cdCliente);
$atv->setCdOrgaoLicenciador($cdOrgaoLicenciador);

$snCad = $atv->CadastrarItAtividade();

switch (gettype($snCad)) {
	case 'integer':
	if($snCad > 0){
		$notificacao->viewSwalNotificacao("Sucesso!", "Andamento cadatrado com sucesso.", "single", "success");

		//Dados da Atividade
		$atv->Dados();
		$cdServico 			= $atv->getCdServico();
		$cdTpAtividade		= $atv->getCdTpAtividade();
		//================================================

		//Dados do item da atividade (Objeto)
		$tpAtv 				= new cTpAtividade($cdTpAtividade);
		$tpAtv->Dados();
		$dsTpAtividade 		= $tpAtv->getDsTpAtividade();
		//================================================

		//Dados do servico
		$serv 				= new cServico($cdServico);
		$serv->Dados();
		$nrProcesso 		= $serv->getNrProcesso();
		$nmEmpreendimento 	= $serv->getNmEmpreendimento();
		//================================================

		//Dados do usuário destinatário
		$user = new cUsuario();
		$user->setCdUsuario($cdResponsavel);
		$user->Dados();
		$dsEmailDestino			= $user->getDsEmail();
		$nmColaboradorDestino	= $user->getNmPessoa();
		//================================================

		//Dados do email

		$dsAssunto			= 'Novo Andamento';
		$dsTitulo			= 'Calango | Novo Andamento';
		$dsCorpoMensagem	= '';

		$dsCorpoMensagem	.= '<table width="100%">';

		$dsCorpoMensagem	.=  '<tr><th align="left" width="150">Proc.:</th><td>'.$nrProcesso.'</td></tr>';
		// $dsCorpoMensagem	.= '<br/>';
		$dsCorpoMensagem	.=  '<tr><th align="left" width="150">Empreendimento:</th><td> '.$nmEmpreendimento.'</td></tr>';
		// $dsCorpoMensagem	.= '<br/>';
		$dsCorpoMensagem	.=  '<tr><th align="left" width="150">Objeto:</th><td> '.$dsTpAtividade.'</td></tr>';
		// $dsCorpoMensagem	.= '<br/>';
		$dsCorpoMensagem	.= '</table>';

		$dsCorpoMensagem	.= '<h4>Você é responsável por um andamento:</h4>';
		$dsCorpoMensagem	.= '<p style="text-align: justify;">'.$dsAndamento.'</p>';

		$dsMensagemFinal	= '<h4>Calango Meio Ambiente</h4>';

		$snEnviaEmail = $notificacao->enviaEmail('info@ecosis.boeckmann.com.br', 'Calango Meio Ambiente', $dsEmailDestino, $nmColaboradorDestino, $dsAssunto, $dsTitulo, $dsCorpoMensagem, $dsMensagemFinal);

	}else{
		$notificacao->viewSwalNotificacao("Erro!", "Ocorreu um erro, contate o administrador do sistema.", "single", "error");
	}
	break;

	case 'string':
	$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cadastrar o andamento. Por favor, contate o administrador do sistema. [".$snCad."]", "single", "error");
	break;

	default:
		# code...
	break;
}
?>