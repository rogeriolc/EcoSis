<?php


class mTpLicencaAmbiental
{

	public $cdTpLicencaAmbiental;
	public $dsTpLicencaAmbiental;
	public $snAtivo;

	public function __construct($cdTpLicencaAmbiental=null, $dsTpLicencaAmbiental=null, $snAtivo=null)
	{
		$this->cdTpLicencaAmbiental = $cdTpLicencaAmbiental;
		$this->dsTpLicencaAmbiental = $dsTpLicencaAmbiental;
		$this->snAtivo 				= $snAtivo;
	}


    public function getCdTpLicenca()
    {
        return $this->cdTpLicencaAmbiental;
    }

    public function setCdTpLicenca($cdTpLicencaAmbiental)
    {
        $this->cdTpLicencaAmbiental = $cdTpLicencaAmbiental;

        return $this;
    }

    public function getDsTpLicenca()
    {
        return $this->dsTpLicencaAmbiental;
    }

    public function setDsTpLicenca($dsTpLicencaAmbiental)
    {
        $this->dsTpLicencaAmbiental = $dsTpLicencaAmbiental;

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