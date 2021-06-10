<?php

/**
* Model Servico
*/
class mServico extends mEmpreendimento{

	public $cdServico;
	public $nrProcesso;
	public $cdOrgaoLicenciado;
	public $dtPrevConclusao;
	public $tpStatus;
	public $cdPropostaCliente;

	public function __construct($cdServico=null, $nrProcesso=null, $cdOrgaoLicenciado=null, $dtPrevConclusao=null, $tpStatus=null)
	{
		$this->cdServico          = $cdServico;
		$this->nrProcesso         = $nrProcesso;
		$this->cdOrgaoLicenciado  = $cdOrgaoLicenciado;
		$this->dtPrevConclusao    = $dtPrevConclusao;
		$this->tpStatus           = $tpStatus;
	}

    public function getCdServico()
    {
        return $this->cdServico;
    }

    public function setCdServico($cdServico)
    {
        $this->cdServico = $cdServico;

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

    public function getDtPrevConclusao()
    {
        return $this->dtPrevConclusao;
    }

    public function setDtPrevConclusao($dtPrevConclusao)
    {
        $this->dtPrevConclusao = $dtPrevConclusao;

        return $this;
    }

    public function getTpStatus()
    {
        return $this->tpStatus;
    }

    public function setTpStatus($tpStatus)
    {
        $this->tpStatus = $tpStatus;

        return $this;
    }
}

?>