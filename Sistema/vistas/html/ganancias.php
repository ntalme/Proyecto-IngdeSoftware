<?php
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "Administrador") {
  echo '<script>window.location = "inicio";</script>';
  return;
}
?>

<!-- Contenedor general -->
<div class="content-wrapper">

  <!-- Encabezado del contenido -->
  <section class="content-header">
    <div class="container-fluid">

      <!-- TITULO PAGINA -->
      <div class="row align-items-start mb-2">
        <div class="col-sm-8">
          <h1>Ganancias Semanales o Mensuales</h1>
        </div>
      </div>

      <!-- DESCRIPCIÓN DE LA PÁGINA -->
      <div class="row">
        <div class="col-12">
          <p class="descripcion-pagina">
            Consulte las ganancias del negocio por semana o mes, 
            visualizando el total de ventas, reinversión y utilidades obtenidas 
            en el período seleccionado. 
          </p>
        </div>
      </div>

      <!-- LÍNEA DIVISORA -->
      <hr class="linea-divisora">
    </div>
  </section>

  <!--------------------- CONTENIDO PRINCIPAL PAGINA ------------------------>
  <section class="content">
    <div class="container-fluid">
      <div class="card card-tabla-productos">
        <div class="card-body">

          <!-- FILTRO DE PERÍODO -->
          <form id="formGanancias" class="row g-2 align-items-end mb-3">
            <div class="col-12 col-sm-auto">
              <label for="tipoPeriodo" class="form-label mb-1">Período</label>
              <select id="tipoPeriodo" class="form-control">
                <option value="semana" selected>Ganancia Semanal</option>
                <option value="mes">Ganancia Mensual</option>
              </select>
            </div>

            <div class="col-12 col-sm-auto" id="colSemana">
              <label for="inputSemana" class="form-label mb-1">Semana</label>
              <input type="week" id="inputSemana" class="form-control">
            </div>

            <div class="col-12 col-sm-auto" id="colMes" style="display:none;">
              <label for="inputMes" class="form-label mb-1">Mes</label>
              <input type="month" id="inputMes" class="form-control">
            </div>

            <div class="col-12 col-sm-auto">
              <label class="form-label mb-1 d-block">&nbsp;</label>
              <button type="button" id="btnConsultar" class="btn btn-consultar">
                <i class="fas fa-search"></i> Consultar
              </button>
            </div>
          </form>

          <!-- RESUMEN -->
          <div id="resultadoGanancias" style="display:none;">
            <div class="row text-center mt-4">
              <div class="col-md-4 mb-3">
                <div class="card bg-success text-white p-3 rounded">
                  <h5>Ganancia Total</h5>
                  <h3 id="gananciaTotal">$0</h3>
                </div>
              </div>
              <div class="col-md-4 mb-3">
                <div class="card bg-info text-white p-3 rounded">
                  <h5>Reinversión Total</h5>
                  <h3 id="reinversionTotal">$0</h3>
                </div>
              </div>
              <div class="col-md-4 mb-3">
                <div class="card bg-warning text-dark p-3 rounded">
                  <h5>Total Ventas</h5>
                  <h3 id="ventasTotales">$0</h3>
                </div>
              </div>
            </div>

            <!-- GRAFICO -->
            <div id="wrapGrafico" class="mb-4" style="display:none;">
              <canvas id="graficoGanancias" height="110"></canvas>
            </div>

            <!-- TABLA DETALLE -->
            <div class="table-responsive">
              <table id="tablaGanancias" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Fecha</th>
                    <th>Total Ventas</th>
                    <th>Ganancia Día</th>
                    <th>Reinversión Día</th>
                  </tr>
                </thead>
                <tbody id="tablaDetalle"></tbody>
              </table>
            </div>
          </div>

          <!-- MENSAJE SIN DATOS -->
          <div id="sinDatos" class="text-center text-muted mt-4" style="display:none;">
            <em>No hay ventas en este período.</em>
          </div>

        </div>
      </div>
    </div>
  </section>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="vistas/js/ganancias.js"></script>

<!-- CSS -->
<link rel="stylesheet" href="vistas/css/ganancias.css">
