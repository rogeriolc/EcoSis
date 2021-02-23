<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$objLicenca 	= new cObjetoLicenca();
$catObjLicenca  = new cCatObjetoLicenca();
$fase 			= new cFaseObjeto();

$nmArquivo 		= basename($_SERVER['PHP_SELF']);
?>
<div class="container-fluid">
	<div class="col-white">
		<h4>Cadastro de Objeto da Licença Ambiental</h4>
	</div>
	<br/>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
				<div class="header">
					<h2>
						Lista de Objeto da Licença Ambiental
					</h2>
					<ul class="header-dropdown m-r--5">
						<li class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
								<i class="material-icons">more_vert</i>
							</a>
							<ul class="dropdown-menu pull-right">
								<li><a data-toggle="modal" href="#modalFormCadObjetoLicenca" class="waves-effect waves-block"><i class="material-icons">add</i> Adicionar</a></li>
							</ul>
						</li>
					</ul>
				</div>
				<div class="body">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
							<thead>
								<tr>
									<th width="30px">Código</th>
									<th>Tipo da Licença Ambiental</th>
									<th>Categoria</th>
									<th width="50px">Status</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th width="30px">Código</th>
									<th>Tipo da Licença Ambiental</th>
									<th>Categoria</th>
									<th width="50px">Status</th>
								</tr>
							</tfoot>
							<tbody>
								<?php
								$objLicenca->listTable();
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="modalFormCadObjetoLicenca">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id="formCadObjetoLicenca">
				<div class="modal-header bg-deep-purple">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="material-icons pull-left" style="margin-top: -3px; padding-right: 5px;">add</i> Formulário de Cadastro de Objeto da Licença Ambiental</h4>
				</div>
				<div class="modal-body">
					<br/>
					<div class="row clearfix">
						<div class="col-md-8 col-lg-8">
							<div class="form-group">
								<div class="form-line">
									<label>Descrição do Objeto:</label>
									<input type="text" name="dsObjetoLicenca" class="form-control">
								</div>
							</div>
						</div>
						<div class="col-md-4 col-lg-4">
							<div class="form-group">
								<div class="form-line">
									<label>Categoria:</label>
									<select class="select2 form-control" name="cdCatObjetoLicenca">
										<option value=""></option>
										<?php $catObjLicenca->listOption(); ?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-md-4 col-lg-4">
							<div class="form-group">
								<label>Pedir protocolo?</label>
								<div class="switch">
									<label><input type="checkbox" name="snPedirProtocolo" value="S"><span class="lever switch-col-green"></span></label>
								</div>
							</div>
						</div>
					</div>

					<!-- Nav tabs -->
					<ul class="nav nav-tabs tab-nav-right" role="tablist">
						<li role="presentation" class="active"><a href="#tabFaseObj" data-toggle="tab">Fases</a></li>
					</ul>

					<!-- Tab panes -->
					<div class="tab-content">
						<div class="row">
							<div class="col-xs-2 col-sm-2 col-md-1 col-lg-1 text-center" style="border-right: 2px solid #eeeeee;">
								<a href="javascript:void(0)" onclick="addFase(this)" class="col-green"><i class="material-icons">add</i></a>
							</div>
							<div class="col-xs-10 col-sm-10 col-md-11 col-lg-11">
								<div role="tabpanel" class="tab-pane fade in active" id="tabFaseObj">
									<table class="table table-condensed table-hover table-striped table-bordered tableFaseObj">
										<tbody>

										</tbody>
									</table>
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

