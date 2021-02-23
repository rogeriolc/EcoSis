<?php

class cTpDocumento extends mTpDocumento {

    public function __construct($cdTpDocumento=null, $dsTpDocumento=null, $vlTpDocumento=null, $snAtivo=null)
    {
        parent::__construct($cdTpDocumento, $dsTpDocumento, $vlTpDocumento, $snAtivo);
    }

    public function returnCodigo($cdTpDocumento=""){

        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT cd_tp_documento FROM eco_tp_documento WHERE ds_tp_documento = UPPER(:dsTpDocumento) ";
        $sql .= (!empty($cdTpDocumento)) ? " AND cd_tp_documento NOT IN ($cdTpDocumento)" : "";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":dsTpDocumento", $this->dsTpDocumento);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                $reg = $stmt->fetch(PDO::FETCH_OBJ);

                return $reg->cd_tp_documento;
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

        $sql = "SELECT cd_tp_documento, ds_tp_documento, CASE sn_ativo WHEN 'S' THEN '<span class=\"col-green\">ATIVO</span>' ELSE '<span class=\"col-red\">INATIVO</span>' END AS ds_status, sn_ativo FROM eco_tp_documento ORDER BY ds_tp_documento";
        $stmt = $mysql->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
                    echo '
                    <tr>
                        <td>'.$reg->cd_tp_documento.'</td>
                        <td><a data-toggle="modal" href="#modalFormAlterTpDocumento" onclick="preencheFormAlterTpDocumento('.$reg->cd_tp_documento.',\''.$reg->ds_tp_documento.'\',\''.$reg->sn_ativo.'\')">'.$reg->ds_tp_documento.'</a></td>
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

    public function listOption($cdTpDocumento=null){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "SELECT cd_tp_documento, ds_tp_documento FROM eco_tp_documento WHERE sn_ativo = 'S' ORDER BY ds_tp_documento";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpresa", $cdEmpresa);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){

                    $selected = ($cdTpDocumento == $reg->cd_tp_documento) ? 'selected' : '';

                    echo '<option value="'.base64_encode($reg->cd_tp_documento).'" '.$selected.'>'.$reg->ds_tp_documento.'</option>';
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

        $sql = "SELECT ds_tp_documento FROM eco_tp_documento WHERE cd_tp_documento = :cdTpDocumento";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdTpDocumento", $this->cdTpDocumento);
        $result = $stmt->execute();
        if ($result) {
            $reg = $stmt->fetch(PDO::FETCH_OBJ);

            $this->dsTpDocumento = $reg->ds_tp_documento;
        }else{
            echo var_dump($stmt->errorInfo());
        }
    }

    public function Cadastro(){
        $mysql = MysqlConexao::getInstance();

        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $sql = "INSERT INTO eco_tp_documento (ds_tp_documento, cd_usuario_registro) VALUES (UPPER(:dsTpDocumento), :cdUsuarioSessao)";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":dsTpDocumento", $this->dsTpDocumento);
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

        $sql = "UPDATE eco_tp_documento SET ds_tp_documento = UPPER(:dsTpDocumento), sn_ativo = :snAtivo WHERE cd_tp_documento = :cdTpDocumento";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdTpDocumento", $this->cdTpDocumento);
        $stmt->bindParam(":dsTpDocumento", $this->dsTpDocumento);
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