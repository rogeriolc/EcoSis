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

$cdCliente 				= $_POST['cdCliente'];
$nmCliente 			 	= isset($_POST['nmCliente']) ? strtoupper($_POST['nmCliente']) : null;
$tpPessoa 			 	= isset($_POST['tpPessoa']) ? strtoupper($_POST['tpPessoa']) : null;
$nrRg 				 	= isset($_POST['nrRg']) ? str_replace('-','',str_replace('.','', $_POST['nrRg'])) : null;
$cpfCnpj 			 	= isset($_POST['cpfCnpj']) ? str_replace('/','',str_replace('-','',str_replace('.','', $_POST['cpfCnpj']))) : null;
$nrInscricaoEstadual 	= isset($_POST['nrInscricaoEstadual']) ? $_POST['nrInscricaoEstadual'] : null;
$nrInscricaoMunicipal 	= isset($_POST['nrInscricaoMunicipal']) ? $_POST['nrInscricaoMunicipal'] : null;
$dsCtf 					= isset($_POST['dsCtf']) ? $_POST['dsCtf'] : null;
$nrTelefone 		 	= isset($_POST['nrTelefone']) ? $_POST['nrTelefone'] : null;
$nrCelular 			 	= isset($_POST['nrCelular']) ? $_POST['nrCelular'] : null;
$dsEmail 			 	= isset($_POST['dsEmail']) ? strtoupper($_POST['dsEmail']) : null;
$dsSite 				= isset($_POST['dsSite']) ? $_POST['dsSite'] : null;
$cdCep 				 	= isset($_POST['cdCep']) ? $_POST['cdCep'] : null;
$dsEndereco 		 	= isset($_POST['dsEndereco']) ? strtoupper($_POST['dsEndereco']): null;
$nmBairro 			 	= isset($_POST['nmBairro']) ? strtoupper($_POST['nmBairro']): null;
$nmCidade 			 	= isset($_POST['nmCidade']) ? strtoupper($_POST['nmCidade']): null;
$uf 				 	= isset($_POST['uf']) ? strtoupper($_POST['uf']): null;

$snAtivo 				= strtoupper($_POST['snAtivo']);
$cdEmpresa 				= $_SESSION['cdEmpresa'];

/*DADOS DO REPRESENTANTE*/

$nmRepresentanteLegal 				= isset($_POST['nmRepresentanteLegal']) ? $_POST['nmRepresentanteLegal']: null;
$tpPessoaRepresentante 				= isset($_POST['tpPessoaRepresentante']) ? $_POST['tpPessoaRepresentante']: null;
$nrRgRepresentante 					= isset($_POST['nrRgRepresentante']) ? str_replace('-','',str_replace('.','', $_POST['nrRgRepresentante'])) : null;
$cpfCnpjRepresentante 				= isset($_POST['cpfCnpjRepresentante']) ? str_replace('/','',str_replace('-','',str_replace('.','', $_POST['cpfCnpjRepresentante']))) : null;
$nrInscricaoEstadualRepresentante 	= isset($_POST['nrInscricaoEstadualRepresentante']) ? $_POST['nrInscricaoEstadualRepresentante']: null;
$nrInscricaoMunicipalRepresentante 	= isset($_POST['nrInscricaoMunicipalRepresentante']) ? $_POST['nrInscricaoMunicipalRepresentante']: null;
$dsCtfRepresentante 				= isset($_POST['dsCtfRepresentante']) ? $_POST['dsCtfRepresentante']: null;
$nrTelefoneRepresentante 			= isset($_POST['nrTelefoneRepresentante']) ? $_POST['nrTelefoneRepresentante']: null;
$nrCelularRepresentante 			= isset($_POST['nrCelularRepresentante']) ? $_POST['nrCelularRepresentante']: null;
$dsEmailRepresentante 				= isset($_POST['dsEmailRepresentante']) ? $_POST['dsEmailRepresentante']: null;
$dsSiteRepresentante 				= isset($_POST['dsSiteRepresentante']) ? $_POST['dsSiteRepresentante']: null;
$cdCepRepresentante 				= isset($_POST['cdCepRepresentante']) ? $_POST['cdCepRepresentante']: null;
$dsEnderecoRepresentante 			= isset($_POST['dsEnderecoRepresentante']) ? $_POST['dsEnderecoRepresentante']: null;
$nmBairroRepresentante 				= isset($_POST['nmBairroRepresentante']) ? $_POST['nmBairroRepresentante']: null;
$nmCidadeRepresentante 				= isset($_POST['nmCidadeRepresentante']) ? $_POST['nmCidadeRepresentante']: null;
$ufRepresentante 					= isset($_POST['ufRepresentante']) ? $_POST['ufRepresentante']: null;

$cliente 			= new cCliente($cdCliente, $nmCliente, $tpPessoa, $nrRg, $cpfCnpj, $nrInscricaoEstadual, $nrInscricaoMunicipal, $dsCtf, $dsEmail, $dsSite, $dsEndereco, $nmBairro, $nmCidade, $cdCep, $uf, $nrTelefone, $nrCelular, $snAtivo);
$notificacao 		= new Notificacao;

$snClienteExistente = $cliente->returnCodigo($cdCliente);

if($snClienteExistente == 0){

	$snAlterCliente = $cliente->alterCliente();

	switch ($snAlterCliente) {
		case 'S':
		$notificacao->viewSwalNotificacao("Sucesso!", "Cliente alterado com sucesso.", "single", "success");

		$representante = new cRepresentante($cdCliente, $nmRepresentanteLegal, $tpPessoaRepresentante, $nrRgRepresentante, $cpfCnpjRepresentante, $nrInscricaoEstadualRepresentante, $nrInscricaoMunicipalRepresentante, $dsCtfRepresentante, $dsEmailRepresentante, $dsSiteRepresentante, $dsEnderecoRepresentante, $nmBairroRepresentante, $nmCidadeRepresentante, $cdCepRepresentante, $ufRepresentante, $nrTelefoneRepresentante, $nrCelularRepresentante);

		$alterR = $representante->Alterar();

		var_dump($alterR);


		break;

		case 'N':
		$notificacao->viewSwalNotificacao("Sem alterações", "Não há dados para alterar.", "single", "info");

		$representante = new cRepresentante($cdCliente, $nmRepresentanteLegal, $tpPessoaRepresentante, $nrRgRepresentante, $cpfCnpjRepresentante, $nrInscricaoEstadualRepresentante, $nrInscricaoMunicipalRepresentante, $dsCtfRepresentante, $dsEmailRepresentante, $dsSiteRepresentante, $dsEnderecoRepresentante, $nmBairroRepresentante, $nmCidadeRepresentante, $cdCepRepresentante, $ufRepresentante, $nrTelefoneRepresentante, $nrCelularRepresentante);

		$alterR = $representante->Alterar();

		var_dump($alterR);
		break;

		case 'E':
		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao alterar os dados do cliente. Por favor, contate o administrador do sistema.", "single", "error");
		break;

		default:
		$notificacao->viewSwalNotificacao("Erro", "Parâmetro de notificação incorreto. Por favor, contate o adminsitrador do sistema.", "single", "error");
		break;
	}

}else{

	$notificacao->viewSwalNotificacao("Encontramos alguma coisa...", "Já existe um cliente com este CPF/CNPJ. Por favor, escolha outro.", "single", "warning");

}
?>