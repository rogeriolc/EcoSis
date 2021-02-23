<?php

/**
* cPorteEmpreendimento
*/
class cPorteEmpreendimento extends mPorteEmpreendimento
{

	function __construct($cdPorteEmpreendimento=null, $dsPorteEmpreendimento=null, $snAtivo=null, $dhRegistro=null)
	{
		parent::__construct($cdPorteEmpreendimento, $dsPorteEmpreendimento, $snAtivo, $dhRegistro);
	}

	public function returnCodigo($cdPorteEmpreendimento=""){

        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT cd_porte_empreendimento FROM eco_porte_empreendimento WHERE ds_porte_empreendimento = UPPER(:dsPorteEmpreendimento) ";
        $sql .= (!empty($cdPorteEmpreendimento)) ? " AND cd_porte_empreendimento NOT IN ($cdPorteEmpreendimento)" : "";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":dsPorteEmpreendimento", $this->dsPorteEmpreendimento);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                $reg = $stmt->fetch(PDO::FETCH_OBJ);

                return $reg->cd_porte_empreendimento;
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

        $sql = "SELECT cd_porte_empreendimento, ds_porte_empreendimento, CASE sn_ativo WHEN 'S' THEN '<span class=\"col-green\">ATIVO</span>' ELSE '<span class=\"col-red\">INATIVO</span>' END AS ds_status, sn_ativo FROM eco_porte_empreendimento ORDER BY ds_porte_empreendimento";
        $stmt = $mysql->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
                    echo '
                    <tr>
                    <td>'.$reg->cd_porte_empreendimento.'</td>
                    <td><a data-toggle="modal" href="#modalFormAlterPorteEmpreendimento" onclick="preencheFormAlterPorteEmpreendimento('.$reg->cd_porte_empreendimento.',\''.$reg->ds_porte_empreendimento.'\',\''.$reg->sn_ativo.'\')">'.$reg->ds_porte_empreendimento.'</a></td>
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

    public function listOption($cdPorteEmpreendimento=null){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "SELECT cd_porte_empreendimento, ds_porte_empreendimento FROM eco_porte_empreendimento WHERE sn_ativo = 'S' ORDER BY ds_porte_empreendimento";
        $stmt = $mysql->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){

                    $selected = ($cdPorteEmpreendimento == $reg->cd_porte_empreendimento) ? "selected" : null;

                    echo '<option value="'.base64_encode($reg->cd_porte_empreendimento).'" '.$selected.'>'.$reg->ds_porte_empreendimento.'</option>';
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

        $sql = "INSERT INTO eco_porte_empreendimento (ds_porte_empreendimento, cd_usuario_registro) VALUES (UPPER(:dsPorteEmpreendimento), :cdUsuarioSessao)";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":dsPorteEmpreendimento", $this->dsPorteEmpreendimento);
        $stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                return true;
            }else{
                return false;
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

    public function Alterar(){
        $mysql = MysqlConexao::getInstance();

        $sql = "UPDATE eco_porte_empreendimento SET ds_porte_empreendimento = UPPER(:dsPorteEmpreendimento), sn_ativo = :snAtivo WHERE cd_porte_empreendimento = :cdPorteEmpreendimento";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdPorteEmpreendimento", $this->cdPorteEmpreendimento);
        $stmt->bindParam(":dsPorteEmpreendimento", $this->dsPorteEmpreendimento);
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