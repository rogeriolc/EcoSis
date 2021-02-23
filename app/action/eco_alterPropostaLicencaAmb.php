<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$notificacao 			= new Notificacao();

$cdEmpresa 				= $_SESSION['cdEmpresa'];

$cdPropostaLicenca 		= base64_decode($_POST['cdPropostaLicenca']);
$cdCliente 				= base64_decode($_POST['cdCliente']);
$cdEmpreendimento 		= base64_decode($_POST['cdEmpreendimento']);
// $cdTpLicencaAmbiental	= base64_decode($_POST['cdTpLicenca']);
$dtPrevConclusaoLicenca = implode("-",array_reverse(explode("/",$_POST['dtPrevConclusaoLicenca'])));
$dsObservacao 			= $_POST['dsObservacao'];
$tpStatusForm			= isset($_POST['tpStatus']) ? $_POST['tpStatus'] : 'E';

//arrays das atividades
$cdItProposta 	 		= $_POST['cdItProposta'];
$cdTpAtividade 	 		= $_POST['cdTpAtividade'];
$dtPrevEntregaAtividade = $_POST['dtPrevEntregaAtividade'];
$nrProposta 			= isset($_POST['nrProposta']) ? $_POST['nrProposta'] : null;
$tpAtividade 			= $_POST['tpAtividade'];
$vlAtividadeNegociado 	= $_POST['vlAtividadeNegociado'];
$vlAtividadePago 		= $_POST['vlAtividadePago'];

$formPropostaPai 		= array();

foreach ($cdItProposta as $key => $value) {

	$propPai = cPropostaLicencaAmb::getPropostaPai($value);

	if (is_null($propPai)) {
		$formPropostaPai[] = $value;
	} else {
		$formPropostaPai[] = $propPai;
	}

}

// echo "Array dos itens:<br>";
// var_dump($cdItProposta);
// echo "<br>";

$prop = new cPropostaLicencaAmb($cdPropostaLicenca);
//Pega os dados da proposta que está sendo alterada
$prop->Dados();
//armazena o status atual dela
$tpStatus = $prop->tpStatus;

//serviço
$nrProcesso 			= null;
$cdOrgaoLicenciado 		= null;

