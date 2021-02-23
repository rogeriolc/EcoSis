<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdItAtividade 	= $_POST['cdItAtividade'];

$usuarios 		= new cUsuario;
$cliente 		= new cCliente;
$orgaoLic 		= new cOrgaoLicenciado;

$atv 			= new cAtividade();
$atv->setCdItAtividade($cdItAtividade);

$atv->DadosItAtividade();

$cdAtividade 		= $atv->getCdAtividade();
$dtProtocolo 		= $atv->getDtProtocolo();
$dtPrazo 			= $atv->getDtPrazo();
$dsAndamento 		= $atv->getDsAndamento();
$cdResponsavel 		= $atv->getCdResponsavel();
$cdCliente 			= $atv->getCdCliente();
$cdOrgaoLicenciador	= $atv->getCdOrgaoLicenciador();
$tpStatus 			= $atv->getTpStatus();

$atv->Dados();

$cdServico = $atv->cdServico;

//Configura exibição do menu
switch ($tpStatus) {
	//Concluído
	case 'O':
	case 'C':
	$optMenu = '
	<li>
	<a href="javascript:void(0);" onclick="reabrirItAtividade(this)" data-cod="'.$cdItAtividade.'" data-atv="'.$cdAtividade.'" class="waves-effect waves-block"><i class="material-icons" style="color: #FFC107 !important">refresh</i> Reabrir</a>
	</li>';
	$disabled = 'disabled';
	break;

	//Em andamento
	//Reaberta
	//Atrasado
	case 'E':
	case 'R':
	case 'A':
	$optMenu = '
	<li>
	<a href="javascript:void(0);" onclick="salvarItAtividade(this)" data-cod="'.$cdItAtividade.'" class="waves-effect waves-block"><i class="material-icons" style="color: #3F51B5 !important">save</i> Salvar</a>
	</li>
	<li>
	<a href="javascript:void(0);" onclick="tramiteItAtividade(this)" data-cod="'.$cdItAtividade.'" data-atv="'.$cdAtividade.'" class="waves-effect waves-block"><i class="material-icons" style="color: #FFC107 !important">compare_arrows</i> Em trâmite</a>
	</li>
	<li>
	<a href="javascript:void(0);" onclick="concluirItAtividade(this)" data-cod="'.$cdItAtividade.'" data-atv="'.$cdAtividade.'" class="waves-effect waves-block"><i class="material-icons col-green" style="color: #4CAF50 !important">done</i> Concluir</a>
	</li>
	<li>
	<a href="javascript:void(0);" onclick="cancelarItAtividade(this)" data-cod="'.$cdItAtividade.'" data-atv="'.$cdAtividade.'" class="waves-effect waves-block"><i class="material-icons col-red" style="color: #F44336 !important">block</i> Cancelar</a>
	</li>';
	$disabled = null;
	break;

	case 'T':
	$optMenu = '
	<li>
	<a href="javascript:void(0);" onclick="salvarItAtividade(this)" data-cod="'.$cdItAtividade.'" class="waves-effect waves-block"><i class="material-icons" style="color: #3F51B5 !important">save</i> Salvar</a>
	</li>
	<li>
	<a href="javascript:void(0);" onclick="concluirItAtividade(this)" data-cod="'.$cdItAtividade.'" data-atv="'.$cdAtividade.'" class="waves-effect waves-block"><i class="material-icons col-green" style="color: #4CAF50 !important">done</i> Concluir</a>
	</li>
	<li>
	<a href="javascript:void(0);" onclick="cancelarItAtividade(this)" data-cod="'.$cdItAtividade.'" data-atv="'.$cdAtividade.'" class="waves-effect waves-block"><i class="material-icons col-red" style="color: #F44336 !important">block</i> Cancelar</a>
	</li>';
	$disabled = null;
	break;

	default:
	$optMenu = '';
	$disabled = null;
	break;
}


