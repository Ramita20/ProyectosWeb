<?php
require_once "../model/UsuariosModel.php";

session_start();

$userModel = new UsuariosModel();

if ($userModel->existeCBU_Alias($_POST['origen']) && $userModel->existeCBU_Alias($_POST['destino'])) {
    if ($userModel->cajaEsDeUsuario($_SESSION['user'], $_POST['origen'])) {

        $caja = $userModel->traerCajaPorCBU_Alias($_POST['origen']);
        if ($caja['monto'] >= $_POST['monto']) {
            $monto_nuevo = $caja['monto'] - $_POST['monto'];
            $userModel->actulizarMonto($_POST['origen'], $monto_nuevo);
        } else {
            $_SESSION['tipo_error'] = "Fondos insuficientes";
            $_SESSION['error'] = "La caja seleccionada no posee los fondos suficientes para su transferencia.";
            header("Location: ../index.php?c=usuarios&a=inicio");
            exit();
        }

        $caja = $userModel->traerCajaPorCBU_Alias($_POST['destino']);
        $monto_nuevo = $caja['monto'] + $_POST['monto'];
        $userModel->actulizarMonto($_POST['destino'], $monto_nuevo);

        $transferencia = array();
        $transferencia['origen'] = $_POST['origen'];
        $transferencia['destino'] = $_POST['destino'];
        $transferencia['monto'] = $_POST['monto'];
        $transferencia['fecha'] = date('Y-m-d');

        $userModel->agregarTranferencia($transferencia);
    } else {
        $_SESSION['tipo_error'] = "CBU o Alias propio.";
        $_SESSION['error'] = "El CBU o Alias de origen ingresado no corresponde a ninguna caja.";
    }
} else {
    $_SESSION['tipo_error'] = "CBU o Alias inexistente.";
    $_SESSION['error'] = "El CBU o Alias de origen y/o destino ingresado no corresponde a ninguna caja.";
}

header("Location: ../index.php?c=usuarios&a=inicio");
exit();
?>