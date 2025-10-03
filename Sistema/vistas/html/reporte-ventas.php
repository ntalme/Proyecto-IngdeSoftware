<?php
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "Administrador") {
  echo '<script>window.location = "inicio";</script>'; return;
}
?>

<!-- Contenedor general -->
<div class="content-wrapper">

    <!-- Título de la vista  -->
    <section class="content-header">
      <div class="container-fluid">

        <!-- TITULO PAGINA: REPORTES VENTAS -->
        <div class="row align-items-start mb-2">
          <div class="col-sm-8">
            <h1>Reportes de Ventas Diarias</h1>
          </div>
        </div>

        <!-- DESCRIPCION DE LA PAGINA -->
        <div class="row">
          <div class="col-12">
            <p class="descripcion-pagina">
              Revise el total de ventas del día y los horarios de mayor venta. Seleccione una fecha para analizar.
            </p>
          </div>
        </div>
        <hr class="linea-divisora">
      </div>
    </section>

 <!--------------------- CONTENIDO PRINCIPAL PAGINA ------------------------>
  <section class="content">
    <div class="container-fluid">
      <div class="card card-tabla-productos">
        <div class="card-body">

          <!-- FILTRO -->
          <form id="formReporteVentasDia" class="row g-2 align-items-end mb-3">
            <div class="col-12 col-sm-auto">
              <label for="fechaReporte" class="form-label mb-1">Fecha</label>
              <div class="input-group">
                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                <input type="date" id="fechaReporte" name="fechaReporte" class="form-control" required>
              </div>
            </div>
            <div class="col-12 col-sm-auto">
              <label class="form-label mb-1 d-block">&nbsp;</label>
              <button type="submit" class="btn btn-dark btn-consultar">
                <i class="fas fa-search"></i> Consultar
              </button>
            </div>
          </form>

          <!-- RESUMEN -->
          <div id="resumenDia" class="mb-3" style="display:none;">
            <div class="row">
              <div class="col-sm-6 col-lg-3"><strong>Fecha:</strong> <span id="rFecha">-</span></div>
              <div class="col-sm-6 col-lg-3"><strong>Ventas:</strong> <span id="rVentas">0</span></div>
              <div class="col-sm-6 col-lg-3"><strong>Monto del día:</strong> <span id="rMonto">$0</span></div>
              <div class="col-sm-6 col-lg-3"><strong>Promedio de Venta por Boleta:</strong> <span id="rTicket">$0</span></div>
            </div>
            <div class="row mt-2">
              <div class="col-sm-6 col-lg-3"><strong>Hora pico:</strong> <span id="rHoraPico">-</span> h</div>
            </div>
          </div>

          <!-- GRAFICO -->
          <div id="wrapGrafico" class="mb-3" style="display:none;">
            <canvas id="graficoHoras" height="110"></canvas>
          </div>

          <!-- TABLA POR HORA -->
          <div class="table-responsive">
            <table id="tablaVentasPorHora" class="table-horas">
              <thead>
                <tr>
                  <th>Hora</th>
                  <th>Ventas</th>
                  <th>Monto Total</th>
                  <th>Promedio de Venta por Boleta</th>
                </tr>
              </thead>
              <tbody></tbody>
              <tfoot>
                <tr>
                  <th class="text-end">Totales:</th>
                  <th id="tVentas" class="text-right">0</th>
                  <th id="tMonto" class="text-right">$0</th>
                  <th id="tTicket" class="text-right">$0</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="vistas/js/reporte-ventas-diarias.js"></script>
<!-- CSS -->
<link rel="stylesheet" href="vistas/css/reporte-ventas.css">
