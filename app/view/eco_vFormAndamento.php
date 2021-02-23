<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mdc-bg-grey-100" id="divAndamento">
		<br>
		<div class="container-fluid">
			<?php if ($tpStatus != "S") { ?>
			<a href="#cadNovoAndamamento" class="btn bg-deep-purple btn-sm pull-right" data-toggle="collapse" aria-expanded="false" aria-controls="cadNovoAndamamento"><i class="material-icons">add</i></a>
			<?php } ?>
			<p class="lead">Andamentos</p>
		</div>
		<br>

		<div class="card rounded collapse" id="cadNovoAndamamento">
			<input type="hidden" name="cdAtividade" value="<?php echo $cdAtividade; ?>" />
			<div class="header bg-deep-purple">
				<i class="material-icons pull-left p-r-10 m-t--3 ">add</i>
				<h2>Novo andamento</h2>
			</div>
			<div class="body">
				<div class="row">
					<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
						<div class="form-group">
							<div class="form-line">
								<label>Data do protocolo</label>
								<input type="text" name="dtProtocolo" class="form-control datepicker">
							</div>
						</div>
					</div>
					<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
						<div class="form-group">
							<label>Responsável</label>
							<div class="form-line">
								<select name="cdResponsavel" style="width:100%" class="form-control" data-live-search="true">
									<option></option>
									<?php $usuarios->listOption(); ?>
								</select>
							</div>
						</div>
					</div>
					<!-- <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
						<div class="form-group">
							<label>Cliente</label>
							<div class="form-line">
								<select name="cdCliente" style="width:100%" class="form-control" data-live-search="true">
									<option></option>
									<?php $cliente->listOption(); ?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
						<div class="form-group">
							<label>Órgão Licenciador</label>
							<div class="form-line">
								<select name="cdOrgaoLicenciador" style="width:100%" class="form-control" data-live-search="true">
									<option></option>
									<?php $orgaoLic->listOption(); ?>
								</select>
							</div>
						</div>
					</div> -->
					<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
						<label>Descrição</label>
						<div class="form-group">
							<div class="form-line">
								<textarea name="dsAndamento" class="form-control" rows="3"></textarea>
							</div>
						</div>
					</div>
					<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
						<div class="form-group">
							<div class="form-line">
								<label>Prazo</label>
								<input type="text" name="dtPrazo" class="form-control datepicker" />
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="body">
				<div id="divMsgAndamento"></div>
				<div class="text-right">
					<button type="button" class="btn bg-green" onclick="cadAndamento()"><i class="material-icons">save</i> Salvar</button>
				</div>
			</div>
		</div>

		<div class="card rounded">
			<div class="body">
				<table class="table table-hover table-striped" id="tableAndamentos">
					<thead>
						<tr>
							<th>Cód</th>
							<th>Data protocolo</th>
							<th>Prazo</th>
							<th>Descrição do andamento</th>
							<th>Usuário Resp.</th>
							<!-- <th>Cliente Resp.</th>
							<th>Orgão Resp.</th> -->
							<th>Anexos</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						<?php $atv->ListarItAtividade(); ?>
					</tbody>
				</table>
			</div>
		</div>

		<div class="hide" id="cardViewAndamento">

		</div>
	</div>
