<?php

class cEmpreendimento extends mEmpreendimento {

    public function __construct($cdEmpreendimento=null, $nmEmpreendimento=null, $cdCliente=null, $cdPorteEmpreendimento = null, $cdTipografia = null, $cdPotencialPoluidor = null, $dsArea = null, $cdCep = null, $dsEndereco = null, $nmBairro = null, $nmCidade = null, $uf = null, $dsEmpreendimento = null, $cdEmpresa=null, $snAtivo=null) {

        parent::__construct($cdEmpreendimento, $nmEmpreendimento, $cdCliente, $cdPorteEmpreendimento, $cdTipografia, $cdPotencialPoluidor, $dsArea, $cdCep, $dsEndereco, $nmBairro, $nmCidade, $uf, $dsEmpreendimento, $cdEmpresa, $snAtivo);
    }

    public function returnSaldoEmpreendimento(){
        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT saldo FROM g_empreendimento WHERE cd_empreendimento = :cdEmpreendimento";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpreendimento", $this->cdEmpreendimento);
        $result = $stmt->execute();
        if ($result) {
            $reg = $stmt->fetch(PDO::FETCH_OBJ);

            return $reg->saldo;

        }else{
            return 0;
        }
    }

    public function returnCodigo($cdEmpreendimento="", $cdDepartamento=""){

        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT cd_empreendimento FROM g_empreendimento WHERE nm_empreendimento = UPPER(:nmEmpreendimento) ";
        $sql .= (!empty($cdEmpreendimento)) ? " AND cd_empreendimento NOT IN ($cdEmpreendimento)" : "";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":nmEmpreendimento", $this->nmEmpreendimento);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                $reg = $stmt->fetch(PDO::FETCH_OBJ);

                return $reg->cd_empreendimento;
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

        $sql = "SELECT
        e.cd_empreendimento,
        e.nm_empreendimento,
        e.cd_cliente,
        c.nm_cliente,
        e.ds_empreendimento,
        pp.cd_potencial_poluidor,
        pp.ds_potencial_poluidor,
        pe.cd_porte_empreendimento,
        pe.ds_porte_empreendimento,
        t.cd_tipografia,
        t.ds_tipografia,
        e.cd_cep,
        e.ds_endereco,
        e.nm_bairro,
        e.nm_cidade,
        e.uf,
        e.sn_ativo,
        CASE e.sn_ativo WHEN 'S' THEN '<span class=\"col-green\">ATIVO</span>'
        ELSE '<span class=\"col-red\">INATIVO</span>'
        END AS ds_status
        FROM
        g_cliente c,
        g_empreendimento e
        LEFT JOIN
        eco_potencial_poluidor pp ON e.cd_potencial_poluidor = pp.cd_potencial_poluidor
        LEFT JOIN
        eco_porte_empreendimento pe ON e.cd_porte_empreendimento = pe.cd_porte_empreendimento
        LEFT JOIN
        g_tipografia t ON e.cd_tipografia = t.cd_tipografia
        WHERE
        e.cd_cliente = c.cd_cliente
        ORDER BY e.nm_empreendimento ASC";
        $stmt = $mysql->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){

                    $reg->encode_cd_cliente = base64_encode($reg->cd_cliente);
                    $reg->encode_cd_tipografia = base64_encode($reg->cd_tipografia);
                    $reg->encode_cd_potencial_poluidor = base64_encode($reg->cd_potencial_poluidor);
                    $reg->encode_cd_porte_empreendimento = base64_encode($reg->cd_porte_empreendimento);

                    echo '
                    <tr>
                    <td>'.$reg->cd_empreendimento.'</td>
                    <td><a data-toggle="modal" href="#modalFormAlterEmpreendimento" onclick=\'preencheFormAlterEmpreendimento('.json_encode($reg).')\'>'.$reg->nm_empreendimento.'</a></td>
                    <td>'.$reg->nm_cliente.'</td>
                    <td>'.$reg->ds_potencial_poluidor.'</td>
                    <td>'.$reg->ds_tipografia.'</td>
                    <td>'.$reg->ds_porte_empreendimento.'</td>
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

    public function listOption($cdEmpreendimento = null){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "SELECT cd_empreendimento, nm_empreendimento FROM g_empreendimento WHERE sn_ativo = 'S' ";
        $sql .= (isset($this->cdCliente)) ? " AND cd_cliente = ".$this->cdCliente : "";
        echo $sql .= " ORDER BY nm_empreendimento";
        $stmt = $mysql->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){

                    $selected = ($cdEmpreendimento == $reg->cd_empreendimento) ? 'selected' : '';

                    echo '
                    <option value="'.base64_encode($reg->cd_empreendimento).'" '.$selected.'>'.$reg->nm_empreendimento.'</option>
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

        $sql = "SELECT nm_empreendimento FROM g_empreendimento WHERE cd_empreendimento = :cdEmpreendimento";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpreendimento", $this->cdEmpreendimento);
        $result = $stmt->execute();
        if ($result) {
            $reg = $stmt->fetch(PDO::FETCH_OBJ);

            $this->nmEmpreendimento = $reg->nm_empreendimento;
        }else{
            echo var_dump($stmt->errorInfo());
        }
    }

    public function Cadastro(){
        $mysql = MysqlConexao::getInstance();

        $sql = "INSERT INTO g_empreendimento (`nm_empreendimento`, `cd_cliente`, `cd_porte_empreendimento`, `cd_tipografia`, `cd_potencial_poluidor`, `ds_area`, `cd_cep`, `ds_endereco`, `nm_bairro`, `nm_cidade`, `uf`, `ds_empreendimento`) VALUES (UPPER(:nmEmpreendimento), :cdCliente, :cdPorteEmpreendimento, :cdTipografia, :cdPotencialPoluidor, UPPER(:dsArea), :cdCep, UPPER(:dsEndereco), UPPER(:nmBairro), UPPER(:nmCidade), UPPER(:uf), UPPER(:dsEmpreendimento))";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":nmEmpreendimento", $this->nmEmpreendimento);
        $stmt->bindParam(":cdCliente", $this->cdCliente);
        $stmt->bindParam(":cdPorteEmpreendimento", $this->cdPorteEmpreendimento);
        $stmt->bindParam(":cdTipografia", $this->cdTipografia);
        $stmt->bindParam(":cdPotencialPoluidor", $this->cdPotencialPoluidor);
        $stmt->bindParam(":dsArea", $this->dsArea);
        $stmt->bindParam(":cdCep", $this->cdCep);
        $stmt->bindParam(":dsEndereco", $this->dsEndereco);
        $stmt->bindParam(":nmBairro", $this->nmBairro);
        $stmt->bindParam(":nmCidade", $this->nmCidade);
        $stmt->bindParam(":uf", $this->uf);
        $stmt->bindParam(":dsEmpreendimento", $this->dsEmpreendimento);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                parent::setCdEmpreendimento($mysql->lastInsertId());
                return 'S';
            }else{
                return 'N';
            }

        }else{
            $erro = $stmt->errorInfo();
            return $erro[2];
        }
    }

    public function Alterar(){
        $mysql = MysqlConexao::getInstance();

        $sql = "UPDATE `g_empreendimento` SET `nm_empreendimento` = UPPER(:nmEmpreendimento), `cd_cliente` = :cdCliente, `cd_cliente` = :cdCliente, `cd_porte_empreendimento` = :cdPorteEmpreendimento, `cd_tipografia` = :cdTipografia, `cd_potencial_poluidor` = :cdPotencialPoluidor, `ds_area` = :dsArea, `cd_cep` = :cdCep, `ds_endereco` = :dsEndereco, `nm_bairro` = :nmBairro, `nm_cidade` = :nmCidade , `uf` = :uf, `ds_empreendimento` = :dsEmpreendimento, `sn_ativo` = :snAtivo WHERE `cd_empreendimento` = :cdEmpreendimento";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpreendimento", $this->cdEmpreendimento);
        $stmt->bindParam(":nmEmpreendimento", $this->nmEmpreendimento);
        $stmt->bindParam(":cdCliente", $this->cdCliente);
        $stmt->bindParam(":cdPorteEmpreendimento", $this->cdPorteEmpreendimento);
        $stmt->bindParam(":cdTipografia", $this->cdTipografia);
        $stmt->bindParam(":cdPotencialPoluidor", $this->cdPotencialPoluidor);
        $stmt->bindParam(":dsArea", $this->dsArea);
        $stmt->bindParam(":cdCep", $this->cdCep);
        $stmt->bindParam(":dsEndereco", $this->dsEndereco);
        $stmt->bindParam(":nmBairro", $this->nmBairro);
        $stmt->bindParam(":nmCidade", $this->nmCidade);
        $stmt->bindParam(":uf", $this->uf);
        $stmt->bindParam(":dsEmpreendimento", $this->dsEmpreendimento);
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

    public function AdicionarArea($cdTpArea, $vlArea){

        $mysql = MysqlConexao::getInstance();

        $cdUsuario = $_SESSION['cdUsuario'];

        $sql = "INSERT INTO `g_empreendimento_area` (cd_empreendimento, cd_tp_area, vl_area, cd_usuario_registro) VALUES (:cdEmpreendimento, :cdTpArea, UPPER(:vlArea), :cdUsuario)";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpreendimento", $this->cdEmpreendimento);
        $stmt->bindParam(":cdTpArea", $cdTpArea);
        $stmt->bindParam(":vlArea", $vlArea);
        $stmt->bindParam(":cdUsuario", $cdUsuario);
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

    public function AtualizarArea($cdEmpreendimentoArea, $cdTpArea, $vlArea){

        $mysql = MysqlConexao::getInstance();

        $cdUsuario = $_SESSION['cdUsuario'];

        $sql = "UPDATE `g_empreendimento_area` SET cd_tp_area = :cdTpArea, vl_area = :vlArea WHERE cd_empreendimento_area = :cdEmpreendimentoArea";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpreendimentoArea", $cdEmpreendimentoArea);
        $stmt->bindParam(":cdTpArea", $cdTpArea);
        $stmt->bindParam(":vlArea", $vlArea);
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

    public function ExcluirArea($cdEmpreendimentoArea){

        $mysql = MysqlConexao::getInstance();

        $cdUsuario = $_SESSION['cdUsuario'];

        $sql = "DELETE FROM `g_empreendimento_area` WHERE cd_empreendimento_area = :cdEmpreendimentoArea";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpreendimentoArea", $cdEmpreendimentoArea);
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

    public function ListarArea(){

        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT * FROM `g_empreendimento_area` WHERE cd_empreendimento = :cdEmpreendimento";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpreendimento", $this->cdEmpreendimento);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while ($reg = $stmt->fetch(PDO::FETCH_OBJ)) {

                    $tpArea = new cTpArea();

                    echo '
                    <tr>
                    <td>
                    <input type="hidden" name="cdEmpreendimentoArea[]" value="'.$reg->cd_empreendimento_area.'">
                    <div class="form-group">
                    <div class="form-line">
                    <select class="select2 form-control" name="cdTpArea[]">';

                    $tpArea->listOption($reg->cd_tp_area);

                    echo '
                    </select>
                    </div>
                    </div>
                    </td>
                    <td>
                    <div class="form-group">
                    <div class="form-line">
                    <input type="text" class="form-control" name="vlArea[]" placeholder="Ex: 1000" value="'.$reg->vl_area.'" />
                    </div>
                    </div>
                    </td>
                    <td class="text-center">
                    <a href="javascript:void(0)" onclick="removerArea(this)"><i class="material-icons col-red">delete</i>&nbsp;</a>
                    </td>
                    </tr>
                    ';
                }
            }else{
                echo '
                <tr>
                    <td colspan="5" class="text-center">Nenhuma área inserida</td>
                </tr>
                ';
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

    public function returnArrayAreas(){

        $mysql = MysqlConexao::getInstance();
        $data  = array();

        $sql = "SELECT * FROM `g_empreendimento_area` WHERE cd_empreendimento = :cdEmpreendimento";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpreendimento", $this->cdEmpreendimento);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){

                $a = 0;

                while ($reg = $stmt->fetch(PDO::FETCH_OBJ)) {

                    $data[$a]['cd_empreendimento_area']  = $reg->cd_empreendimento_area;
                    $data[$a]['cd_empreendimento']       = $reg->cd_empreendimento;
                    $data[$a]['cd_tp_area']              = $reg->cd_tp_area;
                    $data[$a]['vl_area']                 = $reg->vl_area;

                    $a++;
                }

            }
            return $data;
        }
    }

    /*Tipo do Empreendimento*/

    public static function listOptionTpEmpreendimento($cdTpEmpreendimento = null){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "SELECT cd_tp_empreendimento, ds_tp_empreendimento FROM g_tp_empreendimento WHERE sn_ativo = 'S' ORDER BY ds_tp_empreendimento";
        $stmt = $mysql->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){

                    $selected = ($cdTpEmpreendimento == $reg->cd_tp_empreendimento) ? 'selected' : '';

                    echo '
                    <option value="'.base64_encode($reg->cd_tp_empreendimento).'" '.$selected.'>'.$reg->ds_tp_empreendimento.'</option>
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

    public function cadRevisao(){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa       = $_SESSION['cdEmpresa'];
        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $sql = "INSERT INTO eco_rev_empreendimento (`cd_empreendimento`, `cd_tp_empreendimento`, `nr_dormitorios`, `nr_banheiros`, `nr_unidades`, `ds_tamanho_unidades`, `ds_abastecimento`, `sn_outorga_abastecimento`,`ds_tratamento_afluente`, `sn_outorga_tratamento`, `sn_terraplanagem`, `sn_suspensao_erradicacao`, `cd_usuario_registro`) VALUES (:cdEmpreendimento, :cdTpEmpreendimento, :nrDormitorios, :nrBanheiros, :nrUnidades, :dsTamanhoUnidades, :dsAbastecimentos, :snOutorgaAbastecimento, :dsTratamentoAfluente, :snOutorgaTratamentoAfluente, :snTerraplanagem, :snSuspensaoErradicacao, :cdUsuarioSessao)";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpreendimento", $this->cdEmpreendimento);
        $stmt->bindParam(":cdTpEmpreendimento", $this->cdTpEmpreendimento);
        $stmt->bindParam(":nrDormitorios", $this->qtdDormitorios);
        $stmt->bindParam(":nrBanheiros", $this->qtdBanheiros);
        $stmt->bindParam(":nrUnidades", $this->qtdUnidades);
        $stmt->bindParam(":dsTamanhoUnidades", $this->dsTamanhoUnidade);
        $stmt->bindParam(":dsAbastecimentos", $this->dsAbastecimento);
        $stmt->bindParam(":snOutorgaAbastecimento", $this->snOutorgaAbastecimento);
        $stmt->bindParam(":dsTratamentoAfluente", $this->dsTratamentoAfluentes);
        $stmt->bindParam(":snOutorgaTratamentoAfluente", $this->snOutorgaTratamentoAfluente);
        $stmt->bindParam(":snTerraplanagem", $this->snTerraplanagem);
        $stmt->bindParam(":snSuspensaoErradicacao", $this->snSuspensaoErradicacao);
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

    public function cadRevisaoTratamento(){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa       = $_SESSION['cdEmpresa'];
        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $sql = "INSERT INTO eco_rev_tratamento_afluente (`cd_rev_empreendimento`,`cd_tratamento_afluente`,`cd_usuario_registro`) VALUES (:cdRevEmpreendimento, :cdTratamentoAfluente, :cdUsuarioSessao)";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdRevEmpreendimento", $this->cdRevEmpreendimento);
        $stmt->bindParam(":cdTratamentoAfluente", $this->cdTratamentoAfluente);
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

    public function cadRevisaoAbastecimento(){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa       = $_SESSION['cdEmpresa'];
        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $sql = "INSERT INTO `eco_rev_abastecimento` (`cd_rev_empreendimento`,`cd_abastecimento`,`cd_usuario_registro`) VALUES (:cdRevEmpreendimento, :cdAbastecimento, :cdUsuarioSessao)";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdRevEmpreendimento", $this->cdRevEmpreendimento);
        $stmt->bindParam(":cdAbastecimento", $this->cdAbastecimento);
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

    public static function arrayRevisao($cdEmpreendimento=null, $cdRevEmpreendimento=null){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa       = $_SESSION['cdEmpresa'];
        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $data = array();

        $sql = "SELECT rev.cd_rev_empreendimento, rev.cd_tp_empreendimento, TO_BASE64(rev.cd_tp_empreendimento) as encode_cd_tp_empreendimento, rev.nr_dormitorios, rev.nr_banheiros, rev.nr_unidades, rev.ds_tamanho_unidades, rev.ds_abastecimento, rev.sn_outorga_abastecimento, rev.ds_tratamento_afluente, rev.sn_outorga_tratamento, rev.sn_terraplanagem, rev.sn_suspensao_erradicacao, u.nm_usuario, date_format(rev.dh_registro,'%d/%m/%Y %H:%i:%s') as dh_registro FROM eco_rev_empreendimento rev, g_usuario u WHERE rev.cd_usuario_registro = u.cd_usuario ";
        $sql .= !is_null($cdEmpreendimento) ? " AND rev.cd_empreendimento = :cdEmpreendimento " : null;
        $sql .= !is_null($cdRevEmpreendimento) ? " AND rev.cd_rev_empreendimento = :cdRevEmpreendimento " : null;
        $stmt = $mysql->prepare($sql);
        !is_null($cdEmpreendimento) ? $stmt->bindParam(":cdEmpreendimento", $cdEmpreendimento) : null;
        !is_null($cdRevEmpreendimento) ? $stmt->bindParam(":cdRevEmpreendimento", $cdRevEmpreendimento) : null;
        $result = $stmt->execute();
        if($result){

            $a = 0;

            while($reg  = $stmt->fetch(PDO::FETCH_OBJ)){

                $data[$a] = $reg;
                $data[$a]->abastecimento  = null;
                $data[$a]->tratamento     = null;

                $a++;
            }

            return $data;

        }else{
            $error = $stmt->errorInfo();
            return $error[2];
        }
    }

    public static function arrayAbastecimentoRevisao($cdRevEmpreendimento){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa       = $_SESSION['cdEmpresa'];
        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $data = array();

        $sql = "SELECT cd_abastecimento FROM eco_rev_abastecimento WHERE cd_rev_empreendimento = :cdRevEmpreendimento;";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdRevEmpreendimento", $cdRevEmpreendimento);
        $result = $stmt->execute();
        if($result){


            while($reg  = $stmt->fetch(PDO::FETCH_OBJ)){
                $data[] = base64_encode($reg->cd_abastecimento);
            }

            return $data;

        }else{
            $error = $stmt->errorInfo();
            return $error[2];
        }
    }

    public static function arrayTratamentoRevisao($cdRevEmpreendimento){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa       = $_SESSION['cdEmpresa'];
        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $data = array();

        $sql = "SELECT cd_tratamento_afluente FROM eco_rev_tratamento_afluente WHERE cd_rev_empreendimento = :cdRevEmpreendimento;";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdRevEmpreendimento", $cdRevEmpreendimento);
        $result = $stmt->execute();
        if($result){


            while($reg  = $stmt->fetch(PDO::FETCH_OBJ)){
                $data[] = base64_encode($reg->cd_tratamento_afluente);
            }

            return $data;

        }else{
            $error = $stmt->errorInfo();
            return $error[2];
        }
    }

    public function cadTratamento(){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa       = $_SESSION['cdEmpresa'];
        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $sql = "INSERT INTO g_empreendimento_tratamento_afluente (`cd_empreendimento`,`cd_tratamento_afluente`,`cd_usuario_registro`) VALUES (:cdEmpreendimento, :cdTratamentoAfluente, :cdUsuarioSessao)";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpreendimento", $this->cdEmpreendimento);
        $stmt->bindParam(":cdTratamentoAfluente", $this->cdTratamentoAfluente);
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

    public function cadAbastecimento(){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa       = $_SESSION['cdEmpresa'];
        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $sql = "INSERT INTO `g_empreendimento_abastecimento` (`cd_empreendimento`,`cd_abastecimento`,`cd_usuario_registro`) VALUES (:cdEmpreendimento, :cdAbastecimento, :cdUsuarioSessao)";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpreendimento", $this->cdEmpreendimento);
        $stmt->bindParam(":cdAbastecimento", $this->cdAbastecimento);
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

    public function addContato($nmContato, $nrTelefone, $nmCargo, $nmDepartamento)
    {
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa       = $_SESSION['cdEmpresa'];
        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $data = array();

        $sql = "INSERT INTO g_empreendimento_contato (cd_empreendimento, nm_contato, nr_telefone, nm_cargo, nm_departamento) VALUES (:cdEmpreendimento, :nmContato, :nrTelefone, :nmCargo, :nmDepartamento);";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpreendimento", $this->cdEmpreendimento);
        $stmt->bindParam(":nmContato", $nmContato);
        $stmt->bindParam(":nrTelefone", $nrTelefone);
        $stmt->bindParam(":nmCargo", $nmCargo);
        $stmt->bindParam(":nmDepartamento", $nmDepartamento);
        $result = $stmt->execute();
        if($result){
            return $mysql->lastInsertId();
        }else{
            $error = $stmt->errorInfo();
            return $error[2];
        }
    }

    public function updContato($cdEmpreendimentoContato, $nmContato, $nrTelefone, $nmCargo, $nmDepartamento)
    {
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa       = $_SESSION['cdEmpresa'];
        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $data = array();

        $sql = "UPDATE g_empreendimento_contato SET nm_contato = :nmContato, nr_telefone = :nrTelefone, nm_cargo = :nmCargo, nm_departamento = :nmDepartamento WHERE cd_empreendimento_contato = :cdEmpreendimentoContato;";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpreendimentoContato", $cdEmpreendimentoContato);
        $stmt->bindParam(":nmContato", $nmContato);
        $stmt->bindParam(":nrTelefone", $nrTelefone);
        $stmt->bindParam(":nmCargo", $nmCargo);
        $stmt->bindParam(":nmDepartamento", $nmDepartamento);
        $result = $stmt->execute();
        if($result){
            return $stmt->rowCount();
        }else{
            $error = $stmt->errorInfo();
            return $error[2];
        }
    }

    public function removerContato($cdEmpreendimentoContato)
    {
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa       = $_SESSION['cdEmpresa'];
        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $data = array();

        $sql = "DELETE FROM g_empreendimento_contato WHERE cd_empreendimento_contato = :cdEmpreendimentoContato;";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpreendimentoContato", $cdEmpreendimentoContato);
        $result = $stmt->execute();
        if($result){
            return $stmt->rowCount();
        }else{
            $error = $stmt->errorInfo();
            return $error[2];
        }
    }

    public static function getContatos($cdEmpreendimento)
    {
        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT * FROM g_empreendimento_contato WHERE cd_empreendimento = :cdEmpreendimento;";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpreendimento", $cdEmpreendimento);
        $result = $stmt->execute();
        if($result){
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }else{
            $error = $stmt->errorInfo();
            return $error[2];
        }
    }

    public function addTipoRevisao($cdTpRevisao, $vlRevisao)
    {
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa       = $_SESSION['cdEmpresa'];
        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $data = array();

        $sql = "INSERT INTO g_empreendimento_revisao (cd_empreendimento, cd_tp_revisao, valor) VALUES (:cdEmpreendimento, :cdTpRevisao, :vlRevisao);";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpreendimento", $this->cdEmpreendimento);
        $stmt->bindParam(":cdTpRevisao", $cdTpRevisao);
        $stmt->bindParam(":vlRevisao", $vlRevisao);
        $result = $stmt->execute();
        if($result){
            return $mysql->lastInsertId();
        }else{
            $error = $stmt->errorInfo();
            return $error[2];
        }
    }

    public function updTipoRevisao($cdEmpreendimentoRevisao, $cdTpRevisao, $vlRevisao)
    {
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa       = $_SESSION['cdEmpresa'];
        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $data = array();

        $sql = "UPDATE g_empreendimento_revisao SET cd_tp_revisao = :cdTpRevisao, valor = :vlRevisao WHERE cd_empreendimento_revisao = :cdEmpreendimentoRevisao;";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpreendimentoRevisao", $cdEmpreendimentoRevisao);
        $stmt->bindParam(":cdTpRevisao", $cdTpRevisao);
        $stmt->bindParam(":vlRevisao", $vlRevisao);
        $result = $stmt->execute();
        if($result){
            return $stmt->rowCount();
        }else{
            $error = $stmt->errorInfo();
            return $error[2];
        }
    }

    public function removerTipoRevisao($cdEmpreendimentoRevisao)
    {
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa       = $_SESSION['cdEmpresa'];
        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $data = array();

        $sql = "DELETE FROM g_empreendimento_revisao WHERE cd_empreendimento_revisao = :cdEmpreendimentoRevisao;";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpreendimentoRevisao", $cdEmpreendimentoRevisao);
        $result = $stmt->execute();
        if($result){
            return $stmt->rowCount();
        }else{
            $error = $stmt->errorInfo();
            return $error[2];
        }
    }

    public static function getTiposRevisao($cdEmpreendimento)
    {
        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT * FROM g_empreendimento_revisao WHERE cd_empreendimento = :cdEmpreendimento;";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpreendimento", $cdEmpreendimento);
        $result = $stmt->execute();
        if($result){
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }else{
            $error = $stmt->errorInfo();
            return $error[2];
        }
    }

    public function ListarTpRevisao(){

        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT * FROM `g_empreendimento_revisao` WHERE cd_empreendimento = :cdEmpreendimento";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpreendimento", $this->cdEmpreendimento);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while ($reg = $stmt->fetch(PDO::FETCH_OBJ)) {

                    $tpRevisao = new cTpRevisao();

                    echo '
                    <tr>
                    <td>
                    <input type="hidden" name="cdEmpreendimentoRevisao[]" value="'.$reg->cd_empreendimento_revisao.'"/>
                    <div class="form-group">
                    <div class="form-line">
                    <select class="form-control" name="cdTpRevisao[]">
                    <option value=""></option>
                    ';

                    $tpRevisao->listOption($reg->cd_tp_revisao);

                    echo '
                    </select>
                    </div>
                    </div>
                    </td>
                    <td>
                    <div class="form-group">
                    <div class="form-line">
                    <input type="text" class="form-control" name="vlRevisao[]" placeholder="Ex: 1" value="'.$reg->valor.'" />
                    </div>
                    </div>
                    </td>
                    <td class="text-center">
                    <a href="javascript:void(0)" onclick="removerRevisao(this)">
                    <i class="material-icons col-red">delete</i>&nbsp;</a>
                    </td>
                    </tr>
                    ';
                }
            }else{
                echo '
                <tr>
                    <td colspan="5" class="text-center">Nenhuma revisão inserida</td>
                </tr>
                ';
            }

        }else{
            $error = $stmt->errorInfo();

            echo $error[2];
        }

    }

    public function getByCliente($cdCliente){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "SELECT cd_empreendimento, nm_empreendimento FROM `g_empreendimento` WHERE sn_ativo = 'S' AND cd_cliente = :cdCliente ORDER BY nm_empreendimento";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdCliente", $cdCliente);
        $result = $stmt->execute();
        if ($result) {
            
            return $stmt->fetchAll(PDO::FETCH_OBJ);

        }else{
            //GERAR LOG
            ob_start();
            var_dump($stmt->errorInfo());
            $dsError = ob_get_clean();
            regLog($dsError, basename( __FILE__ ));
        }
    }


    public static function setHistoryName($cdEmpreendimento, $nmEmpreendimentoAntigo)
    {
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];
        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $sql = "INSERT INTO `g_empreendimento_nome` (cd_empreendimento, nm_empreendimento_novo, nm_empreendimento_antigo, cd_usuario_registro) SELECT :cdEmpreendimento, nm_empreendimento, :nmEmpreendimentoAntigo, :cdUsuarioSessao FROM `g_empreendimento` WHERE cd_empreendimento = :cdEmpreendimento";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpreendimento", $cdEmpreendimento);
        $stmt->bindParam(":nmEmpreendimentoAntigo", $nmEmpreendimentoAntigo);
        $stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            
            return ($num > 0) ? $mysql->lastInsertId() : null;
        } else {
            //GERAR LOG
            ob_start();
            var_dump($stmt->errorInfo());
            $dsError = ob_get_clean();
            regLog($dsError, basename(__FILE__));
        }
    }

    public static function getHistoryName($cdEmpreendimento)
    {
        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT en.*, u.nm_usuario as nm_usuario_registro FROM `g_empreendimento_nome` en, `g_usuario` u WHERE en.cd_empreendimento = :cdEmpreendimento AND en.cd_usuario_registro = u.cd_usuario";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpreendimento", $cdEmpreendimento);
        $result = $stmt->execute();
        if ($result) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } else {
            //GERAR LOG
            ob_start();
            var_dump($stmt->errorInfo());
            $dsError = ob_get_clean();
            regLog($dsError, basename(__FILE__));
        }
    }
}