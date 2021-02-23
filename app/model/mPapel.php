<?php
/**
 * mPapel
 */
class mPapel
{

	public $cdPapel;
	public $dsPapel;
	public $snAtivo;


	public function __construct($cdPapel="", $dsPapel="", $snAtivo="")
	{
		$this->cdPapel = $cdPapel;
		$this->dsPapel = $dsPapel;
		$this->snAtivo = $snAtivo;
	}

	public function getCdPapel()
	{
		return $this->cdPapel;
	}

	public function setCdPapel($cdPapel)
	{
		$this->cdPapel = $cdPapel;

		return $this;
	}

	public function getDsPapel()
	{
		return $this->dsPapel;
	}

	public function setDsPapel($dsPapel)
	{
		$this->dsPapel = $dsPapel;

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