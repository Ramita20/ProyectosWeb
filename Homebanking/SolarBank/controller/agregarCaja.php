<?php

require_once("../model/UsuariosModel.php");

session_start();

$caja['num_caja'] = random_int(100000, 999999);
$caja['cbu'] = random_int(100000000000, 999999000000);
$caja['dinero'] = 0;

$userModel = new UsuariosModel();

$userModel->agregarCaja($_SESSION['user'], $caja);

header("Location: ../index.php?c=usuarios&a=inicio");
exit();

?>