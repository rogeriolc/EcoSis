<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdLicencaAmbiental = isset($_POST['cdLicencaAmbiental']) ? $_POST['cdLicencaAmbiental'] : null;
$cdCliente 			= base64_decode($_POST['cdCliente']);
$cdEmpreendimento 	= base64_decode($_POST['cdEmpreendimento']);
$nrProcesso 		= $_POST['nrProcesso'];
$cdObjetoLicenca 	= base64_decode($_POST['cdObjetoLicenca']);
$cdOrgaoLicenciado 	= base64_decode($_POST['cdOrgaoLicenciado']);

// var_dump($cdLicencaAmbiental);

$lic = new cLicencaAmbiental(null, $cdCliente, $cdEmpreendimento, $nrProcesso, $cdOrgaoLicenciado);

if(is_null($cdLicencaAmbiental) || empty($cdLicencaAmbiental)){
	$cdLicencaAmbiental = $lic->Cadastro();
}

$lic->setCdLicencaAmbiental($cdLicencaAmbiental);

// echo $lic->getCdLicencaAmbiental();

$cdItLicenca = $lic->addObjetoLicenca($cdObjetoLicenca);

// var_dump($cdItLicenca);

try{

	$it = $lic->addFaseObjeto($cdItLicenca, $cdObjetoLicenca);

}catch(Exception $e){
	var_dump($e->getMessage());
}


?>
<script type="text/javascript">
	$("#formCadLicencaAmb input[type=hidden][name=cdLicencaAmbiental]").val("<?php echo $cdLicencaAmbiental; ?>");
</script>
<div role="tabpanel">
	<!-- Nav tabs -->
	<ul class="nav nav-tabs" role="tablist" style="margin-left: 3px;">
		<li role="presentation" class="active">
			<a href="#tabViewLicencaConsultoria" aria-controls="tabViewLicencaConsultoria" role="tab" data-toggle="tab">
				Consultoria &nbsp;
				<span class="badge bg-teal"><?php echo $lic->returnQtdObjetoLicenca(1); ?></span>
			</a>
		</li>
		<li role="presentation">
			<a href="#tabViewLicencaAssessoria" aria-controls="tabViewLicencaAssessoria" role="tab" data-toggle="tab">
				Assessoria &nbsp;
				<span class="badge bg-teal"><?php echo $lic->returnQtdObjetoLicenca(2); ?></span>
			</a>
		</li>
	</ul>

	<!-- Tab panes -->
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active p-a-10" id="tabViewLicencaConsultoria" style="height: 100rem; overflow-x: hidden;">
			<?php $lic->listObjetoLicenca(1); ?>
		</div>
		<div role="tabpanel" class="tab-pane p-a-10" id="tabViewLicencaAssessoria" style="height: 100rem; overflow-x: hidden;">
			<?php $lic->listObjetoLicenca(2); ?>
		</div>
	</div>
</div>