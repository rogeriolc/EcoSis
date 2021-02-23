<?php

class mEmpresa {
    public $cdEmpresa;
    public $nmEmpresa;
    public $snAtivo;


    public function __construct($cdEmpresa, $nmEmpresa, $snAtivo)
    {
        $this->cdEmpresa = $cdEmpresa;
        $this->nmEmpresa = $nmEmpresa;
        $this->snAtivo 	 = $snAtivo;
    }


    public function getCdEmpresa()
    {
        return $this->cdEmpresa;
    }

    public function setCdEmpresa($cdEmpresa)
    {
        $this->cdEmpresa = $cdEmpresa;

        return $this;
    }

    public function getNmEmpresa()
    {
        return $this->nmEmpresa;
    }

    public function setNmEmpresa($nmEmpresa)
    {
        $this->nmEmpresa = $nmEmpresa;

        return $this;
    }

    function getSnAtivo()
    {
        return $this->snAtivo;
    }

    public function setSnAtivo($snAtivo)
    {
        $this->snAtivo = $snAtivo;

        return $this;
    }

}