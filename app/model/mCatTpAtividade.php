<?php


class mCatTpAtividade
{

	public $cdCatTpAtividade;
	public $dsCatTpAtividade;
	public $snAtivo;

	public function __construct($cdCatTpAtividade=null, $dsCatTpAtividade=null, $snAtivo=null)
	{
		$this->cdCatTpAtividade = $cdCatTpAtividade;
		$this->dsCatTpAtividade = $dsCatTpAtividade;
		$this->snAtivo 				= $snAtivo;
	}


    public function getCdTpLicenca()
    {
        return $this->cdCatTpAtividade;
    }

    public function setCdTpLicenca($cdCatTpAtividade)
    {
        $this->cdCatTpAtividade = $cdCatTpAtividade;

        return $this;
    }

    public function getDsTpLicenca()
    {
        return $this->dsCatTpAtividade;
    }

    public function setDsTpLicenca($dsCatTpAtividade)
    {
        $this->dsCatTpAtividade = $dsCatTpAtividade;

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