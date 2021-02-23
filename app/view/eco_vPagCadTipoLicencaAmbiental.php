<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$tpLicencaAmb = new cTpLicencaAmbiental();
?>
<div class="container-fluid">
	<div class="col-white">
		<h4>Cadastro de Tipo de Licença Ambiental</h4>
	</div>
	<br/>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
				<div class="header">
					<h2>
						Lista de Tipo de Licença Ambiental
					</h2>
					<ul class="header-dropdown m-r--5">
						<li class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
								<i class="material-icons">more_vert</i>
							</a>
							<ul class="dropdown-menu pull-right">
								<li><a data-toggle="modal" href="#modalFormCadTpLicencaAmbiental" class="waves-effect waves-block"><i class="material-icons">add</i> Adicionar</a></li>
							</ul>
						</li>
					</ul>
				</div>
				<div class="body">
					<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
						<thead>
							<tr>
								<th width="30px">Código</th>
								<th>Tipo da Licença Ambiental</th>
								<th width="50px">Status</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th width="30px">Código</th>
								<th>Tipo da Licença Ambiental</th>
								<th width="50px">Status</th>
							</tr>
						</tfoot>
						<tbody>
							<?php
							$tpLicencaAmb->listTable();
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="modalFormCadTpLicencaAmbiental">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="formCadTpLicencaAmbiental">
				<div class="modal-header bg-deep-purple">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="material-icons pull-left" style="margin-top: -3px; padding-right: 5px;">add</i> Formulário de Cadastro de Tipo de Licença Ambiental</h4>
				</div>
				<div class="modal-body">
					<br/>
					<div class="row clearfix">
						<div class="col-sm-12">
							<div class="form-group">
								<div class="form-line">
									<label>Tipo da Licença Ambiental:</label>
									<input type="text" name="dsTpLicencaAmbiental" class="form-control">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><i class="material-icons">close</i> Fechar</button>
					<button type="submit" class="btn bg-amber waves-effect"><i class="material-icons">save</i> Salvar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="modalFormAlterTpLicencaAmbiental">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="formAlterTpLicencaAmbiental">
				<div class="modal-header bg-deep-purple">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="material-icons pull-left" style="margin-top: -3px; padding-right: 5px;">add</i> Formulário de Alteração de Tipo de Licença Ambiental</h4>
				</div>
				<div class="modal-body">
					<br/>
					<div class="row clearfix">
						<div class="col-sm-4">
							<div class="form-group">
								<div class="form-line">
									<label>Cód. do Tipo:</label>
									<input type="text" name="cdTpLicencaAmbiental" class="form-control" readonly>
								</div>
							</div>
						</div>
						<div class="clearfix">

						</div>
						<div class="col-sm-10">
							<div class="form-group">
								<div class="form-line">
									<label>Tipo da Licença Ambiental:</label>
									<input type="text" name="dsTpLicencaAmbiental" class="form-control">
								</div>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<div class="form-line">
									<label>Ativo?</label>
									<select class="form-control show-tick" name="snAtivo" data-live-search="true" autofocus="off">
										<option value="S">Sim</option>
										<option value="N">Não</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><i class="material-icons">close</i> Fechar</button>
					<button type="submit" class="btn bg-amber waves-effect"><i class="material-icons">save</i> Salvar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	$.AdminBSB.input.activate();
	$.AdminBSB.select.activate();

	$('.js-basic-example').DataTable({
		responsive: true,
		"columnDefs": [
    		{ "type": "num", "targets": 0 }
  		]
	});

	$(document).ready(function(){
		$("div.form-line.focused").removeClass("focused");
	});

	$("#formCadTpLicencaAmbiental").validate({
		rules:{
			nmOrgaoLicenciado: "required"
		},
		messages:{
			nmOrgaoLicenciado: "O nome do orgão não pode ser vazio"
		},
		highlight: function (input) {
			$(input).parents('.form-line').addClass('error');
		},
		unhighlight: function (input) {
			$(input).parents('.form-line').removeClass('error');
		},
		errorPlacement: function (error, element) {
			$(element).parents('.form-group').append(error);
		},
		success: "valid",
		submitHandler: function(form){

			$.ajax({
				url: 'action/eco_cadTipoLicencaAmbiental.php',
				type: 'POST',
				data: $(form).serialize(),
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

			return false;
		}
	});

	$("#formAlterTpLicencaAmbiental").validate({
		rules:{
			cdOrgaoLicenciado: "required",
			nmOrgaoLicenciado: "required",
			snAtivo: "required"
		},
		messages:{
			cdOrgaoLicenciado: "O código do orgão não pode ser vazio",
			nmOrgaoLicenciado: "O nome do orgão não pode ser vazio",
			snAtivo: "Você tem que escolher o status do orgão"
		},
		highlight: function (input) {
			$(input).parents('.form-line').addClass('error');
		},
		unhighlight: function (input) {
			$(input).parents('.form-line').removeClass('error');
		},
		errorPlacement: function (error, element) {
			$(element).parents('.form-group').append(error);
		},
		success: "valid",
		submitHandler: function(form){

			$.ajax({
				url: 'action/eco_alterTipoLicencaAmbiental.php',
				type: 'POST',
				data: $(form).serialize(),
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

			return false;
		}
	});

	function preencheFormAlterTpLicencaAmbiental(c,n,a){
		$("#formAlterTpLicencaAmbiental input[name=cdTpLicencaAmbiental]").val(c);
		$("#formAlterTpLicencaAmbiental input[name=dsTpLicencaAmbiental]").val(n);
		$("#formAlterTpLicencaAmbiental select[name=snAtivo]").val(a).trigger("change");
	}

</script>