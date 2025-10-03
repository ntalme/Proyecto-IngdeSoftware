<?php

require_once  "controladores/principal.controlador.php";
require_once  "controladores/usuarios.controlador.php";
require_once  "controladores/ventas.controlador.php";
require_once  "controladores/productos.controlador.php";
require_once  "controladores/registropc.controlador.php";
require_once  "controladores/promociones.controlador.php";

require_once  "modelos/usuarios.modelo.php";
require_once  "modelos/ventas.modelo.php";
require_once  "modelos/producto.modelo.php";
require_once  "modelos/registropc.modelo.php";
require_once  "modelos/promociones.modelo.php";

if (isset($_GET['ruta']) && $_GET['ruta'] === 'stock-critico' && isset($_GET['export'])) {
  require_once "controladores/productos.controlador.php";

  if ($_GET['export'] === 'pdf') {
    ControladorProductos::ctrExportarStockCriticoPDF();
    exit;
  }
}

if (isset($_GET['accion']) && $_GET['accion'] == "comprobante" && isset($_GET['idVenta'])) {
    ControladorVentas::ctrExportarComprobantePDF((int)$_GET['idVenta']);
    exit;
}
$principal = new ControladorPrincipal();
$principal->ctrPrincipal();

if (isset($_GET['route']) && $_GET['route'] === 'promociones-crear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    ControladorPromociones::ctrCrearPromocion();
    exit;
}
