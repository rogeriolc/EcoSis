<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$cdProposta			= $_POST["cd_proposta"];
$clientes 			= $_POST["clientes"];
$dtPrevConclusao 	= implode("-",array_reverse(explode("/",$_POST['dt_prev_entrega'])));
$dsObservacao 		= $_POST['ds_observacao'];
$fechar 			= $_POST['fechar'];
$aprovacaoCliente	= $_POST['aprovacao_cliente'];
$status				= ($aprovacaoCliente == 'true') ? 'A' : 'E';
$valor				= $_POST['vl_proposta'];
$nrProtocolo		= $_POST['nr_protocolo'];
$nrAlteracao		= $_POST['nr_alteracao'];
$valor				= $_POST['vl_proposta'];

$notificacao 		= new Notificacao();
$cdEmpresa 			= $_SESSION['cdEmpresa'];

$proposta 			= new cProposta($cdProposta, $status, $valor, $dtPrevConclusao, $dsObservacao);

$propostaAtual		= cProposta::getData($cdProposta);
$propostaAtual		= $propostaAtual[0];

$clientesAtuais		= cProposta::getClientesByProposta($cdProposta);
$atividadesAtuais	= cProposta::getItensProposta($cdProposta);

$proposta->setNrProtocolo($propostaAtual->nr_protocolo);
$proposta->setNrAlteracao($propostaAtual->nr_alteracao);

if ($propostaAtual->tp_status == 'C' || $propostaAtual->tp_status == 'F') {
	//abre a proposta
	$proposta->abrir();
	$proposta->novaVersao();

	$cdPropostaOrigem  = $cdProposta;
	$cdPropostaDestino = $proposta->guardarVersao();

	$proposta->copiarClientes($cdPropostaOrigem, $cdPropostaDestino);
}

if ($nrProtocolo != $propostaAtual->nr_protocolo || $nrAlteracao != $propostaAtual->nr_alteracao) {
	$proposta->setNrProtocolo($nrProtocolo);
	$proposta->setNrAlteracao($nrAlteracao);
}

//atualiza os dados gerais da proposta
$proposta->alterar();

//verifica se a proposta é filha
$cdPropostaPai = (is_null($propostaAtual->cd_proposta_pai)) ? $cdProposta : $propostaAtual->cd_proposta_pai;
$proposta->setCdPropostaPai($cdPropostaPai);

/*********************************************** */
/* 	Exclusão dos clientes retirados da proposta  */
/*********************************************** */

if (count($clientesAtuais) > 0) {

	foreach ($clientesAtuais as $key => $clienteAtual) {

		$existeCliente = array_filter(
			$clientes,
			function ($e) use (&$clienteAtual) {
				if (isset($e['cd_proposta_cliente'])) {
					return $e['cd_proposta_cliente'] == $clienteAtual->cd_proposta_cliente;
				}
			}
		);
		//se não encontrar o item do formulário no banco, exclui o cliente e as atividades dele
		if (!$existeCliente) {
			cProposta::deletarCliente($clienteAtual->cd_proposta_cliente);
		}

	}

}

$novasAtividades = array();

if ($fechar == "true") {
	//fecha a proposta
	$proposta->fechar();
}

