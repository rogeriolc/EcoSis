<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$porteEmpre = new cPorteEmpreendimento();

$nmArquivo 	= basename($_SERVER['PHP_SELF']);

?>
<div class="container-fluid">
	<div class="col-white">
		<h4>Cadastro de Portes dos Empreendimentos</h4>
	</div>
	<br/>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
				<div class="header">
					<h2>
						Lista de Portes dos Empreendimentos
					</h2>
					<ul class="header-dropdown m-r--5">
						<li class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
								<i class="material-icons">more_vert</i>
							</a>
							<ul class="dropdown-menu pull-right">
								<li><a data-toggle="modal" href="#modalFormCadPorteEmpreendimento" class="waves-effect waves-block"><i class="material-icons">add</i> Adicionar</a></li>
							</ul>
						</li>
					</ul>
				</div>
				<div class="body">
					<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
						<thead>
							<tr>
								<th width="30px">Código</th>
								<th>Nome do porte do empreendimento</th>
								<th width="50px">Status</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th width="30px">Código</th>
								<th>Nome do porte do empreendimento</th>
								<th width="50px">Status</th>
							</tr>
						</tfoot>
						<tbody>
							<?php
							$porteEmpre->listTable();
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="modalFormCadPorteEmpreendimento">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="formCadPorteEmpreendimento">
				<div class="modal-header bg-deep-purple">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="material-icons pull-left" style="margin-top: -3px; padding-right: 5px;">add</i> Formulário de Cadastro de Portes dos Empreendimentos</h4>
				</div>
				<div class="modal-body">
					<br/>
					<div class="row clearfix">
						<div class="col-sm-12">
							<div class="form-group">
								<div class="form-line">
									<label>Nome do Porte do Empreendimento:</label>
									<input type="text" name="dsPorteEmpreendimento" class="form-control">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><i class="material-icons">close</i> Fechar</button>
					<button type="submit" class="btn bg-green waves-effect"><i class="material-icons">save</i> Salvar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="modalFormAlterPorteEmpreendimento">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="formAlterPorteEmpreendimento">
				<div class="modal-header bg-deep-purple">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="material-icons pull-left" style="margin-top: -3px; padding-right: 5px;">add</i> Formulário de Alteração de Portes dos Empreendimentos</h4>
				</div>
				<div class="modal-body">
					<br/>
					<div class="row clearfix">
						<div class="col-sm-5">
							<div class="form-group">
								<div class="form-line">
									<label>Cód. Porte do Empreendimento:</label>
									<input type="text" name="cdPorteEmpreendimento" class="form-control" readonly>
								</div>
							</div>
						</div>
						<div class="clearfix">

						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<div class="form-line">
									<label>Nome do Porte do Empreendimento:</label>
									<input type="text" name="dsPorteEmpreendimento" class="form-control">
								</div>
							</div>
						</div>
						<div class="col-sm-3">
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
					<button type="submit" class="btn bg-green waves-effect"><i class="material-icons">save</i> Salvar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	$.AdminBSB.input.activate();
	$.AdminBSB.select.activate();

	var path = '<?php echo $nmArquivo;?>';

	function refresh(p){
		$("#divConteudo").load("view/"+p);
		$("div.overlay").trigger('click');
	}

	$('.js-basic-example').DataTable({
		responsive: true,
		"columnDefs": [
    		{ "type": "num", "targets": 0 }
  		]
	});

	$(document).ready(function(){
		$("div.form-line.focused").removeClass("focused");
	});

	$("#formCadPorteEmpreendimento").validate({
		rules:{
			dsPorteEmpreendimento: "required"
		},
		messages:{
			dsPorteEmpreendimento: "O nome do Porte do Empreendimento não pode ser vazio"
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
				url: 'action/eco_cadPorteEmpreendimento.php',
				type: 'POST',
				data: $(form).serialize(),
				success: function(data){
					$("#divResult").html(data);
				}
			})
			.done(function() {
				console.log("success");
				$("#modalFormCadPorteEmpreendimento").modal("hide");
				setTimeout(function(){
					refresh(path);
				},1500);
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

	$("#formAlterPorteEmpreendimento").validate({
		rules:{
			cdPorteEmpreendimento: "required",
			dsPorteEmpreendimento: "required",
			snAtivo: "required"
		},
		messages:{
			cdPorteEmpreendimento: "O código do Porte do Empreendimento não pode ser vazio",
			dsPorteEmpreendimento: "O nome do Porte do Empreendimento não pode ser vazio",
			snAtivo: "Você tem que escolher o status do Porte do Empreendimento"
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
				url: 'action/eco_alterPorteEmpreendimento.php',
				type: 'POST',
				data: $(form).serialize(),
				success: function(data){
					$("#divResult").html(data);
				}
			})
			.done(function() {
				console.log("success");
				$("#modalFormAlterPorteEmpreendimento").modal("hide");
				setTimeout(function(){
					refresh(path);
				},1500);
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

	function preencheFormAlterPorteEmpreendimento(c,n,a){
		$("#formAlterPorteEmpreendimento input[name=cdPorteEmpreendimento]").val(c);
		$("#formAlterPorteEmpreendimento input[name=dsPorteEmpreendimento]").val(n);
		$("#formAlterPorteEmpreendimento select[name=snAtivo]").val(a).trigger("change");
	}

</script>