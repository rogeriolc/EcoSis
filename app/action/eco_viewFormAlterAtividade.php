<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdAtividade = $_POST['cdAtividade'];

$atv 		 = new cAtividade($cdAtividade);
$tpAtv 		 = new cTpAtividade();
$usuarios 	 = new cUsuario();

$atv->Dados();

$cdServico   	= $atv->getCdServico();
$tpAtividade 	= $atv->getTpAtividade();
$cdTpAtividade 	= $atv->getCdTpAtividade();
$dsAtividade 	= $atv->getDsAtividade();
$cdResponsavel 	= $atv->getCdUsuarioResponsavel();
$dtPrevEntrega 	= implode("/",array_reverse(explode("-",$atv->getDtPrevEntrega())));
$dhRegistro 	= date("d/m/Y H:i:s", strtotime($atv->getDhRegistro()));
$tpStatus 		= $atv->getDsStatus();

switch ($tpStatus) {
	case 'E':
	$dsStatus = '<span class="col-amber">TRABALHANDO</span>';
	$optMenu = '<li>
	<a href="javascript:void(0);" onclick="concluirAtividade(this)" data-cod="'.$cdAtividade.'" data-cd_atividade="'.$cdAtividade.'"  data-codserv="'.$cdServico.'" data-tptable="'.$tpAtividade.'" class="waves-effect waves-block"><i class="material-icons mdc-text-green">done</i> Concluir</a>
	</li>
	<li>
	<a href="javascript:void(0);" onclick="removerAtividade(this)" data-cod="'.$cdAtividade.'" data-cd_atividade="'.$cdAtividade.'"  data-codserv="'.$cdServico.'" data-tptable="'.$tpAtividade.'" class="waves-effect waves-block"><i class="material-icons mdc-text-red">delete</i> Excluir</a>
	</li>';
	$disabled = null;
	break;

	case 'O':
	$dsStatus = '<span class="col-green">CONCLUÍDO</span>';
	$optMenu = '
	<li>
	<a href="javascript:void(0);" onclick="reabrirAtividade(this)" data-cod="'.$cdAtividade.'"  data-cd_atividade="'.$cdAtividade.'"  data-codserv="'.$cdServico.'" data-tptable="'.$tpAtividade.'" class="waves-effect waves-block"><i class="material-icons mdc-text-amber">refresh</i> Reabrir</a>
	</li>';
	$disabled = 'disabled';
	break;

	case 'R':
	$dsStatus = '<span class="col-orange">REABERTO</span>';
	$optMenu = '<li>
	<a href="javascript:void(0);" onclick="concluirAtividade(this)" data-cod="'.$cdAtividade.'" data-cd_atividade="'.$cdAtividade.'"  data-codserv="'.$cdServico.'" data-tptable="'.$tpAtividade.'" class="waves-effect waves-block"><i class="material-icons mdc-text-green">done</i> Concluir</a>
	</li>
	<li>
	<a href="javascript:void(0);" onclick="removerAtividade(this)" data-cod="'.$cdAtividade.'" data-cd_atividade="'.$cdAtividade.'"  data-codserv="'.$cdServico.'" data-tptable="'.$tpAtividade.'" class="waves-effect waves-block"><i class="material-icons mdc-text-red">delete</i> Excluir</a>
	</li>';
	$disabled = null;
	break;

	default:
	$dsStatus = $tpStatus;
	$optMenu = '';
	$disabled = null;
	break;
}

