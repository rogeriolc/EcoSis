<?php

class mUsuario extends mPessoa {

    public $cdUsuario;
    public $login;
    public $dsSenha;
    public $snAtivo;
    public $dsEmail;
    public $dsImagem;
    public $cdEmpresa;
    protected $cdPerfilUsuario;

    public function __construct($cdUsuario="", $nmPessoa = "", $login="", $dsSenha="", $snAtivo="", $dsEmail="", $dsImagem="",$cdEmpresa="")
    {
        $this->cdUsuario = $cdUsuario;
        $this->nmPessoa = $nmPessoa;
        $this->login = $login;
        $this->dsSenha = $dsSenha;
        $this->snAtivo = $snAtivo;
        $this->dsEmail = $dsEmail;
        $this->dsImagem = $dsImagem;
        $this->cdEmpresa = $cdEmpresa;
    }

    public function getCdUsuario()
    {
        return $this->cdUsuario;
    }

    public function setCdUsuario($cdUsuario)
    {
        $this->cdUsuario = $cdUsuario;

        return $this;
    }

    public function getlogin()
    {
        return $this->login;
    }

    public function setlogin($login)
    {
        $this->login = $login;

        return $this;
    }

    public function getDsSenha()
    {
        return $this->dsSenha;
    }

    public function setDsSenha($dsSenha)
    {
        $this->dsSenha = $dsSenha;

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

    public function getDsEmail()
    {
        return $this->dsEmail;
    }

    public function setDsEmail($dsEmail)
    {
        $this->dsEmail = $dsEmail;

        return $this;
    }

    public function getDsImagem()
    {
        return $this->dsImagem;
    }

    public function setDsImagem($dsImagem)
    {
        $this->dsImagem = $dsImagem;

        return $this;
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

    public function getCdPerfilUsuario()
    {
        return $this->cdPerfilUsuario;
    }

    public function setCdPerfilUsuario($cdPerfilUsuario)
    {
        $this->cdPerfilUsuario = $cdPerfilUsuario;

        return $this;
    }
}