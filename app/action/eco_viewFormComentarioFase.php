<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$user = new cUsuario;
$atv  = new cAtividade;

$cdAtividadeFase = $_POST['cdAtividadeFase'];

$dados 			 = cAtividade::dadosFase($cdAtividadeFase);
$responsaveis 	 = cAtividade::getResponsaveisFase($cdAtividadeFase);

$dtPrazo		 = !is_null($dados->dt_prazo) ? date('d/m/Y', strtotime($dados->dt_prazo)) : null;

?>
<form id="formComentarioFase" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="cdAtividadeFase" value="<?php echo base64_encode($cdAtividadeFase); ?>">
	<div class="card rounded">
		<div class="header bg-deep-purple" style="padding: 10px;">
			<h5 class="card-title"><?php echo $dados->ds_fase_atividade; ?></h5>
			<!-- <div class="form-inline">
				<label>Responsável: </label>
				<select class="form-control" name="cdUsuarioResp" data-live-search="true">
					<option></option>
					<?php $user->listOption($dados->cd_usuario_responsavel); ?>
				</select>
			</div> -->
		</div>
		<div class="body" style="border-bottom: 1px solid #c9c9c9; padding: 5px 10px;">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 m-b-0">
					<small>Membros:</small>
					<ul class="list-unstyled list-inline" id="listFaseResponsaveis">
						<?php
						if ($responsaveis) {
							foreach ($responsaveis as $key => $responsavel) {
								echo '
								<li class="dropdown" title="'.$responsavel->nm_usuario.'">
								<a href="javascript:void(0);" class="dropdown-toggle btn bg-grey btn-circle waves-effect waves-circle waves-float" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
								<i class="material-icons" data-toggle="tooltip" data-placement="bottom">person</i>
								</a>
								<ul class="dropdown-menu">
								<li><a href="javascript:void(0);" class="waves-effect waves-block disabled">'.$responsavel->nm_usuario.'</a></li>
								<li><a href="javascript:void(0);" class="waves-effect waves-block" onclick="removerResponsavelFase(this)" data-user="'.base64_encode($responsavel->cd_usuario_responsavel).'"><i class="material-icons" style="color: #F44336 !important;">close</i> Remover desta fase</a></li>
								</ul>
								</li>
								';
							}
						}
						?>
						<li>
							<button type="button" class="btn btn-sm btn-default btn-popover" id="popoverChatUsers" data-container="body" data-toggle="popover" data-placement="bottom" title="Adicionar novo membro" data-content="<form><label>Selecione os responsáveis:</label><select class='form-control' name='cdUsuarioResp' onchange='addRespFase(this)' data-live-search='true'><option>&nbsp;</option><?php $user->listOption(); ?></select><br><button type='button' class='btn btn-block btn-default' onclick='closePopOverCharUsers()'>Fechar</button></form>">
							<i class="material-icons" style="color: #673ab7 !important">add</i></button>
						</li>
					</ul>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 m-b-0">
					<div class="form-group m-b-0">
						<small>Previsão de Entrega:</small>
						<div class="input-group m-b-0">
							<span class="input-group-addon">
								<i class="material-icons">calendar_today</i>
							</span>
							<div class="form-line">
								<input class="form-control datepicker" onchange="alterPrazoFaseAtividade(this)" name="dtPrazo" value="<?php echo $dtPrazo; ?>" />
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="body mdc-bg-grey-200" id="listComentario" style="height: 45vh; overflow-x: hidden;">
			<?php cAtividade::listComentarioFase($cdAtividadeFase); ?>
		</div>
		<div class="body">
			<div class="form-group">
				<div class="form-line">
					<textarea name="dsComentario" class="form-control" placeholder="Digite aqui sua mensagem..."></textarea>
				</div>
			</div>
			<div class="form-group">
				<div class="form-line">
					<input type="file" name="dsAnexo" class="form-control">
				</div>
			</div>
			<div class="text-right">
				<button type="submit" class="btn btn-lg bg-green"><i class="material-icons pull-left m-t--5">send</i>&nbsp; Enviar</button>
			</div>
		</div>
	</div>
</form>



<div id="resultComentario"></div>

