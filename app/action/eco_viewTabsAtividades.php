<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdServico = isset($_POST['cdServico']) ? $_POST['cdServico'] : null;

$serv	   = new cServico($cdServico);
$atv	   = new cAtividade(null, $cdServico);

$arrayAtvAssessoria  = array();
$arrayAtvConsultoria = array();

$arrayAtividades	 = $atv->returnArrayAtividade();

if(!empty($cdServico) && !is_null($cdServico)) {
	if(count($arrayAtividades) > 0){
		foreach ($arrayAtividades as $key => $value) {
			//Separa em assessoria e consultoria
			if($value["tp_atividade"] == 'A'){
				$arrayAtvAssessoria[] = $value["cd_atividade"];
			}else if($value["tp_atividade"] == 'C'){
				$arrayAtvConsultoria[] = $value["cd_atividade"];
			}else{

			}
		}
	}
}
?>
<div role="tabpanel">
	<!-- Nav tabs -->
	<ul class="nav nav-tabs tab-col-deep-purple" id="tabAtividades" role="tablist">
		<li role="presentation" class="active">
			<a href="#tabAssessoria" aria-controls="tabAssessoria" role="tab" data-toggle="tab">Assessoria <span class="label-count mdc-bg-green" style="color: #fff;"><?php echo count($arrayAtvAssessoria); ?></span></a>
		</li>
		<li role="presentation">
			<a href="#tabConsultoria" aria-controls="tabConsultoria" role="tab" data-toggle="tab">Consultoria <span class="label-count mdc-bg-green" style="color: #fff";><?php echo count($arrayAtvConsultoria); ?></span></a>
		</li>
	</ul>
	<!-- Tab panes -->
	<div class="tab-content no-padding">
		<div role="tabpanel" class="tab-pane active" id="tabAssessoria">
			<br>
			<div class="table-responsive" style="min-height: 45em">
				<table class="table table-striped table-bordered dataTableSimple" id="tableAssessoria">
					<thead>
						<tr>
							<th class="text-center mdc-bg-grey-100"><small>Descrição da atividade</small></th>
							<th class="text-center mdc-bg-grey-100"><small>Responsável</small></th>
							<th class="text-center mdc-bg-grey-100 text-center"><small>Prev. Entrega</small></th>
							<th class="text-center mdc-bg-grey-100 text-center"><small>Opções</small></th>
						</tr>
					</thead>
					<tbody>
						<?php $atv->ListarTableForm('A'); ?>
					</tbody>
				</table>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane" id="tabConsultoria">
			<br>
			<div class="table-responsive" style="min-height: 45em">
				<table class="table table-striped table-bordered dataTableSimple" id="tableConsultoria">
					<thead>
						<tr>
							<th class="text-center mdc-bg-grey-100"><small>Descrição da atividade</small></th>
							<th class="text-center mdc-bg-grey-100"><small>Responsável</small></th>
							<th class="text-center mdc-bg-grey-100 text-center"><small>Prev. Entrega</small></th>
							<th class="text-center mdc-bg-grey-100 text-center"><small>Opções</small></th>
						</tr>
					</thead>
					<tbody>
						<?php $atv->ListarTableForm('C'); ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

	$.AdminBSB.input.activate();
	$.AdminBSB.select.activate();

	$(document).ready(function(){
		$("div.form-line.focused").removeClass("focused");

		$('.listComentariosFase').slimScroll({
			height: '20em'
		});

		$('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
			$.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
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

	var tblAssessoria = $("#tableAssessoria").DataTable({
		"bLengthChange": false,
		"bInfo": false,
		"columns": [
		{ "width": "45%" },
		{ "width": "30%" },
		null,
		{ "width": "5%" }
		],
		responsive: true,
		pageLength: 5
	});

	var tblConsultoria = $("#tableConsultoria").DataTable({
		"bLengthChange": false,
		"bInfo": false,
		"columns": [
		{ "width": "45%" },
		{ "width": "30%" },
		null,
		{ "width": "5%" }
		],
		pageLength: 5
	});
</script>