?>
<div class="card rounded">
	<div class="header bg-deep-purple">

		<?php if ($atv->dsStatus != "S") { ?>

		<ul class="m-r--5 pull-right" style="list-style: none; margin-bottom: 0;">
			<li class="dropdown">
				<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
					<i class="material-icons mdc-text-grey-600">more_vert</i>
				</a>
				<ul class="dropdown-menu pull-right">
					<?php echo $optMenu; ?>
				</ul>
			</li>
		</ul>

		<?php } ?>

		<i class="material-icons pull-left col-deep-purple" style="padding-right: 8px; margin-top: -2px;">info</i>
		<h2>Sobre o andamento</h2>
	</div>
	<div class="body">
		<input type="hidden" name="cdItAtividade" value="<?php echo $cdItAtividade;?>">
		<div class="row">
			<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
				<div class="form-group">
					<div class="form-line">
						<label>Data do protocolo</label>
						<input type="text" name="dtProtocolo" class="form-control datepicker" value="<?php echo date('d/m/Y', strtotime($dtProtocolo)); ?>" <?php echo $disabled; ?>>
					</div>
				</div>
			</div>
			<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
				<div class="form-group">
					<label>Usuário Responsável</label>
					<div class="form-line">
						<select name="cdResponsavel" style="width:100%" class="form-control" data-live-search="true" <?php echo $disabled; ?>>
							<option></option>
							<?php $usuarios->listOption($cdResponsavel); ?>
						</select>
					</div>
				</div>
			</div>
			<!-- <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
				<div class="form-group">
					<label>Cliente</label>
					<div class="form-line">
						<select name="cdCliente" style="width:100%" class="form-control" data-live-search="true" <?php echo $disabled; ?>>
							<option></option>
							<?php $cliente->listOption($cdCliente); ?>
						</select>
					</div>
				</div>
			</div>
			<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
				<div class="form-group">
					<label>Órgão Licenciador</label>
					<div class="form-line">
						<select name="cdOrgaoLicenciador" style="width:100%" class="form-control" data-live-search="true" <?php echo $disabled; ?>>
							<option></option>
							<?php $orgaoLic->listOption($cdOrgaoLicenciador); ?>
						</select>
					</div>
				</div>
			</div> -->
			<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
				<label>Descrição</label>
				<div class="form-group">
					<div class="form-line">
						<textarea name="dsAndamento" class="form-control" rows="3" <?php echo $disabled; ?>><?php echo $dsAndamento; ?></textarea>
					</div>
				</div>
			</div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
				<div class="form-group">
					<div class="form-line">
						<label>Prazo</label>
						<input type="text" name="dtPrazo" class="form-control datepicker" value="<?php echo date('d/m/Y', strtotime($dtPrazo)); ?>" <?php echo $disabled; ?>>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div role="tabpanel">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs tab-col-deep-purple" role="tablist">
			<li role="presentation" class="active">
				<a href="#formAnexo" aria-controls="formAnexo" role="tab" data-toggle="tab"><i class="material-icons col-deep-purple">attach_file</i> Anexos</a>
			</li>
			<li role="presentation">
				<a href="#listAnexoAndamento" aria-controls="listAnexoAndamento" role="tab" data-toggle="tab"><i class="material-icons col-deep-purple">folder</i> Documentos Anexados</a>
			</li>
			<li role="presentation">
				<a href="#tabEnviaEmail" aria-controls="tabEnviaEmail" role="tab" data-toggle="tab"><i class="material-icons col-deep-purple">mail</i> Enviar e-mail</a>
			</li>
			<li role="presentation">
				<a href="#tabProdutoFinal" aria-controls="tabProdutoFinal" role="tab" data-toggle="tab"><i class="material-icons col-deep-purple">check</i> Produtos Finais</a>
			</li>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="formAnexo">
				<form action="action/eco_uploadAnexoAtividade.php" id="formUploadAnexoAndamento" class="dropzone" method="POST" target="_blank" enctype="multipart/form-data">
					<input type="hidden" name="cdItAtividade" value="<?php echo $cdItAtividade;?>">
					<div class="dz-message">
						<div class="drag-icon-cph">
							<i class="material-icons">touch_app</i>
						</div>
						<h3>Solte seus arquivos aqui ou clique para fazer o <i>Upload</i>.</h3>
					</div>
					<div class="fallback">
						<input name="file" type="file" multiple />
					</div>
					<button type="submit" class="hidden">button</button>
				</form>
			</div>
			<div role="tabpanel" class="tab-pane" id="listAnexoAndamento">
				<div class="container-fluid">
					<div class="row">
						<?php $atv->ListarAnexos(); ?>
					</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="tabEnviaEmail">
				<div class="container-fluid">
					<form id="formEnviaEmailAndamento" method="POST">
						<input type="hidden" name="cdAtividade" value="<?php echo $cdAtividade;?>">
						<div class="row">
							<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
								<div class="form-group">
									<label>Destinatário:</label>
									<div class="form-line">
										<select name="cdUsuarioDestinatario" style="width:100%" class="form-control" data-live-search="true">
											<option></option>
											<?php $usuarios->listOption(); ?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
								<div class="form-group">
									<div class="form-line">
										<label>Prazo</label>
										<input type="text" name="dtPrazo" class="form-control datepicker">
									</div>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label>Assunto:</label>
							<div class="form-line">
								<input type="text" name="dsAssunto" class="form-control" autocomplete="off" />
							</div>
						</div>
						<div class="form-group">
							<label>Mensagem:</label>
							<div class="form-line">
								<textarea class="form-control" name="dsMensagem" rows="5" autocomplete="off"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label>Anexo:</label>
							<div class="form-line">
								<input type="file" name="fileAnexo">
							</div>
						</div>

						<button type="submit" class="btn bg-deep-purple"><i class="material-icons">send</i> Enviar</button>

					</form>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="tabProdutoFinal">
				<div class="mdc-bg-grey-200 p-a-20 m-t--15">
					<form id="formAnexoProduto">
						<input type="hidden" name="cdItAtividade" value="<?php echo base64_encode($cdItAtividade); ?>">
						<input type="file" name="dsAnexo">
					</form>
				</div>
				<br>
				<div class="container-fluid" id="produtosItAtividade">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<table class="table table-condensed table-hover">
								<thead>
									<tr>
										<th></th>
										<th class="text-left">Descrição</th>
										<th>Data de emissão</th>
										<th>Data de validade</th>
										<th>Opções</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$produtos = cAtividade::getProdutosAssessoria($cdItAtividade);

									foreach ($produtos as $produto) {

										$dtEmissao = (!is_null($produto->dt_emissao) && !empty($produto->dt_emissao)) ? date("d/m/Y", strtotime($produto->dt_emissao)) : null;
										$dtValidade = (!is_null($produto->dt_validade) && !empty($produto->dt_validade)) ? date("d/m/Y", strtotime($produto->dt_validade)) : null;

										echo '
										<tr>
										<td>
										<a href="repo/eco/assessoria/'.$cdItAtividade.'/'.$produto->cd_doc_assessoria.'/'.$produto->ds_anexo.'" target="_blank">
										<i class="material-icons">insert_drive_file</i>
										<br>
										'.$produto->ds_anexo.'
										</a>
										</td>
										<td style="vertical-align: middle !important">
										<div class="form-group m-b-0">
										<div class="form-line m-b-0">
										<input type="text" name="ds_documento" onchange="updateDadosDocAssessoria(this)" data-cod="'.$produto->cd_doc_assessoria.'" class="form-control" placeholder="Digite a descrição do produto..." value="'.$produto->ds_documento.'">
										</div>
										</div>
										</td>
										<td style="vertical-align: middle !important">
										<div class="form-group m-b-0">
										<div class="form-line m-b-0">
										<input type="text" name="dt_emissao" data-cod="'.$produto->cd_doc_assessoria.'" class="form-control datepicker" placeholder="Selecione a data de emissão..." value="'.$dtEmissao.'">
										</div>
										</div>
										</td>
										<td style="vertical-align: middle !important">
										<div class="form-group m-b-0">
										<div class="form-line m-b-0">
										<input type="text" name="dt_validade" data-cod="'.$produto->cd_doc_assessoria.'" class="form-control datepicker" placeholder="Selecione a data de validade..." value="'.$dtValidade.'">
										</div>
										</div>
										</td>
										<td style="vertical-align: middle !important">
										<a href="javascript:void(0)" onclick="removerDocumentoAssessoria(\''.$produto->cd_doc_assessoria.'\')">
										<i class="material-icons col-red">delete</i>
										</a>
										</td>
										</tr>
										';
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="msgItAtividade"></div>


<script type="text/javascript">

	$.AdminBSB.input.activate();
	$.AdminBSB.select.activate();

	$('.datepicker').datetimepicker({
		format: 'DD/MM/YYYY'
	});

	$('#produtosItAtividade .datepicker').datetimepicker({
		format: 'DD/MM/YYYY'
	})
	.on('dp.change', function (e){
		var val 	= e.date.format("DD/MM/YYYY");
		var cod 	= e.currentTarget.dataset.cod;
		var name 	= e.currentTarget.name;

		$.ajax({
			url: 'action/eco_alterDadosDocAssessoria.php',
			type: 'POST',
			data: {
				value: val,
				cdDocAssessoria: cod,
				coluna: name
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
	});

	$(document).ready(function(){
		//Dropzone
		var myDropzone = new Dropzone("#formUploadAnexoAndamento", { url: "action/eco_uploadProtocolo.php", addRemoveLinks: true });
		//atualiza lista de anexos
		myDropzone.on("complete", function (file) {
			if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {

				var cdItAtividade = $("#formUploadAnexoAndamento").find('input[type=hidden][name=cdItAtividade]').val();

				$.ajax({
					url: 'action/eco_listarAnexos.php',
					type: 'POST',
					data: {cdItAtv: cdItAtividade},
					success: function(data){
						$("#listAnexoAndamento div.row").html(data);
					}
				})
				.done(function() {
					console.log("success");
					setTimeout(function(){
						Dropzone.forElement("#formUploadAnexoAndamento").removeAllFiles(true);
					},1000)
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});

			}
		});

		$("div.form-line.focused").removeClass("focused");
	});

	function recarregarFormAndamento(){
		$.ajax({
			url: 'action/eco_viewFormAndamento.php',
			type: 'POST',
			data: {cdItAtividade: <?php echo $cdItAtividade; ?>},
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
	}

	function salvarItAtividade(a){

		$.ajax({
			url: 'action/eco_alterItAtividade.php',
			type: 'POST',
			data: {
				cdItAtividade: 		$(a).data("cod"),
				dtProtocolo: 		$("#cardViewAndamento input[name=dtProtocolo]").val(),
				dtPrazo: 			$("#cardViewAndamento input[name=dtPrazo]").val(),
				cdResponsavel: 		$("#cardViewAndamento select[name=cdResponsavel]").val(),
				cdCliente: 			$("#cardViewAndamento select[name=cdCliente]").val(),
				cdOrgaoLicenciador: $("#cardViewAndamento select[name=cdOrgaoLicenciador]").val(),
				dsAndamento: 		$("#cardViewAndamento textarea[name=dsAndamento]").val()
			},
			success: function(data){
				$("#msgItAtividade").html(data);
			}
		})
		.done(function() {


			$.ajax({
				url: 'action/eco_listTableItAtividade.php',
				type: 'POST',
				data: {cdAtividade: '<?php echo $cdAtividade; ?>'},
				success: function(data){

					DestruirDataTable();

					$("#tableAndamentos tbody").html(data);

					$("#tableAndamentos").dataTable({
						"dom": '<"toolbar pull-right">frt<"bottom">p',
						"columnDefs": [
						{ "type": 'num', "targets": 0 }
						],
						"order": [[ 0, "desc" ]],
						"scrollY":        "500px",
						"scrollCollapse": true,
						"paging":         false
					});

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


	$("#formEnviaEmailAndamento").submit(function(){

		var formData = new FormData(this);

		$.ajax({
			url: 'action/eco_enviaEmailAndamento.php',
			type: 'POST',
			enctype: 'multipart/form-data',
            processData: false, // impedir que o jQuery tranforma a "data" em querystring
            contentType: false, // desabilitar o cabeçalho "Content-Type"
            cache: false, // desabilitar o "cache"
            timeout: 600000, // definir um tempo limite (opcional)
			data: formData, //$(this).serialize(),
			success: function(data){
				$("#msgItAtividade").html(data);
			}
		})
		.done(function() {
			setTimeout(() => {

				$.ajax({
					url: 'action/eco_listTableItAtividade.php',
					type: 'POST',
					data: {
						cdAtividade: '<?php echo $cdAtividade; ?>'
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

		return false;
	});

	function concluirItAtividade(a){

		$.ajax({
			url: 'action/eco_concluirAndamento.php',
			type: 'POST',
			data: {
				cdAndamento: $(a).data('cod'),
				cdAtividade: $(a).data('atv')
			},
			success: function(data){
				$("#msgItAtividade").html(data);
			}
		})
		.done(function() {
			setTimeout(() => {

				$.ajax({
					url: 'action/eco_listTableItAtividade.php',
					type: 'POST',
					data: {
						cdAtividade: '<?php echo $cdAtividade; ?>'
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

		return false;

	}

	function cancelarItAtividade(a){

		$.ajax({
			url: 'action/eco_cancelarAndamento.php',
			type: 'POST',
			data: {
				cdAndamento: $(a).data('cod'),
				cdAtividade: $(a).data('atv')
			},
			success: function(data){
				$("#msgItAtividade").html(data);
			}
		})
		.done(function() {
			setTimeout(() => {

				$.ajax({
					url: 'action/eco_listTableItAtividade.php',
					type: 'POST',
					data: {
						cdAtividade: '<?php echo $cdAtividade; ?>'
					},
					success: function(data){
						table.fnDestroy();
						$("#tableAndamentos tbody").html(data);

						$("#tableAndamentos").dataTable({
							"dom": '<"toolbar pull-right">frt<"bottom">p',
							"columnDefs": [
							{ "type": 'num', "targets": 0 }
							],
							"order": [[ 0, "desc" ]],
							"scrollY":        "500px",
							"scrollCollapse": true,
							"paging":         false
						});
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

			}, 500);
			
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

		
		return false;
	}

	function reabrirItAtividade(a){

		$.ajax({
			url: 'action/eco_reabrirAndamento.php',
			type: 'POST',
			data: {
				cdAndamento: $(a).data('cod'),
				cdAtividade: $(a).data('atv')
			},
			success: function(data){
				$("#msgItAtividade").html(data);
			}
		})
		.done(function() {
			setTimeout(() => {

				$.ajax({
					url: 'action/eco_listTableItAtividade.php',
					type: 'POST',
					data: {
						cdAtividade: '<?php echo $cdAtividade; ?>'
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
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});

			}, 500);

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

		return false;

	}

	function tramiteItAtividade(a){

		$.ajax({
			url: 'action/eco_tramiteAndamento.php',
			type: 'POST',
			data: {
				cdAndamento: $(a).data('cod'),
				cdAtividade: $(a).data('atv')
			},
			success: function(data){
				$("#msgItAtividade").html(data);
			}
		})
		.done(function() {
			setTimeout(() => {

				$.ajax({
					url: 'action/eco_listTableItAtividade.php',
					type: 'POST',
					data: {
						cdAtividade: '<?php echo $cdAtividade; ?>'
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
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});

			}, 500);

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

		return false;

	}

	$("#formAnexoProduto input[name=dsAnexo]").change(function(){
		var file = $(this).val();
		var it_atividade = $("#formAnexoProduto input[name=cdItAtividade]").val();

		$("#formAnexoProduto").submit();
	});

	$("#formAnexoProduto").submit(function() {
		var formData = new FormData(this);

		$.ajax({
			url: 'action/eco_addDocumentoAssessoria.php',
			type: 'POST',
			enctype: 'multipart/form-data',
            processData: false, // impedir que o jQuery tranforma a "data" em querystring
            contentType: false, // desabilitar o cabeçalho "Content-Type"
            cache: false, // desabilitar o "cache"
            timeout: 600000, // definir um tempo limite (opcional)
            data: formData,
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


	function removerDocumentoAssessoria(cod)
	{
		$.ajax({
			url: 'action/removerDocumentoAssessoria.php',
			type: 'POST',
			data: {cdDocAssessoria: cod},
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

	function updateDadosDocAssessoria(e)
	{
		var val = $(e).val();
		var cod = $(e).data("cod");
		var name = $(e).attr("name");

		$.ajax({
			url: 'action/eco_alterDadosDocAssessoria.php',
			type: 'POST',
			data: {
				value: val,
				cdDocAssessoria: cod,
				coluna: name
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
</script>