?>
<?php //echo $dados['cd_servico'];?>
<style type="text/css">
	.stepwizard-step { -webkit-transition: 0.1s ease; }
	div.ui-sortable-helper { -webkit-transform: scale(1.2); -webkit-transition: 0.1s ease;}
	.ui-state-highlight { height: 1.5em; line-height: 1.2em; background: #7E57C2; padding: 50px; -webkit-transition: 0.1s ease; }
</style>
<input type="hidden" name="cdAtividade" value="<?php echo $cdAtividade; ?>" />
<input type="hidden" name="cdServico" value="<?php echo $cdServico; ?>" />
<div class="modal-content">
	<div class="modal-header bg-deep-purple">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title"><i class="material-icons pull-left p-r-10 m-t--3">info</i> Informações da Atividade</h4>
	</div>
	<div class="mdc-bg-grey-100 p-a-10">

		<ul class="m-r--5 pull-right" style="list-style: none; margin-bottom: 0;">
			<li class="dropdown">
				<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
					<i class="material-icons mdc-text-grey-600 p-r-10">more_vert</i>
				</a>
				<ul class="dropdown-menu pull-right">
					<?php echo $optMenu; ?>
				</ul>
			</li>
		</ul>

		<ul class="list-unstyled list-inline font-12">
			<li><strong>Iniciado em:</strong><li><?php echo $dhRegistro; ?></li>
			<li><strong>Status:</strong><li><?php echo $dsStatus; ?></li>
		</ul>
	</div>
	<div class="stepwizard">
		<div class="stepwizard-row setup-panel text-center" id="timeLineFases">
			<div class="stepwizard-step col-xs-3 text-center">
				<a href="#step-1" role="button" class="btn btn-success btn-circle waves-effect waves-circle waves-float text-center btn-faseAtv" data-toggle="popover"><i class="material-icons">done</i></a>
				<p class="text-center"><small>Preparação de campo</small></p>
			</div>
			<div class="stepwizard-step col-xs-3 text-center">
				<a href="#step-2" role="button" class="btn bg-grey btn-circle waves-effect waves-circle waves-float text-center"><i class="material-icons">redo</i></a>
				<p class="text-center"><small>Agendamento do campo</small></p>
			</div>
			<div class="stepwizard-step col-xs-3 text-center">
				<a href="#step-3" role="button" class="btn bg-amber btn-circle waves-effect waves-circle waves-float text-center"><i class="material-icons">build</i></a>
				<p class="text-center"><small>Elaboração do texto</small></p>
			</div>
			<div class="stepwizard-step col-xs-3 text-center">
				<a href="#step-4" role="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float text-center"><i class="material-icons">more_horiz</i></a>
				<p class="text-center"><small>Elaboração dos mapas</small></p>
			</div>
			<div class="stepwizard-step col-xs-3 text-center">
				<a href="#step-4" role="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float text-center"><i class="material-icons">more_horiz</i></a>
				<p class="text-center"><small>Revisão do produto</small></p>
			</div>
			<div class="stepwizard-step col-xs-3 text-center">
				<a href="#step-4" role="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float text-center"><i class="material-icons">more_horiz</i></a>
				<p class="text-center"><small>Revisao do Cliente</small></p>
			</div>
			<div class="stepwizard-step col-xs-3 text-center">
				<a href="#step-4" role="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float text-center"><i class="material-icons">more_horiz</i></a>
				<p class="text-center"><small>Emissão de Art</small></p>
			</div>
			<div class="stepwizard-step col-xs-3 text-center">
				<a href="#step-4" role="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float text-center"><i class="material-icons">more_horiz</i></a>
				<p class="text-center"><small>Alterações após revisão do cliente</small></p>
			</div>
			<div class="stepwizard-step col-xs-3 text-center">
				<a href="#step-4" role="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float text-center"><i class="material-icons">more_horiz</i></a>
				<p class="text-center"><small>Impressão</small></p>
			</div>
		</div>
	</div>

	<div class="alert alert-danger">
		<strong>Atenção!</strong> As fases acima são um teste, ou seja, não representa a atividade atual!
	</div>

	<div class="modal-body">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
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
							<strong>Descrição da atividade:</strong>
							<div class="form-line">
								<textarea class="form-control no-resize" name="dsAtividade" rows="10" <?php echo $disabled; ?>><?php echo $dsAtividade; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<strong>Responsável:</strong>
							<div class="input-group">
								<span class="input-group-addon">
									<i class="material-icons">person</i>
								</span>
								<select name="cdUsuario" style="width:100%" class="form-control" data-live-search="true" <?php echo $disabled; ?>>
									<option></option>
									<?php $usuarios->listOption($cdResponsavel); ?>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
								<div class="form-group">
									<strong>Previsão de Entrega:</strong>
									<div class="input-group">
										<span class="input-group-addon">
											<i class="material-icons">calendar_today</i>
										</span>
										<div class="form-line">
											<input class="form-control datepicker" name="dtPrevEntrega" value="<?php echo $dtPrevEntrega; ?>" <?php echo $disabled; ?> />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
						<div style="height: 313px; border-bottom: 2px solid #eee; overflow-x: hidden;" id="listaComentariosAtividade" class="mdc-bg-grey-100 p-a-10">
							<?php $atv->ListarComentarios(); ?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
						<div class="m-t-5">
							<div class="form-group">
								<div class="input-group">
									<div class="form-line">
										<textarea name="dsComentario" class="form-control no-resize" rows="3" placeholder="Digite aqui sua mensagem..."></textarea>
									</div>
									<span class="input-group-addon">
										<button type="button" class="btn bg-deep-purple pull-right btn-circle-lg waves-effect waves-circle waves-float" onclick="comentarAtividade(this)" data-cod="<?php echo $cdAtividade; ?>" type="button"><i class="material-icons col-white">send</i></button>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- abas dos anexos -->
	<div role="tabpanel">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs tab-col-deep-purple" role="tablist">
			<li role="presentation" class="active">
				<a href="#formAnexo" aria-controls="formAnexo" role="tab" data-toggle="tab">Anexar Protocolo</a>
			</li>
			<li role="presentation">
				<a href="#listAnexo" aria-controls="tab" role="tab" data-toggle="tab">Protocolos Anexados</a>
			</li>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="formAnexo">
				<form action="action/eco_uploadAnexoAtividade.php" id="frmFileUpload" class="dropzone" method="POST" target="_blank" enctype="multipart/form-data">
					<input type="hidden" name="cdAtividade" value="<?php echo $cdAtividade;?>">
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
			<div role="tabpanel" class="tab-pane" id="listAnexo">
				<div class="container-fluid">
					<div class="row">
						<?php $atv->ListarAnexos(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal"><i class="material-icons pull-left m-t--5 col-red">close</i> Fechar</button>
		<button type="submit" class="btn bg-green"><i class="material-icons pull-left m-t--5">save</i> Salvar</button>
	</div>
</div>

<div id="divFormFase" class="hide container-fluid">
	<form id="formFase" class="form-inline" role="form">
		<button type="button" onclick="concluirFase(this)" class="btn bg-green">Concluir</button>
		<button type="button" onclick="pularFase(this)" class="btn bg-grey">Pular</button>
		<button type="button" onclick="voltarFase(this)" class="btn bg-blue">Voltar</button>
	</form>
</div>
<script type="text/javascript">
	$.AdminBSB.input.activate();
	$.AdminBSB.select.activate();

	$("div.form-line.focused").removeClass("focused");

	$('.datepicker').bootstrapMaterialDatePicker({
		format: 'DD/MM/YYYY',
		lang: 'pt-BR',
		nowButton: true,
		switchOnClick: true,
		weekStart: 1,
		time: false
	});

	$(document).ready(function(){
		//Dropzone
		var myDropzone = new Dropzone("#frmFileUpload", { url: "action/eco_uploadProtocolo.php", addRemoveLinks: true });
		//atualiza lista de anexos
		myDropzone.on("complete", function (file) {
			if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {

				var cdAtividade = $("#frmFileUpload").find('input[type=hidden][name=cdAtividade]').val();

				$.ajax({
					url: 'action/eco_listarAnexos.php',
					type: 'POST',
					data: {cdAtv: cdAtividade},
					success: function(data){
						$("#listAnexo > div.row").html(data);
					}
				})
				.done(function() {
					console.log("success");
					setTimeout(function(){
						Dropzone.forElement("#frmFileUpload").removeAllFiles(true);
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

		$("#timeLineFases").sortable({
			placeholder: "ui-state-highlight"
		});
		$("#timeLineFases").disableSelection();
	});

	$(".btn-faseAtv").popover({
		title: '<h4>Esta fase foi:</h4>',
		container: 'body',
		placement: 'bottom',
		html: true,
		content: function(){
			return $('#divFormFase').html();
		}
	});

	function concluirFase(a){
		$(".btn-faseAtv").popover('toggle');
	}

	function pularFase(a){
		$(".btn-faseAtv").popover('toggle');
	}

	function voltarFase(a){
		$(".btn-faseAtv").popover('toggle');
	}
</script>