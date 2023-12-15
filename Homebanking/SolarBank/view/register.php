<?php

?>

<div class="color-fondo" id="pag-registro">
    <div id="form-registro">
        <form action="<?php echo ROOT ?>/controller/registro.php" method="post" autocomplete="off">
            <div id="left-column">
                <label for="user-r">Nombre de usuario: </label><br />
                <input tabindex="1" class="disenio-ingreso" id="user-r" name="user-r" type="text" required><br />
                <label for="nombre-r">Nombre/s: </label><br />
                <input tabindex="3" class="disenio-ingreso" id="nombre-r" name="nombre-r" type="text" required><br />
                <label for="correo-r">Correo electronico:</label><br />
                <input tabindex="5" class="disenio-ingreso" id="correo-r" name="correo-r" type="email" required><br />
            </div>
            <div id="right-column">
                <label for="password-r">Contraseña: </label><br />
                <input tabindex="2" class="disenio-ingreso" id="password-r" name="password-r" type="password" required><br />
                <label for="apellido-r">Apellido/s: </label><br />
                <input tabindex="4" class="disenio-ingreso" id="apellido-r" name="apellido-r" type="text" required><br />
                <label for="dni-r">Número de documento: </label><br />
                <input tabindex="6" class="disenio-ingreso" id="dni-r" name="dni-r" type="text" required><br />
            </div>

            <div id="captcha" class="g-recaptcha" data-sitekey="6LewSZQoAAAAAJ7k9HWFzYzCh-ab9SnOAYIYddA3"></div>

            <button class="estilo-boton" id="registro-btn" type="submit">Registrarse</button>
        </form>
        <a id="regresar" href="<?php echo ROOT ?>/index.php">Regresar</a>
    </div>
</div>