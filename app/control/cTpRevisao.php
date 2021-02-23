<?php

class cTpRevisao extends mTpRevisao {

    public function __construct($cdTpRevisao=null, $dsTpRevisao=null, $snAtivo=null)
    {
        parent::__construct($cdTpRevisao, $dsTpRevisao, $snAtivo);
    }

    public function returnCodigo($cdTpRevisao=""){

        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT cd_tp_revisao FROM g_tp_revisao WHERE ds_tp_revisao = UPPER(:dsTpRevisao) ";
        $sql .= (!empty($cdTpRevisao)) ? " AND cd_tp_revisao NOT IN ($cdTpRevisao)" : "";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":dsTpRevisao", $this->dsTpRevisao);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                $reg = $stmt->fetch(PDO::FETCH_OBJ);

                return $reg->cd_tp_revisao;
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

        $sql = "SELECT cd_tp_revisao, ds_tp_revisao, CASE ativo WHEN 1 THEN '<span class=\"col-green\">ATIVO</span>' ELSE '<span class=\"col-red\">INATIVO</span>' END AS ds_status, ativo FROM g_tp_revisao ORDER BY ds_tp_revisao";
        $stmt = $mysql->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
                    echo '
                    <tr>
                        <td>'.$reg->cd_tp_revisao.'</td>
                        <td><a data-toggle="modal" href="#modalFormAlterTpRevisao" onclick="preencheFormAlterTpRevisao('.$reg->cd_tp_revisao.',\''.$reg->ds_tp_revisao.'\',\''.$reg->ativo.'\')">'.$reg->ds_tp_revisao.'</a></td>
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

    public function listOption($cdTpRevisao=null){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "SELECT cd_tp_revisao, ds_tp_revisao FROM g_tp_revisao WHERE ativo = 1 ORDER BY ds_tp_revisao";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpresa", $cdEmpresa);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){

                    $selected = ($cdTpRevisao == $reg->cd_tp_revisao) ? 'selected' : '';

                    echo '<option value="'.($reg->cd_tp_revisao).'" '.$selected.'>'.$reg->ds_tp_revisao.'</option>';
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

        $sql = "SELECT ds_tp_revisao FROM g_tp_revisao WHERE cd_tp_revisao = :cdTpRevisao";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdTpRevisao", $this->cdTpRevisao);
        $result = $stmt->execute();
        if ($result) {
            $reg = $stmt->fetch(PDO::FETCH_OBJ);

            $this->dsTpRevisao = $reg->ds_tp_revisao;
        }else{
            echo var_dump($stmt->errorInfo());
        }
    }

    public function Cadastro(){
        $mysql = MysqlConexao::getInstance();

        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $sql = "INSERT INTO g_tp_revisao (ds_tp_revisao, cd_usuario_registro) VALUES (UPPER(:dsTpRevisao), :cdUsuarioSessao)";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":dsTpRevisao", $this->dsTpRevisao);
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

        $sql = "UPDATE g_tp_revisao SET ds_tp_revisao = UPPER(:dsTpRevisao), ativo = :snAtivo WHERE cd_tp_revisao = :cdTpRevisao";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdTpRevisao", $this->cdTpRevisao);
        $stmt->bindParam(":dsTpRevisao", $this->dsTpRevisao);
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