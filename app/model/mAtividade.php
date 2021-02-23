<?php

/**
*  Model Atividade
*/
class mAtividade extends mServico
{

	public $cdAtividade;
    public $dsAtividade;
	public $tpAtividade;
    public $dtPrevEntrega;
    public $cdUsuarioResponsavel;
    public $cdTpAtividade;
    public $dsStatus;
    public $dhRegistro;
    public $nrOrdem;

    /*====== ITATIVIDADE ======*/
    public $cdItAtividade;
    public $dtProtocolo;
	public $dtPrazo;
    public $dsAndamento;
    public $cdResponsavel;
    public $cdCliente;
    public $cdOrgaoLicenciador;
    public $nrProcesso;


	public function __construct($cdAtividade=null, $dsAtividade=null, $tpAtividade=null, $dtPrevEntrega=null, $cdUsuarioResponsavel=null)
	{
		$this->cdAtividade            = $cdAtividade;
        $this->dsAtividade            = $dsAtividade;
		$this->tpAtividade            = $tpAtividade;
		$this->dtPrevEntrega          = $dtPrevEntrega;
		$this->cdUsuarioResponsavel   = $cdUsuarioResponsavel;
	}


    public function getCdAtividade()
    {
        return $this->cdAtividade;
    }

    public function setCdAtividade($cdAtividade)
    {
        $this->cdAtividade = $cdAtividade;

        return $this;
    }

    public function getDsAtividade()
    {
        return $this->dsAtividade;
    }

    public function setDsAtividade($dsAtividade)
    {
        $this->dsAtividade = $dsAtividade;

        return $this;
    }

    public function getDtPrevEntrega()
    {
        return $this->dtPrevEntrega;
    }

    public function setDtPrevEntrega($dtPrevEntrega)
    {
        $this->dtPrevEntrega = $dtPrevEntrega;

        return $this;
    }

    public function getCdUsuarioResponsavel()
    {
        return $this->cdUsuarioResponsavel;
    }

    public function setCdUsuarioResponsavel($cdUsuarioResponsavel)
    {
        $this->cdUsuarioResponsavel = $cdUsuarioResponsavel;

        return $this;
    }

    public function getTpAtividade()
    {
        return $this->tpAtividade;
    }

    public function setTpAtividade($tpAtividade)
    {
        $this->tpAtividade = $tpAtividade;

        return $this;
    }

    public function getCdTpAtividade()
    {
        return $this->cdTpAtividade;
    }

    public function setCdTpAtividade($cdTpAtividade)
    {
        $this->cdTpAtividade = $cdTpAtividade;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDsStatus()
    {
        return $this->dsStatus;
    }

    /**
     * @param mixed $dsStatus
     *
     * @return self
     */
    public function setDsStatus($dsStatus)
    {
        $this->dsStatus = $dsStatus;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDhRegistro()
    {
        return $this->dhRegistro;
    }

    /**
     * @param mixed $dhRegistro
     *
     * @return self
     */
    public function setDhRegistro($dhRegistro)
    {
        $this->dhRegistro = $dhRegistro;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCdItAtividade()
    {
        return $this->cdItAtividade;
    }

    /**
     * @param mixed $cdItAtividade
     *
     * @return self
     */
    public function setCdItAtividade($cdItAtividade)
    {
        $this->cdItAtividade = $cdItAtividade;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDtProtocolo()
    {
        return $this->dtProtocolo;
    }

    /**
     * @param mixed $dtProtocolo
     *
     * @return self
     */
    public function setDtProtocolo($dtProtocolo)
    {
        $this->dtProtocolo = $dtProtocolo;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDsAndamento()
    {
        return $this->dsAndamento;
    }

    /**
     * @param mixed $dsAndamento
     *
     * @return self
     */
    public function setDsAndamento($dsAndamento)
    {
        $this->dsAndamento = $dsAndamento;

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
    public function getCdCliente()
    {
        return $this->cdCliente;
    }

    /**
     * @param mixed $cdCliente
     *
     * @return self
     */
    public function setCdCliente($cdCliente)
    {
        $this->cdCliente = $cdCliente;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCdOrgaoLicenciador()
    {
        return $this->cdOrgaoLicenciador;
    }

    /**
     * @param mixed $cdOrgaoLicenciador
     *
     * @return self
     */
    public function setCdOrgaoLicenciador($cdOrgaoLicenciador)
    {
        $this->cdOrgaoLicenciador = $cdOrgaoLicenciador;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDtPrazo()
    {
        return $this->dtPrazo;
    }

    /**
     * @param mixed $dtPrazo
     *
     * @return self
     */
    public function setDtPrazo($dtPrazo)
    {
        $this->dtPrazo = $dtPrazo;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNrOrdem()
    {
        return $this->nrOrdem;
    }

    /**
     * @param mixed $nrOrdem
     *
     * @return self
     */
    public function setNrOrdem($nrOrdem)
    {
        $this->nrOrdem = $nrOrdem;

        return $this;
    }

    /**
     * Get the value of nrProcesso
     */ 
    public function getNrProcesso()
    {
        return $this->nrProcesso;
    }

    /**
     * Set the value of nrProcesso
     *
     * @return  self
     */ 
    public function setNrProcesso($nrProcesso)
    {
        $this->nrProcesso = $nrProcesso;

        return $this;
    }
}

?>