<script type="text/javascript">
	$.AdminBSB.input.activate();
	$.AdminBSB.select.activate();

	$(document).ready(function(){
		$("div.form-line.focused").removeClass("focused");
		$(".btn-popover").popover({
			html: true
		});

		$('#formComentarioFase .datepicker').datetimepicker({
			format: 'DD/MM/YYYY'
		})
		.on('dp.change', function(e){
			var val = e.date.format("DD/MM/YYYY");

			$.ajax({
				url: 'action/eco_alterPrazoFaseAtividade.php',
				type: 'POST',
				data: {dtPrazo: val, cdAtividadeFase: '<?php echo base64_encode($cdAtividadeFase); ?>'},
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
		});
	});

	function closePopOverCharUsers() {
		$("#popoverChatUsers").popover('hide');
	}

	$('#dropdownMenu1').on('hide.bs.dropdown', function (e) {
		var target = $(e.target);
		if(target.hasClass("keepopen") || target.parents(".keepopen").length){
       		return false; // returning false should stop the dropdown from hiding.
       	}else{
       		return true;
       	}
       });

	$("#formComentarioFase").submit(function(){

		var formData = new FormData(this);

		$.ajax({
			url: 'action/eco_addComentarioFase.php',
			type: 'POST',
			enctype: 'multipart/form-data',
            processData: false, // impedir que o jQuery tranforma a "data" em querystring
            contentType: false, // desabilitar o cabeçalho "Content-Type"
            cache: false, // desabilitar o "cache"
            timeout: 600000, // definir um tempo limite (opcional)
			data: formData, //$(this).serialize(),
			success: function(data){
				$("#resultComentario").html(data);
			}
		})
		.done(function() {
			console.log("success");

			$('#formComentarioFase').trigger("reset");

			$.ajax({
				url: 'action/eco_listComentarioFase.php',
				type: 'POST',
				data: $("#formComentarioFase").serialize(),
				success: function(data){
					$("#listComentario").html(data);
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

		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});

		return false;
	});

	function addRespFase(e){

		var val = $(e).val();

		if (val != '') {

			$.ajax({
				url: 'action/eco_addResponsavelFase.php',
				type: 'POST',
				data: { cdUsuarioResp: $(e).val(), cdAtividadeFase: '<?php echo base64_encode($cdAtividadeFase); ?>' },
				success: function(data) {
					$("#resultComentario").html(data);
					closePopOverCharUsers();
				}
			})
			.done(function() {
				$.ajax({
					url: 'action/eco_viewFormComentarioFase.php',
					type: 'POST',
					data: { cdAtividadeFase: '<?php echo $cdAtividadeFase; ?>' },
					success: function(data){
						$("#comentarioFase").html(data);
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
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});

		}

	}

	function publicarAnexo(a){
		let comentario 	= $(a).data('comentario');
		let arquivo 	= $(a).data('arquivo');

		swal({
			title: "Tem certeza que deseja publicar este arquivo?",
			text: "Publicando o arquivo ele ficará disponível na aba: \"Documentos da Consultoria\" no resumo do serviço.",
			icon: "warning",
			buttons: true,
		})
		.then((response) => {
			if (response) {
				$.ajax({
					url: 'action/eco_publicarDocumentoConsultoria.php',
					type: 'POST',
					data: { cdAtividadeFaseComentario: comentario, dsAnexo: arquivo },
					success: function(data){
						$("#resultComentario").html(data);
					}
				})
				.done(function() {
					console.log("success");
					viewFormServico('<?php echo $dados->cd_servico; ?>', false, false)
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});

			} else {
				swal("Certo, fica para outra hora!", "Nada foi alterado.", "info");
			}
		});
	}

	function removerResponsavelFase(u){
		var user = $(u).data("user");

		swal("Tem certeza que deseja remover esse usuário?", {
			icon: "warning",
			buttons: {
				cancel: "Não me tire daqui!",
				catch: {
					text: "Sim, remova-o!",
					value: "remove",
				}
			},
		})
		.then((value) => {
			switch (value) {

				case "remove":

				$.ajax({
					url: 'action/eco_removerResponsavelFase.php',
					type: 'POST',
					data: {cdUsuarioResp: user, cdAtividadeFase: '<?php echo base64_encode($cdAtividadeFase); ?>'},
					success: function(data) {
						$("#divResult").html(data);
					}
				})
				.done(function() {
					$.ajax({
						url: 'action/eco_viewFormComentarioFase.php',
						type: 'POST',
						data: { cdAtividadeFase: '<?php echo $cdAtividadeFase; ?>' },
						success: function(data){
							$("#comentarioFase").html(data);
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
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});


				break;

			}
		});
	}
</script>