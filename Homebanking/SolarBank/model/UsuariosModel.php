<?php
require_once __DIR__ . "/../config/database.php";

class UsuariosModel
{
  private $conexion; //Almacena la conexion.

  public function __construct()
  {
    $this->conexion = Conect::conexion();
  }

  //Añade un nuevo usuario a la db.
  public function insertarUsuario($usuario)
  {
    $hash = password_hash($usuario['password'], PASSWORD_DEFAULT);
    $this->conexion->query(
      "INSERT INTO credenciales(user, password)
      VALUES ('" . $usuario['user'] . "', '" . $hash . "');
      "
    );

    $this->conexion->query(
      "INSERT INTO clientes(nombre, apellido, dni, id_credencial) 
      VALUES ('" . $usuario['nombre'] . "',
      '" . $usuario['apellido'] . "',
      " . $usuario['dni'] . ",
      (SELECT cr.id FROM credenciales cr WHERE cr.user = '" . $usuario['user'] . "')
  );"
    );

    $this->conexion->query("INSERT INTO correos(correo, id_cliente)
    VALUES('" . $usuario['correo'] . "',
    (SELECT c.id FROM clientes c 
    INNER JOIN credenciales cr ON c.id_credencial = cr.id
    WHERE cr.user = '" . $usuario['user'] . "'));");
  }

  //Agrega una caja con datos aleatorios.
  public function agregarCajaRandom($user)
  {
    $caja = array();
    $caja['num_caja'] = random_int(100000, 999999);
    $caja['cbu'] = random_int(1000000, 9999990);
    $caja['alias'] = $user['nombre'] . '.' . $user['apellido'] . '.' . random_int(100, 999);
    $caja['monto'] = random_int(10000, 99999);

    $this->conexion->query(
      "INSERT INTO cajas(num_caja, cbu, alias, monto, id_cliente)
      VALUES('" . $caja['num_caja'] . "',
      '" . $caja['cbu'] . "',
      '" . $caja['alias'] . "',
      " . $caja['monto'] . ",
      (SELECT c.id FROM clientes c INNER JOIN credenciales cr ON c.id_credencial = cr.id
      WHERE cr.user = '" . $user['user'] . "'));"
    );

  }

  //Agrega un nuevo destinatario para transaccionar.
  public function agregarDestinatario($user, $userDest)
  {
    $this->conexion->query(
      "INSERT INTO destinatarios(cliente_origen, cliente_destino)
      VALUES(" . $user . ", " . $userDest . ");"
    );
  }

  //Agrega una nueva transferencia.
  public function agregarTranferencia($transferencia)
  {
    $this->conexion->query(
      "INSERT INTO transferencias(cliente_origen, cliente_destino, caja_origen, caja_destino, monto, fecha)
      VALUES(
        (SELECT c.id FROM clientes c
        INNER JOIN cajas ca ON c.id = ca.id_cliente
        WHERE ca.cbu = '" . $transferencia['origen'] . "' OR ca.alias = '" . $transferencia['origen'] . "'),
        (SELECT c.id FROM clientes c
        INNER JOIN cajas ca ON c.id = ca.id_cliente
        WHERE ca.cbu = '" . $transferencia['destino'] . "' OR ca.alias = '" . $transferencia['destino'] . "'),
        (SELECT ca.id FROM cajas ca
        WHERE ca.cbu = '" . $transferencia['origen'] . "' OR ca.alias = '" . $transferencia['origen'] . "'),
        (SELECT ca.id FROM cajas ca
        WHERE ca.cbu = '" . $transferencia['destino'] . "' OR ca.alias = '" . $transferencia['destino'] . "'),
        " . $transferencia['monto'] . ",
        '" . $transferencia['fecha'] . "'
      );"
    );
  }

  //Trae las ultima 10 transferencias.
  public function traerTransferencias($user)
  {
    $resultado = $this->conexion->query(
      "SELECT cd.nombre as nombreD, 
              cd.apellido as apellidoD,
              ca.num_caja as cajaO,
              cad.num_caja as cajaD,
              t.monto, t.fecha
      FROM clientes c
      INNER JOIN transferencias t ON c.id = t.cliente_origen
      INNER JOIN clientes cd ON t.cliente_destino = cd.id
      INNER JOIN cajas ca ON t.caja_origen = ca.id
      INNER JOIN cajas cad ON t.caja_destino = cad.id
      INNER JOIN credenciales cr ON c.id_credencial = cr.id
      WHERE cr.user = '" . $user . "';"
    );

    $transferencias = array();
    $transferencias = $resultado->fetch_all(MYSQLI_ASSOC);

    return $transferencias;
  }

  //Trae todos los usuarios de la db.
  public function traerUsuarios()
  {
    $resultado = $this->conexion->query(
      "SELECT c.id, c.nombre, c.apellido, co.correo FROM clientes c
      INNER JOIN credenciales cr ON c.id_credencial = cr.id
      INNER JOIN correos co ON c.id = co.id_cliente;"
    );
    $usuarios = array();
    $usuarios = $resultado->fetch_all(MYSQLI_ASSOC);
    return $usuarios;
  }

  //Trae un usuario especificado de la db.
  public function traerUsuario($user)
  {
    $resultado = $this->conexion->query(
      "SELECT c.id, c.nombre, c.apellido, co.correo, cr.user FROM clientes c 
      INNER JOIN credenciales cr ON c.id_credencial = cr.id
      INNER JOIN correos co ON c.id = co.id_cliente
      WHERE cr.user = '" . $user . "';"
    );
    $usuario = array();
    $usuario = $resultado->fetch_assoc();

    return $usuario;
  }

  //Trae a un usuario identificado por su cbu o alias.
  public function traerUsuarioPorCBU_Alias($cbu_alias)
  {
    $resultado = $this->conexion->query(
      "SELECT c.id, c.nombre, c.apellido, co.correo, cr.user FROM clientes c 
      INNER JOIN credenciales cr ON c.id_credencial = cr.id
      INNER JOIN correos co ON c.id = co.id_cliente
      INNER JOIN cajas ca ON ca.id_cliente = c.id
      WHERE ca.cbu = '" . $cbu_alias . "' OR ca.alias = '" . $cbu_alias . "';"
    );

    $usuario = array();
    $usuario = $resultado->fetch_assoc();

    return $usuario;
  }

  //Trae todas las cajas pertenecientes a un usuario especificado.
  public function traerCajas($user)
  {
    $resultado = $this->conexion->query(
      "SELECT ca.num_caja, ca.cbu, ca.alias, ca.monto FROM clientes c
      INNER JOIN credenciales cr ON c.id_credencial = cr.id
      INNER JOIN cajas ca ON ca.id_cliente = c.id
      WHERE cr.user = '" . $user . "';"
    );

    $cajas = $resultado->fetch_all(MYSQLI_ASSOC);

    return $cajas;
  }

  //Trae una caja especifica por su CBU o Alias.
  public function traerCajaPorCBU_Alias($cbu_alias)
  {
    $resultado = $this->conexion->query(
      "SELECT ca.num_caja, ca.cbu, ca.alias, ca.monto FROM cajas ca
      WHERE ca.cbu = '" . $cbu_alias . "' OR ca.alias = '" . $cbu_alias . "';"
    );

    $caja = $resultado->fetch_assoc();

    return $caja;
  }

  //Revisa que una caja pertenezca al usuario especificado.
  public function cajaEsDeUsuario($user, $cbu_alias)
  {
    $resultado = $this->conexion->query(
      "SELECT c.nombre FROM clientes c
      INNER JOIN credenciales cr ON c.id_credencial = c.id
      INNER JOIN cajas ca ON ca.id_cliente = c.id
      WHERE cr.user = '" . $user . "' AND (ca.cbu = '" . $cbu_alias . "' OR ca.alias = '" . $cbu_alias . "');"
    );

    $caja = $resultado->fetch_assoc();

    return (empty($caja));
  }

  public function actulizarMonto($cbu_alias, $monto)
  {
    $this->conexion->query(
      "UPDATE cajas ca SET monto = " . $monto . "
      WHERE ca.cbu = '" . $cbu_alias . "' OR ca.alias = '" . $cbu_alias . "'"
    );
  }

  //Trae todos los destinatarios asociados a la cuenta.
  public function traerDestinatarios($user)
  {
    $resultado = $this->conexion->query(
      "SELECT cd.id, cd.nombre, cd.apellido, ca.cbu, ca.alias FROM clientes c
      INNER JOIN credenciales cr ON c.id_credencial = cr.id
      INNER JOIN destinatarios d ON d.cliente_origen = c.id
      INNER JOIN clientes cd ON d.cliente_destino = cd.id
      INNER JOIN cajas ca ON cd.id = ca.id_cliente
      WHERE cr.user = '" . $user . "';"
    );

    $destinatarios = $resultado->fetch_all(MYSQLI_ASSOC);

    return $destinatarios;
  }

  //Verifica que las credenciales ingresadas coincidan con algun usuario.
  public function checkearUsuario($userChecking, $passwordChecking)
  {
    $resultado = $this->conexion->query(
      "SELECT cr.password FROM credenciales cr WHERE cr.user = '" . $userChecking . "';"
    );
    $user = $resultado->fetch_assoc();
    if (isset($user['password'])) {
      $hash = $user['password'];
      if (password_verify($passwordChecking, $hash)) {
        return true;
      }
      return false;
    }
    return false;
  }

  //Verifica si dicho usuario existe en la db.
  public function existeUsuario($usuario)
  {
    $resultado = $this->conexion->query(
      "SELECT cr.user FROM credenciales cr WHERE user = '" . $usuario['user'] . "';"
    );
    $user = $resultado->fetch_assoc();
    return (isset($user['user']));
  }

  //Verifica si dicho CBU o Alias existen en la db.
  public function existeCBU_Alias($cbu_alias)
  {
    $resultado = $this->conexion->query(
      "SELECT c.nombre FROM clientes c
      INNER JOIN cajas ca ON ca.id_cliente = c.id
      WHERE ca.cbu = '" . $cbu_alias . "' OR ca.alias = '" . $cbu_alias . "';"
    );
    $user = array();
    $user = $resultado->fetch_assoc();
    return (!empty($user));
  }

  //Elimina al destinatario indicado por parametros.
  public function eliminarDestinatarios($usuario, $destino)
  {
    $this->conexion->query(
      "DELETE FROM destinatarios 
      WHERE cliente_origen =  '" . $usuario . "' AND cliente_destino =  '" . $destino . "';"
    );
  }
}

?>