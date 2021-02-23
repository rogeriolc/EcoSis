<?php

class cCatObjetoLicenca extends mCatObjetoLicenca
{

	public function __construct($cdCatObjetoLicenca=null, $dsCatObjetoLicenca=null, $snAtivo=null)
    {
        parent::__construct($cdCatObjetoLicenca, $dsCatObjetoLicenca, $snAtivo);
    }

    public function returnCodigo($cdCatObjetoLicenca=""){

        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT cd_cat_objeto_licenca FROM eco_cat_objeto_licenca WHERE ds_cat_objeto_licenca = UPPER(:dsCatObjetoLicenca) ";
        $sql .= (!empty($cdCatObjetoLicenca)) ? " AND cd_cat_objeto_licenca NOT IN ($cdCatObjetoLicenca)" : "";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":dsCatObjetoLicenca", $this->dsCatObjetoLicenca);
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
            var_dump($stmt->errorInfo());
            $dsError = ob_get_clean();
            regLog($dsError, basename( __FILE__ ));

            return 'E';
        }
    }

    public function listTable(){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "SELECT cd_cat_objeto_licenca, ds_cat_objeto_licenca, CASE sn_ativo WHEN 'S' THEN 'ATIVO' ELSE 'INATIVO' END AS ds_status, sn_ativo FROM eco_cat_objeto_licenca ORDER BY ds_cat_objeto_licenca";
        $stmt = $mysql->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
                    echo '
                    <tr>
                        <td>'.$reg->cd_cat_objeto_licenca.'</td>
                        <td><a data-toggle="modal" href="#modalFormAlterCatObjetoLicenca" onclick="preencheFormAlterCatObjetoLicenca('.$reg->cd_cat_objeto_licenca.',\''.$reg->ds_cat_objeto_licenca.'\',\''.$reg->sn_ativo.'\')">'.$reg->ds_cat_objeto_licenca.'</a></td>
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

    public function listOption(){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "SELECT cd_cat_objeto_licenca, ds_cat_objeto_licenca FROM eco_cat_objeto_licenca WHERE sn_ativo = 'S' ORDER BY ds_cat_objeto_licenca";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpresa", $cdEmpresa);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
                    echo '
                    <option value="'.base64_encode($reg->cd_cat_objeto_licenca).'">'.$reg->ds_cat_objeto_licenca.'</option>
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

        $sql = "INSERT INTO eco_cat_objeto_licenca (ds_cat_objeto_licenca) VALUES (UPPER(:dsCatObjetoLicenca))";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":dsCatObjetoLicenca", $this->dsCatObjetoLicenca);
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

        $sql = "UPDATE eco_cat_objeto_licenca SET ds_cat_objeto_licenca = UPPER(:dsCatObjetoLicenca), sn_ativo = :snAtivo WHERE cd_cat_objeto_licenca = :cdCatObjetoLicenca";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdCatObjetoLicenca", $this->cdCatObjetoLicenca);
        $stmt->bindParam(":dsCatObjetoLicenca", $this->dsCatObjetoLicenca);
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