switch ($tpStatus) {

	/***********************************************************************************************************************************
	* SCRIPT PARA PROPOSTA ABERTA
	************************************************************************************************************************************/

	//se o status atual da proposta for 'E', ou seja, em andamento
	case 'E':

	// echo "A proposta está aberta.<br>";

	//Seta os novos dados da proposta
	$prop->setCdCliente($cdCliente);
	$prop->setCdEmpreendimento($cdEmpreendimento);
	$prop->setDsObservacao($dsObservacao);
	$prop->setDtPrevConclusao($dtPrevConclusaoLicenca);

	$prop->AlterarSimples();

	//se o código da proposta tiver setado
	if($cdPropostaLicenca > 0){

		//seta o código da proposta gerada
		$prop->setCdPropostaLicenca($cdPropostaLicenca);

		//se for clicado fechar no formulário
		if ($tpStatusForm == 'F') {

			// echo "A proposta foi fechada.<br>";

			//fecha a proposta
			$prop->Fechar();

			/******************************************************************************************************
			* (!) INSERIR AQUI O CÓDIGO PARA GERAR O SERVIÇO
			******************************************************************************************************/

			//verifica se já existe um serviço ligado a proposta

			//se a proposta pai não for nulo, pega o serviço da proposta pai
			if (!is_null($prop->cdPropostaPai)) {
				$cdServico = $prop->getServico($prop->cdPropostaPai);
			} else {
				$cdServico = $prop->getServico($cdPropostaLicenca);
			}

			$cdServico = $cdServico[0]->cd_servico;

			// echo "Serviço da proposta: ";
			// var_dump($cdServico);
			// echo "<br>";

			$serv = new cServico;
			$serv->setCdCliente($cdCliente);
			$serv->setCdEmpreendimento($cdEmpreendimento);
			$serv->setNrProcesso($nrProcesso);
			$serv->setCdOrgaoLicenciado($cdOrgaoLicenciado);

			//se não retornar cod de serviço (false) cadastra o serviço
			if (!$cdServico) {

				// echo "Cadastra o serviço.<br>";

				// echo "Cadastra serviço: <br>";

				$cdServico = $serv->Cadastrar();
				$serv->setCdServico($cdServico);

				// echo "Serviço cadastrado:$cdServico .<br>";

				// var_dump($cdServico);

				//vincula a proposta ao serviço criado
				$serv->vincularProposta($cdPropostaLicenca);
				echo "O serviço foi vinculado a proposta.<br>";

			} else {
				// echo "Não foi necessário vincular o serviço a proposta, apenas atualizado os dados.";
				// echo "Atualizar serviço: <br>";

				$serv->setCdServico($cdServico);
				//altera dos dados do serviço
				$serv->Alterar();

			}

		}


		/******************************************************************************************************
		* (!) REMOVE DO BANCO OS ITENS RETIRADOS DA PROPOSTA
		*******************************************************************************************************
		* VERIFICA SE O QUE TEM NA PROPOSTA TEM NO FORMULÁRIO DA PROPOSTA
		*******************************************************************************************************/
		$itensProposta = $prop->DadosItensProposta();

		foreach ($itensProposta as $item) {
			// echo "vamos ver se temos itens para remover.<br>";

			//pesquisa se o item existente no banco ainda consta no formulario
			$removerItem = array_search($item['cd_itproposta_licenca'], $cdItProposta);

			// var_dump($removerItem);

			//se não existir
			if ($removerItem === false) {
				// echo "Item: ".$item['cd_itproposta_licenca']." removido.<br>";
				//deleta os itens da proposta retirados no formulário do banco de dados
				$prop->removerItemProposta($item['cd_itproposta_licenca']);

				//se for fechada a proposta, suspende as atividades do serviço
				if ($tpStatusForm == 'F' && !is_null($cdServico)) {
					// echo "Suspendendo atividades.<br>";
					$atv = new cAtividade;

					$atv->suspenderAtividade($item['cd_itproposta_licenca']);
					$atv->suspenderAtividade($item['cd_itproposta_pai']);

				}
			}
		}

		/*******************************************************************************************************
		* VERIFICA SE O QUE TEM NO SERVIÇO TEM NA PROPOSTA
		*******************************************************************************************************/

		//se for fechada a proposta, suspende as atividades do servilço
		if ($tpStatusForm == 'F' && !is_null($cdServico)) {
			$atv = new cAtividade;
			$atv->setCdServico($cdServico);

			$atividades = $atv->returnArrayAtividade();

			// echo "Verificando se existem atividades no serviço que foram removidas da proposta.<br>";
			// var_dump($atividades);

			foreach ($atividades as $atividade) {
				// echo "Percorrendo as atividades do serviço:<br>";
				// echo "Atividade: ".$atividade['cd_atividade'].", item da proposta: ".$atividade['cd_itproposta_licenca'];
				// echo "<br>";

				//pega a proposta pai do item
				$cdPropostaPaiRemocao = cPropostaLicencaAmb::getPropostaPai($atividade['cd_itproposta_licenca']);

				// echo "Proposta pai?<br>";
				// var_dump($cdPropostaPaiRemocao);

				//verifica se o retorno é nulo, se sim, assim o código da principal da proposta
				$propostaRemover = is_null($cdPropostaPaiRemocao) ? $atividade['cd_itproposta_licenca'] : $cdPropostaPaiRemocao;

				// echo "Proposta para remover: ".$propostaRemover;
				// echo "<br>";

				//verifica se existe no formulario
				$removerAtividade = array_search($propostaRemover, $formPropostaPai);

				// echo "Encontrou a proposta na atividade: ";
				// var_dump($removerAtividade);
				// echo "<br>";

				//se não existir, suspende do serviço
				if ($removerAtividade === false) {
					// echo "Suspendendo atividade da proposta: ".$propostaRemover."<br>";
					$atv->suspenderAtividade($propostaRemover);
				}

			}
		}


		$totalNegociado = 0;
		$totalPago 		= 0;
		$dataPrev		= array();

		/******************************************************************************************************
		* (!) ADICIONA NO BANCO OS ITENS INSERIDOS NA PROPOSTA E ATUALIZA OS EXISTENTES
		******************************************************************************************************/

		// echo "itens para inserir: ";
		// var_dump($cdTpAtividade);

		//verifica os itens que contem no formulário e não existem no banco para inseri-los
		foreach ($cdTpAtividade as $i => $value) {

			//retirar máscara do valor
			$valorNegociado = str_replace(".","",$vlAtividadeNegociado[$i]);
			$valorNegociado = str_replace(",",".",$valorNegociado);

			$totalNegociado += $valorNegociado;

			$valorPago 		= str_replace(".","",$vlAtividadePago[$i]);
			$valorPago 		= str_replace(",",".",$valorPago);

			$totalPago		+= $valorPago;


			//decodifica o código da atividade
			$cdTpAtividadeProposta 	= $cdTpAtividade[$i];
			$dtPrevEntrega 		   	= implode("-",array_reverse(explode("/",$dtPrevEntregaAtividade[$i])));

			$dataPrev[]				= $dtPrevEntrega;

			$tipoAtividade 		   	= $tpAtividade[$i];

			$proposta 				= null; //$nrProposta[$i];

			//se o código do item da proposta no formulário for nulo insere, ou seja, se a proposta for uma pai e ainda não tiver sido fechada
			if (is_null($cdItProposta[$i]) || empty($cdItProposta[$i])) {

				$nvItProposta = $prop->CadastroItem($cdTpAtividadeProposta, $tipoAtividade, $dtPrevEntrega, $proposta, $valorNegociado, $valorPago);

				// echo "Adiciona o item: ".$nvItProposta.", pois não existe na proposta.<br>";

				//se for clicado em fechar a proposta
				if ($tpStatusForm == 'F' && !is_null($cdServico)) {
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
					$atv->vincularItemProposta($nvItProposta);

					// echo "Adiciona o item: ".$nvItProposta." no serviço.<br>";
				}

			} else {

				$prop->AtualizarItem($cdItProposta[$i], $dtPrevEntrega, $proposta, $valorNegociado, $valorPago);

				// echo "Já existe item de proposta cadastrado.<br>";

				$atv = new cAtividade;
				$atv->setCdServico($cdServico);

			    //retorna as atividades do serviço
				$atividades = $atv->returnArrayAtividade();

			    //verifica se existe alguma atividade no serviço, se não existir, a proposta NÃO TEM NENHUMA VERSÃO FECHADA. Insere os itens da proposta como atividades
				if (count($atividades) == 0) {

					// echo "Não existem atividades no serviço para esta proposta.<br>";

					// $nvItProposta = $prop->CadastroItem($cdTpAtividadeProposta, $tipoAtividade, $dtPrevEntrega, $proposta, $valorNegociado, $valorPago);
					$dsAtividade	= "Esta atividade foi gerada através da proposta.";

        			//se for clicado em fechar a proposta
					if ($tpStatusForm == 'F' && !is_null($cdServico)) {
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
						$atv->vincularItemProposta($nvItProposta);

						// echo "Adiciona o item: ".$nvItProposta.", pois não existe no serviço.<br>";
					}

				}
				else if (count($atividades) > 0) {

					// echo "entrou na desgraça!<br>";

	            	/***********************************************************************************************************************************
	            	* A DESGRAÇA TÁ AQUI!!!!!!
	            	************************************************************************************************************************************
	            	* SE JÁ HOUVER VERSÃO FECHADA, O SCRIPT TERÁ QUE COMPARAR SE OS ITENS DA PROPOSTA QUE ESTÃO NO FORMULÁRIO JÁ ESTÃO INSERIDOS NO
	            	* SERVIÇO COMO ATIVIDADE. SE SIM, NÃO INSERE, SE NÃO, INSERE.
	            	************************************************************************************************************************************/

					//Pega os dados do item
	            	$itemProposta = cPropostaLicencaAmb::getItemProposta($cdItProposta[$i]);
	            	$itemProposta = $itemProposta[0];

	            	// var_dump($itemProposta);

	            	// echo '<br>';

					//verifica se existe alguma atividade com o código do item da proposta da licenca ou o item da proposta pai
	            	if (is_null($itemProposta['cd_itproposta_pai'])) {
	            		// echo "Procurando pelo item: ".$itemProposta['cd_itproposta_licenca']."<br>";
	            		$existe = array_search($itemProposta['cd_itproposta_licenca'], array_column($atividades, 'cd_itproposta_licenca'));
	            	} else {
	            		// echo "Procurando pelo item pai: ".$itemProposta['cd_itproposta_pai']."<br>";
	            		$existe = array_search($itemProposta['cd_itproposta_pai'], array_column($atividades, 'cd_itproposta_licenca'));
	            	}

	            	var_dump($existe);
	            	echo "<br>";

	            	switch (gettype($existe)) {
						//se encontrar uma atividade com o código da proposta, atualiza os dados
	            		case 'integer':
	            		// echo "encontrou! atualiza os dados.<br>";
	            		$prop->AtualizarItem($cdItProposta[$i], $dtPrevEntrega, $proposta, $valorNegociado, $valorPago);
	            		break;

						//se não encontrar insere um novo, uma nova atividade e vincula um ao outro
	            		default:
	            		// echo "não encontrou! cadastra novo item da proposta!<br>";
	            		$nvItProposta = $prop->CadastroItem($cdTpAtividadeProposta, $tipoAtividade, $dtPrevEntrega, $proposta, $valorNegociado, $valorPago);

	            		//Vincula o item da proposta atual ao item da proposta anterior (Pai)
	            		if (!empty($cdItProposta[$i])) {

	            			echo "Vincula o item da proposta a proposta pai!<br>";

	            			$cdItPropostaPai = $cdItProposta[$i];

	            			foreach ($itensProposta as $key => $itemProposta) {
	            				if ($itemProposta['cd_itproposta_licenca'] == $cdItPropostaPai && !empty($itemProposta['cd_itproposta_pai']) && !is_null($itemProposta['cd_itproposta_pai'])) {
	            					$cdItPropostaPai = $itemProposta['cd_itproposta_pai'];
	            				}
	            			}

	            			$prop->vincularItemPropostaPai($nvItProposta, $cdItPropostaPai);
	            		}

						//se for clicado em fechar a proposta
	            		if ($tpStatusForm == 'F' && !is_null($cdServico)) {
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
	            			$atv->vincularItemProposta($nvItProposta);
	            		}
	            		break;
	            	}
	            }
	        }
	    }

		/****************************************************************************************************
		* (!) Atualizar a data prevista para conclusão e os totais pago e negociado
		*****************************************************************************************************/

		$prop->AtualizarTotalNegociado($totalNegociado);
		$prop->AtualizarTotalPago($totalPago);
		// $maiorDataPrevista = max($dataPrev);
		// $prop->AtualizarTotalDtPrevista($maiorDataPrevista);



		// $novosItensProposta = $prop->DadosItensProposta();
		// $atividadesServico  = $serv->getAtividades();

		// //FAZER DIFERENCIA ENTRE OS ARRAYS. CANCELAR OS REMOVIDOS DA PROPOSTA E ADICIONAR OS NOVOS
		// $arrayItensProposta = array();
		// $arrayItensServico 	= array();

		// foreach ($novosItensProposta as $itemProposta) {
		// 	$arrayItensProposta[] = $itemProposta['cd_itproposta_licenca'];
		// }

		// foreach ($atividadesServicoas $itemServico) {
		// 	$arrayItensServico[] = $itemServico['cd_itproposta_licenca'];
		// }

		// $suspenderItens	= array_diff($arrayItensProposta, $arrayItensServico);
		// $adicionarItens = array_diff($arrayItensServico, $arrayItensProposta);

		// if (count($suspenderItens) > 0) {
		// 	foreach ($suspenderItens as $key => $value) {
		// 		$atv = new cAtividade;
		// 		$atv->suspenderAtividade($value);
		// 	}
		// }

		// if (count($adicionarItens) > 0) {
		// 	foreach ($adicionarItens as $key => $value) {
		// 		$prop->removerItemProposta($value);
		// 	}
		// }


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

	break;



	/***********************************************************************************************************************************
	* SCRIPT PARA PROPOSTA FECHADA
	************************************************************************************************************************************/


	//se o status atual da proposta for 'F', ou seja, fechada
	case 'F':
	case 'C':

	$vlNegociado = 0;
	$vlPago = 0;

	//realiza o lançamento de uma nova proposta

	//Se a proposta não tiver uma proposta pai set o proprio código como pai para ligar a nova proposta
	$cdPropostaPai = (is_null($prop->cdPropostaPai)) ? $cdPropostaLicenca : $prop->cdPropostaPai;

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

	//O status da nova proposta (filha): ANDAMENTO
	// $tpStatus = "E";

	//O status da proposta a partir deste ponto será de acordo com a variável: tpStatusForm

	//instancia o objeto
	$prop = new cPropostaLicencaAmb(null, $cdCliente, $cdEmpreendimento, $tpStatusForm, $vlNegociado, $vlPago, $dtPrevConclusaoLicenca, $dsObservacao);
	//seta o código da proposta pai
	$prop->setCdPropostaPai($cdPropostaPai);
	//cadastra a proposta
	$cdPropostaLicenca = $prop->Cadastro();

	//se cadastrar
	if($cdPropostaLicenca > 0){

		//seta o código da proposta gerada
		$prop->setCdPropostaLicenca($cdPropostaLicenca);

		//se for clicado fechar no formulário
		if ($tpStatusForm == 'F') {

			//fecha a proposta
			$prop->Fechar();

			/******************************************************************************************************
			* (!) INSERIR AQUI O CÓDIGO PARA GERAR O SERVIÇO
			******************************************************************************************************/

			//Gerar serviço
			//verifica se já existe um serviço ligado a proposta pai
			$dadosServico = $prop->getServico($cdPropostaPai);
			$cdServico	  = $dadosServico[0]->cd_servico;

			$serv = new cServico;
			$serv->setCdCliente($cdCliente);
			$serv->setCdEmpreendimento($cdEmpreendimento);
			$serv->setNrProcesso($nrProcesso);
			$serv->setCdOrgaoLicenciado($cdOrgaoLicenciado);

			//se não retornar cod de serviço (false) cadastra o serviço
			if (!$cdServico) {

				// echo "Cadastra serviço: <br>";

				$cdServico = $serv->Cadastrar();
				$serv->setCdServico($cdServico);

				//vincula a proposta ao serviço criado
				$serv->vincularProposta($cdPropostaLicenca);

			} else {
				// echo "Atualizar serviço: <br>";

				$serv->setCdServico($cdServico);
				//altera dos dados do serviço
				$serv->Alterar();
				$serv->Reabrir();

			}
		}

		/******************************************************************************************************
		* (!) REMOVE DO BANCO OS ITENS RETIRADOS DA PROPOSTA
		******************************************************************************************************/

		$propAnt = new cPropostaLicencaAmb(base64_decode($_POST['cdPropostaLicenca']));
		$itensProposta = $propAnt->DadosItensProposta();

		// var_dump($itensProposta);

		foreach ($itensProposta as $item) {

			//pesquisa se o item existente no banco ainda consta no formulario
			$removerItem = array_search($item['cd_itproposta_licenca'], $cdItProposta);

			// var_dump($removerItem);

			//se não existir
			if ($removerItem === false) {
				//deleta os itens da proposta retirados no formulário do banco de dados
				$prop->removerItemProposta($item['cd_itproposta_licenca']);

				//se for fechada a proposta, suspende as atividades do servilço
				if ($tpStatusForm == 'F' && !is_null($cdServico)) {
					$atv = new cAtividade;
					$atv->suspenderAtividade($item['cd_itproposta_licenca']);
					$atv->suspenderAtividade($item['cd_itproposta_pai']);
				}
			}

		}



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

			$proposta = $nrProposta[$i];

			$nvItProposta = $prop->CadastroItem($cdTpAtividadeProposta, $tipoAtividade, $dtPrevEntrega, $proposta, $valorNegociado, $valorPago);

			if ($tpStatusForm == 'F' && !is_null($cdServico) && empty($cdItProposta[$i])) {

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
				$atv->vincularItemProposta($nvItProposta);
			}

			//Vincula o item da proposta atual ao item da proposta anterior (Pai)
			// if ($tpStatusForm == 'F' && !is_null($cdServico) && !empty($cdItProposta[$i])) {
			if (!empty($cdItProposta[$i])) {

				$cdItPropostaPai = $cdItProposta[$i];

				foreach ($itensProposta as $key => $itemProposta) {
					if ($itemProposta['cd_itproposta_licenca'] == $cdItPropostaPai && !empty($itemProposta['cd_itproposta_pai']) && !is_null($itemProposta['cd_itproposta_pai'])) {
						$cdItPropostaPai = $itemProposta['cd_itproposta_pai'];
					}
				}

				$prop->vincularItemPropostaPai($nvItProposta, $cdItPropostaPai);
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


	break;

	default:
	# code...
	break;
}