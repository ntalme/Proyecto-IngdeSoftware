<?php
$cierres = ControladorVentas::ctrObtenerCierres();
?>

<div class="content-wrapper">
  <!-- HEADER -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Cierres de Caja</h1>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <p class="descripcion-pagina mb-0">
            En esta sección puede revisar los cierres diarios, con el detalle completo de las ventas registradas.
          </p>
        </div>
      </div>
      <hr class="linea-divisora">
    </div>
  </section>

  <!-- CONTENIDO -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card shadow-sm">
            <!-- TABLA -->
            <div class="card-body">
              <div class="table-responsive">
                <table class="table-cierres">
                  <thead class="thead">
                    <tr>
                      <th>#</th>
                      <th>Fecha Cierre</th>
                      <th>Total Ventas</th>
                      <th>Cantidad</th>
                      <th>Usuario</th>
                      <th>Observaciones</th> 
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <!-- CONTENIDO TABLA -->
                  <tbody>
                    <?php if (!empty($cierres)): ?>
                      <?php foreach ($cierres as $c): ?>
                        <tr>
                          <td><?= $c["id"] ?></td>
                          <td><?= date("d-m-Y H:i", strtotime($c["fecha_cierre"])) ?></td>
                          <td>$<?= number_format((float)($c["total_general"] ?? 0), 0, ",", ".") ?></td>
                          <td><?= (int)$c["cantidad_ventas"] ?></td>
                          <td><?= htmlspecialchars($c["usuario"]) ?></td>

                          <td>
                            <?php if (!empty(trim($c["observaciones"]))): ?>
                              <?= nl2br(htmlspecialchars($c["observaciones"])) ?>
                            <?php else: ?>
                              <span class="text-muted"><em>Sin observaciones</em></span>
                            <?php endif; ?>
                          </td>

                          <td>
                            <div class="btn-group">
                              <!-- Botón Ver Ventas -->
                              <button class="btn btn-ver-ventas btn-sm verVentas"
                                      title="Ver ventas del cierre"
                                      data-json='<?= htmlspecialchars($c["ventas_json"], ENT_QUOTES, "UTF-8") ?>'>
                                <i class="fas fa-eye"></i>
                              </button>

                              <!-- Botón Ver Resumen -->
                              <button class="btn btn-ver-resumen btn-sm modalResumen"
                                      title="Ver resumen financiero"
                                      data-resumen='<?= htmlspecialchars(json_encode($c, JSON_UNESCAPED_UNICODE), ENT_QUOTES, "UTF-8") ?>'>
                                <i class="fas fa-chart-pie"></i>
                              </button>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="7" class="text-center text-muted">
                          <em>No existen cierres registrados.</em>
                        </td>
                      </tr>
                    <?php endif; ?>
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

<!--------------------- MODAL DETALLE DE VENTAS ------------------------>
<div class="modal fade" id="modalVentas" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content modal-elegante">

      <!-- HEADER -->
      <div class="modal-header modal-header-custom">
        <h5 class="modal-title">
          <i class="fas fa-shopping-cart mr-2"></i> Ventas del Cierre
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <!-- BODY -->
      <div class="modal-body modal-body-custom">
        <div class="alert alert-info mb-3">
          <i class="fas fa-info-circle mr-1"></i> A continuación se muestran las ventas registradas en este cierre.
        </div>

        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover text-center" id="tablaVentas">
            <thead class="thead-custom">
              <tr>
                <th>ID Venta</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Método Pago</th>
                <th>Productos</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>

      <!-- FOOTER -->
      <div class="modal-footer modal-footer-custom">
        <button class="btn btn-secondary" data-dismiss="modal">
          <i class="fas fa-times mr-1"></i> Cerrar
        </button>
      </div>
    </div>
  </div>
</div>

