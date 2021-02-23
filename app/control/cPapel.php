<?php
/**
* Papel
*/
class cPapel extends mPapel
{

	public function Cadastrar(){
		$mysql = MysqlConexao::getInstance();

		$cdUsuario = $_SESSION['cdUsuario'];
		$cdEmpresa = $_SESSION['cdEmpresa'];

		$snPapelCadastrado = self::returnCdPapel();

		if($snPapelCadastrado){
			return array('error', 'Erro!','Já existe um papel cadastrado com este nome.');
			exit();
		}

		$sql = "INSERT INTO g_papel (ds_papel, cd_empresa, cd_usuario_registro) VALUES (UPPER(:dsPapel), :cdEmpresa, :cdUsuario)";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":dsPapel", $this->dsPapel);
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

	public function Atualizar(){
		$mysql = MysqlConexao::getInstance();

		$sql = "UPDATE g_papel SET ds_papel = :dsPapel, sn_ativo = :snAtivo WHERE cd_papel = :cdPapel";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdPapel", $this->cdPapel);
		$stmt->bindParam(":dsPapel", $this->dsPapel);
		$stmt->bindParam(":snAtivo", $this->snAtivo);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				return $num;
			}else{
				return $this->cdPapel;
			}

		}else{
			$error   = $stmt->errorInfo();
			$dsError = $error[2];
			return array("error","Erro!","Descrição do erro: ".$dsError);
		}
	}

	public function returnCdPapel(){
		$mysql = MysqlConexao::getInstance();

		$sql = "SELECT cd_papel FROM g_papel WHERE ds_papel = :dsPapel";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":dsPapel", $this->dsPapel);
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

		$sql = "SELECT cd_papel, ds_papel, CASE sn_ativo WHEN 'S' THEN '<span class=\"col-green\">ATIVO</span>' ELSE '<span class=\"col-red\">INATIVO</span>' END AS ds_status, sn_ativo FROM g_papel WHERE cd_empresa = :cdEmpresa ORDER BY ds_papel;";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdEmpresa", $cdEmpresa);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
					echo '
					<tr>
					<td>'.$reg->cd_papel.'</td>
					<td>
					<a data-toggle="modal" href="#modalFormAlterPapel" onclick="preencheformAlterPapel(\''.$reg->cd_papel.'\',\''.str_replace("'", "\\'", $reg->ds_papel).'\',\''.$reg->sn_ativo.'\')">'.$reg->ds_papel.'</a>
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

		$sql = "SELECT cd_papel, ds_papel FROM g_papel WHERE cd_empresa = :cdEmpresa AND sn_ativo = 'S' ORDER BY ds_papel;";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdEmpresa", $cdEmpresa);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
					echo '
					<option value="'.base64_encode($reg->cd_papel).'">'.$reg->ds_papel.'</option>
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

		$sql = "SELECT cd_pagina FROM g_papel_pagina WHERE cd_papel = :cdPapel";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdPapel", $this->cdPapel);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				$arrayPag = array();
				for ($i=0; $i < $num; $i++) {
					$reg = $stmt->fetch(PDO::FETCH_OBJ);
					$arrayPag[$i] = $formId." #editCheckPagina".md5($reg->cd_pagina);
				}

				$paginas = implode(', ', array_filter($arrayPag));

				echo "$('".$paginas."').prop('checked',true);";

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

	public static function addPapelUsuario($cdPapel, $cdUsuario){

		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$sql = "INSERT INTO g_papel_usuario (cd_papel, cd_usuario, cd_usuario_registro) SELECT :cdPapel, :cdUsuario, :cdUsuarioSessao FROM DUAL WHERE NOT EXISTS (SELECT cd_papel FROM g_papel_usuario WHERE cd_usuario = :cdUsuario AND cd_papel = :cdPapel); INSERT INTO g_pagina_usuario (cd_pagina, cd_usuario, cd_usuario_registro) SELECT DISTINCT cd_pagina_principal, :cdUsuario, :cdUsuarioSessao FROM g_papel_usuario pu, g_papel_pagina pp, g_pagina p WHERE pu.cd_usuario = :cdUsuario AND pp.cd_papel = pu.cd_papel AND p.cd_pagina = pp.cd_pagina AND p.sn_ativo = 'S' AND p.cd_pagina_principal IS NOT NULL AND NOT EXISTS (SELECT cd_pagina FROM g_pagina_usuario WHERE cd_usuario = :cdUsuario AND cd_pagina = p.cd_pagina_principal) UNION ALL SELECT DISTINCT p.cd_pagina, :cdUsuario, :cdUsuarioSessao FROM g_papel_usuario pu, g_papel_pagina pp, g_pagina p WHERE pu.cd_usuario = :cdUsuario AND pp.cd_papel = pu.cd_papel AND p.cd_pagina = pp.cd_pagina AND p.sn_ativo = 'S' AND NOT EXISTS (SELECT cd_pagina FROM g_pagina_usuario WHERE cd_usuario = :cdUsuario AND cd_pagina = p.cd_pagina); INSERT INTO g_modulo_usuario (cd_modulo, cd_usuario, cd_usuario_registro) SELECT DISTINCT cd_modulo, :cdUsuario, :cdUsuarioSessao FROM g_papel_usuario pu, g_papel_pagina pp, g_pagina p WHERE pu.cd_usuario = :cdUsuario AND pp.cd_papel = pu.cd_papel AND p.cd_pagina = pp.cd_pagina AND NOT EXISTS (SELECT cd_modulo FROM g_modulo_usuario WHERE cd_usuario = :cdUsuario AND cd_modulo = p.cd_modulo)";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdPapel", $cdPapel);
		$stmt->bindParam(":cdUsuario", $cdUsuario);
		$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();

			return intval($num);

		}else{

			$error = $stmt->errorInfo();
			$dsError = $error[2];

			return $dsError;
		}
	}
}
?>