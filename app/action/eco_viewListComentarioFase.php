<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdItLicencaFase = isset($_POST['cdItLicencaFase']) ? $_POST['cdItLicencaFase'] : $_GET['cdItLicencaFase'];

if(!empty($cdItLicencaFase)){

	$quest = new cLicencaAmbiental;
	$quest->listComentarioFaseObjeto($cdItLicencaFase);

}else{
	echo "Paramentro incorreto.";
}
?>