<?php

class cFaseAtividade extends mFaseAtividade {

    public function __construct($cdFaseAtividade=null, $dsFaseAtividade=null, $nrOrdem = null, $snAtivo=null)
    {
        parent::__construct($cdFaseAtividade, $dsFaseAtividade, $nrOrdem, $snAtivo);
    }

    public function returnCodigo($cdFaseAtividade=""){

        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT cd_fase_atividade FROM eco_fase_atividade WHERE ds_fase_atividade = UPPER(:dsFaseAtividade) ";
        $sql .= (!empty($cdFaseAtividade)) ? " AND cd_fase_atividade NOT IN ($cdFaseAtividade)" : "";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":dsFaseAtividade", $this->dsFaseAtividade);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                $reg = $stmt->fetch(PDO::FETCH_OBJ);

                return $reg->cd_fase_atividade;
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

        $sql = "SELECT cd_fase_atividade, ds_fase_atividade, CASE sn_ativo WHEN 'S' THEN '<span class=\"col-green\">ATIVO</span>' ELSE '<span class=\"col-red\">INATIVO</span>' END AS ds_status, sn_ativo FROM eco_fase_atividade ORDER BY ds_fase_atividade";
        $stmt = $mysql->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
                    echo '
                    <tr>
                    <td>'.$reg->cd_fase_atividade.'</td>
                    <td><a data-toggle="modal" href="#modalFormAlterFaseAtividade" onclick="preencheFormAlterFaseAtividade('.$reg->cd_fase_atividade.',\''.$reg->ds_fase_atividade.'\',\''.$reg->sn_ativo.'\')">'.$reg->ds_fase_atividade.'</a></td>
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

    public function listOption($cdFaseAtividade=null){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "SELECT cd_fase_atividade, ds_fase_atividade FROM eco_fase_atividade WHERE sn_ativo = 'S' ORDER BY ds_fase_atividade";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpresa", $cdEmpresa);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){

                    $selected = ($cdFaseAtividade == $reg->cd_fase_atividade) ? "selected" : null;

                    echo '<option value=\''.base64_encode($reg->cd_fase_atividade).'\' '.$selected.'>'.$reg->ds_fase_atividade.'</option>';
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

        $sql = "INSERT INTO eco_fase_atividade (ds_fase_atividade, cd_usuario_registro) VALUES (UPPER(:dsFaseAtividade), :cdUsuarioSessao)";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":dsFaseAtividade", $this->dsFaseAtividade);
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

        $sql = "UPDATE eco_fase_atividade SET ds_fase_atividade = UPPER(:dsFaseAtividade), sn_ativo = :snAtivo WHERE cd_fase_atividade = :cdFaseAtividade";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdFaseAtividade", $this->cdFaseAtividade);
        $stmt->bindParam(":dsFaseAtividade", $this->dsFaseAtividade);
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

    public function addFaseAtividade(){

        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $mysql = MysqlConexao::getInstance();

        $sql = "INSERT INTO eco_tp_atividade_fase (cd_tp_atividade, cd_fase_atividade, nr_ordem, cd_usuario_registro) VALUES (:cdTpAtividade, :cdFaseAtividade, :nrOrdem, :cdUsuarioSessao)";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdTpAtividade", $this->cdTpAtividade);
        $stmt->bindParam(":cdFaseAtividade", $this->cdFaseAtividade);
        $stmt->bindParam(":nrOrdem", $this->nrOrdem);
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

    public function removeFaseAtividade(){

        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $mysql = MysqlConexao::getInstance();

        $sql = "DELETE FROM eco_tp_atividade_fase WHERE cd_tp_atividade = :cdTpAtividade";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdTpAtividade", $this->cdTpAtividade);
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

    public function listForm($cdTpAtividade){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "SELECT f.cd_fase_atividade, f.ds_fase_atividade FROM eco_fase_atividade f, eco_tp_atividade_fase af WHERE f.sn_ativo = 'S' AND f.cd_fase_atividade = af.cd_fase_atividade AND af.cd_tp_atividade = :cdTpAtividade ORDER BY af.nr_ordem";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdTpAtividade", $cdTpAtividade);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
                    echo '
                    <tr>
                    <td class="cursorMove col-md-1 col-xs-1 text-center text-middle">
                    <i class="fas fa-ellipsis-v"></i><i class="fas fa-ellipsis-v"></i>
                    </td>
                    <td>
                    <select class="form-control select2" name="cdFase[]" style="width: 100%;">
                    ';
                    self::listOption($reg->cd_fase_atividade);
                    echo '
                    </select>
                    </td>
                    <td class="col-md-1 col-xs-2 text-center text-middle">
                    <a href="javascript:void(0)" onclick="removerFase(this)" class="col-red">
                    <i class="material-icons">delete</i>
                    </a>
                    </td>
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

}