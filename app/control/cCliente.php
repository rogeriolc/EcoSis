<?php

class cCliente extends mCliente {

    public function __construct($cdCliente = null, $nmPessoa = null, $tpPessoa = null, $nrRg = null, $nrCpf = null, $nrInscricaoEstadual = null, $nrInscricaoMunicipal = null, $dsCtf = null, $dsEmail=null, $dsSite=null, $dsEndereco = null, $nmBairro = null, $nmCidade = null, $cdCep = null, $uf = null, $nrTelefone = null, $nrCelular = null, $snAtivo = null){

        //pega o construtor mCliente
        parent::__construct($cdCliente, $nmPessoa, $tpPessoa, $nrRg, $nrCpf, $nrInscricaoEstadual, $nrInscricaoMunicipal, $dsCtf, $dsEmail, $dsSite, $dsEndereco, $nmBairro, $nmCidade, $cdCep, $uf, $nrTelefone, $nrCelular, $snAtivo);
    }


    public function returnCodigo($cdCliente=""){

        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "SELECT cd_cliente FROM g_cliente WHERE cpf_cnpj = :cpfCnpj ";
        $sql .= (!empty($cdCliente)) ? " AND cd_cliente NOT IN ($cdCliente)" : "";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cpfCnpj", $this->nrCpf);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                $reg = $stmt->fetch(PDO::FETCH_OBJ);

                return $reg->cd_cliente;
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

    public function listOption($cdCliente = null){

        //LEMBRAR DE COLOCAR MULTI EMPRESA

        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT cd_cliente, nm_cliente FROM g_cliente ORDER BY nm_cliente ASC";
        $stmt = $mysql->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            while ($reg = $stmt->fetch(PDO::FETCH_OBJ)) {

                $selected = ($cdCliente == $reg->cd_cliente) ? 'selected' : '';

                echo '<option value="'.base64_encode($reg->cd_cliente).'" '.$selected.'>'.$reg->nm_cliente.'</option>';
            }
        }else{
            echo '<option>'.var_dump($stmt->errorInfo()).'</option>';
        }
    }

