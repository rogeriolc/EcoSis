<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

//1 - Permissao para cadastros simples
$cdPermissao 			= 1;
$autorizado 			= cPermissao::validarPermissao($cdPermissao);

if (!$autorizado) {
	exit();
}

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

//Areas
$cdTpArea 				= $_POST['cdTpArea'];
$vlArea 				= $_POST['vlArea'];

//Revs
$cdTpEmpreendimento 	= isset($_POST['cdTpEmpreendimento']) ? base64_decode($_POST['cdTpEmpreendimento']) : null;
$qtdDormitorios 		= $_POST['qtdDormitorios'];
$qtdBanheiros 			= $_POST['qtdBanheiros'];
$qtdUnidades 			= $_POST['qtdUnidades'];
$dsTamanhoUnidade 		= $_POST['dsTamanhoUnidade'];
$cdAbastecimento 		= $_POST['cdAbastecimento'];
$dsAbastecimento 		= $_POST['dsAbastecimento'];
$snOutorgaAbastecimento = isset($_POST['snOutorgaAbastecimento']) ? 1 : 0;
$cdTratamento 			= $_POST['cdTratamento'];
$dsTratamentoAfluentes 	= $_POST['dsTratamentoAfluentes'];
$snOutorgaTratamento 	= isset($_POST['snOutorgaTratamento']) ? 1 : 0;
$snTerraplanagem 		= isset($_POST['snTerraplanagem']) ? 1 : 0;
$snSuspensaoErradicacao = isset($_POST['snSuspensaoErradicacao']) ? 1 : 0;

//Tipos de Revisão
$cdEmpreendimentoRevisao    = $_POST['cdEmpreendimentoRevisao'];
$cdTpRevisao                = $_POST['cdTpRevisao'];
$vlRevisao                  = $_POST['vlRevisao'];

//contatos
$nmContato 				= $_POST['nmContato'];
$nrTelefone 			= $_POST['nrTelefone'];
$nmCargo 				= $_POST['nmCargo'];
$nmDepartamento 		= $_POST['nmDepartamento'];

// var_dump($cdTipografia);

$empreendimento 		= new cEmpreendimento(null, $nmEmpreendimento, $cdCliente, $cdPorteEmpreendimento, $cdTipografia, $cdPotencialPoluidor, $dsArea, $cdCep, $dsEndereco, $nmBairro, $nmCidade, $uf, $dsEmpreendimento, null, $snAtivo);
$notificacao 			= new Notificacao;

// Retirado por solicitacao de Lais em 29/07 para que se possa cadastrar um empreendimento com mesmo nome
// $snEmpreendimentoExistente = $empreendimento->returnCodigo();

// if($snEmpreendimentoExistente == 0){

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

	$snCadEmpreendimento = $empreendimento->Cadastro();

	switch ($snCadEmpreendimento) {

		case 'S':
		$notificacao->viewSwalNotificacao("Sucesso!", "Empreendimento cadastrado com sucesso.", "single", "success");

		$empreendimento->getCdEmpreendimento();

		//se inserido areas no formulário, percorre e insere
		if(count($cdTpArea) > 0){

			foreach ($cdTpArea as $key => $value) {
				$cdTpAreaDecode = base64_decode($value);
				$valorArea 		= strtoupper($vlArea[$key]);
				$empreendimento->AdicionarArea($cdTpAreaDecode, $valorArea);
			}

		}

		if (count($nmContato) > 0) {
			foreach ($nmContato as $key => $value) {
				$empreendimento->addContato($nmContato[$key], $nrTelefone[$key], $nmCargo[$key], $nmDepartamento[$key]);
			}
		}

		if (count($cdTpRevisao) > 0) {
			foreach ($cdTpRevisao as $key => $value) {
				$empreendimento->addTipoRevisao($cdTpRevisao[$key], $vlRevisao[$key]);
			}
		}

		if (count($cdAbastecimento) > 0) {
			foreach ($cdAbastecimento as $key => $value) {

				$cod = base64_decode($value);

				$empreendimento->setCdAbastecimento($cod);
				$empreendimento->cadAbastecimento();

			}
		}

		if (count($cdTratamento) > 0) {
			foreach ($cdTratamento as $key => $value) {

				$cod = base64_decode($value);

				$empreendimento->setCdTratamentoAfluente($cod);
				$empreendimento->cadTratamento();

			}
		}

		// if(isset($cdTpEmpreendimento) && !is_null($cdTpEmpreendimento) && !empty($cdTpEmpreendimento)){

		// 	$empreendimento->setCdTpEmpreendimento($cdTpEmpreendimento);
		// 	$empreendimento->setQtdDormitorios($qtdDormitorios);
		// 	$empreendimento->setQtdBanheiros($qtdBanheiros);
		// 	$empreendimento->setQtdUnidades($qtdUnidades);
		// 	$empreendimento->setDsTamanhoUnidade($dsTamanhoUnidade);
		// 	$empreendimento->setDsAbastecimento($dsAbastecimento);
		// 	$empreendimento->setSnOutorgaAbastecimento($snOutorgaAbastecimento);
		// 	$empreendimento->setDsTratamentoAfluentes($dsTratamentoAfluentes);
		// 	$empreendimento->setSnOutorgaTratamentoAfluente($snOutorgaTratamento);
		// 	$empreendimento->setSnTerraplanagem($snTerraplanagem);
		// 	$empreendimento->setSnSuspensaoErradicacao($snSuspensaoErradicacao);

		// 	$snCadRev = $empreendimento->cadRevisao();

		// 	switch (gettype($snCadRev)) {
		// 		case 'integer':

		// 		if($snCadRev > 0){

		// 			$empreendimento->setCdRevEmpreendimento($snCadRev);

		// 			foreach ($cdAbastecimento as $key => $value) {

		// 				$cod = base64_decode($value);

		// 				$empreendimento->setCdAbastecimento($cod);
		// 				$empreendimento->cadRevisaoAbastecimento();

		// 			}

		// 			foreach ($cdTratamento as $key => $value) {

		// 				$cod = base64_decode($value);

		// 				$empreendimento->setCdTratamentoAfluente($cod);
		// 				$empreendimento->cadRevisaoTratamento();

		// 			}


		// 		}else{

		// 			$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cadastrar a revisão do empreendimento. Por favor, contate o administrador do sistema.", "single", "error");
		// 		}

		// 		break;

		// 		case 'string':
		// 		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cadastrar a revisão do empreendimento. Por favor, contate o administrador do sistema.", "single", "error");

		// 		var_dump($snCadEmpreendimento);
		// 		break;

		// 		default:
		// 			# code...
		// 		break;
		// 	}

		// }

		break;

		case 'N':
		$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um empreendimento com este nome. Por favor, escolha outro.", "single", "warning");
		break;

		case 'E':
		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao cadastrar o empreendimento. Por favor, contate o administrador do sistema.", "single", "error");
		break;

		default:
		$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
		break;
	}

// }else{

// 	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um empreendimento com este nome. Por favor, escolha outro.", "single", "warning");

// }
?>