<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$idForm 			= $_POST['idForm'];
$cdObjetoLicenca 	= base64_decode($_POST['cdObjetoLicenca']);
$dsObjetoLicenca 	= $_POST['dsObjetoLicenca'];
$posicaoAtual 		= $_POST['posicaoAtual'];

$objLicenca 		= new cObjetoLicenca($cdObjetoLicenca);
$cdCatObjetoLicenca = $objLicenca->returnCatObjetoLicenca();

// [!] -  COLOCAR AS INFORMAÇÕES ABAIXO EM UM VETOR MULTIDIMENCIONAL E ACESSAR A POSICAO DE ACORDO COM O FORMULARIO
if($idForm == '#formCadPropostaLicencaAmb'){

	$totalNegociado 	 = "cadTotalPropostaNegociado";
	$totalPago 			 = "cadTotalPropostaPago";
	$tabConsultoria 	 = '#cadTabConsultoria';
	$tabAssessoria		 = '#cadTabAssessoria';
	$badgeTabConsultoria = '#badgeCadTabConsultoria';
	$badgeTabAssessoria   = '#badgeCadTabAssessoria';

}else if($idForm == '#formAlterPropostaLicencaAmb'){

	$totalNegociado  	 = "alterTotalPropostaNegociado";
	$totalPago 		 	 = "alterTotalPropostaPago";
	$tabConsultoria  	 = '#alterTabConsultoria';
	$tabAssessoria	 	 = '#alterTabAssessoria';
	$badgeTabConsultoria = '#badgeAlterTabConsultoria';
	$badgeTabAssessoria   = '#badgeAlterTabAssessoria';

}else{

	$notificacao = new Notificacao();
	$notificacao->viewSwalNotificacao("Essa não!", "Ocorreu um erro ao definir a categoria do objeto. Contate o administrador do sistema", "single", "error");
	exit();

}

if($cdCatObjetoLicenca == 1){

	$tab 		= $tabConsultoria;
	$badge 		= $badgeTabConsultoria;

}else if($cdCatObjetoLicenca == 2){

	$tab 		= $tabAssessoria;
	$badge 		= $badgeTabAssessoria;

}else{

	$notificacao = new Notificacao();
	$notificacao->viewSwalNotificacao("Essa não!", "Ocorreu um erro ao definir a categoria do objeto. Contate o administrador do sistema", "single", "error");
	exit();

}

$elem = $tab." .listaObjetosProposta";

?>

<script type="text/javascript">

	var idForm 		 = '<?php echo $idForm; ?>';
	var valor 		 = '<?php echo $cdObjetoLicenca; ?>';
	var texto 		 = '<?php echo $dsObjetoLicenca; ?>';
	var posicaoAtual = '<?php echo $posicaoAtual; ?>';
	var divTotalN 	 = '<?php echo $totalNegociado; ?>';
	var divTotalP 	 = '<?php echo $totalPago; ?>';

	var qtdObj 		= $("<?php echo $elem; ?> .objElement").length + 1;
	var badge 		= '<?php echo $badge ?>';

	$("<?php echo $elem; ?>").append('<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 objElement"><div class="card"><div class="header bg-deep-purple cursorMove"><h2>'+texto+'</h2><ul class="header-dropdown m-r--5"><li class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="material-icons">more_vert</i></a><ul class="dropdown-menu pull-right"><li><a class="waves-effect waves-block" onclick="removerObjetoLicenca(this)" data-index="'+posicaoAtual+'" data-dtotaln="'+divTotalN+'" data-dtotalp="'+divTotalP+'" data-badge="'+badge+'" data-qtdbadge="'+qtdObj+'"><i class="material-icons" style="color:#F44336 !important">delete</i> Remover</a></li></ul></li></ul><input type="hidden" name="cdObjetoProposta[]" value="'+valor+'"/></div><div class="body"><div class="row"><div class="col-xs-6 col-sm-6 col-md-3 col-lg-3"><div class="form-group"><div class="form-line"><label>Entrega prevista</label><input type="text" name="dtPrevEntregaObjeto[]" class="form-control datepicker" placeholder="" /></div></div></div><div class="col-xs-6 col-sm-6 col-md-3 col-lg-3"><div class="form-group"><div class="form-line"><label>Nº da Proposta</label><input type="text" name="nrProposta[]" class="form-control" data-index="'+posicaoAtual+'" /></div></div></div><div class="col-xs-6 col-sm-6 col-md-3 col-lg-3"><div class="form-group"><div class="form-line"><label>Valor Negociado</label><input type="text" name="vlObjetoNegociado[]" class="form-control inputMoney" data-index="'+posicaoAtual+'" /></div></div></div><div class="col-xs-6 col-sm-6 col-md-3 col-lg-3"><div class="form-group"><div class="form-line"><label>Valor Pago</label><input type="text" name="vlObjetoPago[]" class="form-control inputMoney" data-index="'+posicaoAtual+'" /></div></div></div><div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"><div class="form-group"><div class="form-line"><label>Anexo:</label><input type="file" name="anexo[]" class="form-control" data-index="'+posicaoAtual+'" /></div></div></div></div></div></div></div>');

	$(badge).html(qtdObj);

	$(".inputMoney").maskMoney({prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',', affixesStay: false});

	$('.datepicker').datetimepicker({
		format: 'DD/MM/YYYY'
	});

	$("<?php echo $elem; ?> input[name='vlObjetoNegociado[]']").change(function(){

		var vlObjeto;
		var posicao = $(this).data("index");


		vlObjeto  = $(this).val().replace(".","");
		vlObjeto 	  = vlObjeto.replace(",",".");
		if(vlObjeto == ''){vlObjeto=0;}


		valoresObjetoNegociado[posicao] = parseFloat(vlObjeto);

		$("#"+divTotalN).html(valoresObjetoNegociado.reduce(getSum).format(2, 3, '.', ','));
		$("#"+divTotalN).data('to', valoresObjetoNegociado.reduce(getSum));

	});

	$("<?php echo $elem; ?> input[name='vlObjetoPago[]']").change(function(){

		var vlObjeto;
		var posicao = $(this).data("index");


		vlObjeto  = $(this).val().replace(".","");
		vlObjeto 	  = vlObjeto.replace(",",".");
		if(vlObjeto == ''){vlObjeto=0;}


		valoresObjetoPago[posicao] = parseFloat(vlObjeto);

		$("#"+divTotalP).html(valoresObjetoPago.reduce(getSum).format(2, 3, '.', ','));
		$("#"+divTotalP).data('to', valoresObjetoPago.reduce(getSum));

	});
</script>