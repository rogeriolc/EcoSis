<?php

class cUsuario extends mUsuario {

    public function dadosUsuario($cd_usuario="", $login=""){
        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT cd_usuario, login, nm_usuario, email FROM g_usuario WHERE ";
        $sql .= (!empty($cd_usuario)) ? "cd_usuario = $cd_usuario " : "";
        $sql .= (!empty($login)) ? "login = '$login' " : "";
        $stmt = $mysql->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if ($num > 0) {
                $reg = $stmt->fetch(PDO::FETCH_OBJ);

                $_SESSION['cdUsuario']  = $reg->cd_usuario;
                $_SESSION['login']      = $reg->login;
                $_SESSION['nmUsuario']  = $reg->nm_usuario;
                $_SESSION['dsSenha']    = $reg ->ds_senha;
                $_SESSION['dsEmail']    = $reg->email;
            }else{

            }
        }
    }

    public function Dados(){
        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT cd_usuario, login, nm_usuario, email, cd_perfil_usuario FROM g_usuario WHERE cd_usuario = :cdUsuario ";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdUsuario", $this->cdUsuario);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if ($num > 0) {
                $reg = $stmt->fetch(PDO::FETCH_OBJ);
                $this->login            = $reg->login;
                $this->nmPessoa         = $reg->nm_usuario;
                $this->dsEmail          = $reg->email;
                $this->cdPerfilUsuario  = $reg->cd_perfil_usuario;
            }else{

            }
        }
    }

    public static function validaEmail($dsEmail){
        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT cd_usuario FROM g_usuario WHERE email = :dsEmail ";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":dsEmail", $dsEmail);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if ($num > 0) {
                $reg = $stmt->fetch(PDO::FETCH_OBJ);

                return $reg->cd_usuario;
            }else{
                return false;
            }
        }
    }

    public static function validarToken($token){
        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT cd_usuario FROM g_usuario WHERE token_rec_senha = :token ";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":token", $token);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if ($num > 0) {
                $reg = $stmt->fetch(PDO::FETCH_OBJ);

                return $reg->cd_usuario;
            }else{
                return false;
            }
        }
    }

    public function returnCdUsuario($cdUsuario=""){
        $mysql = MysqlConexao::getInstance();

        // $cdEmpresa = isset($_SESSION['cdEmpresa']) ? $_SESSION['cdEmpresa'] : 0;
        $cdEmpresa = 0;

        $sql = "SELECT cd_usuario as cdUsuario FROM g_usuario WHERE login = :login";
        $sql .= ($cdEmpresa > 0) ? " AND cd_empresa = :cdEmpresa" : "";
        $sql .= (!empty($cdUsuario)) ? " AND cd_usuario NOT IN ($cdUsuario)" : "";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":login", $this->login);
        ($cdEmpresa > 0) ? $stmt->bindParam(":cdEmpresa", $cdEmpresa) : '';
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if ($num > 0) {
                $reg = $stmt->fetch(PDO::FETCH_OBJ);

                return $reg->cdUsuario;

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

    public function listTableUsuario(){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "SELECT cd_usuario, nm_usuario, login, email, cd_perfil_usuario, sn_ativo, CASE sn_ativo WHEN 'S' THEN '<span class=\"col-green\">ATIVO</span>' ELSE '<span class=\"col-red\">INATIVO</span>' END AS ds_status FROM g_usuario ORDER BY nm_usuario;";
        $stmt = $mysql->prepare($sql);
        // $stmt->bindParam(":cdEmpresa", $cdEmpresa);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
                    echo '
                    <tr>
                        <td>'.$reg->cd_usuario.'</td>
                        <td>
                            <a data-toggle="modal" href="#modalFormAlterUsuario" onclick="preencheFormAlterUsuario(\''.$reg->cd_usuario.'\',\''.$reg->nm_usuario.'\',\''.$reg->login.'\',\''.$reg->email.'\',\''.base64_encode($reg->cd_perfil_usuario).'\',\''.$reg->sn_ativo.'\')">'.$reg->nm_usuario.'</a>
                        </td>
                        <td>'.$reg->login.'</td>
                        <td>'.$reg->email.'</td>
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

    public function listOption($cdUsuario = null){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "SELECT cd_usuario, nm_usuario FROM g_usuario WHERE sn_ativo = 'S' ORDER BY nm_usuario";
        $stmt = $mysql->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){

                    $selected = ($cdUsuario == $reg->cd_usuario) ? 'selected' : '';

                    echo "<option value='".base64_encode($reg->cd_usuario)."' $selected>".$reg->nm_usuario."</option>";
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

    public function cadUsuario(){

        // $cdEmpresa = $_SESSION['cdEmpresa'];

        $mysql = MysqlConexao::getInstance();

        //md5("str")

        $sql = "INSERT INTO g_usuario (nm_usuario, login, ds_senha, email, cd_perfil_usuario) VALUES (UPPER(:nmPessoa), UPPER(:login), :dsSenha, :dsEmail, :cdPerfilUsuario)";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":nmPessoa", $this->nmPessoa);
        $stmt->bindParam(":login", $this->login);
        $stmt->bindParam(":dsSenha", $this->dsSenha);
        $stmt->bindParam(":dsEmail", $this->dsEmail);
        $stmt->bindParam(":cdPerfilUsuario", $this->cdPerfilUsuario);
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
            $nrLinha = __LINE__;
            regLog($dsError, basename( __FILE__ ), $nrLinha);

            return 'E';
        }
    }

    public function alterUsuario(){
        $mysql = MysqlConexao::getInstance();

        $sql = "UPDATE g_usuario SET nm_usuario = UPPER(:nmPessoa), login = UPPER(:login), ds_senha = :dsSenha, email = UPPER(:dsEmail), cd_perfil_usuario = :cdPerfilUsuario, sn_ativo = UPPER(:snAtivo) WHERE cd_usuario = :cdUsuario";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdUsuario", $this->cdUsuario);
        $stmt->bindParam(":nmPessoa", $this->nmPessoa);
        $stmt->bindParam(":login", $this->login);
        $stmt->bindParam(":dsSenha", $this->dsSenha);
        $stmt->bindParam(":dsEmail", $this->dsEmail);
        $stmt->bindParam(":cdPerfilUsuario", $this->cdPerfilUsuario);
        $stmt->bindParam(":snAtivo", $this->snAtivo);
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

            return 'E';
        }
    }

    public function updateTokenSenha($token){
        $mysql = MysqlConexao::getInstance();

        $sql = "UPDATE g_usuario SET token_rec_senha = :token, dh_token = now() WHERE cd_usuario = :cdUsuario";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdUsuario", $this->cdUsuario);
        $stmt->bindParam(":token", $token);
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

            return 'E';
        }
    }

    public function updateSenhaUsuario(){
        $mysql = MysqlConexao::getInstance();

        $sql = "UPDATE g_usuario SET ds_senha = :dsSenha, token_rec_senha = null WHERE cd_usuario = :cdUsuario";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdUsuario", $this->cdUsuario);
        $stmt->bindParam(":dsSenha",  $this->dsSenha);
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

            return 'E';
        }
    }

}