<?php

class cTpArea extends mTpArea {

    public function __construct($cdTpArea=null, $dsTpArea=null, $vlTpArea=null, $snAtivo=null)
    {
        parent::__construct($cdTpArea, $dsTpArea, $vlTpArea, $snAtivo);
    }

    public function returnCodigo($cdTpArea=""){

        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT cd_tp_area FROM g_tp_area WHERE ds_tp_area = UPPER(:dsTpArea) ";
        $sql .= (!empty($cdTpArea)) ? " AND cd_tp_area NOT IN ($cdTpArea)" : "";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":dsTpArea", $this->dsTpArea);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                $reg = $stmt->fetch(PDO::FETCH_OBJ);

                return $reg->cd_tp_area;
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

        $sql = "SELECT cd_tp_area, ds_tp_area, CASE sn_ativo WHEN 'S' THEN '<span class=\"col-green\">ATIVO</span>' ELSE '<span class=\"col-red\">INATIVO</span>' END AS ds_status, sn_ativo FROM g_tp_area ORDER BY ds_tp_area";
        $stmt = $mysql->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
                    echo '
                    <tr>
                        <td>'.$reg->cd_tp_area.'</td>
                        <td><a data-toggle="modal" href="#modalFormAlterTpArea" onclick="preencheFormAlterTpArea('.$reg->cd_tp_area.',\''.$reg->ds_tp_area.'\',\''.$reg->sn_ativo.'\')">'.$reg->ds_tp_area.'</a></td>
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

    public function listOption($cdTpArea=null){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "SELECT cd_tp_area, ds_tp_area FROM g_tp_area WHERE sn_ativo = 'S' ORDER BY ds_tp_area";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpresa", $cdEmpresa);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){

                    $selected = ($cdTpArea == $reg->cd_tp_area) ? 'selected' : '';

                    echo '<option value="'.base64_encode($reg->cd_tp_area).'" '.$selected.'>'.$reg->ds_tp_area.'</option>';
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

        $sql = "SELECT ds_tp_area FROM g_tp_area WHERE cd_tp_area = :cdTpArea";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdTpArea", $this->cdTpArea);
        $result = $stmt->execute();
        if ($result) {
            $reg = $stmt->fetch(PDO::FETCH_OBJ);

            $this->dsTpArea = $reg->ds_tp_area;
        }else{
            echo var_dump($stmt->errorInfo());
        }
    }

    public function Cadastro(){
        $mysql = MysqlConexao::getInstance();

        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $sql = "INSERT INTO g_tp_area (ds_tp_area, cd_usuario_registro) VALUES (UPPER(:dsTpArea), :cdUsuarioSessao)";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":dsTpArea", $this->dsTpArea);
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

        $sql = "UPDATE g_tp_area SET ds_tp_area = UPPER(:dsTpArea), sn_ativo = :snAtivo WHERE cd_tp_area = :cdTpArea";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdTpArea", $this->cdTpArea);
        $stmt->bindParam(":dsTpArea", $this->dsTpArea);
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