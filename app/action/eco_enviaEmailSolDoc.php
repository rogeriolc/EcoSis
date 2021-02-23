<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';
include '../../lib/plugins/phpmailer/class.phpmailer.php';

cSeguranca::validaSessao();

$notif 					= new Notificacao;

$cdUsuarioSessao 		= $_SESSION['cdUsuario'];
$cdServico	            = $_POST['cdServico'];
$cdAtividadeSolDoc	    = $_POST['cdAtividadeSolDoc'];
@$docsServico			= $_POST['docServico'];
@$dsEmail			    = $_POST['dsEmail'];

$servico 				= new cServico($cdServico);
$servico->Dados();

$atv    				= new cAtividade($cdAtividadeSolDoc);
$atv->Dados();

$cliente 				= new cCliente($servico->cdCliente);
$cliente->Dados();

if(count($dsEmail) == 0){
	$notif->viewSwalNotificacao("Atenção!", "Você não selecionou nenhum e-mail.","single", "warning");
	exit;
}

if(count($docsServico) == 0){
	$notif->viewSwalNotificacao("Atenção!", "Você não selecionou nenhum documento.","single", "warning");
	exit;
}

// $cdUsuarioDestinatario 	= isset($_POST['cdUsuarioDestinatario']) 	? base64_decode($_POST['cdUsuarioDestinatario']) : null;
// $dsAssunto 				= isset($_POST['dsAssunto']) 				? $_POST['dsAssunto'] 	: null;
// $dtPrazo 				= isset($_POST['dtPrazo']) 					? $_POST['dtPrazo'] 	: NULL;
// $dsMensagem 			= isset($_POST['dsMensagem']) 				? $_POST['dsMensagem'] 	: null;

// $cdAtividade			= isset($_POST['cdAtividade']) 				? $_POST['cdAtividade'] : null;

// $atv = new cAtividade($cdAtividade);
// $atv->Dados();

// $dsAtividade = $atv->getDsAtividade();

// if(is_null($cdAtividade)){
// 	$notif->viewSwalNotificacao("Essa não!", "Parâmetros incorretos.","single", "danger");
// 	exit;
// }


// $user = new cUsuario();
// $user->setCdUsuario($cdUsuarioDestinatario);
// $user->Dados();

// //dados do usuário de destino
// $dsEmailDestino			= $cliente->dsEmail;
// $nmColaboradorDestino	= $cliente->nmCliente;

// //dados do usuário da sessão
// $user->setCdUsuario($cdUsuarioSessao);
// $user->Dados();
// $nmRemetente			= $user->getNmPessoa();

$dsAssunto				= 'Solicitação de Documento';
$dsTitulo				= 'Calango | Solicitação de Documento';
$dsCorpoMensagem		= '
<h3 align="center">Solicitação de Documento</h3>
<br>
<p align="justify">Olá '.$servico->nmCliente.'!</p>
<br>
<p>Número do Processo: <strong>'.$servico->nrProcesso.'</strong></p>
<p>Empreendimento: <strong>'.$servico->nmEmpreendimento.'</strong></p>
<p>Objeto: <strong>'.strtoupper($atv->dsAtividade).'</strong></p>

<br>
<p align="justify">Informo que ainda não recebemos o(s) documento(s) abaixo:</p>

<ul>';

foreach ($docsServico as $doc) {
	$dsCorpoMensagem .= '<li>'.$doc.'</li>';
}
	
$dsCorpoMensagem .= '</ul>
<br>
<br>
<p align="justify">
Salientamos que o envio do(s) documento(s) é de extrema importância para que possamos dar prossecução ao andamento do objeto em epígrafe. 
<br>
Se já nos enviou, por favor desconsidere este e-mail.
</p>
<br>
<br>
<p>Obrigado!</p>
';
$dsMensagemFinal		= '';

foreach ($dsEmail as $email) {
    $snEnviaEmail = $notif->enviaEmail('ecosis@calango.eng.br', 'Calango Meio Ambiente', $email, $servico->nmCliente, $dsAssunto, $dsTitulo, $dsCorpoMensagem, $dsMensagemFinal);
}

?>