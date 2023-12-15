<?php
function loadController($controller) //Carga un controlador segun el solicitado por parametro.
{
    $controllerName = ucwords($controller) . "Controller";
    $controllerFile = "controller/" . ucwords($controller) . ".php";

    if (!is_file($controllerFile)) {
        $controllerFile = "controller" . CONTROLADOR_PRINCIPAL . ".php";
    }

    require_once $controllerFile;
    $control = new $controllerName();
    return $control;
}

function loadAction($controller, $action) //Carga una accion solicitada por parametro, segun el controlador.
{
    if (isset($action) && method_exists($controller, $action)) {
        $controller->$action();
    } else {
        $controller->login();
    }
}
?>