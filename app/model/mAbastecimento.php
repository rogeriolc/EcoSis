<?php

/**
 * Abastecimento
 */
class mAbastecimento
{
	public $cdAbastecimento;
	public $dsAbastecimento;
	public $snAtivo;

	public function __construct($cdAbastecimento=null, $dsAbastecimento=null, $snAtivo=null)
	{
		$this->cdAbastecimento = $cdAbastecimento;
		$this->dsAbastecimento = $dsAbastecimento;
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

    public function getCdAbastecimento()
    {
        return $this->cdAbastecimento;
    }

    public function setCdAbastecimento($cdAbastecimento)
    {
        $this->cdAbastecimento = $cdAbastecimento;

        return $this;
    }

    public function getDsAbastecimento()
    {
        return $this->dsAbastecimento;
    }

    public function setDsAbastecimento($dsAbastecimento)
    {
        $this->dsAbastecimento = $dsAbastecimento;

        return $this;
    }
}

?>