<?php
require_once "controladores/promociones.controlador.php";
require_once "modelos/promociones.modelo.php";

ControladorPromociones::ctrCrearPromocion();
ControladorPromociones::ctrBorrarPromocion();

$MAPA_PROMOS = ControladorPromociones::ctrMapaPromosActivasPorProducto();
?>
<script>
  window.MAPA_PROMOS = <?php echo json_encode($MAPA_PROMOS, JSON_UNESCAPED_UNICODE); ?>;
</script>

<body class="hold-transition sidebar-mini">

<!--------------------- CONTENIDO PRINCIPAL PAGINA ------------------------>
<div class="wrapper">

  <!-- Barra superior de navegación -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <!-- Izquierda: botón para mostrar/ocultar el menú lateral -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button" aria-label="Alternar menú">
          <i class="fas fa-bars"></i>
        </a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <!--------------------- NOTIFICACIONES ------------------------>
      <?php
        $notis = ControladorProductos::ctrProductosStockCritico();
        $totalNotis = is_array($notis) ? count($notis) : 0;
        $esAdmin = isset($_SESSION["rol"]) && $_SESSION["rol"] === "Administrador";
      ?>
      <li class="nav-item dropdown">
        <a class="nav-link" href="#" id="notifDropdown" role="button"
          data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
          aria-label="Notificaciones de stock">
          <i class="fas fa-bell"></i>
          <?php if ($totalNotis > 0): ?>
            <span class="badge badge-danger navbar-badge"><?= (int)$totalNotis ?></span>
          <?php endif; ?>
        </a>

        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg p-0 dropdown-menu-notif"
            aria-labelledby="notifDropdown">

          <div class="notif-header">
            <span>Notificaciones de stock</span>
            <span class="text-muted">(<?= (int)$totalNotis ?>)</span>
          </div>

          <div class="list-group list-group-flush" style="max-height:360px; overflow:auto;">
            <?php if ($totalNotis === 0): ?>
              <div class="px-3 py-3 text-muted">Sin alertas por ahora.</div>
            <?php else: ?>
              <?php foreach ($notis as $p):
                $agotado     = ((int)$p['cantidad'] === 0);
                $estadoClase = $agotado ? 'agotado' : 'critico';
                $estadoTexto = $agotado ? 'Agotado' : 'Crítico';
                $estadoIcon  = $agotado ? 'fas fa-times-circle' : 'fas fa-exclamation-triangle';
                $criticoTxt  = $agotado ? 'agotado' : 'bajo stock mínimo';
                $hrefAgregar = 'index.php?ruta=anadir-stock&id='.(int)$p['id'];
              ?>
                <?php if ($esAdmin): ?>
                  <!-- SI ES ADMIN LLEVA A AÑADIR STOCK -->
                  <a href="<?= $hrefAgregar ?>" class="list-group-item list-group-item-action">
                <?php else: ?>
                  <!-- SI NO ES ADMIN -->
                  <a href="#" class="list-group-item list-group-item-action" onclick="sinPermisoStock(); return false;">
                <?php endif; ?>

                    <h6 class="mb-1">
                      <?= htmlspecialchars($p['nombre']) ?>
                      <small class="text-muted">[<?= htmlspecialchars($p['codigo']) ?>]</small>
                      <small class="estado <?= $estadoClase ?>">
                        <i class="<?= $estadoIcon ?> mr-1"></i><?= $estadoTexto ?>
                      </small>
                    </h6>

                    <p class="mb-1">El siguiente producto está <?= $criticoTxt ?>.</p>

                    <div class="stock-info">
                      Cant.: <strong><?= (int)$p['cantidad'] ?></strong>
                    </div>
                  </a>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
          <div class="notif-footer">
            <a href="index.php?ruta=stock-critico" class="btn btn-sm btn-outline-secondary btn-block">
              Ver página de stock crítico
            </a>
          </div>
        </div>
      </li>

      <!-- SI NO ES ADMIN -->
      <?php if (!$esAdmin): ?>
      <script>
        function sinPermisoStock(){
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              icon: 'error',
              title: 'Acceso denegado',
              text: 'Solo los administradores pueden añadir stock.',
              confirmButtonText: 'Entendido'
            });
          } else {
            alert('Acceso denegado. Solo los administradores pueden añadir stock.');
          }
        }
      </script>
      <?php endif; ?>


      <!--------------------- BOTON: REGISTRAR PERDIDA O CONSUMO INTERNO ------------------------>
      <li class="nav-item">
        <a href="#" class="nav-link"
           data-toggle="modal" data-target="#modalNavbarPerdida"
           aria-label="Registrar pérdida o consumo interno" style="padding:8px;">
          <i class="fas fa-trash-restore-alt"
             data-toggle="tooltip" data-placement="bottom"
             title="Registrar pérdida o consumo interno"></i>
        </a>
      </li>

      <!--------------------- BOTON: PROMOCIONES ------------------------>
      <?php if ($_SESSION["rol"] === "Administrador"): ?>
        <li class="nav-item">
          <a href="#" class="nav-link"
            data-toggle="modal" data-target="#modalPromociones"
            aria-label="Crear promociones" style="padding:8px;">
            <i class="fas fa-tags"
              data-toggle="tooltip" data-placement="bottom"
              title="Crear promociones"></i>
          </a>
        </li>
      <?php else: ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="sinPermisoPromos(); return false;" style="padding:8px;">
            <i class="fas fa-tags"
              data-toggle="tooltip" data-placement="bottom"
              title="Acceso restringido"></i>
          </a>
        </li>
      <?php endif; ?>

      <?php if ($_SESSION["rol"] !== "Administrador"): ?>
      <script>
        function sinPermisoPromos(){
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              icon: 'error',
              title: 'Acceso denegado',
              text: 'Solo los administradores pueden crear promociones.',
              confirmButtonText: 'Entendido'
            });
          } else {
            alert('Acceso denegado. Solo los administradores pueden crear promociones.');
          }
        }
      </script>
      <?php endif; ?>

      <!--------------------- MENU USUARIO ------------------------>
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle"
           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
           aria-label="Menú de usuario">
          <i class="fas fa-user-circle mr-1"></i>
          <span class="d-none d-md-inline">
            <?= htmlspecialchars($_SESSION['nombre'] ?? 'Usuario') ?>
          </span>
        </a>
        <div class="dropdown-menu dropdown-menu-right shadow rounded">
          <a href="cerrar-sesion" class="dropdown-item text-danger">
            <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
          </a>
        </div>
      </li>
    </ul>
  </nav>
