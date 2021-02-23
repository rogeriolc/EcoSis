<?php

/**
 * cTpAtividade
 */
class cTpAtividade extends mTpAtividade
{

	function __construct($cdTpAtividade=null, $dsTpAtividade=null, $snAtivo=null)
	{
		parent::__construct($cdTpAtividade,$dsTpAtividade,$snAtivo);
	}

	public function returnCodigo($cdTpAtividade=""){

		$mysql = MysqlConexao::getInstance();

		$sql = "SELECT cd_tp_atividade FROM eco_tp_atividade WHERE ds_tp_atividade = UPPER(:dsTpAtividade) ";
		$sql .= (!empty($cdTpAtividade)) ? " AND cd_tp_atividade NOT IN ($cdTpAtividade)" : "";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":dsTpAtividade", $this->dsTpAtividade);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				$reg = $stmt->fetch(PDO::FETCH_OBJ);

				return $reg->cd_tp_atividade;
			}else{
				return 0;
			}

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			return 'E';
		}
	}

	public function listTable(){
		$mysql = MysqlConexao::getInstance();

		$cdEmpresa = $_SESSION['cdEmpresa'];

		$sql = "SELECT tp.cd_tp_atividade, tp.ds_tp_atividade, cat.cd_cat_tp_atividade, cat.ds_cat_tp_atividade, CASE tp.sn_ativo WHEN 'S' THEN '<span class=\"col-green\">ATIVO</span>' ELSE '<span class=\"col-red\">INATIVO</span>' END AS ds_status, tp.sn_ativo FROM eco_tp_atividade tp, eco_cat_tp_atividade cat WHERE tp.cd_cat_tp_atividade = cat.cd_cat_tp_atividade ORDER BY ds_tp_atividade";
		$stmt = $mysql->prepare($sql);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
					echo '
					<tr data-toggle="modal" href="#modalFormAlterTpAtividade" onclick="preencheFormAlterTpAtividade('.$reg->cd_tp_atividade.',\''.$reg->ds_tp_atividade.'\',\''.base64_encode($reg->cd_cat_tp_atividade).'\',\''.$reg->sn_ativo.'\')">
					<td>'.$reg->cd_tp_atividade.'</td>
					<td><a href="javascrip:void(0)">'.$reg->ds_tp_atividade.'</a></td>
					<td>'.$reg->ds_cat_tp_atividade.'</td>
					<td class="text-center">'.$reg->ds_status.'</td>
					</tr>
					';
				}
			}else{
				return 0;
			}

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));
		}
	}

	public function listOption($cdTpAtividade=null){
		$mysql = MysqlConexao::getInstance();

		$cdEmpresa = $_SESSION['cdEmpresa'];

		$sql = "SELECT cd_tp_atividade, ds_tp_atividade FROM eco_tp_atividade WHERE sn_ativo = 'S' ORDER BY ds_tp_atividade";
		$stmt = $mysql->prepare($sql);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				while($reg = $stmt->fetch(PDO::FETCH_OBJ)){

					$selected = ($cdTpAtividade == $reg->cd_tp_atividade) ? 'selected' : '';

					echo '<option value="'.base64_encode($reg->cd_tp_atividade).'" '.$selected.'>'.$reg->ds_tp_atividade.'</option>';
				}
			}else{
				return 0;
			}

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));
		}
	}

	public function Dados(){

		$mysql = MysqlConexao::getInstance();

		$sql = "SELECT ds_tp_atividade, cd_cat_tp_atividade FROM eco_tp_atividade WHERE cd_tp_atividade = :cdTpAtividade";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdTpAtividade", $this->cdTpAtividade);
		$result = $stmt->execute();
		if ($result) {
			$reg = $stmt->fetch(PDO::FETCH_OBJ);

			$this->dsTpAtividade = $reg->ds_tp_atividade;
			$this->cdCatTpAtividade = $reg->cd_cat_tp_atividade;
		}else{
			echo var_dump($stmt->errorInfo());
		}
	}

	public function Cadastro(){
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$sql = "INSERT INTO eco_tp_atividade (ds_tp_atividade, cd_cat_tp_atividade, cd_usuario_registro) VALUES (UPPER(:dsTpAtividade), :cdCatTpAtividade, :cdUsuarioSessao)";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":dsTpAtividade", $this->dsTpAtividade);
		$stmt->bindParam(":cdCatTpAtividade", $this->cdCatTpAtividade);
		$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				return intval($mysql->lastInsertId());
			}else{
				return 'N';
			}

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			return 'E';
		}
	}

	public function Alterar(){
		$mysql = MysqlConexao::getInstance();

		$sql = "UPDATE eco_tp_atividade SET ds_tp_atividade = UPPER(:dsTpAtividade), cd_cat_tp_atividade = :cdCatTpAtividade, sn_ativo = :snAtivo WHERE cd_tp_atividade = :cdTpAtividade";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdTpAtividade", $this->cdTpAtividade);
		$stmt->bindParam(":dsTpAtividade", $this->dsTpAtividade);
		$stmt->bindParam(":cdCatTpAtividade", $this->cdCatTpAtividade);
		$stmt->bindParam(":snAtivo", $this->snAtivo);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				$reg = $stmt->fetch(PDO::FETCH_OBJ);
				return 'S';
			}else{
				return 'N';
			}

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public function getAll(){

        //LEMBRAR DE COLOCAR MULTI EMPRESA

        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT cd_tp_atividade, ds_tp_atividade, cd_cat_tp_atividade FROM eco_tp_atividade ORDER BY ds_tp_atividade ASC";
        $stmt = $mysql->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            return $reg = $stmt->fetchAll(PDO::FETCH_OBJ);
        }
    }
}

?>