<div class="modal fade" id="modalFormAlterObjetoLicenca">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id="formAlterObjetoLicenca">
				<div class="modal-header bg-deep-purple">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="material-icons pull-left" style="margin-top: -3px; padding-right: 5px;">add</i> Formulário de Alteração de Objeto da Licença Ambiental</h4>
				</div>
				<div class="modal-body">
					<br/>
					<div class="row clearfix">
						<div class="col-sm-4">
							<div class="form-group">
								<div class="form-line">
									<label>Cód. do Tipo:</label>
									<input type="text" name="cdObjetoLicenca" class="form-control" readonly>
								</div>
							</div>
						</div>
						<div class="clearfix">

						</div>
						<div class="col-md-8 col-lg-8">
							<div class="form-group">
								<div class="form-line">
									<label>Descrição do Objeto:</label>
									<input type="text" name="dsObjetoLicenca" class="form-control">
								</div>
							</div>
						</div>
						<div class="col-md-4 col-lg-4">
							<div class="form-group">
								<div class="form-line">
									<label>Categoria:</label>
									<select class="select2 form-control" name="cdCatObjetoLicenca">
										<option value=""></option>
										<?php $catObjLicenca->listOption(); ?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-md-4 col-lg-4">
							<div class="form-group">
								<label>Pedir protocolo?</label>
								<div class="switch">
									<label><input type="checkbox" name="snPedirProtocolo" value="S"><span class="lever switch-col-green"></span></label>
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
					<!-- Nav tabs -->
					<ul class="nav nav-tabs tab-nav-right" role="tablist">
						<li role="presentation" class="active"><a href="#tabFaseObj" data-toggle="tab">Fases</a></li>
					</ul>

					<!-- Tab panes -->
					<div class="tab-content">
						<div class="row">
							<div class="col-xs-2 col-sm-2 col-md-1 col-lg-1 text-center" style="border-right: 2px solid #eeeeee;">
								<a href="javascript:void(0)" onclick="addFase(this)" class="col-green"><i class="material-icons">add</i></a>
							</div>
							<div class="col-xs-10 col-sm-10 col-md-11 col-lg-11">
								<div role="tabpanel" class="tab-pane fade in active" id="tabFaseObj">
									<table class="table table-condensed table-hover table-striped table-bordered tableFaseObj">
										<tbody>

										</tbody>
									</table>
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

	$(".tableFaseObj tbody").sortable({
		placeholder: "ui-state-highlight"
	});

	function addFase(t){

		var form = $(t).closest("form").attr('id');

		$("#"+form+" .tableFaseObj").append('<tr> <td class="cursorMove col-md-1 col-xs-1 text-center text-middle"><i class="fas fa-ellipsis-v"></i><i class="fas fa-ellipsis-v"></i></td><td> <select class="form-control select2" name="cdFase[]" style="width: 100%;"><?php $fase->listOption(); ?></select> </td> <td class="col-md-1 col-xs-2  text-center text-middle"> <a href="javascript:void(0)" onclick="removerFase(this)" class="col-red"> <i class="material-icons">delete</i> </a> </td> </tr>');

		$.AdminBSB.select.activate();
	}

	function removerFase(f){
		$(f).closest("tr").remove();
	}

	$("#formCadObjetoLicenca").validate({
		rules:{
			dsObjetoLicenca: "required",
			cdCatObjetoLicenca: "required"
		},
		messages:{
			dsObjetoLicenca: "A descrição do objeto não pode ser vazio",
			cdCatObjetoLicenca: "Você tem que escolher a categoria do objeto"
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
				url: 'action/eco_cadObjetoLicenca.php',
				type: 'POST',
				data: $(form).serialize(),
				success: function(data){
					$("#divResult").html(data);
				}
			})
			.done(function() {
				console.log("success");
				$("#modalFormCadObjetoLicenca").modal("hide");
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

	$("#formAlterObjetoLicenca").validate({
		rules:{
			cdObjetoLicenca: "required",
			dsObjetoLicenca: "required",
			cdCatObjetoLicenca: "required"
		},
		messages:{
			cdObjetoLicenca: "O código do objeto não pode ser vazio",
			dsObjetoLicenca: "A descrição do objeto não pode ser vazio",
			cdCatObjetoLicenca: "Você tem que escolher a categoria do objeto"
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
				url: 'action/eco_alterObjetoLicenca.php',
				type: 'POST',
				data: $(form).serialize(),
				success: function(data){
					$("#divResult").html(data);
				}
			})
			.done(function() {
				console.log("success");
				$("#modalFormAlterObjetoLicenca").modal("hide");
				setTimeout(function(){
					refresh(path);
				},1000)
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

	function preencheFormAlterObjetoLicenca(c,n,cat,pro,a){
		$("#formAlterObjetoLicenca input[name=cdObjetoLicenca]").val(c);
		$("#formAlterObjetoLicenca input[name=dsObjetoLicenca]").val(n);
		$("#formAlterObjetoLicenca select[name=cdCatObjetoLicenca]").val(cat).trigger("change");
		$("#formAlterObjetoLicenca select[name=snAtivo]").val(a).trigger("change");

		if(pro == 'S'){

			$("#formAlterObjetoLicenca input[name=snPedirProtocolo]").prop("checked",true);

		}else{

			$("#formAlterObjetoLicenca input[name=snPedirProtocolo]").prop("checked",false);

		}

		$("#formAlterObjetoLicenca .tableFaseObj tbody").html('<i class="fas fa-spinner fa-spin"></i> Carregando...');

		$.ajax({
			url: 'action/eco_viewListFormObjetoLicenca.php',
			type: 'POST',
			data: {cdObjetoLicenca: c},
			success: function(data){
				$("#formAlterObjetoLicenca .tableFaseObj tbody").html(data);
				$.AdminBSB.select.activate();
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

</script>