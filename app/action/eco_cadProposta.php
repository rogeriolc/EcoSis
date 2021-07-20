<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

// header('Access-Control-Allow-Origin: *');
// header('Content-Type: application/json');

// var_dump($_POST);

$clientes 			= $_POST["clientes"];
$dtPrevConclusao 	= implode("-",array_reverse(explode("/",$_POST['dt_prev_entrega'])));
$dsObservacao 		= $_POST['ds_observacao'];
$fechar 			= $_POST['fechar'];
$aprovacaoCliente	= $_POST['aprovacao_cliente'];
$status				= ($aprovacaoCliente == 'true') ? 'A' : 'E';

$notificacao		= new Notificacao();

$cdEmpresa 			= $_SESSION['cdEmpresa'];


/************************/
/* Cadastrar a Proposta */
/************************/

$proposta = new cProposta(null, $status, $_POST['vl_proposta'], $dtPrevConclusao, $dsObservacao);

$cdProposta = $proposta->cadastrar();

//se a proposta for cadastrada
if ($cdProposta) {
	$proposta->setCdPropostaLicenca($cdProposta);

	//Fechar Proposta?
	if ($fechar == 'true') {
		//Altera o status da proposta para fechado
		$proposta->fechar();
	}

	if (count($clientes) > 0) {
		foreach ($clientes as $key => $cliente) {

			$cdCliente 			= $cliente['cd_cliente'];
			$cdEmpreendimento 	= $cliente['cd_empreendimento'];
			$cdClienteVinculo 	= isset($cliente['cd_cliente_vinculo']) ? $cliente['cd_cliente_vinculo'] : null;

			$cdPropostaCliente 	= cProposta::adicionarCliente($cdProposta, $cdCliente, $cdEmpreendimento, $cdClienteVinculo);

			//se o cliente for inserido na proposta
			if ($cdPropostaCliente > 0) {

				if ($fechar == "true") {
					$nrProcesso 		= null;
					$cdOrgaoLicenciado 	= null;

					$serv = new cServico;
					$serv->setCdCliente($cdCliente);
					$serv->setCdEmpreendimento($cdEmpreendimento);
					$serv->setNrProcesso($nrProcesso);
					$serv->setCdOrgaoLicenciado($cdOrgaoLicenciado);
					$serv->setDtPrevConclusao($dtPrevConclusao);

					$cdServico = $serv->Cadastrar();
					$serv->setCdServico($cdServico);

					$serv->vincularProposta($cdPropostaCliente);
				}

				//percorre os itens de assessoria e consultoria
				if (isset($cliente['itens']['assessoria'])) {
					foreach ($cliente['itens']['assessoria'] as $akey => $item) {

						$dtPrevEntrega = null;
						$dtPrevEntrega = implode("-",array_reverse(explode("/",$item["dt_prev_entrega"])));

						$cdPropostaAtividade = cProposta::adicionarItem($cdPropostaCliente, $item["cd_tp_atividade"], $item["valor"], $item["desconto"], $dtPrevEntrega);

						if ($fechar == 'true' && !is_null($cdServico)) {

							//ainda não irá exitir usuário responsável
							$cdUsuarioResponsavel = null;
							$dsAtividade	= "Esta atividade foi gerada através da proposta.";

							$tpAtividade	= new cTpAtividade($item["cd_tp_atividade"]);
							$dadosTpAtividade = $tpAtividade->Dados();

							if ($tpAtividade->cdCatTpAtividade == 1) {
								$categoriaAtividade = 'A';
							} else if ($tpAtividade->cdCatTpAtividade == 2) {
								$categoriaAtividade = 'C';
							} else {
								$categoriaAtividade = null;
							}

							//adicionar os itens do serviço
							$atv 			= new cAtividade(null, $cdServico, $dsAtividade, $categoriaAtividade, $cdUsuarioResponsavel, $dtPrevEntrega, $item["cd_tp_atividade"]);
							$cdAtividade	= $atv->Cadastrar();

							$atv->setCdAtividade($cdAtividade);

							//adiciona as fases da atividade se houver
							$atv->addFaseAtividade();

							//vincula a atividade ao item de proposta
							$snVinculo = $atv->vincularItemProposta($cdPropostaAtividade);
						}
					}
				}

				if (isset($cliente['itens']['consultoria'])) {
					foreach ($cliente['itens']['consultoria'] as $ckey => $item) {

						$dtPrevEntrega = null;
						$dtPrevEntrega = implode("-",array_reverse(explode("/",$item["dt_prev_entrega"])));

						$cdPropostaAtividade = cProposta::adicionarItem($cdPropostaCliente, $item["cd_tp_atividade"], $item["valor"], $item["desconto"], $dtPrevEntrega);

						if ($fechar == 'true' && !is_null($cdServico)) {

							//ainda não irá exitir usuário responsável
							$cdUsuarioResponsavel = null;
							$dsAtividade	= "Esta atividade foi gerada através da proposta.";

							$tpAtividade	= new cTpAtividade($item["cd_tp_atividade"]);
							$tpAtividade->Dados();

							if ($tpAtividade->cdCatTpAtividade == 1) {
								$categoriaAtividade = 'A';
							} else if ($tpAtividade->cdCatTpAtividade == 2) {
								$categoriaAtividade = 'C';
							} else {
								$categoriaAtividade = null;
							}

							//adicionar os itens do serviço
							$atv 			= new cAtividade(null, $cdServico, $dsAtividade, $categoriaAtividade, $cdUsuarioResponsavel, $dtPrevEntrega, $item["cd_tp_atividade"]);
							$cdAtividade	= $atv->Cadastrar();

							$atv->setCdAtividade($cdAtividade);

							//adiciona as fases da atividade se houver
							$atv->addFaseAtividade();

							//vincula a atividade ao item de proposta
							$snVinculo = $atv->vincularItemProposta($cdPropostaAtividade);
						}
					}
				}
			}
		}
	}

	//Adicionar os documentos ao serviço
	if ($fechar == 'true') {
		// Recupera os itens da proposta
		$itens = cProposta::getItensProposta($cdProposta);

		// Se tiver algum, percorre e adiciona ao servico
		if (count($itens) > 0) {
			$arrayDocumentos = array();

			// Percorre estes itens
			foreach ($itens as $key => $item) {

				// Pega todos os documentos do item
				$documento 		= new cTpDocumento();
				$documento->setCdTpAtividade($item['cd_tp_atividade']);
				$documentosItens = $documento->getByCdTpAtividade();

				if (count($documentosItens) > 0) {
					
					foreach ($documentosItens as $key => $tpDoc) {

						$serv 			= new cServico($item['cd_servico']);
						$snCadSolDoc  	= $serv->solicitarDocumento($tpDoc->cd_tp_documento);
						$arrayDocumentos[] = $tpDoc->ds_tp_documento;
					}
				}
			}

			// Enviar e-mail para o cliente solicitando o documento
			$servico = new cServico();
			$servicos = $servico->getServicosByProposta($cdProposta);

			foreach ($servicos as $key => $serv) {

				$servico->setCdServico($serv->cd_servico);
				$servico->Dados();

				$cliente 				= new cCliente($servico->cdCliente);
				$cliente->Dados();

				$empreendimento = new cEmpreendimento($serv->cd_empreendimento);

				$dsAssunto				= 'Solicitação de Documentos';
				$dsTitulo				= 'Calango | Solicitação de Documentos';
				$dsCorpoMensagem		= '
				<h3 align="center">Solicitação de Documentos</h3>
				<br>
				<p align="justify">Olá '.$servico->nmCliente.'! Para que possamos iniciar o seu serviço, precisamos que você nos passe alguns documentos:</p>
				<br>
				<p>Empreendimento: <strong>'.$servico->nmEmpreendimento.'</strong></p>

				<br>
				<p align="justify">Segue abaixo a lista de documentos necessários:</p>

				<ul>';

				foreach ($arrayDocumentos as $doc) {
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

				if ($cliente->dsEmail) {
					$snEnviaEmail = $notif->enviaEmail('ecosis@calango.eng.br', 'Calango Meio Ambiente', $cliente->dsEmail, $servico->nmCliente, $dsAssunto, $dsTitulo, $dsCorpoMensagem, $dsMensagemFinal);
				}

				if ($cliente->dsEmailRepresentante) {
					$snEnviaEmail = $notif->enviaEmail('ecosis@calango.eng.br', 'Calango Meio Ambiente', $cliente->dsEmailRepresentante, $servico->nmCliente, $dsAssunto, $dsTitulo, $dsCorpoMensagem, $dsMensagemFinal);
				}

			}
		} else {
			echo "Nenhum documento encontrado para envio";
		}
	}

	//Envia e-mail solicitando aprovação do cliente
	if ($aprovacaoCliente == 'true') {

		$clientesProposta = cProposta::getClientesByProposta($cdProposta);

		foreach ($clientesProposta as $key => $clienteProposta) {

			// Gerar um token para identificar o cliente
			$token = cSeguranca::geraToken();
			cProposta::setTokenAprovacao($clienteProposta->cd_proposta_cliente, $token);

			$cliente 				= new cCliente($clienteProposta->cd_cliente);
			$cliente->Dados();

			$dsAssunto				= 'Aprovação de Proposta';
			$dsTitulo				= 'Calango | Aprovação de Proposta';
			$dsCorpoMensagem		= '
			<h3 align="center">Aprovação de Proposta</h3>
			<br>
			<p align="justify">Olá '.$clienteProposta->nm_cliente.', estamos prestes a iniciar um novo serviço com você através do empreendimento <strong>'.$clienteProposta->nm_empreendimento.'</strong> e estamos muito felizes!</p>

			<br>
			<p align="justify">Para que possamos seguir precisamos que você aprove nossa proposta. Clique no botão abaixo para visualiza-lá e iniciar o processo de aprovação:</p>
			
			<p align="center">
				<a href="http://'. $_SERVER['SERVER_NAME'] .'/ecosis/aprovacao-proposta?t='.$token.'" style="padding: 20px; text-align: center; background: #673AB7; border: none; color: #fff;">Clique aqui abrir a proposta</a>
			</p>

			<br>
			<br>
			<p>Obrigado!</p>
			';
			$dsMensagemFinal		= '';

			if ($cliente->dsEmail) {
				$snEnviaEmail = $notificacao->enviaEmail('ecosis@calango.eng.br', 'Calango Meio Ambiente', $cliente->dsEmail, $clienteProposta->nm_cliente, $dsAssunto, $dsTitulo, $dsCorpoMensagem, $dsMensagemFinal);
			}
		}
	}

}

switch (gettype($cdProposta)) {

	case 'integer':
	$dsMensagem = ($fechar == 'true') ? "Proposta e serviço gerados com sucesso." : "Proposta gerada com sucesso.";
	$notificacao->viewSwalNotificacao("Sucesso!", "Proposta cadastrada com sucesso.", "single", "success");
	break;

	default:
	$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao gerar a proposta. Por favor, contate o adminsitrador do sistema.", "single", "error");
	break;
}

