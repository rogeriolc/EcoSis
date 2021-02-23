<?php

/**
 * Tipografia
 */
class mTipografia
{
	public $cdTipografia;
	public $dsTipografia;
	public $snAtivo;

	public function __construct($cdTipografia=null, $dsTipografia=null, $snAtivo=null)
	{
		$this->cdTipografia = $cdTipografia;
		$this->dsTipografia = $dsTipografia;
		$this->snAtivo = $snAtivo;
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

    public function getCdTipografia()
    {
        return $this->cdTipografia;
    }

    public function setCdTipografia($cdTipografia)
    {
        $this->cdTipografia = $cdTipografia;

        return $this;
    }

    public function getDsTipografia()
    {
        return $this->dsTipografia;
    }

    public function setDsTipografia($dsTipografia)
    {
        $this->dsTipografia = $dsTipografia;

        return $this;
    }
}

?>