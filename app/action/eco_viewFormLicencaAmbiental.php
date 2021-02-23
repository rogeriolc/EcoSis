<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$usuarios 		= new cUsuario;
$empreendimento = new cEmpreendimento;
$cliente 		= new cCliente;
$objLicenca		= new cObjetoLicenca;
$tpLicenca		= new cTpLicencaAmbiental;
$prop			= new cPropostaLicencaAmb;
$orgLicenciado  = new cOrgaoLicenciado;
$fase  			= new cFaseObjeto;

$cdLicencaAmbiental = (isset($_POST['cdLicencaAmbiental'])) ? base64_decode($_POST['cdLicencaAmbiental']) : null;

$lic 				= new cLicencaAmbiental($cdLicencaAmbiental);

$cdCliente 			= null;
$tpStatus 			= null;
$cdEmpreendimento 	= null;
$nrProcesso 		= null;
$nmOrgaoLicenciado 	= null;

if(!empty($cdLicencaAmbiental) && !is_null($cdLicencaAmbiental)) {
	$lic->Dados();


	$cdCliente 			= $lic->getCdCliente();

	$cdEmpreendimento 	= $lic->getCdEmpreendimento();
	$nrProcesso 		= $lic->getNrProcesso();
	$cdOrgaoLicenciado	= $lic->getCdOrgaoLicenciado();
	$tpStatus 			= $lic->getTpStatus();

	$cliente->setCdCliente($cdCliente);
	$cliente->Dados();
	$nmCliente 			= $cliente->nmCliente;

	$empreendimento->setCdCliente($cdCliente);
	$empreendimento->setCdEmpreendimento($cdEmpreendimento);
	$empreendimento->Dados();
	$nmEmpreendimento	= $empreendimento->nmEmpreendimento;

	$orgLicenciado->setCdOrgaoLicenciado($cdOrgaoLicenciado);
	$orgLicenciado->Dados();
	$nmOrgaoLicenciado	= $orgLicenciado->nmOrgaoLicenciado;

}

/*
E - Em andamento
C - Cancelado
A - Atrasado
O - Concluído
N - Cadastro não finalizado
R - Reaberta
*/

switch ($tpStatus) {
	case 'C':
	case 'O':
	$propBtnSalvar   = 'disabled';
	$propBtnConcluir = 'disabled';
	$propBtnCancelar = 'disabled';
	$propInputsForm  = 'disabled';
	break;

	default:
	$propBtnSalvar   = null;
	$propBtnConcluir = null;
	$propBtnCancelar = null;
	$propInputsForm  = null;
	break;
}

?>

