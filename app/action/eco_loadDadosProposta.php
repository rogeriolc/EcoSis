<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

if(!isset($_POST['cdPropostaLicenca'])){
	echo 'parametro incorreto';
	exit();
}

$usuarios 			= new cUsuario;
$empreendimento 	= new cEmpreendimento;
$cliente 			= new cCliente;
$tpAtividade 		= new cTpAtividade;
$prop				= new cPropostaLicencaAmb;

$cdPropostaLicenca 	= base64_decode($_POST['cdPropostaLicenca']);
$idForm			   	= "#formAlterPropostaLicencaAmb";

//pega ultima versão da proposta

//antes de alterar a proposta validar se a proposta que está sendo alterada é a ultima versão

$prop = new cPropostaLicencaAmb($cdPropostaLicenca);
$prop->Dados();

$itProposta = $prop->DadosItensProposta();

$badgeTabAssessoria 	= 0;
$badgeTabConsultoria 	= 0;

$cdCliente 				= $prop->getCdCliente();
$cdEmpreendimento 		= $prop->getCdEmpreendimento();
$dsObservacao 			= $prop->getDsObservacao();
$dtPrevConclusaoLicenca = $prop->getDtPrevConclusao();
$vlNegociado			= $prop->getVlNegociado();
$vlPago					= $prop->getVlPago();

$arrayAtvAssessoria		= array();
$arrayAtvConsultoria 	= array();

foreach ($itProposta as $key => $value) {

	if ($value['tp_atividade'] == 'C') {
		$badgeTabConsultoria++;

		array_push($arrayAtvConsultoria, $itProposta[$key]);

	} elseif ($value['tp_atividade'] == 'A') {
		$badgeTabAssessoria++;

		array_push($arrayAtvAssessoria, $itProposta[$key]);

	} else {

	}

}

