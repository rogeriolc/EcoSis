<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$usuarios 		= new cUsuario;
$empreendimento = new cEmpreendimento;
$cliente 		= new cCliente;
$objLicenca		= new cObjetoLicenca;
$tpLicenca		= new cTpLicencaAmbiental;
$prop			= new cPropostaLicencaAmb;
$orgLicenciado  = new cOrgaoLicenciado;
$lic 			= new cLicencaAmbiental;

?>

<div class="container-fluid">
	<!-- <div class="block-header">
		<h2>Licenças Ambientais</h2>
	</div>
	<br/> -->
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
				<div class="header">
					<h2>
						<i class="material-icons pull-left" style="padding-right: 8px; margin-top: -2px;">assignment</i> Serviços
					</h2>
					<ul class="header-dropdown m-r--5">
						<li class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
								<i class="material-icons">more_vert</i>
							</a>
							<ul class="dropdown-menu pull-right">
								<li><a data-toggle="modal" href="javascript:void(0)" onclick="viewFormLicencaAmbiental(null)" class="waves-effect waves-block"><i class="material-icons">add</i> Novo Serviço</a></li>
							</ul>
						</li>
					</ul>
				</div>
				<div class="body">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
							<thead>
								<tr>
									<th width="30px">Cód</th>
									<th>Cliente</th>
									<th>Empreendimento</th>
									<th>N. do Processo</th>
									<th>Objetos</th>
									<th>Dt. Prev Conclusão</th>
									<th width="50px">Status</th>
								</tr>
							</thead>
							<tbody>
								<?php $lic->listTable(); ?>
							</tbody>
							<tfoot>
								<tr>
									<th width="30px">Cód</th>
									<th>Cliente</th>
									<th>Empreendimento</th>
									<th>N. do Processo</th>
									<th>Objetos</th>
									<th>Dt. Prev Conclusão</th>
									<th width="50px">Status</th>
								</tr>
							</tfoot>
							<tbody>


							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- <div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="card">
				<div class="header bg-deep-purple">
					<h2><i class="material-icons">people</i> Cliente</h2>
				</div>
				<div class="body">
					<div class="row">
						<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
							<h4>Licencas</h4>
							<br/>
							<p class="text-center"><label class="badge font-32">12</label></p>
							<br/>
							<div class="row">
								<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
									<div class="progress">
										<div class="progress-bar bg-teal" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
											3
										</div>
									</div>
								</div>
								<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
									<small>Retiradas</small>
								</div>
								<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
									<div class="progress">
										<div class="progress-bar bg-amber" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
											9
										</div>
									</div>
								</div>
								<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
									<small>Andamento</small>
								</div>
							</div>
						</div>
						<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">


						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						</div>
					</div>
				</div>
				<div class="bg-teal p-a-10 col-white">
					<p class="font-20"><i class="material-icons">description</i> Licenças</p>
				</div>
				<div class="table-responsive">
					<table class="table table-striped table-condensed table-hover tablePagination">
						<thead>
							<tr>
								<th>Empreendimento</th>
								<th>Tipo da Licença</th>
								<th>Objetos</th>
								<th>Data Prev Conclusão</th>
								<th>Status</th>
								<th>Opções</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Empreendimento</td>
								<td>Tipo da Licença</td>
								<td><span class="badge">3</span></td>
								<td>00/00/0000</td>
								<td>Em andamento</td>
								<td class="text-center"><a href="javascript:void(0)"><i class="fas fa-eye"></i></a></td>
							</tr>
							<tr>
								<td>Empreendimento</td>
								<td>Tipo da Licença</td>
								<td><span class="badge">3</span></td>
								<td>00/00/0000</td>
								<td>Em andamento</td>
								<td class="text-center"><a href="javascript:void(0)"><i class="fas fa-eye"></i></a></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div> -->

	<section id="viewFormLicencaAmbiental">

	</section>
</div>



<script type="text/javascript">

	$.AdminBSB.input.activate();
	$.AdminBSB.select.activate();

	var totalNegociado = 0;

	$('.js-basic-example').DataTable({
		responsive: true,
		pageLength: 5,
		"columnDefs": [
    		{ "type": "num", "targets": 0 }
  		]
	});

	$(document).ready(function(){
		$("div.form-line.focused").removeClass("focused");

		$('.listComentariosFase').slimScroll({
			height: '20em'
		});
	});

	// autosize($('textarea.auto-growth'));

	$(".tablePagination").dataTable({"searching": false,"bLengthChange": false,"bInfo": false});

	$(".listaObjetosProposta").sortable({
		placeholder: "ui-state-highlight"
	});

	$(".listaObjetosProposta").disableSelection();

	$(".inputMoney").maskMoney({prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',', affixesStay: false});

	$('.datepicker').bootstrapMaterialDatePicker({
		format: 'DD/MM/YYYY',
		clearButton: true,
		weekStart: 1,
		time: false
	});


	Number.prototype.format = function(n, x, s, c) {
		var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
		num = this.toFixed(Math.max(0, ~~n));
		return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
	};

	function viewFormLicencaAmbiental(l){

		var divForm = $("#viewFormLicencaAmbiental");

		$(".page-loader-wrapper").fadeIn("fast");

		$.ajax({
			url: 'action/eco_viewFormLicencaAmbiental.php',
			type: 'POST',
			data: {
				cdLicencaAmbiental: l
			},
			success: function(data){
				divForm.html(data);
			}
		})
		.done(function() {

			$("html, body").animate({ scrollTop: divForm.offset().top - 80 }, 600);
			$(".page-loader-wrapper").fadeOut("fast");

		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});

	}

</script>



