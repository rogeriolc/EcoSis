<?php

session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$dropbox 	= new cDropbox();

$mysql 		= MysqlConexao::getInstance();

$cdUsuario		= $_SESSION['cdUsuario'];
$nmUsuario 		= $_SESSION['nmUsuario'];
$cdAtividade 	= base64_decode($_POST['cdAtividade']);

// Pasta onde o arquivo vai ser salvo
$_UP['pasta'] = '..'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'protocoloAnexo'.DIRECTORY_SEPARATOR.$cdAtividade.DIRECTORY_SEPARATOR;

// Tamanho máximo do arquivo (em Bytes)
$_UP['tamanho'] = 1024 * 1024 * 100; // 100Mb

// Array com as extensões permitidas
$_UP['extensoes'] = array('jpg', 'jpeg', 'png', 'PNG', 'gif', 'pdf','doc','docx');

// Renomeia o arquivo? (Se true, o arquivo será salvo como .jpg e um nome único)
$_UP['renomeia'] = false;

// Array com os tipos de erros de upload do PHP
$_UP['erros'][0] = 'Não houve erro';
$_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
$_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
$_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
$_UP['erros'][4] = 'Não foi feito o upload do arquivo';
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>EcoSis | Calango - Anexar Comprovante</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
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
    <meta name="msapplication-TileImage" content="../lib/media/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css" />
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css" />

	<!-- Fontawesome -->
    <link rel="stylesheet" type="text/css" href="../lib/plugins/font-awesome-5/css/fontawesome-all.min.css" />

    <!-- Bootstrap Core Css -->
    <link href="../lib/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
	<!-- Animation Css -->
    <link href="../lib/plugins/animate-css/animate.css" rel="stylesheet"/>
	<!-- SweetAlert -->
    <link href="../lib/plugins/sweetalert/sweetalert.css" rel="stylesheet">
	<!-- Custom Css -->
    <link href="../lib/css/style.css" rel="stylesheet">

    <!-- Material Colors -->
    <link href="../lib/css/material-colors/material-design-color-palette.min.css" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="../lib/css/themes/all-themes.css" rel="stylesheet"/>
</head>
<body>
	<?php

	// Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
	if ($_FILES['file']['error'] != 0) {

		die("Não foi possível fazer o upload, erro:<br/>" . $_UP['erros'][$_FILES['file']['error']]);
		exit;
		// Para a execução do script
	}
	// Caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar

	// Faz a verificação da extensão do arquivo
	@$extensao = strtolower(end(explode('.', $_FILES['file']['name'])));

	if (array_search($extensao, $_UP['extensoes']) === false) {
		echo "Por favor, envie arquivos com as seguintes extensões: jpg, png ou gif. Extensão escolhida:".$extensao;
	}

	// Faz a verificação do tamanho do arquivo
	else if ($_UP['tamanho'] < $_FILES['file']['size']) {
		echo "O arquivo enviado é muito grande, envie arquivos de até 2Mb.";
	}

	// O arquivo passou em todas as verificações, hora de tentar movê-lo para a pasta
	else {
		// Primeiro verifica se deve trocar o nome do arquivo
		if ($_UP['renomeia'] == true) {
			// Cria um nome baseado no UNIX TIMESTAMP atual e com extensão .jpg
			$nome_final = time().'.jpg';
		} else {
			$nome_final = $_FILES['file']['name'];

			function tirarAcentos($string){

				return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);

			}

			$nome_final =  tirarAcentos($nome_final);
			$nome_final =  str_replace(' ', '_', $nome_final);

			// Mantém o nome original do arquivo
			//$nome_final2 = preg_replace( '/[`^~\'"]/ç', null, iconv( 'UTF-8', 'ASCII//TRANSLIT', $nome_final ) );
		}

		
		$servico = cServico::getServicoByAtividade($cdAtividade);
		$folder  = trim($servico->nm_cliente)."/".trim($servico->nm_empreendimento)."/Proposta - $servico->nr_protocolo.$servico->competencia";

		$dropBoxUpload = $dropbox->upload($_FILES['file']['tmp_name'], $_UP['pasta']);

		// Depois verifica se é possível mover o arquivo para a pasta escolhida
		if (move_uploaded_file($_FILES['file']['tmp_name'], $_UP['pasta'] . $nome_final)) {
			// Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
			$sql = "UPDATE eco_atividade SET sn_anexo = 'S', cd_usuario_alteracao = :cdUsuario, dh_alteracao = now() WHERE cd_atividade = :cdAtividade";
			$stmt = $mysql->prepare($sql);
			$stmt->bindParam(":cdAtividade", $cdAtividade);
			$stmt->bindParam(":cdUsuario", $cdUsuario);
			$result = $stmt->execute();
			if ($result) {
				$num = $stmt->rowCount();
				if($num > 0){

					echo '
					<script>
					swal({
						title: "Sucesso!",
						text: "Comprovante anexado com sucesso!",
						type: "success",
						closeOnConfirm: true
					},
					function(isConfirm){
						if (isConfirm) {
							window.close();
						}
					});
					</script>
					';

				}else{
					echo '
					<script>
					swal({
						title: "Atenção!",
						text: "Não foi possível atualizar os dados da operação, porem o comprovante foi anexado.",
						type: "warning",
						closeOnConfirm: true
					},
					function(isConfirm){
						if (isConfirm) {
							window.close();
						}
					});
					</script>
					';
				}
			} else {
				$error 	 = $stmt->errorInfo();
				$dsError = str_replace("'", "", str_replace('"', '', $error[2]));

				echo '
				<script>
				swal({
					title: "Erro!",
					text: "Não foi possível realizar o anexo do comprovante. Erro: '.$dsError.'",
					type: "error",
					closeOnConfirm: true
				},
				function(isConfirm){
					if (isConfirm) {
						window.close();
					}
				});
				</script>
				';
			}
		}else{
			// Não foi possível fazer o upload, provavelmente a pasta está incorreta
			echo '
			<script>
			swal({
				title: "Erro!",
				text: "Não foi possível realizar o anexo do comprovante.",
				type: "error",
				closeOnConfirm: true
			},
			function(isConfirm){
				if (isConfirm) {
					window.close();
				}
			});
			</script>
			';
		}
	}
	?>
</body>
</html>