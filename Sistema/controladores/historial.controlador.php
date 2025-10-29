<?php
require_once "modelos/historial.modelo.php";

class ControladorHistorial {

    // ---------------------------- REGISTRAR CAMBIO EN HISTORIAL -----------------------------------------
    public static function ctrRegistrarCambio($usuario, $modulo, $accion, $idRegistro, $valorAnterior, $valorNuevo) {
        return ModeloHistorial::mdlRegistrarCambio($usuario, $modulo, $accion, $idRegistro, $valorAnterior, $valorNuevo);
    }

    // ---------------------------- OBTENER HISTORIAL COMPLETO -----------------------------------------
    public static function ctrMostrarHistorial() {
        return ModeloHistorial::mdlObtenerHistorial();
    }
}
?>
