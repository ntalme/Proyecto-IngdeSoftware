<?php

// MODELO Y CONTROLADOR
require_once "../controladores/usuarios.controlador.php";
require_once "../modelos/usuarios.modelo.php";

// CLASE: AJAXUSUARIOS
class AjaxUsuarios {

    public $idUsuario;
    public $validarUsuario;

    // EDITAR USUARIO
    // Busca un usuario por id y devuelve sus datos en JSON
    public function ajaxEditarUsuario() {
        $item  = "id";
        $valor = $this->idUsuario;

        $respuesta = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);
        echo json_encode($respuesta);
    }

    // VALIDAR USUARIO
    // Comprueba si un nombre de usuario ya existe en la BD
    public function ajaxValidarUsuario() {
        $item  = "usuario";
        $valor = $this->validarUsuario;

        $respuesta = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);
        echo json_encode($respuesta);
    }
}

// EDITAR USUARIO
// Cuando recibe un idUsuario devuelve sus datos
if (isset($_POST["idUsuario"])) {
    $editar = new AjaxUsuarios();
    $editar->idUsuario = $_POST["idUsuario"];
    $editar->ajaxEditarUsuario();
}

// VALIDAR USUARIO
// Cuando recibe un nombre de usuario valida si existe
if (isset($_POST["validarUsuario"])) {
    $valUsuario = new AjaxUsuarios();
    $valUsuario->validarUsuario = $_POST["validarUsuario"];
    $valUsuario->ajaxValidarUsuario();
}