<div class="row animated bounceInRight">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<form id="formCadLicencaAmb">
			<input type="hidden" name="cdLicencaAmbiental" value="<?php echo $cdLicencaAmbiental; ?>" />
			<div class="card">
				<div class="header bg-deep-purple">
					<ul class="m-r--5 pull-right" style="list-style: none; margin-bottom: 0;">
						<li class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
								<i class="material-icons">more_vert</i>
							</a>
							<ul class="dropdown-menu pull-right">
								<li><a data-toggle="modal" href="javascript:void(0)" class="waves-effect waves-block" onclick="cadLicenca(this)"><i class="material-icons" style="color: #3F51B5 !important;">save</i> Salvar</a></li>
								<li><a data-toggle="modal" href="javascript:void(0)" class="waves-effect waves-block" onclick="concluirLicenca(this)"><i class="material-icons" style="color: #4CAF50 !important;">check</i> Concluir</a></li>
								<li><a data-toggle="modal" href="javascript:void(0)" class="waves-effect waves-block" onclick="cancelarLicenca(this)"><i class="material-icons" style="color: #F44336 !important;">block</i> Cancelar</a></li>
							</ul>
						</li>
					</ul>
					<h2><i class="material-icons pull-left p-r-10 m-t--3">assignment</i> Dados do Serviço</h2>
				</div>
				<div class="body" style="padding-bottom: 0;">
					<div class="row clearfix">
						<div class="col-sm-6" style="margin-bottom: 0;">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
									<strong>Cliente:</strong>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
									<select name="cdCliente" style="width:100%" class="form-control" data-live-search="true" <?php echo $propInputsForm;?>>
										<option></option>
										<?php $cliente->listOption($cdCliente); ?>
									</select>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
									<strong>Empreendimento:</strong>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
									<select name="cdEmpreendimento" style="width:100%" class="form-control" data-live-search="true" <?php echo $propInputsForm;?>>
										<option></option>
										<?php (!empty($cdLicencaAmbiental) && !is_null($cdLicencaAmbiental)) ? $empreendimento->listOption($cdEmpreendimento) : ''; ?>
									</select>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
									<strong>Orgão Licenciado:</strong>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
									<select name="cdOrgaoLicenciado" style="width:100%" class="form-control" data-live-search="true" <?php echo $propInputsForm;?>>
										<option></option>
										<?php $orgLicenciado->listOption($cdOrgaoLicenciado); ?>
									</select>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
									<strong>N do processo:</strong>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
									<div class="form-group">
										<div class="form-line">
											<input type="text" name="nrProcesso" class="form-control" value="<?php echo $nrProcesso;?>" <?php echo $propInputsForm;?> />
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6" style="margin-bottom: 00;">
							<div class="row" style="display: flex; justify-content: center; min-height: 16em;">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 mdc-bg-grey-200 p-t-5">

									Resumo do serviço
									<h1>5<small>/10</small></h1>
									<br/>
									<div class="progress">
										<div class="progress-bar bg-green" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
											60%
										</div>
									</div>

								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 mdc-bg-grey-200 p-t-5">
									Conclusão prévia
									<h2><small><i class="material-icons pull-left p-r-10 m-t-5">event</i> 01/01/2018</small></h2>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="header bg-deep-purple">
					<ul class="m-r--5 pull-right" style="list-style: none; margin-bottom: 0;">
						<li class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
								<i class="material-icons">more_vert</i>
							</a>
							<ul class="dropdown-menu pull-right">
								<li><a data-toggle="modal" href="javascript:void(0)" class="waves-effect waves-block" onclick="addAtividade()"><i class="material-icons" style="color: #4CAF50 !important;">add</i> Adicionar</a></li>
							</ul>
						</li>
					</ul>
					<h2><i class="material-icons pull-left p-r-10 m-t--3">assignment_turned_in</i> Atividades</h2>
				</div>

				<!-- Tabela de Atividades -->
				<div class="body">
					<div class="row clearfix">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
							<div role="tabpanel">
								<!-- Nav tabs -->
								<ul class="nav nav-tabs tab-col-deep-purple" id="tabAtividades" role="tablist">
									<li role="presentation" class="active">
										<a href="#tabAssessoria" aria-controls="tabAssessoria" role="tab" data-toggle="tab">Assessoria <span class="label-count mdc-bg-green" style="color: #fff;">7</span></a>
									</li>
									<li role="presentation">
										<a href="#tabConsultoria" aria-controls="tabConsultoria" role="tab" data-toggle="tab">Consultoria <span class="label-count mdc-bg-green" style="color: #fff";>7</span></a>
									</li>
								</ul>
								<!-- Tab panes -->
								<div class="tab-content no-padding">
									<div role="tabpanel" class="tab-pane active" id="tabAssessoria">
										<br>
										<div class="table-responsive" style="min-height: 45em">
											<table class="table table-bordered dataTableSimple" id="tableAssessoria">
												<thead>
													<tr>
														<th class="text-center mdc-bg-grey-100"><small>Descrição da atividade</small></th>
														<th class="text-center mdc-bg-grey-100"><small>Responsável</small></th>
														<th class="text-center mdc-bg-grey-100 text-center"><small>Prev. Entrega</small></th>
														<th class="text-center mdc-bg-grey-100 text-center"><small>Opções</small></th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
									<div role="tabpanel" class="tab-pane" id="tabConsultoria">
										<br>
										<div class="table-responsive" style="min-height: 45em">
											<table class="table table-bordered dataTableSimple" id="tableConsultoria">
												<thead>
													<tr>
														<th class="text-center mdc-bg-grey-100"><small>Descrição da atividade</small></th>
														<th class="text-center mdc-bg-grey-100"><small>Responsável</small></th>
														<th class="text-center mdc-bg-grey-100 text-center"><small>Prev. Entrega</small></th>
														<th class="text-center mdc-bg-grey-100 text-center"><small>Opções</small></th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>

				<div class="header bg-deep-purple">
					<ul class="m-r--5 pull-right" style="list-style: none; margin-bottom: 0;">
						<li class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
								<i class="material-icons">more_vert</i>
							</a>
							<ul class="dropdown-menu pull-right">
								<li><a data-toggle="modal" href="javascript:void(0)" class="waves-effect waves-block" onclick=""><i class="material-icons" style="color: #4CAF50 !important;">add</i> Adicionar</a></li>
							</ul>
						</li>
					</ul>
					<h2><i class="material-icons pull-left p-r-10 m-t--3">assignment_turned_in</i> Atividades</h2>
				</div>
				<div class="body">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
							<div style="height: 300px; border-bottom: 2px solid #eee;" class="mdc-bg-grey-100"></div>
							<div class="m-t-5">
								<div class="form-group">
									<div class="form-line">
										<textarea name="dsMensagem" class="form-control no-resize" rows="3" placeholder="Digite aqui sua mensagem..."></textarea>
									</div>
									<br/>
									<button class="btn bg-deep-purple pull-right"><i class="material-icons">send</i> Enviar</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="footer">
				</div>
			</div>
		</form>
	</div>
