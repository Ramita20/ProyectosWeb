<head>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="./js/alertas.js"></script>
</head>

<?php
require_once("../model/UsuariosModel.php");

session_start();

$userModel = new UsuariosModel();

if ($userModel->existeCBU_Alias($_POST['cbu-alias'])) {

    $user = $userModel->traerUsuario($_SESSION['user']);
    $userDest = $userModel->traerUsuarioPorCBU_Alias($_POST['cbu-alias']);
    $userModel->agregarDestinatario($user['id'], $userDest['id']);

} else {
    /*echo "
              <script>
                  Error('Error al a�adir un Destinatario','El usuario o el cbu no existe');
              </script
          ";
     */
}



header("Location: ../index.php?c=usuarios&a=inicio");
exit();
?>