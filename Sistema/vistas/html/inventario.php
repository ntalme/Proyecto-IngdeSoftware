<!-- Contenedor principal -->
<div class="content-wrapper">

  <!-- Encabezado de la página -->
  <section class="content-header">
    <div class="container-fluid">

       <!-- TITULO PAGINA: INVENTARIO-->
      <div class="row align-items-center mb-2">
        <div class="col-sm-6">
          <h1>Inventario</h1>
        </div>

        <!-- BOTON: CONFIGURAR STOCK MINIMO -->
        <div class="col-sm-6 text-right">

          <!-- Ver si el usuario es administrador -->
          <?php if ($_SESSION["rol"] === "Administrador"): ?>
            <button type="button" id="btnConfigStockMinimo" 
                    class="btn btn-stockmin"
                    data-toggle="modal" data-target="#modalConfigStockMinimo">
              <i class="fas fa-bell me-1"></i> Configurar stock mínimo
            </button>

          <!-- Si no es administardor -->
          <?php else: ?>
            <button class="btn btn-stockmin" onclick="sinPermiso()" type="button">
              <i class="fas fa-bell me-1"></i> Configurar stock mínimo
            </button>
            <script>
              function sinPermiso(){
                Swal.fire({
                  icon: 'error',
                  title: 'Acceso denegado',
                  text: 'Solo los administradores pueden configurar el stock mínimo.',
                  confirmButtonText: 'Entendido'
                });
              }
            </script>
          <?php endif; ?>
        </div>
      </div>

      <!-- DESCRIPCION DE LA PAGINA -->
      <div class="row">
        <div class="col-12">
          <p class="descripcion-pagina">
            En esta sección podrá consultar y gestionar el inventario de productos, 
            verificando sus cantidades disponibles, precios y estado en stock.
          </p>
        </div>
      </div>
      <hr class="linea-divisora">
    </div>
  </section>

