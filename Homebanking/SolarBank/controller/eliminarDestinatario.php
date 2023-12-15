<?php
require_once("../model/UsuariosModel.php");

session_start();

$userModel = new UsuariosModel();

$usuario = array();
$usuario = $userModel->traerUsuario($_SESSION['user']);

$eliminarElemento = $_POST['id'];

$userModel->eliminarDestinatarios($usuario['id'], $_POST['id']);


header("Location: ../index.php?c=usuarios&a=inicio");
exit();
?>