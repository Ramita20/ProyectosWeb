<?php
class Conect
{
  public static function conexion()
  {
    $conexion = new mysqli('127.0.0.1', 'root', '', 'solar_bank');
    if ($conexion->connect_error) {
      printf("Conexión fallida: %s\n", $conexion->connect_error);
      exit();
    }
    return $conexion;
  }
}


?>