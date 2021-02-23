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

switch ($propostaAtual->tp_status) {

	/***********************************************************************************************************************************
	* SCRIPT PARA PROPOSTA ABERTA [VERIFICAR SCRIPT NO FECHAMENTO DA PROPOSTA ABERTA]
	************************************************************************************************************************************/

	case 'E':

		//atualiza os dados gerais da proposta
		$proposta->alterar();

		//verifica se a proposta é filha
		$cdPropostaPai = (is_null($propostaAtual->cd_proposta_pai)) ? $cdProposta : $propostaAtual->cd_proposta_pai;
		$proposta->setCdPropostaPai($cdPropostaPai);

		/*********************************************** */
		/* 	Exclusão dos clientes retirados da proposta  */
		/*********************************************** */

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

		$novasAtividades = array();

		/* Lança os itens na proposta e no serviço */
		if (count($clientes) > 0) {
			
			foreach ($clientes as $key => $cliente) {
				$cdPropostaCliente		= $cliente['cd_proposta_cliente'];
				$cdPropostaClientePai	= $cliente['cd_proposta_cliente_pai'];
				$cdCliente 				= $cliente['cd_cliente'];
				$cdEmpreendimento 		= $cliente['cd_empreendimento'];
				$cdClienteVinculo 		= isset($cliente['cd_cliente_vinculo']) ? $cliente['cd_cliente_vinculo'] : null;

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
								//atualiza os dados dele
								cProposta::atualizarItem($cdPropostaAtividade, $item["cd_tp_atividade"], $item["valor"], $dtPrevEntrega);
							} else {
								echo "cadastra item";
								//cadastra o novo item
								$cdPropostaAtividade = cProposta::adicionarItem($cdPropostaCliente, $item["cd_tp_atividade"], $item["valor"], $dtPrevEntrega);
							}

							$novasAtividades[] = $cdPropostaAtividade;
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
								cProposta::atualizarItem($cdPropostaAtividade, $item["cd_tp_atividade"], $item["valor"], $dtPrevEntrega);
							} else {
								//cadastra o novo item
								$cdPropostaAtividade = cProposta::adicionarItem($cdPropostaCliente, $item["cd_tp_atividade"], $item["valor"], $dtPrevEntrega);
							}

							$novasAtividades[] = $cdPropostaAtividade;
						}
					}

				} else {
					$cdPropostaCliente 	= cProposta::adicionarCliente($cdProposta, $cdCliente, $cdEmpreendimento, $cdClienteVinculo);

					//se o cliente for inserido na proposta
					if ($cdPropostaCliente > 0) {
						//percorre os itens de assessoria e consultoria
						if (isset($cliente['itens']['assessoria'])) {
							foreach ($cliente['itens']['assessoria'] as $akey => $item) {
								
								$dtPrevEntrega = null;
								$dtPrevEntrega = implode("-",array_reverse(explode("/",$item["dt_prev_entrega"])));

								$cdPropostaAtividade = cProposta::adicionarItem($cdPropostaCliente, $item["cd_tp_atividade"], $item["valor"], $dtPrevEntrega);
								$novasAtividades[] = $cdPropostaAtividade; 
							}
						}

						if (isset($cliente['itens']['consultoria'])) {
							foreach ($cliente['itens']['consultoria'] as $ckey => $item) {
								
								$dtPrevEntrega = null;
								$dtPrevEntrega = implode("-",array_reverse(explode("/",$item["dt_prev_entrega"])));

								$cdPropostaAtividade = cProposta::adicionarItem($cdPropostaCliente, $item["cd_tp_atividade"], $item["valor"], $dtPrevEntrega);
								$novasAtividades[] = $cdPropostaAtividade; 
							}
						}
					}
				}
			}
			
			$atividadesExcluir = array_diff(array_column($atividadesAtuais, 'cd_proposta_atividade'), $novasAtividades);

			/*********************************************** */
			/* Exclusão das atividades retiradas da proposta */
			/*********************************************** */

			foreach ($atividadesExcluir as $key => $atividade) {
				cProposta::deletarAtividade($atividade);
			}

			/*********************************************** */
			/* 			Fechamento da proposta aberta 		 */
			/*********************************************** */
			
			if ($fechar == "true") {
				//fecha a proposta
				$proposta->fechar();

				//percorre os clientes para lançar seus respectivos serviços
				foreach ($clientes as $key => $cliente) {
					$cdPropostaClientePai	= $cliente['cd_proposta_cliente_pai'];
					$cdCliente 				= $cliente["cd_cliente"];
					$cdEmpreendimento 		= $cliente["cd_empreendimento"];
					$nrProcesso 			= null;
					$cdOrgaoLicenciado 		= null;
					//PRECISO SABER SE A PROPOSTA_CLIENTE É UMA FILHA PARA DAÍ PEGAR O SERVIÇO

					//checa se a proposta é pai
					if ($cdPropostaClientePai > 0) {
						//se ela for uma proposta filha, pega pelo serviço da proposta pai
						// $cdServico = cProposta::getServico($cdProposta, $cdCliente);
						$cdServico = cProposta::getServicoByPropostaClientePai($cdPropostaClientePai);
					} else {
						//pega o serviço pelo código da proposta
						// $cdServico = cProposta::getServico($proposta->cdPropostaPai, $cdCliente);
						$cdServico = cProposta::getServicoByPropostaClientePai($cdPropostaCliente);
					}

					$serv = new cServico;
					$serv->setCdCliente($cdCliente);
					$serv->setCdEmpreendimento($cdEmpreendimento);
					$serv->setNrProcesso($nrProcesso);
					$serv->setCdOrgaoLicenciado($cdOrgaoLicenciado);
					$serv->setDtPrevConclusao($dtPrevConclusao);

					//se não retornar cod de serviço (false) cadastra o serviço
					if (!$cdServico) {

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
			}

		}

	break;

	/***********************************************************************************************************************************
	* SCRIPT PARA PROPOSTA FECHADA
	************************************************************************************************************************************/

	//se o status atual da proposta for 'F', ou seja, fechada
	case 'F':
	case 'C':

		// echo $fechar;

		//Se a proposta não tiver uma proposta pai set o proprio código como pai para ligar a nova proposta
		$cdPropostaPai = (is_null($propostaAtual->cd_proposta_pai)) ? $cdProposta : $propostaAtual->cd_proposta_pai;

		var_dump($cdPropostaPai);

		//seta o código da proposta pai
		$proposta->setCdPropostaPai($cdPropostaPai);

		$cdProposta = $proposta->cadastrar();

		echo "Nova proposta cadastrada: ". $cdProposta;

		$atividadesProposta = array();

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
					
					$cdPropostaClientePai 	= $cliente['cd_proposta_cliente'];
					$cdCliente 			 	= $cliente['cd_cliente'];
					$cdEmpreendimento 		= $cliente['cd_empreendimento'];
					$cdClienteVinculo 		= $cliente['cd_cliente_vinculo'];
					
					$cdPropostaCliente 	= cProposta::adicionarCliente($cdProposta, $cdCliente, $cdEmpreendimento, $cdClienteVinculo, $cdPropostaClientePai);

					var_dump($cdPropostaCliente);

					//se o cliente for inserido na proposta
					if ($cdPropostaCliente > 0) {

						//percorre os itens de assessoria e consultoria
						if (isset($cliente['itens']['assessoria'])) {
							foreach ($cliente['itens']['assessoria'] as $akey => $item) {
								
								$dtPrevEntrega = null;
								$dtPrevEntrega = implode("-",array_reverse(explode("/",$item["dt_prev_entrega"])));
								
								$cdPropostaAtividade = cProposta::adicionarItem($cdPropostaCliente, $item["cd_tp_atividade"], $item["valor"], $dtPrevEntrega);

								$atividadesProposta[] = $cdPropostaAtividade;
							}
						}
						
						if (isset($cliente['itens']['consultoria'])) {
							foreach ($cliente['itens']['consultoria'] as $ckey => $item) {
								
								$dtPrevEntrega = null;
								$dtPrevEntrega = implode("-",array_reverse(explode("/",$item["dt_prev_entrega"])));
								
								$cdPropostaAtividade = cProposta::adicionarItem($cdPropostaCliente, $item["cd_tp_atividade"], $item["valor"], $dtPrevEntrega);

								$atividadesProposta[] = $cdPropostaAtividade;
							}
						}
						
					}

				}

				/* ******************************************************* */
				/* Supender os atividades do serviço retiradas da proposta */
				/* ******************************************************* */
				
				// if ($fechar == 'true') {

				// 	foreach ($clientes as $key => $cliente) {
				// 		$cdPropostaCliente	= $cliente['cd_proposta_cliente'];
				// 		//pega o serviço do cliente
				// 		$cdServico 			= cProposta::getServicoByPropostaClientePai($cdPropostaCliente);

				// 		$atividadesServico 	 = cServico::getAtividadesArray($cdServico);
				// 		$suspenderAtividades = array_diff($atividadesProposta, array_column($atividadesServico, 'cd_proposta_atividade'));

				// 		foreach ($suspenderAtividades as $key => $atividade) {
				// 			echo "<p>Suspendendo Atividade: $atividade</p>";
				// 			$atv = new cAtividade;
				// 			$suspe = $atv->suspenderAtividade($atividade);
				// 		}
					
				// 	}

				// }

				/* ******************************************************* */
				/* 			Adicionar as novas atividades no serviço 	   */
				/* ******************************************************* */
			}
		}
	
	break;

	default:
		# code...
		break;
}


