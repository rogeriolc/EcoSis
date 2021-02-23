<?php

class cFaseObjeto extends mFaseObjeto {

    public function __construct($cdFaseObjeto=null, $dsFaseObjeto=null, $nrOrdem = null, $snAtivo=null)
    {
        parent::__construct($cdFaseObjeto, $dsFaseObjeto, $nrOrdem, $snAtivo);
    }

    public function returnCodigo($cdFaseObjeto=""){

        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT cd_fase_objeto FROM eco_fase_objeto WHERE ds_fase_objeto = UPPER(:dsFaseObjeto) ";
        $sql .= (!empty($cdFaseObjeto)) ? " AND cd_fase_objeto NOT IN ($cdFaseObjeto)" : "";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":dsFaseObjeto", $this->dsFaseObjeto);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                $reg = $stmt->fetch(PDO::FETCH_OBJ);

                return $reg->cd_fase_objeto;
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

        $sql = "SELECT cd_fase_objeto, ds_fase_objeto, CASE sn_ativo WHEN 'S' THEN '<span class=\"col-green\">ATIVO</span>' ELSE '<span class=\"col-red\">INATIVO</span>' END AS ds_status, sn_ativo FROM eco_fase_objeto ORDER BY ds_fase_objeto";
        $stmt = $mysql->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
                    echo '
                    <tr>
                    <td>'.$reg->cd_fase_objeto.'</td>
                    <td><a data-toggle="modal" href="#modalFormAlterFaseObjeto" onclick="preencheFormAlterFaseObjeto('.$reg->cd_fase_objeto.',\''.$reg->ds_fase_objeto.'\',\''.$reg->sn_ativo.'\')">'.$reg->ds_fase_objeto.'</a></td>
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

    public function listOption($cdFaseObjeto=null){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "SELECT cd_fase_objeto, ds_fase_objeto FROM eco_fase_objeto WHERE sn_ativo = 'S' ORDER BY ds_fase_objeto";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpresa", $cdEmpresa);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){

                    $selected = ($cdFaseObjeto == $reg->cd_fase_objeto) ? "selected" : null;

                    echo '<option value="'.base64_encode($reg->cd_fase_objeto).'" '.$selected.'>'.$reg->ds_fase_objeto.'</option>';
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

        $sql = "INSERT INTO eco_fase_objeto (ds_fase_objeto, cd_usuario_registro) VALUES (UPPER(:dsFaseObjeto), :cdUsuarioSessao)";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":dsFaseObjeto", $this->dsFaseObjeto);
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

        $sql = "UPDATE eco_fase_objeto SET ds_fase_objeto = UPPER(:dsFaseObjeto), sn_ativo = :snAtivo WHERE cd_fase_objeto = :cdFaseObjeto";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdFaseObjeto", $this->cdFaseObjeto);
        $stmt->bindParam(":dsFaseObjeto", $this->dsFaseObjeto);
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

    public function addFaseObjeto(){

        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $mysql = MysqlConexao::getInstance();

        $sql = "INSERT INTO eco_objeto_licenca_fase (cd_objeto_licenca, cd_fase_objeto, nr_ordem, cd_usuario_registro) VALUES (:cdObjetoLicenca, :cdFaseObjeto, :nrOrdem, :cdUsuarioSessao)";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdObjetoLicenca", $this->cdObjetoLicenca);
        $stmt->bindParam(":cdFaseObjeto", $this->cdFaseObjeto);
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

    public function removeFaseObjeto(){

        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $mysql = MysqlConexao::getInstance();

        $sql = "DELETE FROM eco_objeto_licenca_fase WHERE cd_objeto_licenca = :cdObjetoLicenca";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdObjetoLicenca", $this->cdObjetoLicenca);
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

    public function listForm($cdObjetoLicenca){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "SELECT f.cd_fase_objeto, f.ds_fase_objeto FROM eco_fase_objeto f, eco_objeto_licenca_fase of WHERE f.sn_ativo = 'S' AND f.cd_fase_objeto = of.cd_fase_objeto AND of.cd_objeto_licenca = :cdObjetoLicenca ORDER BY f.ds_fase_objeto";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdObjetoLicenca", $cdObjetoLicenca);
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
                    self::listOption($reg->cd_fase_objeto);
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