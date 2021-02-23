<?php


class mCatObjetoLicenca
{

	public $cdCatObjetoLicenca;
	public $dsCatObjetoLicenca;
	public $snAtivo;

	public function __construct($cdCatObjetoLicenca=null, $dsCatObjetoLicenca=null, $snAtivo=null)
	{
		$this->cdCatObjetoLicenca = $cdCatObjetoLicenca;
		$this->dsCatObjetoLicenca = $dsCatObjetoLicenca;
		$this->snAtivo 				= $snAtivo;
	}


    public function getCdTpLicenca()
    {
        return $this->cdCatObjetoLicenca;
    }

    public function setCdTpLicenca($cdCatObjetoLicenca)
    {
        $this->cdCatObjetoLicenca = $cdCatObjetoLicenca;

        return $this;
    }

    public function getDsTpLicenca()
    {
        return $this->dsCatObjetoLicenca;
    }

    public function setDsTpLicenca($dsCatObjetoLicenca)
    {
        $this->dsCatObjetoLicenca = $dsCatObjetoLicenca;

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

?>