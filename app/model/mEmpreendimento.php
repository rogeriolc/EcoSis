<?php

class mEmpreendimento extends mCliente {

    public $cdEmpreendimento;
    public $nmEmpreendimento;
    public $cdCliente;
    public $cdPorteEmpreendimento;
    public $cdTipografia;
    public $cdPotencialPoluidor;
    public $cdTpEmpreendimento;
    public $dsTpEmpreendimento;
    public $dsArea;
    public $cdCep;
    public $dsEndereco;
    public $nmBairro;
    public $nmCidade;
    public $uf;
    public $dsEmpreendimento;
    public $cdEmpresa;
    public $snAtivo;

    public $cdRevEmpreendimento;
    public $qtdDormitorios;
    public $qtdBanheiros;
    public $qtdUnidades;
    public $cdAbastecimento;
    public $dsTamanhoUnidade;
    public $dsAbastecimento;
    public $snOutorgaAbastecimento;
    public $cdTratamentoAfluente;
    public $dsTratamentoAfluentes;
    public $snOutorgaTratamentoAfluente;
    public $snTerraplanagem;
    public $snSuspensaoErradicacao;


    public function __construct($cdEmpreendimento=null, $nmEmpreendimento=null, $cdCliente=null, $cdPorteEmpreendimento = null, $cdTipografia = null, $cdPotencialPoluidor = null, $dsArea = null, $cdCep = null, $dsEndereco = null, $nmBairro = null, $nmCidade = null, $uf = null, $dsEmpreendimento = null, $cdEmpresa=null, $snAtivo=null) {

        $this->cdEmpreendimento         = $cdEmpreendimento;
        $this->nmEmpreendimento         = $nmEmpreendimento;
        $this->cdCliente                = $cdCliente;
        $this->cdPorteEmpreendimento    = $cdPorteEmpreendimento;
        $this->cdTipografia             = $cdTipografia;
        $this->cdPotencialPoluidor      = $cdPotencialPoluidor;
        $this->dsArea                   = $dsArea;
        $this->cdCep                    = $cdCep;
        $this->dsEndereco               = $dsEndereco;
        $this->nmBairro                 = $nmBairro;
        $this->nmCidade                 = $nmCidade;
        $this->uf                       = $uf;
        $this->dsEmpreendimento         = $dsEmpreendimento;
        $this->cdEmpresa                = $cdEmpresa;
        $this->snAtivo                  = $snAtivo;

    }

    public function getCdEmpreendimento() {
        return $this->cdEmpreendimento;
    }

    public function setCdEmpreendimento($cdEmpreendimento) {
        $this->cdEmpreendimento = $cdEmpreendimento;

        return $this;
    }

    public function getNmEmpreendimento() {
        return $this->nmEmpreendimento;
    }

    public function setNmEmpreendimento($nmEmpreendimento) {
        $this->nmEmpreendimento = $nmEmpreendimento;

        return $this;
    }

    public function getCdEmpresa() {
        return $this->cdEmpresa;
    }

    public function setCdEmpresa($cdEmpresa) {
        $this->cdEmpresa = $cdEmpresa;

        return $this;
    }

    public function getSnAtivo() {
        return $this->snAtivo;
    }

    public function setSnAtivo($snAtivo) {
        $this->snAtivo = $snAtivo;

        return $this;
    }

    public function getCdCliente()
    {
        return $this->cdCliente;
    }

    public function setCdCliente($cdCliente)
    {
        $this->cdCliente = $cdCliente;

        return $this;
    }

    public function getCdTpEmpreendimento()
    {
        return $this->cdTpEmpreendimento;
    }

    public function setCdTpEmpreendimento($cdTpEmpreendimento)
    {
        $this->cdTpEmpreendimento = $cdTpEmpreendimento;

        return $this;
    }

    public function getDsTpEmpreendimento()
    {
        return $this->dsTpEmpreendimento;
    }

    public function setDsTpEmpreendimento($dsTpEmpreendimento)
    {
        $this->dsTpEmpreendimento = $dsTpEmpreendimento;

        return $this;
    }

    public function getQtdDormitorios()
    {
        return $this->qtdDormitorios;
    }

    public function setQtdDormitorios($qtdDormitorios)
    {
        $this->qtdDormitorios = $qtdDormitorios;

        return $this;
    }

    public function getQtdBanheiros()
    {
        return $this->qtdBanheiros;
    }

    public function setQtdBanheiros($qtdBanheiros)
    {
        $this->qtdBanheiros = $qtdBanheiros;

        return $this;
    }

    public function getQtdUnidades()
    {
        return $this->qtdUnidades;
    }

    public function setQtdUnidades($qtdUnidades)
    {
        $this->qtdUnidades = $qtdUnidades;

        return $this;
    }

    public function getDsTamanhoUnidade()
    {
        return $this->dsTamanhoUnidade;
    }

    public function setDsTamanhoUnidade($dsTamanhoUnidade)
    {
        $this->dsTamanhoUnidade = $dsTamanhoUnidade;

        return $this;
    }

    public function getDsAbastecimento()
    {
        return $this->dsAbastecimento;
    }

    public function setDsAbastecimento($dsAbastecimento)
    {
        $this->dsAbastecimento = $dsAbastecimento;

        return $this;
    }

