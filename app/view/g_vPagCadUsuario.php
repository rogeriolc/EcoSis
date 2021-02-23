<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$usuarios 		= new cUsuario;
$empreendimento = new cEmpreendimento;
$papel  		= new cPapel;
$pUsuario  		= new cPerfilUsuario;
?>
<div class="container-fluid">
	<div class="col-white">
		<h4>Cadastro de Usuário</h4>
	</div>
	<br/>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
				<div class="header">
					<h2>
						Lista de Usuários
					</h2>
					<ul class="header-dropdown m-r--5">
						<li class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
								<i class="material-icons">more_vert</i>
							</a>
							<ul class="dropdown-menu pull-right">
								<li><a  data-toggle="modal" href="#modalFormCadUsuario" class="waves-effect waves-block"><i class="material-icons">add</i> Adicionar</a></li>
							</ul>
						</li>
					</ul>
				</div>
				<div class="body">
					<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
						<thead>
							<tr>
								<th width="30px">Código</th>
								<th>Nome do usuário</th>
								<th>Login</th>
								<th>Email</th>
								<th width="50px">Status</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th width="30px">Código</th>
								<th>Nome do usuário</th>
								<th>Login</th>
								<th>Email</th>
								<th width="50px">Status</th>
							</tr>
						</tfoot>
						<tbody>
							<?php $usuarios->listTableUsuario(); ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="modalFormCadUsuario">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="formCadUsuario">
				<div class="modal-header bg-deep-purple">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="material-icons pull-left" style="margin-top: -3px; padding-right: 5px;">add</i> Formulário de Cadastro de Usuários</h4>
				</div>
				<div class="modal-body" style="padding: 1px 5px;">
					<!-- Nav tabs -->
					<ul class="nav nav-tabs tab-nav-right tab-col-green" role="tablist">
						<li role="presentation" class="active"><a href="#tabDadosUsuario" data-toggle="tab" aria-expanded="false">DADOS GERAIS</a></li>
						<li role="presentation" class=""><a href="#tabPermissoes" data-toggle="tab" aria-expanded="false">PERMISSÕES</a></li>
						<!-- <li role="presentation" class=""><a href="#messages" data-toggle="tab" aria-expanded="false">MESSAGES</a></li> -->
						<!-- <li role="presentation" class=""><a href="#settings" data-toggle="tab" aria-expanded="true">SETTINGS</a></li> -->
					</ul>

					<!-- Tab panes -->
					<div class="tab-content" style="padding:  15px">
						<div role="tabpanel" class="tab-pane fade active in" id="tabDadosUsuario">
							<div class="row clearfix">
								<div class="col-sm-6">
									<div class="form-group">
										<div class="form-line">
											<label>Nome do usuário:</label>
											<input type="text" name="nmUsuario" class="form-control">
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<div class="form-line">
											<label>Login:</label>
											<input type="text" name="login" class="form-control">
										</div>
									</div>
								</div>
								<div class="clearfix">

								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<div class="form-line">
											<label>Senha:</label>
											<input type="password" name="dsSenha" class="form-control">
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<div class="form-line">
											<label>Confirmação da Senha:</label>
											<input type="password" name="dsConfirmaSenha" class="form-control">
										</div>
									</div>
								</div>
								<div class="clearfix">

								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<div class="form-line">
											<label>E-mail:</label>
											<input type="text" name="dsEmail" class="form-control">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane fade" id="tabPermissoes">
							<div class="row clearfix">
								<div class="col-sm-12">
									<div class="form-group">
										<div class="form-line">
											<label>Perfil do Usuário:</label>
											<select class="form-control show-tick" name="cdPerfilUsuario" data-live-search="true" autofocus="off">
												<?php $pUsuario->listOption(); ?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<div class="form-line">
											<label>Papel:</label>
											<select class="form-control show-tick" name="cdPapel[]" data-live-search="true" autofocus="off" multiple>
												<?php $papel->listOption(); ?>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane" id="messages">
						</div>
						<div role="tabpanel" class="tab-pane fade" id="settings">
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

