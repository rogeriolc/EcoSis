<?php

class cPropostaLicencaAmb extends mPropostaLicencaAmb{

    public function __construct($cdPropostaLicenca=null, $cdCliente=null, $cdEmpreendimento=null, $tpStatus=null, $vlNegociado=null, $vlPago=null, $dtPrevConclusao=null, $dsObservacao=null)
    {
        parent::__construct($cdPropostaLicenca, $cdCliente, $cdEmpreendimento, $tpStatus, $vlNegociado, $vlPago, $dtPrevConclusao, $dsObservacao);
    }

    public function returnCodigo($cdPropostaLicenca=""){

        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT cd_proposta_licenca FROM eco_proposta_licenca WHERE ds_tp_licenca_ambiental = UPPER(:dsTpLicencaAmbiental) ";
        $sql .= (!empty($cdPropostaLicenca)) ? " AND cd_proposta_licenca NOT IN ($cdPropostaLicenca)" : "";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":dsTpLicencaAmbiental", $this->dsTpLicencaAmbiental);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                $reg = $stmt->fetch(PDO::FETCH_OBJ);

                return $reg->cd_proposta_licenca;
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

    public function iniciarProposta(){

        $mysql = MysqlConexao::getInstance();

        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $sql = "INSERT INTO eco_proposta_licenca (cd_usuario_registro) VALUES (:cdUsuarioSessao)";
        $stmt = $mysql->prepare($sql);
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

    public function getMaxNrProtocolo()
    {
        $mysql = MysqlConexao::getInstance();

        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $sql = "SELECT MAX(CAST(nr_protocolo AS UNSIGNED)) as nr_protocolo FROM eco_proposta_licenca WHERE competencia = date_format(now(), '%Y')";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":competencia", $competencia);
        $stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
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

        $sql = "SELECT MAX(CAST(nr_alteracao AS UNSIGNED)) as nr_alteracao FROM eco_proposta_licenca WHERE competencia = date_format('%Y', now()) AND cd_proposta_pai = :cdPropostaPai";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":competencia", $competencia);
        $stmt->bindParam(":cdPropostaPai", $this->cdPropostaPai);
        $stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
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

