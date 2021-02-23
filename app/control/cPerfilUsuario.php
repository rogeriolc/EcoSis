<?php
/**
* PerfilUsuario
*/
class cPerfilUsuario extends mPerfilUsuario {

  public function Cadastrar(){
    $mysql = MysqlConexao::getInstance();

    $cdUsuario = $_SESSION['cdUsuario'];
    $cdEmpresa = $_SESSION['cdEmpresa'];

    $snPerfilCadastrado = self::returnCdPerfilUsuario();

    if($snPerfilCadastrado){
      return array('error', 'Erro!','Perfil já cadastrado com este nome.');
      exit();
    }

    $sql = "INSERT INTO g_perfil_usuario (ds_perfil_usuario, cd_empresa, cd_usuario_registro) VALUES (UPPER(:dsPerfilUsuario), :cdEmpresa, :cdUsuario)";
    $stmt = $mysql->prepare($sql);
    $stmt->bindParam(":dsPerfilUsuario", $this->dsPerfilUsuario);
    $stmt->bindParam(":cdUsuario", $cdUsuario);
    $stmt->bindParam(":cdEmpresa", $cdEmpresa);
    $result = $stmt->execute();
    if ($result) {
      $num = $stmt->rowCount();
      if($num > 0){
        return $mysql->lastInsertId();
      }else{
        return array("info","Informativo!","Nenhum dado alterado.");
      }
    }else{
      $error   = $stmt->errorInfo();
      $dsError = $error[2];
      return array("error","Erro!","Descrição do erro: ".$dsError);
    }
  }

  public function returnCdPerfilUsuario(){
    $mysql = MysqlConexao::getInstance();

    $sql = "SELECT cd_perfil_usuario FROM g_perfil_usuario WHERE ds_perfil_usuario = :dsPerfilUsuario";
    $stmt = $mysql->prepare($sql);
    $stmt->bindParam(":dsPerfilUsuario", $this->dsPerfilUsuario);
    $result = $stmt->execute();
    if ($result) {
      $num = $stmt->rowCount();
      if($num > 0){
        return true;
      }else{
        return false;
      }
    }else{
      return false;
    }
  }

  public function listTable(){
    $mysql = MysqlConexao::getInstance();

    $cdEmpresa = $_SESSION['cdEmpresa'];

    $sql = "SELECT cd_perfil_usuario, ds_perfil_usuario, CASE sn_ativo WHEN 'S' THEN '<span class=\"col-green\">ATIVO</span>' ELSE '<span class=\"col-red\">INATIVO</span>' END AS ds_status, sn_ativo FROM g_perfil_usuario WHERE cd_empresa = :cdEmpresa ORDER BY ds_perfil_usuario;";
    $stmt = $mysql->prepare($sql);
    $stmt->bindParam(":cdEmpresa", $cdEmpresa);
    $result = $stmt->execute();
    if ($result) {
      $num = $stmt->rowCount();
      if($num > 0){
        while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
          echo '
          <tr>
          <td>'.$reg->cd_perfil_usuario.'</td>
          <td>
          <a data-toggle="modal" href="#modalFormAlterPerfilUsuario" onclick="preencheformAlterPerfilUser(\''.$reg->cd_perfil_usuario.'\',\''.str_replace("'", "\\'", $reg->ds_perfil_usuario).'\',\''.$reg->sn_ativo.'\')">'.$reg->ds_perfil_usuario.'</a>
          </td>
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

  public function listOption(){
    $mysql = MysqlConexao::getInstance();

    $cdEmpresa = $_SESSION['cdEmpresa'];

    $sql = "SELECT cd_perfil_usuario, ds_perfil_usuario FROM g_perfil_usuario WHERE cd_empresa = :cdEmpresa AND sn_ativo = 'S' ORDER BY ds_perfil_usuario;";
    $stmt = $mysql->prepare($sql);
    $stmt->bindParam(":cdEmpresa", $cdEmpresa);
    $result = $stmt->execute();
    if ($result) {
      $num = $stmt->rowCount();
      if($num > 0){
        while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
          echo '
          <option value="'.base64_encode($reg->cd_perfil_usuario).'">'.$reg->ds_perfil_usuario.'</option>
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

  public function markCheckTable($formId){
    $mysql = MysqlConexao::getInstance();

    $sql = "SELECT cd_permissao_sis FROM g_permissao_perfil WHERE cd_perfil_usuario = :cdPerfilUsuario";
    $stmt = $mysql->prepare($sql);
    $stmt->bindParam(":cdPerfilUsuario", $this->cdPerfilUsuario);
    $result = $stmt->execute();
    if ($result) {
      $num = $stmt->rowCount();
      if($num > 0){
        $arrayPerm = array();
        for ($i=0; $i < $num; $i++) {
          $reg = $stmt->fetch(PDO::FETCH_OBJ);
          $arrayPerm[$i] = $formId." #editCheckPermissao".md5($reg->cd_permissao_sis);
        }

        $permissoes = implode(', ', array_filter($arrayPerm));

        echo "$('".$permissoes."').prop('checked',true);";

      }else{

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

  public function Atualizar(){
    $mysql = MysqlConexao::getInstance();

    $sql = "UPDATE g_perfil_usuario SET ds_perfil_usuario = UPPER(:dsPerfilUsuario), sn_ativo = :snAtivo WHERE cd_perfil_usuario = :cdPerfilUsuario";
    $stmt = $mysql->prepare($sql);
    $stmt->bindParam(":cdPerfilUsuario", $this->cdPerfilUsuario);
    $stmt->bindParam(":dsPerfilUsuario", $this->dsPerfilUsuario);
    $stmt->bindParam(":snAtivo", $this->snAtivo);
    $result = $stmt->execute();
    if ($result) {
      $num = $stmt->rowCount();
      if($num > 0){
        return $num;
      }else{
        return $this->cdPerfilUsuario;
      }

    }else{
      $error   = $stmt->errorInfo();
      $dsError = $error[2];
      return array("error","Erro!","Descrição do erro: ".$dsError);
    }
  }
}
?>