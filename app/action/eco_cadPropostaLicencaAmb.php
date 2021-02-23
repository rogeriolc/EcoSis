<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$notificacao 			= new Notificacao();

$cdEmpresa 				= $_SESSION['cdEmpresa'];

$cdCliente 				= base64_decode($_POST['cdCliente']);
$cdEmpreendimento 		= base64_decode($_POST['cdEmpreendimento']);
// $cdTpLicencaAmbiental	= base64_decode($_POST['cdTpLicenca']);
$dtPrevConclusaoLicenca = implode("-",array_reverse(explode("/",$_POST['dtPrevConclusaoLicenca'])));
$dsObservacao 			= $_POST['dsObservacao'];
$tpStatus 				= isset($_POST['tpStatus']) ? $_POST['tpStatus'] : 'E';

//arrays das atividades
$cdTpAtividade 	 		= $_POST['cdTpAtividade'];
$dtPrevEntregaAtividade = $_POST['dtPrevEntregaAtividade'];
$nrProposta 			= isset($_POST['nrProposta']) ? $_POST['nrProposta'] : null;
$tpAtividade 			= $_POST['tpAtividade'];
$vlAtividadeNegociado 	= $_POST['vlAtividadeNegociado'];
$vlAtividadePago 		= $_POST['vlAtividadePago'];
//$anexo 					= $_FILES['anexo'];

//serviço
$nrProcesso 			= null;
$cdOrgaoLicenciado 		= null;

//valor

$vlNegociado			= 0;
$vlPago 				= 0;

//soma so valores dos objetos e armazena o total nas variaveis vlNegociado e vlPago
foreach ($vlAtividadeNegociado as $valor) {
	$valor = str_replace(".","",$valor);
	$valor = str_replace(",",".",$valor);

	$vlNegociado += $valor;

}

foreach ($vlAtividadePago as $valor) {
	$valor = str_replace(".","",$valor);
	$valor = str_replace(",",".",$valor);

	$vlPago += $valor;

}


$prop = new cPropostaLicencaAmb(null, $cdCliente, $cdEmpreendimento, $tpStatus, $vlNegociado, $vlPago, $dtPrevConclusaoLicenca, $dsObservacao);


$cdPropostaLicenca = $prop->Cadastro();

if($cdPropostaLicenca > 0){

	if ($tpStatus == 'F') {
		//Altera o status da proposta para fechado
		$prop->Fechar();

		/**
		* Gera o serviço para a proposta
		*/

		$serv = new cServico;
		$serv->setCdCliente($cdCliente);
		$serv->setCdEmpreendimento($cdEmpreendimento);
		$serv->setNrProcesso($nrProcesso);
		$serv->setCdOrgaoLicenciado($cdOrgaoLicenciado);

		$cdServico = $serv->Cadastrar();
		$serv->setCdServico($cdServico);

		//vincula a proposta ao serviço criado
		$serv->vincularProposta($cdPropostaLicenca);

	}

	$prop->setCdPropostaLicenca($cdPropostaLicenca);

	//Depois da proposta cadastrada, vamos inserir as atividades da mesma
	$qtdAtividades = count($cdTpAtividade);

	for ($i=0; $i < $qtdAtividades; $i++) {

		//retirar máscara do valor
		$valorNegociado = str_replace(".","",$vlAtividadeNegociado[$i]);
		$valorNegociado = str_replace(",",".",$valorNegociado);

		$valorPago 		= str_replace(".","",$vlAtividadePago[$i]);
		$valorPago 		= str_replace(",",".",$valorPago);


		//decodifica o código da atividade
		$cdTpAtividadeProposta 	= $cdTpAtividade[$i];
		$dtPrevEntrega 		   	= implode("-",array_reverse(explode("/",$dtPrevEntregaAtividade[$i])));
		$tipoAtividade 		   	= $tpAtividade[$i];

		// $proposta = $nrProposta[$i];
		$proposta 				= null;

		$cdItProposta 			= $prop->CadastroItem($cdTpAtividadeProposta, $tipoAtividade, $dtPrevEntrega, $proposta, $valorNegociado, $valorPago);

		if ($tpStatus == 'F' && !is_null($cdServico)) {

			//ainda não irá exitir usuário responsável
			$cdUsuario 		= null;
			$dsAtividade	= "Esta atividade foi gerada através da proposta.";

			//adicionar os itens do serviço
			$atv 			= new cAtividade(null, $cdServico, $dsAtividade, $tipoAtividade, $cdUsuario, $dtPrevEntrega, $cdTpAtividadeProposta);
			$cdAtividade	= $atv->Cadastrar();

			$atv->setCdAtividade($cdAtividade);

			//adiciona as fases da atividade se houver
			$atv->addFaseAtividade();

			//vincula a atividade ao item de proposta
			$snVinculo = $atv->vincularItemProposta($cdItProposta);
		}

	}

	$notificacao->viewSwalNotificacao("Sucesso!", "Proposta salva com sucesso!", "single", "success");
}else{

	switch (gettype($cdPropostaLicenca)) {
		case 'string':
			$notificacao->viewSwalNotificacao("Erro", "Não foi possível concluir a proposta erro: [".$cdPropostaLicenca."]. Por favor, contate o adminstrador do sistema.", "single", "error");
			break;

		default:
			$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminstrador do sistema.", "single", "error");
			break;
	}

}



// $cdCliente				= $_POST['cdCliente'];
// $cdEmpreendimento		= $_POST['cdEmpreendimento'];
// $cdTpLicencaAmbiental	= $_POST['cdTpLicencaAmbiental'];
// $tpStatus				= strtoupper($_POST['tpStatus']);
// $vlParcial				= $_POST['vlParcial'];
// $dsObservacao			= strtoupper($_POST['dsObservacao']);

// $propLicenca = new cTpLicencaAmbiental(null, $dsTpLicencaAmbiental);
// $notificacao  = new Notificacao;

// $snCadPropLicenca = $propLicenca->Cadastro();

// switch ($snCadPropLicenca) {

// 	case 'S':
// 	$notificacao->viewSwalNotificacao("Sucesso!", "Orgão Licenciado cadastrado com sucesso.", "single", "success");
// 	break;

// 	case 'N':
// 	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um orgão licenciado com este nome. Por favor, escolha outro.", "single", "warning");
// 	break;

// 	case 'E':
// 	$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cadastrar o orgão licenciado. Por favor, contate o administrador do sistema.", "single", "error");
// 	break;

// 	default:
// 	$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorretor. Por favor, contate o adminsitrador do sistema.", "single", "error");
// 	break;
// }

?>