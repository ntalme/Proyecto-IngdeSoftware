<?php

// MODELO Y CONTROLADOR
require_once "../controladores/ventas.controlador.php";
require_once "../modelos/ventas.modelo.php";

// CLASE: AJAXREPORTES
class AjaxReportes {

  public $fecha;
  public $mes;     
  public $limite;  

  // ---------------------------- PRODUCTOS VENDIDOS EN UN DIA --------------------------------------
  public function ajaxProductosVendidosPorDia() {
    header("Content-Type: application/json; charset=utf-8");

    $fecha = trim($this->fecha ?? "");
    $resp  = ControladorVentas::ctrProductosVendidosPorDia($fecha);

    echo json_encode($resp);
    exit;
  }

  // VENTAS DIARIAS POR HORA
  public function ajaxVentasDiariasPorHora() {
    header("Content-Type: application/json; charset=utf-8");

    $fecha = trim($this->fecha ?? "");
    $resp  = ControladorVentas::ctrVentasDiariasPorHora($fecha);

    echo json_encode($resp);
    exit;
  }

  // TOP PRODUCTOS DEL MES
  public function ajaxTopProductosMes() {
    header("Content-Type: application/json; charset=utf-8");

    $mes    = trim($this->mes ?? "");
    $limite = (int)($this->limite ?? 10);

    $resp = ControladorVentas::ctrTopProductosMes($mes, $limite);

    echo json_encode($resp);
    exit;
  }
}

// RUTAS / ENTRADAS AJAX

// POST PRODUCTOS VENDIDOS EN UN DIA
if (isset($_POST["accion"]) && $_POST["accion"] === "productosVendidosPorDia") {
  $obj = new AjaxReportes();
  $obj->fecha = $_POST["fecha"] ?? "";
  $obj->ajaxProductosVendidosPorDia();
}

// POST VENTAS DIARIAS POR HORA
if (isset($_POST["accion"]) && $_POST["accion"] === "ventasDiariasPorHora") {
  $obj = new AjaxReportes();
  $obj->fecha = $_POST["fecha"] ?? "";
  $obj->ajaxVentasDiariasPorHora();
}

// POST TOP PRODUCTOS DEL MES
if (isset($_POST["accion"]) && $_POST["accion"] === "topProductosMes") {
  $obj = new AjaxReportes();
  $obj->mes    = $_POST["mes"] ?? "";    // YYYY-MM
  $obj->limite = $_POST["limite"] ?? 10; // opcional
  $obj->ajaxTopProductosMes();
}