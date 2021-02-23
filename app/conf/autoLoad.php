<?php
date_default_timezone_set('America/Sao_Paulo');

function __autoload($classe) {

    $indexCamadaClasse = substr($classe, 0, 1);

    switch ($indexCamadaClasse) {
        case 'm':
            $camadaClasse = 'model';
            break;

        case 'c':
            $camadaClasse = 'control';
            break;

        default:
            $camadaClasse = '';
            break;
    }

    if (!empty($camadaClasse)) {

        if (is_dir("app" . DIRECTORY_SEPARATOR . $camadaClasse)) {

            include_once "app" . DIRECTORY_SEPARATOR . $camadaClasse . DIRECTORY_SEPARATOR . "{$classe}.php";

        } else {
            if (is_dir($camadaClasse)) {

                include_once $camadaClasse . DIRECTORY_SEPARATOR . "{$classe}.php";

            } else {
                if (is_dir(".." . DIRECTORY_SEPARATOR . $camadaClasse)) {

                    include_once ".." . DIRECTORY_SEPARATOR . $camadaClasse . DIRECTORY_SEPARATOR . "{$classe}.php";

                } else {

                    include_once "{$classe}.php";

                }
            }
        }
    } else {
        if (is_dir(".." . DIRECTORY_SEPARATOR . "conf")) {

            include_once ".." . DIRECTORY_SEPARATOR . "conf" . DIRECTORY_SEPARATOR . "{$classe}.php";

        } else {
            if (is_dir("conf" . DIRECTORY_SEPARATOR . $camadaClasse)) {

                include_once "conf" . DIRECTORY_SEPARATOR . "{$classe}.php";

            }
        }
    }
}

function regLog($dsLog, $nmArquivoPHP, $nrLinha = "") {
    $data = date("d/m/Y");
    $hora = date("H:i:s");

    //dados do usuario
    $cd_usuario = 1;//$_SESSION['cdUsuario'];
    $nm_usuario = 1;//$_SESSION['nmUsuario'];

    //nome do arquivo
    $nmArquivo = "error_log.txt";

    //texto escrito no log
    $dsTexto = "[$nmArquivoPHP]:  $dsLog \n\n";
    $dsTexto = (!empty($nrLinha)) ? "\nLinha: $nrLinha" : "";

    $manipular = fopen("$nmArquivo", "a+b");
    fwrite($manipular, $dsTexto);
    fclose($manipular);
}

?>