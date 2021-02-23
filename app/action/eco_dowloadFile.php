<?php
// header('Content-Description: File Transfer');
// header('Content-Type: application/octet-stream');
// header('Content-Disposition: attachment; filename="file.txt"');
// header('Expires: 0');
// header('Cache-Control: must-revalidate');
// header('Pragma: public');

session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$dropbox          = new cDropbox();
$fileData = $dropbox->get('id:21jBdEBEl4AAAAAAAAiINg');

var_dump($fileData);

// $dropBoxUpload = $dropbox->download('id:21jBdEBEl4AAAAAAAAiINg');
