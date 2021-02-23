<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$nmArquivo = basename($_SERVER['PHP_SELF']);

$usuarios 		= new cUsuario;
$empreendimento = new cEmpreendimento;
$cliente 		= new cCliente;
$tpAtividade 	= new cTpAtividade;
$prop			= new cPropostaLicencaAmb;

?>
<div class="container-fluid">
	<div class="block-header">
		<div class="pull-right" style="z-index: 999">
			<a data-toggle="modal" href="#modalNovaProposta" class="btn bg-green waves-effect"><i class="material-icons">add</i> Nova Proposta</a>
		</div>
		<h2 style="color: white !important;">Propostas</h2>
	</div>
	<br/>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<!-- <div class="card">
				<div class="header">
					<h2>
						Lista de Propostas
					</h2>
					<ul class="header-dropdown m-r--5">
						<li class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
								<i class="material-icons">more_vert</i>
							</a>
							<ul class="dropdown-menu pull-right">
								<li><a data-toggle="modal" href="#modalNovaProposta" class="waves-effect waves-block"><i class="material-icons">add</i> Nova Proposta</a></li>
							</ul>
						</li>
					</ul>
				</div>
			</div> -->
			<div id="listPropostas">
				<div class="form-group rounded">
					<div class="form-line" style="background: #fff; padding: 5px 10px 5px 10px; border-radius: 5px;">
						<input class="search form-control" placeholder=" Digite para pesquisar a proposta..." />
					</div>
				</div>
				<ul class="list list-unstyled">
					<?php
					$propostasPai = cPropostaLicencaAmb::getTodasPai();

					foreach ($propostasPai as $key => $propostaPai) {

						echo '<li>';
						echo '<div class="card rounded">';
						echo '<div class="body">';

						//se não tiver proposta pai, pega o código da proposta
						$propostaAtual = (is_null($propostaPai->cd_proposta_atual)) ? $propostaPai->cd_proposta_licenca : $propostaPai->cd_proposta_atual;

						echo '
						<ul class="header-dropdown m-r--5 list-unstyled pull-right">
						<li class="dropdown">
						<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						<i class="material-icons">more_vert</i>
						</a>
						<ul class="dropdown-menu pull-right">
						<li><a data-toggle="modal" href="#modalFormAlterProposta" onclick="preencheFormAlterProposta(\''.base64_encode($propostaAtual).'\')" class="waves-effect waves-block"><i class="material-icons">edit</i> Editar</a></li>
						</ul>
						</li>
						</ul>
						';

						$protocolo = (is_null($propostaPai->nr_alteracao)) ? $propostaPai->nr_protocolo."/".$propostaPai->competencia : $propostaPai->nr_protocolo."/".$reg->competencia."/ALT-".$propostaPai->nr_alteracao;

						echo '<div class="row">';
						echo '<div class="col-md-2">';
						echo 'Protocolo:<br><strong class="protocolo">'.$protocolo.'</strong>';
						echo '</div>';
						echo '<div class="col-md-3">';
						echo 'Cliente:</strong><br><strong class="cliente">'.$propostaPai->nm_cliente.'</strong>';
						echo '</div>';
						echo '<div class="col-md-4">';
						echo 'Empreendimento:</strong><br><strong class="empreendimento">'.$propostaPai->nm_empreendimento.'</strong>';
						echo '</div>';
						echo '<div class="col-md-2">';
						echo 'Previsão:</strong><br><strong class="previsao">'.date("d/m/Y", strtotime($propostaPai->dt_prev_conclusao)).'</strong>';
						echo '</div>';
						echo '<div class="col-md-12">';
						echo 'Observação:</strong><p class="text-justify observacao">'.$propostaPai->ds_observacao.'</p>';
						echo '</div>';
						echo '</div>';

						$propFilha 	= new cPropostaLicencaAmb($propostaPai->cd_proposta_licenca);

						$versoes 	= $propFilha->historicoVersoes();

						echo '<ul class="list-unstyled list-inline">';
						echo '<li><a href="#versoesProposta'.$propostaPai->cd_proposta_licenca.'" data-toggle="collapse"><i class="fa fa-chevron-right"></i> Versões ('.count($versoes).')</a></li>';
						echo '</ul>';

						echo '<div class="collapse" id="versoesProposta'.$propostaPai->cd_proposta_licenca.'">';

						echo '<hr/>';

						foreach ($versoes as $key => $versao) {

							$proposta 	= new cPropostaLicencaAmb($versao->cd_proposta_licenca);

							$dadosItens  = $proposta->DadosItensProposta();
							$assessoria  = array();
							$consultoria = array();

							foreach ($dadosItens as $dadosItem) {

								if ($dadosItem['tp_atividade'] == 'A') {
									$assessoria[] = $dadosItem;
								} else {
									$consultoria[] = $dadosItem;
								}

							}

							if (is_null($versao->cd_proposta_pai)) {
								$tpProposta = '<label class="label label-success">Versão inicial</label>';
								$border 	= ' border-green';
							} else if ($key == 0) {
								$tpProposta = '<label class="label bg-deep-purple">Versão atual</label>';
								$border 	= ' border-deep-purple';
							} else {
								$tpProposta = '<label class="label mdc-bg-grey-400">Proposta alterada</label>';
								$border 	= '';
							}

							switch ($versao->tp_status) {
								case 'E':
								$statusProposta = '<label class="label label-warning">Proposta Em Negociação</label>';
								break;

								case 'F':
								$statusProposta = '<label class="label label-success">Proposta Fechada</label>';
								break;

								case 'C':
								$statusProposta = '<label class="label label-danger">Proposta Cancelada</label>';
								break;

								default:
								$statusProposta = null;
								break;
							}

							$vlDiferenca = (is_null($versoes[$key+1]->vl_negociado)) ? null : $versao->vl_negociado - $versoes[$key+1]->vl_negociado;

							if (!is_null($vlDiferenca) && $vlDiferenca >= 0) {
								$labelDiferenca = '<p class="col-green"><i class="fa fa-arrow-up"></i> R$ '.number_format($vlDiferenca,2,',','.').'</p>';
							} else if (!is_null($vlDiferenca) && $vlDiferenca < 0) {
								$labelDiferenca = '<p class="col-red"><i class="fa fa-arrow-down"></i> R$ '.number_format($vlDiferenca,2,',','.').'</p>';
							} else {
								$labelDiferenca = null;
							}

							echo '
							<div class="pull-right">
							<br>
							<small>Valor:</small>
							<p>
							<small>R$</small> <span class="col-green font-30">'.number_format($versao->vl_negociado,2,',','.').'</span>
							</p>
							'.$labelDiferenca.'
							</div>
							<ul class="list-inline">
							<li>'.$statusProposta.'</li>
							<li>'.$tpProposta.'</li>
							</ul>
							<ul class="list-inline">
							<li>
							<small>Proposta criada em:</small>
							<br>
							<strong>'.date("d/m/Y H:i:s", strtotime($versao->dh_registro)).'</strong>
							</li>
							<li>
							<small>Autor:</small>
							<br>
							<strong>'.$versao->nm_usuario.'</strong>
							</li>
							</ul>
							<ul class="list-unstyled">
							<li>
							<a data-toggle="collapse" href="#listAssessoria'.$versao->cd_proposta_licenca.'" aria-expanded="false" aria-controls="collapseExample"><span class="badge">'.$versao->qtd_assessoria.'</span> Assessoria</a>
							<div class="collapse" id="listAssessoria'.$versao->cd_proposta_licenca.'">
							<br>
							<table class="table table-condensed table-striped">
							<tbody>
							';

							foreach ($assessoria as $key => $value) {
								echo "<tr>";
								echo "<td>";
								echo $value['ds_tp_atividade'];
								echo "</td>";
								echo "<td>";
								echo date("d/m/Y", strtotime($value['dt_prev_entrega']));
								echo "</td>";
								echo "<td>";
								echo "R$ ";
								echo number_format($value['vl_negociado'],2,',','.');
								echo "</td>";
								echo "</tr>";
							}

							echo '
							</tbody>
							</table>
							</div>
							</li>
							<li>
							<a data-toggle="collapse" href="#listConsultoria'.$versao->cd_proposta_licenca.'" aria-expanded="false" aria-controls="collapseExample"><span class="badge">'.$versao->qtd_consultoria.'</span> Consultoria</a>
							<div class="collapse" id="listConsultoria'.$versao->cd_proposta_licenca.'">
							<br>
							<table class="table table-condensed table-striped">
							<tbody>
							';

							foreach ($consultoria as $key => $value) {
								echo "<tr>";
								echo "<td>";
								echo $value['ds_tp_atividade'];
								echo "</td>";
								echo "<td>";
								echo date("d/m/Y", strtotime($value['dt_prev_entrega']));
								echo "</td>";
								echo "<td>";
								echo "R$ ";
								echo number_format($value['vl_negociado'],2,',','.');
								echo "</td>";
								echo "</tr>";
							}

							echo '
							</tbody>
							</table>
							</div>
							</li>
							</ul>
							';
							echo '<hr/>';
						}
						echo '</div>';
						echo '</div>';
						echo '</div>';
						echo '</li>';
					}

					?>
				</ul>
			</div>
		</div>
	</div>
