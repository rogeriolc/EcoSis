<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$snPermiteAlterarData 	= cPermissao::validarPermissao(22, false);

$cdAtividade 	= $_POST['cdAtividade'];

$atv 		 	= new cAtividade($cdAtividade);
$tpAtv 		 	= new cTpAtividade();
$usuarios 	 	= new cUsuario();
$cliente 		= new cCliente;
$orgaoLic 		= new cOrgaoLicenciado;

$atv->Dados();

$cdServico   	= $atv->getCdServico();
$tpAtividade 	= $atv->getTpAtividade();
$cdTpAtividade 	= $atv->getCdTpAtividade();
$dsAtividade 	= $atv->getDsAtividade();
$nrOrdem 		= $atv->getNrOrdem();
$cdUsuarioResponsavel 	= $atv->getCdUsuarioResponsavel();
$cdOrgaoLicenciador 	= $atv->getCdOrgaoLicenciador();
$nrProcesso 	= $atv->getNrProcesso();
$dtPrevEntrega 	= implode("/",array_reverse(explode("-",$atv->getDtPrevEntrega())));
$dhRegistro 	= date("d/m/Y H:i:s", strtotime($atv->getDhRegistro()));
$tpStatus 		= $atv->getDsStatus();

switch ($tpStatus) {
	case 'E':
	$dsStatus = '<span class="col-amber">TRABALHANDO</span>';
	$headerStatus = '<div class="mdc-bg-amber col-white text-center" style="border-radius: 10px;">TRABALHANDO</div>';
	$optMenu = '<li>
	<a href="javascript:void(0);" onclick="tramiteAtividade(this)" data-cod="'.$cdAtividade.'" data-cd_atividade="'.$cdAtividade.'"  data-codserv="'.$cdServico.'" data-tptable="'.$tpAtividade.'" class="waves-effect waves-block"><i class="material-icons mdc-text-orange">compare_arrows</i> Em Trâmite</a>
	</li>
	<li>
	<a href="javascript:void(0);" onclick="concluirAtividade(this)" data-cod="'.$cdAtividade.'" data-cd_atividade="'.$cdAtividade.'"  data-codserv="'.$cdServico.'" data-tptable="'.$tpAtividade.'" class="waves-effect waves-block"><i class="material-icons mdc-text-green">done</i> Concluir</a>
	</li>
	<li>
	<a href="javascript:void(0);" onclick="removerAtividade(this)" data-cod="'.$cdAtividade.'" data-cd_atividade="'.$cdAtividade.'"  data-codserv="'.$cdServico.'" data-tptable="'.$tpAtividade.'" class="waves-effect waves-block"><i class="material-icons mdc-text-red">delete</i> Excluir</a>
	</li>';
	$disabled = null;
	break;

	case 'O':
	$dsStatus = '<span class="col-green">CONCLUÍDO</span>';
	$headerStatus = '<div class="mdc-bg-green col-white text-center" style="border-radius: 10px;">CONCLUÍDO</div>';
	$optMenu = '
	<li>
	<a href="javascript:void(0);" onclick="reabrirAtividade(this)" data-cod="'.$cdAtividade.'"  data-cd_atividade="'.$cdAtividade.'"  data-codserv="'.$cdServico.'" data-tptable="'.$tpAtividade.'" class="waves-effect waves-block"><i class="material-icons mdc-text-amber">refresh</i> Reabrir</a>
	</li>';
	$disabled = 'disabled';
	break;

	case 'R':
	$dsStatus = '<span class="col-orange">REABERTO</span>';
	$headerStatus = '<div class="mdc-bg-orange col-white text-center" style="border-radius: 10px;">REABERTO</div>';
	$optMenu = '<li>
	<a href="javascript:void(0);" onclick="tramiteAtividade(this)" data-cod="'.$cdAtividade.'" data-cd_atividade="'.$cdAtividade.'"  data-codserv="'.$cdServico.'" data-tptable="'.$tpAtividade.'" class="waves-effect waves-block"><i class="material-icons mdc-text-orange">compare_arrows</i> Em Trâmite</a>
	</li>
	<li>
	<a href="javascript:void(0);" onclick="concluirAtividade(this)" data-cod="'.$cdAtividade.'" data-cd_atividade="'.$cdAtividade.'"  data-codserv="'.$cdServico.'" data-tptable="'.$tpAtividade.'" class="waves-effect waves-block"><i class="material-icons mdc-text-green">done</i> Concluir</a>
	</li>
	<li>
	<a href="javascript:void(0);" onclick="removerAtividade(this)" data-cod="'.$cdAtividade.'" data-cd_atividade="'.$cdAtividade.'"  data-codserv="'.$cdServico.'" data-tptable="'.$tpAtividade.'" class="waves-effect waves-block"><i class="material-icons mdc-text-red">delete</i> Excluir</a>
	</li>';
	$disabled = null;
	break;

	case 'T':
	$dsStatus = '<span class="col-orange">EM TRÂMITE</span>';
	$headerStatus = '<div class="mdc-bg-orange col-white text-center" style="border-radius: 10px;">EM TRÂMITE</div>';
	$optMenu = '<li>
	<a href="javascript:void(0);" onclick="trabalharAtividade(this)" data-cod="'.$cdAtividade.'" data-cd_atividade="'.$cdAtividade.'"  data-codserv="'.$cdServico.'" data-tptable="'.$tpAtividade.'" class="waves-effect waves-block"><i class="material-icons mdc-text-orange">build</i> Trabalhar</a>
	</li>
	<li>
	<a href="javascript:void(0);" onclick="concluirAtividade(this)" data-cod="'.$cdAtividade.'" data-cd_atividade="'.$cdAtividade.'"  data-codserv="'.$cdServico.'" data-tptable="'.$tpAtividade.'" class="waves-effect waves-block"><i class="material-icons mdc-text-green">done</i> Concluir</a>
	</li>
	<li>
	<a href="javascript:void(0);" onclick="removerAtividade(this)" data-cod="'.$cdAtividade.'" data-cd_atividade="'.$cdAtividade.'"  data-codserv="'.$cdServico.'" data-tptable="'.$tpAtividade.'" class="waves-effect waves-block"><i class="material-icons mdc-text-red">delete</i> Excluir</a>
	</li>';
	$disabled = null;
	break;

	case 'S':
	$dsStatus 		= '<span class="col-red">SUSPENSA VIA PROPOSTA</span>';
	$headerStatus 	= '<div class="mdc-bg-red text-center" style="border-radius: 10px;">SUSPENSA VIA PROPOSTA</div>';
	$optMenu 		= '';
	$disabled 		= 'disabled';
	break;

	default:
	$dsStatus = $tpStatus;
	$headerStatus = null;
	$optMenu = '';
	$disabled = null;
	break;
}

