<?php
include_once('../conf/interfaceBase.php');
/**
 * Tratamento
 */
class cTratamento extends mTratamento
{

	function __construct($cdTratamento=null, $dsTratamento=null, $snAtivo=null)
	{
		parent::__construct($cdTratamento, $dsTratamento, $snAtivo);
	}

	public function returnCodigo($cdTratamento=""){

        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT cd_tratamento_afluente FROM eco_tratamento_afluente WHERE ds_tratamento_afluente = UPPER(:dsTratamento) ";
        $sql .= (!empty($cdTratamento)) ? " AND cd_tratamento_afluente NOT IN ($cdTratamento)" : "";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":dsTratamento", $this->dsTratamento);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                $reg = $stmt->fetch(PDO::FETCH_OBJ);

                return $reg->cd_tratamento_afluente;
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

	//Realiza o cadastro
	public function Cadastrar()
	{

		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$sql = "INSERT INTO `eco_tratamento_afluente` (`ds_tratamento_afluente`, `cd_empresa`, `cd_usuario_registro`) VALUES (UPPER(:dsTratamento), :cdEmpresa, :cdUsuarioSessao);";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":dsTratamento", $this->dsTratamento);
		$stmt->bindParam(":cdEmpresa", $cdEmpresa);
		$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		$result = $stmt->execute();
		if ($result) {

			$num = $stmt->rowCount();
			if($num > 0){
				return intval($mysql->lastInsertId());
			}else{
				return false;
			}

		}else{
			$erro = $stmt->errorInfo();
			return $erro[2];
		}

	}
	//Altera o cadastro
	public function Alterar()
	{

		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$sql = "UPDATE `eco_tratamento_afluente` SET `ds_tratamento_afluente` = :dsTratamento, `sn_ativo` = :snAtivo WHERE cd_tratamento_afluente = :cdTratamento";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdTratamento", $this->cdTratamento);
		$stmt->bindParam(":dsTratamento", $this->dsTratamento);
		$stmt->bindParam(":snAtivo", $this->snAtivo);
		$result = $stmt->execute();
		if ($result) {

			$num = $stmt->rowCount();

			return intval($num);

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
	//Lista os dados em formato de tabela
	public function ListarTable()
	{

		$mysql = MysqlConexao::getInstance();

		$cdEmpresa = $_SESSION['cdEmpresa'];

		$sql = "SELECT cd_tratamento_afluente, ds_tratamento_afluente, CASE sn_ativo WHEN 'S' THEN 'ATIVO' ELSE 'INATIVO' END AS ds_status, sn_ativo FROM eco_tratamento_afluente ORDER BY ds_tratamento_afluente";
		$stmt = $mysql->prepare($sql);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
					echo '
					<tr>
					<td>'.$reg->cd_tratamento_afluente.'</td>
					<td><a data-toggle="modal" href="#modalFormAlterTratamento" onclick="preencheFormAlterTratamento('.$reg->cd_tratamento_afluente.',\''.$reg->ds_tratamento_afluente.'\',\''.$reg->sn_ativo.'\')">'.$reg->ds_tratamento_afluente.'</a></td>
					<td>'.$reg->ds_status.'</td>
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
	//Lista os dados em formato de select > option
	public static function ListarOption()
	{

		$mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "SELECT cd_tratamento_afluente, ds_tratamento_afluente FROM eco_tratamento_afluente WHERE sn_ativo = 'S' AND cd_empresa = :cdEmpresa ORDER BY ds_tratamento_afluente";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpresa", $cdEmpresa);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
                    echo '
                    <option value="'.base64_encode($reg->cd_tratamento_afluente).'">'.$reg->ds_tratamento_afluente.'</option>
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
	//Construtor genérico
	public function Dados()
	{

	}
}
?>