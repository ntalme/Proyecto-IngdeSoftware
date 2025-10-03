<?php
require_once "controladores/registropc.controlador.php";
require_once "modelos/registropc.modelo.php";

$items = ControladorRegistropc::ctrMostrarRegistros(null, null);
?>

<!-- Contenedor general -->
<div class="content-wrapper">

  <!-- Título de la vista  -->
  <section class="content-header">
    <div class="container-fluid">

      <!-- TITULO PAGINA: PERDIDAS Y CONSUMOS -->
      <div class="row align-items-start mb-2">
        <div class="col-sm-8">
          <h1>Perdidas y Consumos Internos</h1>
        </div>
      </div>

      <!-- DESCRIPCION DE LA PAGINA -->
      <div class="row">
        <div class="col-12">
          <p class="descripcion-pagina">
             En esta sección podrá ver las pérdidas de productos 
            y los consumos internos registrados.
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

          <div class="card">
            <div class="card-body">

             <!-- Controles de orden + buscador a la derecha -->
              <div class="row g-2 align-items-end mb-3 filtros-perdidas w-100">

                <!-- ORDENAR POR -->
                <div class="col-auto">
                  <label class="form-label mb-1 me-2">Ordenar por</label>
                  <select id="criterioOrden" class="form-select form-select-sm w-auto select-bonito">
                    <option value="2">Código</option>
                    <option value="3" selected>Producto</option>
                    <option value="5">Motivo</option>
                    <option value="4">Cantidad</option>
                    <option value="1">Fecha</option>
                    <option value="7">Usuario</option>
                  </select>
                </div>

                <!-- DIRECCION -->
                <div class="col-auto">
                  <label class="form-label mb-1 me-2">Dirección</label>
                  <select id="direccionOrden" class="form-select form-select-sm w-auto select-bonito">
                    <option value="asc">Ascendente</option>
                    <option value="desc" selected>Descendente</option>
                  </select>
                </div>

                <!-- BUSCADOR -->
                <div class="col ms-auto d-flex justify-content-end">
                  <div class="input-group input-group-sm" id="buscadorPerdidasWrap" style="max-width:300px;">
                    <input type="text" id="buscarPerdidas" class="form-control" placeholder="Buscar...">
                    <span class="input-group-text bg-white" id="btnBuscarPerdidas" role="button" aria-label="Buscar">
                      <i class="fas fa-search"></i>
                    </span>
                  </div>
                </div>
              </div>
              
              <!-- TABLA -->
              <div class="table-responsive">
                <table id="tablaPerdidas" class="table-perdidas">
                  <thead>
                    <tr class="text-center">
                      <th style="width:60px;">#</th>         
                      <th>Fecha</th>                           
                      <th>Código</th>                         
                      <th>Producto</th>                       
                      <th>Cantidad</th>                        
                      <th>Motivo</th>                         
                      <th>Observación</th>                    
                      <th>Usuario</th>                         
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($items)) : ?>
                      <?php foreach ($items as $it) :
                        $fechaISO = !empty($it["fecha"]) ? date('Y-m-d H:i:s', strtotime($it["fecha"])) : '';
                        $fechaCL  = !empty($it["fecha"]) ? date('d-m-Y H:i', strtotime($it["fecha"])) : '-';
                      ?>
                        <tr>
                          <td><?php echo (int)$it["id"]; ?></td>
                          <td <?php echo $fechaISO ? 'data-order="'.$fechaISO.'"' : ''; ?>>
                            <?php echo htmlspecialchars($fechaCL); ?>
                          </td>
                          <td><?php echo htmlspecialchars($it["producto_codigo"] ?? ''); ?></td>
                          <td><?php echo htmlspecialchars($it["producto_nombre"] ?? ''); ?></td>
                          <td><?php echo (int)$it["cantidad"]; ?></td>
                          <td class="text-capitalize"><?php echo htmlspecialchars(str_replace('_',' ', $it["motivo"] ?? '')); ?></td>
                          <td><?php echo htmlspecialchars($it["observacion"] ?? ''); ?></td>
                          <td><?php echo htmlspecialchars($it["nombre_usuario"] ?? ''); ?></td>
                        </tr>
                      <?php endforeach; ?>
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

<!-- CSS -->
<link rel="stylesheet" href="vistas/css/registrarpc.css">
<!-- JS -->
<script src="vistas/js/registrarpc.js"></script>