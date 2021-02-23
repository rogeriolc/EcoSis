<?php

class mOrgaoLicenciado {
	public $cdOrgaoLicenciado;
	public $nmOrgaoLicenciado;
	public $snAtivo;


	public function __construct($cdOrgaoLicenciado=null, $nmOrgaoLicenciado=null, $snAtivo=null)
	{
		$this->cdOrgaoLicenciado = $cdOrgaoLicenciado;
		$this->nmOrgaoLicenciado = $nmOrgaoLicenciado;
		$this->snAtivo = $snAtivo;
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


	public function getDsOrgaoLicenciado()
	{
		return $this->nmOrgaoLicenciado;
	}


	public function setDsOrgaoLicenciado($nmOrgaoLicenciado)
	{
		$this->nmOrgaoLicenciado = $nmOrgaoLicenciado;

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
}