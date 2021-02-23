<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdDocAssessoria = $_POST['cdDocAssessoria'];

$removido = cAtividade::removerProdutoAssessoria($cdDocAssessoria);