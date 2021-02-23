<?php

class mPotencialPoluidor
{

	public $cdPotencialPoluidor;
	public $dsPotencialPoluidor;
	public $snAtivo;
	public $dhRegistro;


	public function __construct($cdPotencialPoluidor=null, $dsPotencialPoluidor=null, $snAtivo=null, $dhRegistro=null)
	{
		$this->cdPotencialPoluidor 	= $cdPotencialPoluidor;
		$this->dsPotencialPoluidor 	= $dsPotencialPoluidor;
		$this->snAtivo 				= $snAtivo;
		$this->dhRegistro 			= $dhRegistro;
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

    public function getCdPotencialPoluidor()
    {
        return $this->cdPotencialPoluidor;
    }

    public function setCdPotencialPoluidor($cdPotencialPoluidor)
    {
        $this->cdPotencialPoluidor = $cdPotencialPoluidor;

        return $this;
    }

    public function getDsPotencialPoluidor()
    {
        return $this->dsPotencialPoluidor;
    }

    public function setDsPotencialPoluidor($dsPotencialPoluidor)
    {
        $this->dsPotencialPoluidor = $dsPotencialPoluidor;

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