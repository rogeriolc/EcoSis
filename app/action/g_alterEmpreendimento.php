<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

//13 - Permissao para alterar cadastros simples
$cdPermissao 			= 13;
$autorizado 			= cPermissao::validarPermissao($cdPermissao);

if (!$autorizado) {
	exit();
}

$cdEmpreendimento 		= $_POST['cdEmpreendimento'];
$nmEmpreendimento 		= strtoupper($_POST['nmEmpreendimento']);
$cdCliente 				= base64_decode($_POST['cdCliente']);
$cdPorteEmpreendimento 	= base64_decode($_POST['cdPorteEmpreendimento']);
$cdTipografia 			= base64_decode($_POST['cdTipografia']);
$cdPotencialPoluidor 	= base64_decode($_POST['cdPotencialPoluidor']);
$dsArea 				= null; //$_POST['dsArea'];
$cdCep 					= $_POST['cdCep'];
$dsEndereco 			= strtoupper($_POST['dsEndereco']);
$nmBairro 				= strtoupper($_POST['nmBairro']);
$nmCidade 				= strtoupper($_POST['nmCidade']);
$uf 					= strtoupper($_POST['uf']);
$dsEmpreendimento 		= strtoupper($_POST['dsEmpreendimento']);
$cdEmpresa 				= $_SESSION['cdEmpresa'];
$snAtivo 		  		= strtoupper($_POST['snAtivo']);

//Areas
$cdEmpreendimentoArea	= $_POST['cdEmpreendimentoArea'];
$cdTpArea 				= $_POST['cdTpArea'];
$vlArea 				= $_POST['vlArea'];


//Revs
$cdTpEmpreendimento 	= isset($_POST['cdTpEmpreendimento']) ? base64_decode($_POST['cdTpEmpreendimento']) : null;
$qtdDormitorios 		= $_POST['qtdDormitorios'];
$qtdBanheiros 			= $_POST['qtdBanheiros'];
$qtdUnidades 			= $_POST['qtdUnidades'];
$dsTamanhoUnidade 		= $_POST['dsTamanhoUnidade'];
$cdAbastecimento 		= isset($_POST['cdAbastecimento']) ? $_POST['cdAbastecimento'] : null;
$dsAbastecimento 		= $_POST['dsAbastecimento'];
$snOutorgaAbastecimento = isset($_POST['snOutorgaAbastecimento']) ? 'S' : 'N';
$cdTratamento 			= isset($_POST['cdTratamento']) ? $_POST['cdTratamento'] : null;
$dsTratamentoAfluentes 	= $_POST['dsTratamentoAfluentes'];
$snOutorgaTratamento 	= isset($_POST['snOutorgaTratamento']) ? 'S' : 'N';
$snTerraplanagem 		= isset($_POST['snTerraplanagem']) ? 'S' : 'N';
$snSuspensaoErradicacao = isset($_POST['snSuspensaoErradicacao']) ? 'S' : 'N';

//Tipos de Revisão
$cdEmpreendimentoRevisao    = $_POST['cdEmpreendimentoRevisao'];
$cdTpRevisao                = $_POST['cdTpRevisao'];
$vlRevisao                  = $_POST['vlRevisao'];

//contatos
$cdEmpreendimentoContato = $_POST['cdEmpreendimentoContato'];
$nmContato 				 = $_POST['nmContato'];
$nrTelefone 			 = $_POST['nrTelefone'];
$nmCargo 				 = $_POST['nmCargo'];
$nmDepartamento 		 = $_POST['nmDepartamento'];

$oldEmpreendimento 		= new cEmpreendimento($cdEmpreendimento);
$oldEmpreendimento->Dados();

$saveNameHistory 		= ($oldEmpreendimento->nmEmpreendimento != $nmEmpreendimento);

$empreendimento 		= new cEmpreendimento($cdEmpreendimento, $nmEmpreendimento, $cdCliente, $cdPorteEmpreendimento, $cdTipografia, $cdPotencialPoluidor, $dsArea, $cdCep, $dsEndereco, $nmBairro, $nmCidade, $uf, $dsEmpreendimento, null, $snAtivo);
$notificacao 			= new Notificacao;

