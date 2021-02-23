<?php
include '../conf/autoLoad.php';

session_start();

header('Content-Type: application/json');

$tiposAtividade = new cTpAtividade;

echo json_encode($tiposAtividade->getAll());