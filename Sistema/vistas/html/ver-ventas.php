<!-- Contenedor principal -->
<div class="content-wrapper">

  <!-- Encabezado de la página -->
  <section class="content-header">
    <div class="container-fluid">

      <!-- TITULO PAGINA: LISTADO DE VENTAS -->
      <div class="row align-items-start mb-2">
        <div class="col-12">
          <h1>Listado de Ventas</h1>
        </div>
      </div>

      <!-- DESCRIPCION DE LA PAGINA -->
      <div class="row">
        <div class="col-12">
          <p class="descripcion-pagina">
            En esta sección podrá consultar todas las ventas registradas en el sistema. 
            Es posible visualizar la fecha, el vendedor, los productos asociados con su formato y tamaño, 
            la cantidad vendida, el monto total y el método de pago utilizado.
          </p>
        </div>
      </div>

      <!-- LINEA DIVISORA -->
      <hr class="linea-divisora">
    </div>
  </section>

  <!--------------------- CONTENIDO PRINCIPAL PAGINA ------------------------>
  <section class="content">
    <div class="container-fluid">

      <!-- Caja que contiene la tabla de ventas -->
      <div class="card card-tabla-productos">
        <div class="card-body">

          <!-- Controles de orden + buscador a la derecha -->
          <div class="row g-2 align-items-end mb-3 controles-orden">

            <!-- ORDENAR POR -->
            <div class="col-auto">
              <label class="form-label mb-1 me-2">Ordenar por</label>
              <select id="criterioOrdenVentas" class="form-select d-inline-block" style="width:auto;">
                <option value="0">ID</option>
                <option value="1">Vendedor</option>
                <option value="2">Total</option>
                <option value="3">Método de pago</option>
                <option value="4" selected>Fecha y Hora</option>
                <option value="5">Productos</option>
                <option value="6">Observación</option>
              </select>
            </div>
            <div class="col-auto">
              <label class="form-label mb-1 me-2">Dirección</label>
              <select id="direccionOrdenVentas" class="form-select d-inline-block" style="width:auto;">
                <option value="asc">Ascendente</option>
                <option value="desc" selected>Descendente</option>
              </select>
            </div>

            <!-- BUSCADOR -->
            <div class="col ms-auto d-flex justify-content-end">
              <div class="input-group input-group-sm" id="buscadorVentasWrap" style="max-width:280px;">
                <input type="text" id="buscarVentas" class="form-control border-end-0" placeholder="Buscar...">
                <button class="btn btn-outline-secondary border-start-0" id="btnBuscarVentas" type="button" aria-label="Buscar">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- TABLA DE DATOS -->
          <div class="table-responsive">
            <table id="tablaVentas" class="table-fresh">
              <thead>
                <tr class="text-center">
                  <th>#</th>
                  <th>Vendedor</th>
                  <th>Total</th>
                  <th>Método de pago</th>
                  <th>Fecha y Hora</th>
                  <th>Productos</th>
                  <th>Observación</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $ventas = ControladorVentas::ctrMostrarVentas(null, null);

                  foreach ($ventas as $venta) {
                    $productos = json_decode($venta["productos"] ?? '[]', true);
                    if (!is_array($productos)) { $productos = []; }

                    // Vendedor
                    $usuario = ControladorUsuarios::ctrMostrarUsuarios("id", $venta["id_usuario"]);
                    $nombreVendedor = $usuario ? ($usuario["nombre"] ?? "Sin nombre") : "Sin nombre";

                    // -------- Indexar promociones a nivel de venta (si existen) --------
                    $idxPromos = [];
                    $promosVentaRaw = $venta["promociones_aplicadas"] ?? ($venta["promociones_Aplicadas"] ?? null);
                    if (!empty($promosVentaRaw)) {
                      $pa = json_decode($promosVentaRaw, true);
                      if (is_array($pa)) {
                        foreach ($pa as $k => $p) {
                          $idProd  = $p['id_producto']      ?? $p['producto_id']      ?? null;
                          $nomProd = $p['nombre_producto']  ?? $p['producto_nombre']  ?? (is_string($k) ? $k : null);

                          if ($idProd !== null) {
                            $idxPromos["id:$idProd"][] = $p;
                          } elseif ($nomProd !== null) {
                            $idxPromos["nm:$nomProd"][] = $p;
                          } else {
                            $idxPromos["*"][] = $p; // sin referencia explícita
                          }
                        }
                      }
                    }

                    // Formateador de etiqueta de promo
                    $fmtPromo = function($p) {
                      $et = $p['etiqueta'] ?? null;
                      if (!$et) {
                        $tipo = strtolower($p['tipo'] ?? '');
                        $param = $p['parametro'] ?? null;
                        if ($tipo === 'descuento' && is_numeric($param)) {
                          // porcentaje con strip de ceros
                          $val = rtrim(rtrim(number_format((float)$param, 2, '.', ''), '0'), '.');
                          $et = "-{$val}%";
                        } elseif ($tipo === 'precio_fijo' && is_numeric($param)) {
                          $et = '$'.number_format((float)$param, 0, ",", ".").' fijo';
                        } elseif ($tipo === '2x1' || $tipo === '2por1' || $tipo === 'dos_por_uno') {
                          $et = '2x1';
                        } else {
                          $et = strtoupper($tipo ?: 'PROMO');
                        }
                      }
                      return htmlspecialchars($et, ENT_QUOTES, 'UTF-8');
                    };

                    // Tipo → clase visual
                    $clsPromo = function($p) {
                      $tipo = strtolower($p['tipo'] ?? '');
                      if ($tipo === 'descuento')   return 'is-descuento';
                      if ($tipo === 'precio_fijo') return 'is-precio';
                      if ($tipo === '2x1' || $tipo === '2por1' || $tipo === 'dos_por_uno') return 'is-2x1';
                      return 'is-generic';
                    };

                    echo '<tr>
                            <td class="text-center">#'.htmlspecialchars((string)$venta["id"]).'</td>
                            <td class="text-center">'.htmlspecialchars($nombreVendedor).'</td>
                            <td class="text-center">$'.number_format((float)$venta["total"], 0, ",", ".").'</td>
                            <td class="text-center">'.htmlspecialchars((string)$venta["metodo_pago"]).'</td>
                            <td class="text-center">'.date("d/m/Y H:i", strtotime($venta["fecha"])).'</td>
                            <td>
                              <ul class="mb-0 lista-productos-venta">';

                                foreach ($productos as $prod) {
                                  $nombre   = $prod["nombre"]   ?? "Producto sin nombre";
                                  $cantidad = $prod["cantidad"] ?? "0";
                                  $idProd   = $prod["id"] ?? ($prod["id_producto"] ?? null);

                                  // Recolectar promos que apliquen a este producto
                                  $promos = [];
                                  // A) embebidas en el producto
                                  if (!empty($prod["promo"]))          { $promos[] = $prod["promo"]; }
                                  if (!empty($prod["promo_aplicada"])) { $promos[] = $prod["promo_aplicada"]; }
                                  // B) indexadas a nivel de venta
                                  if ($idProd !== null && !empty($idxPromos["id:$idProd"])) {
                                    foreach ($idxPromos["id:$idProd"] as $p) { $promos[] = $p; }
                                  } elseif (!empty($idxPromos["nm:$nombre"])) {
                                    foreach ($idxPromos["nm:$nombre"] as $p) { $promos[] = $p; }
                                  } elseif (!empty($idxPromos["*"])) {
                                    // opcional: mostrar también las no mapeadas
                                    foreach ($idxPromos["*"] as $p) { $promos[] = $p; }
                                  }

                                  echo '<li>';
                                  echo htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') . ' x' . htmlspecialchars((string)$cantidad, ENT_QUOTES, 'UTF-8');

                                  if (!empty($promos)) {
                                    foreach ($promos as $p) {
                                      $label = $fmtPromo($p);
                                      $cls   = $clsPromo($p);
                                      $title = htmlspecialchars($p['detalle'] ?? $p['observacion'] ?? '', ENT_QUOTES, 'UTF-8');
                                      echo ' <span class="badge-promo '.$cls.'"'.($title ? ' title="'.$title.'"' : '').'>'.$label.'</span>';
                                    }
                                  }

                                  echo '</li>';
                                }

                    echo    '</ul>
                            </td>
                            <td class="text-center">'.(!empty($venta["observacion"]) ? nl2br(htmlspecialchars((string)$venta["observacion"])) : '<em>Sin observación</em>').'</td>
                            <td class="text-center">
                              <a href="index.php?ruta=ventas&accion=comprobante&idVenta='.urlencode((string)$venta['id']).'" 
                                 class="btn btn-sm btn-danger">
                                <i class="fas fa-file-pdf mr-1"></i> Descargar PDF
                              </a>
                            </td>
                          </tr>';
                  }
                ?>
              </tbody>
            </table>
          </div> 
        </div>
      </div>
    </div> 
  </section> 
</div>

<!-- JS -->
<script src="vistas/js/ver-ventas.js"></script>
<!-- CSS -->
<link rel="stylesheet" href="vistas/css/ver-ventas.css">