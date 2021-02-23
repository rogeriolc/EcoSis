<?php

class mPorteEmpreendimento
{

	public $cdPorteEmpreendimento;
	public $dsPorteEmpreendimento;
	public $snAtivo;
	public $dhRegistro;


	public function __construct($cdPorteEmpreendimento=null, $dsPorteEmpreendimento=null, $snAtivo=null, $dhRegistro=null)
	{
		$this->cdPorteEmpreendimento	= $cdPorteEmpreendimento;
		$this->dsPorteEmpreendimento 	= $dsPorteEmpreendimento;
		$this->snAtivo 					= $snAtivo;
		$this->dhRegistro 				= $dhRegistro;
	}


    public function getCdPorteEmpreendimento()
    {
        return $this->cdPorteEmpreendimento;
    }


    public function setCdPorteEmpreendimento($cdPorteEmpreendimento)
    {
        $this->cdPorteEmpreendimento = $cdPorteEmpreendimento;

        return $this;
    }


    public function getDsPorteEmpreendimento()
    {
        return $this->dsPorteEmpreendimento;
    }


    public function setDsPorteEmpreendimento($dsPorteEmpreendimento)
    {
        $this->dsPorteEmpreendimento = $dsPorteEmpreendimento;

        return $this;
    }


    public function getSnAtivo()
    {
        return $this->snAtivo;
    }


    public function setSnAtivo($snAtivo)
    {
        $this->snAtivo = $snAtivo;

        return $this;
    }


    public function getDhRegistro()
    {
        return $this->dhRegistro;
    }


    public function setDhRegistro($dhRegistro)
    {
        $this->dhRegistro = $dhRegistro;

        return $this;
    }
}

?>