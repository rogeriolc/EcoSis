<?php

/**
* cPotencialPoluidor
*/
class cPotencialPoluidor extends mPotencialPoluidor
{

	function __construct($cdPotencialPoluidor=null, $dsPotencialPoluidor=null, $snAtivo=null, $dhRegistro=null)
	{
		parent::__construct($cdPotencialPoluidor, $dsPotencialPoluidor, $snAtivo, $dhRegistro);
	}

	public function returnCodigo($cdPotencialPoluidor=""){

        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT cd_potencial_poluidor FROM eco_potencial_poluidor WHERE ds_potencial_poluidor = UPPER(:dsPotencialPoluidor) ";
        $sql .= (!empty($cdPotencialPoluidor)) ? " AND cd_potencial_poluidor NOT IN ($cdPotencialPoluidor)" : "";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":dsPotencialPoluidor", $this->dsPotencialPoluidor);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                $reg = $stmt->fetch(PDO::FETCH_OBJ);

                return $reg->cd_potencial_poluidor;
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

        $sql = "SELECT cd_potencial_poluidor, ds_potencial_poluidor, CASE sn_ativo WHEN 'S' THEN '<span class=\"col-green\">ATIVO</span>' ELSE '<span class=\"col-red\">INATIVO</span>' END AS ds_status, sn_ativo FROM eco_potencial_poluidor ORDER BY ds_potencial_poluidor";
        $stmt = $mysql->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
                    echo '
                    <tr>
                    <td>'.$reg->cd_potencial_poluidor.'</td>
                    <td><a data-toggle="modal" href="#modalFormAlterPotencialPoluidor" onclick="preencheFormAlterPotencialPoluidor('.$reg->cd_potencial_poluidor.',\''.$reg->ds_potencial_poluidor.'\',\''.$reg->sn_ativo.'\')">'.$reg->ds_potencial_poluidor.'</a></td>
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

    public function listOption($cdPotencialPoluidor=null){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "SELECT cd_potencial_poluidor, ds_potencial_poluidor FROM eco_potencial_poluidor WHERE sn_ativo = 'S' ORDER BY ds_potencial_poluidor";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpresa", $cdEmpresa);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){

                    $selected = ($cdPotencialPoluidor == $reg->cd_potencial_poluidor) ? "selected" : null;

                    echo '<option value="'.base64_encode($reg->cd_potencial_poluidor).'" '.$selected.'>'.$reg->ds_potencial_poluidor.'</option>';
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

    public function Cadastro(){

        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $mysql = MysqlConexao::getInstance();

        $sql = "INSERT INTO eco_potencial_poluidor (ds_potencial_poluidor, cd_usuario_registro) VALUES (UPPER(:dsPotencialPoluidor), :cdUsuarioSessao)";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":dsPotencialPoluidor", $this->dsPotencialPoluidor);
        $stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
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

            return 'E';
        }
    }

    public function Alterar(){
        $mysql = MysqlConexao::getInstance();

        $sql = "UPDATE eco_potencial_poluidor SET ds_potencial_poluidor = UPPER(:dsPotencialPoluidor), sn_ativo = :snAtivo WHERE cd_potencial_poluidor = :cdPotencialPoluidor";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdPotencialPoluidor", $this->cdPotencialPoluidor);
        $stmt->bindParam(":dsPotencialPoluidor", $this->dsPotencialPoluidor);
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
}

?>