</div>
<script type="text/javascript">

	$('.datepicker').datetimepicker({
		format: 'DD/MM/YYYY'
	});

	$(document).ready(function(){
		//Dropzone
		// var myDropzone = new Dropzone("#formUploadAnexoAndamento", { url: "action/eco_uploadProtocolo.php", addRemoveLinks: true });
		// //atualiza lista de anexos
		// myDropzone.on("complete", function (file) {
		// 	// if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {

		// 	// 	var cdAtividade = $("#formUploadAnexoAndamento").find('input[type=hidden][name=cdAtividade]').val();

		// 	// 	$.ajax({
		// 	// 		url: 'action/eco_listarAnexos.php',
		// 	// 		type: 'POST',
		// 	// 		data: {cdAtv: cdAtividade},
		// 	// 		success: function(data){
		// 	// 			$("#listAnexo > div.row").html(data);
		// 	// 		}
		// 	// 	})
		// 	// 	.done(function() {
		// 	// 		console.log("success");
		// 	// 		setTimeout(function(){
		// 	// 			Dropzone.forElement("#formUploadAnexoAndamento").removeAllFiles(true);
		// 	// 		},1000)
		// 	// 	})
		// 	// 	.fail(function() {
		// 	// 		console.log("error");
		// 	// 	})
		// 	// 	.always(function() {
		// 	// 		console.log("complete");
		// 	// 	});

		// 	// }
		// });

		$("div.form-line.focused").removeClass("focused");
	});

	var table = $("#tableAndamentos").dataTable({
		"dom": '<"toolbar pull-right">frt<"bottom">p',
		"columnDefs": [
		{ "type": 'num', "targets": 0 }
		],
		"order": [[ 0, "desc" ]],
		"scrollY":        "500px",
        "scrollCollapse": true,
        "paging":         false
	});

	// $("#formAtividade").submit(function(){

	// 	$.ajax({
	// 		url: 'action/eco_alterAtividade.php',
	// 		type: 'POST',
	// 		data: $(this).serialize(),
	// 		success: function(data){

	// 			$("#msgAtividade").html(data);

	// 		}
	// 	})
	// 	.done(function() {
	// 		console.log("success");
	// 	})
	// 	.fail(function() {
	// 		console.log("error");
	// 	})
	// 	.always(function() {
	// 		console.log("complete");
	// 	});

	// 	return false;

	// });

	function cadAndamento(){

		var frm = $("#cadNovoAndamamento");

		var frmCdAtividade 	 = frm.find("input[name=cdAtividade]").val();
		var frmDtProtocolo 	 = frm.find("input[name=dtProtocolo]").val();
		var frmDtPrazo 	 	 = frm.find("input[name=dtPrazo]").val();
		var frmcdResponsavel = frm.find("select[name=cdResponsavel]").val();
		var frmcdOrgaoLic 	 = frm.find("select[name=cdOrgaoLicenciador]").val();
		var frmcdCliente 	 = frm.find("select[name=cdCliente]").val();
		var frmDsAndamento 	 = frm.find("textarea[name=dsAndamento]").val();

		$.ajax({
			url: 'action/eco_cadAndamento.php',
			type: 'POST',
			data: {
				cdAtividade: frmCdAtividade,
				dtProtocolo: frmDtProtocolo,
				dtPrazo: frmDtPrazo,
				cdResponsavel: frmcdResponsavel,
				cdOrgaoLicenciador: frmcdOrgaoLic,
				cdCliente: frmcdCliente,
				dsAndamento: frmDsAndamento
			},
			success: function(data){
				$("#divMsgAndamento").html(data);
			}
		})
		.done(function() {
			console.log("success");
			setTimeout(() => {

				$("#cadNovoAndamamento").collapse("toggle");

				$.ajax({
					url: 'action/eco_listTableItAtividade.php',
					type: 'POST',
					data: {
						cdAtividade: frmCdAtividade
					},
					success: function(data){
						table.fnDestroy();
						$("#tableAndamentos tbody").html(data);

						$("#tableAndamentos").dataTable({
							"dom": '<"toolbar pull-right">frt<"bottom">p',
							"columnDefs": [
							{ "type": 'num', "targets": 0 }
							],
							"order": [[ 0, "desc" ]]
						});
					}
				})
				.done(function() {
					console.log("success");
					
					consultoriaList.clear();
					assessoriaList.clear();
					
					//assessoria
					$.ajax({
						url: 'action/eco_returnJsonAtividades.php',
						type: 'GET',
						datatype: 'json',
						data: { cdServico: '<?php echo $cdServico; ?>', tpAtividade: 'A' },
						success: function(data){
							ListValues = data;
							assessoriaData = data;

							console.log(data);
						}
					})
					.done(function() {
						assessoriaList = new List('tabAssessoria', ListOptions, ListValues);
					})
					.fail(function() {
					})
					.always(function() {
					});

					//consultoria
					$.ajax({
						url: 'action/eco_returnJsonAtividades.php',
						type: 'GET',
						datatype: 'json',
						data: { cdServico: '<?php echo $cdServico; ?>', tpAtividade: 'C' },
						success: function(data){
							ListValues = data;
							consultoriaList = data;
						}
					})
					.done(function() {
						consultoriaList = new List('tabConsultoria', ListOptions, ListValues);
					})
					.fail(function() {
					})
					.always(function() {
					});
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});

			}, 500);
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});

	}

	// $("div.toolbar").html('<ul class="list-inline p-l-10"><li><a href="javascript:void(0)" class="btn bg-deep-purple btn-sm"><i class="material-icons">add</i></a></li></ul>');

	$("tr.viewAndamento").click(function(){
		const cod = $(this).data('cod');

		console.log(cod);

		$("#cardViewAndamento").removeClass('hide');

		$('#divAndamento').animate({
			scrollTop: $("#cardViewAndamento").offset().top
		}, 500);

		$.ajax({
			url: 'action/eco_viewFormAndamento.php',
			type: 'POST',
			data: {cdItAtividade: $(this).data('cod')},
			success: function(data){
				$("#cardViewAndamento").html(data);
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

	function DestruirDataTable(){

		table.fnDestroy();

	}
</script>