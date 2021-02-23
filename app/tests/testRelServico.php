<?php
include '..'.DIRECTORY_SEPARATOR.'conf'.DIRECTORY_SEPARATOR.'autoLoad.php';

$cdCliente = isset($_GET['cdCliente']) ? $_GET['cdCliente'] : null;

$html = '<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
<title>EcoSis | Calango</title>
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">

<!-- Material Colors -->
<link href="../../lib/css/material-colors/material-design-color-palette.min.css" rel="stylesheet">

<style type="text/css">
	body{
		font-family: "Roboto", sans-serif;
	}

	table th {
		text-align: left !important;
	}

	table.table{
		width: 100%;
		margin-bottom: 20px;
	}

	table.table, table.table tr, table.table td, table.table th{
		padding: 5px;
	}

	table.bordered tr, table.bordered td, table.bordered th{
		border: 1px solid #E9E9E9;
	}

	.bg-deep-purple{
		background-color: #673AB7 !important;
		color: #fff !important;
	}
<style>

</head>
<body style="background-color: none;">';

if (is_null($cdCliente)) {
	$html .= "Parametros incorretos";
	exit();
}

$mysql = MysqlConexao::getInstance();

$dataHead  = array();
$dataBody  = array();

$sql ="
SELECT 	c.nm_cliente,
o.nm_orgao_licenciado,
e.cd_empreendimento,
e.nm_empreendimento,
s.cd_servico,
s.nr_processo,
tp.ds_tp_atividade,
it.cd_it_atividade,
it.dt_protocolo,
(SELECT nm_usuario FROM g_usuario WHERE cd_usuario = it.cd_responsavel) as nm_usuario_resp,
(SELECT nm_orgao_licenciado FROM g_orgao_licenciado WHERE cd_orgao_licenciado = it.cd_orgao_licenciador) as nm_orgao_licenciador_resp,
(SELECT nm_cliente FROM g_cliente WHERE cd_cliente = it.cd_orgao_licenciador) as nm_cliente_resp,
it.ds_andamento,
CASE it.tp_status
WHEN 'O' THEN 'CONCLUÍDO'
WHEN 'E' THEN 'EM ANDAMENTO'
WHEN 'R' THEN 'REABERTO'
WHEN 'A' THEN 'ATRASADO'
WHEN 'C' THEN 'CANCELADO'
ELSE ''
END as tp_status,
a.cd_atividade

FROM 	eco_it_atividade it,
eco_atividade a,
eco_servico s,
g_orgao_licenciado o,
g_cliente c,
eco_tp_atividade tp,
g_empreendimento e

WHERE 	it.cd_atividade 	= a.cd_atividade
AND		a.cd_servico		= s.cd_servico
AND		a.cd_tp_atividade 	= tp.cd_tp_atividade
AND		s.cd_cliente		= c.cd_cliente
AND		s.cd_orgao_licenciado = o.cd_orgao_licenciado
AND		s.cd_empreendimento	= e.cd_empreendimento
AND		s.cd_cliente		= :cdCliente
ORDER BY it.cd_it_atividade DESC
;
";
$stmt = $mysql->prepare($sql);
$stmt->bindParam(":cdCliente", $cdCliente);
$result = $stmt->execute();
if ($result) {
	$num = $stmt->rowCount();
	if($num > 0){

		$a = 0;

		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

		foreach ($data as $key => $value) {

			$cdEmpre = $data[$key]['cd_empreendimento'];
			$nmEmpre = $data[$key]['nm_empreendimento'];

			$dataHead[$cdEmpre]['nm_empreendimento'] 	= $nmEmpre;
			$dataHead[$cdEmpre]['nm_cliente'] 			= $data[$key]['nm_cliente'];
			$dataHead[$cdEmpre]['nm_orgao_licenciado'] 	= $data[$key]['nm_orgao_licenciado'];
			$dataHead[$cdEmpre]['nr_processo'] 			= $data[$key]['nr_processo'];
		}

		foreach ($data as $key => $value) {

			$cdEmpre = $data[$key]['cd_empreendimento'];
			$cdAtv = $data[$key]['cd_atividade'];

			$dataHead[$cdEmpre]['atividades'][$cdAtv] = array(
				'ds_tp_atividade' 	=> $data[$key]['ds_tp_atividade'],
				'cd_it_atividade' 	=> $data[$key]['cd_it_atividade']
			);

		}

		foreach ($data as $key => $value) {

			$cdEmpre = $data[$key]['cd_empreendimento'];
			$cdAtv = $data[$key]['cd_atividade'];

			$dataHead[$cdEmpre]['atividades'][$cdAtv]['andamentos'][] = array(
				'dt_protocolo' 					=> $data[$key]['dt_protocolo'],
				'nm_usuario_resp' 				=> $data[$key]['nm_usuario_resp'],
				'nm_orgao_licenciador_resp' 	=> $data[$key]['nm_orgao_licenciador_resp'],
				'nm_cliente_resp' 				=> $data[$key]['nm_cliente_resp'],
				'ds_andamento' 					=> $data[$key]['ds_andamento'],
				'tp_status' 					=> $data[$key]['tp_status']
			);
		}

			// $html .= json_encode($dataHead);


	}else{
			//$html .= $num;
	}
}else{
	var_dump($stmt->errorInfo());
}

