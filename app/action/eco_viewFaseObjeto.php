<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdItLicenca 	 = (isset($_POST['cdItLicenca'])) ? $_POST['cdItLicenca'] : $_GET['cdItLicenca'];

if(is_null($cdItLicenca)){

	$notificacao = new Notificacao;

	$notificacao->viewSwalNotificacao("Essa não!", "Não foi possível obter a lista de fase [PARAMETROS INCORRETOS]. Por favor entre contato com o administrador do sistema", "single", "error");

}else{

	$lic = new cLicencaAmbiental();
	$lic->listFaseObjetoLicenca($cdItLicenca);

}
?>
<script type="text/javascript">
	$.AdminBSB.input.activate();
	$.AdminBSB.select.activate();

	$(document).ready(function(){
		$("div.form-line.focused").removeClass("focused");

		$('.listComentariosFase').slimScroll({
			height: '20em'
		});
	});

	$('.datepicker').bootstrapMaterialDatePicker({
		format: 'DD/MM/YYYY',
		lang: 'PT-BR',
		nowButton: true,
		switchOnClick: true,
		weekStart: 1,
		time: false
	});
</script>