</div>

<!--------------------- MODAL: REGISTRAR PERDIDA Y CONSUMO INTERNO ------------------------>
<div class="modal fade" id="modalNavbarPerdida" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <form id="formNavbarPerdida" class="modal-content shadow-lg border-0 rounded-xl overflow-hidden">

      <!-- Header -->
      <div class="modal-header modal-header-custom text-white">
        <div class="d-flex align-items-center">
          <div class="modal-icon mr-2"><i class="fas fa-minus-circle text-white"></i></div>
          <h5 class="modal-title mb-0">Registrar pérdida / consumo interno</h5>
        </div>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Body -->
      <div class="modal-body p-4">

        <!-- Selector como tabla (click en fila) -->
        <div class="mb-3">
          <label class="font-weight-semibold d-block">Producto</label>

          <div class="d-flex align-items-center justify-content-between flex-wrap mb-2">
            <div class="input-group input-group-sm" style="max-width:340px;">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
              </div>
              <input type="text" class="form-control" id="buscarProductoNavbar" placeholder="Buscar por código, nombre, marca, formato o tamaño…">
            </div>
            <small class="text-muted mt-2 mt-sm-0">Haga clic en una fila para seleccionar.</small>
          </div>

          <div class="tabla-wrap-navbar">
            <table class="table table-hover table-sm mb-0" id="tablaProductosNavbar">
              <thead>
                <tr>
                  <th style="min-width:110px;">Código</th>
                  <th>Nombre</th>
                  <th style="min-width:120px;">Formato</th>
                  <th style="min-width:120px;">Tamaño</th>
                  <th style="min-width:140px;">Marca</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $productosNavbar = ControladorProductos::ctrMostrarProductos(null, null);
                  if (!empty($productosNavbar)) {
                    foreach ($productosNavbar as $p) {
                      $id      = (int)($p["id"] ?? 0);
                      $codigo  = htmlspecialchars($p["codigo"]  ?? "", ENT_QUOTES, 'UTF-8');
                      $nombre  = htmlspecialchars($p["nombre"]  ?? "", ENT_QUOTES, 'UTF-8');
                      $formato = htmlspecialchars($p["formato"] ?? "-", ENT_QUOTES, 'UTF-8');
                      $tamano  = htmlspecialchars($p["tamano"]  ?? "-", ENT_QUOTES, 'UTF-8');
                      $marca   = htmlspecialchars($p["marca"]   ?? "-", ENT_QUOTES, 'UTF-8');
                      $stock   = (int)($p["cantidad"] ?? 0);

                      echo '<tr class="fila-prod-navbar" '.
                              'data-id="'.$id.'" '.
                              'data-codigo="'.$codigo.'" '.
                              'data-nombre="'.$nombre.'" '.
                              'data-formato="'.$formato.'" '.
                              'data-tamano="'.$tamano.'" '.
                              'data-marca="'.$marca.'" '.
                              'data-stock="'.$stock.'">'.
                              '<td class="align-middle font-mono">'.$codigo.'</td>'.
                              '<td class="align-middle nombre-celda">'.$nombre.'</td>'.
                              '<td class="align-middle">'.$formato.'</td>'.
                              '<td class="align-middle">'.$tamano.'</td>'.
                              '<td class="align-middle">'.$marca.'</td>'.
                           '</tr>';
                    }
                  } else {
                    echo '<tr><td colspan="5" class="text-center text-muted py-4">No hay productos para mostrar.</td></tr>';
                  }
                ?>
              </tbody>
            </table>
          </div>

          <!-- Badge de stock -->
          <small id="ayudaStock" class="form-text mt-2" style="display:none;">
            <span id="badgeStock" class="badge badge-soft-info">Stock: 0</span>
          </small>

          <!-- input oculto que viaja al backend (required) -->
          <input type="hidden" name="producto_id" id="productoNavbarId" required>
        </div>

        <div class="row">
          <!-- Cantidad -->
          <div class="col-md-6 mb-3">
            <label class="font-weight-semibold">Cantidad</label>
            <div class="input-group input-group-lg">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-boxes"></i></span>
              </div>
              <input type="number" class="form-control" name="cantidad" id="cantidadInput" min="1" placeholder="Ej: 1" required>
              <div class="input-group-append">
                <span class="input-group-text">unid.</span>
              </div>
            </div>
            <div id="errorCantidad" class="invalid-feedback d-block" style="display:none;">
              La cantidad no puede superar el stock disponible.
            </div>
          </div>

          <!-- Motivo -->
          <div class="col-md-6 mb-3">
            <label class="font-weight-semibold">Motivo</label>
            <div class="input-group input-group-lg">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-clipboard-list"></i></span>
              </div>
              <select class="custom-select custom-select-lg" name="motivo" id="motivoSelect" required>
                <option value="rotura">Rotura</option>
                <option value="vencimiento">Vencimiento</option>
                <option value="perdida" selected>Pérdida</option>
                <option value="merma">Merma</option>
                <option value="consumo_interno">Consumo interno</option>
                <option value="otros">Otros</option>
              </select>
            </div>
          </div>

          <!-- Observación -->
          <div class="col-md-12 mb-0">
            <label class="font-weight-semibold">Observación (opcional)</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="far fa-comment-dots"></i></span>
              </div>
              <textarea class="form-control" name="observacion" rows="2" maxlength="255" placeholder="Ej: Botella quebrada en recepción"></textarea>
            </div>
            <small class="form-text text-muted">Máx. 255 caracteres</small>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer border-0 pt-0 px-4 pb-3 d-flex justify-content-between">
        <button type="button" class="btn btn-light border" data-dismiss="modal">
          <i class="fas fa-times mr-1"></i> Cancelar
        </button>
        <button type="submit" class="btn btn-primary btn-modern" id="btnRegistrarPerdida" disabled>
          <i class="fas fa-save mr-1"></i> Registrar
        </button>
      </div>
    </form>
  </div>
