<?php
$cierres = ControladorVentas::ctrObtenerCierres();
?>

<!-- Contenedor general -->
<div class="content-wrapper">

  <!-- TITULO PAGINA: CIERRES DE CAJA -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Cierres de Caja</h1>
        </div>
      </div>

      <!-- DESCRIPCION DE LA PAGINA -->
      <div class="row">
        <div class="col-12">
          <p class="descripcion-pagina mb-0">
            En esta sección podrá gestionar los cierres diarios, ver el total de ventas,
            la cantidad de transacciones y revisar el detalle de cada cierre.
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
      <div class="row">
        <div class="col-12">

          <!-- Caja de la tabla -->
          <div class="card">

            <!-- Cuerpo -->
            <div class="card-body">
              <div class="table-responsive">
                <table class="table-cierres"> 

                  <!-- TABLA DE DATOS -->
                  <thead class="thead">
                    <tr>
                      <th style="width: 40px;">#</th>
                      <th>Fecha Cierre</th>
                      <th>Total Ventas</th>
                      <th>Cantidad Ventas</th>
                      <th>Usuario</th>
                      <th>Registrado en</th>
                      <th style="width: 120px;">Acciones</th>
                    </tr>
                  </thead>

                  <!-- Cuerpo de la tabla -->
                  <tbody>
                    <?php if (!empty($cierres)): ?>
                      <?php foreach ($cierres as $key => $cierre): ?>
                        <tr>
                          <td><?= $cierre["id"] ?></td>
                          <td><?= date("d-m-Y H:i", strtotime($cierre["fecha_cierre"])) ?></td>
                          <td>
                              $<?= number_format((float)$cierre["total_ventas"], 0, ",", ".") ?>
                          </td>
                          <td>
                              <?= (int)$cierre["cantidad_ventas"] ?>
                          </td>
                          <td><?= htmlspecialchars($cierre["usuario"]) ?></td>
                          <td><?= date("d-m-Y H:i", strtotime($cierre["creado_en"])) ?></td>
                          <td>
                            <div class="btn-group">
                              <!-- Botón Ver ventas -->
                              <button class="btn btn-ver-ventas btn-sm verVentas"
                                      title="Ver ventas del cierre"
                                      data-json='<?= htmlspecialchars($cierre["ventas_json"], ENT_QUOTES, "UTF-8") ?>'>
                                <i class="fas fa-eye"></i>
                              </button>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="8" class="text-center text-muted">
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

<!--------------------- MODAL: DETALLE VENTAS ------------------------>
<div class="modal fade" id="modalVentas" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content modal-elegante">

      <!-- Header -->
      <div class="modal-header modal-header-custom">
        <h5 class="modal-title">
          <i class="fas fa-shopping-cart mr-2"></i> Detalle de Ventas del Cierre
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Body -->
      <div class="modal-body modal-body-custom">
        <div class="alert alert-custom mb-3" role="alert">
          <i class="fas fa-info-circle mr-1"></i>
          A continuación se muestran todas las ventas registradas en este cierre.
        </div>

        <div class="table-responsive">
          <table class="table table-hover table-striped table-bordered shadow-sm rounded" id="tablaVentas">
            <thead class="thead-custom">
              <tr>
                <th>ID Venta</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Método Pago</th>
                <th>Productos</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer modal-footer-custom">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fas fa-times mr-1"></i> Cerrar
        </button>
      </div>
    </div>
  </div>
</div>

<!-- JS -->
<script src="vistas/js/cierre-caja.js"></script>
<!-- CSS-->
<link rel="stylesheet" href="vistas/css/cierre-caja.css">
