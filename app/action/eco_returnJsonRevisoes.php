<?php
header('Content-Type: application/json');
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$data    				= array();
$cdEmpreendimento 		= isset($_GET['cdEmpreendimento']) 	 	? $_GET['cdEmpreendimento']   : null;
$cdRevEmpreendimento 	= isset($_GET['cdRevEmpreendimento']) 	? $_GET['cdRevEmpreendimento']   : null;

if(is_null($cdEmpreendimento) && is_null($cdRevEmpreendimento)){
	echo 'asdasd';
	exit();
}

try{

	$revs = cEmpreendimento::arrayRevisao($cdEmpreendimento, $cdRevEmpreendimento);


	foreach ($revs as $key => $value) {
		$value->abastecimento = cEmpreendimento::arrayAbastecimentoRevisao($value->cd_rev_empreendimento);
		$value->tratamento = cEmpreendimento::arrayTratamentoRevisao($value->cd_rev_empreendimento);
	}

	echo json_encode($revs);

}catch(Exception $e){
	echo $e->getMessage();
}

?>