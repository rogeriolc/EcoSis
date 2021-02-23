<?php

class mPropostaLicencaAmb{

    public $cdPropostaLicenca;
	public $cdPropostaPai;
	public $cdCliente;
	public $cdEmpreendimento;
	public $cdTpAtividade;
	public $tpStatus;
    public $vlNegociado;
	public $vlPago;
    public $dtPrevConclusao;
	public $dsObservacao;
    public $cdEmpresa;
    public $vlDesconto;


	public function __construct($cdPropostaLicenca=null, $cdCliente=null, $cdEmpreendimento=null, $tpStatus=null, $vlNegociado=null, $vlPago=null, $dtPrevConclusao=null, $dsObservacao=null, $vlDesconto=null)
	{
		$this->cdPropostaLicenca 	= $cdPropostaLicenca;
		$this->cdCliente 			= $cdCliente;
		$this->cdEmpreendimento 	= $cdEmpreendimento;
		$this->tpStatus 			= $tpStatus;
        $this->vlNegociado          = $vlNegociado;
		$this->vlPago 		        = $vlPago;
        $this->dtPrevConclusao      = $dtPrevConclusao;
        $this->dsObservacao 		= $dsObservacao;
        $this->vlDesconto           = $vlDesconto;
	}

    public function getCdPropostaLicenca()
    {
        return $this->cdPropostaLicenca;
    }

    public function setCdPropostaLicenca($cdPropostaLicenca)
    {
        $this->cdPropostaLicenca = $cdPropostaLicenca;

        return $this;
    }

    public function getCdCliente()
    {
        return $this->cdCliente;
    }

    public function setCdCliente($cdCliente)
    {
        $this->cdCliente = $cdCliente;

        return $this;
    }

    public function getCdEmpreendimento()
    {
        return $this->cdEmpreendimento;
    }

    public function setCdEmpreendimento($cdEmpreendimento)
    {
        $this->cdEmpreendimento = $cdEmpreendimento;

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

    public function getTpStatus()
    {
        return $this->tpStatus;
    }

    public function setTpStatus($tpStatus)
    {
        $this->tpStatus = $tpStatus;

        return $this;
    }

    public function getVlNegociado()
    {
        return $this->vlNegociado;
    }

    public function setVlNegociado($vlNegociado)
    {
        $this->vlNegociado = $vlNegociado;

        return $this;
    }

    public function getVlPago()
    {
        return $this->vlPago;
    }

    public function setVlPago($vlPago)
    {
        $this->vlPago = $vlPago;

        return $this;
    }

    public function getDsObservacao()
    {
        return $this->dsObservacao;
    }

    public function setDsObservacao($dsObservacao)
    {
        $this->dsObservacao = $dsObservacao;

        return $this;
    }

    public function setCdEmpresa($cdEmpresa)
    {
        $this->cdEmpresa = $cdEmpresa;

        return $this;
    }

    public function getCdEmpresa()
    {
        return $this->cdEmpresa;
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

    public function getCdPropostaPai()
    {
        return $this->cdPropostaPai;
    }

    public function setCdPropostaPai($cdPropostaPai)
    {
        $this->cdPropostaPai = $cdPropostaPai;

        return $this;
    }

    public function getVlDesconto()
    {
        return $this->vlDesconto;
    }

    public function setVlDesconto($vlDesconto)
    {
        $this->vlDesconto = $vlDesconto;

        return $this;
    }
}

?>