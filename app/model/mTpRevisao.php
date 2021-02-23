<?php
/**
 * mTpRevisao
 */
class mTpRevisao extends mEmpreendimento
{
    public $cdTpRevisao;
    public $dsTpRevisao;

    public function __construct($cdTpRevisao=null, $dsTpRevisao=null, $snAtivo=null)
    {
        $this->cdTpRevisao = $cdTpRevisao;
        $this->dsTpRevisao = $dsTpRevisao;
        $this->snAtivo     = $snAtivo;
    }

    public function getDsTpRevisao()
    {
        return $this->dsTpRevisao;
    }

    public function setDsTpRevisao($dsTpRevisao)
    {
        $this->dsTpRevisao = $dsTpRevisao;

        return $this;
    }

    public function getCdTpRevisao()
    {
        return $this->cdTpRevisao;
    }

    public function setCdTpRevisao($cdTpRevisao)
    {
        $this->cdTpRevisao = $cdTpRevisao;

        return $this;
    }
}
?>