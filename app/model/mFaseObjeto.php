<?php
/**
* Fase Objeto
*/
class mFaseObjeto extends cObjetoLicenca
{

	public $cdFaseObjeto;
    public $dsFaseObjeto;
	public $nrOrdem;
	public $snAtivo;

	public function __construct($cdFaseObjeto=null, $dsFaseObjeto=null, $nrOrdem =null, $snAtivo=null)
	{
		$this->cdFaseObjeto = $cdFaseObjeto;
        $this->dsFaseObjeto = $dsFaseObjeto;
		$this->nrOrdem      = $nrOrdem;
		$this->snAtivo 		= $snAtivo;
	}


    public function getCdFaseObjeto()
    {
        return $this->cdFaseObjeto;
    }

    public function setCdFaseObjeto($cdFaseObjeto)
    {
        $this->cdFaseObjeto = $cdFaseObjeto;

        return $this;
    }

    public function getDsFaseObjeto()
    {
        return $this->dsFaseObjeto;
    }

    public function setDsFaseObjeto($dsFaseObjeto)
    {
        $this->dsFaseObjeto = $dsFaseObjeto;

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

    public function getNrOrdem()
    {
        return $this->nrOrdem;
    }

    public function setNrOrdem($nrOrdem)
    {
        $this->nrOrdem = $nrOrdem;

        return $this;
    }
}
?>