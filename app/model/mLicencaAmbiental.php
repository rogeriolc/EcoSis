<?php

class mLicencaAmbiental extends cEmpreendimento {
	public $cdLicencaAmbiental;
	public $cdTpLicenca;
	public $dsTpLicenca;
    public $cdOrgaoLicenciado;
    public $tpStatus;
    protected $dtPrevEntrega;

    protected $cdItLicencaAmbiental;
    protected $cdItLicencaFase;
    protected $cdFaseLicenca;
    protected $dsFase;
    protected $cdResponsavel;
    protected $dsComentario;
    protected $dtConclusao;
    protected $dtCancelamento;

    public $nrProcesso;

    public function getCdLicencaAmbiental()
    {
        return $this->cdLicencaAmbiental;
    }

    public function setCdLicencaAmbiental($cdLicencaAmbiental)
    {
        $this->cdLicencaAmbiental = $cdLicencaAmbiental;

        return $this;
    }

    public function getCdTpLicenca()
    {
        return $this->cdTpLicenca;
    }

    public function setCdTpLicenca($cdTpLicenca)
    {
        $this->cdTpLicenca = $cdTpLicenca;

        return $this;
    }

    public function getDsTpLicenca()
    {
        return $this->dsTpLicenca;
    }

    public function setDsTpLicenca($dsTpLicenca)
    {
        $this->dsTpLicenca = $dsTpLicenca;

        return $this;
    }

    public function getNrProcesso()
    {
        return $this->nrProcesso;
    }

    public function setNrProcesso($nrProcesso)
    {
        $this->nrProcesso = $nrProcesso;

        return $this;
    }

    public function getCdOrgaoLicenciado()
    {
        return $this->cdOrgaoLicenciado;
    }

    public function setCdOrgaoLicenciado($cdOrgaoLicenciado)
    {
        $this->cdOrgaoLicenciado = $cdOrgaoLicenciado;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCdItLicencaAmbiental()
    {
        return $this->cdItLicencaAmbiental;
    }

    /**
     * @param mixed $cdItLicencaAmbiental
     *
     * @return self
     */
    public function setCdItLicencaAmbiental($cdItLicencaAmbiental)
    {
        $this->cdItLicencaAmbiental = $cdItLicencaAmbiental;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCdFaseLicenca()
    {
        return $this->cdFaseLicenca;
    }

    /**
     * @param mixed $cdFaseLicenca
     *
     * @return self
     */
    public function setCdFaseLicenca($cdFaseLicenca)
    {
        $this->cdFaseLicenca = $cdFaseLicenca;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDsFase()
    {
        return $this->dsFase;
    }

    /**
     * @param mixed $dsFase
     *
     * @return self
     */
    public function setDsFase($dsFase)
    {
        $this->dsFase = $dsFase;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCdResponsavel()
    {
        return $this->cdResponsavel;
    }

    /**
     * @param mixed $cdResponsavel
     *
     * @return self
     */
    public function setCdResponsavel($cdResponsavel)
    {
        $this->cdResponsavel = $cdResponsavel;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDtPrevEntrega()
    {
        return $this->dtPrevEntrega;
    }

    /**
     * @param mixed $dtPrevEntrega
     *
     * @return self
     */
    public function setDtPrevEntrega($dtPrevEntrega)
    {
        $this->dtPrevEntrega = $dtPrevEntrega;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCdItLicencaFase()
    {
        return $this->cdItLicencaFase;
    }

    /**
     * @param mixed $cdItLicencaFase
     *
     * @return self
     */
    public function setCdItLicencaFase($cdItLicencaFase)
    {
        $this->cdItLicencaFase = $cdItLicencaFase;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDsComentario()
    {
        return $this->dsComentario;
    }

    /**
     * @param mixed $dsComentario
     *
     * @return self
     */
    public function setDsComentario($dsComentario)
    {
        $this->dsComentario = $dsComentario;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTpStatus()
    {
        return $this->tpStatus;
    }

    /**
     * @param mixed $tpStatus
     *
     * @return self
     */
    public function setTpStatus($tpStatus)
    {
        $this->tpStatus = $tpStatus;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDtConclusao()
    {
        return $this->dtConclusao;
    }

    /**
     * @param mixed $dtConclusao
     *
     * @return self
     */
    public function setDtConclusao($dtConclusao)
    {
        $this->dtConclusao = $dtConclusao;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDtCancelamento()
    {
        return $this->dtCancelamento;
    }

    /**
     * @param mixed $dtCancelamento
     *
     * @return self
     */
    public function setDtCancelamento($dtCancelamento)
    {
        $this->dtCancelamento = $dtCancelamento;

        return $this;
    }
}