<?php
session_start();

include '../conf/showErros.php';
include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$notificacao = new Notificacao;

//5 - Permissao para cadastrar serviço
$cdPermissao 		= 5;
$autorizado 		= cPermissao::validarPermissao($cdPermissao);

if (!$autorizado) {
	exit();
}

$cdServico			= isset($_POST['cdServico']) ? $_POST['cdServico'] : null;
$cdCliente 			= isset($_POST['cdCliente']) ? base64_decode($_POST['cdCliente']) : null;
$cdEmpreendimento 	= isset($_POST['cdEmpreendimento']) ? base64_decode($_POST['cdEmpreendimento']) : null;
$cdOrgaoLicenciado	= isset($_POST['cdOrgaoLicenciado']) ? base64_decode($_POST['cdOrgaoLicenciado']) : null;
$nrProcesso 		= $_POST['nrProcesso'];

if(is_null($cdCliente) || empty($cdCliente) || !isset($cdCliente)) {
	$notificacao->viewSwalNotificacao("Atenção", "Selecione o cliente do serviço.", "single", "warning");
	exit();
}

if(is_null($cdEmpreendimento) || empty($cdEmpreendimento) || !isset($cdEmpreendimento)) {
	$notificacao->viewSwalNotificacao("Atenção", "Selecione o empreendimento do serviço.", "single", "warning");
	exit();
}

if(is_null($cdOrgaoLicenciado) || empty($cdOrgaoLicenciado) || !isset($cdOrgaoLicenciado)) {
	$notificacao->viewSwalNotificacao("Atenção", "Selecione o orgão licenciador do serviço.", "single", "warning");
	exit();
}

//Array com as atividades
// $cdAtividade 		= (isset($_POST['cdAtividade'])) 	? $_POST['cdAtividade'] 	: null;
// $tpAtividade 		= (isset($_POST['tpAtividade'])) 	? $_POST['tpAtividade'] 	: null;
// $cdTpAtividade 		= (isset($_POST['cdTpAtividade'])) 	? $_POST['cdTpAtividade'] 	: null;
// $dsAtividade 		= (isset($_POST['dsAtividade'])) 	? $_POST['dsAtividade'] 	: null;
// $cdUsuario 			= (isset($_POST['cdUsuario'])) 		? $_POST['cdUsuario'] 		: null;
// $dtPrevEntrega 		= (isset($_POST['dtPrevEntrega'])) 	? $_POST['dtPrevEntrega'] 	: null;

$serv = new cServico;
$serv->setCdCliente($cdCliente);
$serv->setCdEmpreendimento($cdEmpreendimento);
$serv->setNrProcesso($nrProcesso);
$serv->setCdOrgaoLicenciado($cdOrgaoLicenciado);

//verifica se a ação é um cadastro ou uma atualização
if(is_null($cdServico) || empty($cdServico)){

	// //verifica se existe um cadastro de licença não concluído
	// echo $cdServico = $serv->cadastroNaoFinalizado();

	// //se retornar algum código de licença
	// if($cdServico > 0 && !is_null($cdServico)){

	// }
	// //se não, realiza o cadastro
	// else{
	// 	echo $cdServico = $serv->Cadastro();
	// }

	$cdServico = $serv->Cadastrar();

	if($cdServico > 0) {

		$notificacao->viewSwalNotificacao("Sucesso!", "Serviço cadatrado com sucesso.", "single", "success");
		echo '
		<script type="text/javascript">
		$("#formCadServico input[name=cdServico]").val("'.$cdServico.'");
		</script>
		';

	} else{

		$notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao atualizar os dados do serviço. Por favor, contate o administrador do sistema. [".$cdServico."]", "single", "error");
	}


}else{

	$serv->setCdServico($cdServico);

	try {

		$snAlteraCadastro = $serv->Alterar();

		(is_int($snAlteraCadastro)) ? $notificacao->viewSwalNotificacao("Sucesso!", "Serviço atualizado com sucesso.", "single", "success") : $notificacao->viewSwalNotificacao("Erro", "Ocorreu um erro ao atualizar os dados do serviço. Por favor, contate o administrador do sistema [".$snAlteraCadastro."].", "single", "error");

	} catch (Exception $e) {

		echo $e->getMessage();

	}
}

// if(!is_null($cdServico) && $cdServico > 0){

// 	//instancia a classe de atividade
// 	$at = new cAtividade(null, $cdServico);

// 	$atividadesInseridas = $at->returnArrayAtividade();

// 	// echo "Atividades no banco: ".count($atividadesInseridas);
// 	// echo '<br>';
// 	// echo "Atividades do form: ".count($cdAtividade);

// 	$arrayCdAtvInseridas = array();

// 	if(count($atividadesInseridas) > 0){
// 		//se todas as atividades forem removidas do formulario é atribuido um array vazio
// 		$cdAtividade = (count($cdAtividade) == 0) ? array() : $cdAtividade;

// 		foreach ($atividadesInseridas as $key => $value) {
// 			$arrayCdAtvInseridas[] = $value["cd_atividade"];
// 		}
// 	}
// 	//<te amo>
// 	$atvExcluir = array_diff($arrayCdAtvInseridas, $cdAtividade);

// 	//Percorre o array para inserir ou atualizar as atividades
// 	for ($i=0; $i < count($dsAtividade); $i++) {

// 		//Verifica o tipo da atividade
// 		switch ($tpAtividade[$i]) {
// 			case '#tabAssessoria':
// 			$tpAtv = 'A';
// 			break;

// 			case '#tabConsultoria':
// 			$tpAtv = 'C';
// 			break;

// 			default:
// 			$tpAtv = $tpAtividade[$i];
// 			break;
// 		}

// 		//instancia a classe de atividade com os dados inseridos no formulário
// 		$atv = new cAtividade($cdAtividade[$i], $cdServico, $dsAtividade[$i], $tpAtv, base64_decode($cdUsuario[$i]), implode("-",array_reverse(explode("/",$dtPrevEntrega[$i]))));

// 		//Insere as atividades
// 		(empty($cdAtividade[$i])) ? $atv->Cadastrar() : $atv->Alterar();

// 	}

// 	//se o array de exclusão retornar mais de uma linha tenta executar método para excluir
// 	if(count($atvExcluir) > 0){
// 		try {
// 			foreach ($atvExcluir as $key => $value) {
// 				$at->Excluir($value);
// 			}
// 		} catch (Exception $e) {
// 			$dsError = $e->getMessage();
// 			$notificacao->viewSwalNotificacao("Erro", $dsError, "single", "error");
// 		}
// 	}

// }else{

// 	$notificacao->viewSwalNotificacao("Erro", "Não foi possível atualizar as atividades pois existe erros de parâmentros", "single", "error");

// }
?>
<script type="text/javascript">
	setTimeout(function(){

		$.ajax({
			type: "POST",
			url: "action/eco_viewFormServico.php?animate=N",
			data: {
				cdServico: '<?php echo base64_encode($cdServico); ?>'
			},
			success: function(data){
				$("#viewFormServico").html(data);
			}
		})
		.done(function(){
			console.log("done2");
		});

	},1000);
</script>