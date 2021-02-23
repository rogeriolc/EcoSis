<?php
include '../app/conf/MysqlConexao.php';
include '../app/model/mEmpresa.php';
include '../app/control/cEmpresa.php';
include '../app/model/mPessoa.php';
include '../app/model/mUsuario.php';
include '../app/control/cUsuario.php';

$token = isset($_GET['t']) ? $_GET['t'] : null;
$error = isset($_GET['e']) ? $_GET['e'] : null;

?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<title>EcoSis | Calango - Esqueceu a senha</title>
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
	<link rel="icon" type="image/png" sizes="192x192"  href="../lib/media/favicon/android-icon-192x192.png">
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
	<link href="../lib/plugins/node-waves/waves.css" rel="stylesheet"/>

	<!-- Animation Css -->
	<link href="../lib/plugins/animate-css/animate.css" rel="stylesheet"/>

	<!-- Bootstrap Material Datetime Picker Css -->
	<link href="../lib/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

	<!-- Morris Chart Css-->
	<link href="../lib/plugins/morrisjs/morris.css" rel="stylesheet"/>

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
	<link href="../lib/css/themes/all-themes.css" rel="stylesheet"/>
</head>

<body class="mdc-bg-grey-200">
	<div class="text-center bg-deep-purple-gr" style="position: fixed; top: 0; z-index:-1; height: 100%; width: 100%; display: flex; align-items: center; text-align: center; align-content: center"></div>
	<div class="login-page" style="background: none;">
		<div class="login-box">
			<div class="logo animated fadeIn" style="margin-left: -15px;">
				<a href="javascript:void(0);"><i class="fab fa-envira fa-lg col-green"></i> <b>Eco</b>Sis</a>
				<!-- <small>Sistema Administrativo Ambiental</small> -->
			</div>
			<div class="card">
				<div class="body">
					<?php if(is_null($token)){ ?>
						<form id="formRecSenha" method="POST" action="../app/action/recuperaSenha.php">
							<div class="text-center">
								<h3>Esqueceu a senha?</h3>
							</div>
							<div class="msg">
								<p class=" text-center">
									Não tem problema!
									<br>
									<br>
									Digite seu e-mail abaixo e vamos iniciar a recuperação da sua senha.
								</p>
							</div>
							<div class="input-group">
								<span class="input-group-addon">
									<i class="material-icons">alternate_email</i>
								</span>
								<div class="form-line">
									<input type="email" class="form-control" name="dsEmail" placeholder="Digite seu email..." autocomplete="off" required autofocus>
								</div>
							</div>

							<?php
							if (!is_null($error)) {
								echo '
								<p class="col-red text-center">'.base64_decode($error).'</p>
								<br>
								';
							}
							?>

							<button class="btn btn-block bg-deep-purple waves-effect" type="submit">Recuperar</button>
						</form>
					<?php } else { ?>

						<?php
						$cdUsuario = cUsuario::validarToken($token);

						if (!isset($cdUsuario) || $cdUsuario == 0 || is_null($cdUsuario)) {

							echo '<div class="col-red text-center">Token inválido!</div>';

						} else {
							?>
							<form id="formAlterSenha" method="POST" action="../app/action/recuperaSenhaAlterar.php">
								<input type="hidden" name="token" value="<?php echo $token; ?>">
								<div class="text-center">
									<h3>Recuperação de senha</h3>
								</div>
								<div class="msg">
									<p class=" text-center">
										Digite sua nova senha abaixo
									</p>
								</div>
								<div class="input-group">
									<span class="input-group-addon">
										<i class="material-icons">lock</i>
									</span>
									<div class="form-line">
										<input type="password" class="form-control" name="dsSenha" placeholder="Digite sua nova senha..." required autofocus>
									</div>
								</div>
								<div class="input-group">
									<span class="input-group-addon">
										<i class="material-icons">lock</i>
									</span>
									<div class="form-line">
										<input type="password" class="form-control" name="dsSenhaConfirm" placeholder="Confirme sua nova senha..." required>
									</div>
								</div>

								<?php
								if (!is_null($error)) {
									echo '
									<p class="col-red text-center">'.base64_decode($error).'</p>
									<br>
									';
								}
								?>


								<button class="btn btn-block bg-deep-purple waves-effect" type="submit">Alterar a senha</button>
							</form>
							<?php
						}
						?>
						<?php
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

		function pag(p){
			$("#divConteudo").load("view/"+p);
			$("div.overlay").trigger('click');
		}
	</script>
</body>

</html>