</div>

<!-- modal cadastro proposta -->
<div class="modal fade" id="modalNovaProposta">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id="formCadPropostaLicencaAmb">
				<input type="hidden" name="tpStatus" value="F">
				<div class="modal-header bg-deep-purple">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="fas fa-dollar-sign fa-lg pull-left" style="margin-top: 3px; padding-right: 5px;"></i> &nbsp;Nova Proposta</h4>
				</div>
				<div class="modal-body" style="padding: 1px 5px;">
					<div class="container-fluid">
						<br/>
						<div class="row clearfix">
							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label>Cliente:</label>
										<select name="cdCliente" class="form-control" data-live-search="true">
											<option></option>
											<?php $cliente->listOption(); ?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label>Empreendimento:</label>
										<select name="cdEmpreendimento" class="form-control" data-live-search="true">
											<option></option>
											<?php $empreendimento->listOption(); ?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-sm-6 col-md-5 col-xs-6">
								<div class="form-group">
									<div class="form-line">
										<label>Itens do Serviço:</label>
										<select name="cdTpAtividade" class="form-control" data-live-search="true">
											<option></option>
											<?php $tpAtividade->listOption(); ?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-xs-2 col-sm-2 col-md-1">
								<br/>
								<button type="button" class="btn btn-block btn-sm bg-green waves-effect col-white" onclick="addAtividadeProposta('cad')"><i class="material-icons">add</i></button>
							</div>
						</div>
					</div>
				</div>

				<!-- Nav tabs -->
				<ul class="nav nav-tabs tab-nav-right tab-col-deep-purple" id="tabAtividades" role="tablist" style="padding-left: 2px;">
					<li role="presentation" class="active"><a href="#cadTabAssessoria" data-toggle="tab">Assessoria <span id="badgeCadTabAssessoria" class="badge bg-deep-purple pull-right" style="margin-left: 10px">0</span></a></li>
					<li role="presentation"><a href="#cadTabConsultoria" data-toggle="tab">Consultoria <span id="badgeCadTabConsultoria" class="badge bg-deep-purple pull-right" style="margin-left: 10px">0</span></a></li>
				</ul>

				<!-- Tab panes -->
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane fade" id="cadTabConsultoria">

						<div style="background: #E9E9E9; padding-top: 30px; min-height: 10em;">
							<div class="container-fluid">
								<div class="row clearfix listaAtividadesProposta">

								</div>
							</div>
						</div>

					</div>

					<div role="tabpanel" class="tab-pane fade in active" id="cadTabAssessoria">

						<div style="background: #E9E9E9; padding-top: 30px; min-height: 10em;">
							<div class="container-fluid">
								<div class="row clearfix listaAtividadesProposta">

								</div>
							</div>
						</div>

					</div>
				</div>

				<div class="msg-objeto" style="padding: 10px;"></div>

				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<div class="form-line">
									<label>Observação</label>
									<textarea class="form-control no-resize auto-growth" name="dsObservacao" rows="5"></textarea>
								</div>
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
							<label>Data Prevista Conclusão</label>
							<div class="form-group">
								<div class="form-line">
									<input type="text" name="dtPrevConclusaoLicenca" class="form-control datepicker" />
								</div>
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
							<div class="container-fluid">
								<h4>Total Negociado</h4>
								<div class="row">
									<div class="col-sm-1" style="vertical-align: baseline;">
										R$
									</div>
									<div class="col-sm-5">
										<div id="cadTotalPropostaNegociado" class="number count-to col-green" data-from="0.01" data-to="257.01" data-speed="1000" data-fresh-interval="20" style="font-size: 30px">0,00</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
							<div class="container-fluid">
								<h4>Total Pago</h4>
								<div class="row">
									<div class="col-sm-1" style="vertical-align: baseline;">
										R$
									</div>
									<div class="col-sm-5">
										<div id="cadTotalPropostaPago" class="number count-to col-green" data-from="0.01" data-to="257.01" data-speed="1000" data-fresh-interval="20" style="font-size: 30px">0,00</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><i class="material-icons col-red">close</i> Fechar</button>
					<button type="button" onclick="salvarProposta('cad')" class="btn bg-indigo waves-effect save-button"><i class="material-icons">save</i> Salvar</button>
					<button type="button" onclick="fecharProposta('cad')" class="btn bg-green waves-effect"><i class="material-icons">check</i> Fechar Proposta</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- modal alterar proposta -->
