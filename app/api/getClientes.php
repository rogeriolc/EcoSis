<?php
include '../conf/autoLoad.php';

header('Content-Type: application/json');

$cliente = new cCliente;

echo json_encode($cliente->getAll());