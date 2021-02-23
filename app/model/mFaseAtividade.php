<?php
/**
* Fase Objeto
*/
class mFaseAtividade extends cTpAtividade
{

	public $cdFaseAtividade;
    public $dsFaseAtividade;
	public $nrOrdem;
	public $snAtivo;

	public function __construct($cdFaseAtividade=null, $dsFaseAtividade=null, $nrOrdem =null, $snAtivo=null)
	{
		$this->cdFaseAtividade = $cdFaseAtividade;
        $this->dsFaseAtividade = $dsFaseAtividade;
		$this->nrOrdem         = $nrOrdem;
		$this->snAtivo 		   = $snAtivo;
	}


    public function getCdFaseAtividade()
    {
        return $this->cdFaseAtividade;
    }

    public function setCdFaseAtividade($cdFaseAtividade)
    {
        $this->cdFaseAtividade = $cdFaseAtividade;

        return $this;
    }

    public function getDsFaseAtividade()
    {
        return $this->dsFaseAtividade;
    }

    public function setDsFaseAtividade($dsFaseAtividade)
    {
        $this->dsFaseAtividade = $dsFaseAtividade;

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