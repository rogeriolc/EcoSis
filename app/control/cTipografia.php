<?php
include_once('../conf/interfaceBase.php');
/**
 * tipografia
 */
class cTipografia extends mTipografia
{

	function __construct($cdTipografia=null, $dsTipografia=null, $snAtivo=null)
	{
		parent::__construct($cdTipografia, $dsTipografia, $snAtivo);
	}

	public function returnCodigo($cdTipografia=""){

        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT cd_tipografia FROM g_tipografia WHERE ds_tipografia = UPPER(:dsTipografia) ";
        $sql .= (!empty($cdTipografia)) ? " AND cd_tipografia NOT IN ($cdTipografia)" : "";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":dsTipografia", $this->dsTipografia);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                $reg = $stmt->fetch(PDO::FETCH_OBJ);

                return $reg->cd_tipografia;
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

		$sql = "INSERT INTO `g_tipografia`(`ds_tipografia`, `cd_empresa`, `cd_usuario_registro`) VALUES (UPPER(:dsTipografia), :cdEmpresa, :cdUsuarioSessao);";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":dsTipografia", $this->dsTipografia);
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

		$sql = "UPDATE `g_tipografia` SET `ds_tipografia` = :dsTipografia, `sn_ativo` = :snAtivo WHERE cd_tipografia = :cdTipografia";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdTipografia", $this->cdTipografia);
		$stmt->bindParam(":dsTipografia", $this->dsTipografia);
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

		$sql = "SELECT cd_tipografia, ds_tipografia, CASE sn_ativo WHEN 'S' THEN 'ATIVO' ELSE 'INATIVO' END AS ds_status, sn_ativo FROM g_tipografia ORDER BY ds_tipografia";
		$stmt = $mysql->prepare($sql);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
					echo '
					<tr>
					<td>'.$reg->cd_tipografia.'</td>
					<td><a data-toggle="modal" href="#modalFormAlterTipografia" onclick="preencheFormAlterTipografia('.$reg->cd_tipografia.',\''.$reg->ds_tipografia.'\',\''.$reg->sn_ativo.'\')">'.$reg->ds_tipografia.'</a></td>
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

        $sql = "SELECT cd_tipografia, ds_tipografia FROM g_tipografia WHERE sn_ativo = 'S' AND cd_empresa = :cdEmpresa ORDER BY ds_tipografia";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpresa", $cdEmpresa);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
                    echo '
                    <option value="'.base64_encode($reg->cd_tipografia).'">'.$reg->ds_tipografia.'</option>
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