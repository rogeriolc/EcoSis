<?php
/**
 * mModulo
 */
class mModulo
{

	public $cdModulo;
	public $nmModulo;


	public function __construct($cdModulo=null, $nmModulo=null)
	{
		$this->cdModulo = $cdModulo;
		$this->nmModulo = $nmModulo;
	}


    public function getCdModulo()
    {
        return $this->cdModulo;
    }

    public function setCdModulo($cdModulo)
    {
        $this->cdModulo = $cdModulo;

        return $this;
    }

    public function getNmModulo()
    {
        return $this->nmModulo;
    }

    public function setNmModulo($nmModulo)
    {
        $this->nmModulo = $nmModulo;

        return $this;
    }
}
?>