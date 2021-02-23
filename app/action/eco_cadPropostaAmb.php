<?php
session_start();

include '../conf/autoload.php';

cSeguranca::validaSessao();

$lic = new cLicencaAmb;
echo $cdLicencaAmbiental = $lic->iniciar();

?>