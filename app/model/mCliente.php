<?php

class mCliente extends mPessoa {

    public $cdCliente;
    public $nrInscricaoEstadual;
    public $nrInscricaoMunicipal;
    public $dsCtf;
    public $snAtivo;

    public function __construct($cdCliente = null, $nmPessoa = null, $tpPessoa = null, $nrRg = null, $nrCpf = null, $nrInscricaoEstadual = null, $nrInscricaoMunicipal = null, $dsCtf = null, $dsEmail = null, $dsSite = null, $dsEndereco = null, $nmBairro = null, $nmCidade = null, $cdCep = null, $uf = null, $nrTelefone = null, $nrCelular = null, $snAtivo = null)
    {
        //pega o construtor de mPessoa
        parent::__construct($nmPessoa, $tpPessoa, $nrRg, $nrCpf, $dsEmail, $dsSite, $dsEndereco, $nmBairro, $nmCidade, $cdCep, $uf, $nrTelefone, $nrCelular);
        self::setCdCliente($cdCliente);
        self::setSnAtivo($snAtivo);
        self::setNrInscricaoEstadual($nrInscricaoEstadual);
        self::setNrInscricaoMunicipal($nrInscricaoMunicipal);
        self::setDsCtf($dsCtf);
    }

    public function getCdCliente() {
        return $this->cdCliente;
    }

    public function setCdCliente($cdCliente) {
        $this->cdCliente = $cdCliente;
    }

    public function getSnAtivo() {
        return $this->snAtivo;
    }

    public function setSnAtivo($snAtivo) {
        $this->snAtivo = $snAtivo;
    }

    public function getNrInscricaoEstadual()
    {
        return $this->nrInscricaoEstadual;
    }

    public function setNrInscricaoEstadual($nrInscricaoEstadual)
    {
        $this->nrInscricaoEstadual = $nrInscricaoEstadual;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNrInscricaoMunicipal()
    {
        return $this->nrInscricaoMunicipal;
    }

    /**
     * @param mixed $nrInscricaoMunicipal
     *
     * @return self
     */
    public function setNrInscricaoMunicipal($nrInscricaoMunicipal)
    {
        $this->nrInscricaoMunicipal = $nrInscricaoMunicipal;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDsCtf()
    {
        return $this->dsCtf;
    }

    /**
     * @param mixed $dsCtf
     *
     * @return self
     */
    public function setDsCtf($dsCtf)
    {
        $this->dsCtf = $dsCtf;

        return $this;
    }
}