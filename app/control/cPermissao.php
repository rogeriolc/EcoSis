<?php

/**
 * PermissaoSis
 */

class cPermissao
{

	public static function validarPermissao($cdPermissao, $alerta = true)
	{

		$notificacao = new Notificacao;

		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$permissaoConcedida = null;

		$sql = "SELECT cd_permissao_sis FROM g_permissao_perfil pp, g_usuario u WHERE pp.cd_perfil_usuario = u.cd_perfil_usuario AND pp.cd_permissao_sis = :cdPermissao AND u.cd_usuario = :cdUsuarioSessao";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdPermissao", $cdPermissao);
		$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if ($num > 0) {
				$reg = $stmt->fetch(PDO::FETCH_OBJ);

				$permissaoConcedida = intval($reg->cd_permissao_sis);
			} else {
				$permissaoConcedida = intval(0);
			}
		} else {
			$error = $stmt->errorInfo();
			$permissaoConcedida = $error[2];
		}

		switch (gettype($permissaoConcedida)) {
			case 'integer':

				if ($permissaoConcedida > 0) {

					return true;
				} else {
					$dsMensagemBloqueio = cPermissao::mensagemBloqueio($cdPermissao);

					if ($alerta) {

						$notificacao->viewSwalNotificacao("Erro!", $dsMensagemBloqueio, "single", "error");
					}

					return false;

					exit();
				}

				break;

			default:

				return false;

				break;
		}
	}

	public static function mensagemBloqueio($cdPermissao)
	{

		$mysql = MysqlConexao::getInstance();

		$cdUsuario = $_SESSION['cdUsuario'];

		$sql = "SELECT ds_mensagem_bloqueio FROM g_permissao_sis WHERE cd_permissao_sis = :cdPermissao";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdPermissao", $cdPermissao);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if ($num > 0) {
				$reg = $stmt->fetch(PDO::FETCH_OBJ);

				return $reg->ds_mensagem_bloqueio;
			} else {
				return intval(0);
			}
		} else {
			$error = $stmt->errorInfo();
			return $error[2];
		}
	}

	public static function getUsersByPermissao($cdPermissao)
	{

		$mysql = MysqlConexao::getInstance();

		$sql = "SELECT u.cd_usuario, u.nm_usuario, u.email FROM g_permissao_perfil pp, g_permissao_sis p, g_usuario u WHERE pp.cd_permissao_sis = p.cd_permissao_sis AND u.cd_perfil_usuario = pp.cd_perfil_usuario AND p.cd_permissao_sis = :cdPermissao AND u.cd_usuario = 1";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdPermissao", $cdPermissao);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if ($num > 0) {
				return $stmt->fetchAll(PDO::FETCH_OBJ);
			} else {
				return false;
			}
		} else {
			$error = $stmt->errorInfo();
			return $error[2];
		}
	}
}
