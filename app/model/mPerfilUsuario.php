<?php
/**
 * mPerfilUsuario
 */
class mPerfilUsuario extends cPagina
{


  public $cdPerfilUsuario;
  public $dsPerfilUsuario;
  public $snAtivo;

  public function __construct($cdPerfilUsuario="", $dsPerfilUsuario="", $snAtivo="")
  {
    $this->cdPerfilUsuario = $cdPerfilUsuario;
    $this->dsPerfilUsuario = $dsPerfilUsuario;
    $this->snAtivo = $snAtivo;
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

  public function getDsPerfilUsuario()
  {
    return $this->dsPerfilUsuario;
  }

  public function setDsPerfilUsuario($dsPerfilUsuario)
  {
    $this->dsPerfilUsuario = $dsPerfilUsuario;

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