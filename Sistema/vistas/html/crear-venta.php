<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'cerrarCaja') {
  ControladorVentas::ctrCrearCierre();
}
?>

<!-- Contenedor general -->
<div class="content-wrapper">

  <!-- Título de la vista  -->
  <section class="content-header">
    <div class="container-fluid">

      <!-- TITULO PAGINA: AÑADIR STOCK -->
      <div class="row align-items-center mb-3">
        <!-- Título -->
        <div class="col-sm-8">
          <h1 class="mb-0">Crear venta</h1>
        </div>

        <!-- BOTON: OBSERVACION -->
        <div class="col-sm-4 d-flex justify-content-end">
          <button type="button" id="btnMostrarObservacion" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-comment-dots mr-1"></i> Agregar observación
          </button>

          <!-- BOTON: CIERRE CAJA -->
          <button type="button" class="btn btn-cerrar-caja btn-sm ml-2" data-toggle="modal" data-target="#modalCierreCaja">
            <i class="fas fa-cash-register mr-1"></i> Cerrar Caja
          </button>
        </div>
      </div>

     <!-- DESCRIPCION DE LA PAGINA -->
      <div class="row">
        <div class="col-12">
          <p class="descripcion-pagina">
            Aquí puede registrar una nueva venta, seleccionando los productos, indicando cantidades y agregando observaciones si es necesario.
          </p>
        </div>
      </div>

      <!-- LINEA DIVISORA -->
      <hr class="linea-divisora">
      
      <!--------------------- PROMOCIONES ACTIVAS ------------------------>
      <?php
        $promosActivas = ControladorPromociones::ctrPromocionesActivas();
        $hayPromos = !empty($promosActivas);
      ?>
      <!-- Titulo -->
      <div class="promo-showcase mb-3">
        <div class="promo-showcase__header">
          <div class="promo-showcase__title">
            <i class="fas fa-bullhorn"></i>
            Promociones activas
            <?php if ($hayPromos): ?><span class="count"><?php echo count($promosActivas); ?></span><?php endif; ?>
          </div>
          <button class="promo-toggle" type="button"
                  data-toggle="collapse" data-target="#promoShowcaseBody"
                  aria-expanded="false" aria-controls="promoShowcaseBody">
            Mostrar
          </button>
        </div>

        <!-- Si no hay promos -->
        <div id="promoShowcaseBody" class="collapse">
          <?php if (!$hayPromos): ?>
            <div class="promo-empty">No hay promociones activas por ahora.</div>
          <!-- Si hay promos -->
          <?php else: ?>
            <div class="promo-cards">
              <!-- Tipos de Promos -->
              <?php foreach ($promosActivas as $pr):

                if ($pr['tipo'] === 'descuento') {
                  $title = intval($pr['parametro'])."% DESCUENTO";
                  $cls   = "is-descuento";
                  $icon  = "fas fa-bolt";
                } elseif ($pr['tipo'] === 'precio_fijo') {
                  $title = "PRECIO $".number_format($pr['parametro'],0,',','.');
                  $cls   = "is-precio";
                  $icon  = "fas fa-tag";
                } else {
                  $title = "2×1";
                  $cls   = "is-2x1";
                  $icon  = "fas fa-gift";
                }

                $chips = [];
                if (!empty($pr['productos'])) {
                  foreach (array_filter(array_map('trim', explode(',', $pr['productos']))) as $prod) {
                    $chips[] = '<span class="promo-chip promo-chip-big">'.htmlspecialchars($prod).'</span>';
                  }
                }

                $ini = date('d-m-Y H:i', strtotime($pr['fecha_inicio']));
                $fin = date('d-m-Y H:i', strtotime($pr['fecha_fin']));
                $obs = trim((string)$pr['observacion']);
              ?>
                <div class="promo-card <?php echo $cls; ?>">
                  <div class="promo-accent"></div>
                  <div class="promo-main">
                    <div class="promo-left">
                      <div class="promo-icon"><i class="<?php echo $icon; ?>"></i></div>
                      <div class="promo-title"><?php echo htmlspecialchars($title); ?></div>
                    </div>

                    <div class="promo-right">
                      <?php if ($chips): ?>
                        <div class="promo-chips">
                          <?php echo implode('', $chips); ?>
                        </div>
                      <?php endif; ?>

                      <div class="promo-validity">
                        <i class="far fa-calendar-alt"></i>
                        Válido del <strong><?php echo $ini; ?></strong> al <strong><?php echo $fin; ?></strong>
                      </div>

                      <?php if ($obs !== ''): ?>
                        <div class="promo-note"><i class="far fa-sticky-note"></i> <?php echo htmlspecialchars($obs); ?></div>
                      <?php endif; ?>
                    </div>

                    <!-- Botón eliminar -->
                   <?php if ($_SESSION["rol"] === "Administrador"): ?>
                      <button type="button"
                              class="btn btn-sm btn-danger btnEliminarPromocion"
                              idPromocion="<?php echo $pr['id']; ?>">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    <?php else: ?>
                      <button type="button"
                              class="btn btn-sm btn-danger"
                              onclick="sinPermisoPromos(); return false;">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>
  <!--------------------- SOLO ADMINISTRADOR ------------------------>
  <?php if ($_SESSION["rol"] !== "Administrador"): ?>
  <script>
    function sinPermisoPromos(){
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          icon: 'error',
          title: 'Acceso denegado',
          text: 'Solo los administradores pueden eliminar promociones.',
          confirmButtonText: 'Entendido'
        });
      } else {
        alert('Acceso denegado. Solo los administradores pueden eliminar promociones.');
      }
    }
  </script>
  <?php endif; ?>

  <!--------------------- CONTENIDO PRINCIPAL PAGINA ------------------------>
  <section class="content">
    <div class="container-fluid">
      <div class="row">

        <!-- COLUMNA IZQUIERDA: VENTA -->
        <div class="col-lg-5">
          <div class="card card-tabla-productos">
            <form class="formularioVenta" method="POST">

            <!-- Encabezado del formulario con nombre del vendedor -->
            <div class="card-header d-flex justify-content-between align-items-center text-white rounded-top">
              <span class="fw-bold">
                <i class="me-2"></i> Venta
              </span>
              <span class="small">
                <i class="fas fa-user me-1"></i> Vendedor: <strong><?php echo $_SESSION["nombre"]; ?></strong>
              </span>
              <!-- ID del usuario (oculto) -->
              <input type="hidden" name="idUsuario" value="<?php echo $_SESSION['id']; ?>">
            </div>

            <!-- Cuerpo del formulario -->
            <div class="card-body">

                <!-- Productos que se han agregado a esta venta -->
                <div class="card mb-3 card-productos mt-2">
                  <div class="card-header">
                    <strong> Productos en la venta</strong>
                  </div>
                  <div class="card-body p-0">
                    <div class="venta-grid">
                        <!-- Encabezado -->
                        <div class="venta-grid__header">
                          <div class="col-sm-4">Nombre</div>
                          <div class="col-sm-2">Cantidad</div>
                          <div class="col-sm-3">Precio</div>
                          <div class="col-sm-3">Acciones</div>
                        </div>
                        <!-- Aquí se insertan los productos con JS -->
                        <div class="nuevoProducto"></div>
                    </div>
                  </div>
                </div>

                <!-- Total de la venta -->
                <div class="row mb-3">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="fw-bold">Total</label>
                      <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="text" class="form-control totalVenta" id="totalVenta" name="totalVenta" readonly value="000000">
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Selección del método de pago -->
                <div class="row mb-3">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="metodoPago" class="fw-bold">Método de pago</label>
                      <select id="metodoPago" class="form-control">
                        <option disabled selected>Seleccione método de pago</option>
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta">Tarjeta</option>
                      </select>
                      <!-- Se guarda en este input el valor seleccionado -->
                      <input type="hidden" id="listaMetodoPago" name="listaMetodoPago">
                    </div>
                  </div>
                  <!-- Aquí se muestran inputs adicionales según el método de pago -->
                  <div id="contenedorMetodos" class="col-md-8"></div>
                </div>

                <!-- Datos ocultos que se envían con el formulario -->
                <input type="hidden" id="listaProductos" name="listaProductos">
                <input type="hidden" name="nuevaVenta" value="1">

                <!-- Campo de observación-->
                <div class="row mb-3" id="campoObservacion" style="display: none;">
                  <div class="col-12">
                    <label class="fw-bold">Observación</label>
                    <textarea name="observacionVenta" id="observacionVenta" class="form-control" rows="3" placeholder="Ingrese alguna nota sobre esta venta..."></textarea>
                  </div>
                </div>

                <!-- Botón para guardar la venta -->
                <button type="submit" class="btn btn-guardar w-100">Guardar venta</button>

              </form>

              <!-- Aquí se procesa la venta con PHP -->
              <?php
                $guardarVenta = new ControladorVentas();
                $guardarVenta->ctrCrearVenta();
              ?>
            </div> 
          </div> 
        </div>

        <!-- COLUMNA DERECHA: PRDOUCTOS PARA ELEGIR-->
        <div class="col-lg-7">
          <div class="card card-tabla-productos">

            <!-- Header negro -->
            <div class="card-header d-flex justify-content-between align-items-center text-white">
              <span>Agregar productos a la venta</span>
            </div>

            <!-- Cuerpo -->
            <div class="card-body">

              <!-- Buscador debajo del header -->
              <div class="d-flex justify-content-end mb-2">
                <div class="input-group input-group-sm" id="buscadorVentaWrap" style="max-width:280px;">
                  <input type="text" id="buscarVentaProducto" class="form-control" placeholder="Buscar producto...">
                  <span class="input-group-text" id="btnBuscarVentaProducto" role="button" aria-label="Buscar">
                    <i class="fas fa-search"></i>
                  </span>
                </div>
              </div>

              <!-- Tabla -->
              <div class="table-responsive">
                <table id="tablaProductos" class="tabla-productos">
                  <thead>
                    <tr>
                      <th>Imagen</th>
                      <th class="th-codigo">Código</th>
                      <th>Nombre</th>
                      <th class="th-formato">Formato</th>
                      <th class="th-tamano">Tamaño</th>
                      <th class="th-marca">Marca</th>
                      <th>Stock</th>
                      <th>Precio</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $productos = ControladorProductos::ctrMostrarProductosActivos();
                      foreach ($productos as $value) {
                        $src = (!empty($value["imagen"]) && file_exists($value["imagen"])) 
                                ? $value["imagen"] : "vistas/imagenes/sinfoto.png";

                        // === NUEVO: data-* para el JS de promociones ===
                        echo '<tr
                                data-id="'.$value["id"].'"
                                data-precio="'.(float)$value["precio_venta"].'"
                                data-nombre="'.htmlspecialchars($value["nombre"], ENT_QUOTES, "UTF-8").'">
                          <td class="text-center" data-label="Imagen">
                            <img src="'.$src.'" class="img-thumbnail"
                                style="width:60px; height:60px; object-fit:cover;"
                                alt="Foto producto"
                                onerror="this.onerror=null;this.src=\'vistas/imagenes/sinfoto.png\';">
                          </td>
                          <td class="td-codigo" data-label="Código">'.$value["codigo"].'</td>
                          <td data-label="Nombre">'.$value["nombre"].'</td>
                          <td class="td-formato" data-label="Formato">'.$value["formato"].'</td>
                          <td class="td-tamano" data-label="Tamaño">'.$value["tamano"].'</td>
                          <td class="td-marca" data-label="Marca">'.$value["marca"].'</td>
                          <td data-label="Stock">'.$value["cantidad"].'</td>
                          <td data-label="Precio">'.(float)$value["precio_venta"].'</td>
                          <td data-label="Acciones">
                            <div class="btn-group">
                              <button class="btn btnAgregarProducto btn-sm"
                                      idProducto="'.$value["id"].'">
                                Agregar
                              </button>
                            </div>
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
      </div>
    </div>
  </section>
</div>

<!--------------------- MODAL: CIERRE CAJA ------------------------>
<div class="modal fade" id="modalCierreCaja" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
     <form method="POST" action="index.php?ruta=crear-venta" id="formCierreCaja" class="modal-content cierre-modal">

      <!-- Header -->
      <div class="modal-header cierre-header">
        <div class="d-flex align-items-center">
          <div class="cierre-icon mr-2"><i class="fas fa-cash-register"></i></div>
          <div>
            <h5 class="modal-title mb-0">Cierre de Caja — Resumen del Día</h5>
            <small class="text-muted">Revise los datos y confirme el cierre de la jornada</small>
          </div>
        </div>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Body -->
      <div class="modal-body">
        <?php
          // Datos para el resumen (ajustado a sus tablas singulares)
          $totalHoy    = ControladorVentas::ctrObtenerTotalDelDia();
          $cantidadHoy = ControladorVentas::ctrObtenerCantidadDelDia();

          // Fecha y usuario (opcional, según su sesión)
          $tz = new DateTimeZone('America/Santiago');
          $hoyCL = (new DateTime('now', $tz))->format('d-m-Y');
          $nombreUsuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : ('Usuario #'.(int)($_SESSION['id'] ?? 0));
        ?>

        <!-- AVISO: solo 1 cierre por día -->
        <div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
          <i class="fas fa-lock mr-2"></i>
          <div>
            <strong>Importante:</strong> el cierre de caja se puede realizar <u>solo una vez por día</u>. 
          </div>
        </div>

        <!-- KPI Cards -->
        <div class="row">
          <div class="col-md-4 mb-3">
            <div class="card shadow-sm kpi-card">
              <div class="card-body">
                <div class="kpi-label">Fecha</div>
                <div class="kpi-value"><?= htmlspecialchars($hoyCL) ?></div>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="card shadow-sm kpi-card">
              <div class="card-body">
                <div class="kpi-label">Total ventas del día</div>
                <div class="kpi-value">$<?= number_format((float)$totalHoy, 2, ',', '.') ?></div>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="card shadow-sm kpi-card">
              <div class="card-body">
                <div class="kpi-label">Cantidad de ventas</div>
                <div class="kpi-value"><?= (int)$cantidadHoy ?></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Detalle contextual -->
        <div class="card shadow-sm mb-3">
          <div class="card-body py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
              <div class="mb-2">
                <span class="text-muted d-block" style="line-height:1;">Usuario responsable</span>
                <strong><?= htmlspecialchars($nombreUsuario) ?></strong>
              </div>
            </div>
          </div>
        </div>

        <!-- Observaciones -->
        <div class="form-group">
          <label class="mb-1 font-weight-600">Observaciones (opcional)</label>
          <textarea name="observaciones" class="form-control" rows="3" placeholder="Notas internas del cierre, incidencias, arqueo, etc."></textarea>
          <small class="form-text text-muted">Estas observaciones quedarán registradas en <em>Cierre Caja Observaciones</em>.</small>
        </div>

        <!-- Confirmación -->
        <div class="custom-control custom-checkbox mt-2">
          <input type="checkbox" class="custom-control-input" id="chkConfirmaCierre" name="chkConfirmaCierre" value="1">
          <label class="custom-control-label" for="chkConfirmaCierre">
            Declaro que revisé la información y deseo confirmar el cierre de la jornada.
          </label>
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer d-flex align-items-center justify-content-between">
        <div class="text-muted small">
          <i class="far fa-shield-check mr-1"></i>
          Al confirmar, se guardará un registro en <code>Cierre Caja</code>.
        </div>

        <div>
          <input type="hidden" name="accion" value="cerrarCaja">
          <button type="button" class="btn btn-cancelar" data-dismiss="modal">
            Cancelar
          </button>
          <button type="submit" id="btnConfirmarCierre" class="btn btn-cierre ml-2" disabled>
            <i class="fas fa-check mr-1"></i> Confirmar Cierre
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- JS -->
<script src="vistas/js/crear-venta.js"></script>
<!-- CSS -->
<link rel="stylesheet" href="vistas/css/crear-venta.css">