<!--------------------- CONTENIDO PRINCIPAL PAGINA ------------------------>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">

          <!-- Card de la tabla de inventario -->
          <div class="card">
            <div class="card-body">

              <!-- Barra de controles -->
              <div class="toolbar-inventario mb-3">

                <!-- ORDENAR POR -->
                <div class="d-flex flex-wrap align-items-center justify-content-start mb-2" style="gap: 1.5rem;">
                  <div class="ctrl">
                    <label for="criterioOrden" class="form-label fw-bold me-2">Ordenar por</label>
                    <select id="criterioOrden" class="form-select form-select-sm w-auto">
                      <option value="2">Código de barras</option>
                      <option value="3" selected>Nombre</option>
                      <option value="4">Formato</option>
                      <option value="5">Tamaño</option>
                      <option value="6">Marca</option>
                      <option value="7">Cantidad</option>
                      <option value="8">Precio compra</option>
                      <option value="9">Precio venta</option>
                      <option value="10">Proveedor</option>
                      <option value="11">Fecha ingreso</option>
                      <option value="12">Fecha vencimiento</option>
                      <option value="13">Stock mínimo</option>
                    </select>
                  </div>

                  <!-- DIRECCION -->
                  <div class="ctrl">
                    <label for="direccionOrden" class="form-label fw-bold me-2">Dirección</label>
                    <select id="direccionOrden" class="form-select form-select-sm w-auto">
                      <option value="asc" selected>Ascendente</option>
                      <option value="desc">Descendente</option>
                    </select>
                  </div>

                  <!-- FILTRAR POR ESTADO -->
                  <div class="ctrl">
                    <label for="filtroEstado" class="form-label fw-bold me-2">Estado</label>
                    <select id="filtroEstado" class="form-select form-select-sm w-auto">
                      <option value="todos" selected>Todos</option>
                      <option value="activo">Activos</option>
                      <option value="inactivo">Inactivos</option>
                      <option value="sinstock">Sin stock</option>
                    </select>
                  </div>

                  <!-- BUSCADOR -->
                  <div class="col ms-auto d-flex justify-content-end">
                    <div class="input-group input-group-sm" id="buscadorInventarioWrap" style="max-width:300px;">
                      <input type="text" id="buscarInventario" class="form-control" placeholder="Buscar...">
                      <button class="input-group-text bg-white" id="btnBuscarInventario" role="button" aria-label="Buscar">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <!-- VER RECIENTES -->
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                  <div class="ctrl d-flex align-items-center" style="gap: 12px;">
                    <label for="diasRecientes" class="form-label fw-bold mb-0">Recientes</label>
                    <select id="diasRecientes" class="form-select form-select-sm w-auto">
                      <option value="0">Hoy</option>
                      <option value="7" selected>7 días</option>
                      <option value="15">15 días</option>
                      <option value="30">30 días</option>
                    </select>
                    <div class="btn-group" role="group">
                      <button type="button" id="btnVerRecientes" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-clock me-1"></i> Ver recientes
                      </button>
                      <button type="button" id="btnVerTodos" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-list me-1"></i> Ver todos
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- TABLA DE DATOS -->
              <div class="table-responsive">
                <table id="tablaInventario" class="tabla-inventario">
                  <thead> 
                    <tr class="text-center">
                      <th>Imagen</th>
                      <th>Código de barras</th>
                      <th>Nombre</th>
                      <th>Formato</th>
                      <th>Tamaño</th>
                      <th>Marca</th>
                      <th>Cantidad</th>
                      <th>Precio compra</th>
                      <th>Precio venta</th>
                      <th>Proveedor</th>
                      <th>Fecha ingreso</th>
                      <th>Fecha vencimiento</th> <!-- NUEVA -->
                      <th>Stock Minimo</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $productos = ControladorProductos::ctrMostrarProductos(null, null);

                      foreach ($productos as $key => $value) {

                        // Fecha INGRESO (para ordenar y mostrar)
                        $fechaISO = '';
                        $fechaCL  = '';
                        if (!empty($value["fecha_ingreso"])) {
                          $ts = strtotime($value["fecha_ingreso"]);
                          $fechaISO = date('Y-m-d', $ts);
                          $fechaCL  = date('d-m-Y', $ts);
                        }

                        // Fecha VENCIMIENTO (acepta varios nombres)
                        $rawVence  = $value["fecha_vencimiento"] ?? $value["vencimiento"] ?? $value["fecha_caducidad"] ?? '';
                        $venceISO  = '';
                        $venceCL   = '';
                        $badgeVenc = '';
                        if (!empty($rawVence)) {
                          $tv = strtotime($rawVence);
                          if ($tv) {
                            $venceISO = date('Y-m-d', $tv);
                            $venceCL  = date('d-m-Y', $tv);

                            // Etiquetas: Vencido / Pronto a vencer (<=30 días)
                            $dias = floor(($tv - strtotime('today')) / 86400);
                            if ($dias < 0) {
                              $badgeVenc = '<span class="badge bg-danger ms-1">Vencido</span>';
                            } elseif ($dias <= 30) {
                              $badgeVenc = '<span class="badge bg-warning text-dark ms-1">Pronto a vencer</span>';
                            }
                          }
                        }

                        // Imagen
                        $src = (!empty($value["imagen"]) && file_exists($value["imagen"])) 
                                  ? $value["imagen"] 
                                  : "vistas/imagenes/sinfoto.png";

                        // Fila
                        echo '<tr class="'.($value["estado"] == 0 ? 'table-secondary text-muted' : '').'">
                          <td class="text-center">
                            <img src="'.$src.'" 
                                class="img-thumbnail" 
                                style="width:100px; height:100px; object-fit:cover;" 
                                alt="Foto producto"
                                onerror="this.onerror=null;this.src=\'vistas/imagenes/sinfoto.png\';">
                          </td>
                          <td>'.$value["codigo"].'</td>
                          <td>'.$value["nombre"].'</td>
                          <td>'.$value["formato"].'</td>
                          <td>'.$value["tamano"].'</td>
                          <td>'.$value["marca"].'</td>
                          <td>'.$value["cantidad"].'</td>
                          <td>'.$value["precio_compra"].'</td>
                          <td>'.$value["precio_venta"].'</td>
                          <td>'.$value["proveedor"].'</td>
                          <td '.($fechaISO ? 'data-order="'.$fechaISO.'"' : '').'>'.($fechaCL ?: '-').'</td>
                          <td '.($venceISO ? 'data-order="'.$venceISO.'"' : '').'>'
                            . ($venceCL ?: '-')
                            . ($badgeVenc ? '<br>'.$badgeVenc : '')
                          . '</td>
                          <td>'.$value["stock_minimo"].'</td>
                          <td class="text-center">';

                        // Acción según rol
                        if ($_SESSION["rol"] === "Administrador") {
                          if($value["estado"] == 1){
                            echo '<button class="btn btn-danger btn-sm btnCambiarEstado" 
                                          idProducto="'.$value["id"].'" 
                                          nuevoEstado="0"
                                          title="Inactivar producto">
                                    <i class="fas fa-ban"></i> Inactivar
                                  </button>';
                          } else {
                            echo '<button class="btn btn-success btn-sm btnCambiarEstado" 
                                          idProducto="'.$value["id"].'" 
                                          nuevoEstado="1"
                                          title="Activar producto">
                                    <i class="fas fa-check"></i> Activar
                                  </button>';
                          }
                        } else {
                          if($value["estado"] == 1){
                            echo '<button class="btn btn-danger btn-sm" 
                                          onclick="sinPermisoInventario(); return false;">
                                    <i class="fas fa-ban"></i> Inactivar
                                  </button>';
                          } else {
                            echo '<button class="btn btn-success btn-sm" 
                                          onclick="sinPermisoInventario(); return false;">
                                    <i class="fas fa-check"></i> Activar
                                  </button>';
                          }
                        }

                        echo '</td></tr>';
                      }
                    ?>
                    <?php if ($_SESSION["rol"] !== "Administrador"): ?>
                    <script>
                      function sinPermisoInventario(){
                        if (typeof Swal !== 'undefined') {
                          Swal.fire({
                            icon: 'error',
                            title: 'Acceso denegado',
                            text: 'Solo los administradores pueden activar o inactivar productos.',
                            confirmButtonText: 'Entendido'
                          });
                        } else {
                          alert('Acceso denegado. Solo los administradores pueden activar o inactivar productos.');
                        }
                      }
                    </script>
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

