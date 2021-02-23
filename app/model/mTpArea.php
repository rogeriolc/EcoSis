<?php
/**
 * mTpArea
 */
class mTpArea extends mEmpreendimento
{
	public $cdTpArea;
	public $dsTpArea;
	public $vlArea;

	public function __construct($cdTpArea=null, $dsTpArea=null, $vlArea=null, $snAtivo=null)
	{
		$this->cdTpArea = $cdTpArea;
		$this->dsTpArea = $dsTpArea;
		$this->vlArea 	= $vlArea;
		$this->snAtivo  = $snAtivo;
	}

    public function getDsTpArea()
    {
        return $this->dsTpArea;
    }

    public function setDsTpArea($dsTpArea)
    {
        $this->dsTpArea = $dsTpArea;

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

    public function getCdTpArea()
    {
        return $this->cdTpArea;
    }

    public function setCdTpArea($cdTpArea)
    {
        $this->cdTpArea = $cdTpArea;

        return $this;
    }
}
?>