?>
<form id="formAlterPropostaLicencaAmb">
	<input type="hidden" name="tpStatus" value="F">
	<input type="hidden" name="cdPropostaLicenca" value="<?php echo base64_encode($cdPropostaLicenca);?>">
	<div class="modal-header bg-deep-purple">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title"><i class="material-icons pull-left" style="margin-top: 0px; padding-right: 5px;">class</i> &nbsp;Proposta</h4>
	</div>

	<?php

	switch ($prop->tpStatus) {
		case 'F':
		echo '
		<div class="bg-green p-a-10">
		<p class="text-center">Esta proposta foi fechada</p>
		</div>
		';
		break;

		case 'E':
		echo '
		<div class="bg-amber p-a-10">
		<p class="text-center">Esta proposta ainda está sendo negociada</p>
		</div>
		';
		break;

		default:
			# code...
		break;
	}

	?>

	<!-- Nav tabs -->
	<ul class="nav nav-tabs tab-nav-right tab-col-deep-purple" role="tablist" style="padding-left: 2px;">
		<li role="presentation" class="active"><a href="#tabPropostaAtual" data-toggle="tab">Proposta Atual</a></li>
		<li role="presentation"><a href="#tabVersoesProposta" data-toggle="tab">Versões da Proposta</a></li>
	</ul>

	<!-- Tab panes -->
	<div class="tab-content">

		<div role="tabpanel" class="tab-pane fade in active" id="tabPropostaAtual">
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
										<?php $cliente->listOption($cdCliente); ?>
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
										<?php $empreendimento->listOption($cdEmpreendimento); ?>
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
						<div class="col-xs-12 col-sm-12 col-md-1">
							<br/>
							<button type="button" class="btn btn-block btn-sm bg-green waves-effect col-white" onclick="addAtividadePropostaAlter()"><i class="material-icons">add</i></button>
						</div>
					</div>
				</div>

				<!-- Nav tabs -->
				<ul class="nav nav-tabs tab-nav-right tab-col-deep-purple" role="tablist" style="padding-left: 2px;">
					<li role="presentation" class="active"><a href="#alterTabAssessoria" data-toggle="tab">Assessoria <span id="badgeAlterTabAssessoria" class="badge pull-right" style="margin-left: 10px"><?php echo $badgeTabAssessoria; ?></span></a></li>
					<li role="presentation"><a href="#alterTabConsultoria" data-toggle="tab">Consultoria <span id="badgeAlterTabConsultoria" class="badge pull-right" style="margin-left: 10px"><?php echo $badgeTabConsultoria; ?></span></a></li>
				</ul>

				<!-- Tab panes -->
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane fade in active" id="alterTabAssessoria">

						<div style="background: #E9E9E9; padding-top: 30px; min-height: 10em;">
							<div class="container-fluid">
								<ul class="list-unstyled listaAtividadesProposta">
									<?php

									$arrayvlAtividadeNegociado 	= array();
									$arrayValorPago 	 		= array();

									foreach ($arrayAtvAssessoria as $key => $value) {
										echo '
										<li>
										<div class="row">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 objElement">
										<input type="hidden" name="tpAtividade[]" class="form-control" data-index="" value="'.$value['tp_atividade'].'" />
										<div class="card">
										<div class="header bg-deep-purple cursorMove">
										<h2>'.$value['ds_tp_atividade'].'</h2>
										<ul class="header-dropdown m-r--5">
										<li class="dropdown">
										<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="material-icons">more_vert</i>
										</a>
										<ul class="dropdown-menu pull-right">
										<li>
										<a class="waves-effect waves-block" onclick="removerAtividade(this)" data-index="" data-dtotaln="" data-dtotalp="" data-badge="#badgeAlterTabAssessoria" data-qtdbadge="'.count($arrayAtvAssessoria).'">
										<i class="material-icons" style="color:#F44336 !important">delete</i> Remover</a>
										</li>
										</ul>
										</li>
										</ul>
										</div>
										<div class="body">
										<input type="hidden" name="cdTpAtividade[]" value="'.$value['cd_tp_atividade'].'"/>
										<input type="hidden" name="cdItProposta[]" value="'.$value['cd_itproposta_licenca'].'"/>
										<div class="row">
										<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
										<div class="form-group">
										<div class="form-line">
										<label>Entrega prevista</label>
										<input type="text" name="dtPrevEntregaAtividade[]" class="form-control datepicker" placeholder="" value="'.date("d/m/Y", strtotime($value['dt_prev_entrega'])).'" />
										</div>
										</div>
										</div>
										<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
										<div class="form-group">
										<div class="form-line">
										<label>Valor Negociado</label>
										<input type="text" name="vlAtividadeNegociado[]" class="form-control inputMoney" data-index="'.$value['cd_itproposta_licenca'].'" value="'.number_format($value['vl_negociado'], 2,',','.').'" />
										</div>
										</div>
										</div>
										<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
										<div class="form-group">
										<div class="form-line">
										<label>Valor Pago</label>
										<input type="text" name="vlAtividadePago[]" class="form-control inputMoney" data-index="'.$value['cd_itproposta_licenca'].'" value="'.number_format($value['vl_pago'], 2,',','.').'" />
										</div>
										</div>
										</div>
										</div>
										</div>
										</div>
										</div>
										</div>
										</li>
										';

										$arrayvlAtividadeNegociado[$value['cd_itproposta_licenca']]['valor'] = $value['vl_negociado'];
										$arrayValorPago[$value['cd_itproposta_licenca']]['valor'] = $value['vl_pago'];
									}

									?>
								</ul>
							</div>
						</div>

					</div>

					<div role="tabpanel" class="tab-pane fade" id="alterTabConsultoria">

						<div style="background: #E9E9E9; padding-top: 30px; min-height: 10em;">
							<div class="container-fluid">
								<ul class="list-unstyled listaAtividadesProposta">
									<?php

									foreach ($arrayAtvConsultoria as $key => $value) {
										echo '
										<li>
										<div class="row">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 objElement">
										<input type="hidden" name="tpAtividade[]" class="form-control" data-index="" value="'.$value['tp_atividade'].'" />
										<div class="card">
										<div class="header bg-deep-purple cursorMove">
										<h2>'.$value['ds_tp_atividade'].'</h2>
										<ul class="header-dropdown m-r--5">
										<li class="dropdown">
										<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="material-icons">more_vert</i>
										</a>
										<ul class="dropdown-menu pull-right">
										<li>
										<a class="waves-effect waves-block" onclick="removerAtividade(this)" data-index="" data-dtotaln="" data-dtotalp="" data-badge="#badgeAlterTabConsultoria" data-qtdbadge="'.count($arrayAtvConsultoria).'">
										<i class="material-icons" style="color:#F44336 !important">delete</i> Remover</a>
										</li>
										</ul>
										</li>
										</ul>
										</div>
										<div class="body">
										<input type="hidden" name="cdTpAtividade[]" value="'.$value['cd_tp_atividade'].'"/>

										<input type="hidden" name="cdItProposta[]" value="'.$value['cd_itproposta_licenca'].'"/>
										<div class="row">
										<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
										<div class="form-group">
										<div class="form-line">
										<label>Entrega prevista</label>
										<input type="text" name="dtPrevEntregaAtividade[]" class="form-control datepicker" placeholder="" value="'.date("d/m/Y", strtotime($value['dt_prev_entrega'])).'" />
										</div>
										</div>
										</div>
										<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
										<div class="form-group">
										<div class="form-line">
										<label>Valor Negociado</label>
										<input type="text" name="vlAtividadeNegociado[]" class="form-control inputMoney" data-index="'.$value['cd_itproposta_licenca'].'" value="'.number_format($value['vl_negociado'], 2,',','.').'" />
										</div>
										</div>
										</div>
										<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
										<div class="form-group">
										<div class="form-line">
										<label>Valor Pago</label>
										<input type="text" name="vlAtividadePago[]" class="form-control inputMoney" data-index="'.$value['cd_itproposta_licenca'].'" value="'.number_format($value['vl_pago'], 2,',','.').'" />
										</div>
										</div>
										</div>
										</div>
										</div>
										</div>
										</div>
										</div>
										</li>
										';

										$arrayvlAtividadeNegociado[$value['cd_itproposta_licenca']]['valor'] = $value['vl_negociado'];
										$arrayValorPago[$value['cd_itproposta_licenca']]['valor'] = $value['vl_pago'];
									}

									?>
								</ul>
							</div>
						</div>

					</div>
				</div>

				<div class="msg-objeto" style="padding: 10px;"></div>

				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<div class="form-line">
									<label>Observação</label>
									<textarea class="form-control no-resize auto-growth" name="dsObservacao" rows="5"><?php echo $dsObservacao; ?></textarea>
								</div>
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
							<label>Data Prevista Conclusão</label>
							<div class="form-group">
								<div class="form-line">
									<input type="text" name="dtPrevConclusaoLicenca" class="form-control datepicker" value="<?php echo date('d/m/Y', strtotime($dtPrevConclusaoLicenca)); ?>" />
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
										<div id="alterTotalPropostaNegociado" class="number count-to col-green" data-from="0.01" data-to="257.01" data-speed="1000" data-fresh-interval="20" style="font-size: 30px"><?php echo number_format($vlNegociado, 2, ',','.'); ?></div>
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
										<div id="alterTotalPropostaPago" class="number count-to col-green" data-from="0.01" data-to="257.01" data-speed="1000" data-fresh-interval="20" style="font-size: 30px"><?php echo number_format($vlPago, 2, ',','.'); ?></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div role="tabpanel" class="tab-pane fade" id="tabVersoesProposta">
			<div class="container-fluid">

				<?php
				$versoes = $prop->historicoVersoes();

				foreach ($versoes as $key => $versao) {

					$proposta = new cPropostaLicencaAmb($versao->cd_proposta_licenca);

					$dadosItens = $proposta->DadosItensProposta();
					$assessoria = array();
					$consultoria = array();

					foreach ($dadosItens as $dadosItem) {

						if ($dadosItem['tp_atividade'] == 'A') {
							$assessoria[] = $dadosItem;
						} else {
							$consultoria[] = $dadosItem;
						}

					}

					if (is_null($versao->cd_proposta_pai)) {
						$tpProposta = '<label class="label label-success">Proposta inicial</label>';
						$border 	= ' border-green';
					} else if ($key == 0) {
						$tpProposta = '<label class="label bg-deep-purple">Proposta atual</label>';
						$border 	= ' border-deep-purple';
					} else {
						$tpProposta = '<label class="label mdc-bg-grey-400">Proposta alterada</label>';
						$border 	= '';
					}

					echo '
					<div class="card rounded'.$border.'">
						<div class="body">
							<div class="pull-right">
								<br>
								<small>Valor:</small>
								<p>
									<small>R$</small> <span class="col-green font-30">'.number_format($versao->vl_negociado,2,',','.').'</span>
								</p>
							</div>
							<p>'.$tpProposta.'</p>
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

						</div>
					</div>
					';
				}

				?>
			</div>
		</div>
	</div>

	<div class="modal-footer">
		<button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><i class="material-icons">close</i> Fechar</button>
		<button type="button" onclick="cancelarProposta('<?php echo base64_encode($cdPropostaLicenca);?>')" class="btn bg-red waves-effect"><i class="material-icons">block</i> Cancelar</button>
		<button type="button" onclick="salvarSimplesProposta()" class="btn bg-indigo waves-effect"><i class="material-icons">save</i> Salvar Simples</button>
		<button type="button" onclick="salvarProposta('alter')" class="btn bg-indigo waves-effect"><i class="material-icons">save</i> Salvar</button>
		<button type="button" onclick="fecharProposta('alter')" class="btn bg-green waves-effect"><i class="material-icons">check</i> Fechar Proposta</button>
	</div>