?>
<?php //echo $dados['cd_servico'];?>
<div class="row" style="display: flex;  padding: 0 0 0 10px; overflow-x: hidden;">
	<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 mdc-bg-grey-100" style="padding-top: 10px; overflow-x: hidden; height: 100vh;">
		<div class="container-fluid">
			<button type="button" class="close pull-left" data-dismiss="modal" aria-hidden="true"><i class="material-icons">arrow_back</i></button>

			<?php
			if (!empty($optMenu)) {
				?>
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
				<?php
			}
			?>

			<form id="formAtividade" method="POST" class="m-t-40">
				<input type="hidden" name="cdAtividade" value="<?php echo $cdAtividade; ?>" />
				<input type="hidden" name="cdServico" value="<?php echo $cdServico; ?>" />

				<div class="text-center" style="width: 70%; margin: 0 auto;">
					<?php echo $headerStatus; ?>
				</div>
				<br>

				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="form-group">
							<strong>Item do serviço:</strong>
							<div class="form-line">
								<select name="cdTpAtividade" style="width:100%" class="form-control" data-live-search="true" <?php echo $disabled; ?>>
									<option></option>
									<?php $tpAtv->listOption($cdTpAtividade); ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<strong>Orgão Licenciador:</strong>
							<select name="cdOrgaoLicenciado" style="width:100%" class="form-control" data-live-search="true">
								<option></option>
								<?php $orgaoLic->listOption($cdOrgaoLicenciador); ?>
							</select>
						</div>
						<div class="form-group">
							<strong>Nº do processo:</strong>
							<div class="form-line">
								<input type="text" name="nrProcesso" class="form-control" value="<?php echo $nrProcesso;?>" />
							</div>
						</div>
						<div class="form-group">
							<strong>Descrição da atividade:</strong>
							<div class="form-line">
								<textarea class="form-control no-resize" name="dsAtividade" rows="4" <?php echo $disabled; ?>><?php echo $dsAtividade; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<strong>Responsável:</strong>
							<div class="input-group">
								<span class="input-group-addon">
									<i class="material-icons">person</i>
								</span>
								<select name="cdUsuarioResponsavel" style="width:100%" class="form-control" data-live-search="true" <?php echo $disabled; ?>>
									<option></option>
									<?php $usuarios->listOption($cdUsuarioResponsavel); ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<strong>Previsão de Entrega:</strong>
							<div class="input-group">
								<span class="input-group-addon">
									<i class="material-icons">calendar_today</i>
								</span>
								<div class="form-line">
									<input class="form-control datepicker" name="dtPrevEntrega" value="<?php echo $dtPrevEntrega; ?>" <?php echo ($disabled == 'disabled' || !$snPermiteAlterarData) ? 'disabled' : null ; ?> />
								</div>
							</div>
							<div class="hide" id="justificativaAlterData">
								<strong>Por que você está alterando a data?</strong>
								<div class="form-line">
									<textarea class="form-control no-resize" name="dsJustificativa" rows="3"></textarea>
								</div>
							</div>
							<div class="text-right m-t-5">
								<a data-toggle="modal" href="#modalHistPrevData" aria-expanded="false" aria-controls="modalHistPrevData">
									<i class="material-icons">history</i>&nbsp; <span style="position: relative; top: -8px;">Ver alterações</span>
								</a>
							</div>
						</div>
						<div class="form-group">
							<strong>Prioridade:</strong>
							<div class="form-line">
								<select name="nrOrdem" class="form-control" data-live-search="true" <?php echo $disabled; ?>>
									<?php
									for ($i=0; $i <= 10; $i++) {
										$selected = ($i == $nrOrdem) ? 'selected' : null;
										echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
									}
									?>
								</select>
							</div>
							<span class="col-red">Quanto <strong>MENOR</strong> o número, maior a prioridade</span>
						</div>
					</div>
				</div>

				<div class="text-right">

					<button type="submit" class="btn bg-deep-purple" <?php echo $disabled; ?>><i class="material-icons">save</i> Salvar</button>

				</div>
			</form>

		</div>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 mdc-bg-grey-100" id="divAndamento" style="overflow-x: hidden; height: 100vh;">
		<?php

		if ($tpAtividade == 'C') {
			include('../view/eco_vFormAcompConsultoria.php');
		} elseif ($tpAtividade == 'A') {
			include('../view/eco_vFormAndamento.php');
		}else {
			echo 'parametro incorreto';
		}

		?>

	</div>
