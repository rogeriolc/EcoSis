<?php
include '../conf/autoLoad.php';

session_start();

header('Content-Type: application/json');

$cdCliente = isset($_GET['cdCliente']) ? $_GET['cdCliente'] : null;

$empreendimento = new cEmpreendimento;

echo json_encode($empreendimento->getByCliente($cdCliente));