<?php
include '../app/conf/MysqlConexao.php';
include '../app/model/mEmpresa.php';
include '../app/control/cEmpresa.php';
include '../app/model/mPessoa.php';
include '../app/model/mUsuario.php';
include '../app/control/cUsuario.php';
include '../app/model/mPropostaLicencaAmb.php';
include '../app/control/cProposta.php';

$token = isset($_GET['t']) ? $_GET['t'] : null;
$error = isset($_GET['e']) ? $_GET['e'] : null;

?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<title>EcoSis | Calango - Aprovação da Proposta</title>
	<!-- Favicon-->
	<link rel="apple-touch-icon" sizes="57x57" href="../lib/media/favicon/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="../lib/media/favicon/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="../lib/media/favicon/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="../lib/media/favicon/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="../lib/media/favicon/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="../lib/media/favicon/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="../lib/media/favicon/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="../lib/media/favicon/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="../lib/media/favicon/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192" href="../lib/media/favicon/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="../lib/media/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="../lib/media/favicon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="../lib/media/favicon/favicon-16x16.png">
	<link rel="manifest" href="/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="lib/media/favicon/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">

	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Poppins:300,regular,500,600,700" />
	<link href="https://fonts.googleapis.com/css?family=Hammersmith+One" rel="stylesheet">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

	<!-- Fontawesome -->
	<link rel="stylesheet" type="text/css" href="../lib/plugins/font-awesome-5/css/fontawesome-all.min.css" />

	<!-- Bootstrap Core Css -->
	<link href="../lib/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

	<!-- Waves Effect Css -->
	<link href="../lib/plugins/node-waves/waves.css" rel="stylesheet" />

	<!-- Animation Css -->
	<link href="../lib/plugins/animate-css/animate.css" rel="stylesheet" />

	<!-- Bootstrap Material Datetime Picker Css -->
	<link href="../lib/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

	<!-- Morris Chart Css-->
	<link href="../lib/plugins/morrisjs/morris.css" rel="stylesheet" />

	<!-- JQuery DataTable Css -->
	<link href="../lib/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

	<!-- SweetAlert -->
	<!-- <link href="../lib/plugins/sweetalert/sweetalert.css"h rel="stylesheet"> -->

	<!-- Bootstrap Select Css -->
	<link href="../lib/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
	<!-- Custom Css -->
	<link href="../lib/css/style.css" rel="stylesheet">

	<!-- Material Colors -->
	<link href="../lib/css/material-colors/material-design-color-palette.min.css" rel="stylesheet">

	<!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
	<link href="../lib/css/themes/all-themes.css" rel="stylesheet" />
</head>

