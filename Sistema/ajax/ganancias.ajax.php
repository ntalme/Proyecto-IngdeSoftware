<?php
require_once "../controladores/ventas.controlador.php";
require_once "../modelos/ventas.modelo.php";

if (isset($_POST["accion"]) && $_POST["accion"] === "calcularGanancia") {

  $periodo = $_POST["periodo"] ?? "semana";
  $fecha = $_POST["fecha"] ?? null;

  $resultado = ControladorVentas::ctrObtenerGanancias($periodo, $fecha);

  echo json_encode([
    "ok" => true,
    "totales" => $resultado["totales"] ?? [],
    "detalle" => $resultado["detalle"] ?? []
  ]);
  exit;
}
