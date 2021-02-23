<?php
class MysqlConexao extends PDO {

    private static $instancia;

    public function Conexao($dsn, $username = "", $password = "") {
        // O construtro abaixo é o do PDO
        parent::__construct($dsn, $username, $password);
    }

    public static function getInstance() {
        // Se o a instancia não existe eu faço uma
        if(!isset( self::$instancia )){
            try {
                // self::$instancia = new MysqlConexao("mysql:host=localhost;dbname=boeckman_ecosis_sml;charset=utf8", "boeckman_heytor", base64_decode("MzI1Njk4NzQxRG90QA=="));
                // self::$instancia = new MysqlConexao("mysql:host=mysql.calango.eng.br;dbname=calango01;charset=utf8", "calango01", "calango2019");
                self::$instancia = new MysqlConexao("mysql:host=localhost;dbname=calango01;charset=utf8", "root", "");
            } catch ( Exception $e ) {
                echo 'Erro ao conectar o mysql';
                exit ();
            }
        }
        // Se já existe instancia na memória eu retorno ela
        return self::$instancia;
    }
}
?>