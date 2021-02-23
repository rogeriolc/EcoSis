<?php

interface baseInterface{

	//Realiza o cadastro
	public function Cadastrar();
	//Altera o cadastro
	public function Alterar();
	//Lista os dados em formato de tabela
	public function ListarTable();
	//Lista os dados em formato de select > option
	public function ListarOption();
	//Construtor genérico
	public function Dados();

}

?>