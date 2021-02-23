<?php
header('Content-Type: application/json');
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$data    		= array();
$cdServico 		= isset($_GET['cdServico']) 	 ? $_GET['cdServico']   : null;
$tpAtividade 	= isset($_GET['tpAtividade'])   ? $_GET['tpAtividade'] : null;

if(is_null($cdServico) || is_null($tpAtividade)){
	echo 'asdasd';
	exit();
}

try{

	$conexao = MysqlConexao::getInstance();

	$sql = "
	SELECT
		a.cd_atividade,
		a.ds_atividade,
		a.tp_atividade,
		a.nr_ordem,
		ta.cd_tp_atividade,
		ta.ds_tp_atividade,
		u.nm_usuario,
		date_format(a.dt_prev_entrega,'%d/%m/%Y') as dt_prev_entrega,
		date_format(a.dh_registro,'%d/%m/%Y') as dh_registro,
		CASE a.tp_status
		WHEN 'R' THEN '<span class=\"col-orange\"><i class=\"material-icons font-18\" style=\"margin-top: 5px\">refresh</i> REABERTO</span>'
		WHEN 'O' THEN '<span class=\"col-green\"><i class=\"material-icons font-18\" style=\"margin-top: 5px\">done</i> CONCLUÍDO</span>'
		WHEN 'E' THEN '<span class=\"col-amber\"><i class=\"material-icons font-18\" style=\"margin-top: 5px\">build</i> TRABALHANDO</span>'
		WHEN 'S' THEN '<span class=\"col-red\"><i class=\"material-icons font-18\" style=\"margin-top: 5px\">block</i> SUSPENSO VIA PROPOSTA</span>'
		WHEN 'T' THEN '<span class=\"col-amber\"><i class=\"material-icons font-18\" style=\"margin-top: 5px\">compare_arrows</i> EM TRÂMITE</span>'
		ELSE tp_status
		END AS tp_status,
		(SELECT count(*) FROM eco_it_atividade WHERE cd_atividade = a.cd_atividade AND tp_status NOT IN ('C')) as total_it_atividade
	FROM 	`eco_tp_atividade` ta, `eco_atividade` a LEFT JOIN `g_usuario` u
	ON 		a.cd_usuario 		= u.cd_usuario
	WHERE 	a.cd_tp_atividade 	= ta.cd_tp_atividade
	AND 	a.cd_servico 		= :cdServico
	AND 	a.tp_atividade 		= :tpAtividade
	ORDER BY a.nr_ordem ASC, a.cd_atividade DESC";
	$stmt = $conexao->prepare($sql);
	$stmt->bindParam(":cdServico", $cdServico);
	$stmt->bindParam(":tpAtividade", $tpAtividade);
	$result = $stmt->execute();
	if($result){


		while($reg  = $stmt->fetch(PDO::FETCH_OBJ)){
			$data[] = $reg;
		}

		echo json_encode($data);

	}
}catch(Exception $e){
	echo $e->getMessage();
}

?>