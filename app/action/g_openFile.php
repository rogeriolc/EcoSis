<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$dropbox 	= new cDropbox();

$id = isset($_GET['id']) ? base64_decode($_GET['id']) : null;

if (is_null($id)) {
    echo 'Parametro do arquivo inválido';
    return;
}

$fileData = $dropbox->get($id);

if ($fileData->metadata->id) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>

        <p>Seu download será iniciado automáticamente...</p>
        <p>Caso isso não ocorra clique <a href="<?php echo $fileData->link; ?>">aqui</a> para baixar novamente.</p>
            
        <script>
            window.location.href = "<?php echo $fileData->link; ?>";
        </script>
    </body>
    </html>
    <?php
}