<body class="mdc-bg-grey-200">
	<div class="text-center bg-deep-purple-gr" style="position: fixed; top: 0; z-index:-1; height: 100%; width: 100%; display: flex; align-items: center; text-align: center; align-content: center"></div>
	<div class="login-page" style="background: none; max-width: 600px">
		<div class="login-box">
			<div class="logo animated fadeIn" style="margin-left: -15px;">
				<a href="javascript:void(0);"><i class="fab fa-envira fa-lg col-green"></i> <b>Eco</b>Sis</a>
			</div>
			<div class="card">
				<div class="body">
					<?php
					if (is_null($token)) {
					?>
						<div class="text-center">
							<h3>Hmm, temos um problema...</h3>
						</div>
						<br>
						<div class="msg">
							<p class=" text-center">
								Não foi possível encontrar uma proposta para aprovação.
								<br>
								<br>
								Se você estiver vendo esta tela frequentemente, favor entre em contato concosco.
							</p>
						</div>
						<?php
					} else {
						$proposta = cProposta::getPropostaByToken($token);

						if (!$proposta) {
							echo '<h4 class="text-center">Não foi possível encontrar uma proposta para aprovar.</h4>';
						} else if (date('Y-m-d', strtotime($proposta->dt_prev_conclusao)) < date('Y-m-d')) {
							echo '<h4 class="text-center">Esta proposta está venciada</h4>';
						} else if ($proposta->dh_aprovado) {
							echo '<h4 class="text-center">Você já respondeu sobre a aprovação desta proposta.</h4>';
						} else {
							$itens = cProposta::getItensProposta($proposta->cd_proposta, $proposta->cd_proposta_cliente);
							$vlTotal = 0;
						?>

							<p class="text-justify">Olá, esta tela é para você avaliar nossa proposta. Segue abaixo os dados e valores:</p>

							<br />

							<p>Cliente: <strong><?php echo $proposta->nm_cliente ?></strong></p>
							<p>Empreendimento: <strong><?php echo $proposta->nm_empreendimento ?></strong></p>

							<br />

							<h4>Itens</h4>

							<table class="table table-striped">
								<thead>
									<tr>
										<th>Atividade</th>
										<th>Data Prevista</th>
										<th>Valor (R$)</th>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach ($itens as $key => $item) {
										$vlTotal += floatval($item['valor']);
									?>
										<tr>
											<td><?php echo $item['ds_tp_atividade']; ?></td>
											<td><?php echo date('d/m/Y', strtotime($item['dt_prev_entrega'])); ?></td>
											<td><?php echo number_format($item['valor'], 2, ',', '.'); ?></td>
										</tr>
									<?php
									}
									?>
								</tbody>
							</table>

							<h4>Descrição:</h4>

							<div style="padding: 20px 5px">
								<p class="text-justify"><?php echo $proposta->ds_observacao; ?></p>
							</div>

							<div class="row">
								<div class="col-md-12 text-right">
									<h2><small class="mdc-text-grey-500">R$ &nbsp;&nbsp;</small><span class="mdc-text-green-500"><?php echo number_format($vlTotal, 2, ',', '.'); ?></span></h2>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<button class="btn btn-danger btn-block" onclick="approve(false)">Não Aprovar</button>
								</div>
								<div class="col-md-6">
									<button class="btn btn-success btn-block" onclick="approve(true)">Aprovar</button>
								</div>
							</div>

					<?php
						}
					}
					?>
				</div>
			</div>
		</div>
	</div>

	<div id="divResult"></div>


	<!-- Jquery Core Js -->
	<script src="../lib/plugins/jquery/jquery.min.js"></script>

	<!-- Jquery Ui -->
	<script src="../lib/plugins/jquery/jquery-ui.min.js"></script>

	<!-- Bootstrap Core Js -->
	<script src="../lib/plugins/bootstrap/js/bootstrap.js"></script>

	<!-- Select Plugin Js -->
	<script src="../lib/plugins/bootstrap-select/js/bootstrap-select.js"></script>

	<!-- Slimscroll Plugin Js -->
	<script src="../lib/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

	<!-- Waves Effect Plugin Js -->
	<script src="../lib/plugins/node-waves/waves.js"></script>

	<!-- Jquery CountTo Plugin Js -->
	<script src="../lib/plugins/jquery-countto/jquery.countTo.js"></script>

	<!-- Morris Plugin Js -->
	<script href="../lib/plugins/raphael/raphael.min.js"></script>
	<script href="../lib/plugins/morrisjs/morris.js"></script>

	<!-- ChartJs -->
	<script src="../lib/plugins/chartjs/Chart.bundle.js"></script>

	<!-- Flot Charts Plugin Js -->
	<script src="../lib/plugins/flot-charts/jquery.flot.js"></script>
	<script src="../lib/plugins/flot-charts/jquery.flot.resize.js"></script>
	<script src="../lib/plugins/flot-charts/jquery.flot.pie.js"></script>
	<script src="../lib/plugins/flot-charts/jquery.flot.categories.js"></script>
	<script src="../lib/plugins/flot-charts/jquery.flot.time.js"></script>

	<!-- Sparkline Chart Plugin Js -->
	<script src="../lib/plugins/jquery-sparkline/jquery.sparkline.js"></script>

	<!-- Jquery DataTable Plugin Js -->
	<script src="../lib/plugins/jquery-datatable/jquery.dataTables.js"></script>
	<script src="../lib/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>

	<!-- SweetAlert -->
	<script src="../lib/plugins/sweetalert/sweetalert2.min.js"></script>

	<!-- JqueryValidator -->
	<script src="../lib/plugins/jquery-validation/jquery.validate.js"></script>

	<!-- InputMask -->
	<script src="../lib/plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>

	<!-- MaskMoney -->
	<script src="../lib/plugins/mask-money/jquery.maskMoney.min.js"></script>

	<!-- Moment Plugin Js -->
	<script src="../lib/plugins/momentjs/moment.js"></script>

	<!-- Autosize Plugin Js -->
	<script src="../lib/plugins/autosize/autosize.js"></script>

	<!-- Bootstrap Material Datetime Picker Plugin Js -->
	<script src="../lib/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>

	<!-- jQuery MD5 -->
	<script src="../lib/plugins/jquery-md5/jquery.md5.js"></script>

	<!-- Custom Js -->
	<script src="../lib/js/admin.js"></script>
	<!--<script src="../lib/js/pages/index.js"></script>-->

	<!-- Demo Js -->
	<script src="../lib/js/demo.js"></script>
	<script type="text/javascript">
		$.AdminBSB.input.activate();

		function approve(approve) {
			const text = approve ? 'Tem certeza que deseja aprovar nossa proposta?' : 'Respeitamos sua decisão, mas queremos apenas confirmar. Deseja desistir da proposta?';

			swal({
					title: "Aprovação da Proposta",
					text,
					icon: "warning",
					buttons: {
						cancel: {
							text: "Não, vou analisar melhor",
							value: null,
							visible: true,
							className: "",
							closeModal: true,
						},
						confirm: {
							text: "Sim, estou certo!",
							value: true,
							visible: true,
							className: "bg-green",
							closeModal: true
						}
					}
				})
				.then((willDelete) => {
					if (willDelete) {
						$.ajax({
								url: '../app/action/eco_clienteAprovarProposta.php',
								type: 'POST',
								data: {
									token: '<?php echo $token; ?>',
									approve
								},
								success: function(data) {
									$("#divResult").html(data);
								}
							})
							.done(function() {
								window.location.reload();
							})
							.fail(function() {
								console.log("error");
							})
							.always(function() {});
					} else {
						swal({
							title: "Certo!",
							text: "Nada foi alterado",
							icon: "info"
						});
					}
				});
		}
	</script>
</body>

</html>