    public function listTable(){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "SELECT cd_proposta_licenca, c.nm_cliente, emp.nm_empreendimento, p.vl_negociado, p.vl_pago, CASE tp_status WHEN 'E' THEN 'EM NEGOCIAÇÃO' WHEN 'F' THEN 'FECHADA' WHEN 'C' THEN 'CANCELADA' ELSE '' END AS ds_status FROM eco_proposta_licenca p, g_cliente c, g_empreendimento emp WHERE p.cd_cliente = c.cd_cliente AND c.cd_cliente = emp.cd_cliente AND p.cd_empreendimento = emp.cd_empreendimento";
        $stmt = $mysql->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
                    echo '
                    <tr>
                        <td>'.$reg->cd_proposta_licenca.'</td>
                        <td><a data-toggle="modal" href="#modalFormAlterProposta" onclick="preencheFormAlterProposta(\''.base64_encode($reg->cd_proposta_licenca).'\')">'.$reg->nm_cliente.'</a></td>
                        <td>'.$reg->nm_empreendimento.'</td>
                        <td>'.$reg->vl_negociado.'</td>
                        <td>'.$reg->vl_pago.'</td>
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

        $sql = "SELECT cd_proposta_licenca, ds_tp_licenca_ambiental FROM eco_proposta_licenca WHERE sn_ativo = 'S' ORDER BY ds_tp_licenca_ambiental";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpresa", $cdEmpresa);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
                    echo '
                    <option value="'.base64_encode($reg->cd_proposta_licenca).'">'.$reg->ds_tp_licenca_ambiental.'</option>
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

    public function Cadastro(){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa       = $_SESSION['cdEmpresa'];
        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $nrProtocolo     = self::getMaxNrProtocolo();
        $nrAlteracao     = self::getMaxNrAlteracao();

        if(is_null($nrProtocolo)) {
            $nrProtocolo = 1;
        } else {
            $nrProtocolo++;
        }

        if(is_null($nrAlteracao)) {
            $nrAlteracao = 1;
        } else {
            $nrAlteracao ++;
        }
        
        $competencia  = date("Y");

        $sql = (!is_null($this->cdPropostaPai)) ? "INSERT INTO eco_proposta_licenca (`cd_proposta_pai`, `nr_protocolo`, `nr_alteracao`, `competencia`, `cd_cliente`, `cd_empreendimento`, `tp_status`, `vl_negociado`, `vl_pago`, `dt_prev_conclusao`, `ds_observacao`, cd_empresa, `cd_usuario_registro`) VALUES (:cdPropostaPai, :nrProtocolo, :nrAlteracao, :competencia, :cdCliente, :cdEmpreendimento, :tpStatus, :vlNegociado, :vlPago, :dtPrevConclusao, :dsObservacao, :cdEmpresa, :cdUsuarioSessao)" : "INSERT INTO eco_proposta_licenca (`cd_proposta_pai`, `nr_protocolo`, `competencia`, `cd_cliente`, `cd_empreendimento`, `tp_status`, `vl_negociado`, `vl_pago`, `dt_prev_conclusao`, `ds_observacao`, cd_empresa, `cd_usuario_registro`) VALUES (:cdPropostaPai, :nrProtocolo, :competencia, :cdCliente, :cdEmpreendimento, :tpStatus, :vlNegociado, :vlPago, :dtPrevConclusao, :dsObservacao, :cdEmpresa, :cdUsuarioSessao)";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdPropostaPai", $this->cdPropostaPai);
        $stmt->bindParam(":competencia", $competencia);
        $stmt->bindParam(":nrProtocolo", $nrProtocolo);
        (!is_null($this->cdPropostaPai)) ? $stmt->bindParam(":nrAlteracao", $nrAlteracao) : null;
        $stmt->bindParam(":cdCliente", $this->cdCliente);
        $stmt->bindParam(":cdEmpreendimento", $this->cdEmpreendimento);
        $stmt->bindParam(":tpStatus", $this->tpStatus);
        $stmt->bindParam(":vlNegociado", $this->vlNegociado);
        $stmt->bindParam(":vlPago", $this->vlPago);
        $stmt->bindParam(":dtPrevConclusao", $this->dtPrevConclusao);
        $stmt->bindParam(":dsObservacao", $this->dsObservacao);
        $stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
        $stmt->bindParam(":cdEmpresa", $cdEmpresa);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                return $mysql->lastInsertId();
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

    public function CadastroItem($cdTpAtividade, $tpAtividade, $dtPrevEntrega, $nrProposta, $vlNegociado, $vlPago){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa       = $_SESSION['cdEmpresa'];
        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        // (!) IMPLEMENTAR -> NOT EXISTS

        $sql = "INSERT INTO eco_itproposta_licenca (`cd_proposta_licenca`, `cd_tp_atividade`, `tp_atividade`, `dt_prev_entrega`, `nr_proposta`, `vl_negociado`, `vl_pago`) VALUES (:cdPropostaLicenca, :cdTpAtividade, :tpAtividade, :dtPrevEntrega, :nrProposta, :vlNegociado, :vlPago)";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdPropostaLicenca", $this->cdPropostaLicenca);
        $stmt->bindParam(":cdTpAtividade", $cdTpAtividade);
        $stmt->bindParam(":tpAtividade", $tpAtividade);
        $stmt->bindParam(":nrProposta", $nrProposta);
        $stmt->bindParam(":dtPrevEntrega", $dtPrevEntrega);
        $stmt->bindParam(":vlNegociado", $vlNegociado);
        $stmt->bindParam(":vlPago", $vlPago);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                return intval($mysql->lastInsertId());
            }else{
                return $num;
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

    public function Alterar(){
        $mysql = MysqlConexao::getInstance();

        $sql = "UPDATE eco_proposta_licenca SET ds_tp_licenca_ambiental = UPPER(:dsTpLicencaAmbiental), sn_ativo = :snAtivo WHERE cd_proposta_licenca = :cdPropostaLicenca";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdPropostaLicenca", $this->cdPropostaLicenca);
        $stmt->bindParam(":dsTpLicencaAmbiental", $this->dsTpLicencaAmbiental);
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

    public function AlterarSimples(){
        $mysql = MysqlConexao::getInstance();

        $sql = "UPDATE eco_proposta_licenca SET cd_cliente = :cdCliente, cd_empreendimento = :cdEmpreendimento, dt_prev_conclusao = :dtPrevConclusao, ds_observacao = :dsObservacao WHERE cd_proposta_licenca = :cdPropostaLicenca";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdPropostaLicenca", $this->cdPropostaLicenca);
        $stmt->bindParam(":cdCliente", $this->cdCliente);
        $stmt->bindParam(":cdEmpreendimento", $this->cdEmpreendimento);
        $stmt->bindParam(":dtPrevConclusao", $this->dtPrevConclusao);
        $stmt->bindParam(":dsObservacao", $this->dsObservacao);
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

    public function AtualizarTotalNegociado($total){
        $mysql = MysqlConexao::getInstance();

        $sql = "UPDATE eco_proposta_licenca SET vl_negociado = :totalNegociado WHERE cd_proposta_licenca = :cdPropostaLicenca";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdPropostaLicenca", $this->cdPropostaLicenca);
        $stmt->bindParam(":totalNegociado", $total);
        $result = $stmt->execute();
        if ($result) {

            return $num = $stmt->rowCount();

        }else{

            $erro = $stmt->errorInfo();

            return $erro[2];
        }
    }

    public function AtualizarTotalPago($total){
        $mysql = MysqlConexao::getInstance();

        $sql = "UPDATE eco_proposta_licenca SET vl_pago = :totalPago WHERE cd_proposta_licenca = :cdPropostaLicenca";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdPropostaLicenca", $this->cdPropostaLicenca);
        $stmt->bindParam(":totalPago", $total);
        $result = $stmt->execute();
        if ($result) {

            return $num = $stmt->rowCount();

        }else{

            $erro = $stmt->errorInfo();

            return $erro[2];
        }
    }

    public function AtualizarTotalDtPrevista($dtPrevConclusao){
        $mysql = MysqlConexao::getInstance();

        $sql = "UPDATE eco_proposta_licenca SET dt_prev_conclusao = :dtPrevConclusao WHERE cd_proposta_licenca = :cdPropostaLicenca";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdPropostaLicenca", $this->cdPropostaLicenca);
        $stmt->bindParam(":dtPrevConclusao", $dtPrevConclusao);
        $result = $stmt->execute();
        if ($result) {

            return $num = $stmt->rowCount();

        }else{

            $erro = $stmt->errorInfo();

            return $erro[2];
        }
    }

    public function Dados(){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "SELECT cd_proposta_pai, nr_protocolo, nr_alteracao, competencia, cd_cliente, cd_empreendimento, tp_status, vl_negociado, vl_pago, dt_prev_conclusao, ds_observacao FROM eco_proposta_licenca WHERE cd_proposta_licenca = :cdPropostaLicenca";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdPropostaLicenca", $this->cdPropostaLicenca);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
                   $this->cdPropostaPai         = $reg->cd_proposta_pai;
                   $this->dsProtocolo           = (is_null($reg->nr_alteracao)) ? $reg->nr_protocolo."/".$reg->competencia : $reg->nr_protocolo."/".$reg->competencia."/ALT-".$reg->nr_alteracao;
                   $this->cdCliente             = $reg->cd_cliente;
                   $this->cdEmpreendimento      = $reg->cd_empreendimento;
                   $this->tpStatus              = $reg->tp_status;
                   $this->vlNegociado           = $reg->vl_negociado;
                   $this->vlPago                = $reg->vl_pago;
                   $this->dtPrevConclusao       = $reg->dt_prev_conclusao;
                   $this->dsObservacao          = $reg->ds_observacao;
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

    public function Fechar(){
        $mysql = MysqlConexao::getInstance();

        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $sql = "UPDATE eco_proposta_licenca SET dh_fechamento = NOW(), cd_usuario_fechamento = :cdUsuarioSessao, tp_status = 'F' WHERE cd_proposta_licenca = :cdPropostaLicenca";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdPropostaLicenca", $this->cdPropostaLicenca);
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

    public function Remover(){
        $mysql = MysqlConexao::getInstance();

        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $sql = "DELETE FROM eco_proposta_licenca WHERE cd_proposta_licenca = :cdPropostaLicenca";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdPropostaLicenca", $this->cdPropostaLicenca);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            return $num;

        }else{
            $erro = $stmt->errorInfo();
            return $erro[2];
        }
    }

    public function DadosItensProposta()
    {
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "SELECT itp.cd_itproposta_licenca, itp.cd_itproposta_pai, itp.cd_tp_atividade, a.ds_tp_atividade, itp.tp_atividade, itp.dt_prev_entrega, itp.vl_negociado, itp.vl_pago FROM eco_itproposta_licenca itp, eco_tp_atividade a WHERE itp.cd_proposta_licenca = :cdPropostaLicenca AND itp.cd_tp_atividade = a.cd_tp_atividade";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdPropostaLicenca", $this->cdPropostaLicenca);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                return $reg = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    public function DadosItensPropostaPai()
    {
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "SELECT itp.cd_itproposta_licenca, itp.cd_itproposta_pai, itp.cd_tp_atividade, a.ds_tp_atividade, itp.tp_atividade, itp.dt_prev_entrega, itp.vl_negociado, itp.vl_pago FROM eco_itproposta_licenca itp, eco_tp_atividade a WHERE itp.cd_proposta_licenca = :cdPropostaLicenca AND itp.cd_tp_atividade = a.cd_tp_atividade";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdPropostaLicenca", $this->cdPropostaPai);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                return $reg = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }else{
                return 0;
            }

        }else{
            //GERAR LOG
            ob_start();
            var_dump($stmt->errorInfo());
            $dsError = ob_get_clean();
            regLog($dsError, basename( __FILE__ ));
        }    }


    public static function getItemProposta($cdItProposta)
    {
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "SELECT itp.cd_itproposta_licenca, itp.cd_itproposta_pai, itp.cd_tp_atividade, a.ds_tp_atividade, itp.tp_atividade, itp.dt_prev_entrega, itp.vl_negociado, itp.vl_pago FROM eco_itproposta_licenca itp, eco_tp_atividade a WHERE itp.cd_itproposta_licenca = :cdItProposta AND itp.cd_tp_atividade = a.cd_tp_atividade";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdItProposta", $cdItProposta);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                return $reg = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    public function removerItemProposta($cdItPropostaLicenca)
    {
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "DELETE FROM eco_itproposta_licenca WHERE cd_itproposta_licenca = :cdItPropostaLicenca AND NOT EXISTS (SELECT cd_proposta_licenca FROM eco_proposta_licenca p WHERE tp_status = 'F' AND cd_proposta_licenca = :cdPropostaLicenca)";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdItPropostaLicenca", $cdItPropostaLicenca);
        $stmt->bindParam(":cdPropostaLicenca", $this->cdPropostaLicenca);
        $result = $stmt->execute();
        if ($result) {

            return $num = $stmt->rowCount();

        }else{
            $error = $stmt->errorInfo();
            return $error[2];
        }
    }

    public function AtualizarItem($cdItPropostaLicenca, $dtPrevEntrega, $nrProposta, $vlNegociado, $vlPago)
    {
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "UPDATE eco_itproposta_licenca SET dt_prev_entrega = :dtPrevEntrega, vl_negociado = :vlNegociado, vl_pago = :vlPago WHERE cd_itproposta_licenca = :cdItPropostaLicenca";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdItPropostaLicenca", $cdItPropostaLicenca);
        $stmt->bindParam(":dtPrevEntrega", $dtPrevEntrega);
        $stmt->bindParam(":vlNegociado", $vlNegociado);
        $stmt->bindParam(":vlPago", $vlPago);
        $result = $stmt->execute();
        if ($result) {

            return $num = $stmt->rowCount();

        }else{
            $error = $stmt->errorInfo();
            return $error[2];
        }
    }

    public function historicoVersoes()
    {
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "
        SELECT  p.cd_proposta_licenca,
        p.cd_proposta_pai,
        p.nr_protocolo,
        p.nr_alteracao,
        p.competencia,
        p.ds_observacao,
        c.nm_cliente,
        e.nm_empreendimento,
        p.tp_status,
        u.nm_usuario,
        p.dh_registro,
        p.vl_negociado,
        p.ds_observacao,
        (SELECT count(*) FROM eco_itproposta_licenca itp, eco_tp_atividade ta WHERE itp.cd_tp_atividade = ta.cd_tp_atividade AND itp.cd_proposta_licenca = p.cd_proposta_licenca AND ta.cd_cat_tp_atividade = 1) as qtd_assessoria,
        (SELECT count(*) FROM eco_itproposta_licenca itp, eco_tp_atividade ta WHERE itp.cd_tp_atividade = ta.cd_tp_atividade AND itp.cd_proposta_licenca = p.cd_proposta_licenca AND ta.cd_cat_tp_atividade = 2) as qtd_consultoria
        FROM    eco_proposta_licenca p, g_cliente c, g_empreendimento e, g_usuario u,
        (
            SELECT  ifnull(p.cd_proposta_pai, p.cd_proposta_licenca) as cd_propposta
            FROM    eco_proposta_licenca p
            WHERE   p.cd_proposta_licenca = :cdPropostaLicenca
        ) pp
        WHERE   (p.cd_proposta_licenca   = pp.cd_propposta
        OR      p.cd_proposta_pai       = pp.cd_propposta)
        AND     p.cd_cliente            = c.cd_cliente
        AND     p.cd_empreendimento     = e.cd_empreendimento
        AND     p.cd_usuario_registro   = u.cd_usuario
        ORDER BY 1 DESC
        ";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdPropostaLicenca", $this->cdPropostaLicenca);
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

    public function getServico($cdPropostaLicenca)
    {
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "
        SELECT  s.cd_servico
        FROM    eco_servico s
        WHERE   s.cd_proposta_servico = :cdPropostaLicenca
        ";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdPropostaLicenca", $cdPropostaLicenca);
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

    public static function getTodasPai()
    {
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "
        SELECT  p.cd_proposta_licenca,
        p.cd_proposta_pai,
        p.nr_protocolo,
        p.nr_alteracao,
        p.competencia,
        c.nm_cliente,
        e.nm_empreendimento,
        p.tp_status,
        u.nm_usuario,
        p.dh_registro,
        p.vl_negociado,
        p.dt_prev_conclusao,
        p.ds_observacao,
        (SELECT max(cd_proposta_licenca) FROM eco_proposta_licenca WHERE cd_proposta_pai = p.cd_proposta_licenca) as cd_proposta_atual,
        (SELECT count(*) FROM eco_itproposta_licenca itp, eco_tp_atividade ta WHERE itp.cd_tp_atividade = ta.cd_tp_atividade AND itp.cd_proposta_licenca = p.cd_proposta_licenca AND ta.cd_cat_tp_atividade = 1) as qtd_assessoria,
        (SELECT count(*) FROM eco_itproposta_licenca itp, eco_tp_atividade ta WHERE itp.cd_tp_atividade = ta.cd_tp_atividade AND itp.cd_proposta_licenca = p.cd_proposta_licenca AND ta.cd_cat_tp_atividade = 2) as qtd_consultoria
        FROM    eco_proposta_licenca p, g_cliente c, g_empreendimento e, g_usuario u
        WHERE   p.cd_cliente            = c.cd_cliente
        AND     p.cd_empreendimento     = e.cd_empreendimento
        AND     p.cd_usuario_registro   = u.cd_usuario
        AND     p.cd_proposta_pai       IS NULL
        ORDER BY 1 DESC
        ";
        $stmt = $mysql->prepare($sql);
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

    public static function getVersaoAtual($cdPropostaLicenca)
    {
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "
        SELECT  MAX(p.cd_proposta_licenca) AS cd_proposta_licenca,
        p.cd_proposta_pai,
        p.nr_protocolo,
        p.nr_alteracao,
        p.competencia,
        c.nm_cliente,
        e.nm_empreendimento,
        p.tp_status,
        u.nm_usuario,
        p.dh_registro,
        p.vl_negociado,
        p.dt_prev_conclusao,
        p.ds_observacao,
        (SELECT max(cd_proposta_licenca) FROM eco_proposta_licenca WHERE cd_proposta_pai = p.cd_proposta_licenca) as cd_proposta_atual,
        (SELECT count(*) FROM eco_itproposta_licenca itp, eco_tp_atividade ta WHERE itp.cd_tp_atividade = ta.cd_tp_atividade AND itp.cd_proposta_licenca = p.cd_proposta_licenca AND ta.cd_cat_tp_atividade = 1) as qtd_assessoria,
        (SELECT count(*) FROM eco_itproposta_licenca itp, eco_tp_atividade ta WHERE itp.cd_tp_atividade = ta.cd_tp_atividade AND itp.cd_proposta_licenca = p.cd_proposta_licenca AND ta.cd_cat_tp_atividade = 2) as qtd_consultoria
        FROM    eco_proposta_licenca p, g_cliente c, g_empreendimento e, g_usuario u
        WHERE   p.cd_cliente            = c.cd_cliente
        AND     p.cd_empreendimento     = e.cd_empreendimento
        AND     p.cd_usuario_registro   = u.cd_usuario
        AND     p.cd_proposta_pai       = :cdPropostaLicenca
        ORDER BY 1 DESC
        ";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdPropostaLicenca", $cdPropostaLicenca);
        $result = $stmt->execute();
        if ($result) {

            $num = $stmt->rowCount();

            if ($num > 0) {
                $response = $stmt->fetchAll(PDO::FETCH_OBJ);

                return $response[0];
            } else {
                return $cdPropostaLicenca;
            }

        }else{
            $error = $stmt->errorInfo();
            return $error[2];
        }
    }

    public static function getPropostaPai($cdItProposta){
        $mysql = MysqlConexao::getInstance();

        $cdUsuarioSessao = $_SESSION['cdUsuario'];
        $cdEmpresa       = $_SESSION['cdEmpresa'];

        $sql = "SELECT cd_itproposta_pai FROM `eco_itproposta_licenca` WHERE cd_itproposta_licenca = :cdItProposta;";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdItProposta", $cdItProposta);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();

            if($num > 0){
                $reg = $stmt->fetch(PDO::FETCH_OBJ);
                return $reg->cd_itproposta_pai;
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

    //Realiza o vínculo com o item de proposta pai
    public function vincularItemPropostaPai($cdItProposta, $cdItPropostaPai){
        $mysql = MysqlConexao::getInstance();

        $cdUsuarioSessao = $_SESSION['cdUsuario'];
        $cdEmpresa       = $_SESSION['cdEmpresa'];

        $sql = "UPDATE `eco_itproposta_licenca` SET cd_itproposta_pai = :cdItPropostaPai WHERE cd_itproposta_licenca = :cdItProposta;";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdItProposta", $cdItProposta);
        $stmt->bindParam(":cdItPropostaPai", $cdItPropostaPai);
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

    public function cancelarProposta()
    {
        $mysql = MysqlConexao::getInstance();

        $cdUsuarioSessao = $_SESSION['cdUsuario'];
        $cdEmpresa       = $_SESSION['cdEmpresa'];

        $sql = "UPDATE `eco_proposta_licenca` SET tp_status = 'C', cd_usuario_cancelamento = :cdUsuarioCancelamento, dh_cancelamento = now() WHERE cd_proposta_licenca = :cdProposta;";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdProposta", $this->cdPropostaLicenca);
        $stmt->bindParam(":cdUsuarioCancelamento", $cdUsuarioSessao);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();

            if($num > 0){
                return true;
            }else{
                return false;
            }

        }else{
            $erro = $stmt->errorInfo();

            return $erro[2];
        }
    }

    public static function addCliente($cdProposta)
    {
        $mysql = MysqlConexao::getInstance();

        $cdUsuarioSessao = $_SESSION['cdUsuario'];
        $cdEmpresa       = $_SESSION['cdEmpresa'];

        $sql = "INSERT INTO `eco_empreendimento_proposta` (cd_proposta_licenca, cd_usuario_registro) VALUES (:cdProposta, :cdUsuarioSessao);";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdProposta", $cdProposta);
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
            $erro = $stmt->errorInfo();

            return $erro[2];
        }
    }
}

?>