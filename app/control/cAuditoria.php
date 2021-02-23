<?php

/**
 * Auditoria
 */
class cAuditoria extends mAuditoria
{
	private function registrar()
	{

		$mysql = mysqlConexao::getInstance();

		$sql = "INSERT INTO g_registro_auditoria (cd_modulo, tp_acao, nm_tabela, dados_anteriores, dados_atuais, cd_usuario_registro) VALUES (:cdModulo, :tpAcao, :nmTabela, :dadosAnteriores, :dadosAtuais)";

	}
}

?>