<?php

class cObjetoLicenca extends mObjetoLicenca
{

	public function __construct($cdObjetoLicenca=null, $dsObjetoLicenca=null, $cdCatObjetoLicenca=null, $snPedirProtocolo = null, $snAtivo=null)
    {
        parent::__construct($cdObjetoLicenca, $dsObjetoLicenca, $cdCatObjetoLicenca, $snPedirProtocolo, $snAtivo);
    }

    public function returnCodigo($cdObjetoLicenca=""){

        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT cd_objeto_licenca FROM eco_objeto_licenca WHERE ds_objeto_licenca = UPPER(:dsObjetoLicenca) ";
        $sql .= (!empty($cdObjetoLicenca)) ? " AND cd_objeto_licenca NOT IN ($cdObjetoLicenca)" : "";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":dsObjetoLicenca", $this->dsObjetoLicenca);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                $reg = $stmt->fetch(PDO::FETCH_OBJ);

                return $reg->cd_objeto_licenca;
            }else{
                return 0;
            }

        }else{
            //GERAR LOG
            ob_start();
            $dsError = ob_get_clean();
            regLog($dsError, basename( __FILE__ ));

            $erro = $stmt->errorInfo();

            // return 'E';
            return $erro[2];
        }
    }

    public function returnCatObjetoLicenca(){

        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT cd_cat_objeto_licenca FROM eco_objeto_licenca WHERE cd_objeto_licenca = :cdObjetoLicenca";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdObjetoLicenca", $this->cdObjetoLicenca);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                $reg = $stmt->fetch(PDO::FETCH_OBJ);

                return $reg->cd_cat_objeto_licenca;
            }else{
                return 0;
            }

        }else{
            //GERAR LOG
            ob_start();
            $dsError = ob_get_clean();
            regLog($dsError, basename( __FILE__ ));

            $erro = $stmt->errorInfo();

            // return 'E';
            return $erro[2];
        }
    }

    public function listTable(){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "SELECT o.cd_objeto_licenca, o.ds_objeto_licenca, co.cd_cat_objeto_licenca, co.ds_cat_objeto_licenca, o.sn_pedir_protocolo, CASE o.sn_ativo WHEN 'S' THEN '<span class=\"col-green\">ATIVO</span>' ELSE '<span class=\"col-red\">INATIVO</span>' END AS ds_status, o.sn_ativo FROM eco_objeto_licenca o, eco_cat_objeto_licenca co WHERE o.cd_cat_objeto_licenca = co.cd_cat_objeto_licenca ORDER BY o.ds_objeto_licenca";
        $stmt = $mysql->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
                    echo '
                    <tr>
                    <td>'.$reg->cd_objeto_licenca.'</td>
                    <td><a data-toggle="modal" href="#modalFormAlterObjetoLicenca" onclick="preencheFormAlterObjetoLicenca('.$reg->cd_objeto_licenca.',\''.$reg->ds_objeto_licenca.'\',\''.base64_encode($reg->cd_cat_objeto_licenca).'\',\''.$reg->sn_pedir_protocolo.'\',\''.$reg->sn_ativo.'\')">'.$reg->ds_objeto_licenca.'</a></td>
                    <td>'.$reg->ds_cat_objeto_licenca.'</td>
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

        $sql = "SELECT cd_objeto_licenca, ds_objeto_licenca FROM eco_objeto_licenca WHERE sn_ativo = 'S' ORDER BY ds_objeto_licenca";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpresa", $cdEmpresa);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
                    echo '
                    <option value="'.base64_encode($reg->cd_objeto_licenca).'">'.$reg->ds_objeto_licenca.'</option>
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

        $sql = "INSERT INTO eco_objeto_licenca (ds_objeto_licenca, cd_cat_objeto_licenca, sn_pedir_protocolo) VALUES (UPPER(:dsObjetoLicenca), :cdCatObjetoLicenca, :snPedirProtocolo)";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":dsObjetoLicenca", $this->dsObjetoLicenca);
        $stmt->bindParam(":cdCatObjetoLicenca", $this->cdCatObjetoLicenca);
        $stmt->bindParam(":snPedirProtocolo", $this->snPedirProtocolo);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                return $mysql->lastInsertId();
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

        $sql = "UPDATE eco_objeto_licenca SET ds_objeto_licenca = UPPER(:dsObjetoLicenca), cd_cat_objeto_licenca = :cdCatObjetoLicenca, sn_pedir_protocolo = :snPedirProtocolo, sn_ativo = :snAtivo WHERE cd_objeto_licenca = :cdObjetoLicenca";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdObjetoLicenca", $this->cdObjetoLicenca);
        $stmt->bindParam(":dsObjetoLicenca", $this->dsObjetoLicenca);
        $stmt->bindParam(":cdCatObjetoLicenca", $this->cdCatObjetoLicenca);
        $stmt->bindParam(":snPedirProtocolo", $this->snPedirProtocolo);
        $stmt->bindParam(":snAtivo", $this->snAtivo);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                $reg = $stmt->fetch(PDO::FETCH_OBJ);
                return 'S';
            }else{
                return 'S';
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

    public function Dados(){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "SELECT ds_objeto_licenca, cd_cat_objeto_licenca, sn_pedir_protocolo, sn_ativo FROM eco_objeto_licenca WHERE cd_objeto_licenca = :cdObjetoLicenca";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdObjetoLicenca", $this->cdObjetoLicenca);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                $reg = $stmt->fetch(PDO::FETCH_OBJ);

                $this->dsObjetoLicenca      = $reg->ds_objeto_licenca;
                $this->cdCatObjetoLicenca   = $reg->cd_cat_objeto_licenca;
                $this->snPedirProtocolo     = $reg->sn_pedir_protocolo;
                $this->snAtivo              = $reg->sn_ativo;

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
}

?>