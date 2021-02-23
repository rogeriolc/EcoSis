<?php
session_start();

include '../conf/showErros.php';
include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$cdCliente 			= isset($_POST['cdCliente']) ? base64_decode($_POST['cdCliente']) : null;
$cdEmpreendimento 	= isset($_POST['cdEmpreendimento']) ? base64_decode($_POST['cdEmpreendimento']) : null;
$cdLicencaAmbiental	= isset($_POST['cdLicencaAmbiental']) ? $_POST['cdLicencaAmbiental'] : null;
$cdOrgaoLicenciado	= isset($_POST['cdOrgaoLicenciado']) ? base64_decode($_POST['cdOrgaoLicenciado']) : null;
$nrProcesso 		= $_POST['nrProcesso'];

$notificacao = new Notificacao;

$lic = new cLicencaAmbiental;
$lic->setCdCliente($cdCliente);
$lic->setCdEmpreendimento($cdEmpreendimento);
$lic->setNrProcesso($nrProcesso);
$lic->setCdOrgaoLicenciado($cdOrgaoLicenciado);

if(is_null($cdLicencaAmbiental) || empty($cdLicencaAmbiental)){

	// //verifica se existe um cadastro de licença não concluído
	// echo $cdLicencaAmbiental = $lic->cadastroNaoFinalizado();

	// //se retornar algum código de licença
	// if($cdLicencaAmbiental > 0 && !is_null($cdLicencaAmbiental)){

	// }
	// //se não, realiza o cadastro
	// else{
	// 	echo $cdLicencaAmbiental = $lic->Cadastro();
	// }

	$cdLicencaAmbiental = $lic->Cadastro();

	if($cdLicencaAmbiental > 0) {

		$notificacao->viewSwalNotificacao("Sucesso!", "Serviço atualizado com sucesso.", "single", "success");
		echo '
		<script type="text/javascript">
		$("#formCadLicencaAmb input[name=cdLicencaAmbiental]").val("'.$cdLicencaAmbiental.'");
		</script>
		';

	} else{

		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao atualizar os dados do serviço. Por favor, contate o administrador do sistema.", "single", "error");
	}


}else{

	$lic->setCdLicencaAmbiental($cdLicencaAmbiental);

	try {

		$snAlteraCadastro = $lic->Alterar();

		($snAlteraCadastro) ? $notificacao->viewSwalNotificacao("Sucesso!", "Serviço atualizado com sucesso.", "single", "success") : $notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao atualizar os dados do serviço. Por favor, contate o administrador do sistema.", "single", "error");

	} catch (Exception $e) {

		echo $e->getMessage();

	}
}
?>