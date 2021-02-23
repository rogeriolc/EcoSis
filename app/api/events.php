<?php

header("Content-Type: application/json");

$arrayName = array(
	0 => array(
		"id" => "1",
		"title" => "Nome do Cliente\nNome do Empreendimento",
		"start" => "2019-01-14",
		"end" => "2019-01-20",
	),
	1 => array(
		"id" => "1",
		"title" => "Nome do Cliente 2\nNome do Empreendimento 2",
		"start" => "2019-01-14",
		"end" => "2019-01-20",
	),
	2 => array(
		"id" => "2",
		"title" => "Teste 2",
		"start" => "2019-01-25",
		"end" => "2019-01-30",
	)
);

echo json_encode($arrayName);

?>