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

$notificacao		= new Notificacao();

$cdEmpresa 			= $_SESSION['cdEmpresa'];


/************************/
/* Cadastrar a Proposta */
/************************/

$proposta = new cProposta(null, "E", $_POST['vl_proposta'], $dtPrevConclusao, $dsObservacao);

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

