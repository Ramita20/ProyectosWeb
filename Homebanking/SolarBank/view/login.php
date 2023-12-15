<?php

?>

<div class="color-fondo" id="pag-login">
  <div id="sol"></div>
  <div id="form-login">
    <form action="<?php echo ROOT ?>/controller/inicioSesion.php" method="post" autocomplete="off">
      <h3>Iniciar Sesión</h3>
      <br />
      <input class="disenio-ingreso" id="user" name="user" type="text" placeholder="Usuario" required /><br />
      <input class="disenio-ingreso" id="password" name="password" type="password" placeholder="Contrasena" required /><br />
      <br />
      <div id="captcha" class="g-recaptcha" data-sitekey="6LewSZQoAAAAAJ7k9HWFzYzCh-ab9SnOAYIYddA3"></div>
      <br />
      <button class="estilo-boton" id="ingresar-btn" type="submit">Ingresar</button>
    </form>
    <p id="register">¿Aún no sos cliente? <a href="<?php echo ROOT ?>/index.php?c=usuarios&a=registro">Asociate</a></p>
  </div>
  <div id="logo-verificacion">
    <img id="verificacion" src="<?php echo ROOT ?>/img/verificacion.png" alt="Tik">
    <div id="verificacion-fondo"></div>
    <img id="celular-mano-img" src="<?php echo ROOT ?>/img/celular-mano.png" alt="Mano agarrando celular">
  </div>
  <div id="info-fondo">
    <h2>¡Bienvenido a Solar Bank!</h2>
    <p>Administrá tu cuenta, cajas, tarjetas, etc. <br>desde la comodidad de tu casa.</p>
  </div>
</div>