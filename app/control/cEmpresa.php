<?php

class cEmpresa extends mEmpresa {

    public function listSelect($cdUsuario = ''){
        //LEMBRAR DE COLOCAR MULTI EMPRESA

        $mysql = MysqlConexao::getInstance();

        if(empty($cdUsuario)){
            $sql = "SELECT cd_empresa, nm_empresa FROM g_empresa e WHERE e.sn_ativo = 'S' ORDER BY e.nm_empresa ASC";
            $stmt = $mysql->prepare($sql);
        }else{
            $sql = "SELECT cd_empresa, nm_empresa FROM g_empresa e, g_usuario_empresa ue WHERE e.cd_empresa = ue.cd_empresa AND ue.cdUsuario = :cdUsuario AND e.sn_ativo = 'S' ORDER BY e.nm_empresa ASC";
            $stmt = $mysql->prepare($sql);
            $stmt->bindParam(":cdUsuario", $cdUsuario);
        }
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while ($reg = $stmt->fetch(PDO::FETCH_OBJ)) {
                    echo '<option value="'.base64_encode($reg->cd_empresa).'">'.$reg->nm_empresa.'</option>';
                }
            }else{
                echo '<option value=""></option>';
            }
        }else{
            echo '<option>'.var_dump($stmt->errorInfo()).'</option>';
        }
    }
    public function listSelectLogin($cdUsuario = ''){
        //LEMBRAR DE COLOCAR MULTI EMPRESA

        $mysql = MysqlConexao::getInstance();

        if(empty($cdUsuario)){
            echo '<option value=""></option>';
        }else{

            $sql = "SELECT e.cd_empresa, e.nm_empresa FROM g_empresa e, g_usuario_empresa ue WHERE e.cd_empresa = ue.cd_empresa AND ue.cd_usuario = :cdUsuario AND e.sn_ativo = 'S' ORDER BY e.nm_empresa ASC";
            $stmt = $mysql->prepare($sql);
            $stmt->bindParam(":cdUsuario", $cdUsuario);
            $result = $stmt->execute();
            if ($result) {
                $num = $stmt->rowCount();
                if($num > 0){
                    while ($reg = $stmt->fetch(PDO::FETCH_OBJ)) {
                        echo '<option value="'.base64_encode($reg->cd_empresa).'">'.$reg->nm_empresa.'</option>';
                    }
                }
            }else{
                echo '<option>'.var_dump($stmt->errorInfo()).'</option>';
            }
        }
    }

}