    public function getDsTratamentoAfluentes()
    {
        return $this->dsTratamentoAfluentes;
    }

    public function setDsTratamentoAfluentes($dsTratamentoAfluentes)
    {
        $this->dsTratamentoAfluentes = $dsTratamentoAfluentes;

        return $this;
    }

    public function getSnTerraplanagem()
    {
        return $this->snTerraplanagem;
    }

    public function setSnTerraplanagem($snTerraplanagem)
    {
        $this->snTerraplanagem = $snTerraplanagem;

        return $this;
    }

    public function getSnSuspensaoErradicacao()
    {
        return $this->snSuspensaoErradicacao;
    }

    public function setSnSuspensaoErradicacao($snSuspensaoErradicacao)
    {
        $this->snSuspensaoErradicacao = $snSuspensaoErradicacao;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCdPorteEmpreendimento()
    {
        return $this->cdPorteEmpreendimento;
    }

    /**
     * @param mixed $cdPorteEmpreendimento
     *
     * @return self
     */
    public function setCdPorteEmpreendimento($cdPorteEmpreendimento)
    {
        $this->cdPorteEmpreendimento = $cdPorteEmpreendimento;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCdPotencialPoluidor()
    {
        return $this->cdPotencialPoluidor;
    }

    /**
     * @param mixed $cdPotencialPoluidor
     *
     * @return self
     */
    public function setCdPotencialPoluidor($cdPotencialPoluidor)
    {
        $this->cdPotencialPoluidor = $cdPotencialPoluidor;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDsArea()
    {
        return $this->dsArea;
    }

    /**
     * @param mixed $dsArea
     *
     * @return self
     */
    public function setDsArea($dsArea)
    {
        $this->dsArea = $dsArea;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCdCep()
    {
        return $this->cdCep;
    }

    /**
     * @param mixed $cdCep
     *
     * @return self
     */
    public function setCdCep($cdCep)
    {
        $this->cdCep = $cdCep;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDsEndereco()
    {
        return $this->dsEndereco;
    }

    /**
     * @param mixed $dsEndereco
     *
     * @return self
     */
    public function setDsEndereco($dsEndereco)
    {
        $this->dsEndereco = $dsEndereco;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNmBairro()
    {
        return $this->nmBairro;
    }

    /**
     * @param mixed $nmBairro
     *
     * @return self
     */
    public function setNmBairro($nmBairro)
    {
        $this->nmBairro = $nmBairro;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNmCidade()
    {
        return $this->nmCidade;
    }

    /**
     * @param mixed $nmCidade
     *
     * @return self
     */
    public function setNmCidade($nmCidade)
    {
        $this->nmCidade = $nmCidade;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUf()
    {
        return $this->uf;
    }

    /**
     * @param mixed $uf
     *
     * @return self
     */
    public function setUf($uf)
    {
        $this->uf = $uf;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDsEmpreendimento()
    {
        return $this->dsEmpreendimento;
    }

    /**
     * @param mixed $dsEmpreendimento
     *
     * @return self
     */
    public function setDsEmpreendimento($dsEmpreendimento)
    {
        $this->dsEmpreendimento = $dsEmpreendimento;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCdAbastecimento()
    {
        return $this->cdAbastecimento;
    }

    /**
     * @param mixed $cdAbastecimento
     *
     * @return self
     */
    public function setCdAbastecimento($cdAbastecimento)
    {
        $this->cdAbastecimento = $cdAbastecimento;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSnOutorgaAbastecimento()
    {
        return $this->snOutorgaAbastecimento;
    }

    /**
     * @param mixed $snOutorgaAbastecimento
     *
     * @return self
     */
    public function setSnOutorgaAbastecimento($snOutorgaAbastecimento)
    {
        $this->snOutorgaAbastecimento = $snOutorgaAbastecimento;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCdTratamentoAfluente()
    {
        return $this->cdTratamentoAfluente;
    }

    /**
     * @param mixed $cdTratamentoAfluente
     *
     * @return self
     */
    public function setCdTratamentoAfluente($cdTratamentoAfluente)
    {
        $this->cdTratamentoAfluente = $cdTratamentoAfluente;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSnOutorgaTratamentoAfluente()
    {
        return $this->snOutorgaTratamentoAfluente;
    }

    /**
     * @param mixed $snOutorgaTratamentoAfluente
     *
     * @return self
     */
    public function setSnOutorgaTratamentoAfluente($snOutorgaTratamentoAfluente)
    {
        $this->snOutorgaTratamentoAfluente = $snOutorgaTratamentoAfluente;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCdRevEmpreendimento()
    {
        return $this->cdRevEmpreendimento;
    }

    /**
     * @param mixed $cdRevEmpreendimento
     *
     * @return self
     */
    public function setCdRevEmpreendimento($cdRevEmpreendimento)
    {
        $this->cdRevEmpreendimento = $cdRevEmpreendimento;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCdTipografia()
    {
        return $this->cdTipografia;
    }

    /**
     * @param mixed $cdTipografia
     *
     * @return self
     */
    public function setCdTipografia($cdTipografia)
    {
        $this->cdTipografia = $cdTipografia;

        return $this;
    }
}