foreach ($dataHead as $key => $value) {
	//cabeçalho
	$html .= '<table class="table bordered" cellspacing="0" cellspadding="0">';
	$html .= '<thead>';
	$html .= '<tr><td colspan="2" class="bg-deep-purple" align="center"><h2>Relatório de Andamentos do Serviço</h2></td></tr>';
	$html .= '<tr><th width="160px">Empreedimento: </th><td>'.$value['nm_empreendimento'].'</td></tr>';
	$html .= '<tr><th width="160px">Cliente: </th><td>'.$value['nm_cliente'].'</td></tr>';
	$html .= '<tr><th width="160px">Orgão Licenciador: </th><td>'.$value['nm_orgao_licenciado'].'</td></tr>';
	$html .= '<tr><th width="160px">Processo: </th><td>'.$value['nr_processo'].'</td></tr>';
	$html .= '</thead>';
	$html .= '</table>';

	$atividades = $value['atividades'];

	foreach ($atividades as $akey => $avalue) {
		$html .= '<table class="table bordered" cellspacing="0" cellspadding="0">';
		$html .= '<thead>';
		$html .= '<tr><th colspan="2" style="font-size: 18px;" class="bg-deep-purple">'.$avalue['ds_tp_atividade'].'</th></tr>';
		$html .= '</thead>';
		$html .= '<tbody>';

		$andamentos = $avalue['andamentos'];

		foreach ($andamentos as $adkey => $advalue) {
			$html .= '<tr style="border: none;">';
			$html .= '<td colspan="2" style="padding: 20px; border: none;"></td>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<td align="center" colspan="2">'.$advalue['tp_status'].'</td>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<th width="160px">Data de Protocolo:</th>';
			$html .= '<td>'.date("d/m/Y", strtotime($advalue['dt_protocolo'])).'</td>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<th width="160px">Responsável:</th>';

			if(!is_null($advalue['nm_usuario_resp'])){
				$html .= '<td>'.$advalue['nm_usuario_resp'].'</td>';
			}elseif (!is_null($advalue['nm_orgao_licenciador_resp'])) {
				$html .= '<td>'.$advalue['nm_orgao_licenciador_resp'].'</td>';
			}else{
				$html .= '<td>'.$advalue['nm_cliente_resp'].'</td>';
			}

			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<th colspan="2">Descrição do Andamento:</th>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<td colspan="2" align="justify">'.$advalue['ds_andamento'].'</td>';
			$html .= '</tr>';
		}
		$html .= '</tbody>';
		$html .= '</table>';
	}

}

$html .= '
</body>
</html>';

require_once("../../lib/plugins/dompdf/dompdf_config.inc.php");

/* Cria a instância */
$dompdf = new DOMPDF();

/* Carrega seu HTML */
$dompdf->load_html($html);

/* Renderiza */
$dompdf->render();

/* Exibe */
$dompdf->stream(
	"saida.pdf", /* Nome do arquivo de saída */
	array(
		"Attachment" => false /* Para download, altere para true */
	)
);

?>