</form>

<script type="text/javascript">
	$.AdminBSB.input.activate();
	$.AdminBSB.select.activate();

	function refresh(p){
        $("#divConteudo").load("view/"+p);
        $("div.overlay").trigger('click');
    }

	$(document).ready(function(){
		$("div.form-line.focused").removeClass("focused");
	});

	$(".listaAtividadesProposta .row").sortable({
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

	function getSum(total, num) {
		return total + num;
	}

	$("#formAlterPropostaLicencaAmb input[name='vlAtividadePago[]'], #formAlterPropostaLicencaAmb input[name='vlAtividadeNegociado[]']").change(function(){

		calculaTotais();

	});

	function calculaTotais()
	{
		let totalPago 		= 0;
		let totalNegociado 	= 0;

		$("#formAlterPropostaLicencaAmb input[name='vlAtividadeNegociado[]']").each(function(index, el) {

			let vlInput = $(this).val();

			vlInput  = $(this).val().replace(".","");
			vlInput 	  = vlInput.replace(",",".");
			if(vlInput == ''){vlInput=0;}

			totalNegociado 	+= Number( vlInput );

		});

		$("#formAlterPropostaLicencaAmb input[name='vlAtividadePago[]']").each(function(index, el) {

			let vlInput = $(this).val();

			vlInput  = $(this).val().replace(".","");
			vlInput 	  = vlInput.replace(",",".");
			if(vlInput == ''){vlInput=0;}

			totalPago 	+= Number( vlInput );

		});

		$("#alterTotalPropostaNegociado").html(totalNegociado.format(2, 3, '.', ','));
		$("#alterTotalPropostaPago").html(totalPago.format(2, 3, '.', ','));
	}

	function addAtividadePropostaAlter(){

		var idForm = "#formAlterPropostaLicencaAmb";

		//pega os dados do objeto da licença escolhido
		var valor = $(idForm+" select[name=cdTpAtividade]").val();
		var texto = $(idForm+" select[name=cdTpAtividade] option:selected").text();

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
				posicaoAtual: null,
				tpAtividade: tipoAtividade
			},
			success: function(data){
				$("#divResult").html(data);
			}
		});

	}

	function removerAtividade(e){


		var idBadge	 = $(e).data("badge");
		var qtdBadge = parseInt($(idBadge).text()) - 1;

		//Atualiza badge aba
		$(idBadge).html(qtdBadge);

		//Pega o elemento anterior
		$(e).closest('.objElement').remove();

		calculaTotais();

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
					$("#modalFormAlterProposta").modal("hide");
					setTimeout(function(){
                    	refresh("eco_vPagPropostaLicenca.php");
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

	function cancelarProposta(p)
	{

		swal({
			title: "Deseja realmente cancelar esta proposta?",
			icon: "warning",
  			dangerMode: true,
			buttons: {
				no: "Não",
				yes: "Sim"
			},
		})
		.then((value) => {
			switch (value) {

				case "no":
				swal("Tudo bem, nada foi alterado");
				break;

				case "yes":
				$.ajax({
					url: 'action/eco_cancelarProposta.php',
					type: 'POST',
					data: {cdPropostaLicenca: p},
					success: function(data) {
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
				break;

				default:
				swal("Got away safely!");
			}
		});
	}
</script>