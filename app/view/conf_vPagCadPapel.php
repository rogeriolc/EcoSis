<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$papel  = new cPapel();
$pagina = new cPagina();
?>
<div class="container-fluid">
	<div class="col-white">
		<h4>Cadastro de Papéis</h4>
	</div>
	<br/>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
				<div class="header">
					<h2>
						Lista de Papéis
					</h2>
					<ul class="header-dropdown m-r--5">
						<li class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
								<i class="material-icons">more_vert</i>
							</a>
							<ul class="dropdown-menu pull-right">
								<li><a  data-toggle="modal" href="#modalformCadPapel" class="waves-effect waves-block"><i class="material-icons">add</i> Adicionar</a></li>
							</ul>
						</li>
					</ul>
				</div>
				<div class="body">
					<table class="table table-bordered table-striped table-hover dataTable">
						<thead>
							<tr>
								<th width="30px">Código</th>
								<th>Descrição do Papel</th>
								<th width="50px" class="text-center">Status</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th width="30px">Código</th>
								<th>Descrição da Papel</th>
								<th width="50px" class="text-center">Status</th>
							</tr>
						</tfoot>
						<tbody>
							<?php $papel->listTable(); ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="modalformCadPapel">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id="formCadPapel">
				<div class="modal-header bg-deep-purple">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="material-icons pull-left" style="margin-top: -3px; padding-right: 5px;">add</i> Formulário de Cadastro de Papéis</h4>
				</div>
				<div class="modal-body">
					<br/>
					<div class="row clearfix">
						<div class="col-sm-12">
							<div class="form-group">
								<div class="form-line">
									<label>Descrição do Papel:</label>
									<input type="text" name="dsPapel" class="form-control" placeholder="Digite a descrição do perfil..." autocomplete="off">
								</div>
							</div>
						</div>
					</div>

					<ul class="nav nav-tabs tab-nav-right tab-col-purple" role="tablist">
						<li role="presentation" class="active"><a href="#papelPagina" data-toggle="tab">Permissões de Página</a></li>
					</ul>

					<!-- Tab panes -->
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane fade in active" id="papelPagina">
							<table class="table table-hover table-striped table-condensed dataTable">
								<thead>
									<tr>
										<th>Nome da página</th>
										<th>Descrição da Página</th>
										<th>Módulo</th>
										<th class="col-md-2"></th>
									</tr>
								</thead>
								<tbody>
									<?php

									$mysql = MysqlConexao::getInstance();

									$sql = "SELECT p.cd_pagina, p.nm_pagina, m.nm_modulo, p.ds_pagina FROM g_pagina p, g_modulo m WHERE p.sn_ativo = 'S' AND m.cd_modulo = p.cd_modulo AND ds_caminho IS NOT NULL ORDER BY p.nr_ordem ASC";
									$stmt = $mysql->prepare($sql);
									$stmt->bindParam(":cd_usuario", $cd_usuario);
									$result = $stmt->execute();
									if($result){
										while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
											echo '
											<tr>
											<td><label for="checkPagina'.md5($reg->cd_pagina).'">'.$reg->nm_pagina.'</label></td>
											<td>'.$reg->ds_pagina.'</td>
											<td>'.$reg->nm_modulo.'</td>
											<td>
											<input type="checkbox" id="checkPagina'.md5($reg->cd_pagina).'" name="cdPagina[]" value="'.base64_encode($reg->cd_pagina).'" class="filled-in chk-col-green">
											<label for="checkPagina'.md5($reg->cd_pagina).'"></label>
											</td>
											</tr>
											';
										}
									}

									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><i class="material-icons col-red">close</i> Fechar</button>
					<button type="submit" class="btn bg-green waves-effect"><i class="material-icons">save</i> Salvar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="modalFormAlterPapel">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id="formAlterPapel">
				<input type="hidden" name="cdPapel" />
				<div class="modal-header bg-deep-purple">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="material-icons pull-left" style="margin-top: -3px; padding-right: 5px;">add</i> Formulário de Alteração de Papéis</h4>
				</div>
				<div class="modal-body">
					<br/>
					<div class="row clearfix">
						<div class="col-sm-10">
							<div class="form-group">
								<div class="form-line">
									<label>Descrição do Papel:</label>
									<input type="text" name="dsPapel" class="form-control" placeholder="Digite a descrição do perfil..." autocomplete="off">
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

					<ul class="nav nav-tabs tab-nav-right tab-col-purple" role="tablist">
						<li role="presentation" class="active"><a href="#permPagina" data-toggle="tab">Permissões de Página</a></li>
					</ul>

					<!-- Tab panes -->
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane fade in active" id="permPagina">
							<table class="table table-hover table-striped table-condensed dataTable">
								<thead>
									<tr>
										<th>Nome da página</th>
										<th>Descrição da Página</th>
										<th>Módulo</th>
										<th class="col-md-2"></th>
									</tr>
								</thead>
								<tbody>
									<?php $pagina->listTableCheck(); ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><i class="material-icons col-red">close</i> Fechar</button>
					<button type="submit" class="btn bg-green waves-effect"><i class="material-icons">save</i> Salvar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="scripts"></div>

<script type="text/javascript">
	$.AdminBSB.input.activate();
	$.AdminBSB.select.activate();

	$('.dataTable').DataTable({
		responsive: true,
		"bLengthChange" : false,
		"scrollY":        "300px",
		"scrollCollapse": true,
		"paging":         false,
		"columnDefs": [
    		{ "type": "num", "targets": 0 }
  		]
	});

	$(document).ready(function(){
		$("div.form-line.focused").removeClass("focused");
	});

	$("#formCadPapel").validate({
		rules:{
			dsPapel: "required"
		},
		messages:{
			dsPapel: "O nome do papel não pode ser vazio"
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
				url: 'action/conf_cadPapel.php',
				type: 'POST',
				data: $("#formCadPapel").serialize(),
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

	$("#formAlterPapel").validate({
		rules:{
			cdPapel: "required",
			dsPapel: "required",
			snAtivo: "required"
		},
		messages:{
			cdPapel: "O código do papel não pode ser vazio",
			dsPapel: "O nome do papel não pode ser vazio",
			snAtivo: "Você tem que escolher um status para o papel"
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
				url: 'action/conf_alterPapel.php',
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

	function preencheformAlterPapel(c,n,sn){
		$("#formAlterPapel input[name=cdPapel]").val(c);
		$("#formAlterPapel input[name=dsPapel]").val(n);
		$("#formAlterPapel select[name=snAtivo]").val(sn).trigger("change");

		$("#scripts").load("action/conf_listPagina.php?cdPapel="+c);
	}

</script>