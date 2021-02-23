<?php

/**
 * mAuditoria
 */
class mAuditoria extends mModulo
{
	protected $tpAcao;
	protected $nmTabela;
	protected $dsDadosAnteriores;
	protected $dsDadosAtuais;

    /**
     * @return mixed
     */
    public function getTpAcao()
    {
        return $this->tpAcao;
    }

    /**
     * @param mixed $tpAcao
     *
     * @return self
     */
    public function setTpAcao($tpAcao)
    {
        $this->tpAcao = $tpAcao;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNmTabela()
    {
        return $this->nmTabela;
    }

    /**
     * @param mixed $nmTabela
     *
     * @return self
     */
    public function setNmTabela($nmTabela)
    {
        $this->nmTabela = $nmTabela;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDsDadosAnteriores()
    {
        return $this->dsDadosAnteriores;
    }

    /**
     * @param mixed $dsDadosAnteriores
     *
     * @return self
     */
    public function setDsDadosAnteriores($dsDadosAnteriores)
    {
        $this->dsDadosAnteriores = $dsDadosAnteriores;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDsDadosAtuais()
    {
        return $this->dsDadosAtuais;
    }

    /**
     * @param mixed $dsDadosAtuais
     *
     * @return self
     */
    public function setDsDadosAtuais($dsDadosAtuais)
    {
        $this->dsDadosAtuais = $dsDadosAtuais;

        return $this;
    }
}

?>