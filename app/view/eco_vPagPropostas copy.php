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
			<a data-toggle="modal" href="#modalNewProposta" class="btn bg-green waves-effect"><i
					class="material-icons">add</i> Nova Proposta</a>
		</div>
		<h2 style="color: white !important;">Propostas</h2>
	</div>
	<br />
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div id="listPropostas">
				<div class="form-group rounded">
					<div class="form-line" style="background: #fff; padding: 5px 10px 5px 10px; border-radius: 5px;">
						<input class="search form-control" placeholder=" Digite para pesquisar a proposta..." />
					</div>
				</div>
				<ul class="list list-unstyled">
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="modal full fade" id="modalNewProposta">
	<div class="modal-dialog">
		<form id="formNewProposta">
			<div class="row" style="display: flex; padding: 0 0 0 10px; overflow-x: hidden;">
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 mdc-bg-grey-100" style="padding-top: 10px;">
					<div>
						<button type="button" class="close pull-left" data-dismiss="modal" aria-hidden="true"><i
								class="material-icons">arrow_back</i></button>
						<a href="javascript:void(0)" class="pull-right" onclick="addCliente()"><i
								class="material-icons col-green">add</i></a>

						<br>
						<br>
						<h4>Nova Proposta</h4>
						<br>

						<div style="height: 80vh; overflow-x: hidden" id="cadListClientes">

						</div>

					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 bg-white"
					style="overflow-x: hidden; height: 100vh; border-left: 1px solid #E5E5E5">
					<div class="container-fluid">
						<br>
						<div class="form-group">
							<label>Itens do Serviço:</label>
							<select name="cdTpAtividade" class="form-control" data-live-search="true"
								onchange="addAtividadeProposta(this)">
								<option></option>
							</select>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div>
									<span class="badge bg-green pull-right font-20">R$ 0,00</span>
									<h3>Assessoria</h3>
								</div>
								<div class="container-fluid mdc-bg-grey-100" id="cadListItensAssessoria"
									style="overflow-x: hidden; height: 55vh; padding-top: 20px;">
									<div class="card">
										<div class="body">
											<h4 class="col-deep-purple">Nome do item</h4>
											<br>
											<div class="form-group form-float">
												<div class="form-line">
													<input type="text" name="vlItem[]" class="form-control inputMoney">
													<label class="form-label">Valor</label>
												</div>
											</div>
										</div>
									</div>
									<div class="card">
										<div class="body">
											<h4 class="col-deep-purple">Nome do item</h4>
											<br>
											<div class="form-group form-float">
												<div class="form-line">
													<input type="text" name="vlItem[]" class="form-control inputMoney">
													<label class="form-label">Valor</label>
												</div>
											</div>
										</div>
									</div>
									<div class="card">
										<div class="body">
											<h4 class="col-deep-purple">Nome do item</h4>
											<br>
											<div class="form-group form-float">
												<div class="form-line">
													<input type="text" name="vlItem[]" class="form-control inputMoney">
													<label class="form-label">Valor</label>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div>
									<span class="badge bg-green pull-right font-20">R$ 0,00</span>
									<h3>Consultoria</h3>
								</div>
								<div class="container-fluid mdc-bg-grey-100" id="cadListItensConsultoria"
									style="overflow-x: hidden; height: 55vh; padding-top: 20px;">
									<div class="card">
										<div class="body">
											<h4 class="col-deep-purple">Nome do item</h4>
											<br>
											<div class="form-group form-float">
												<div class="form-line">
													<input type="text" name="vlItem[]" class="form-control inputMoney">
													<label class="form-label">Valor</label>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<br>

						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label>Data Prevista Conclusão</label>
									<div class="form-line">
										<input type="text" name="dtPrevConclusaoLicenca"
											class="form-control datepicker" />
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Observações</label>
									<div class="form-line">
										<textarea name="dsObservacao" class="form-control" id="" rows="3"></textarea>
									</div>
								</div>
							</div>
							<div class="col-md-3 text-center">
								<h1><small class="text-muted" onbd>R$</small> 0,00</h1>
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

	// $(".page-loader-wrapper").fadeOut("fast");

	var path = '<?php echo $nmArquivo;?>';

	function refresh(p) {
		$("#divConteudo").load("view/" + p);
		$("div.overlay").trigger('click');
	}

	var options = {
		valueNames: ['cliente', 'empreendimento', 'previsao', 'observacao', 'protocolo']
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

	// autosize($('textarea.auto-growth'));

	$(".listaAtividadesProposta").sortable({
		placeholder: "ui-state-highlight"
	});

	$(".listaAtividadesProposta").disableSelection();

	$(".inputMoney").maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',', affixesStay: false });

	$('.datepicker').datetimepicker({
		format: 'DD/MM/YYYY'
	});


	Number.prototype.format = function (n, x, s, c) {
		var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
			num = this.toFixed(Math.max(0, ~~n));

		return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
	};

	function addAtividadeProposta(form) {

		idForm = '#formCadPropostaLicencaAmb';

		//pega os dados do objeto da licença escolhido
		var valor = $(idForm + " select[name=cdTpAtividade]").val();
		var texto = $(idForm + " select[name=cdTpAtividade] option:selected").text();

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
			success: function (data) {
				$("#divResult").html(data);
			}
		});
	}

	$('#formCadPropostaLicencaAmb select[name=cdCliente]').change(function () {
		var valor = $(this).val();

		$.ajax({
			url: 'action/g_listOptionEmpreendimento.php',
			type: 'POST',
			data: { cdCliente: valor },
			success: function (data) {
				$("#formCadPropostaLicencaAmb select[name=cdEmpreendimento]").html(data);
				$("#formCadPropostaLicencaAmb select[name=cdEmpreendimento]").selectpicker('refresh');
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


	function salvarProposta(form) {

		var idForm = (form == 'cad') ? "#formCadPropostaLicencaAmb" : "#formAlterPropostaLicencaAmb";

		$(idForm + " input[type='hidden'][name='tpStatus']").val('E');
		$(".save-button").prop('disabled', true);

		setTimeout(function () {
			$(".save-button").prop('disabled', false);
		}, 2000);

		$(idForm).submit();
	}

	function salvarSimplesProposta() {
		$.ajax({
			url: 'action/eco_alterSimplesPropostaLicencaAmb.php',
			type: 'POST',
			data: $("#formAlterPropostaLicencaAmb").serialize(),
			success: function (data) {
				$("#divResult").html(data);
			}
		})
			.done(function () {
				$("#modalFormAlterProposta").modal("hide");
				setTimeout(function () {
					refresh(path);
				}, 2000);
			})
			.fail(function () {
				console.log("error");
			})
			.always(function () {
				console.log("complete");
			});
	}

	function fecharProposta(form) {

		var idForm = (form == 'cad') ? "#formCadPropostaLicencaAmb" : "#formAlterPropostaLicencaAmb";

		$(idForm + " input[type='hidden'][name='tpStatus']").val('F');
		$(idForm).submit();
	}


	$("#formCadPropostaLicencaAmb").validate({
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

			$("#formCadPropostaLicencaAmb").submit(function () {

				$.ajax({
					url: 'action/eco_cadPropostaLicencaAmb.php',
					type: 'POST',
					data: $(this).serialize(),
					success: function (data) {
						$("#divResult").html(data);
					}
				})
					.done(function () {
						$("#modalNovaProposta").modal("hide");
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

	// $("#formCadPropostaLicencaAmb").submit(function(){
	// 	if(arrayAtividade.length <= 0){
	// 		$("#formCadPropostaLicencaAmb .msg-objeto").html('<p class="col-red text-center">Insira ao menos um objeto a proposta</p>');
	// 	}else{

	// 	}
	// 	return false;
	// });


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
						$("#modalNovaProposta").modal("hide");
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
</script>