$snEmpreendimentoExistente 	= $empreendimento->returnCodigo($cdEmpreendimento);
$arrayAreas 				= $empreendimento->returnArrayAreas();
$contatos 					= cEmpreendimento::getContatos($cdEmpreendimento);
$tpsRevisao					= cEmpreendimento::getTiposRevisao($cdEmpreendimento);

$arrayContatos 				= array();
$arrayTpRevisao				= array();

foreach ($contatos as $key => $contato) {
	$arrayContatos[] = $contato->cd_empreendimento_contato;
}

foreach ($tpsRevisao as $key => $tpRevisao) {
	$arrayTpRevisao[] = $tpRevisao->cd_empreendimento_revisao;
}

// var_dump($arrayContatos);
// var_dump($cdTpEmpreendimento);
// var_dump($arrayAreas);
// var_dump($cdTpRevisao);

// echo count($arrayAreas);
// echo '<br>';
// echo count($cdEmpreendimentoArea);

if ($snEmpreendimentoExistente == 0) {

	$snAlterEmpreendimento = $empreendimento->Alterar();

	switch ($snAlterEmpreendimento) {

		case 'S':
		case 'N':
			// Insere o historico de alteracao do nome do empreendimento
			if ($saveNameHistory) {
				cEmpreendimento::setHistoryName($cdEmpreendimento, $oldEmpreendimento->nmEmpreendimento);
			}

			$notificacao->viewSwalNotificacao("Sucesso!", "Empreendimento alterado com sucesso.", "single", "success");
			// $notificacao->viewSwalNotificacao("Sem alterações", "Não há dados para alterar.", "single", "info");

			if (isset($cdTpEmpreendimento) && !is_null($cdTpEmpreendimento) && !empty($cdTpEmpreendimento)) {

				$empreendimento->setCdTpEmpreendimento($cdTpEmpreendimento);
				$empreendimento->setQtdDormitorios($qtdDormitorios);
				$empreendimento->setQtdBanheiros($qtdBanheiros);
				$empreendimento->setQtdUnidades($qtdUnidades);
				$empreendimento->setDsTamanhoUnidade($dsTamanhoUnidade);
				$empreendimento->setDsAbastecimento($dsAbastecimento);
				$empreendimento->setSnOutorgaAbastecimento($snOutorgaAbastecimento);
				$empreendimento->setDsTratamentoAfluentes($dsTratamentoAfluentes);
				$empreendimento->setSnOutorgaTratamentoAfluente($snOutorgaTratamento);
				$empreendimento->setSnTerraplanagem($snTerraplanagem);
				$empreendimento->setSnSuspensaoErradicacao($snSuspensaoErradicacao);

				$snCadRev = $empreendimento->cadRevisao();

				switch (gettype($snCadRev)) {
					case 'integer':

						if ($snCadRev > 0) {

							$empreendimento->setCdRevEmpreendimento($snCadRev);

							foreach ($cdAbastecimento as $key => $value) {

								$cod = base64_decode($value);

								$empreendimento->setCdAbastecimento($cod);
								$empreendimento->cadRevisaoAbastecimento();
							}

							foreach ($cdTratamento as $key => $value) {

								$cod = base64_decode($value);

								$empreendimento->setCdTratamentoAfluente($cod);
								$empreendimento->cadRevisaoTratamento();
							}
						} else {

							$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cadastrar a revisão do empreendimento. Por favor, contate o administrador do sistema.", "single", "error");
						}

						break;

					case 'string':
						$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cadastrar a revisão do empreendimento. Por favor, contate o administrador do sistema.", "single", "error");
						break;

					default:
						# code...
						break;
				}
			}

			break;

		case 'E':
			$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao alterar os dados do empreendimento. Por favor, contate o administrador do sistema.", "single", "error");
			break;

		default:
			$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorretor. Por favor, contate o adminsitrador do sistema.", "single", "error");
			break;
	}

	/*======================================================*/
	/* ADICIONA AS NOVAS ÁREAS								*/
	/*======================================================*/

	$arrayCdAreasInseridas = array();

	if (count($arrayAreas) > 0) {
		//se todas as areas forem removidas do formulario é atribuido um array vazio
		$cdEmpreendimentoArea = (count($cdEmpreendimentoArea) == 0) ? array() : $cdEmpreendimentoArea;

		foreach ($arrayAreas as $key => $value) {
			$arrayCdAreasInseridas[] = $value["cd_empreendimento_area"];
		}
	}

	for ($i = 0; $i < count($cdTpArea); $i++) {

		//Insere as atividades
		(empty($cdEmpreendimentoArea[$i])) ? $empreendimento->AdicionarArea($cdTpArea[$i], $vlArea[$i]) : $empreendimento->AtualizarArea($cdEmpreendimentoArea[$i], $cdTpArea[$i], $vlArea[$i]);
	}

	/*======================================================*/
	/* EXCLUI AS ÁREAS RETIRADAS							*/
	/*======================================================*/

	//<te amo>
	$areaExcluir = array_diff($arrayCdAreasInseridas, $cdEmpreendimentoArea);

	if (count($areaExcluir) > 0) {
		try {
			foreach ($areaExcluir as $key => $value) {
				$empreendimento->ExcluirArea($value);
			}
		} catch (Exception $e) {
			$dsError = $e->getMessage();
			$notificacao->viewSwalNotificacao("Erro", $dsError, "single", "error");
		}
	}


	/*======================================================*/
	/* ADICIONA AS NOVOS TIPOS DE REVISAO   				*/
	/*======================================================*/

	$arrayCdTpRevInseridos = array();


	if (count($tpsRevisao) > 0) {
		//se todas as areas forem removidas do formulario é atribuido um array vazio
		$cdEmpreendimentoRevisao = (count($cdEmpreendimentoRevisao) == 0) ? array() : $cdEmpreendimentoRevisao;

		foreach ($tpsRevisao as $key => $value) {
			$arrayCdTpRevInseridos[] = $value->cd_empreendimento_revisao;
		}
	}

	for ($i = 0; $i < count($cdTpRevisao); $i++) {


		//var_dump($cdTpRevisao[$i]);

		//Insere as atividades
		$response = (empty($cdEmpreendimentoRevisao[$i])) ? $empreendimento->addTipoRevisao($cdTpRevisao[$i], $vlRevisao[$i]) : $empreendimento->updTipoRevisao($cdEmpreendimentoRevisao[$i], $cdTpRevisao[$i], $vlRevisao[$i]);

		//var_dump($response);

	}

	/*======================================================*/
	/* EXCLUI TIPOS DE REVISAO RETIRADOS					*/
	/*======================================================*/

	//<te amo>
	$tpRevExcluir = array_diff($arrayCdTpRevInseridos, $cdEmpreendimentoRevisao);

	if (count($tpRevExcluir) > 0) {
		try {
			foreach ($tpRevExcluir as $key => $value) {
				$empreendimento->removerTipoRevisao($value);
			}
		} catch (Exception $e) {
			$dsError = $e->getMessage();
			$notificacao->viewSwalNotificacao("Erro", $dsError, "single", "error");
		}
	}


	/*======================================================*/
	/* ADICIONA OS NOVOS CONTATOS 							*/
	/*======================================================*/

	$arrayCdContatosInseridos = array();

	if (count($cdEmpreendimentoContato) > 0) {
		$cdEmpreendimentoContato = (count($cdEmpreendimentoContato) == 0) ? array() : $cdEmpreendimentoContato;

		foreach ($arrayContatos as $key => $value) {
			$arrayCdContatosInseridos[] = $value;
		}
	}


	for ($i = 0; $i < count($nmContato); $i++) {

		$response = (empty($cdEmpreendimentoContato[$i])) ? $empreendimento->addContato($nmContato[$i], $nrTelefone[$i], $nmCargo[$i], $nmDepartamento[$i]) : $empreendimento->updContato($cdEmpreendimentoContato[$i], $nmContato[$i], $nrTelefone[$i], $nmCargo[$i], $nmDepartamento[$i]);
	}

	// var_dump($arrayCdContatosInseridos);
	// echo '<br>';
	// var_dump($cdEmpreendimentoContato);
	// echo '<br>';

	$contatoExcluir = array_diff($arrayCdContatosInseridos, $cdEmpreendimentoContato);

	// var_dump($contatoExcluir);

	if (count($contatoExcluir) > 0) {
		try {
			foreach ($contatoExcluir as $key => $value) {
				$empreendimento->removerContato($value);
			}
		} catch (Exception $e) {
			$dsError = $e->getMessage();
			$notificacao->viewSwalNotificacao("Erro", $dsError, "single", "error");
		}
	}
} else {

	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um empreendimento com este nome. Por favor, escolha outro.", "single", "warning");
}
