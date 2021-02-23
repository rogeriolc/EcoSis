<?php
// header("Content-Type: application/json", true);

session_start();

// $cdUsuarioSessao = $_SESSION['cdUsuario'];

// if(isset($cdUsuarioSessao)){

// 	$arraySession = array("session_validate" => true);
// 	echo json_encode($arraySession);

// }else{
// 	session_unset();

// 	$arraySession = array("session_validate" => false);
// 	echo json_encode($arraySession);

// }

session_unset();

?>