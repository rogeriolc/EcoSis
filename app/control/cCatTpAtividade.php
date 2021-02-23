<?php

class cCatTpAtividade extends mCatTpAtividade
{

	public function __construct($cdCatTpAtividade=null, $dsCatTpAtividade=null, $snAtivo=null)
    {
        parent::__construct($cdCatTpAtividade, $dsCatTpAtividade, $snAtivo);
    }

    public function returnCodigo($cdCatTpAtividade=""){

        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT cd_cat_tp_atividade FROM eco_cat_tp_atividade WHERE ds_cat_tp_atividade = UPPER(:dsCatTpAtividade) ";
        $sql .= (!empty($cdCatTpAtividade)) ? " AND cd_cat_tp_atividade NOT IN ($cdCatTpAtividade)" : "";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":dsCatTpAtividade", $this->dsCatTpAtividade);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                $reg = $stmt->fetch(PDO::FETCH_OBJ);

                return $reg->cd_cat_tp_atividade;
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

        $sql = "SELECT cd_cat_tp_atividade, ds_cat_tp_atividade, CASE sn_ativo WHEN 'S' THEN 'ATIVO' ELSE 'INATIVO' END AS ds_status, sn_ativo FROM eco_cat_tp_atividade ORDER BY ds_cat_tp_atividade";
        $stmt = $mysql->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
                    echo '
                    <tr>
                        <td>'.$reg->cd_cat_tp_atividade.'</td>
                        <td><a data-toggle="modal" href="#modalFormAlterCatTpAtividade" onclick="preencheFormAlterCatTpAtividade('.$reg->cd_cat_tp_atividade.',\''.$reg->ds_cat_tp_atividade.'\',\''.$reg->sn_ativo.'\')">'.$reg->ds_cat_tp_atividade.'</a></td>
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

        $sql = "SELECT cd_cat_tp_atividade, ds_cat_tp_atividade FROM eco_cat_tp_atividade WHERE sn_ativo = 'S' ORDER BY ds_cat_tp_atividade";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpresa", $cdEmpresa);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
                    echo '
                    <option value="'.base64_encode($reg->cd_cat_tp_atividade).'">'.$reg->ds_cat_tp_atividade.'</option>
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

        $sql = "INSERT INTO eco_cat_tp_atividade (ds_cat_tp_atividade) VALUES (UPPER(:dsCatTpAtividade))";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":dsCatTpAtividade", $this->dsCatTpAtividade);
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

        $sql = "UPDATE eco_cat_tp_atividade SET ds_cat_tp_atividade = UPPER(:dsCatTpAtividade), sn_ativo = :snAtivo WHERE cd_cat_tp_atividade = :cdCatTpAtividade";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdCatTpAtividade", $this->cdCatTpAtividade);
        $stmt->bindParam(":dsCatTpAtividade", $this->dsCatTpAtividade);
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