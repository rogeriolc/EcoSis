<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$tpRevisao		= new cTpRevisao();

$nmArquivo 	= basename($_SERVER['PHP_SELF']);
?>
<div class="container-fluid">
	<div class="col-white">
		<h4>Cadastro de Tipos de Revisões</h4>
	</div>
	<br/>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
				<div class="header">
					<h2>
						Lista de Tipos de Revisões
					</h2>
					<ul class="header-dropdown m-r--5">
						<li class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
								<i class="material-icons">more_vert</i>
							</a>
							<ul class="dropdown-menu pull-right">
								<li><a data-toggle="modal" href="#modalFormCadTpRevisao" class="waves-effect waves-block"><i class="material-icons">add</i> Adicionar</a></li>
							</ul>
						</li>
					</ul>
				</div>
				<div class="body">
					<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
						<thead>
							<tr>
								<th width="30px">Código</th>
								<th>Descrição</th>
								<th width="50px">Status</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th width="30px">Código</th>
								<th>Descrição</th>
								<th width="50px">Status</th>
							</tr>
						</tfoot>
						<tbody>
							<?php
							$tpRevisao->listTable();
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="modalFormCadTpRevisao">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="formCadTpRevisao">
				<div class="modal-header bg-deep-purple">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="material-icons pull-left" style="margin-top: -3px; padding-right: 5px;">add</i> Formulário de Cadastro de Tipo de Revisão</h4>
				</div>
				<div class="modal-body">
					<br/>
					<div class="row clearfix">
						<div class="col-sm-12">
							<div class="form-group">
								<div class="form-line">
									<label>Descrição:</label>
									<input type="text" name="dsTpRevisao" class="form-control">
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

<div class="modal fade" id="modalFormAlterTpRevisao">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="formAlterTpRevisao">
				<div class="modal-header bg-deep-purple">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="material-icons pull-left" style="margin-top: -3px; padding-right: 5px;">add</i> Formulário de Alteração de Tipo de Revisão</h4>
				</div>
				<div class="modal-body">
					<br/>
					<div class="row clearfix">
						<div class="col-sm-4">
							<div class="form-group">
								<div class="form-line">
									<label>Cód:</label>
									<input type="text" name="cdTpRevisao" class="form-control" readonly>
								</div>
							</div>
						</div>
						<div class="clearfix">

						</div>
						<div class="col-sm-10">
							<div class="form-group">
								<div class="form-line">
									<label>Descrição:</label>
									<input type="text" name="dsTpRevisao" class="form-control">
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

	$("#formCadTpRevisao").validate({
		rules:{
			dsTpRevisao: "required"
		},
		messages:{
			dsTpRevisao: "O Descrição não pode ser vazio"
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
				url: 'action/g_cadTpRevisao.php',
				type: 'POST',
				data: $(form).serialize(),
				success: function(data){
					$("#divResult").html(data);
				}
			})
			.done(function() {
				console.log("success");
				$("#modalFormCadTpRevisao").modal("hide");
				setTimeout(function(){
					refresh(path);
				},1000);
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

	$("#formAlterTpRevisao").validate({
		rules:{
			cdTpRevisao: "required",
			dsTpRevisao: "required",
			snAtivo: "required"
		},
		messages:{
			cdTpRevisao: "O código do Tipo de Revisão não pode ser vazio",
			dsTpRevisao: "O Descrição não pode ser vazio",
			snAtivo: "Você tem que escolher o status do Tipo de Revisão"
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
				url: 'action/g_alterTpRevisao.php',
				type: 'POST',
				data: $(form).serialize(),
				success: function(data){
					$("#divResult").html(data);
				}
			})
			.done(function() {
				console.log("success");
				$("#modalFormAlterTpRevisao").modal("hide");
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

	function preencheFormAlterTpRevisao(c,n,a){
		$("#formAlterTpRevisao input[name=cdTpRevisao]").val(c);
		$("#formAlterTpRevisao input[name=dsTpRevisao]").val(n);
		$("#formAlterTpRevisao select[name=snAtivo]").val(a).trigger("change");
	}

</script>