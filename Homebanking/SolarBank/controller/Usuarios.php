<?php

class UsuariosController
{

  public function login() //Carga la pestaña de login.
  {
    require_once "view/login.php";
  }

  public function inicio() //Carga la pestaña de inicio.
  {
    require_once "view/inicio.php";
  }

  public function registro() { //Carga la pestaña de registro.
    require_once "view/register.php";
  }
}

?>