/* Lança os itens na proposta e no serviço */
if (count($clientes) > 0) {

	foreach ($clientes as $key => $cliente) {
		$cdPropostaCliente		= $cliente['cd_proposta_cliente'];
		$cdCliente 				= $cliente['cd_cliente'];
		$cdEmpreendimento 		= $cliente['cd_empreendimento'];
		$cdClienteVinculo 		= isset($cliente['cd_cliente_vinculo']) ? $cliente['cd_cliente_vinculo'] : null;

		if ($fechar == "true") {

			$cdServico 	= cProposta::getServicoByPropostaCliente($cdPropostaCliente);

			$serv = new cServico;
			$serv->setCdCliente($cdCliente);
			$serv->setCdEmpreendimento($cdEmpreendimento);
			$serv->setNrProcesso(null);
			$serv->setCdOrgaoLicenciado(null);
			$serv->setDtPrevConclusao($dtPrevConclusao);

			//se não retornar cod de serviço (false) cadastra o serviço
			if (!$cdServico && $cdPropostaCliente > 0) {
				$cdServico = $serv->Cadastrar();
				$serv->setCdServico($cdServico);

				//vincula a proposta ao serviço criado
				$serv->vincularProposta($cdPropostaCliente);

				// Adicionar documentos do itens do serviço
			} else {
				$serv->setCdServico($cdServico);
				//altera dos dados do serviço
				$serv->Alterar();
			}

			//pega as atividades do servico
			$atividadesServico 	 = cServico::getAtividadesArray($cdServico);
		}

		/**************************************** */
		/* Atualização das atividades da proposta */
		/**************************************** */

		//se já houver cliente já inserido anteriormente na proposta
		if ($cdPropostaCliente > 0) {
			//atualiza os dados do cliente na proposta
			cProposta::atualizarCliente($cdPropostaCliente, $cdCliente, $cdEmpreendimento, $cdClienteVinculo);

			//percorre os itens de assessoria e consultoria
			if (isset($cliente['itens']['assessoria'])) {
				foreach ($cliente['itens']['assessoria'] as $akey => $item) {

					$cdPropostaAtividade = $item["cd_proposta_atividade"];
					$dtPrevEntrega = null;
					$dtPrevEntrega = implode("-",array_reverse(explode("/",$item["dt_prev_entrega"])));

					//se este item já estiver cadastrado na proposta
					if (intval($cdPropostaAtividade) > 0) {
						// echo "atualiza item";
						//atualiza os dados dele
						cProposta::atualizarItem($cdPropostaAtividade, $item["cd_tp_atividade"], $item["valor"], $item["desconto"], $dtPrevEntrega);
					} else {
						// echo "cadastra item";
						//cadastra o novo item
						$cdPropostaAtividade = cProposta::adicionarItem($cdPropostaCliente, $item["cd_tp_atividade"], $item["valor"], $item["desconto"], $dtPrevEntrega);
					}

					$novasAtividades[] = $cdPropostaAtividade;

					/* Lança o item na proposta se esta estiver fechada */
					if ($fechar == "true") {
						$cdAtividade 	= cAtividade::getAtividadeByAtividadeProposta($cdPropostaAtividade);

						$cdTpAtividade 	= $item["cd_tp_atividade"];
						$tpAtividade 	= $item["tp_atividade"];
						$dtPrevEntrega 	= $item["dt_prev_entrega"];

						//ainda não irá exitir usuário responsável
						$cdUsuarioResponsavel = null;
						$dsAtividade	= "Esta atividade foi gerada através da proposta.";

						//adicionar os itens do serviço
						$atv 			= new cAtividade(null, $cdServico, $dsAtividade, $tpAtividade, $cdUsuarioResponsavel, $dtPrevEntrega, $cdTpAtividade);

						if (intval($cdAtividade) > 0) {

						} else {
							$cdAtividade = $atv->Cadastrar();
							$atv->setCdAtividade($cdAtividade);
							//adiciona as fases da atividade se houver
							$atv->addFaseAtividade();

							//vincula a atividade ao item de proposta
							$atv->vincularItemProposta($cdPropostaAtividade);
						}

						//vincula a atividade ao item de proposta
						$atv->vincularItemProposta($cdPropostaAtividade);
					}
				}
			}

			if (isset($cliente['itens']['consultoria'])) {
				foreach ($cliente['itens']['consultoria'] as $ckey => $item) {

					$cdPropostaAtividade = $item["cd_proposta_atividade"];
					$dtPrevEntrega = null;
					$dtPrevEntrega = implode("-",array_reverse(explode("/",$item["dt_prev_entrega"])));

					//se este item já estiver cadastrado na proposta
					if (intval($cdPropostaAtividade) > 0) {
						//atualiza os dados dele
						cProposta::atualizarItem($cdPropostaAtividade, $item["cd_tp_atividade"], $item["valor"], $item["desconto"], $dtPrevEntrega);
					} else {
						//cadastra o novo item
						$cdPropostaAtividade = cProposta::adicionarItem($cdPropostaCliente, $item["cd_tp_atividade"], $item["valor"], $item["desconto"], $dtPrevEntrega);
					}

					$novasAtividades[] = $cdPropostaAtividade;


					/* Lança o item na proposta se esta estiver fechada */
					if ($fechar == "true") {
						$cdAtividade = cAtividade::getAtividadeByAtividadeProposta($cdPropostaAtividade);

						$cdTpAtividade 	= $item["cd_tp_atividade"];
						$tpAtividade 	= $item["tp_atividade"];
						$dtPrevEntrega 	= $item["dt_prev_entrega"];

						//ainda não irá exitir usuário responsável
						$cdUsuarioResponsavel 		= null;
						$dsAtividade	= "Esta atividade foi gerada através da proposta.";

						//adicionar os itens do serviço
						$atv 			= new cAtividade(null, $cdServico, $dsAtividade, $tpAtividade, $cdUsuarioResponsavel, $dtPrevEntrega, $cdTpAtividade);

						if (intval($cdAtividade) > 0) {

						} else {
							$cdAtividade = $atv->Cadastrar();
							$atv->setCdAtividade($cdAtividade);
							//adiciona as fases da atividade se houver
							$atv->addFaseAtividade();

							//vincula a atividade ao item de proposta
							$atv->vincularItemProposta($cdPropostaAtividade);
						}
					}
				}
			}

		} else {
			$cdPropostaCliente 	= cProposta::adicionarCliente($cdProposta, $cdCliente, $cdEmpreendimento, $cdClienteVinculo);

			if ($fechar == "true") {

				$cdServico 	= cProposta::getServicoByPropostaCliente($cdPropostaCliente);

				$serv = new cServico;
				$serv->setCdCliente($cdCliente);
				$serv->setCdEmpreendimento($cdEmpreendimento);
				$serv->setNrProcesso(null);
				$serv->setCdOrgaoLicenciado(null);
				$serv->setDtPrevConclusao($dtPrevConclusao);

				//se não retornar cod de serviço (false) cadastra o serviço
				if (!$cdServico && $cdPropostaCliente > 0) {
					$cdServico = $serv->Cadastrar();
					$serv->setCdServico($cdServico);

					//vincula a proposta ao serviço criado
					$serv->vincularProposta($cdPropostaCliente);

					// Adicionar documentos do itens do serviço
				} else {
					$serv->setCdServico($cdServico);
					//altera dos dados do serviço
					$serv->Alterar();
				}

				//pega as atividades do servico
				$atividadesServico 	 = cServico::getAtividadesArray($cdServico);
			}

			//se o cliente for inserido na proposta
			if ($cdPropostaCliente > 0) {
				//percorre os itens de assessoria e consultoria
				if (isset($cliente['itens']['assessoria'])) {
					foreach ($cliente['itens']['assessoria'] as $akey => $item) {

						$dtPrevEntrega = null;
						$dtPrevEntrega = implode("-",array_reverse(explode("/",$item["dt_prev_entrega"])));

						$cdPropostaAtividade = cProposta::adicionarItem($cdPropostaCliente, $item["cd_tp_atividade"], $item["valor"], $item["desconto"], $dtPrevEntrega);
						$novasAtividades[] = $cdPropostaAtividade;

						/* Lança o item na proposta se esta estiver fechada */
						if ($fechar == "true") {
							$cdTpAtividade 	= $item["cd_tp_atividade"];
							$tpAtividade 	= $item["tp_atividade"];
							$dtPrevEntrega 	= $item["dt_prev_entrega"];

							//ainda não irá exitir usuário responsável
							$cdUsuarioResponsavel 		= null;
							$dsAtividade	= "Esta atividade foi gerada através da proposta.";

							//adicionar os itens do serviço
							$atv 			= new cAtividade(null, $cdServico, $dsAtividade, $tpAtividade, $cdUsuarioResponsavel, $dtPrevEntrega, $cdTpAtividade);
							$cdAtividade	= $atv->Cadastrar();
							$atv->setCdAtividade($cdAtividade);

							//adiciona as fases da atividade se houver
							$atv->addFaseAtividade();

							//vincula a atividade ao item de proposta
							$atv->vincularItemProposta($cdPropostaAtividade);
						}
					}
				}

				if (isset($cliente['itens']['consultoria'])) {
					foreach ($cliente['itens']['consultoria'] as $ckey => $item) {

						$dtPrevEntrega = null;
						$dtPrevEntrega = implode("-",array_reverse(explode("/",$item["dt_prev_entrega"])));

						$cdPropostaAtividade = cProposta::adicionarItem($cdPropostaCliente, $item["cd_tp_atividade"], $item["valor"], $item["desconto"], $dtPrevEntrega);
						$novasAtividades[] = $cdPropostaAtividade;

						/* Lança o item na proposta se esta estiver fechada */
						if ($fechar == "true") {
							$cdTpAtividade 	= $item["cd_tp_atividade"];
							$tpAtividade 	= $item["tp_atividade"];
							$dtPrevEntrega 	= $item["dt_prev_entrega"];

							//ainda não irá exitir usuário responsável
							$cdUsuarioResponsavel 		= null;
							$dsAtividade	= "Esta atividade foi gerada através da proposta.";

							//adicionar os itens do serviço
							$atv 			= new cAtividade(null, $cdServico, $dsAtividade, $tpAtividade, $cdUsuarioResponsavel, $dtPrevEntrega, $cdTpAtividade);
							$cdAtividade	= $atv->Cadastrar();
							$atv->setCdAtividade($cdAtividade);

							//adiciona as fases da atividade se houver
							$atv->addFaseAtividade();

							//vincula a atividade ao item de proposta
							$atv->vincularItemProposta($cdPropostaAtividade);
						}
					}
				}
			}
		}
	}

	$atividadesProposta = array();

	foreach ($atividadesAtuais as $key => $atv) {
		$atividadesProposta[] = $atv['cd_proposta_atividade'];
	}

	$atividadesExcluir = array_diff($atividadesProposta, $novasAtividades);


	/*********************************************** */
	/* Exclusão das atividades retiradas da proposta */
	/*********************************************** */

	$atv = new cAtividade;

	foreach ($atividadesExcluir as $key => $atividade) {
		cProposta::deletarAtividade($atividade);

		//Se a proposta for fechada, suspende a atividade
		if ($fechar == "true") {
			$atv->suspenderAtividade($atividade);
		}
	}

	$notificacao->viewSwalNotificacao("Sucesso!", "Proposta alterada com sucesso.", "single", "success");

}

