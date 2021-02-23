<?php

include '../conf/autoLoad.php';
include '../conf/showErros.php';

$dsEmail = isset($_POST['dsEmail']) ? $_POST['dsEmail'] : null;

if(is_null($dsEmail)){
	header("Location: ../../recuperacao-de-senha/?e=".base64_encode("Você precisa digitar um e-mail para iniciar a recuperação de senha!"));
	exit();
}

$cdUsuario = cUsuario::validaEmail($dsEmail);

if ($cdUsuario > 0) {

	$token 		= cSeguranca::geraToken();
	$usuario 	= new cUsuario($cdUsuario);
	$usuario->Dados();

	$snUpdToken = $usuario->updateTokenSenha($token);

	if(!is_null($token) && $snUpdToken) {

		$dsTitulo 		 		= NULL;
		$dsAssunto 		 		= "Recuperação de Senha";
		$dsCorpoMensagem 		= "<h4 align='center'>Esta é a mensagem de recuperação de senha.</h4><p align='center'>Clique no link abaixo para alterar sua senha:<br><br><br><div align='center'><a href='http://calango.eng.br/recuperacao-de-senha/?t=".$token."'>Clique aqui para recuperar sua senha!</a></div></p>";
		$nmColaboradorDestino  	= $usuario->getNmPessoa();
		$dsEmailDestino  		= $dsEmail;
		$dsMensagemFinal 		= null;

		$enviaEmail = Notificacao::enviaEmail($dsEmailRemetente="ecosis@calango.eng.br", $nmRemetente="EcoSis", $dsEmailDestino, $nmColaboradorDestino, $dsAssunto, $dsTitulo, $dsCorpoMensagem, $dsMensagemFinal, $anexo=null, $dsAssinatura=null, $user=null,$pass=null);

		if ($enviaEmail) {
			header("Location: ../../recuperacao-de-senha/enviada?t=".$token);
		}

	}else {
		echo 'Erro ao gerar token de recuperação!';
		exit();
	}
}else{
	header("Location: ../../recuperacao-de-senha/?e=".base64_encode("Não conseguimos encontrar o seu e-mail. Por favor, verifique-o e tente novamente."));
	exit();
}

?>