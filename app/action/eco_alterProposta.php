<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$cdProposta			= $_POST["cd_proposta"];
$clientes 			= $_POST["clientes"];
$dtPrevConclusao 	= implode("-",array_reverse(explode("/",$_POST['dt_prev_entrega'])));
$dsObservacao 		= $_POST['ds_observacao'];
$fechar 			= $_POST['fechar'];
$valor				= $_POST['vl_proposta'];

$notificacao 		= new Notificacao();
$cdEmpresa 			= $_SESSION['cdEmpresa'];

$proposta 			= new cProposta($cdProposta, "E", $valor, $dtPrevConclusao, $dsObservacao);

$propostaAtual		= cProposta::getData($cdProposta);
$propostaAtual		= $propostaAtual[0];

$clientesAtuais		= cProposta::getClientesByProposta($cdProposta);
$atividadesAtuais	= cProposta::getItensProposta($cdProposta);

if ($propostaAtual->tp_status == 'C' || $propostaAtual->tp_status == 'F') {
	//abre a proposta
	$proposta->abrir();
	$proposta->novaVersao();

	$cdPropostaOrigem  = $cdProposta;
	$cdPropostaDestino = $proposta->guardarVersao();

	$proposta->copiarClientes($cdPropostaOrigem, $cdPropostaDestino);
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
		// $cdPropostaClientePai	= $cliente['cd_proposta_cliente_pai'];
		$cdCliente 				= $cliente['cd_cliente'];
		$cdEmpreendimento 		= $cliente['cd_empreendimento'];
		$cdClienteVinculo 		= isset($cliente['cd_cliente_vinculo']) ? $cliente['cd_cliente_vinculo'] : null;

		if ($fechar == "true") {

			$cdServico 	= cProposta::getServicoByPropostaCliente($cdPropostaCliente);

			var_dump($cdPropostaCliente);
			var_dump($cdServico);

			// echo "<hr>";

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

