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

$ano 			= isset($_GET['year']) ? $_GET['year'] : date("Y");

?>
<style>
.bootstrap-datetimepicker-widget {
	top: 80px !important;
}
</style>
<div class="container-fluid">
	<div class="block-header">
		<div class="pull-right" style="z-index: 999">
			<a data-toggle="modal" href="#modalNewProposta" onclick="setNewForm()" class="btn bg-green waves-effect"><i class="material-icons">add</i> Nova Proposta</a>
		</div>
		<h2 style="color: white !important;">Propostas</h2>
	</div>
	<br />
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div id="listPropostas">
				<div class="row">
					<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
						<div class="form-group rounded">
							<div class="form-line" style="background: #fff; padding: 5px 10px 5px 10px; border-radius: 5px;">
								<input class="search form-control" placeholder=" Digite para pesquisar a proposta..." />
							</div>
						</div>
					</div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
						<div class="input-group rounded">
							<div class="form-line" style="background: #fff; padding: 5px 10px 5px 10px; border-radius: 5px;">
								<input class="form-control datepicker-year" placeholder="Selecione um ano..." value="<?php echo $ano; ?>" />
							</div>
							<span class="input-group-addon">
								<i class="material-icons col-white">date_range</i>
							</span>
						</div>
					</div>
				</div>
				<ul class="list list-unstyled">
					<?php
					$propostasPai = cProposta::getAllPais($ano);

					if (count($propostasPai) > 0) {

						foreach ($propostasPai as $key => $propostaPai) {

							$icon = cProposta::getIconStatus($propostaPai->tp_status);
							$descriptionStatus = cProposta::getDescriptionStatus($propostaPai->tp_status);
							$dtPrevConclusao = ($propostaPai->dt_prev_conclusao == '0000-00-00') ? '-' : date("d/m/Y", strtotime($propostaPai->dt_prev_conclusao));

							echo '<li>';
							echo '<div class="card rounded">';
							echo '<div class="body">';

							//se não tiver proposta pai, pega o código da proposta
							$propostaAtual = (is_null($propostaPai->cd_proposta_atual)) ? $propostaPai->cd_proposta : $propostaPai->cd_proposta_atual;

							echo '
							<ul class="header-dropdown m-r--5 list-unstyled pull-right">
							<li class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<i class="material-icons">more_vert</i>
							</a>
							<ul class="dropdown-menu pull-right">
							<li><a data-toggle="modal" href="#modalNewProposta" onclick="getPropostaData(\''.($propostaPai->cd_proposta).'\')" class="waves-effect waves-block"><i class="material-icons">edit</i> Editar</a></li>
							</ul>
							</li>
							</ul>
							';

							// $protocolo = (is_null($propostaPai->nr_alteracao_atual)) ? $propostaPai->nr_protocolo."/".$propostaPai->competencia : $propostaPai->nr_protocolo."/".$propostaPai->competencia."/ALT-".$propostaPai->nr_alteracao_atual;
							$protocolo = ($propostaPai->nr_alteracao == 0) ? $propostaPai->nr_protocolo."/".$propostaPai->competencia : $propostaPai->nr_protocolo."/".$propostaPai->competencia."/ALT-".$propostaPai->nr_alteracao;

							echo '<div class="row">';
							echo '<div class="col-md-2">';
							echo 'N° da Proposta:<br><strong class="protocolo">'.$protocolo.'</strong>';
							echo '</div>';
							echo '<div class="col-md-2">';
							echo 'Clientes:</strong><br><strong class="cliente">';

							$clientes = cProposta::getClientesByProposta($propostaPai->cd_proposta);

							if (count($clientes) > 0) {

								foreach ($clientes as $key => $cliente) {
									echo "<div class=\"m-b-5\">";
									echo $cliente->nm_cliente;
									echo ($cliente->nm_cliente_vinculo) ? "<br><small class=\"mdc-text-grey-500\">Vínculo: $cliente->nm_cliente_vinculo</small>" : null;
									echo "</div>";
								}

							}

							echo '</strong></div>';
							echo '<div class="col-md-2">';
							echo 'Empreendimentos:</strong><br><strong class="empreendimento">';
							// $clientes = cProposta::getClientesByProposta($propostaPai->cd_proposta);

							if (count($clientes) > 0) {

								foreach ($clientes as $key => $cliente) {
									echo rtrim($cliente->nm_empreendimento).";<br>";
								}
							
							}
							echo '</strong></div>';
							// if ($propostaPai->nm_cliente_vinculo) {
							// 	echo '<div class="col-md-3">';
							// 	echo 'Vínculo:</strong><br><strong class="cliente_vinculo"></strong>';
							// 	echo '</div>';
							// }
							echo '<div class="col-md-1">';
							echo 'Previsão:</strong><br><strong class="previsao">'.$dtPrevConclusao.'</strong>';
							echo '</div>';
							echo '<div class="col-md-2">';
							echo 'Status:</strong><br>'.$icon.'&nbsp; <span>'.$descriptionStatus.'</span>';
							echo '</div>';

							if ($propostaPai->tp_status === 'F') {
								echo '<div class="col-md-2">';
								echo 'Aprovado por:</strong><br><strong>'.$propostaPai->nm_usuario_fechamento.'</strong><br>Em: '.date('d/m/Y H:i:s', strtotime($propostaPai->dh_fechamento));
								echo '</div>';
							} else if ($propostaPai->tp_status === 'C') {
								echo '<div class="col-md-2">';
								echo 'Cancelado por:</strong><br><strong>'.$propostaPai->nm_usuario_cancelamento.'</strong><br>Em: '.date('d/m/Y H:i:s', strtotime($propostaPai->dh_cancelamento));
								echo '</div>';
							}

							echo '<div class="col-md-12">';
							echo 'Observações:<p class="text-justify observacao"><strong>'.$propostaPai->ds_observacao.'</strong></p>';
							echo '</div>';
							echo '</div>';

							$versoes 	= cProposta::getHistoricoVersoes($propostaPai->cd_proposta);

							echo '<ul class="list-unstyled list-inline">';
							echo '<li><a href="#versoesProposta'.$propostaPai->cd_proposta.'" data-toggle="collapse"><i class="fa fa-chevron-right"></i> Versões ('.count($versoes).')</a></li>';
							echo '</ul>';

							echo '<div class="collapse" id="versoesProposta'.$propostaPai->cd_proposta.'">';

							echo '<hr/>';

							foreach ($versoes as $key => $versao) {

								$dadosItens  = cProposta::getItensProposta($versao->cd_proposta);
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
								

								$vlDiferenca = (!isset($versoes[$key+1])) ? null : $versao->valor - $versoes[$key+1]->valor;

								if (!is_null($vlDiferenca) && $vlDiferenca >= 0) {
									$labelDiferenca = '<p class="col-green"><i class="fa fa-arrow-up"></i> R$ '.number_format($vlDiferenca,2,',','.').'</p>';
								} else if (!is_null($vlDiferenca) && $vlDiferenca < 0) {
									$labelDiferenca = '<p class="col-red"><i class="fa fa-arrow-down"></i> R$ '.number_format($vlDiferenca,2,',','.').'</p>';
								} else {
									$labelDiferenca = null;
								}

								if (isset($versao->cd_proposta_pai) && !empty($versao->cd_proposta_pai)) {
									$labelAlteracao = '
										<ul class="list-inline">
											<li><strong>Alteração: '.$versao->nr_alteracao.'</strong></li>
										</ul>
										<br>
									';
								} else {
									$labelAlteracao = '<p><label class="label bg-deep-purple">Versão atual</label></p>';
								}
									

								echo '
								<div class="pull-right">
								<br>
								<small>Valor:</small>
								<p>
								<small>R$</small> <span class="col-green font-30">'.number_format($versao->valor,2,',','.').'</span>
								</p>
								'.$labelDiferenca.'
								</div>
								'.$labelAlteracao.'
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
								<br>
								<ul class="list-unstyled">
								<li>
								<a data-toggle="collapse" href="#listAssessoria'.$versao->cd_proposta.'" aria-expanded="false" aria-controls="collapseExample"><span class="badge">'.$versao->qtd_assessoria.'</span> Assessoria</a>
								<div class="collapse" id="listAssessoria'.$versao->cd_proposta.'">
								<br>
								<table class="table table-condensed table-striped">
								<thead>
									<tr>
										<th>Cliente</th>
										<th>Atividade</th>
										<th>Previsão de Entrega</th>
										<th>Valor</th>
										<th>Desconto</th>
										<th>Total</th>
									</tr>
								</thead>
								<tbody>
								';

								foreach ($assessoria as $key => $value) {
									echo "<tr>";
									echo "<td>";
									echo $value['nm_cliente'];
									echo "</td>";
									echo "<td>";
									echo $value['ds_tp_atividade'];
									echo "</td>";
									echo "<td>";
									echo date("d/m/Y", strtotime($value['dt_prev_entrega']));
									echo "</td>";
									echo "<td>";
									echo "R$ ";
									echo number_format($value['valor'], 2, ',', '.');
									echo "</td>";
									echo "<td>";
									echo "R$ ";
									echo number_format($value['desconto'], 2, ',', '.');
									echo "</td>";
									echo "<td>";
									echo "R$ ";
									echo number_format($value['valor'] - $value['desconto'], 2, ',', '.');
									echo "</td>";
									echo "</tr>";
								}

								echo '
								</tbody>
								</table>
								</div>
								</li>
								<li>
								<a data-toggle="collapse" href="#listConsultoria'.$versao->cd_proposta.'" aria-expanded="false" aria-controls="collapseExample"><span class="badge">'.$versao->qtd_consultoria.'</span> Consultoria</a>
								<div class="collapse" id="listConsultoria'.$versao->cd_proposta. '">
								<br>
								<table class="table table-condensed table-striped">
								<thead>
									<tr>
										<th>Cliente</th>
										<th>Atividade</th>
										<th>Previsão de Entrega</th>
										<th>Valor</th>
										<th>Desconto</th>
										<th>Total</th>
									</tr>
								</thead>
								<tbody>
								';

								foreach ($consultoria as $key => $value) {
									echo "<tr>";
									echo "<td>";
									echo $value['nm_cliente'];
									echo "</td>";
									echo "<td>";
									echo $value['ds_tp_atividade'];
									echo "</td>";
									echo "<td>";
									echo date("d/m/Y", strtotime($value['dt_prev_entrega']));
									echo "</td>";
									echo "<td>";
									echo "R$ ";
									echo number_format($value['valor'], 2, ',', '.');
									echo "</td>";
									echo "<td>";
									echo "R$ ";
									echo number_format($value['desconto'], 2, ',', '.');
									echo "</td>";
									echo "<td>";
									echo "R$ ";
									echo number_format($value['valor'] - $value['desconto'], 2, ',', '.');
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
					
					} else {
						echo '<li>';
							echo '<div class="card rounded">';
								echo '<div class="body">';
									echo '<p class="text-center"><i class="material-icons">money_off</i></p>';
									echo '<p class="text-center">Nenhuma proposta até o momento</p>';
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

<div class="modal full fade" id="modalNewProposta">
	<div class="modal-dialog">
		<form id="formNewProposta" method="POST">
			<input type="hidden" name="cdProposta" />
			<div class="row" style="display: flex; align-items: stretch; padding: 0 0 0 10px; overflow-x: hidden;">
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 mdc-bg-grey-100" style="padding-top: 10px;">
					<div>
						<button type="button" class="close pull-left" data-dismiss="modal" aria-hidden="true"><i class="material-icons">arrow_back</i></button>
						<a href="javascript:void(0)" class="pull-right" onclick="addCliente()"><i class="material-icons col-green">add</i></a>
						<br>
						<br>
						<h4>Nova Proposta</h4>
						<br>

						<div style="min-height: 80vh;" id="cadListClientes">

						</div>

					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 bg-white">
					<div class="container-fluid">
						<br>
						<div class="form-group">
							<label>Vínculo:</label>
							<!-- Lista de Clientes -->
							<select name="cdClienteVinculo" class="form-control clienteVinculoProposta" data-live-search="true" onchange="setClienteVinculoProposta(this)">
								<option></option>
							</select>
						</div>
						<div class="form-group">
							<label>Itens do Serviço:</label>
							<select name="cdTpAtividade" class="form-control" data-live-search="true" onchange="addAtividadeProposta(this)">
								<option></option>
							</select>
						</div>

						<div class="row" style="display: flex; align-items: stretch;">
							<div class="col-md-6">
								<div>
									<span class="badge bg-green pull-right font-20" id="assessoriaTotal">R$ 0,00</span>
									<h3>Assessoria</h3>
								</div>
								<div class="container-fluid mdc-bg-grey-100" id="cadListItensAssessoria" style="min-height: 50vh; padding-top: 20px;">

								</div>
							</div>
							<div class="col-md-6">
								<div>
									<span class="badge bg-green pull-right font-20" id="consultoriaTotal">R$ 0,00</span>
									<h3>Consultoria</h3>
								</div>
								<div class="container-fluid mdc-bg-grey-100" id="cadListItensConsultoria" style="min-height: 50vh; padding-top: 20px;">

								</div>
							</div>
						</div>
						
						<br>

						<div style="border-top: 1px solid #E5E5E5">
							&nbsp;
						</div>

						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label>Vencimento:</label>
									<div class="form-line">
										<input type="text" name="dtPrevConclusaoLicenca" class="form-control datepicker" onblur="setDtPrevista(this)" autocomplete="off" />
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Observações</label>
									<div class="form-line">
										<textarea name="dsObservacao" class="form-control"
											onchange="setObservacao(this)" id="" rows="3"></textarea>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div id="totalProposta">
									<h1><small class="text-muted">R$<br></small> 0,00</h1>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 mdc-bg-grey-100" style="border-left: 1px solid #E5E5E5">
					<div>
						<div class="p-b-15" style="display: flex; flex-direction: column-reverse; flex-wrap: wrap; align-self: flex-end; align-items: flex-end; align-content: flex-end; justify-content: flex-start; min-height: 100vh;">
							<div class="w-100 p-t-15">
								<div class="p-b-15">
									<input type="checkbox" id="checkFecharProposta" class="filled-in chk-col-green">
									<label for="checkFecharProposta">Proposta Aceita?</label>
								</div>

								<button type="submit" class="btn btn-sm btn-block btn-success"><i class="material-icons">save</i>&nbsp; Salvar</button>
								<button type="button" class="btn btn-sm btn-block btn-danger" onclick="cancelarProposta()"><i class="material-icons">block</i>&nbsp; Cancelar</button>
							</div>
							<div class="w-100 m-b-10">
								<span>Total da Proposta</span>
								<br>
								<small class="font-20">R$&nbsp; </small>
								<strong id="totalGeralProposta" class="font-45 mdc-text-green">0,00</strong>
							</div>
						</div>
					</div>
				</div>

			</div>
		</form>
	</div>
</div>

<script type="text/javascript" src="../lib/js/ecosis/proposta.js"></script>
<script type="text/javascript">
	$.AdminBSB.input.activate();
	// $.AdminBSB.select.activate();

		console.log(window.location);
	// $(".page-loader-wrapper").fadeOut("fast");

	var path = '<?php echo $nmArquivo;?>';

	function refresh(p) {
		$("#divConteudo").load("view/" + p);
		$("div.overlay").trigger('click');
	}

	var options = {
		valueNames: ['cliente', 'empreendimento', 'cliente_vinculo', 'previsao', 'observacao', 'protocolo']	
	};

	var propostaList = new List('listPropostas', options);

	var totalNegociado = 0;

	$('.js-basic-example').DataTable({
		responsive: true,
		"columnDefs": [
			{ "type": "num", "targets": 0 }
		]
	});

	$(document).ready(function () {
		$("div.form-line.focused").removeClass("focused");

		// $('input[name=dsSenha], input[name=dsConfirmaSenha]').pstrength();
	});

	$(".listaAtividadesProposta").disableSelection();

	$(".inputMoney").maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',', affixesStay: false });

	$('.datepicker').datetimepicker({
		format: 'DD/MM/YYYY'
	});

	$('.datepicker-year').datetimepicker({
		format: 'YYYY'
	}).on('dp.change', function (e) { search(e.target.value) });


	Number.prototype.format = function (n, x, s, c) {
		var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
			num = this.toFixed(Math.max(0, ~~n));

		return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
	};

	function setNewForm() {
		$("#formChangeProposta").attr("id", "formNewProposta");
		
		limparObjetoProposta();
		renderItensProposta();
		renderPropostaData();
		renderListaClientes();
		renderOptionClientes();
		renderOptionEmpreendimentos();
		renderOptionTiposAtividade();
		calcularTotalProposta();
		
	}

	function fecharProposta(form) {

		var idForm = (form == 'cad') ? "#formCadPropostaLicencaAmb" : "#formAlterPropostaLicencaAmb";

		$(idForm + " input[type='hidden'][name='tpStatus']").val('F');
		$(idForm).submit();
	}

	$("#formNewProposta").validate({
		rules: {
			cdCliente: "required",
			cdEmpreendimento: "required",
			dtPrevConclusaoLicenca: "required"
		},
		messages: {
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
		submitHandler: function (form) {
			let formData = window.localStorage.getItem("proposta");

			let url;
			let formId;
			
			if (JSON.parse(formData).cd_proposta) {
				url = "action/eco_alterProposta.php";
				formId = "#formChangeProposta";
			} else {
				url = "action/eco_cadProposta.php";
				formId = "#formNewProposta";
			}
			
			$.ajax({
				url: url,
				type: 'POST',
				data: JSON.parse(formData),
				success: function (data) {
					$("#divResult").html(data);
					console.log(data);
				}
			})
				.done(function () {
					$(formId +" button.close").trigger("click");
					limparObjetoProposta();
					renderListaClientes();
					renderItensProposta();
					$(formId)[0].reset();
					setTimeout(function () {
						refresh(path);
					}, 1000);
				})
				.fail(function () {
					console.log("error");
				})
				.always(function () {
					console.log("complete");
				});

			return false;
		}
	});

	function getPropostaData(c) {

		limparObjetoProposta();
		renderItensProposta();

		$.ajax({
			url: 'action/eco_getPropostaData.php',
			type: 'POST',
			data: { cdProposta: c },
			success: function (data) {
				window.localStorage.setItem("proposta", data);
				// setTimeout(() => {
					renderPropostaData();
					renderListaClientes();
					renderOptionClientes();
					renderOptionEmpreendimentos();
					renderOptionTiposAtividade();
					calcularTotalProposta();
					
					$("#formNewProposta").attr("id", "formChangeProposta");
				// }, 1000);
			}
		})
		.done(function () {
			console.log("success");
		})
		.fail(function () {
			console.log("error");
		})
		.always(function () {
			console.log("complete");
		});
	}

	/*============================================== ALTERAÇÃO =======================================================*/



	function preencheFormAlterProposta(c) {

		$("#modalFormAlterProposta div.modal-content").html("<p class='text-center'>Carregando...<p>");

		$.ajax({
			url: 'action/eco_loadDadosProposta.php',
			type: 'POST',
			data: { cdPropostaLicenca: c },
			success: function (data) {
				$("#modalFormAlterProposta div.modal-content").html(data);
			}
		})
			.done(function () {
				console.log("success");
			})
			.fail(function () {
				console.log("error");
			})
			.always(function () {
				console.log("complete");
			});
	}

	$('#formAlterPropostaLicencaAmb select[name=cdCliente]').change(function () {
		var valor = $(this).val();

		$.ajax({
			url: 'action/g_listOptionEmpreendimento.php',
			type: 'POST',
			data: { cdCliente: valor },
			success: function (data) {
				$("#formAlterPropostaLicencaAmb select[name=cdEmpreendimento]").html(data);
				$("#formAlterPropostaLicencaAmb select[name=cdEmpreendimento]").selectpicker('refresh');
			}
		})
			.done(function () {
				console.log("success");
			})
			.fail(function () {
				console.log("error");
			})
			.always(function () {
				console.log("complete");
			});

	});

	$("#formAlterPropostaLicencaAmb").validate({
		rules: {
			cdCliente: "required",
			cdEmpreendimento: "required",
			dtPrevConclusaoLicenca: "required"
		},
		messages: {
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
		submitHandler: function (form) {

			$("#formAlterPropostaLicencaAmb").submit(function () {

				$.ajax({
					url: 'action/eco_alterPropostaLicencaAmb.php',
					type: 'POST',
					data: $(this).serialize(),
					success: function (data) {
						$("#divResult").html(data);
					}
				})
					.done(function () {
						$("#modalNewProposta").modal("hide");
						setTimeout(function () {
							refresh(path);
						}, 1000);
					})
					.fail(function () {
						console.log("error");
					})
					.always(function () {
						console.log("complete");
					});

				return false;

			});
		}
	});

	$("#formAlterPropostaLicencaAmb").submit(function () {
		if (arrayAtividade.length <= 0) {
			$("#formAlterPropostaLicencaAmb .msg-objeto").html('<p class="col-red text-center">Insira ao menos um objeto a proposta</p>');
		} else {

		}
		return false;
	});

	function search(value) {
		localStorage.setItem('page', 'eco_vPagPropostas');
		window.location.href = `${window.location.origin}${window.location.pathname}?year=${value}`;
	}
</script>