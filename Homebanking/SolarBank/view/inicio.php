<?php
require_once "config/config.php";
require_once "model/UsuariosModel.php";
?>

<div class="color-fondo" id="inicio">
    <div id="side-bar">
        <h4>Cajas</h4>
        <div id="info-cajas">
            <?php
            $userModel = new UsuariosModel();
            $cajas = $userModel->traerCajas($_SESSION['user']);

            if (isset($cajas)) {
                foreach ($cajas as $caja) {
                    echo "<div id=\"" . $caja["num_caja"] . "\">
                    <p>Nro. caja: " . $caja["num_caja"] . "</p>
                    <p>CBU: " . $caja["cbu"] . "</p>
                    <p>Alias: " . $caja["alias"] . "</p>
                    <p>$ " . $caja["monto"] . "</p>     
                    </div>";
                }
            }
            ?>
            <button class="estilo-boton" id="tranferencia-btn" type="button" data-toggle="modal"
                data-target="#tranferencia-modal">Tranferencia
            </button>
            <div id="info-transferencias">
                <h5>Últimas transferencias</h5>
                <?php
                $transferencias = $userModel->traerTransferencias($_SESSION["user"]);
                if (isset($transferencias)) {
                    foreach ($transferencias as $transferencia) {
                        echo "<p>" . $transferencia['fecha'] . "</p>
                        <p><span>Dest:</span>  " . $transferencia['nombreD'] . " " . $transferencia['apellidoD'] . "</p>
                        <p><span>Caja origen:</span>  " . $transferencia['cajaO'] . "</p>
                        <p><span>Caja destino:</span>  " . $transferencia['cajaD'] . "</p>
                        <p><span>Monto:</span>  $" . $transferencia['monto'] . "</p>
                        <hr/>";
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <div id="info-cliente">
        <h1 id="bienvenida">¡Bienvenido a tu HomeBanking,
            <?php echo $_SESSION['user'] ?>!
        </h1>
        <h4>Destinatarios</h4>
        <div id="info-destinatarios">
            <?php

            $userModel = new UsuariosModel();
            $destinatarios = $userModel->traerDestinatarios($_SESSION['user']);

            if (isset($destinatarios)) {
                foreach ($destinatarios as $destinatario) {
                    echo "<div id=\"" . $destinatario["id"] . "\">
                        <h4>" . $destinatario["nombre"] . " " . $destinatario["apellido"] . "</h4>
                        <p>CBU: " . $destinatario["cbu"] . "</p>
                        <p>Alias: " . $destinatario["alias"] . "</p>

                        <form id=\"delete-destinatario\" action=\"" . ROOT . "/controller/eliminarDestinatario.php\" method=\"post\">
                        <input type=\"hidden\" name=\"id\" value=\"" . $destinatario["id"] . "\">
                        <button class=\"estilo-boton\" type=\"submit\">Eliminar</button>
                        </form>
                        </div>";
                }
            }

            ?>

            <button id="add-destinatario-btn" type="button" data-toggle="modal" data-target="#add-destinatario-modal">
                <svg id="plus-sign" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                    <path
                        d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                </svg>
            </button>
        </div>

        <!-- Modal para agregar un destinatario -->
        <div class="modal fade color-fondo" id="add-destinatario-modal" tabindex="-1" role="dialog"
            aria-labelledby="add-destinatario-modal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="background-color:#001D3D;">
                    <div class="modal-header">
                        <h5 class="modal-title" id="add-destinatario-label">Agregar nuevo destinatario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cancelar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="form-add-destinatario" action="<?php echo ROOT ?>/controller/agregarDestinatario.php"
                            method="post" autocomplete="off">
                            <label for="cbu-alias">CBU o alias: </label>
                            <input type="text" name="cbu-alias" id="cbu-alias" required><br />
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button form="form-add-destinatario" type="submit" class="btn btn-primary">Agregar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para realizar una transferencias -->
        <div class="modal fade color-fondo" id="tranferencia-modal" tabindex="-1" role="dialog"
            aria-labelledby="tranferencia-modal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="background-color:#001D3D;">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tranferencia-label">Realizar transferencia</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cancelar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="form-tranferencia" action="<?php echo ROOT ?>/controller/transferirDinero.php"
                            method="post" autocomplete="off">
                            <label for="origen">Caja de origen: </label>
                            <input type="text" name="origen" id="origen" placeholder="CBU o Alias" required><br />
                            <label for="destino">Caja de destino: </label>
                            <input type="text" name="destino" id="destino" placeholder="CBU o Alias" required><br />
                            <label for="monto">Monto: </label>
                            <input type="number" name="monto" id="monto" required><br />
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button form="form-tranferencia" type="submit" class="btn btn-primary">Transferir</button>
                    </div>
                    <h4>Cajas propias</h4>
                    <div id="cajas-modal">
                        <?php
                        $cajas = $userModel->traerCajas($_SESSION['user']);

                        if (isset($cajas)) {
                            foreach ($cajas as $caja) {
                                echo "<div id=\"" . $caja["num_caja"] . "\">
                                    <p>Nro. caja: " . $caja["num_caja"] . "</p>
                                    <p>CBU: " . $caja["cbu"] . "</p>
                                    <p>Alias: " . $caja["alias"] . "</p>
                                    <p>$ " . $caja["monto"] . "</p>     
                                    </div>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>



    </div>
</div>
</div>