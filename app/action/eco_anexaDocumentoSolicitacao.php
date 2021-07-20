<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$dropbox 	= new cDropbox();

$cdSolDoc 	= isset($_POST['cdSolDoc']) ? $_POST['cdSolDoc'] : null;
$cdServico 	= isset($_POST['cdServico']) ? base64_decode($_POST['cdServico']) : null;
$anexo 		= $_FILES['fileAnexo'];

$notificacao = new Notificacao;

if (is_null($cdSolDoc) || is_null($cdServico)) {
	$notificacao->viewSwalNotificacao("Erro!", "Parâmetros incorretos.", "single", "error");
	exit();
}
if (!is_file($anexo['tmp_name'])) {
	$notificacao->viewSwalNotificacao("Essa não!", "Anexo inválido.", "single", "error");
	exit();
}

// Pasta onde o arquivo vai ser salvo
$_UP['pasta'] = '..'.DIRECTORY_SEPARATOR.'repo'.DIRECTORY_SEPARATOR.'eco'.DIRECTORY_SEPARATOR.'servDoc'.DIRECTORY_SEPARATOR.$cdServico.DIRECTORY_SEPARATOR.$cdSolDoc;

// Tamanho máximo do arquivo (em Bytes)
$_UP['tamanho'] = 1024 * 1024 * 20; // 2Mb

// Array com as extensões permitidas
$_UP['extensoes'] = array('jpg', 'jpeg', 'png', 'gif', 'pdf','doc','docx');

// Renomeia o arquivo? (Se true, o arquivo será salvo como .jpg e um nome único)
$_UP['renomeia'] = false;

// Array com os tipos de erros de upload do PHP
$_UP['erros'][0] = 'Não houve erro';
$_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
$_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
$_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
$_UP['erros'][4] = 'Não foi feito o upload do arquivo';


// Faz a verificação da extensão do arquivo
@$extensao = strtolower(end(explode('.', $anexo['name'])));

if (array_search($extensao, $_UP['extensoes']) === false) {
	echo $extensao;
	$notificacao->viewSwalNotificacao("Hmm... o arquivo", "Por favor, envie arquivos com as seguintes extensões: JPG, PNG, GIF, PDF, DOC ou DOCX", "single", "error");
	exit();
}

if ($_UP['tamanho'] < $anexo['size']) {

	$notificacao->viewSwalNotificacao("Hmm... o arquivo", "O arquivo enviado é muito grande, envie arquivos de até 2Mb.", "single", "error");
	exit();

}

if ($_UP['renomeia'] == true) {

	// Cria um nome baseado no UNIX TIMESTAMP atual e com extensão .jpg
	$dsAnexo = time().'.jpg';

} else {

	$servico = cProposta::getByServico($cdServico);
	$folder  = trim($servico->nm_cliente)."/".trim($servico->nm_empreendimento)."/Proposta - $servico->nr_protocolo.$servico->competencia";

	$dropBoxUpload = $dropbox->upload($anexo, $folder);

	if ($dropBoxUpload->id) {
		$snAnexo = cServico::addAnexoSolicitacao($cdSolDoc, $anexo['name'], json_encode($dropBoxUpload));

		switch (gettype($snAnexo)) {
			case 'integer':
			if ($snAnexo > 0) {

				$notificacao->viewSwalNotificacao("Sucesso!", "O arquivo foi anexado com sucesso.", "single", "success");
			} else {

			}
			break;

			default:
			$notificacao->viewSwalNotificacao("Erro", "Não foi possível vincular o anexo a solicitação. Por favor, contate o administrador do sistema.", "single", "error");
			rmdir($_UP['pasta']);
			break;
		}
	} else {
		$notificacao->viewSwalNotificacao("Erro", "Não foi possível enviar o anexo. Por favor, contate o administrador do sistema.", "single", "error");
	}
}
?>