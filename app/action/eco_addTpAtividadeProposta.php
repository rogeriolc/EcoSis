<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$idForm 			= $_POST['idForm'];
$cdTpAtividade 		= base64_decode($_POST['cdTpAtividade']);
$dsTpAtividade 		= $_POST['dsTpAtividade'];
// $posicaoAtual 		= $_POST['posicaoAtual'];
$tpAtividade		= $_POST['tpAtividade'];

$objTipoAtividade	= new cTpAtividade($cdTpAtividade);
$objTipoAtividade->Dados();

// [!] -  COLOCAR AS INFORMAÇÕES ABAIXO EM UM VETOR MULTIDIMENCIONAL E ACESSAR A POSICAO DE ACORDO COM O FORMULARIO
if($idForm == '#formCadPropostaLicencaAmb'){

	$totalNegociado 	 = "#cadTotalPropostaNegociado";
	$totalPago 			 = "#cadTotalPropostaPago";
	$tabConsultoria 	 = '#cadTabConsultoria';
	$tabAssessoria		 = '#cadTabAssessoria';
	$badgeTabConsultoria = '#badgeCadTabConsultoria';
	$badgeTabAssessoria  = '#badgeCadTabAssessoria';

}else if($idForm == '#formAlterPropostaLicencaAmb'){

	$totalNegociado  	 = "#alterTotalPropostaNegociado";
	$totalPago 		 	 = "#alterTotalPropostaPago";
	$tabConsultoria  	 = '#alterTabConsultoria';
	$tabAssessoria	 	 = '#alterTabAssessoria';
	$badgeTabConsultoria = '#badgeAlterTabConsultoria';
	$badgeTabAssessoria  = '#badgeAlterTabAssessoria';

}else{

	$notificacao = new Notificacao();
	$notificacao->viewSwalNotificacao("Essa não!", "Ocorreu um erro ao definir a categoria do objeto. Contate o administrador do sistema", "single", "error");
	exit();

}

// if($tpAtividade == '#cadTabConsultoria'){
if($objTipoAtividade->cdCatTpAtividade == 2){

	$tab 			= $tabConsultoria;
	$badge 			= $badgeTabConsultoria;
	$tipoAtividade 	= 'C';

// }else if($tpAtividade == '#cadTabAssessoria'){
}else if($objTipoAtividade->cdCatTpAtividade == 1){

	$tab 			= $tabAssessoria;
	$badge 			= $badgeTabAssessoria;
	$tipoAtividade 	= 'A';

}else{

	$notificacao = new Notificacao();
	$notificacao->viewSwalNotificacao("Essa não!", "Ocorreu um erro ao definir a categoria do objeto. Contate o administrador do sistema", "single", "error");
	exit();

}

$elem = $tab." .listaAtividadesProposta";

?>

<script type="text/javascript">

	var idForm 		 = '<?php echo $idForm; ?>';
	var valor 		 = '<?php echo $cdTpAtividade; ?>';
	var texto 		 = '<?php echo $dsTpAtividade; ?>';
	var tipoAtv 	 = '<?php echo $tipoAtividade; ?>';
	var divTotalN 	 = '<?php echo $totalNegociado; ?>';
	var divTotalP 	 = '<?php echo $totalPago; ?>';

	var qtdAtv 		= $("<?php echo $elem; ?> .objElement").length + 1;
	var badge 		= '<?php echo $badge ?>';

	$("<?php echo $elem; ?>").append('<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 objElement"><div class="card"><input type="hidden" name="tpAtividade[]" class="form-control" data-index="'+tipoAtv+'" value="'+tipoAtv+'" /><input type="hidden" name="cdItProposta[]" class="form-control" /><div class="header bg-deep-purple cursorMove"><h2>'+texto+'</h2><ul class="header-dropdown m-r--5"><li class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="material-icons">more_vert</i></a><ul class="dropdown-menu pull-right"><li><a class="waves-effect waves-block" onclick="removerAtividade(this)" data-index="" data-dtotaln="'+divTotalN+'" data-dtotalp="'+divTotalP+'" data-badge="'+badge+'" data-qtdbadge="'+qtdAtv+'"><i class="material-icons" style="color:#F44336 !important">delete</i> Remover</a></li></ul></li></ul><input type="hidden" name="cdTpAtividade[]" value="'+valor+'"/></div><div class="body"><div class="row"><div class="col-xs-6 col-sm-6 col-md-3 col-lg-3"><div class="form-group"><div class="form-line"><label>Entrega prevista</label><input type="text" name="dtPrevEntregaAtividade[]" class="form-control datepicker" placeholder="" /></div></div></div><div class="col-xs-6 col-sm-6 col-md-3 col-lg-3"><div class="form-group"><div class="form-line"><label>Valor Negociado</label><input type="text" name="vlAtividadeNegociado[]" class="form-control inputMoney" data-index="" /></div></div></div><div class="col-xs-6 col-sm-6 col-md-3 col-lg-3"><div class="form-group"><div class="form-line"><label>Valor Pago</label><input type="text" name="vlAtividadePago[]" class="form-control inputMoney" data-index="" /></div></div></div></div></div></div></div>');

	$(badge).html(qtdAtv);

	$(".inputMoney").maskMoney({prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',', affixesStay: false});

	$('.datepicker').datetimepicker({
		format: 'DD/MM/YYYY'
	});

	$("<?php echo $idForm; ?> input[name='vlAtividadePago[]'], <?php echo $idForm; ?> input[name='vlAtividadeNegociado[]']").change(function(){

		calculaTotais();

	});

	function calculaTotais()
	{
		let totalPago 		= 0;
		let totalNegociado 	= 0;

		console.log('calcular totais...');

		$("<?php echo $idForm; ?> input[name='vlAtividadeNegociado[]']").each(function(index, el) {

			let vlInput = $(this).val();

			vlInput  = $(this).val().replace(".","");
			vlInput 	  = vlInput.replace(",",".");
			if(vlInput == ''){vlInput=0;}

			totalNegociado 	+= Number( vlInput );

		});

		$("<?php echo $idForm; ?> input[name='vlAtividadePago[]']").each(function(index, el) {

			let vlInput = $(this).val();

			vlInput  = $(this).val().replace(".","");
			vlInput 	  = vlInput.replace(",",".");
			if(vlInput == ''){vlInput=0;}

			totalPago 	+= Number( vlInput );

		});

		$("<?php echo $totalNegociado; ?>").html(totalNegociado.format(2, 3, '.', ','));
		$("<?php echo $totalPago; ?>").html(totalPago.format(2, 3, '.', ','));
	}

	function removerAtividade(e){


		var idBadge	 = $(e).data("badge");
		var qtdBadge = parseInt($(idBadge).text()) - 1;

		//Atualiza badge aba
		$(idBadge).html(qtdBadge);

		//Pega o elemento anterior
		$(e).closest('.objElement').remove();

		calculaTotais();

	}

</script>