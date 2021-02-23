<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';
include '../../lib/plugins/phpmailer/class.phpmailer.php';

cSeguranca::validaSessao();

$cdUsuarioSessao 		= $_SESSION['cdUsuario'];

$notif 					= new Notificacao;

$cdUsuarioDestinatario 	= isset($_POST['cdUsuarioDestinatario']) 	? base64_decode($_POST['cdUsuarioDestinatario']) : null;
$dsAssunto 				= isset($_POST['dsAssunto']) 				? $_POST['dsAssunto'] 	: null;
$dtPrazo 				= isset($_POST['dtPrazo']) 					? $_POST['dtPrazo'] 	: NULL;
$dsMensagem 			= isset($_POST['dsMensagem']) 				? $_POST['dsMensagem'] 	: null;
$fileAnexo				= $_FILES['fileAnexo'];

$cdAtividade			= isset($_POST['cdAtividade']) 		? $_POST['cdAtividade'] : null;

$atv = new cAtividade($cdAtividade);
$atv->Dados();

$dsAtividade = $atv->getDsAtividade();

if(is_null($cdAtividade)){
	$notif->viewSwalNotificacao("Essa não!", "Parâmetros incorretos.","single", "danger");
	exit;
}


$user = new cUsuario();
$user->setCdUsuario($cdUsuarioDestinatario);
$user->Dados();

//dados do usuário de destino
echo $dsEmailDestino			= $user->getDsEmail();
$nmColaboradorDestino	= $user->getNmPessoa();

//dados do usuário da sessão
$user->setCdUsuario($cdUsuarioSessao);
$user->Dados();
$nmRemetente			= $user->getNmPessoa();

$dsTitulo				= 'Calango | Andamento';
$dsCorpoMensagem		= '
<h3 align="center">Andamento de Atividade</h3>
<br>
<p align="justify"><strong>Descrição da atividade: '.$dsAtividade.'</strong></p>
<br>
<p align="justify">'.$dsMensagem.'</p>
<br>
<br>
<p align="justify">Esta atividade foi enviada por <strong>'.$nmRemetente.'</strong> com prazo até <strong>'.$dtPrazo.'</strong></p>
';
$dsMensagemFinal		= '<h4>Calango Meio Ambiente</h4>';

$snEnviaEmail = $notif->enviaEmail('ecosis@calango.eng.br', 'Calango Meio Ambiente', $dsEmailDestino, $nmColaboradorDestino, $dsAssunto, $dsTitulo, $dsCorpoMensagem, $dsMensagemFinal, $fileAnexo);

var_dump($snEnviaEmail);

switch (gettype($snEnviaEmail)) {
	case 'boolean':


	if($snEnviaEmail){
		$notif->viewSwalNotificacao("Sucesso!", "Email enviado!","single", "success");

		$dtProtocolo 	= date("Y-m-d");
		$cdResponsavel 	= $cdUsuarioDestinatario;
		$dsAndamento 	= "Atividade gerada por email, em: ".date('d/m/Y H:i:s').".\n".$dsMensagem."\nEsta atividade foi enviada por ".$nmRemetente." com prazo até ".$dtPrazo;

		$serv = new cAtividade();
		$serv->setCdAtividade($cdAtividade);
		$serv->setDtProtocolo($dtProtocolo);
		$serv->setCdResponsavel($cdResponsavel);
		$serv->setDsAndamento($dsAndamento);

		$snCad = $serv->CadastrarItAtividade();

	}else{
		$notif->viewSwalNotificacao("Essa não!", "Não foi possível enviar o email. Por favor, entre em contato com o admistrador do sistema.","single", "error");
	}

	break;

	default:
		# code...
	break;
}
?>