<?php
require_once "../config/config.php";
require_once "../model/UsuariosModel.php";

session_start();
function verificarToken($token, $claveSecreta)
{
    $url = "https://www.google.com/recaptcha/api/siteverify";
    $datos = [
        "secret" => $claveSecreta,
        "response" => $token,
    ];
    $opciones = array(
        "http" => array(
            "header" => "Content-type: application/x-www-form-urlencoded\r\n",
            "method" => "POST",
            "content" => http_build_query($datos),
        ),
    );
    $contexto = stream_context_create($opciones);
    $resultado = file_get_contents($url, false, $contexto);
    if ($resultado === false) {
        return false;
    }
    $resultado = json_decode($resultado);
    $pruebaPasada = $resultado->success;
    return $pruebaPasada;
}

try {
    if (!isset($_POST["g-recaptcha-response"]) || empty($_POST["g-recaptcha-response"])) {
        $_SESSION['tipo_error'] = "Error al iniciar sesion";
        $_SESSION['error'] = "Debes completar el captcha";
        header("Location: ../index.php");
        exit();
    }
    $token = $_POST["g-recaptcha-response"];
    $verificado = verificarToken($token, "6LewSZQoAAAAACXf6BiaYLRHHFk1S8N-LmkRn-HC");
    if ($verificado == false) {
        $_SESSION['tipo_error'] = "Error al iniciar sesion";
        $_SESSION['error'] = "Error al procesar el captcha";
        header("Location: ../index.php");
        exit();
    }



    $usuario = array();
    $usuario['user'] = $_POST['user-r'];
    $usuario['password'] = $_POST['password-r'];
    $usuario['nombre'] = $_POST['nombre-r'];
    $usuario['apellido'] = $_POST['apellido-r'];
    $usuario['dni'] = $_POST['dni-r'];
    $usuario['correo'] = $_POST['correo-r'];


    $userModel = new UsuariosModel();

    if (!$userModel->existeUsuario($usuario)) {
        $userModel->insertarUsuario($usuario);
        $userModel->agregarCajaRandom($usuario);
        $_SESSION['user'] = $usuario['user'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['apellido'] = $usuario['apellido'];
        $_SESSION['logged_in'] = true;
        

        header("Location: ../index.php?c=usuarios&a=inicio");
        exit();
    } else {
        $_SESSION['tipo_error'] = "Error al procesar el registro";
        $_SESSION['error'] = "El usuario ya se encuentra registrado";

        header("Location: ../index.php?c=usuarios&a=registro");
        exit();
    }

} catch (Exception $error) {
    die("Error:    " . $error->getMessage());
}
?>