</div>

<!--------------------- MODAL: PROGRAMAR PROMOCION ------------------------>
<div class="modal fade" id="modalPromociones" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-promos">
    <form id="formPromo" class="modal-content promo2-modal" method="POST">

      <!-- Header -->
      <div class="modal-header promo2-header text-white">
        <div class="d-flex align-items-center">
          <span class="promo2-header-icon mr-2"><i class="fas fa-percent"></i></span>
          <h5 class="modal-title mb-0">Programar promoción</h5>
        </div>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Body -->
      <div class="modal-body promo2-body">

        <div class="form-row">
          <!-- Tipo -->
          <div class="form-group col-md-4">
            <label class="promo2-label">Tipo</label>
            <div class="input-group">
              <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-tags"></i></span></div>
              <select id="promo_tipo" name="tipo" class="form-control" required>
                <option value="" selected disabled>Seleccione…</option>
                <option value="descuento">% Descuento</option>
                <option value="2x1">2x1</option>
                <option value="precio_fijo">Precio fijo</option>
              </select>
            </div>
            <small class="promo2-hint">Elija el tipo de beneficio.</small>
          </div>

          <!-- Parámetro -->
          <div class="form-group col-md-4">
            <label class="promo2-label">Parámetro</label>
            <div class="input-group">
              <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-sliders-h"></i></span></div>
              <input id="promo_param" name="parametro" type="number" step="0.01" class="form-control" placeholder="% o precio">
            </div>
            <small class="promo2-hint" id="promo_param_help">Ej: 10 (para 10%) o 1990 (precio fijo). En 2x1 no se requiere.</small>
          </div>

          <!-- Fecha inicio -->
          <div class="form-group col-md-4">
            <label class="promo2-label">Fecha inicio</label>
            <div class="input-group">
              <div class="input-group-prepend"><span class="input-group-text"><i class="far fa-calendar-alt"></i></span></div>
              <input id="promo_inicio" name="fecha_inicio" type="datetime-local" class="form-control" required>
            </div>
          </div>
        </div>

        <!-- Fila: Fecha fin + buscador a la derecha -->
        <div class="form-row align-items-end row-compact">
          <!-- Fecha fin -->
          <div class="form-group col-md-6">
            <label class="promo2-label mb-1">Fecha fin</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="far fa-calendar-check"></i></span>
              </div>
              <input id="promo_fin" name="fecha_fin" type="datetime-local" class="form-control" required>
            </div>
          </div>

          <!-- Buscador a la derecha -->
          <div class="form-group col-md-6 d-flex">
            <div class="input-group input-group-sm buscador-prod ml-auto">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
              </div>
              <input type="text" id="promo_buscarProd" class="form-control"
                     placeholder="Buscar por código, nombre, marca, formato">
            </div>
          </div>
        </div>

        <!-- Tabla de productos -->
        <div class="promoTable-wrap">
          <table class="table table-sm table-hover mb-0" id="promo_tablaProd">
            <thead>
              <tr>
                <th>CÓDIGO</th>
                <th>NOMBRE</th>
                <th>FORMATO</th>
                <th>TAMAÑO</th>
                <th>MARCA</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $prods = ControladorProductos::ctrMostrarProductosActivos();
                if (!empty($prods)) {
                  foreach ($prods as $p) {
                    $id      = (int)$p['id'];
                    $codigo  = htmlspecialchars($p['codigo'] ?? '', ENT_QUOTES,'UTF-8');
                    $nombre  = htmlspecialchars($p['nombre'] ?? '', ENT_QUOTES,'UTF-8');
                    $formato = htmlspecialchars($p['formato'] ?? '-', ENT_QUOTES,'UTF-8');
                    $tamano  = htmlspecialchars($p['tamano']  ?? '-', ENT_QUOTES,'UTF-8');
                    $marca   = htmlspecialchars($p['marca']   ?? '-', ENT_QUOTES,'UTF-8');
                    echo '<tr class="promo_row"
                              data-id="'.$id.'"
                              data-buscar="'.strtolower("$codigo $nombre $formato $tamano $marca").'">
                            <td class="font-mono">'.$codigo.'</td>
                            <td>'.$nombre.'</td>
                            <td>'.$formato.'</td>
                            <td>'.$tamano.'</td>
                            <td>'.$marca.'</td>
                          </tr>';
                  }
                } else {
                  echo '<tr><td colspan="5" class="text-center text-muted py-4">No hay productos activos.</td></tr>';
                }
              ?>
            </tbody>
          </table>
        </div>

        <!-- Franja de ayuda + contador bajo la tabla -->
        <div class="promo-helpers d-flex justify-content-between align-items-center small text-muted">
          <span>Haga clic en una fila para seleccionar. Vuelva a hacer clic para deseleccionar.</span>
          <span>Seleccionados: <strong id="promo_countSel">0</strong></span>
        </div>

        <!-- Inputs ocultos de productos seleccionados -->
        <div id="promo_hidden_inputs"></div>

        <!-- Observación -->
        <div class="form-group col-12 mt-3">
          <label class="promo2-label">Observación (opcional)</label>
          <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text"><i class="far fa-comment-dots"></i></span></div>
            <textarea id="promo_obs" name="observacion" class="form-control" rows="2" placeholder="Notas internas de la promoción…"></textarea>
          </div>
          <small class="promo2-hint">Máx. 255 caracteres</small>
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer promo2-footer">
        <input type="hidden" name="crearPromocion" value="1">
        <button type="button" class="btn btn-light border" data-dismiss="modal">
          <i class="fas fa-times mr-1"></i> Cancelar
        </button>
        <button type="submit" class="btn promo2-primary">
          <i class="fas fa-save mr-1"></i> Guardar promoción
        </button>
      </div>

    </form>
  </div>
</div>

<!-- JS -->
<script src="vistas/js/navbar.js"></script>
<script src="vistas/js/registrospc.js"></script>
<!-- CSS -->
<link rel="stylesheet" href="vistas/css/navbar.css">

