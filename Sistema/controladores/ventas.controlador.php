<?php

class ControladorVentas {

/* ========================================== VENTAS =================================================== */
  // ---------------------------- MOSTRAR VENTAS ------------------------------------------------
  public static function ctrMostrarVentas($item, $valor) {

    $tabla = "venta";

    // Este método debe estar en tu modelo
    $respuesta = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);

    return $respuesta;
  }

  // ---------------------------- CREAR VENTA ---------------------------------------------------
  public function ctrCrearVenta() {

    if (!isset($_POST["nuevaVenta"])) return;

    $listaProductos = json_decode($_POST["listaProductos"], true);
    if (!is_array($listaProductos)) $listaProductos = [];

    $tablaProd = "producto";
    $detalleProductos   = []; // venta.productos (precios finales por línea)
    $promosAplicadas    = []; // venta.promociones_aplicadas (resumen)
    $totalCalculado     = 0;

    foreach ($listaProductos as $item) {

      $idProducto = (int)($item["id"] ?? 0);
      $cantidad   = max(1, (int)($item["cantidad"] ?? 1));
      if ($idProducto <= 0) continue;

      // Traer producto real desde BD
      $producto = ModeloProductos::mdlMostrarProductos($tablaProd, "id", $idProducto);
      if (!$producto) continue;

      // ⚠️ Precio correcto según tu esquema: 'precio_venta'
      $precioBase = 0.0;
      if (isset($producto["precio_venta"])) {
        $precioBase = (float)$producto["precio_venta"];
      } elseif (isset($producto["precio"])) { // fallback si en otra instalación está así
        $precioBase = (float)$producto["precio"];
      }

      // Calcular mejor promo para este producto
      $promo = ControladorPromociones::mejorPromoParaProducto($idProducto, $precioBase, $cantidad);

      // Valores por defecto (sin promo)
      $precioUnitFinal = $precioBase;
      $totalLinea      = $precioBase * $cantidad;
      $gratis          = 0;
      $pagaUnidades    = $cantidad;
      $promoInfo       = null;

      if ($promo) {
        $precioUnitFinal = (float)($promo['precio_unit']   ?? $precioBase);
        $totalLinea      = (float)($promo['total_linea']   ?? ($precioBase * $cantidad));
        $gratis          = (int)  ($promo['gratis']        ?? 0);
        $pagaUnidades    = (int)  ($promo['paga_unidades'] ?? $cantidad);

        // Resumen para venta.promociones_aplicadas
        $promosAplicadas[] = [
          "id_producto"   => $idProducto,
          "nombre"        => (string)$producto["nombre"],
          "id_promocion"  => (int)($promo['id_promocion'] ?? 0),
          "tipo"          => (string)($promo['tipo'] ?? ''),
          "parametro"     => $promo['parametro'] ?? null,
          "etiqueta"      => (string)($promo['etiqueta'] ?? ''),
          "detalle"       => (string)($promo['detalle'] ?? ''),
          "observacion"   => (string)($promo['observacion'] ?? ''),
          "precio_base"   => $precioBase,
          "precio_final"  => $precioUnitFinal,
          "cantidad"      => $cantidad,       // llevadas (paga + gratis)
          "paga_unidades" => $pagaUnidades,
          "gratis"        => $gratis,
          "total_linea"   => $totalLinea
        ];

        // Detalle para la línea dentro de venta.productos
        $promoInfo = [
          "id_promocion"      => (int)($promo['id_promocion'] ?? 0),
          "tipo"              => (string)($promo['tipo'] ?? ''),
          "parametro"         => $promo['parametro'] ?? null,
          "etiqueta"          => (string)($promo['etiqueta'] ?? ''),
          "detalle"           => (string)($promo['detalle'] ?? ''),
          "observacion"       => (string)($promo['observacion'] ?? ''),
          "precio_unit_base"  => $precioBase,
          "precio_unit_final" => $precioUnitFinal,
          "paga_unidades"     => $pagaUnidades,
          "gratis"            => $gratis
        ];
      }

      // Acumular total
      $totalCalculado += $totalLinea;

      // Línea “real” que irá a venta.productos
      $detalleProductos[] = [
        "id"           => $idProducto,
        "nombre"       => (string)$producto["nombre"],
        "cantidad"     => $cantidad,         // paga + gratis
        "precio_base"  => $precioBase,
        "precio_final" => $precioUnitFinal,
        "total_linea"  => $totalLinea,
        "promocion"    => $promoInfo         // null si no aplicó
      ];

      // Actualizar stock (descuenta todo lo que salió)
      $stockActual = (int)($producto["cantidad"] ?? 0);
      $nuevoStock  = $stockActual - $cantidad;
      ModeloProductos::mdlActualizarProducto($tablaProd, "cantidad", $nuevoStock, $idProducto);
    }

    // Registrar la venta
    date_default_timezone_set('America/Santiago');
    $tabla = "venta";

    $datos = [
      "id_usuario"             => (int)$_POST["idUsuario"],
      "productos"              => json_encode($detalleProductos, JSON_UNESCAPED_UNICODE),
      "promociones_aplicadas"  => json_encode($promosAplicadas, JSON_UNESCAPED_UNICODE),
      "total"                  => $totalCalculado, // ya es numérico sin formateo
      "metodo_pago"            => (string)($_POST["listaMetodoPago"] ?? ''),
      "observacion"            => (string)($_POST["observacionVenta"] ?? ''),
      "fecha"                  => date("Y-m-d H:i:s")
    ];

    $respuesta = ModeloVentas::mdlIngresarVenta($tabla, $datos);

    if ($respuesta === "ok") {
      echo "<script>
        Swal.fire({
          icon: 'success',
          title: '¡Venta guardada!',
          text: 'La venta se ha registrado correctamente.',
          confirmButtonText: 'OK'
        }).then((r)=>{ if(r.isConfirmed){ window.location='crear-venta'; }});
      </script>";
    }
  }

  // ----------------------------  EXPORTAR COMPROBANTE DE VENTA A PDF --------------------------
  public static function ctrExportarComprobantePDF($idVenta){
    // ====== Datos base ======
    $venta   = ModeloVentas::mdlObtenerVenta($idVenta);
    $detalle = ModeloVentas::mdlObtenerDetalle($idVenta);
    if (!$venta) { http_response_code(404); echo "No se encontró la venta."; return; }

    // Vendedor
    $nombreVendedor = '';
    if (!empty($venta['id_usuario'])) {
        $u = ControladorUsuarios::ctrMostrarUsuarios('id', $venta['id_usuario']);
        $nombreVendedor = $u ? ($u['nombre'] ?? ('ID '.$venta['id_usuario'])) : ('ID '.$venta['id_usuario']);
    }

    // Fecha amigable
    $tz    = new DateTimeZone('America/Santiago');
    $ahora = new DateTime('now', $tz);
    $fechaHumana = class_exists('IntlDateFormatter')
        ? ucfirst((new IntlDateFormatter('es_CL', IntlDateFormatter::LONG, IntlDateFormatter::SHORT, 'America/Santiago'))->format($ahora))
        : $ahora->format('d-m-Y H:i');

    // Negocio
    $NEGOCIO = [
        'nombre_fantasia' => 'Botillería Joaquín',
        'giro'            => 'Venta al por menor de bebidas alcohólicas',
        'direccion'       => 'Los lirios 151, Linderos',
        'comuna'          => 'Santiago',
        'telefono'        => '+56 9 1234 5678',
        'correo'          => 'botilleria.spa5357@gmail.com'
    ];

    // Total FINAL (ya con descuentos aplicados)
    $aplicaIVA = true;
    $total = (float)$venta['total'];

    /* ==============================
       PROMOS A NIVEL DE VENTA (JSON)
       ============================== */
    $idxPromos = [];
    $promosVentaRaw = $venta["promociones_aplicadas"] ?? ($venta["promociones_Aplicadas"] ?? null);
    if (!empty($promosVentaRaw)) {
        $pa = json_decode($promosVentaRaw, true);
        if (is_array($pa)) {
            foreach ($pa as $k => $p) {
                $idProd  = $p['id_producto']     ?? $p['producto_id']     ?? null;
                $nomProd = $p['nombre_producto'] ?? $p['producto_nombre'] ?? (is_string($k) ? $k : null);
                if ($idProd !== null)       $idxPromos["id:$idProd"][] = $p;
                elseif ($nomProd !== null)  $idxPromos["nm:$nomProd"][] = $p;
                else                        $idxPromos["*"][] = $p;
            }
        }
    }

    // Helpers de promo
    $fmtPromo = function($p){
        $et = $p['etiqueta'] ?? null;
        if (!$et) {
            $tipo = strtolower($p['tipo'] ?? '');
            $param = $p['parametro'] ?? null;
            if ($tipo==='descuento' && is_numeric($param)) {
                $val = rtrim(rtrim(number_format((float)$param, 2, '.', ''), '0'), '.');
                $et  = "-{$val}%";
            } elseif ($tipo==='precio_fijo' && is_numeric($param)) {
                $et  = '$'.number_format((float)$param, 0, ",", ".").' fijo';
            } elseif (in_array($tipo,['2x1','2por1','dos_por_uno'])) {
                $et  = '2x1';
            } else {
                $et  = strtoupper($tipo ?: 'PROMO');
            }
        }
        return htmlspecialchars($et, ENT_QUOTES, 'UTF-8');
    };
    $clsPromo = function($p){
        $tipo = strtolower($p['tipo'] ?? '');
        if ($tipo==='descuento') return 'pill-descuento';
        if ($tipo==='precio_fijo') return 'pill-precio';
        if (in_array($tipo,['2x1','2por1','dos_por_uno'])) return 'pill-2x1';
        return 'pill-generic';
    };

    // ====== Detalle + cálculo de DESCUENTO TOTAL ======
    $rows = '';
    $descuentoTotal = 0.0;

    if (empty($detalle)) {
        $rows .= '<tr><td colspan="7" class="text-center" style="color:#777;padding:14px">Sin detalle</td></tr>';
    } else {
        foreach ($detalle as $d) {
            // ====== NUEVA LÓGICA DE PRECIOS (usa los valores guardados en la venta) ======
            $producto = htmlspecialchars((string)($d['producto'] ?? $d['nombre'] ?? 'Producto'));
            $marca    = htmlspecialchars((string)($d['marca'] ?? '-'));
            $formato  = htmlspecialchars((string)($d['formato'] ?? '-'));
            $tamano   = htmlspecialchars((string)($d['tamano'] ?? '-'));
            $cant     = (float)($d['cantidad'] ?? 0);

            // 👉 aquí usamos los valores históricos guardados en la venta:
            if (isset($d['precio_final'])) {
                $precio = (float)$d['precio_final'];      
            } elseif (isset($d['precio_base'])) {
                $precio = (float)$d['precio_base'];
            } else {
                $precio = (float)($d['precio'] ?? 0);
            }

            // el subtotal también lo tomamos del JSON si existe
            if (isset($d['total_linea'])) {
                $subtotal = (float)$d['total_linea'];
            } else {
                $subtotal = (float)($d['subtotal'] ?? ($cant * $precio));
            }

            $idProd   = $d['id_producto'] ?? ($d['producto_id'] ?? ($d['id'] ?? null));

            // Promos que aplican a esta línea
            $promos = [];
            if (!empty($d['promo']))            $promos[] = $d['promo'];
            if (!empty($d['promo_aplicada']))   $promos[] = $d['promo_aplicada'];
            if ($idProd !== null && !empty($idxPromos["id:$idProd"])) {
                foreach ($idxPromos["id:$idProd"] as $p) $promos[] = $p;
            } elseif (!empty($idxPromos["nm:".($d['producto'] ?? '')])) {
                foreach ($idxPromos["nm:".($d['producto'] ?? '')] as $p) $promos[] = $p;
            }

            // Pills y cálculo de descuento de la línea
            $pills = '';
            $precioBase = null;   // unitario sin descuento
            $descuentoLinea = 0.0;

            foreach ($promos as $p) {
                $pills .= ' <span class="pill '.$clsPromo($p).'">'.$fmtPromo($p).'</span>';
                if (isset($p['precio_unit_base']) && is_numeric($p['precio_unit_base'])) {
                    $precioBase = max($precioBase ?? 0, (float)$p['precio_unit_base']);
                }
            }

            // 1) Si conocemos precio base, diferencia por todas las unidades
            if ($precioBase !== null && $precioBase > $precio) {
                $descuentoLinea += ($precioBase - $precio) * $cant;
            }

            // 2) 2x1: unidades gratis * precio base (o el aplicado si no hay base)
            foreach ($promos as $p) {
                $tipo = strtolower($p['tipo'] ?? '');
                if (in_array($tipo, ['2x1','2por1','dos_por_uno'])) {
                    $gratis = isset($p['gratis']) ? (int)$p['gratis'] : null;
                    if ($gratis === null) {
                        $paga = isset($p['paga_unidades']) ? (int)$p['paga_unidades'] : null;
                        $gratis = ($paga!==null) ? max(0, (int)$cant - $paga) : floor($cant/2);
                    }
                    $uBase = $precioBase ?? $precio;
                    if ($gratis > 0 && $uBase > 0) $descuentoLinea += $gratis * $uBase;
                }
            }

            $descuentoTotal += $descuentoLinea;

            // Celdas finales
            $prodCell = $producto . $pills;
            $precioCell = '';
            if ($precioBase !== null && $precioBase > $precio) {
                $precioCell .= '<div class="old-price">$'.number_format($precioBase, 0, ",", ".").'</div>';
            }
            $precioCell .= '$'.number_format($precio, 0, ",", ".");

            $rows .= '<tr>
                <td>'.$prodCell.'</td>
                <td>'.$marca.'</td>
                <td>'.$formato.'</td>
                <td>'.$tamano.'</td>
                <td class="text-right">'.number_format($cant, 0, ",", ".").'</td>
                <td class="text-right">'.$precioCell.'</td>
                <td class="text-right">$'.number_format($subtotal, 0, ",", ".").'</td>
            </tr>';
        }
    }

    // ====== Montos "SIN DESCUENTO" (para mostrar en el resumen) ======
    $descuentoTotal = max(0, (float)$descuentoTotal);
    $brutoSinDesc   = $total + $descuentoTotal; // total antes de descuentos

    if ($aplicaIVA) {
        $netoSinDesc = round($brutoSinDesc / 1.19);
        $ivaSinDesc  = $brutoSinDesc - $netoSinDesc;
    } else {
        $netoSinDesc = $brutoSinDesc;
        $ivaSinDesc  = 0.0;
    }

    // ====== HTML ======
    $html = '
    <html>
    <head>
      <meta charset="utf-8">
      <style>
        @page { margin: 28px 28px 50px 28px; }
        body{ font-family: DejaVu Sans, sans-serif; color:#222; font-size:12px; }
        .enc{ display:flex; align-items:flex-start; gap:14px; border-bottom:2px solid #000; padding-bottom:10px; }
        .negocio h1{ font-size:18px; margin:0; }
        .negocio .sub{ margin:2px 0; color:#555; }
        .box-right{ margin-left:auto; text-align:right; }
        .box-right .tipo{ font-size:14px; font-weight:bold; }
        .datos{ margin-top:10px; }
        .datos p{ margin:2px 0; }
        table{ width:100%; border-collapse:collapse; margin-top:12px; table-layout: fixed; }
        th, td{ border:1px solid #ddd; padding:6px 8px; word-wrap:break-word; }
        th{ background:#f5f5f5; text-align:left; }
        .text-right{ text-align:right; }
        .col-prod{ width:28%; } .col-marca{ width:14%; } .col-formato{ width:14%; } .col-tamano{ width:14%; }
        .col-cant{ width:7%; }  .col-precio{ width:11%; } .col-sub{ width:12%; }

        .resumen{ width:320px; margin-left:auto; margin-top:12px; border:1px solid #ddd; }
        .resumen table{ width:100%; border-collapse:collapse; }
        .resumen td{ border:1px solid #ddd; padding:6px 8px; }
        .total{ font-weight:bold; }
        footer{ position: fixed; bottom: -8px; left:0; right:0; text-align:center; font-size:10px; color:#666; }

        .pill{ display:inline-block; padding:2px 6px; border-radius:999px; font-size:10px; font-weight:700;
               line-height:1.2; margin-left:6px; border:1px solid #d0d0d0; background:#f3f4f6; color:#111; }
        .pill-descuento{ background:#e6f7ee; border-color:#bfe7cf; color:#065f46; }
        .pill-precio   { background:#eaf3ff; border-color:#c9ddff; color:#1e3a8a; }
        .pill-2x1      { background:#fff4e5; border-color:#ffe0b3; color:#7c2d12; }
        .pill-generic  { background:#f3f4f6; border-color:#e5e7eb; color:#374151; }
        .old-price{ color:#888; text-decoration:line-through; font-size:10px; }
      </style>
    </head>
    <body>

      <div class="enc">
        <div class="negocio">
          <h1>'.htmlspecialchars($NEGOCIO['nombre_fantasia']).'</h1>
          <div class="sub">'.htmlspecialchars($NEGOCIO['giro']).'</div>
          <div class="sub">'.htmlspecialchars($NEGOCIO['direccion']).', '.htmlspecialchars($NEGOCIO['comuna']).'</div>
          <div class="sub">Tel: '.htmlspecialchars($NEGOCIO['telefono']).' &nbsp;|&nbsp; '.htmlspecialchars($NEGOCIO['correo']).'</div>
        </div>
        <div class="box-right">
          <div class="tipo">COMPROBANTE DE VENTA</div>
          <div>Folio: '.htmlspecialchars((string)$venta['id']).'</div>
          <div>Fecha: '.htmlspecialchars((string)$venta['fecha']).'</div>
          <div>Medio de pago: '.htmlspecialchars((string)$venta['metodo_pago']).'</div>
        </div>
      </div>

      <div class="datos">
        '.($nombreVendedor!=='' ? '<p><strong>Vendedor:</strong> '.htmlspecialchars($nombreVendedor).'</p>' : '').'
      </div>

      <table>
        <thead>
          <tr>
            <th class="col-prod">Producto</th>
            <th class="col-marca">Marca</th>
            <th class="col-formato">Formato</th>
            <th class="col-tamano">Tamaño</th>
            <th class="text-right col-cant">Cant</th>
            <th class="text-right col-precio">Precio</th>
            <th class="text-right col-sub">Subtotal</th>
          </tr>
        </thead>
        <tbody>'.$rows.'</tbody>
      </table>

      <div class="resumen">
        <table>
          <tr>
            <td>Neto</td>
            <td class="text-right">$'.number_format($netoSinDesc, 0, ",", ".").'</td>
          </tr>'.
          ($aplicaIVA ? '
          <tr>
            <td>IVA (19%)</td>
            <td class="text-right">$'.number_format($ivaSinDesc, 0, ",", ".").'</td>
          </tr>' : '').'
          <tr class="total">
            <td>Total</td>
            <td class="text-right">$'.number_format($total, 0, ",", ".").'</td>
          </tr>
        </table>
      </div>

      <footer>
        Documento no tributario. Para efectos fiscales en Chile se requiere DTE (SII).
        &nbsp;&nbsp;|&nbsp;&nbsp; Generado: '.htmlspecialchars($fechaHumana).'
      </footer>

      </body>
      </html>';

    // ====== Dompdf ======
    require_once __DIR__ . "/../vendor/autoload.php";
    $dompdf = new Dompdf\Dompdf(["chroot" => __DIR__ . "/../", "isRemoteEnabled" => true]);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->loadHtml($html, 'UTF-8');
    $dompdf->render();
    $dompdf->stream("comprobante_venta_".$venta["id"].".pdf", ["Attachment" => true]);
    exit;
}

/* ========================================== REPORTES =================================================== */
  // ----------------------------  PRODUCTOS VENDIDOS POR DÍA -----------------------------------
  public static function ctrProductosVendidosPorDia($fechaStr) {

    // Validación formato YYYY-MM-DD (como hace en otros controladores)
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaStr)) {
      return ["status" => "error", "msg" => "Fecha inválida (use YYYY-MM-DD)."];
    }

    date_default_timezone_set('America/Santiago');

    $resp = ModeloVentas::mdlProductosVendidosPorDia("venta", $fechaStr);

    if ($resp === false) {
      return ["status" => "error", "msg" => "No fue posible obtener el reporte."];
    }

    return ["status" => "ok", "data" => $resp];
  }

  // ----------------------------  VENTAS DIARIAS -----------------------------------------------
  public static function ctrVentasDiariasPorHora($fechaStr) {

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaStr)) {
      return ["status" => "error", "msg" => "Fecha inválida (use YYYY-MM-DD)."];
    }

    date_default_timezone_set('America/Santiago');

    $data = ModeloVentas::mdlVentasDiariasPorHora("venta", $fechaStr);
    if ($data === false) {
      return ["status" => "error", "msg" => "No fue posible obtener el reporte."];
    }

    // Armar métricas de resumen
    $totalVentas = 0;   // cantidad de boletas
    $montoDia    = 0;   // CLP
    $horaPico    = null;
    $maxMonto    = -1;

    foreach ($data as $r) {
      $totalVentas += (int)$r["ventas"];
      $montoDia    += (float)$r["monto_total"];
      if ((float)$r["monto_total"] > $maxMonto) {
        $maxMonto = (float)$r["monto_total"];
        $horaPico = (int)$r["hora"];
      }
    }

    $ticketProm = $totalVentas > 0 ? ($montoDia / $totalVentas) : 0;

    return [
      "status" => "ok",
      "data"   => $data,                      // lista 0..23 con ventas/monto/ticket_prom
      "resumen"=> [
        "fecha"        => $fechaStr,
        "ventas"       => $totalVentas,
        "monto_dia"    => $montoDia,
        "ticket_prom"  => $ticketProm,
        "hora_pico"    => $horaPico
      ]
    ];
  }

/* ========================================== CIERRE =================================================== */
// ---------------------------- TOTAL DEL DÍA ----------------------------------------
  public static function ctrObtenerTotalDelDia() {
    return ModeloVentas::mdlTotalDelDia();
  }

  // ---------------------------- CANTIDAD DEL DÍA -------------------------------------
  public static function ctrObtenerCantidadDelDia() {
    return ModeloVentas::mdlCantidadDelDia();
  }

  // ---------------------------- CREAR CIERRE DIARIO ----------------------------------
  public static function ctrCrearCierre() {
    if (!isset($_POST['accion']) || $_POST['accion'] !== 'cerrarCaja') return;

    if (empty($_SESSION['id'])) {
      echo '<script>Swal.fire({icon:"warning", title:"Sesión requerida", text:"Vuelva a iniciar sesión."});</script>';
      return;
    }

    $idUsuario = (int)$_SESSION['id'];

    // Evitar doble cierre el mismo día
    if (ModeloVentas::mdlExisteCierreHoy($idUsuario)) {
      echo '<script>
        Swal.fire({
          icon:"info",
          title:"Cierre ya registrado",
          text:"El cierre de caja del día ya fue realizado."
        });
      </script>';
      return;
    }

    // Datos base del día
    $totalGeneral = ModeloVentas::mdlTotalDelDia();
    $cantidadVentas = ModeloVentas::mdlCantidadDelDia();

    if ($cantidadVentas <= 0 || $totalGeneral <= 0) {
      echo '<script>
        Swal.fire({
          icon:"info",
          title:"No hay ventas para cerrar",
          text:"No se encontraron ventas registradas hoy."
        });
      </script>';
      return;
    }

    // Calcular retenciones y descuentos
    $boletasDigitales = (float)($_POST['montoBoletasDigitales'] ?? 0);
    $retencionBoletas = $boletasDigitales * 0.20;

    $totalTarjeta = ModeloVentas::mdlObtenerTotalConTarjeta("venta");
    $retencionTarjeta = $totalTarjeta * 0.20;

    $descuentoContadora = 5000;

    // Totales finales del día
    $totalFinal = max($totalGeneral - $retencionBoletas - $retencionTarjeta - $descuentoContadora, 0);
    $gananciaDia = $totalFinal * 0.20;
    $reinversionDia = $totalFinal * 0.80;

    // 🔹 Calcular reinversión semanal acumulada (sumando la del día actual)
   $reinversionSemanal = ModeloVentas::mdlCalcularReinversionSemanal($reinversionDia);

    // 🔹 Si es domingo, aplicar descuento por pago de luz (restado de la reinversión semanal)
    $esDomingo = (date('w') == 0); // 0 = domingo
    $descuentoLuz = $esDomingo ? 80000 : 0;
    $reinversionSemanalFinal = max($reinversionSemanal - $descuentoLuz, 0);

    // 🔹 Mensaje automático
    $observaciones = $esDomingo
        ? "Se aplicó descuento dominical de $80.000 por pago de luz (restado de la reinversión semanal)."
        : (!empty($_POST['observaciones']) ? $_POST['observaciones'] : "Sin observaciones.");

  // Guardar en base de datos
  $datos = [
    "id_usuario" => $idUsuario,
    "total_general" => $totalGeneral,
    "cantidad_ventas" => $cantidadVentas,
    "boletas_digitales" => $boletasDigitales,
    "retencion_boletas" => $retencionBoletas,
    "retencion_tarjeta" => $retencionTarjeta,
    "descuento_contadora" => $descuentoContadora,
    "total_final" => $totalFinal,
    "ganancia_dia" => $gananciaDia,
    "reinversion" => $reinversionDia,
    // ✅ nombres ajustados a la tabla y modelo
    "reinversion_semana" => $reinversionSemanal,
    "reinversion_semana_final" => $reinversionSemanalFinal,
    "descuento_luz" => $descuentoLuz,
    "observaciones" => $observaciones,
    "es_domingo" => $esDomingo ? 1 : 0
  ];

    $ok = ModeloVentas::mdlGuardarCierreDiario($datos);

    if ($ok) {
      $mensaje = $esDomingo
        ? "Cierre dominical registrado. Se descontaron $80.000 de la reinversión semanal por pago de luz."
        : "Cierre diario registrado correctamente.";

      echo "<script>
        Swal.fire({
          icon:'success',
          title:'Cierre registrado',
          text:'$mensaje'
        }).then(()=>{ window.location='cierre-caja'; });
      </script>";
    } else {
      echo "<script>
        Swal.fire({
          icon:'error',
          title:'Error al guardar',
          text:'Ocurrió un error al intentar guardar el cierre diario.'
        });
      </script>";
    }
  }

  // ---------------------------- LISTAR CIERRES ---------------------------------------
  public static function ctrObtenerCierres() {
    return ModeloVentas::mdlObtenerCierres();
  }

  // ---------------------------- SI EXISTE CIERRE HOY ---------------------------------
  public static function ctrExisteCierreHoy(bool $porUsuario = false): bool {
    $idUsuario = $porUsuario && !empty($_SESSION['id']) ? (int)$_SESSION['id'] : null;
    return ModeloVentas::mdlExisteCierreHoy($idUsuario);
  }

  // ---------------------------- RESUMEN MÉTODO DE PAGO -------------------------------
  public static function ctrResumenMetodoPagoDirecto() {
    return ModeloVentas::mdlResumenMetodoPago();
  }
  // ---------------------------- CALCULAR RETENCIÓN CON TARJETA -----------------------------------
  public static function ctrCalcularRetencion() {
    $tabla = "venta";
    $totalTarjeta = ModeloVentas::mdlObtenerTotalConTarjeta($tabla);
    $retencion = $totalTarjeta * 0.20;

    return [
      "total_tarjeta" => $totalTarjeta,
      "retencion" => $retencion
    ];
  }

  // ---------------------------- CALCULAR REINVERSIÓN SEMANAL ----------------------------
  public static function ctrCalcularReinversionSemanal() {
    return ModeloVentas::mdlCalcularReinversionSemanal();
  }

  // ---------------------------- CALCULAR GANANCIAS ----------------------------
  public static function ctrObtenerGanancias($periodo = 'semana', $fechaSeleccionada = null) {
    return ModeloVentas::mdlObtenerGanancias($periodo, $fechaSeleccionada);
  }
}