<!--------------------- MODAL: CONFIGURAR STOCK MINIMO ------------------------>
<div class="modal fade" id="modalConfigStockMinimo" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <form method="post" class="modal-content shadow-lg border-0 rounded-xl overflow-hidden">

      <!-- Header -->
      <div class="modal-header modal-header-custom text-white">
        <div class="d-flex align-items-center">
          <div class="modal-icon mr-2"><i class="fas fa-bell"></i></div>
          <h5 class="modal-title mb-0">Configurar stock mínimo</h5>
        </div>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Body -->
      <div class="modal-body p-4">

        <!-- Fila de controles -->
        <div class="d-flex align-items-center justify-content-between flex-wrap mb-3">
          <div class="d-flex align-items-center mb-2">
            <div class="input-group input-group-sm" style="max-width: 360px;">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
              </div>
              <input type="text" class="form-control" id="buscarProductoTabla" placeholder="Buscar por código, nombre, marca, formato o tamaño…">
            </div>
            <small class="text-muted ml-3 d-none d-md-inline">Use la casilla para seleccionar varios productos.</small>
          </div>

          <!-- Aplicación masiva -->
          <div class="d-flex align-items-center mb-2">
            <div class="input-group input-group-sm mr-2" style="width: 180px;">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-sort-numeric-down"></i></span>
              </div>
              <input type="number" min="0" id="minMasivo" class="form-control" placeholder="Mín. masivo">
            </div>
            <button type="button" class="btn btn-aplicar btn-sm" id="btnAplicarMasivo" disabled>Aplicar a seleccionados</button>
            <span class="ml-3 text-muted small">Seleccionados: <strong id="countSel">0</strong></span>
          </div>
        </div>

        <!-- Tabla scrollable -->
        <div class="tabla-wrap">
          <table class="table table-hover table-sm mb-0" id="tablaProductosMin">
            <thead>
              <tr>
                <th style="width:42px;" class="text-center">
                  <input type="checkbox" id="checkAll" aria-label="Seleccionar todo">
                </th>
                <th style="min-width:110px;">Código</th>
                <th>Nombre</th>
                <th style="min-width:120px;">Formato</th>
                <th style="min-width:120px;">Tamaño</th>
                <th style="min-width:140px;">Marca</th>
                <th style="min-width:120px;" class="text-right pr-3">Mín.</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $productosMin = ControladorProductos::ctrMostrarProductos(null, null);
                if (!empty($productosMin)) {
                  foreach ($productosMin as $p) {
                    $id      = (int)($p["id"] ?? 0);
                    $codigo  = htmlspecialchars($p["codigo"]  ?? "", ENT_QUOTES, 'UTF-8');
                    $nombre  = htmlspecialchars($p["nombre"]  ?? "", ENT_QUOTES, 'UTF-8');
                    $formato = htmlspecialchars($p["formato"] ?? "-", ENT_QUOTES, 'UTF-8');
                    $tamano  = htmlspecialchars($p["tamano"]  ?? "-", ENT_QUOTES, 'UTF-8');
                    $marca   = htmlspecialchars($p["marca"]   ?? "-", ENT_QUOTES, 'UTF-8');
                    $minAct  = (int)($p["stock_minimo"] ?? 0);

                    echo '<tr class="fila-prod" '.
                           'data-id="'.$id.'" '.
                           'data-codigo="'.$codigo.'" '.
                           'data-nombre="'.$nombre.'" '.
                           'data-formato="'.$formato.'" '.
                           'data-tamano="'.$tamano.'" '.
                           'data-marca="'.$marca.'" '.
                           'data-actual="'.$minAct.'">'.
                            '<td class="align-middle text-center">
                               <input type="checkbox" class="chk" name="producto_id[]" value="'.$id.'" aria-label="Seleccionar producto">
                             </td>'.
                            '<td class="align-middle font-mono">'.$codigo.'</td>'.
                            '<td class="align-middle nombre-celda">'.$nombre.'</td>'.
                            '<td class="align-middle">'.$formato.'</td>'.
                            '<td class="align-middle">'.$tamano.'</td>'.
                            '<td class="align-middle">'.$marca.'</td>'.
                            '<td class="align-middle text-right pr-3">
                               <input type="number" min="0" class="form-control form-control-sm min-input" name="minimos['.$id.']" placeholder="—" value="'.$minAct.'" disabled>
                             </td>'.
                         '</tr>';
                  }
                } else {
                  echo '<tr><td colspan="7" class="text-center text-muted py-4">No hay productos para mostrar.</td></tr>';
                }
              ?>
            </tbody>
          </table>
        </div>

        <!-- Aviso general -->
        <div class="small text-muted mt-2">
          Tip: Puede escribir mínimos por fila o usar “Mín. masivo” y aplicar a los seleccionados.
        </div>

        <!-- flag -->
        <input type="hidden" name="configurarStockMinimo" value="1">
      </div>

      <!-- Footer con mensaje guía -->
      <div class="modal-footer border-0 pt-0 px-4 pb-3 d-flex flex-column flex-sm-row justify-content-between align-items-center">
        <small id="msgAplicar" class="text-muted mb-2 mb-sm-0">
          ⚠️ Debe presionar <b>Aplicar a seleccionados</b> para habilitar <b>Guardar</b>.
        </small>
        <div>
          <button type="button" class="btn btn-cancelar" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i> Cancelar
          </button>
          <button type="submit" class="btn btn-guardar" id="btnGuardarMinimo" disabled>
            <i class="fas fa-save mr-1"></i> Guardar
          </button>
        </div>
      </div>

      <?php
        $ctrl = new ControladorProductos();
        $ctrl->ctrConfigurarStockMinimo(); 
      ?>
    </form>
  </div>
</div>

<!-- JS -->
<script src="vistas/js/inventario.js"></script>
<!-- CSS -->
<link rel="stylesheet" href="vistas/css/inventario.css">
