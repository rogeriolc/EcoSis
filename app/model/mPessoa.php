<?php

class mPessoa extends cEmpresa {

    public $nmPessoa;
    public $tpPessoa;
    public $nrRg;
    public $nrCpf;
    public $dsEmail;
    public $dsEndereco;
    public $nmBairro;
    public $nmCidade;
    public $cdCep;
    public $uf;
    public $nrTelefone;
    public $nrCelular;
    public $dsSite;

    public function __construct($nmPessoa = null, $tpPessoa = null, $nrRg = null, $nrCpf = null, $dsEmail = null, $dsSite = null, $dsEndereco = null, $nmBairro = null, $nmCidade = null, $cdCep = null, $uf = null, $nrTelefone = null, $nrCelular = null)
    {
        $this->nmPessoa     = $nmPessoa;
        $this->tpPessoa     = $tpPessoa;
        $this->nrRg         = $nrRg;
        $this->nrCpf        = $nrCpf;
        $this->dsEmail      = $dsEmail;
        $this->dsSite       = $dsSite;
        $this->dsEndereco   = $dsEndereco;
        $this->nmBairro     = $nmBairro;
        $this->nmCidade     = $nmCidade;
        $this->cdCep        = $cdCep;
        $this->uf           = $uf;
        $this->nrTelefone   = $nrTelefone;
        $this->nrCelular    = $nrCelular;
    }


    public function getNmPessoa() {
        return $this->nmPessoa;
    }

    public function setNmPessoa($nmPessoa) {
        $this->nmPessoa = $nmPessoa;
    }

    public function getNrCpf() {
        return $this->nrCpf;
    }

    public function setNrCpf($nrCpf) {
        $this->nrCpf = $nrCpf;
    }

    public function getDsEndereco() {
        return $this->dsEndereco;
    }

    public function setDsEndereco($dsEndereco) {
        $this->dsEndereco = $dsEndereco;
    }

    public function getNmBairro() {
        return $this->nmBairro;
    }

    public function setNmBairro($nmBairro) {
        $this->nmBairro = $nmBairro;
    }

    public function getNmCidade() {
        return $this->nmCidade;
    }

    public function setNmCidade($nmCidade) {
        $this->nmCidade = $nmCidade;
    }

    public function getCdCep() {
        return $this->cdCep;
    }

    public function setCdCep($cdCep) {
        $this->cdCep = $cdCep;
    }


    public function getUf()
    {
        return $this->uf;
    }

    public function setUf($uf)
    {
        $this->uf = $uf;

        return $this;
    }

    public function getNrTelefone()
    {
        return $this->nrTelefone;
    }

    public function setNrTelefone($nrTelefone)
    {
        $this->nrTelefone = $nrTelefone;

        return $this;
    }

    public function getNrCelular()
    {
        return $this->nrCelular;
    }

    public function setNrCelular($nrCelular)
    {
        $this->nrCelular = $nrCelular;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTpPessoa()
    {
        return $this->tpPessoa;
    }

    /**
     * @param mixed $tpPessoa
     *
     * @return self
     */
    public function setTpPessoa($tpPessoa)
    {
        $this->tpPessoa = $tpPessoa;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNrRg()
    {
        return $this->nrRg;
    }

    /**
     * @param mixed $nrRg
     *
     * @return self
     */
    public function setNrRg($nrRg)
    {
        $this->nrRg = $nrRg;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDsEmail()
    {
        return $this->dsEmail;
    }

    /**
     * @param mixed $dsEmail
     *
     * @return self
     */
    public function setDsEmail($dsEmail)
    {
        $this->dsEmail = $dsEmail;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDsSite()
    {
        return $this->dsSite;
    }

    /**
     * @param mixed $dsSite
     *
     * @return self
     */
    public function setDsSite($dsSite)
    {
        $this->dsSite = $dsSite;

        return $this;
    }
}