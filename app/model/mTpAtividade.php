<?php
/**
 * TpAtividade
 */
class mTpAtividade extends mAtividade
{

	public $cdTpAtividade;
	public $dsTpAtividade;
    public $cdCatTpAtividade;
	public $snAtivo;


	public function __construct($cdTpAtividade=null, $dsTpAtividade=null, $snAtivo=null)
	{
		$this->cdTpAtividade = $cdTpAtividade;
		$this->dsTpAtividade = $dsTpAtividade;
		$this->snAtivo 		 = $snAtivo;
	}

    public function getCdTpAtividade()
    {
        return $this->cdTpAtividade;
    }

    public function setCdTpAtividade($cdTpAtividade)
    {
        $this->cdTpAtividade = $cdTpAtividade;

        return $this;
    }

    public function getDsTpAtividade()
    {
        return $this->dsTpAtividade;
    }


    public function setDsTpAtividade($dsTpAtividade)
    {
        $this->dsTpAtividade = $dsTpAtividade;

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

    public function getCdCatTpAtividade()
    {
        return $this->cdCatTpAtividade;
    }

    public function setCdCatTpAtividade($cdCatTpAtividade)
    {
        $this->cdCatTpAtividade = $cdCatTpAtividade;

        return $this;
    }
}
?>