    public static function staticListOption($cdCliente=null){

        //LEMBRAR DE COLOCAR MULTI EMPRESA

        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT cd_cliente, nm_cliente FROM g_cliente ORDER BY nm_cliente ASC";
        $stmt = $mysql->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            while ($reg = $stmt->fetch(PDO::FETCH_OBJ)) {

                $snResp = (!is_null($cdCliente)) ? array_search($reg->cd_cliente, $cdCliente) : null;

                $selected = (is_int($snResp)) ? 'selected' : '';

                echo '<option value="'.base64_encode($reg->cd_cliente).'"  '.$selected.'>'.$reg->nm_cliente.'</option>';
            }
        }else{
            echo '<option>'.var_dump($stmt->errorInfo()).'</option>';
        }
    }

    public function Dados(){

        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT cd_cliente, nm_cliente, ds_email FROM g_cliente WHERE cd_cliente = :cdCliente";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdCliente", $this->cdCliente);
        $result = $stmt->execute();
        if ($result) {
            $reg = $stmt->fetch(PDO::FETCH_OBJ);

            $this->nmCliente    = $reg->nm_cliente;
            $this->dsEmail      = $reg->ds_email;
        }else{
            echo var_dump($stmt->errorInfo());
        }
    }

    public function listTable(){
        $mysql = MysqlConexao::getInstance();

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $sql = "SELECT c.cd_cliente, c.nm_cliente, c.tp_pessoa, c.nr_rg, c.cpf_cnpj, c.nr_inscricao_estadual, c.nr_inscricao_municipal, c.ds_ctf, c.nr_telefone, c.nr_celular, c.ds_email, c.ds_site, c.cd_cep, c.ds_endereco, c.nm_bairro, c.nm_cidade, c.uf, CASE c.sn_ativo WHEN 'S' THEN '<span class=\"col-green\">ATIVO</span>' ELSE '<span class=\"col-red\">INATIVO</span>' END AS ds_status, c.sn_ativo, r.nm_representante as nm_representante_r, r.tp_pessoa as tp_pessoa_r, r.nr_rg as nr_rg_r, r.cpf_cnpj as cpf_cnpj_r, r.nr_inscricao_estadual as nr_inscricao_estadual_r, r.nr_inscricao_municipal as nr_inscricao_municipal_r, r.ds_ctf as ds_ctf_r, r.nr_telefone as nr_telefone_r, r.nr_celular as nr_celular_r, r.ds_email as ds_email_r, r.ds_site as ds_site_r, r.cd_cep as cd_cep_r, r.ds_endereco as ds_endereco_r, r.nm_bairro as nm_bairro_r, r.nm_cidade as nm_cidade_r, r.uf as uf_r FROM g_cliente c LEFT JOIN g_representante_cliente r ON c.cd_cliente = r.cd_cliente ORDER BY nm_cliente;";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdEmpresa", $cdEmpresa);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                while($reg = $stmt->fetch(PDO::FETCH_OBJ)){

                    $cpf = $reg->cpf_cnpj;

                    switch(strlen($cpf)) {
                        
                        case 11:
                        $cpfCnpj = substr($cpf, 0, 3).".".substr($cpf, 3, 3).".".substr($cpf, 6, 3)."-".substr($cpf, 9, 2);
                        break;
                        
                        case 14:
                        $cpfCnpj = substr($cpf, 0, 2).".".substr($cpf, 2, 3).".".substr($cpf, 5, 3)."/".substr($cpf, 8, 4)."-".substr($cpf, 12, 3);
                        break;
                        
                        default:
                        $cpfCnpj = $cpf;
                        break;

                    }

                    echo '
                    <tr>
                    <td>'.$reg->cd_cliente.'</td>
                    <td>
                    <a data-toggle="modal" href="#modalFormAlterCliente" onclick="preencheFormAlterCliente(\''.$reg->cd_cliente.'\',\''.$reg->nm_cliente.'\',\''.$reg->tp_pessoa.'\',\''.$reg->nr_rg.'\',\''.$reg->cpf_cnpj.'\',\''.$reg->nr_inscricao_estadual.'\',\''.$reg->nr_inscricao_municipal.'\',\''.$reg->ds_ctf.'\',\''.$reg->nr_telefone.'\',\''.$reg->nr_celular.'\',\''.$reg->ds_email.'\',\''.$reg->ds_site.'\',\''.$reg->cd_cep.'\',\''.$reg->ds_endereco.'\',\''.$reg->nm_bairro.'\',\''.$reg->nm_cidade.'\',\''.$reg->uf.'\',\''.$reg->sn_ativo.'\'); preencheFormAlterClienteResp(\''.$reg->nm_representante_r.'\',\''.$reg->tp_pessoa_r.'\',\''.$reg->nr_rg_r.'\',\''.$reg->cpf_cnpj_r.'\',\''.$reg->nr_inscricao_estadual_r.'\',\''.$reg->nr_inscricao_municipal_r.'\',\''.$reg->ds_ctf_r.'\',\''.$reg->nr_telefone_r.'\',\''.$reg->nr_celular_r.'\',\''.$reg->ds_email_r.'\',\''.$reg->ds_site_r.'\',\''.$reg->cd_cep_r.'\',\''.$reg->ds_endereco_r.'\',\''.$reg->nm_bairro_r.'\',\''.$reg->nm_cidade_r.'\',\''.$reg->uf_r.'\')">'.$reg->nm_cliente.'</a>
                    </td>
                    <td>'.$cpfCnpj.'</td>
                    <td>'.$reg->nr_telefone.', '.$reg->nr_celular.'</td>
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

    public function cadCliente(){

        $cdEmpresa = $_SESSION['cdEmpresa'];

        $mysql = MysqlConexao::getInstance();

        $sql = "INSERT INTO g_cliente (`nm_cliente`,`tp_pessoa`, `nr_rg`,`cpf_cnpj`, `nr_inscricao_estadual`, `nr_inscricao_municipal`,`ds_ctf`,`ds_endereco`,`nm_bairro`,`nm_cidade`,`uf`,`cd_cep`,`nr_telefone`,`nr_celular`,`ds_email`,`ds_site`) VALUES (UPPER(:nmCliente), UPPER(:tpPessoa), :nrRg, :cpfCnpj, :nrInscricaoEstadual, :nrInscricaoMunicipal, :dsCtf, UPPER(:dsEndereco), UPPER(:nmBairro), UPPER(:nmCidade), UPPER(:uf), :cdCep, :nrTelefone, :nrCelular, UPPER(:dsEmail), UPPER(:dsSite));";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":nmCliente", $this->nmPessoa);
        $stmt->bindParam(":tpPessoa", $this->tpPessoa);
        $stmt->bindParam(":nrRg", $this->nrRg);
        $stmt->bindParam(":cpfCnpj", $this->nrCpf);
        $stmt->bindParam(":nrInscricaoEstadual", $this->nrInscricaoEstadual);
        $stmt->bindParam(":nrInscricaoMunicipal", $this->nrInscricaoMunicipal);
        $stmt->bindParam(":dsCtf", $this->dsCtf);
        $stmt->bindParam(":nrTelefone", $this->nrTelefone);
        $stmt->bindParam(":nrCelular", $this->nrCelular);
        $stmt->bindParam(":dsEmail", $this->dsEmail);
        $stmt->bindParam(":dsSite", $this->dsSite);
        $stmt->bindParam(":cdCep", $this->cdCep);
        $stmt->bindParam(":dsEndereco", $this->dsEndereco);
        $stmt->bindParam(":nmBairro", $this->nmBairro);
        $stmt->bindParam(":nmCidade", $this->nmCidade);
        $stmt->bindParam(":uf", $this->uf);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                return intval($mysql->lastInsertId());
            }else{
                return false;
            }

        }else{

            $error = $stmt->errorInfo();

            return $error[2];
        }
    }

    public function alterCliente(){
        $mysql = MysqlConexao::getInstance();

        $sql = "UPDATE `g_cliente` SET `nm_cliente` = UPPER(:nmCliente), `tp_pessoa` = UPPER(:tpPessoa),`nr_rg` = :nrRg, `cpf_cnpj` = :cpfCnpj, `nr_inscricao_estadual` = :nrInscricaoEstadual, `nr_inscricao_municipal` = :nrInscricaoMunicipal, `ds_ctf` = :dsCtf, `ds_endereco` = UPPER(:dsEndereco), `nm_bairro` = UPPER(:nmBairro), `nm_cidade` = UPPER(:nmCidade), `uf` = UPPER(:uf), `cd_cep` = :cdCep, `nr_telefone` = :nrTelefone, `nr_celular` = :nrCelular, `ds_email` = UPPER(:dsEmail), `ds_site` = UPPER(:dsSite), sn_ativo = UPPER(:snAtivo) WHERE `cd_cliente` = :cdCliente";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdCliente", $this->cdCliente);
        $stmt->bindParam(":nmCliente", $this->nmPessoa);
        $stmt->bindParam(":tpPessoa", $this->tpPessoa);
        $stmt->bindParam(":nrRg", $this->nrRg);
        $stmt->bindParam(":cpfCnpj", $this->nrCpf);
        $stmt->bindParam(":nrInscricaoEstadual", $this->nrInscricaoEstadual);
        $stmt->bindParam(":nrInscricaoMunicipal", $this->nrInscricaoMunicipal);
        $stmt->bindParam(":dsCtf", $this->dsCtf);
        $stmt->bindParam(":dsEmail", $this->dsEmail);
        $stmt->bindParam(":dsSite", $this->dsSite);
        $stmt->bindParam(":nrTelefone", $this->nrTelefone);
        $stmt->bindParam(":nrCelular", $this->nrCelular);
        $stmt->bindParam(":dsEmail", $this->dsEmail);
        $stmt->bindParam(":cdCep", $this->cdCep);
        $stmt->bindParam(":dsEndereco", $this->dsEndereco);
        $stmt->bindParam(":nmBairro", $this->nmBairro);
        $stmt->bindParam(":nmCidade", $this->nmCidade);
        $stmt->bindParam(":uf", $this->uf);
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
    
    public function getRepresentante(){
        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT * FROM `g_representante_cliente`WHERE `cd_cliente` = :cdCliente";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdCliente", $this->cdCliente);
        $result = $stmt->execute();
        if ($result) {
            $num = $stmt->rowCount();
            if($num > 0){
                return $stmt->fetch(PDO::FETCH_OBJ);
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

    public function getAll(){

        //LEMBRAR DE COLOCAR MULTI EMPRESA

        $mysql = MysqlConexao::getInstance();

        $sql = "SELECT cd_cliente, nm_cliente FROM g_cliente ORDER BY nm_cliente ASC";
        $stmt = $mysql->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            return $reg = $stmt->fetchAll(PDO::FETCH_OBJ);
        }
    }
}