<div class="modal fade" id="modalFormAlterUsuario">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="formAlterUsuario">
				<div class="modal-header bg-deep-purple">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="material-icons pull-left" style="margin-top: -3px; padding-right: 5px;">add</i> Formulário de Alteração de Usuários</h4>
				</div>
				<div class="modal-body"  style="padding: 1px 5px;">
					<!-- Nav tabs -->
					<ul class="nav nav-tabs tab-nav-right tab-col-deep-purple" role="tablist">
						<li role="presentation" class="active"><a href="#tabEditDadosUsuario" data-toggle="tab" aria-expanded="false">DADOS GERAIS</a></li>
						<li role="presentation" class=""><a href="#tabEditPermissoes" data-toggle="tab" aria-expanded="false">PERMISSÕES</a></li>
					</ul>

					<!-- Tab panes -->
					<div class="tab-content" style="padding:  15px">
						<div role="tabpanel" class="tab-pane fade active in" id="tabEditDadosUsuario">
							<div class="row clearfix">
								<div class="col-xs-4 col-sm-4 col-md-2 col-lg-2">
									<div class="form-group">
										<div class="form-line">
											<label>Código:</label>
											<input type="text" name="cdUsuario" class="form-control" autocomplete="off" readonly>
										</div>
									</div>
								</div>
								<div class="clearfix">

								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<div class="form-line">
											<label>Nome do usuário:</label>
											<input type="text" name="nmUsuario" class="form-control">
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<div class="form-line">
											<label>Login:</label>
											<input type="text" name="username" class="form-control">
										</div>
									</div>
								</div>
								<div class="clearfix">

								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<div class="form-line">
											<label>E-mail:</label>
											<input type="text" name="dsEmail" class="form-control">
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
						<div role="tabpanel" class="tab-pane fade" id="tabEditPermissoes">
							<div class="row clearfix">
								<div class="col-sm-12">
									<div class="form-group">
										<div class="form-line">
											<label>Perfil do Usuário:</label>
											<select class="form-control show-tick" name="cdPerfilUsuario" data-live-search="true" autofocus="off">
												<?php $pUsuario->listOption(); ?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<div class="form-line">
											<label>Papel:</label>
											<select class="form-control show-tick" name="cdPapel[]" data-live-search="true" autofocus="off" multiple>
												<?php $papel->listOption(); ?>
											</select>
										</div>
									</div>
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

	$('.js-basic-example').DataTable({
		responsive: true,
		"columnDefs": [
    		{ "type": "num", "targets": 0 }
  		]
	});

	$(document).ready(function(){
		$("div.form-line.focused").removeClass("focused");

		// $('input[name=dsSenha], input[name=dsConfirmaSenha]').pstrength();
	});

	$("#formCadUsuario").validate({
		rules:{
			nmUsuario: "required",
			username: "required",
			dsSenha: {
				required: true,
				minlength: 5
			},
			dsConfirmaSenha: {
				required: true,
				minlength: 5,
				equalTo: "input[name=dsSenha]"
			}
		},
		messages:{
			nmUsuario: "O nome do usuário não pode ser vazio",
			username: "O login não pode ser vazio",
			dsSenha: "A senha não pode ser vazia",
			dsConfirmaSenha: "A confirmação da senha não pode ser vazia",
			dsSenha: {
				required: "A senha não pode ser vazia",
				minlength: "Sua senha deve conter 5 ou mais caracteres"
			},
			dsConfirmaSenha: {
				required: "A confirmação da senha não pode ser vazia",
				minlength: "Sua senha deve conter 5 ou mais caracteres",
				equalTo: "A confirmação deve ser igual a senha"
			}
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
			$("#formCadUsuario").submit(function(){

				$.ajax({
					url: 'action/g_cadUsuario.php',
					type: 'POST',
					data: $(this).serialize(),
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
			});
		}
	});

	$("#formAlterUsuario").validate({
		rules:{
			cdUsuario: "required",
			nmUsuario: "required",
			username: "required",
			snAtivo: "required"
		},
		messages:{
			cdUsuario: "O código do usuário não pode ser vazio",
			nmUsuario: "O nome do usuário não pode ser vazio",
			username: "O login do usuário não pode ser vazio",
			snAtivo: "Você tem que escolher um status para o usuário"
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
				url: 'action/g_alterUsuario.php',
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

	function preencheFormAlterUsuario(c,n,u,e,p,sn){
		$("#formAlterUsuario input[name=cdUsuario]").val(c);
		$("#formAlterUsuario input[name=nmUsuario]").val(n);
		$("#formAlterUsuario input[name=username]").val(u);
		$("#formAlterUsuario input[name=dsEmail]").val(e);
		$("#formAlterUsuario select[name=cdPerfilUsuario]").val(p).trigger("change");
		$("#formAlterUsuario select[name=snAtivo]").val(sn).trigger("change");
	}

</script>