<?php

/**
 * mPagina
 */
class mPagina extends cModulo
{

    public $cdPagina;
    public $nmPagina;


    /**
     * Class Constructor
     * @param    $cdPagina
     * @param    $nmPagina
     */
    public function __construct($cdPagina="", $nmPagina=""){
        $this->cdPagina = $cdPagina;
        $this->nmPagina = $nmPagina;
    }


    /**
     * @return mixed
     */
    public function getCdPagina()
    {
        return $this->cdPagina;
    }

    /**
     * @param mixed $cdPagina
     *
     * @return self
     */
    public function setCdPagina($cdPagina)
    {
        $this->cdPagina = $cdPagina;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNmPagina()
    {
        return $this->nmPagina;
    }

    /**
     * @param mixed $nmPagina
     *
     * @return self
     */
    public function setNmPagina($nmPagina)
    {
        $this->nmPagina = $nmPagina;

        return $this;
    }

}

?>