<div class="modal fade" id="modalFormAlterProposta">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

		</div>
	</div>
</div>

<script type="text/javascript">
	$.AdminBSB.input.activate();
	$.AdminBSB.select.activate();

	// $(".page-loader-wrapper").fadeOut("fast");

	var path = '<?php echo $nmArquivo;?>';

	function refresh(p){
		$("#divConteudo").load("view/"+p);
		$("div.overlay").trigger('click');
	}

	var options = {
		valueNames: [ 'cliente', 'empreendimento', 'previsao', 'observacao', 'protocolo' ]
	};

	var propostaList = new List('listPropostas', options);

	var totalNegociado = 0;

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

	// autosize($('textarea.auto-growth'));

	$(".listaAtividadesProposta").sortable({
		placeholder: "ui-state-highlight"
	});

	$(".listaAtividadesProposta").disableSelection();

	$(".inputMoney").maskMoney({prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',', affixesStay: false});

	$('.datepicker').datetimepicker({
		format: 'DD/MM/YYYY'
	});


	Number.prototype.format = function(n, x, s, c) {
		var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
		num = this.toFixed(Math.max(0, ~~n));

		return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
	};

	function addAtividadeProposta(form){

		idForm = '#formCadPropostaLicencaAmb';

		//pega os dados do objeto da licença escolhido
		var valor = $(idForm+" select[name=cdTpAtividade]").val();
		var texto = $(idForm+" select[name=cdTpAtividade] option:selected").text();

		if (valor == '') {

			alert('Selecione uma atividade para adicionar!');
			return false;
		}


		//pega a aba ativa para categorizar as atividades em 'A' Assessoria e 'C' Consultoria
		var tipoAtividade = $("#tabAtividades").find("li.active > a").attr('href');

		//envia os dados para verificar de qual categoria eles pertencem e exibe na aba correspondente
		$.ajax({
			url: 'action/eco_addTpAtividadeProposta.php',
			type: 'POST',
			data: {
				idForm: idForm,
				cdTpAtividade: valor,
				dsTpAtividade: texto,
				tpAtividade: tipoAtividade
			},
			success: function(data){
				$("#divResult").html(data);
			}
		});
	}

	$('#formCadPropostaLicencaAmb select[name=cdCliente]').change(function(){
		var valor = $(this).val();

		$.ajax({
			url: 'action/g_listOptionEmpreendimento.php',
			type: 'POST',
			data: {cdCliente: valor},
			success: function(data){
				$("#formCadPropostaLicencaAmb select[name=cdEmpreendimento]").html(data);
				$("#formCadPropostaLicencaAmb select[name=cdEmpreendimento]").selectpicker('refresh');
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


	function salvarProposta(form){

		var idForm = (form == 'cad') ? "#formCadPropostaLicencaAmb" : "#formAlterPropostaLicencaAmb";

		$(idForm+" input[type='hidden'][name='tpStatus']").val('E');
		$(".save-button").prop('disabled', true);

		setTimeout(function(){
		    $(".save-button").prop('disabled', false);
		}, 2000);

		$(idForm).submit();
	}

	function salvarSimplesProposta() {
		$.ajax({
			url: 'action/eco_alterSimplesPropostaLicencaAmb.php',
			type: 'POST',
			data: $("#formAlterPropostaLicencaAmb").serialize(),
			success: function(data){
				$("#divResult").html(data);
			}
		})
		.done(function() {
			$("#modalFormAlterProposta").modal("hide");
			setTimeout(function(){
				refresh(path);
			},2000);
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	}

	function fecharProposta(form){

		var idForm = (form == 'cad') ? "#formCadPropostaLicencaAmb" : "#formAlterPropostaLicencaAmb";

		$(idForm+" input[type='hidden'][name='tpStatus']").val('F');
		$(idForm).submit();
	}


	$("#formCadPropostaLicencaAmb").validate({
		rules:{
			cdCliente: "required",
			cdEmpreendimento: "required",
			dtPrevConclusaoLicenca: "required"
		},
		messages:{
			cdCliente: "Selecione o cliente da proposta",
			cdEmpreendimento: "Selecione o empreendimento do cliente",
			dtPrevConclusaoLicenca: "Digite a data prevista para a conclusão"
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

			$("#formCadPropostaLicencaAmb").submit(function(){

				$.ajax({
					url: 'action/eco_cadPropostaLicencaAmb.php',
					type: 'POST',
					data: $(this).serialize(),
					success: function(data){
						$("#divResult").html(data);
					}
				})
				.done(function() {
					$("#modalNovaProposta").modal("hide");
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

			});
		}
	});

	// $("#formCadPropostaLicencaAmb").submit(function(){
	// 	if(arrayAtividade.length <= 0){
	// 		$("#formCadPropostaLicencaAmb .msg-objeto").html('<p class="col-red text-center">Insira ao menos um objeto a proposta</p>');
	// 	}else{

	// 	}
	// 	return false;
	// });


	/*============================================== ALTERAÇÃO =======================================================*/



	function preencheFormAlterProposta(c){

		$("#modalFormAlterProposta div.modal-content").html("<p class='text-center'>Carregando...<p>");

		$.ajax({
			url: 'action/eco_loadDadosProposta.php',
			type: 'POST',
			data: {cdPropostaLicenca: c},
			success: function(data){
				$("#modalFormAlterProposta div.modal-content").html(data);
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

	$('#formAlterPropostaLicencaAmb select[name=cdCliente]').change(function(){
		var valor = $(this).val();

		$.ajax({
			url: 'action/g_listOptionEmpreendimento.php',
			type: 'POST',
			data: {cdCliente: valor},
			success: function(data){
				$("#formAlterPropostaLicencaAmb select[name=cdEmpreendimento]").html(data);
				$("#formAlterPropostaLicencaAmb select[name=cdEmpreendimento]").selectpicker('refresh');
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

	$("#formAlterPropostaLicencaAmb").validate({
		rules:{
			cdCliente: "required",
			cdEmpreendimento: "required",
			dtPrevConclusaoLicenca: "required"
		},
		messages:{
			cdCliente: "Selecione o cliente da proposta",
			cdEmpreendimento: "Selecione o empreendimento do cliente",
			dtPrevConclusaoLicenca: "Digite a data prevista para a conclusão"
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

			$("#formAlterPropostaLicencaAmb").submit(function(){

				$.ajax({
					url: 'action/eco_alterPropostaLicencaAmb.php',
					type: 'POST',
					data: $(this).serialize(),
					success: function(data){
						$("#divResult").html(data);
					}
				})
				.done(function() {
					$("#modalNovaProposta").modal("hide");
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

			});
		}
	});

	$("#formAlterPropostaLicencaAmb").submit(function(){
		if(arrayAtividade.length <= 0){
			$("#formAlterPropostaLicencaAmb .msg-objeto").html('<p class="col-red text-center">Insira ao menos um objeto a proposta</p>');
		}else{

		}
		return false;
	});
</script>

