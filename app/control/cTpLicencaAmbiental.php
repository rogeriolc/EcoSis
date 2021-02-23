<?php

class cTpLicencaAmbiental extends mTpLicencaAmbiental
{

	public function __construct($cdTpLicencaAmbiental=null, $dsTpLicencaAmbiental=null, $snAtivo=null)
    {
        parent::__construct($cdTpLicencaAmbiental, $dsTpLicencaAmbiental, $snAtivo);
    }

    public function returnCodigo($cdTpLicencaAmbiental=""){

        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT cd_tp_licenca_ambiental FROM eco_tp_licenca_ambiental WHERE ds_tp_licenca_ambiental = UPPER(:dsTpLicencaAmbiental) ";
        $sql .= (!empty($cdTpLicencaAmbiental)) ? " AND cd_tp_licenca_ambiental NOT IN ($cdTpLicencaAmbiental)" : "";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":dsTpLicencaAmbiental", $this->dsTpLicencaAmbiental);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                $reg = $stmt->fetch(PDO::FETCH_OBJ);

                return $reg->cd_tp_licenca_ambiental;
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

        $sql = "SELECT cd_tp_licenca_ambiental, ds_tp_licenca_ambiental, CASE sn_ativo WHEN 'S' THEN '<span class=\"col-green\">ATIVO</span>' ELSE '<span class=\"col-red\">INATIVO</span>' END AS ds_status, sn_ativo FROM eco_tp_licenca_ambiental ORDER BY ds_tp_licenca_ambiental";
        $stmt = $mysql->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
                    echo '
                    <tr>
                        <td>'.$reg->cd_tp_licenca_ambiental.'</td>
                        <td><a data-toggle="modal" href="#modalFormAlterTpLicencaAmbiental" onclick="preencheFormAlterTpLicencaAmbiental('.$reg->cd_tp_licenca_ambiental.',\''.$reg->ds_tp_licenca_ambiental.'\',\''.$reg->sn_ativo.'\')">'.$reg->ds_tp_licenca_ambiental.'</a></td>
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

    public function listOption(){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "SELECT cd_tp_licenca_ambiental, ds_tp_licenca_ambiental FROM eco_tp_licenca_ambiental WHERE sn_ativo = 'S' ORDER BY ds_tp_licenca_ambiental";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpresa", $cdEmpresa);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
                    echo '
                    <option value="'.base64_encode($reg->cd_tp_licenca_ambiental).'">'.$reg->ds_tp_licenca_ambiental.'</option>
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

    public function Cadastro(){
        $mysql = MysqlConexao::getInstance();

        $sql = "INSERT INTO eco_tp_licenca_ambiental (ds_tp_licenca_ambiental) VALUES (UPPER(:dsTpLicencaAmbiental))";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":dsTpLicencaAmbiental", $this->dsTpLicencaAmbiental);
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

        $sql = "UPDATE eco_tp_licenca_ambiental SET ds_tp_licenca_ambiental = UPPER(:dsTpLicencaAmbiental), sn_ativo = :snAtivo WHERE cd_tp_licenca_ambiental = :cdTpLicencaAmbiental";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdTpLicencaAmbiental", $this->cdTpLicencaAmbiental);
        $stmt->bindParam(":dsTpLicencaAmbiental", $this->dsTpLicencaAmbiental);
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