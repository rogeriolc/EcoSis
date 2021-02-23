<?php
session_start();

// include '../conf/showErros.php';
include '../conf/autoLoad.php';

cSeguranca::validaSessao();

//19 - Permissao para excluir anexos
$cdPermissao 			= 19;
$autorizado 			= cPermissao::validarPermissao($cdPermissao);

if (!$autorizado) {
	exit();
}

$ds 			= DIRECTORY_SEPARATOR;

// include '..'.$ds.'conf'.$ds.'autoLoad.php';

$cdUsuario		= $_SESSION['cdUsuario'];
$nmUsuario 		= $_SESSION['nmUsuario'];

$dsDir 			= '..'.$ds.base64_decode($_POST['dsDir']);
$cdItAtividade 	= $_POST['cdItAtividade'];

$path       	= "..".$ds."repo".$ds."eco".$ds."protocoloAnexo".$ds.$cdItAtividade.$ds;

if(!unlink($dsDir)){

	echo 'Ocorreu um problema ao excluir o anexo.';
	echo '
	<script>
	swal("Erro!", "Ocorreu um problema ao excluir o anexo.", "error");
	</script>
	';

}else{

	echo '
	<script>
	swal("Sucesso!", "Anexo excluido!", "success");
	</script>
	';

	$atv = new cAtividade();
	$atv->setCdItAtividade($cdItAtividade);
	$atv->ListarAnexos();
}