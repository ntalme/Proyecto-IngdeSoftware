<!-- Contenedor principal del contenido de la página -->
<div class="content-wrapper">

  <!-- Encabezado del contenido -->
  <section class="content-header">
    <div class="container-fluid">

      <!-- TITULO PAGINA: AÑADIR STOCK -->
      <div class="row align-items-start mb-2">
        <div class="col-12">
          <h1>Añadir Stock</h1>
        </div>
      </div>

      <!-- DESCRIPCION DE LA PAGINA -->
      <div class="row">
        <div class="col-12">
          <p class="descripcion-pagina mb-0">
            En esta sección podrá registrar el ingreso de nuevas unidades a los productos existentes,
            actualizando las cantidades disponibles en el inventario.
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

          <!-- Controles de orden + buscador a la derecha -->
          <div class="row g-2 align-items-end mb-3 filtros-stock w-100">

            <!-- ORDENAR POR -->
            <div class="col-auto">
              <label class="form-label mb-1 me-2">Ordenar por</label>
              <select id="criterioOrdenStock" class="form-select form-select-sm w-auto select-bonito">
                <option value="0" selected>Id</option>
                <option value="1">Código de barras</option>
                <option value="2">Nombre</option>
                <option value="3">Formato</option>
                <option value="4">Tamaño</option>
                <option value="5">Marca</option>
                <option value="6">Cantidad</option>
                <option value="7">Fecha de recepción</option>
                <option value="8">Fecha de vencimiento</option>
                <option value="9">Proveedor</option>
              </select>
            </div>

            <!-- DIRECCION ORDENAMIENTO -->
            <div class="col-auto">
              <label class="form-label mb-1 me-2">Dirección</label>
              <select id="direccionOrdenStock" class="form-select form-select-sm w-auto select-bonito">
                <option value="asc">Ascendente</option>
                <option value="desc" selected>Descendente</option>
              </select>
            </div>

            <!-- BUSCADOR -->
            <div class="col ms-auto d-flex justify-content-end">
              <div class="input-group input-group-sm" id="buscadorStockWrap" style="max-width: 300px;">
                <input type="text" class="form-control" placeholder="Buscar..." id="buscarStock">
                <span class="input-group-text bg-white" id="btnBuscarStock" role="button" aria-label="Buscar">
                  <i class="fas fa-search"></i>
                </span>
              </div>
            </div>
          </div>

          <!-- TABLA DE DATOS -->
          <div class="table-responsive">
            <table id="tablaAddStock" class="table-addstock">
              <thead>
                <tr class="text-center">
                  <th style="width:40px;">Id</th>
                  <th>Código de barras</th>
                  <th>Nombre</th>
                  <th>Formato</th>
                  <th>Tamaño</th>
                  <th>Marca</th>
                  <th>Cantidad</th>
                  <th>Fecha de recepción</th>
                  <th>Fecha de vencimiento</th>
                  <th>Proveedor</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $productos = ControladorProductos::ctrMostrarProductos(null, null);
                  foreach ($productos as $p) {
                    // Formateo de fechas a dd/mm/YYYY para coherencia con el parser del DataTable
                    $frec = !empty($p["fecha_recepcion"])   ? date("d/m/Y", strtotime($p["fecha_recepcion"]))   : "";
                    $fven = !empty($p["fecha_vencimiento"]) ? date("d/m/Y", strtotime($p["fecha_vencimiento"])) : "";

                    echo '<tr>
                            <td class="text-center">#'.$p["id"].'</td>
                            <td class="text-center">'.$p["codigo"].'</td>
                            <td>'.$p["nombre"].'</td>
                            <td class="text-center">'.$p["formato"].'</td>
                            <td class="text-center">'.$p["tamano"].'</td>
                            <td class="text-center">'.$p["marca"].'</td>
                            <td class="text-center">'.$p["cantidad"].'</td>
                            <td class="text-center">'.$frec.'</td>
                            <td class="text-center">'.$fven.'</td>
                            <td class="text-center">'.$p["proveedor"].'</td>
                            <td class="text-center">
                              <button class="btn btn-sm btn-primary btnAgregarStock"
                                      idProducto="'.$p["id"].'"
                                      data-toggle="modal"
                                      data-target="#modalAgregarStock">
                                <i class="fas fa-plus-square mr-1"></i> Añadir
                              </button>
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

<!--------------------- MODAL: AÑADIR STOCK ------------------------>
<div class="modal fade" id="modalAgregarStock" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form method="post" class="modal-content shadow-lg border-0 rounded-xl overflow-hidden">

      <!-- Header -->
      <div class="modal-header modal-header-custom text-white">
        <div class="d-flex align-items-center">
          <div class="modal-icon mr-2"><i class="fas fa-plus-circle"></i></div>
          <h5 class="modal-title mb-0">Añadir Stock</h5>
        </div>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Body -->
      <div class="modal-body p-4">
        <input type="hidden" name="idProductoStock" id="idProductoStock">

        <!-- Cantidad recibida -->
        <div class="form-group">
          <label class="font-weight-semibold">Cantidad recibida</label>
          <div class="input-group input-group-lg">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-boxes"></i></span>
            </div>
            <input type="number" class="form-control" name="nuevaCantidadStock" min="1" placeholder="Ej: 10" required>
          </div>
        </div>

        <!-- Fecha de recepción -->
        <div class="form-group">
          <label class="font-weight-semibold">Fecha de recepción</label>
          <div class="input-group input-group-lg">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="far fa-calendar-check"></i></span>
            </div>
            <input type="date" class="form-control" name="nuevaFechaRecepcion" required>
          </div>
        </div>

        <!-- Fecha de vencimiento -->
        <div class="form-group">
          <label class="font-weight-semibold">Fecha de vencimiento</label>
          <div class="input-group input-group-lg">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="far fa-calendar-times"></i></span>
            </div>
            <input type="date" class="form-control" name="nuevaFechaVencimiento">
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer border-0 pt-0 px-4 pb-3 d-flex justify-content-between">
        <button type="button" class="btn btn-cancelar" data-dismiss="modal">
          <i class="fas fa-times mr-1"></i> Cancelar
        </button>
        <button type="submit" class="btn btn-guardar-stock">
          <i class="fas fa-save mr-1"></i> Guardar
        </button>
      </div>

      <?php
        $agregarStock = new ControladorProductos();
        $agregarStock -> ctrAgregarStock();
      ?>
    </form>
  </div>
</div>

<!-- JS -->
<script src="vistas/js/productos.js"></script>
<script src="vistas/js/anadir-stock.js"></script>
<!-- CSS -->
<link rel="stylesheet" href="vistas/css/anadir-stock.css">