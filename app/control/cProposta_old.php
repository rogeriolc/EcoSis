<?php

class cProposta extends mPropostaLicencaAmb
{
    public function __construct($cdPropostaLicenca=null, $tpStatus=null, $vlPago=null, $dtPrevConclusao=null, $dsObservacao=null)
    {
        $this->cdPropostaLicenca 	= $cdPropostaLicenca;
		$this->tpStatus 			= $tpStatus;
		$this->vlPago 		        = $vlPago;
        $this->dtPrevConclusao      = $dtPrevConclusao;
		$this->dsObservacao 		= $dsObservacao;
    }

    public static function getData($cdProposta)
    {
        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT * FROM eco_proposta WHERE cd_proposta = :cdProposta";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdProposta", $cdProposta);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                return $stmt->fetchAll(PDO::FETCH_OBJ);
            }else{
                return null;
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

    public function cadastrar()
    {
        $mysql = MysqlConexao::getInstance();

        $nrProtocolo     = self::getMaxNrProtocolo();
        $nrAlteracao     = self::getMaxNrAlteracao();

        if(is_null($nrProtocolo)) {
            $nrProtocolo = 1;
        } else {
            $nrProtocolo++;
        }

        if($nrAlteracao == 0) {
            $nrAlteracao = 0;
        } else {
            $nrAlteracao++;
        }
        
        $competencia  = date("Y");

        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $sql = "INSERT INTO eco_proposta (cd_proposta_pai, competencia, nr_protocolo, nr_alteracao, dt_prev_conclusao, ds_observacao, valor, cd_usuario_registro) VALUES (:cdPropostaPai, :competencia, :nrProtocolo, :nrAlteracao, :dtPrevConclusao, :dsObservacao, :valor, :cdUsuarioSessao)";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdPropostaPai", $this->cdPropostaPai);
        $stmt->bindParam(":competencia", $competencia);
        $stmt->bindParam(":nrProtocolo", $nrProtocolo);
        $stmt->bindParam(":nrAlteracao", $nrAlteracao);
        $stmt->bindParam(":dtPrevConclusao", $this->dtPrevConclusao);
        $stmt->bindParam(":dsObservacao", $this->dsObservacao);
        $stmt->bindParam(":valor", $this->vlPago);
        $stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                return intval($mysql->lastInsertId());
            }else{
                return 0;
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

    public function novaVersao()
    {
        $mysql = MysqlConexao::getInstance();

        $nrAlteracao     = self::getMaxNrAlteracao();

        if(is_null($nrAlteracao)) {
            $nrAlteracao = 0;
        } else {
            $nrAlteracao++;
        }
        
        $competencia  = date("Y");

        $sql = "UPDATE eco_proposta SET nr_alteracao = :nrAlteracao WHERE cd_proposta = :cdProposta";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdProposta", $this->cdPropostaLicenca);
        $stmt->bindParam(":nrAlteracao", $nrAlteracao);
        $result = $stmt->execute();
        if ($result) {
           return $stmt->rowCount();
        }else{
            $erro = $stmt->errorInfo();
            return $erro[2];
        }
    }

    public function guardarVersao()
    {
        $mysql = MysqlConexao::getInstance();
        
        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $sql = "INSERT INTO eco_proposta (cd_proposta_pai, nr_protocolo, nr_alteracao, competencia, dt_prev_conclusao, ds_observacao, valor, tp_status, cd_usuario_registro) SELECT cd_proposta, nr_protocolo, nr_alteracao, competencia, dt_prev_conclusao, ds_observacao, valor, tp_status, :cdUsuarioSessao FROM eco_proposta WHERE cd_proposta = :cdProposta";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdProposta", $this->cdPropostaLicenca);
        $stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
        $result = $stmt->execute();
        if ($result) {
           $num = $stmt->rowCount();
            if($num > 0){
                return intval($mysql->lastInsertId());
            }else{
                return 0;
            }
        }else{
            $erro = $stmt->errorInfo();
            return $erro[2];
        }
    }

    public function copiarClientes($cdPropostaOrigem, $cdPropostaDestino)
    {
        $mysql = MysqlConexao::getInstance();
        
        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $sql = "INSERT INTO eco_proposta_cliente (cd_proposta, cd_cliente, cd_empreendimento, cd_cliente_vinculo, cd_usuario_registro) SELECT :cdPropostaDestino, cd_cliente, cd_empreendimento, cd_cliente_vinculo, :cdUsuarioSessao FROM eco_proposta_cliente WHERE cd_proposta = :cdPropostaOrigem";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdPropostaOrigem", $cdPropostaOrigem);
        $stmt->bindParam(":cdPropostaDestino", $cdPropostaDestino);
        $stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
        $result = $stmt->execute();
        if ($result) {
           $num = $stmt->rowCount();
            if($num > 0){
                $cdPropostaClienteDestino = intval($mysql->lastInsertId());

                $clientesAtividade = self::getClientesByProposta($cdPropostaOrigem);

                foreach ($clientesAtividade as $key => $clienteAtividade) {
                    $cdPropostaClienteOrigem = $clienteAtividade->cd_proposta_cliente;
                    self::copiarAtividades($cdPropostaClienteOrigem, $cdPropostaClienteDestino);
                }

            }else{
                return 0;
            }
        }else{
            $erro = $stmt->errorInfo();
            return $erro[2];
        }
    }

    public function copiarAtividades($cdPropostaClienteOrigem, $cdPropostaClienteDestino)
    {
        $mysql = MysqlConexao::getInstance();
        
        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $sql = "INSERT INTO eco_proposta_atividade (cd_proposta_cliente, cd_tp_atividade, tp_atividade, dt_prev_entrega, valor, cd_usuario_registro) SELECT :cdPropostaClienteDestino, cd_tp_atividade, tp_atividade, dt_prev_entrega, valor, :cdUsuarioSessao FROM eco_proposta_atividade WHERE cd_proposta_cliente = :cdPropostaClienteOrigem";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdPropostaClienteOrigem", $cdPropostaClienteOrigem);
        $stmt->bindParam(":cdPropostaClienteDestino", $cdPropostaClienteDestino);
        $stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
        $result = $stmt->execute();
        if ($result) {
           $num = $stmt->rowCount();
            if($num > 0){
                return intval($mysql->lastInsertId());
            }else{
                return 0;
            }
        }else{
            $erro = $stmt->errorInfo();
            return $erro[2];
        }
    }

    public function fechar()
    {
        $mysql = MysqlConexao::getInstance();

        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $sql = "UPDATE eco_proposta SET dh_fechamento = NOW(), cd_usuario_fechamento = :cdUsuarioSessao, tp_status = 'F' WHERE cd_proposta = :cdProposta";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdProposta", $this->cdPropostaLicenca);
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

            return $erro[2];
        }
    }

    public function abrir()
    {
        $mysql = MysqlConexao::getInstance();

        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $sql = "UPDATE eco_proposta SET dh_reabertura = NOW(), cd_usuario_reabertura = :cdUsuarioSessao, tp_status = 'E' WHERE cd_proposta = :cdProposta";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdProposta", $this->cdPropostaLicenca);
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

            return $erro[2];
        }
    }

    public function alterar()
    {
        $mysql = MysqlConexao::getInstance();
        
        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $sql = "UPDATE eco_proposta SET dt_prev_conclusao = :dtPrevConclusao, ds_observacao = :dsObservacao, valor = :valor WHERE cd_proposta = :cdProposta";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdProposta", $this->cdPropostaLicenca);
        $stmt->bindParam(":dtPrevConclusao", $this->dtPrevConclusao);
        $stmt->bindParam(":dsObservacao", $this->dsObservacao);
        $stmt->bindParam(":valor", $this->vlPago);
        $result = $stmt->execute();
        if ($result) {
           return $stmt->rowCount();
        }else{
            $erro = $stmt->errorInfo();
            return $erro[2];
        }
    }

    public static function getAllPais()
    {
        $mysql = MysqlConexao::getInstance();

        $sql = "
        SELECT  *,
                (SELECT nr_alteracao FROM eco_proposta WHERE cd_proposta = a.cd_proposta_atual) as nr_alteracao_atual
        FROM    (
            SELECT 	p.cd_proposta,
                    p.cd_proposta_pai,
                    p.nr_protocolo,
                    p.nr_alteracao,
                    p.competencia,
                    p.tp_status,
                    u.nm_usuario,
                    p.dh_registro,
                    p.valor,
                    p.dt_prev_conclusao,
                    p.ds_observacao,
                    (SELECT max(cd_proposta) FROM eco_proposta WHERE cd_proposta_pai = p.cd_proposta) as cd_proposta_atual,
                    (SELECT count(*) FROM eco_proposta_atividade pa, eco_proposta_cliente pc, eco_tp_atividade ta WHERE pa.cd_tp_atividade = ta.cd_tp_atividade AND pa.cd_proposta_cliente = pc.cd_proposta_cliente AND pc.cd_proposta = p.cd_proposta AND ta.cd_cat_tp_atividade = 1) as qtd_assessoria,
                    (SELECT count(*) FROM eco_proposta_atividade pa, eco_proposta_cliente pc, eco_tp_atividade ta WHERE pa.cd_tp_atividade = ta.cd_tp_atividade AND pa.cd_proposta_cliente = pc.cd_proposta_cliente AND pc.cd_proposta = p.cd_proposta AND ta.cd_cat_tp_atividade = 2)  as qtd_consultoria,
                    (SELECT nm_usuario FROM g_usuario WHERE cd_usuario = p.cd_usuario_fechamento) as nm_usuario_fechamento,
                    dh_fechamento,
                    (SELECT nm_usuario FROM g_usuario WHERE cd_usuario = p.cd_usuario_cancelamento) as nm_usuario_cancelamento,
                    dh_cancelamento
            FROM 	eco_proposta p,
                    g_usuario u
            WHERE	p.cd_usuario_registro   = u.cd_usuario
            AND		p.cd_proposta_pai		IS NULL
        ) a
        ORDER BY a.cd_proposta DESC
        ";
        $stmt = $mysql->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }else{
            $erro = $stmt->errorInfo();
            return $erro[2];
        }
    }

    public static function getHistoricoVersoes($cdProposta)
    {
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "
        SELECT  p.cd_proposta,
                p.cd_proposta_pai,
                p.nr_protocolo,
                p.nr_alteracao,
                p.competencia,
                p.tp_status,
                u.nm_usuario,
                p.dh_registro,
                p.dt_prev_conclusao,
                p.ds_observacao,
                (SELECT sum(valor) FROM eco_proposta_atividade pa, eco_proposta_cliente pc WHERE pc.cd_proposta = p.cd_proposta AND pa.cd_proposta_cliente = pc.cd_proposta_cliente) as valor,
                (SELECT count(*) FROM eco_proposta_atividade pa, eco_proposta_cliente pc, eco_tp_atividade ta WHERE pa.cd_tp_atividade = ta.cd_tp_atividade AND pa.cd_proposta_cliente = pc.cd_proposta_cliente AND pc.cd_proposta = p.cd_proposta AND ta.cd_cat_tp_atividade = 1) as qtd_assessoria,
                (SELECT count(*) FROM eco_proposta_atividade pa, eco_proposta_cliente pc, eco_tp_atividade ta WHERE pa.cd_tp_atividade = ta.cd_tp_atividade AND pa.cd_proposta_cliente = pc.cd_proposta_cliente AND pc.cd_proposta = p.cd_proposta AND ta.cd_cat_tp_atividade = 2)  as qtd_consultoria
        FROM 	eco_proposta p,
				g_usuario u,
                (
                    SELECT  ifnull(p.cd_proposta_pai, p.cd_proposta) as cd_proposta
                    FROM    eco_proposta p
                    WHERE   p.cd_proposta = :cdProposta
                ) pp
                WHERE   (p.cd_proposta = pp.cd_proposta OR p.cd_proposta_pai = pp.cd_proposta)
                AND     p.cd_usuario_registro   = u.cd_usuario
        -- ORDER BY 1 DESC
        ORDER BY nr_alteracao DESC
        ";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdProposta", $cdProposta);
        $result = $stmt->execute();
        if ($result) {

            $num = $stmt->rowCount();

            if ($num > 0) {
                return $stmt->fetchAll(PDO::FETCH_OBJ);
            } else {
                return false;
            }

        }else{
            $error = $stmt->errorInfo();
            return $error[2];
        }
    }

    public static function getClientesByProposta($cdProposta)
    {
        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT *, (SELECT nm_cliente FROM g_cliente WHERE cd_cliente = eco_proposta_cliente.cd_cliente) as nm_cliente, (SELECT nm_empreendimento FROM g_empreendimento WHERE cd_empreendimento = eco_proposta_cliente.cd_empreendimento) as nm_empreendimento, (SELECT nm_empreendimento FROM g_empreendimento WHERE cd_empreendimento = eco_proposta_cliente.cd_cliente_vinculo) as nm_cliente_vinculo FROM eco_proposta_cliente WHERE cd_proposta = :cdProposta";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdProposta", $cdProposta);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                return $stmt->fetchAll(PDO::FETCH_OBJ);
            }else{
                return null;
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
    
    public static function getItensProposta($cdProposta, $cdPropostaCliente = null)
    {
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "
        SELECT 	pc.cd_proposta_cliente,
                pc.cd_proposta_cliente_pai,
                pa.cd_proposta_atividade,
                pa.cd_tp_atividade,
                ta.ds_tp_atividade,
                c.nm_cliente,
                pa.valor,
                pa.dt_prev_entrega,
                CASE ta.cd_cat_tp_atividade WHEN 1 THEN 'A' WHEN 2 THEN 'C' ELSE NULL END AS tp_atividade,
                0 as total
        FROM 	eco_proposta_cliente pc,
                eco_proposta_atividade pa,
                eco_tp_atividade ta,
                g_cliente c
        WHERE	pc.cd_proposta 			= :cdProposta
        AND		pc.cd_proposta_cliente 	= pa.cd_proposta_cliente
        AND		pa.cd_tp_atividade 		= ta.cd_tp_atividade
        AND     pc.cd_cliente           = c.cd_cliente
        ";

        $sql .= (!is_null($cdPropostaCliente)) ? " AND pc.cd_proposta_cliente = :cdPropostaCliente " : null;

        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdProposta", $cdProposta);
        (!is_null($cdPropostaCliente)) ? $stmt->bindParam(":cdPropostaCliente", $cdPropostaCliente) : null;
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                return $reg = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }else{
                return array();
            }

        }else{
            //GERAR LOG
            ob_start();
            var_dump($stmt->errorInfo());
            $dsError = ob_get_clean();
            regLog($dsError, basename( __FILE__ ));
        }
    } 

    public static function getServico($cdProposta, $cdCliente = null) {
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $filterCliente = (!is_null($cdCliente)) ? " AND s.cd_cliente = $cdCliente" : null;

        $sql = "
        SELECT  s.cd_servico
        FROM    eco_servico s
        WHERE   s.cd_proposta_servico = :cdProposta
        $filterCliente
        ";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdProposta", $cdProposta);
        $result = $stmt->execute();
        if ($result) {

            $num = $stmt->rowCount();

            if ($num > 0) {
                $response =  $stmt->fetchAll(PDO::FETCH_OBJ);
                return $response[0]->cd_servico;
            } else {
                return false;
            }

        }else{
            $error = $stmt->errorInfo();
            return $error[2];
        }
    }

    public static function getServicoByPropostaCliente($cdPropostaCliente) {
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "
        SELECT  s.cd_servico
        FROM    eco_servico s
        WHERE   s.cd_proposta_cliente = :cdPropostaCliente
        ";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdPropostaCliente", $cdPropostaCliente);
        $result = $stmt->execute();
        if ($result) {

            $num = $stmt->rowCount();

            if ($num > 0) {
                $response =  $stmt->fetchAll(PDO::FETCH_OBJ);
                return $response[0]->cd_servico;
            } else {
                return false;
            }

        }else{
            $error = $stmt->errorInfo();
            return $error[2];
        }
    }

    public function getMaxNrProtocolo()
    {
        $mysql = MysqlConexao::getInstance();

        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $sql = "SELECT MAX(CAST(nr_protocolo AS UNSIGNED)) as nr_protocolo FROM eco_proposta WHERE competencia = date_format(now(), '%Y')";
        $stmt = $mysql->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                $reg = $stmt->fetch(PDO::FETCH_OBJ);
                return intval($reg->nr_protocolo);
            }else{
                return 0;
            }

        }else{
            $erro = $stmt->errorInfo();

            return $erro[2];
        }
    }

    public function getMaxNrAlteracao()
    {
        $mysql = MysqlConexao::getInstance();

        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        // $sql = "SELECT MAX(CAST(nr_alteracao AS UNSIGNED)) as nr_alteracao FROM eco_proposta WHERE competencia = date_format(now(), '%Y') AND cd_proposta_pai = :cdPropostaPai";
        $sql = "SELECT IFNULL(MAX(CAST(nr_alteracao AS UNSIGNED)),0) as nr_alteracao FROM eco_proposta WHERE competencia = date_format(now(), '%Y') AND cd_proposta = :cdProposta";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdProposta", $this->cdPropostaLicenca);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                $reg = $stmt->fetch(PDO::FETCH_OBJ);
                return intval($reg->nr_alteracao);
            }else{
                return 0;
            }

        }else{
            $erro = $stmt->errorInfo();

            return $erro[2];
        }
    }

    public static function adicionarCliente($cdProposta, $cdCliente, $cdEmpreendimento, $cdClienteVinculo = null, $cdPropostaClientePai = null)
    {
        $mysql = MysqlConexao::getInstance();

        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $sql = "INSERT INTO eco_proposta_cliente (cd_proposta_cliente_pai, cd_proposta, cd_cliente, cd_empreendimento, cd_cliente_vinculo, cd_usuario_registro) VALUES (:cdPropostaClientePai, :cdProposta, :cdCliente, :cdEmpreendimento, :cdClienteVinculo, :cdUsuarioSessao)";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdPropostaClientePai", $cdPropostaClientePai);
        $stmt->bindParam(":cdProposta", $cdProposta);
        $stmt->bindParam(":cdCliente", $cdCliente);
        $stmt->bindParam(":cdEmpreendimento", $cdEmpreendimento);
        $stmt->bindParam(":cdClienteVinculo", $cdClienteVinculo);
        $stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                return $mysql->lastInsertId();
            }else{
                return 0;
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

    public static function atualizarCliente($cdPropostaCliente, $cdCliente, $cdEmpreendimento, $cdClienteVinculo = null)
    {
        $mysql = MysqlConexao::getInstance();

        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $sql = "UPDATE eco_proposta_cliente SET cd_cliente = :cdCliente, cd_empreendimento = :cdEmpreendimento, cd_cliente_vinculo = :cdClienteVinculo WHERE cd_proposta_cliente = :cdPropostaCliente";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdPropostaCliente", $cdPropostaCliente);
        $stmt->bindParam(":cdCliente", $cdCliente);
        $stmt->bindParam(":cdEmpreendimento", $cdEmpreendimento);
        $stmt->bindParam(":cdClienteVinculo", $cdClienteVinculo);
        $result = $stmt->execute();
        if ($result) {
            return $stmt->rowCount();
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

    public static function adicionarItem($cdPropostaCliente, $cdTpAtividade, $vlAtividade, $dtPrevEntrega)
    {
        $mysql = MysqlConexao::getInstance();

        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $sql = "INSERT INTO eco_proposta_atividade (cd_proposta_cliente, cd_tp_atividade, valor, dt_prev_entrega, cd_usuario_registro) VALUES (:cdPropostaCliente, :cdTpAtividade, :vlAtividade, :dtPrevEntrega, :cdUsuarioSessao)";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdPropostaCliente", $cdPropostaCliente);
        $stmt->bindParam(":cdTpAtividade", $cdTpAtividade);
        $stmt->bindParam(":vlAtividade", $vlAtividade);
        $stmt->bindParam(":dtPrevEntrega", $dtPrevEntrega);
        $stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                return $mysql->lastInsertId();
            }else{
                return 0;
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

    public static function atualizarItem($cdPropostaAtividade, $cdTpAtividade, $vlAtividade, $dtPrevEntrega)
    {
        $mysql = MysqlConexao::getInstance();

        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $sql = "UPDATE eco_proposta_atividade SET cd_tp_atividade = :cdTpAtividade, valor = :vlAtividade, dt_prev_entrega = :dtPrevEntrega WHERE cd_proposta_atividade = :cdPropostaAtividade";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdPropostaAtividade", $cdPropostaAtividade);
        $stmt->bindParam(":cdTpAtividade", $cdTpAtividade);
        $stmt->bindParam(":vlAtividade", $vlAtividade);
        $stmt->bindParam(":dtPrevEntrega", $dtPrevEntrega);
        $result = $stmt->execute();
        if ($result) {
            return $stmt->rowCount();
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

    public static function deletarCliente($cdPropostaCliente) {
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "DELETE FROM eco_proposta_cliente WHERE cd_proposta_cliente = :cdPropostaCliente";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdPropostaCliente", $cdPropostaCliente);
        $result = $stmt->execute();
        if ($result) {

            return $stmt->rowCount();

        }else{
            $error = $stmt->errorInfo();
            return $error[2];
        }
    }

    public static function deletarAtividade($cdPropostaAtividade) {
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "DELETE FROM eco_proposta_atividade WHERE cd_proposta_atividade = :cdPropostaAtividade";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdPropostaAtividade", $cdPropostaAtividade);
        $result = $stmt->execute();
        if ($result) {

            return $stmt->rowCount();

        }else{
            $error = $stmt->errorInfo();
            return $error[2];
        }
    }

    public static function getIconStatus($tpStatus) {
        switch ($tpStatus) {
            case 'C':
                return '<i class="material-icons col-red" title="Cancelada" style="position: relative; top: 8px">block</i>';
                break;
            case 'E':
                return '<i class="material-icons col-orange" title="Em negociação" style="position: relative; top: 8px">schedule</i>';
                break;
            case 'F':
                return '<i class="material-icons col-green" title="Aprovada" style="position: relative; top: 8px">check</i>';
                break;
            
            default:
                return $tpStatus;
                break;
        }
    }

    public static function getDescriptionStatus($tpStatus) {
        switch ($tpStatus) {
            case 'C':
                return 'Cancelada';
                break;
            case 'E':
                return 'Em negociação';
                break;
            case 'F':
                return 'Aprovada';
                break;
            
            default:
                return $tpStatus;
                break;
        }
    }
}
