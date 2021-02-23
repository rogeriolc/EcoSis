<?php

/**
 * Tratamento
 */
class mTratamento
{
	public $cdTratamento;
	public $dsTratamento;
	public $snAtivo;

	public function __construct($cdTratamento=null, $dsTratamento=null, $snAtivo=null)
	{
		$this->cdTratamento = $cdTratamento;
		$this->dsTratamento = $dsTratamento;
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

    public function getCdTratamento()
    {
        return $this->cdTratamento;
    }

    public function setCdTratamento($cdTratamento)
    {
        $this->cdTratamento = $cdTratamento;

        return $this;
    }

    public function getDsTratamento()
    {
        return $this->dsTratamento;
    }

    public function setDsTratamento($dsTratamento)
    {
        $this->dsTratamento = $dsTratamento;

        return $this;
    }
}

?>