</div>

<div id="msgAtividade"></div>
<script type="text/javascript">

	$.AdminBSB.input.activate();
	$.AdminBSB.select.activate();

	$('#formAtividade input[name=dtPrevEntrega]').datetimepicker({
		format: 'DD/MM/YYYY',
	});
	
	$('#formAtividade input[name=dtPrevEntrega]').datetimepicker()
	.on("dp.change", function (e) {
		let dtAtual = '<?php echo $dtPrevEntrega; ?>';
		let dataSelecionada = e.date.format("DD/MM/YYYY");

		if(dtAtual != dataSelecionada) {
			$("#justificativaAlterData").removeClass("hide");
			$("#justificativaAlterData").addClass("show");
		}
	});

	$(document).ready(function(){
		$("div.form-line.focused").removeClass("focused");
	});

	$("#formAtividade").submit(function(event) {
		let justificativa 	= $("#formAtividade textarea[name=dsJustificativa]").val();
		let dtAtual 		= '<?php echo $dtPrevEntrega; ?>';
		let dataSelecionada = $('#formAtividade input[name=dtPrevEntrega]').val();

		if(dtAtual != dataSelecionada && justificativa == '') {
			alert("Digite uma justificativa para continuar");
			return false;
		} else {
			$.ajax({
				url: 'action/eco_alterAtividade.php',
				type: 'POST',
				data: $(this).serialize(),
				success: function(data){
					$("#msgAtividade").html(data);
				}
			})
			.done(function() {
				console.log("success");
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
		}

		event.preventDefault();

		return false;

	});

</script>