</div>

<div class="modal fade" id="modalFormAddFaseObjetoLicenca">
	<form id="formAddFaseObjeto" method="POST">
		<input type="hidden" name="cdItLicencaAmbiental" />
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="material-icons pull-left m-t--5 col-green">add</i> Adicionar Fase</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<select class="form-control" name="cdFase[]" data-live-search="true" multiple>
							<?php $fase->listOption(); ?>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="material-icons pull-left m-t--5 col-red">close</i> Fechar</button>
					<button type="submit" class="btn bg-green"><i class="material-icons pull-left m-t--5">add</i> Adicionar</button>
				</div>
			</div>
		</div>
	</form>
</div>

<div class="modal fade" id="modalProtocolo">
	<form id="formAddAnexoObjeto" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="cdItLicencaAmbiental" />
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="material-icons pull-left m-t--5 col-green">add</i> Adicionar Fase</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<select class="form-control" name="cdFase[]" data-live-search="true" multiple>
							<?php $fase->listOption(); ?>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="material-icons pull-left m-t--5 col-red">close</i> Fechar</button>
					<button type="submit" class="btn bg-green"><i class="material-icons pull-left m-t--5">add</i> Adicionar</button>
				</div>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">

	$.AdminBSB.input.activate();
	$.AdminBSB.select.activate();

	var objPosition;
	var tpStatus = '<?php echo $tpStatus;?>';

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

	function anchor(t){
		$("html, body").animate({ scrollTop: t.offset().top - 80 }, 600);
	}

	$('#formCadLicencaAmb select[name=cdCliente]').change(function(){
		var valor = $(this).val();
		var empre = '<?php echo base64_encode($cdEmpreendimento); ?>';

		$.ajax({
			url: 'action/g_listOptionEmpreendimento.php',
			type: 'POST',
			data: {cdCliente: valor, cdEmpreendimento: empre},
			success: function(data){
				$("#formCadLicencaAmb select[name=cdEmpreendimento]").html(data);
				$("#formCadLicencaAmb select[name=cdEmpreendimento]").selectpicker('refresh');
			}
		})
		.done(function() {
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});

	});


	//adicionar atividade

	function addAtividade(e){
		var tpAtividade = $("#tabAtividades").find("li.active > a").attr('href');
		var menuOptions = [
				'<div class="form-group"><div class="form-line"><textarea class="form-control no-resize" name="dsAtividade[]"></textarea></div></div>',
				'<div class="form-group"><div class="form-line"><select class="form-control" style="width: 100%" data-live-search="true"><?php cCliente::staticListOption(); ?></select></div></div>',
				'<div class="form-group"><div class="form-line"><input class="form-control datepicker" /></div></div>',
				'<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="material-icons">more_vert</i></button><ul class="dropdown-menu dropdown-menu-right"><li><a href="javascript:void(0);" class=" waves-effect waves-block"><i class="material-icons mdc-text-indigo">save</i> Salvar</a></li><li><a href="javascript:void(0);" class=" waves-effect waves-block"><i class="material-icons mdc-text-green">done</i> Concluir</a></li><li><a href="javascript:void(0);" class=" waves-effect waves-block"><i class="material-icons mdc-text-red">delete</i> Excluir</a></li><li role="separator" class="divider"></li><li><a href="javascript:void(0);" class=" waves-effect waves-block"><i class="material-icons mdc-text-indigo">attach_file</i> Anexos</a></li></ul></div>'
				];

		if(tpAtividade == "#tabAssessoria"){
			tblAssessoria.row.add(menuOptions).draw();
		}else{
			tblConsultoria.row.add(menuOptions).draw();
		}

		$.AdminBSB.input.activate();
		$.AdminBSB.select.activate();
		$('.datepicker').bootstrapMaterialDatePicker({
			format: 'DD/MM/YYYY',
			lang: 'PT-BR',
			nowButton: true,
			switchOnClick: true,
			weekStart: 1,
			time: false
		});
		$("div.form-line.focused").removeClass("focused");

	}

	$("#formAddFaseObjeto").submit(function(){

		var itLic = $("#formAddFaseObjeto input[name=cdItLicencaAmbiental]").val();

		$.ajax({
			url: 'action/eco_addFaseObjeto.php',
			type: 'POST',
			data: $("#formAddFaseObjeto").serialize(),
			success: function(data){
				// anchor("#tabObj"+objPosition);
			}
		})
		.done(function() {
			setTimeout(function(){
				$.AdminBSB.input.activate();
				$.AdminBSB.select.activate();

				$("#tabObj"+ (itLic) + "" + objPosition).load('action/eco_viewFaseObjeto.php?cdItLicenca='+itLic);
				$("#tabObj"+ (itLic) + "" + objPosition).addClass('in');

				$("#modalFormAddFaseObjetoLicenca").modal("hide");
			},1000);
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
		});

		return false;
	});

	function selecionarObjeto(o,i){

		objPosition = o;

		$("#formAddFaseObjeto input[name=cdItLicencaAmbiental]").val(i);
	}

	function cadLicenca(){

		if(tpStatus == 'C' || tpStatus == 'O'){

			swal("Desculpe...", "Esta licença já foi cancelada ou concluida.","error");

		}else{

			$.ajax({
				type: "POST",
				url: "action/eco_cadLicencaAmb.php",
				data: $("#formCadLicencaAmb").serialize(),
				success: function(data){
					$("#divResult").html(data);
				}
			})
			.done(function(){
				console.log("done");
			});

		}
	}

	function removerObjeto(o,i){

		objPosition = o;

		swal({
			title: "Só para confirmar...",
			text: "Tem certeza que deseja excluir o objeto? \n Lembre-se que excluindo o objeto suas FASES TAMBÉM SERÃO EXCLUÍDAS",
			icon: "warning",
			buttons: ["Talvez mais tarde", "Sim, tenho"],
			dangerMode: true
		})
		.then((willDelete) => {
			if (willDelete) {

				$(".page-loader-wrapper").fadeIn("fast");

				$.ajax({
					url: 'action/eco_removerObjetoLicenca.php',
					type: 'POST',
					data: {
						cdItLicencaAmbiental: i,
						objPosition: objPosition
					},
					success: function(data){
						$("#divResult").html(data);
					}
				})
				.done(function() {
					console.log("success");
					$(".page-loader-wrapper").fadeOut("fast");
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});

			} else {
				swal({
					icon: "info",
					title: "Nada foi alterado",
					text: "A fase não foi deletada!"
				});
			}
		});
	}

	function addObjetoLicenca(){

		if(tpStatus == 'C' || tpStatus == 'O'){

			swal("Desculpe...", "Esta licença já foi cancelada ou concluida.","error");

		}else{

			$.ajax({
				type: "POST",
				url: "action/eco_addObjetoLicenca.php",
				data: $("#formCadLicencaAmb").serialize(),
				success: function(data){
					$("#tabsObj").html(data);
				}
			}).done(function() {
				$.AdminBSB.select.activate();
				$.AdminBSB.input.activate();
				$('.datepicker').bootstrapMaterialDatePicker({
					format: 'DD/MM/YYYY',
					clearButton: true,
					weekStart: 1,
					time: false
				});
				$("div.form-line.focused").removeClass("focused");
			});
		}
	}

	function salvarFase(f){

		var cdItLicencaFase	= $(f).find("input[name=cdItLicencaFase]").val();
		var dsFase 			= $(f).find("textarea[name=dsFase]").val();
		var dtPrevEntrega 	= $(f).find("input[name=dtPrevEntrega]").val();
		var cdResponsavel 	= $(f).find("select[name=cdResponsavel]").val();

		$.ajax({
			url: 'action/eco_salvarFaseObjeto.php',
			type: 'POST',
			data: {
				cdItLicencaFase: cdItLicencaFase,
				dsFase: dsFase,
				dtPrevEntrega: dtPrevEntrega,
				cdResponsavel: cdResponsavel
			},
			success: function(data){
				$("#divResult").html(data);
			}
		})
		.done(function() {
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	}

	function addFase(f){

		var cdItLicencaFase	= $(f).find("input[name=cdItLicencaFase]").val();

		$.ajax({
			url: 'action/eco_addFaseObjeto.php',
			type: 'POST',
			data: {
				cdItLicencaFase: cdItLicencaFase
			},
			success: function(data){
				$("#divResult").html(data);
			}
		})
		.done(function() {
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("always");
		});
	}

	function removerFase(f){

		var cdItLicencaFase	= $(f).find("input[name=cdItLicencaFase]").val();

		swal({
			title: "Tem certeza?",
			text: "Após remover uma fase, não poderá voltar atrás. Realmente concorda?",
			icon: "warning",
			buttons: ["Talvez mais tarde", "Sim, tenho"],
			dangerMode: true
		})
		.then((willDelete) => {
			if (willDelete) {

				$.ajax({
					url: 'action/eco_removerFaseObjeto.php',
					type: 'POST',
					data: {
						cdItLicencaFase: cdItLicencaFase
					},
					success: function(data){
						$("#divResult").html(data);
					}
				})
				.done(function() {
					console.log("success");
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});

			} else {
				swal({
					icon: "info",
					title: "Nada foi alterado",
					text: "A fase não foi deletada!"
				});
			}
		});
	}

	function concluirFase(f){

		var cdItLicencaFase	= $(f).find("input[name=cdItLicencaFase]").val();

		//$.md5(cdItLicencaFase)

		swal({
			title: "Só para confirmar...",
			text: "Tem certeza que deseja concluir esta fase?",
			icon: "warning",
			buttons: ["Talvez mais tarde", "Sim, tenho"],
			dangerMode: true
		})
		.then((willDelete) => {
			if (willDelete) {

				$.ajax({
					url: 'action/eco_alterarStatusFase.php',
					type: 'POST',
					data: {
						cdItLicencaFase: cdItLicencaFase,
						status: 'O'
					},
					success: function(data){
						$("#divResult").html(data);
					}
				})
				.done(function() {
					console.log("success");
					$("#tabViewLicencaConsultoria").load("action/eco_viewListObjetoLicenca.php?cdItLicencaFase="+cdItLicencaFase+"&cdCatObjetoLicenca=1");
					$("#tabViewLicencaAssessoria").load("action/eco_viewListObjetoLicenca.php?cdItLicencaFase="+cdItLicencaFase+"&cdCatObjetoLicenca=2");
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});

			} else {
				swal({
					icon: "info",
					title: "Nada foi alterado",
					text: "A fase não foi deletada!"
				});
			}
		});
	}

	function cancelarFase(f){

		var cdItLicencaFase	= $(f).find("input[name=cdItLicencaFase]").val();

		swal({
			title: "Só para confirmar...",
			text: "Tem certeza que deseja cancelar esta fase?",
			icon: "warning",
			buttons: ["Talvez mais tarde", "Sim, tenho"],
			dangerMode: true
		})
		.then((willDelete) => {
			if (willDelete) {

				$.ajax({
					url: 'action/eco_alterarStatusFase.php',
					type: 'POST',
					data: {
						cdItLicencaFase: cdItLicencaFase,
						status: 'C'
					},
					success: function(data){
						$("#divResult").html(data);
						refreshAbasObjetos(cdItLicencaFase);
					}
				})
				.done(function() {
					console.log("success");
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});

			} else {
				swal({
					icon: "info",
					title: "Nada foi alterado",
					text: "A fase não foi deletada!"
				});
			}
		});
	}

	function reabrirFase(f){

		var cdItLicencaFase	= $(f).find("input[name=cdItLicencaFase]").val();

		swal({
			title: "Só para confirmar...",
			text: "Tem certeza que deseja REABIR esta fase?",
			icon: "warning",
			buttons: ["Talvez mais tarde", "Sim, tenho"],
			dangerMode: true
		})
		.then((willDelete) => {
			if (willDelete) {

				$.ajax({
					url: 'action/eco_alterarStatusFase.php',
					type: 'POST',
					data: {
						cdItLicencaFase: cdItLicencaFase,
						status: 'R'
					},
					success: function(data){
						$("#divResult").html(data);
						refreshAbasObjetos(cdItLicencaFase);
					}
				})
				.done(function() {
					console.log("success");
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});

			} else {
				swal({
					icon: "info",
					title: "Nada foi alterado",
					text: "A fase não foi modificada!"
				});
			}
		});
	}

	function addComentarioFase(f){

		var it = $(f).data("cod");

		$.ajax({
			url: 'action/eco_addComentarioFase.php',
			type: 'POST',
			data: {cdItLicencaFase: it, dsComentario: $("#dsComentario"+it).val()},
			success: function(data){
				$("#divResult").html(data);
			}
		})
		.done(function() {
			$("div#listComentariosFase"+it).load("action/eco_viewListComentarioFase.php?cdItLicencaFase="+it);

			//limpa campo
			$("#dsComentario"+it).val('');
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	}

	function refreshAbasObjetos(cdItLicencaFase){
		$("#tabViewLicencaConsultoria").load("action/eco_viewListObjetoLicenca.php?cdItLicencaFase="+cdItLicencaFase+"&cdCatObjetoLicenca=1");
		$("#tabViewLicencaAssessoria").load("action/eco_viewListObjetoLicenca.php?cdItLicencaFase="+cdItLicencaFase+"&cdCatObjetoLicenca=2");
	}
</script>