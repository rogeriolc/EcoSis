<?php

class cOrgaoLicenciado extends mOrgaoLicenciado {

    public function __construct($cdOrgaoLicenciado=null, $nmOrgaoLicenciado=null, $snAtivo=null)
    {
        parent::__construct($cdOrgaoLicenciado, $nmOrgaoLicenciado, $snAtivo);
    }

    public function returnCodigo($cdOrgaoLicenciado=""){

        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT cd_orgao_licenciado FROM g_orgao_licenciado WHERE nm_orgao_licenciado = UPPER(:nmOrgaoLicenciado) ";
        $sql .= (!empty($cdOrgaoLicenciado)) ? " AND cd_orgao_licenciado NOT IN ($cdOrgaoLicenciado)" : "";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":nmOrgaoLicenciado", $this->nmOrgaoLicenciado);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                $reg = $stmt->fetch(PDO::FETCH_OBJ);

                return $reg->cd_orgao_licenciado;
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

        $sql = "SELECT cd_orgao_licenciado, nm_orgao_licenciado, CASE sn_ativo WHEN 'S' THEN '<span class=\"col-green\">ATIVO</span>' ELSE '<span class=\"col-red\">INATIVO</span>' END AS ds_status, sn_ativo FROM g_orgao_licenciado ORDER BY nm_orgao_licenciado";
        $stmt = $mysql->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
                    echo '
                    <tr>
                        <td>'.$reg->cd_orgao_licenciado.'</td>
                        <td><a data-toggle="modal" href="#modalFormAlterOrgaoLicenciado" onclick="preencheFormAlterOrgaoLicenciado('.$reg->cd_orgao_licenciado.',\''.$reg->nm_orgao_licenciado.'\',\''.$reg->sn_ativo.'\')">'.$reg->nm_orgao_licenciado.'</a></td>
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

    public function listOption($cdOrgaoLicenciado=null){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "SELECT cd_orgao_licenciado, nm_orgao_licenciado FROM g_orgao_licenciado WHERE sn_ativo = 'S' ORDER BY nm_orgao_licenciado";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpresa", $cdEmpresa);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){

                    $selected = ($cdOrgaoLicenciado == $reg->cd_orgao_licenciado) ? 'selected' : '';

                    echo '
                    <option value="'.base64_encode($reg->cd_orgao_licenciado).'" '.$selected.'>'.$reg->nm_orgao_licenciado.'</option>
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

    public function Dados(){

        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT nm_orgao_licenciado FROM g_orgao_licenciado WHERE cd_orgao_licenciado = :cdOrgaoLicenciado";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdOrgaoLicenciado", $this->cdOrgaoLicenciado);
        $result = $stmt->execute();
        if ($result) {
            $reg = $stmt->fetch(PDO::FETCH_OBJ);

            $this->nmOrgaoLicenciado = $reg->nm_orgao_licenciado;
        }else{
            echo var_dump($stmt->errorInfo());
        }
    }

    public function Cadastro(){
        $mysql = MysqlConexao::getInstance();

        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $sql = "INSERT INTO g_orgao_licenciado (nm_orgao_licenciado, cd_usuario_registro) VALUES (UPPER(:nmOrgaoLicenciado), :cdUsuarioSessao)";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":nmOrgaoLicenciado", $this->nmOrgaoLicenciado);
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

        $sql = "UPDATE g_orgao_licenciado SET nm_orgao_licenciado = UPPER(:nmOrgaoLicenciado), sn_ativo = :snAtivo WHERE cd_orgao_licenciado = :cdOrgaoLicenciado";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdOrgaoLicenciado", $this->cdOrgaoLicenciado);
        $stmt->bindParam(":nmOrgaoLicenciado", $this->nmOrgaoLicenciado);
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