<?php

// MODELO Y CONTROLADOR
require_once "../controladores/registropc.controlador.php";
require_once "../modelos/registropc.modelo.php";


class AjaxRegistroPC {

    // REGISTRAR PC 
    public $producto_id;
    public $cantidad;
    public $motivo;
    public $observacion;

    public function ajaxRegistrarPC() {
        // Prepara los datos que se envían al controlador
        $datos = [
            "producto_id" => (int) $this->producto_id,
            "cantidad"    => (int) $this->cantidad,
            "motivo"      => (string) $this->motivo,
            "observacion" => (string) ($this->observacion ?? "")
        ];

        // Llama al controlador para registrar
        $respuesta = ControladorRegistropc::ctrRegistrar($datos);

        // Devuelve la respuesta en JSON
        echo json_encode($respuesta);
    }
}

// RUTA: REGISTRAR PC
if (isset($_POST["accion"]) && $_POST["accion"] === "registrarPC") {

    // Crea el objeto
    $ajax = new AjaxRegistroPC();

    // Asigna valores recibidos por POST
    $ajax->producto_id = $_POST["producto_id"] ?? 0;
    $ajax->cantidad    = $_POST["cantidad"] ?? 0;
    $ajax->motivo      = $_POST["motivo"] ?? "";
    $ajax->observacion = $_POST["observacion"] ?? "";

    // Ejecuta la función para registrar
    $ajax->ajaxRegistrarPC();
}