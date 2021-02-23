<?php
include_once('../conf/interfaceBase.php');
/**
 * Abastecimento
 */
class cAbastecimento extends mAbastecimento
{

	function __construct($cdAbastecimento=null, $dsAbastecimento=null, $snAtivo=null)
	{
		parent::__construct($cdAbastecimento, $dsAbastecimento, $snAtivo);
	}

	public function returnCodigo($cdAbastecimento=""){

        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT cd_abastecimento FROM eco_abastecimento WHERE ds_abastecimento = UPPER(:dsAbastecimento) ";
        $sql .= (!empty($cdAbastecimento)) ? " AND cd_abastecimento NOT IN ($cdAbastecimento)" : "";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":dsAbastecimento", $this->dsAbastecimento);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                $reg = $stmt->fetch(PDO::FETCH_OBJ);

                return $reg->cd_abastecimento;
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

		$sql = "INSERT INTO `eco_abastecimento`(`ds_abastecimento`, `cd_empresa`, `cd_usuario_registro`) VALUES (UPPER(:dsAbastecimento), :cdEmpresa, :cdUsuarioSessao);";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":dsAbastecimento", $this->dsAbastecimento);
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

		$sql = "UPDATE `eco_abastecimento` SET `ds_abastecimento` = :dsAbastecimento, `sn_ativo` = :snAtivo WHERE cd_abastecimento = :cdAbastecimento";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAbastecimento", $this->cdAbastecimento);
		$stmt->bindParam(":dsAbastecimento", $this->dsAbastecimento);
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

		$sql = "SELECT cd_abastecimento, ds_abastecimento, CASE sn_ativo WHEN 'S' THEN 'ATIVO' ELSE 'INATIVO' END AS ds_status, sn_ativo FROM eco_abastecimento ORDER BY ds_abastecimento";
		$stmt = $mysql->prepare($sql);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
					echo '
					<tr>
					<td>'.$reg->cd_abastecimento.'</td>
					<td><a data-toggle="modal" href="#modalFormAlterAbastecimento" onclick="preencheFormAlterAbastecimento('.$reg->cd_abastecimento.',\''.$reg->ds_abastecimento.'\',\''.$reg->sn_ativo.'\')">'.$reg->ds_abastecimento.'</a></td>
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

        $sql = "SELECT cd_abastecimento, ds_abastecimento FROM eco_abastecimento WHERE sn_ativo = 'S' AND cd_empresa = :cdEmpresa ORDER BY ds_abastecimento";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpresa", $cdEmpresa);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
                    echo '
                    <option value="'.base64_encode($reg->cd_abastecimento).'">'.$reg->ds_abastecimento.'</option>
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