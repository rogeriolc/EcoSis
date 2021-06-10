<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$pUsuario = new cPerfilUsuario;
$pagina = new cPagina;

$mysql = MysqlConexao::getInstance();

?>
<div class="container-fluid">
	<div class="col-white">
		<h4>Cadastro de Perfil de Usuário</h4>
	</div>
	<br/>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
				<div class="header">
					<h2>
						Lista de Perfil de Usuários
					</h2>
					<ul class="header-dropdown m-r--5">
						<li class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
								<i class="material-icons">more_vert</i>
							</a>
							<ul class="dropdown-menu pull-right">
								<li><a  data-toggle="modal" href="#modalformCadPerfilUser" class="waves-effect waves-block"><i class="material-icons">add</i> Adicionar</a></li>
							</ul>
						</li>
					</ul>
				</div>
				<div class="body">
					<table class="table table-bordered table-striped table-hover dataTable">
						<thead>
							<tr>
								<th width="30px">Código</th>
								<th>Descrição do Perfil</th>
								<th width="50px" class="text-center">Status</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th width="30px">Código</th>
								<th>Descrição do Perfil</th>
								<th width="50px" class="text-center">Status</th>
							</tr>
						</tfoot>
						<tbody>
							<?php $pUsuario->listTable(); ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="modalformCadPerfilUser">
	<form id="formCadPerfilUser">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-deep-purple">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="material-icons pull-left" style="margin-top: -3px; padding-right: 5px;">add</i> Formulário de Cadastro de Perfil do Usuário</h4>
				</div>
				<div class="modal-body">
					<br/>
					<div class="row clearfix">
						<div class="col-sm-12">
							<div class="form-group">
								<div class="form-line">
									<label>Descrição do Perfil:</label>
									<input type="text" name="dsPerfilUsuario" class="form-control" placeholder="Digite a descrição do perfil..." autocomplete="off">
								</div>
							</div>
						</div>
					</div>

					<table class="table table-hover table-striped table-condensed dataTable">
						<thead>
							<tr>
								<th class="text-left">Permissões</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php

							$sql = "SELECT cd_permissao_sis, ds_permissao_sis FROM g_permissao_sis ORDER BY 2 DESC";
							$stmt = $mysql->prepare($sql);
							$result = $stmt->execute();
							if ($result) {
								while ($reg = $stmt->fetch(PDO::FETCH_OBJ)) {
									echo '
									<tr>
									<td class="text-right">'.$reg->ds_permissao_sis.'</td>
									<td>
									<input type="checkbox" id="checkPermissao'.md5($reg->cd_permissao_sis).'" name="cdPermissao[]" value="'.base64_encode($reg->cd_permissao_sis).'" class="filled-in chk-col-green">
									<label for="checkPermissao'.md5($reg->cd_permissao_sis).'"></label>
									</td>
									</tr>
									';
								}
							}else{
								// echo '<option>'.var_dump($stmt->errorInfo()).'</option>';
							}

							?>
						</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><i class="material-icons col-red">close</i> Fechar</button>
					<button type="submit" class="btn bg-green waves-effect"><i class="material-icons">save</i> Salvar</button>
				</div>
			</div>
		</div>
	</form>
</div>

<div class="modal fade" id="modalFormAlterPerfilUsuario">
	<div class="modal-dialog">
		<form id="formAlterPerfilUser">
			<input type="hidden" name="cdPerfilUsuario" />
			<div class="modal-content">
				<div class="modal-header bg-deep-purple">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="material-icons pull-left" style="margin-top: -3px; padding-right: 5px;">add</i> Formulário de Cadastro de Perfil do Usuário</h4>
				</div>
				<div class="modal-body">
					<br/>
					<div class="row clearfix">
						<div class="col-sm-10">
							<div class="form-group">
								<div class="form-line">
									<label>Descrição do Perfil:</label>
									<input type="text" name="dsPerfilUsuario" class="form-control" placeholder="Digite a descrição do perfil..." autocomplete="off">
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

					<table class="table table-hover table-striped table-condensed dataTable">
						<thead>
							<tr>
								<th class="text-left">Permissões</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php

							$sql = "SELECT cd_permissao_sis, ds_permissao_sis FROM g_permissao_sis ORDER BY 2 DESC";
							$stmt = $mysql->prepare($sql);
							$result = $stmt->execute();
							if ($result) {
								while ($reg = $stmt->fetch(PDO::FETCH_OBJ)) {
									echo '
									<tr>
										<td class="text-left">'.$reg->ds_permissao_sis.'</td>
										<td>
											<input type="checkbox" id="editCheckPermissao'.md5($reg->cd_permissao_sis).'" name="cdPermissao[]" value="'.base64_encode($reg->cd_permissao_sis).'" class="filled-in  chk-col-green">
											<label for="editCheckPermissao'.md5($reg->cd_permissao_sis).'"></label>
										</td>
									</tr>
									';
								}
							}else{
								// echo '<option>'.var_dump($stmt->errorInfo()).'</option>';
							}

							?>
						</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><i class="material-icons col-red">close</i> Fechar</button>
					<button type="submit" class="btn bg-green waves-effect"><i class="material-icons">save</i> Salvar</button>
				</div>
			</div>
		</form>
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

	$("#formCadPerfilUser").validate({
		rules:{
			dsPerfilUsuario: "required"
		},
		messages:{
			dsPerfilUsuario: "O nome do perfil não pode ser vazio"
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
				url: 'action/conf_cadPerfilUser.php',
				type: 'POST',
				data: $("#formCadPerfilUser").serialize(),
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

	$("#formAlterPerfilUser").validate({
		rules:{
			cdAssociado: "required",
			nmAssociado: "required",
			snAtivo: "required"
		},
		messages:{
			cdAssociado: "O código do associado não pode ser vazio",
			nmAssociado: "O nome do associado não pode ser vazio",
			snAtivo: "Você tem que escolher um status para o associado"
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
				url: 'action/conf_alterPerfilUsuario.php',
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

	function preencheformAlterPerfilUser(c,n,sn){
		$("#formAlterPerfilUser input[name=cdPerfilUsuario]").val(c);
		$("#formAlterPerfilUser input[name=dsPerfilUsuario]").val(n);
		$("#formAlterPerfilUser select[name=snAtivo]").val(sn).trigger("change");

		$("#formAlterPerfilUser input[type=checkbox]").prop('checked', false);

		$("#scripts").load("action/conf_listPermissaoPerfil.php?cdPerfilUsuario="+c);
	}

</script>