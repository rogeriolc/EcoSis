<?php

/**
 * Representante Legal
 */
class cRepresentante extends mCliente
{

	public function __construct($cdCliente = null, $nmPessoa = null, $tpPessoa = null, $nrRg = null, $nrCpf = null, $nrInscricaoEstadual = null, $nrInscricaoMunicipal = null, $dsCtf = null, $dsEmail=null, $dsSite=null, $dsEndereco = null, $nmBairro = null, $nmCidade = null, $cdCep = null, $uf = null, $nrTelefone = null, $nrCelular = null, $snAtivo = null){

        //pega o construtor mCliente
        parent::__construct($cdCliente, $nmPessoa, $tpPessoa, $nrRg, $nrCpf, $nrInscricaoEstadual, $nrInscricaoMunicipal, $dsCtf, $dsEmail, $dsSite, $dsEndereco, $nmBairro, $nmCidade, $cdCep, $uf, $nrTelefone, $nrCelular, $snAtivo);
    }

    public function Cadastrar(){

        $cdEmpresa = $_SESSION['cdEmpresa'];
        $cdUsuarioSessao = $_SESSION['cdUsuario'];

        $mysql = MysqlConexao::getInstance();

        $sql = "INSERT INTO g_representante_cliente (`cd_cliente`, `nm_representante`,`tp_pessoa`, `nr_rg`,`cpf_cnpj`, `nr_inscricao_estadual`, `nr_inscricao_municipal`, `ds_ctf`,`ds_endereco`,`nm_bairro`,`nm_cidade`,`uf`,`cd_cep`,`nr_telefone`,`nr_celular`,`ds_email`,`ds_site`, `cd_usuario_registro`) VALUES (UPPER(:cdCliente), UPPER(:nmRepresentante), UPPER(:tpPessoa), :nrRg, :cpfCnpj, :nrInscricaoEstadual, :nrInscricaoMunicipal, :dsCtf, UPPER(:dsEndereco), UPPER(:nmBairro), UPPER(:nmCidade), UPPER(:uf), :cdCep, :nrTelefone, :nrCelular, UPPER(:dsEmail), UPPER(:dsSite), :cdUsuarioSessao);";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdCliente", $this->cdCliente);
        $stmt->bindParam(":nmRepresentante", $this->nmPessoa);
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

            $error = $stmt->errorInfo();

            return $error[2];
        }
    }

    public function Alterar(){
        $mysql = MysqlConexao::getInstance();

        $sql = "UPDATE `g_representante_cliente` SET `nm_representante` = UPPER(:nmRepresentante), `tp_pessoa` = UPPER(:tpPessoa),`nr_rg` = :nrRg, `cpf_cnpj` = :cpfCnpj, `nr_inscricao_estadual` = :nrInscricaoEstadual, `nr_inscricao_municipal` = :nrInscricaoMunicipal, `ds_ctf` = :dsCtf, `ds_endereco` = UPPER(:dsEndereco), `nm_bairro` = UPPER(:nmBairro), `nm_cidade` = UPPER(:nmCidade), `uf` = UPPER(:uf), `cd_cep` = :cdCep, `nr_telefone` = :nrTelefone, `nr_celular` = :nrCelular, `ds_email` = UPPER(:dsEmail), `ds_site` = UPPER(:dsSite) WHERE `cd_cliente` = :cdCliente";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdCliente", $this->cdCliente);
        $stmt->bindParam(":nmRepresentante", $this->nmPessoa);
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

?>