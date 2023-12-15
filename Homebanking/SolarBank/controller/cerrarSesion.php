<?php
session_start();

unset($_SESSION['nombre']);
unset($_SESSION['apellido']);
unset($_SESSION['correo']);
unset($_SESSION['user']);
unset($_SESSION['logged_in']);

session_destroy();

header("Location: ../index.php");
exit();
?>