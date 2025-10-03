<?php
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "Administrador") {
  echo '<script>window.location = "inicio";</script>';
  return;
}
?>
<!-- Contenedor general -->
<div class="content-wrapper">

    <!-- Título de la vista  -->
    <section class="content-header">
        <div class="container-fluid">

            <!-- TITULO PAGINA: PRODUCTOS VENDIDOS -->
            <div class="row align-items-start mb-2">
                <div class="col-sm-8">
                <h1>Productos Vendidos en un Día</h1>
                </div>
            </div>

            <!-- DESCRIPCION DE LA PAGINA -->
            <div class="row">
                <div class="col-12">
                <p class="descripcion-pagina">
                Aquí puede consultar los productos vendidos en una fecha específica, junto con su formato, tamaño, cantidad y monto total.
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
            <div class="card card-tabla-productos">
                <div class="card-body">

                <!-- FILTRO CONSULTAR POR FECHA -->
                <form id="formConsultaProductosDia" class="row g-2 align-items-end mb-3">
                    <div class="col-12 col-sm-auto">
                        <label for="fechaConsulta" class="form-label mb-1 me-2">Fecha</label>
                        <div class="input-group">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        <input type="date" id="fechaConsulta" name="fechaConsulta" class="form-control" required>
                        </div>
                    </div>

                    <!-- BOTON: CONSULTAR -->
                    <div class="col-12 col-sm-auto">
                        <label class="form-label mb-1 d-block">&nbsp;</label>
                        <button type="submit" id="btnConsultar" class="btn btn-dark btn-consultar">
                        <i class="fas fa-search"></i> Consultar
                        </button>
                    </div>

                    <!-- BUSCADOR -->
                    <div class="col ms-auto d-flex justify-content-end">
                    <div class="input-group input-group-sm" id="buscadorSCWrap" style="max-width: 300px;">
                        <input type="text" id="buscarSC" class="form-control" placeholder="Buscar...">
                        <span class="input-group-text bg-white" id="btnBuscarSC" role="button" aria-label="Buscar">
                        <i class="fas fa-search"></i>
                        </span>
                    </div>
                    </div>
                    </form>

                    <!-- TABLA DE DATOS-->
                    <div class="table-responsive">
                        <table id="tablaProductosVendidos" class="table-vendidos">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Producto</th>
                            <th>Formato</th>
                            <th>Tamaño</th>
                            <th>Cantidad</th>
                            <th>Monto</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">Totales:</th>
                            <th id="footCantidad" class="text-end">0</th>
                            <th id="footMonto" class="text-end">$0</th>
                        </tr>
                        </tfoot>
                    </table>
                    </div>

                    <!--------------------- GRAFICO TOP DEL MES ------------------------>
                    <div id="wrapTopMes" class="mt-4" style="display:none;">
                    <h5 class="mb-2">
                        <i class="fas fa-chart-pie me-1"></i> Productos más vendidos del mes
                    </h5>
                    <div class="chart-container" style="max-width:600px;margin:0 auto;">
                        <canvas id="graficoTopMes" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- JS -->
<script src="vistas/js/productos-vendidos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<!-- CSS -->
<link rel="stylesheet" href="vistas/css/productos-vendidos.css">