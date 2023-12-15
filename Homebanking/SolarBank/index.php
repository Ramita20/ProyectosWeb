<?php
require_once("config/config.php");
require_once("core/routes.php");
require_once("config/database.php");
require_once("controller/Usuarios.php");

session_start();

//Si existe una sesion, muestra su usuario y le permite finalizar la sesion.
if (isset($_SESSION['logged_in'])) {
    $loguedBy = '<p id="logueado-como">Sesion iniciada como ' . $_SESSION['user'] . '</p>';
    $closeSession = '<a id="salir-boton" href="controller/cerrarSesion.php">Cerrar sesion</a>';
} else {
    $loguedBy = "";
    $closeSession = "";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://www.google.com/recaptcha/api.js?hl=es" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"
        integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s"
        crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="js/alertas.js"></script>
    <title>Trabajo Práctico</title>
</head>

<body>
    <header id="cabecera-pagina">
        <?php echo $loguedBy ?>
        <img id="icono-sol" src="icons/luz-del-sol.png" alt="Icono sol" />
        <h1 id="nombre-banco">Solar Bank</h1>
        <?php echo $closeSession ?>
    </header>

    <?php

    //CHECK PARA SCRIPT
    if (isset($_SESSION['error'])) {
        $error = $_SESSION['tipo_error'];
        $mensaje = $_SESSION['error'];
        echo "
              <script>
                  Error('$error','$mensaje');                                               
              </script>
              ";
        unset($_SESSION['tipo_error']);
        unset($_SESSION['error']);
    }
    //FINALIZA CHECK
    
    if (isset($_SESSION['logged_in'])) { //Si existe sesion, redirecciona a inicio.
        if (isset($_GET['c']) && isset($_GET['a'])) { //Si existe controlador y accion, sigue.
            if (ucwords($_GET['c']) == "Usuarios" && strtolower($_GET['a']) == "login") { //Si controlador y accion son iguales a "Usuarios" y "login", redirige a inicio.
                $controller = loadController(CONTROLADOR_PRINCIPAL);
                loadAction($controller, "inicio");
            } else { //Si no, continua con las comprobaciones.
                $controller = loadController($_GET['c']);
                loadAction($controller, $_GET['a']);
            }
        } else { //Si no, redirecciona a inicio.
            $controller = loadController(CONTROLADOR_PRINCIPAL);
            loadAction($controller, "inicio");
        }
    } else { //Si no, comprueba si el usuario quiere registrarse.
        if (isset($_GET['c']) && isset($_GET['a'])) {
            if (ucwords($_GET['c']) == "Usuarios" && strtolower($_GET['a']) == "registro") {
                $controller = loadController(CONTROLADOR_PRINCIPAL);
                loadAction($controller, "registro");
            }
        } else { //Si no es asi, redirecciona al login.
            $controller = loadController(CONTROLADOR_PRINCIPAL);
            loadAction($controller, ACCION_PRINCIPAL);
        }
    }

    ?>


    <footer id="pie-pagina">
        <p>Copyright 2023 | Programación Avanzada</p>
    </footer>
    <script src="<?php echo ROOT ?>/script.js"></script>
</body>

</html>