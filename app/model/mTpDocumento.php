<?php
/**
 * mTpDocumento
 */
class mTpDocumento
{
	public $cdTpDocumento;
	public $dsTpDocumento;
	public $vlArea;

	public function __construct($cdTpDocumento=null, $dsTpDocumento=null, $vlArea=null, $snAtivo=null)
	{
		$this->cdTpDocumento = $cdTpDocumento;
		$this->dsTpDocumento = $dsTpDocumento;
		$this->vlArea 	= $vlArea;
		$this->snAtivo  = $snAtivo;
	}

    public function getDsTpDocumento()
    {
        return $this->dsTpDocumento;
    }

    public function setDsTpDocumento($dsTpDocumento)
    {
        $this->dsTpDocumento = $dsTpDocumento;

        return $this;
    }

    public function getVlArea()
    {
        return $this->vlArea;
    }

    public function setVlArea($vlArea)
    {
        $this->vlArea = $vlArea;

        return $this;
    }

    public function getCdTpDocumento()
    {
        return $this->cdTpDocumento;
    }

    public function setCdTpDocumento($cdTpDocumento)
    {
        $this->cdTpDocumento = $cdTpDocumento;

        return $this;
    }
}
?>