<?php

/**
 *
 */
class Anexo
{

	private $message;


    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

	function __construct($arquivo, $caminho, $extensoes = array('jpg', 'jpeg', 'png', 'gif', 'pdf','doc','docx'))
	{
		// Pasta onde o arquivo vai ser salvo
		$_UP['pasta'] = $caminho;

		// Tamanho máximo do arquivo (em Bytes)
		$_UP['tamanho'] = 1024 * 1024 * 20; // 2Mb

		// Array com as extensões permitidas
		$_UP['extensoes'] = array('jpg', 'jpeg', 'png', 'gif', 'pdf','doc','docx');

		// Renomeia o arquivo? (Se true, o arquivo será salvo como .jpg e um nome único)
		$_UP['renomeia'] = false;

		// Array com os tipos de erros de upload do PHP
		$_UP['erros'][0] = 'Não houve erro';
		$_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
		$_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
		$_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
		$_UP['erros'][4] = 'Não foi feito o upload do arquivo';


		// Faz a verificação da extensão do arquivo
		@$extensao = strtolower(end(explode('.', $arquivo['name'])));

		if (array_search($extensao, $_UP['extensoes']) === false) {
			$this->message = array(
					"success" => false,
					"message" => "Por favor, envie arquivos com as seguintes extensões: JPG, PNG, GIF, PDF, DOC ou DOCX"
				);
		}

		if ($_UP['tamanho'] < $arquivo['size']) {

			$this->message = array(
					"success" => false,
					"message" => "O arquivo enviado é muito grande, envie arquivos de até 2Mb."
				);

		}

		$dsAnexo = $arquivo['name'];

		$dsAnexo =  self::tirarAcentos($dsAnexo);

		if (is_dir($_UP['pasta'])){

		}else{
			mkdir($_UP['pasta'], 0777, true);
		}

		// Depois verifica se é possível mover o arquivo para a pasta escolhida
		if (move_uploaded_file($arquivo['tmp_name'], $_UP['pasta'] . DIRECTORY_SEPARATOR .$dsAnexo)) {

			$this->message = array(
					"success" => true,
					"filename" => $dsAnexo
				);

		} else {

			$this->message = array(
					"success" => false,
					"message" => "Falha ao mover o arquivo"
				);

		}
	}

	public function tirarAcentos($string){

		return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);

	}
}

?>