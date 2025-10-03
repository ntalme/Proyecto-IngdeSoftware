<?php

// MODELO Y CONTROLADOR
require_once "../controladores/productos.controlador.php";
require_once "../modelos/producto.modelo.php";

// ---------------------------- EDITAR PRODUCTO --------------------------------------
class AjaxProductos {
  public $idProducto;

  public function ajaxEditarProducto() {
    // Busca por id
    $item  = "id";
    $valor = (int) $this->idProducto;

    // Pide el producto
    $producto  = ControladorProductos::ctrMostrarProductos($item, $valor);
    $respuesta = $producto ?: [];

    // Agrega promo si existe el modelo/método (opcional)
    if ($valor > 0 && class_exists('ModeloPromociones') && method_exists('ModeloPromociones', 'mdlPromoActivaPorProducto')) {
      $promo = ModeloPromociones::mdlPromoActivaPorProducto($valor);
      $respuesta["promo"] = $promo ?: null;
    }

    // Respuesta JSON
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($respuesta);
  }
}

// ---------------------------- PRODUCTOS RECIENTES--------------------------------------
class AjaxProductosRecientes {
  public $dias;
  public $limite;

  public function ajaxGetRecientes() {
    // Respuesta JSON
    header("Content-Type: application/json; charset=utf-8");

    // Asegura rangos
    $dias   = isset($this->dias)   ? (int) $this->dias   : 7;
    $limite = isset($this->limite) ? (int) $this->limite : 50;
    $dias   = max(1, min($dias, 365));
    $limite = max(1, min($limite, 200));

    // Pide datos
    $data = ControladorProductos::ctrMostrarProductosRecientes($dias, $limite);

    // Devuelve lista
    echo json_encode(["status" => "ok", "data" => $data]);
  }
}

// RUTAS / ENTRADAS AJAX
// ---------------------------- CAMBIAR ESTADO --------------------------------------
if (isset($_POST["idProducto"]) && isset($_POST["nuevoEstado"])) {
  // Cambia activo/inactivo
  $respuesta = ControladorProductos::ctrCambiarEstadoProducto();

  // Responde simple
  header("Content-Type: text/plain; charset=utf-8");
  echo $respuesta; // "ok" | "error"
  exit;
}

// ---------------------------- EDITAR PRODUCTO ID --------------------------------------
if (isset($_POST["idProducto"]) && !isset($_POST["nuevoEstado"])) {
  $editar = new AjaxProductos();
  $editar->idProducto = $_POST["idProducto"];
  $editar->ajaxEditarProducto();
  exit;
}

// ---------------------------- EDITAR PRODUCTO POR ACCION --------------------------------------
if (isset($_POST["accion"]) && $_POST["accion"] === "editar" && isset($_POST["idProducto"])) {
  $editar = new AjaxProductos();
  $editar->idProducto = $_POST["idProducto"];
  $editar->ajaxEditarProducto();
  exit;
}

// ---------------------------- ACTUALIAR STOCK MINIMO --------------------------------------
if (isset($_POST["accion"]) && $_POST["accion"] === "actualizarStockMinimo") {
  $ajax = new AjaxProductosMinimo();
  $ajax->idProducto  = $_POST["producto_id"] ?? 0;
  $ajax->stockMinimo = $_POST["nuevoStockMinimo"] ?? 0;
  $ajax->ajaxActualizarStockMinimo();
  exit;
}

// ---------------------------- OBTENER PRODUCTOS RECIENTES --------------------------------------
if (isset($_POST["accion"]) && $_POST["accion"] === "getRecientes") {
  $ajax = new AjaxProductosRecientes();
  $ajax->dias   = $_POST["dias"]   ?? 7;
  $ajax->limite = $_POST["limite"] ?? 50;
  $ajax->ajaxGetRecientes();
  exit;
}

// ---------------------------- FILTRAR POR ESTADO --------------------------------------
if (isset($_POST["accion"]) && $_POST["accion"] === "filtrarEstado") {
  // "activos" | "inactivos" | "todos"
  $estado    = $_POST["estado"] ?? "todos";
  $respuesta = ControladorProductos::ctrFiltrarProductosPorEstado($estado);

  // Devuelve lista
  header("Content-Type: application/json; charset=utf-8");
  echo json_encode($respuesta);
  exit;
}

$promo = ModeloPromociones::mdlPromoActivaPorProducto($idProducto); 
$respuesta["promo"] = $promo ?: null;
echo json_encode($respuesta);