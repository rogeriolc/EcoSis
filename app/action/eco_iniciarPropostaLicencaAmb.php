<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$propLicenca = new cPropostaLicencaAmb();

echo $propLicenca->iniciarProposta();
?>