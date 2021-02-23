<?php
/**
 * 
 */
class cSeguranca extends mUsuario
{
	public function login($login, $dsSenha)
	{
		$mysql = MysqlConexao::getInstance();

		$sql = "SELECT cd_usuario, login, nm_usuario, sn_ativo FROM g_usuario WHERE login = :login AND ds_senha = :dsSenha";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":login", $login);
		$stmt->bindParam(":dsSenha", $dsSenha);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if ($num > 0) {
				$reg = $stmt->fetch(PDO::FETCH_OBJ);

				$sn_ativo = $reg->sn_ativo;

				if ($sn_ativo == 'N') {
					return "B";
				}else{
					return "S";
				}

			}else{
				return "U";

			}
		}else{
			var_dump($stmt->errorInfo());
		}
	}

	public static function validaSessao()
	{
		@session_start();
		if(isset($_SESSION['cdUsuario'])){

		}else{
			session_unset();
			echo '<script>window.location.href="../?auth=false";</script>';
			//parar execucao php
			exit();
		}
	}

	public function logOut()
	{
		session_start();
		session_unset();
		session_destroy();
		echo '<script>window.location.href="../../?auth=false";</script>';
		//parar execucao php
		exit();
	}

	public static function geraToken($tamanho = 50, $maiusculas = true, $numeros = true, $simbolos = false){
		// Caracteres de cada tipo
		$lmin = 'abcdefghijklmnopqrstuvwxyz';
		$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$num = '1234567890';
		$simb = '!@#$%*-';

		// Variáveis internas
		$retorno = '';
		$caracteres = '';

		// Agrupamos todos os caracteres que poderão ser utilizados
		$caracteres .= $lmin;
		if ($maiusculas) $caracteres .= $lmai;
		if ($numeros) $caracteres .= $num;
		if ($simbolos) $caracteres .= $simb;

		// Calculamos o total de caracteres possíveis
		$len = strlen($caracteres);

		for ($n = 1; $n <= $tamanho; $n++) {
			// Criamos um número aleatório de 1 até $len para pegar um dos caracteres
			$rand = mt_rand(1, $len);
			// Concatenamos um dos caracteres na variável $retorno
			$retorno .= $caracteres[$rand-1];
		}

		return $retorno;
	}
}

?>