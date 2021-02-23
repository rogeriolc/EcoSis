<?php


class mObjetoLicenca
{

	public $cdObjetoLicenca;
    public $dsObjetoLicenca;
    public $cdCatObjetoLicenca;
	public $snPedirProtocolo;
	public $snAtivo;

	public function __construct($cdObjetoLicenca=null, $dsObjetoLicenca=null, $cdCatObjetoLicenca=null, $snPedirProtocolo=null, $snAtivo=null)
	{
		$this->cdObjetoLicenca      = $cdObjetoLicenca;
        $this->dsObjetoLicenca      = $dsObjetoLicenca;
        $this->cdCatObjetoLicenca   = $cdCatObjetoLicenca;
		$this->snPedirProtocolo     = $snPedirProtocolo;
		$this->snAtivo 			    = $snAtivo;
	}


    public function getCdTpLicenca()
    {
        return $this->cdObjetoLicenca;
    }

    public function setCdTpLicenca($cdObjetoLicenca)
    {
        $this->cdObjetoLicenca = $cdObjetoLicenca;

        return $this;
    }

    public function getDsTpLicenca()
    {
        return $this->dsObjetoLicenca;
    }

    public function setDsTpLicenca($dsObjetoLicenca)
    {
        $this->dsObjetoLicenca = $dsObjetoLicenca;

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

    public function getCdObjetoLicenca()
    {
        return $this->cdObjetoLicenca;
    }

    public function setCdObjetoLicenca($cdObjetoLicenca)
    {
        $this->cdObjetoLicenca = $cdObjetoLicenca;

        return $this;
    }

    public function getDsObjetoLicenca()
    {
        return $this->dsObjetoLicenca;
    }

    public function setDsObjetoLicenca($dsObjetoLicenca)
    {
        $this->dsObjetoLicenca = $dsObjetoLicenca;

        return $this;
    }

    public function getCdCatObjetoLicenca()
    {
        return $this->cdCatObjetoLicenca;
    }

    public function setCdCatObjetoLicenca($cdCatObjetoLicenca)
    {
        $this->cdCatObjetoLicenca = $cdCatObjetoLicenca;

        return $this;
    }

    public function getSnPedirProtocolo()
    {
        return $this->snPedirProtocolo;
    }

    public function setSnPedirProtocolo($snPedirProtocolo)
    {
        $this->snPedirProtocolo = $snPedirProtocolo;

        return $this;
    }
}

?>