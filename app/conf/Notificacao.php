<?php

class Notificacao{

	function viewNotificacao($ds_icon, $ds_mensagem, $tp_notificacao, $nr_delay){
		echo '<script>$.notify({icon: \''.$ds_icon.'\', message: \''.$ds_mensagem.'\'},{newest_on_top: true, type: \''.$tp_notificacao.'\',delay: \''.$nr_delay.'\', animate: {enter: \'animated bounceInDown\', exit: \'animated bounceOutUp\'}});</script>';
	}

	function viewSwalNotificacao($ds_titulo, $ds_mensagem, $tp_alerta, $tp_notificacao, $nr_delay=""){
		switch ($tp_alerta) {

			case 'single':
			echo '
			<script>
				swal("'.$ds_titulo.'", "'.$ds_mensagem.'", "'.$tp_notificacao.'");
			</script>
			';
			break;

			case 'timer':
			echo '
			<script>
				swal({
					title: "'.$ds_titulo.'",
					text: "'.$ds_mensagem.'",
					icon: "'.$tp_notificacao.'",
					timer: '.$nr_delay.',
					showConfirmButton: false
				});
			</script>
			';
			break;

			case 'confirm':
			echo '
			<script>
				swal({
					title: "'.$ds_titulo.'",
					text: "'.$ds_mensagem.'",
					type: "'.$tp_notificacao.'",
					showCancelButton: true,
					confirmButtonColor: "#4CAF50",
					confirmButtonText: "Sim, desejo",
					cancelButtonText: "N�o, depois",
					closeOnConfirm: false,
					closeOnCancel: false
				},
				function(isConfirm){
					if (isConfirm) {
						swal("Deleted!", "Opera��o Realizada com Sucesso!", "success");
					} else {
						swal("Cancelled", "Opera��o Cancelada!", "error");
					}
				});
			</script>
			';
			break;

			default:
			echo '
			<script>
				swal({
					title: "Erro!",
					text: "Parametros incorretos para o alerta",
					type: "error"
				});
			</script>
			';
			break;
		}
	}

	public static function enviaEmail($dsEmailRemetente="ecosis@calango.eng.br", $nmRemetente="EcoSis", $dsEmailDestino, $nmColaboradorDestino, $dsAssunto, $dsTitulo, $dsCorpoMensagem, $dsMensagemFinal, $anexo=null, $dsAssinatura=null, $user=null,$pass=null){

		// var_dump($anexo);

		// Inclui o arquivo class.phpmailer.php localizado na pasta phpmailer
		require_once("../../lib/plugins/phpmailer/class.phpmailer.php");

		// Inicia a classe PHPMailer
		$mail = new PHPMailer();

		// Define os dados do servidor e tipo de conex�o
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		$mail->IsSMTP(); // Define que a mensagem ser� SMTP
		$mail->Host = "smtp.calango.eng.br"; // Endere�o do servidor SMTP

		// if(!is_null($user) && !is_null($pass)){

		$mail->SMTPAuth = true; // Usa autentica��o SMTP? (opcional)
		$mail->Username = 'ecosis@calango.eng.br'; // Usu�rio do servidor SMTP
		$mail->Password = 'Calango@2019'; // Senha do servidor SMTP

		// }

		// Define o remetente
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		$mail->From 	= $dsEmailRemetente; // Seu e-mail
		$mail->FromName = $nmRemetente; // Seu nome

		// Define os destinat�rio(s)
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		$mail->AddAddress($dsEmailDestino, $nmColaboradorDestino);
		//$mail->AddAddress('ciclano@site.net');
		//$mail->AddCC('ciclano@site.net', 'Ciclano'); // Copia
		//$mail->AddBCC('fulano@dominio.com.br', 'Fulano da Silva'); // C�pia Oculta

		// Define os dados t�cnicos da Mensagem
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		$mail->IsHTML(true); // Define que o e-mail ser� enviado como HTML
		$mail->CharSet = 'ISO-8859-1'; // Charset da mensagem (opcional)


		$html = "";
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<title>EcoSis | Calango</title>
		<link href="https://fonts.googleapis.com/css?family=Hammersmith+One" rel="stylesheet">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

		<!-- Fontawesome -->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">

		<!-- Bootstrap Core Css -->
		<link href="http://ecosis.boeckmann.com.br/lib/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
		<!-- Custom Css -->
		<link href="http://ecosis.boeckmann.com.br/lib/css/style.css" rel="stylesheet">

		<!-- Material Colors -->
		<link href="http://ecosis.boeckmann.com.br/lib/css/material-colors/material-design-color-palette.min.css" rel="stylesheet">
		</head>
		<body>
		<table width="100%" style="border: 1px solid #e9e9e9">
		<thead>
		<tr>
		<th bgcolor="#dfe6e9">
		<!--<h2 style="color: #fff;" align="center"><i class="fab fa-envira fa-lg col-green"></i> <b>Eco</b>Sis</h2>-->
		<h2 style="color: #fff;" align="center"><i class="fab fa-envira fa-lg col-green"></i><img src="http://calango.eng.br/wp-content/uploads/2016/08/MARCACALANGO.png"></h2>
		</th>
		</tr>
		<tr>
		<th bgcolor="#673AB7" style="color:#FFF" align="center"><h2 align="center">'.utf8_decode($dsAssunto).'</h2></th>
		</tr>
		</thead>
		<tbody>
		<tr>
		<td style="padding: 30px">';
		//motivo da mensagem
		$html .= utf8_decode($dsCorpoMensagem);
		$html .= utf8_decode('
		</td>
		</tr>
		<tr>
		<td style="padding: 30px;">
		<br><br>
		<p><strong>Atenção:</strong> Esta é uma mensagem automática, por favor, não responda.</p>
		</td>
		</tr>
		</tbody>');
		$html .= '<tfoot class="header mdc-bg-grey-700" style="color: white;">
		<tr>
		<td bgcolor="#616161" align="center">';
		$html .= utf8_decode($dsMensagemFinal);
		$html .= '</td>
		</tr>
		</tfoot>
		</table>
		</body>
		</html>';


		// Define a mensagem (Texto e Assunto)
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		$mail->Subject  = utf8_decode($dsAssunto); // Assunto da mensagem
		$mail->Body 	= $html;
		$mail->AltBody 	= utf8_decode($dsAssunto)." \r\n";
		$mail->IsHTML(true);

		// Define os anexos (opcional)
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo
		if(!is_null($anexo)){

			$mail->AddAttachment($anexo['tmp_name'], $anexo['name']);  // Insere um anexo

		}

		// Envia o e-mail
		$enviado = $mail->Send();

		// Limpa os destinat�rios e os anexos
		$mail->ClearAllRecipients();
		$mail->ClearAttachments();

		// Exibe uma mensagem de resultado
		if ($enviado) {
			// echo "E-mail enviado com sucesso!";
			return true;
		} else {
			echo $mail->ErrorInfo;
			return false;
			// echo "N�o foi poss�vel enviar o e-mail.";
			// echo "<b>Informa��es do erro:</b> " . $mail->ErrorInfo;
		}

	}
}

?>