//Adicionar os documentos ao serviço
if ($fechar == 'true') {
	// Recupera os itens da proposta
	$itens = cProposta::getItensProposta($cdProposta);

	// Pega todos os documentos do item
	$documento 		= new cTpDocumento();
	$documento->setCdTpAtividade($cdTpAtividade);
	$documentosItens = $documento->getByCdTpAtividade();

	// Se tiver algum, percorre e adiciona ao servico
	if (count($documentosItens) > 0) {

		// Percorre estes itens
		foreach ($itens as $key => $item) {
			foreach ($documentosItens as $key => $tpDoc) {
				$serv 			= new cServico($item->cd_servico);
				$snCadSolDoc  	= $serv->solicitarDocumento($tpDoc->cd_tp_documento);
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

			$empreendimento = new cEmpreendimento($serv->cdEmpreendimento);

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

			foreach ($documentosItens as $doc) {
				$dsCorpoMensagem .= '<li>'.$doc->nm_tp_documento.'</li>';
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

			// foreach ($dsEmail as $email) {
			// 	$snEnviaEmail = $notif->enviaEmail('ecosis@calango.eng.br', 'Calango Meio Ambiente', $email, $servico->nmCliente, $dsAssunto, $dsTitulo, $dsCorpoMensagem, $dsMensagemFinal);
			// }
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