<!--------------------- MODAL RESUMEN DIARIO ------------------------>
<div class="modal fade" id="modalResumen" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content resumen-cuadros">

      <!-- HEADER -->
      <div class="modal-header resumen-header">
        <h5 class="modal-title mb-0">
          <i class="fas fa-chart-pie mr-2"></i> Resumen Diario del Cierre
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- BODY -->
      <div class="modal-body resumen-body">

        <!-- SECCIÓN A: INGRESOS DEL DÍA -->
        <h6 class="resumen-titulo-seccion">Resumen del Día</h6>
        <div class="resumen-grid">

          <div class="resumen-card total-general">
            <div class="icono"><i class="fas fa-hand-holding-usd"></i></div>
            <h6>Total General</h6>
            <p id="totalGeneral" class="monto monto-grande">$0</p>
          </div>

          <div class="resumen-card ventas-tarjeta">
            <div class="icono"><i class="fas fa-credit-card"></i></div>
            <h6>Ventas con Tarjeta</h6>
            <p id="ventasTarjeta" class="monto">$0</p>
          </div>

          <div class="resumen-card ventas-efectivo">
            <div class="icono"><i class="fas fa-money-bill-wave"></i></div>
            <h6>Ventas en Efectivo</h6>
            <p id="ventasEfectivo" class="monto">$0</p>
          </div>

          <div class="resumen-card boletas-digitales">
            <div class="icono"><i class="fas fa-receipt"></i></div>
            <h6>Monto Boletas Digitales</h6>
            <p id="boletasDigitales" class="monto">$0</p>
          </div>
        </div>

        <!-- SECCIÓN B: RETENCIONES Y DESCUENTOS -->
        <h6 class="resumen-titulo-seccion mt-4">Retenciones y Descuentos</h6>
        <div class="resumen-grid">

          <div class="resumen-card retencion-boletas">
            <div class="icono"><i class="fas fa-minus-circle"></i></div>
            <h6>Retención Boletas Digitales</h6>
            <p id="retencionBoletas" class="monto text-danger">$0</p>
          </div>

          <div class="resumen-card retencion-tarjeta">
            <div class="icono"><i class="fas fa-percent"></i></div>
            <h6>Retención Pagos con Tarjeta</h6>
            <p id="retencionTarjeta" class="monto text-danger">$0</p>
          </div>

          <div class="resumen-card descuento-contadora">
            <div class="icono"><i class="fas fa-user-check"></i></div>
            <h6>Descuento Contadora</h6>
            <p id="descuentoContadora" class="monto text-danger">$0</p>
          </div>

          <div class="resumen-card descuento-luz d-none">
            <div class="icono"><i class="fas fa-bolt"></i></div>
            <h6>Descuento Luz (Domingo)</h6>
            <p id="descuentoLuz" class="monto text-danger">$0</p>
          </div>
        </div>

        <!-- SECCIÓN C: RESULTADO FINAL -->
        <h6 class="resumen-titulo-seccion mt-4">Resultado del Día</h6>
        <div class="resumen-grid">

          <div class="resumen-card ganancia-dia">
            <div class="icono"><i class="fas fa-coins"></i></div>
            <h6>Ganancia del Día</h6>
            <p id="gananciaDia" class="monto monto-grande">$0</p>
          </div>

          <div class="resumen-card reinversion">
            <div class="icono"><i class="fas fa-sync-alt"></i></div>
            <h6>Reinversión</h6>
            <p id="reinversion" class="monto">$0</p>
          </div>

          <div class="resumen-card reinversion-semanal d-none">
            <div class="icono"><i class="fas fa-layer-group"></i></div>
            <h6>Reinversión Semanal Acumulada</h6>
            <p id="reinversionSemanal" class="monto">$0</p>
          </div>

          <div class="resumen-card reinversion-final d-none">
            <div class="icono"><i class="fas fa-check-circle"></i></div>
            <h6>Reinversión Semanal Final</h6>
            <p id="reinversionSemanalFinal" class="monto">$0</p>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- JS -->
<script src="vistas/js/cierre-caja.js"></script>
<!-- CSS -->
<link rel="stylesheet" href="vistas/css/cierre-caja.css">

