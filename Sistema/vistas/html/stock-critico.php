<?php
// Trae productos críticos (agotados o bajo mínimo)
$productos = ControladorProductos::ctrProductosStockCritico();
?>

<div class="content-wrapper">

  <!-- Encabezado del contenido -->
  <section class="content-header">
    <div class="container-fluid">

      <!-- TITULO PAGINA: STOCK CRITICO -->
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Stock Crítico</h1>
        </div>

        <div class="col-sm-6 text-right">
          <!-- BOTON: DESCARGAR PDF -->
          <a href="index.php?ruta=stock-critico&export=pdf" class="btn btn-danger mr-2">
            <i class="fas fa-file-pdf mr-1"></i> Descargar PDF
          </a>
        </div>
      </div>

      <!-- DESCRIPCION DE LA PAGINA -->
      <p class="descripcion-pagina mb-0">
        En esta sección se listan los productos que se encuentran <strong>agotados</strong> 
        o con stock por debajo del nivel mínimo configurado, para facilitar su control y reposición.
      </p>
    </div>

    <!-- LINEA DIVISORA -->
    <hr class="linea-divisora">
  </section>

<!--------------------- CONTENIDO PRINCIPAL PAGINA ------------------------>
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-body">

          <!-- Controles -->
          <div class="row g-2 align-items-end mb-3 filtros-sc w-100">

            <!-- ORDENAR POR -->
            <div class="col-auto">
              <label class="form-label mb-1 me-2">Ordenar por</label>
              <select id="criterioOrdenSC" class="form-select form-select-sm w-auto select-bonito">
                <option value="1">Código</option>
                <option value="2" selected>Producto</option>
                <option value="3">Tamaño</option>
                <option value="4">Marca</option>
                <option value="5">Cantidad</option>
                <option value="6">Proveedor</option>
                <option value="7">Stock mínimo</option>
                <option value="8">Estado</option>
              </select>
            </div>

            <!-- DIRECCION -->
            <div class="col-auto">
              <label class="form-label mb-1 me-2">Dirección</label>
              <select id="direccionOrdenSC" class="form-select form-select-sm w-auto select-bonito">
                <option value="asc" selected>Ascendente</option>
                <option value="desc">Descendente</option>
              </select>
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
          </div>

          <!-- TABLA DE DATOS -->
         <div class="table-responsive">
          <table id="tablaStockCritico" class="table-stock text-center">
              <thead>
                <tr>             
                  <th>CÓDIGO</th>          
                  <th>PRODUCTO</th>  
                  <th>FORMATO</th>        
                  <th>TAMAÑO</th>           
                  <th>MARCA</th>             
                  <th>CANTIDAD</th>          
                  <th>PROVEEDOR</th>         
                  <th>STOCK MÍNIMO</th>     
                  <th>ESTADO</th>            
                </tr>
              </thead>
              <tbody>
                <?php
                  if (empty($productos)) {
                    echo '<tr><td colspan="10" class="text-center text-muted py-4">No hay productos críticos</td></tr>';
                  } else {
                    foreach ($productos as $p) {
                      $codigo      = htmlspecialchars($p["codigo"] ?? '');
                      $nombre      = htmlspecialchars($p["nombre"] ?? '');
                      $tamano      = htmlspecialchars($p["tamano"] ?? '');
                      $formato     = htmlspecialchars($p["formato"] ?? '');
                      $marca       = htmlspecialchars($p["marca"] ?? '');
                      $proveedor   = htmlspecialchars($p["proveedor"] ?? '');
                      $cantidad    = (int)($p["cantidad"] ?? 0);
                      $stockMinimo = (int)($p["stock_minimo"] ?? 0);

                      $isAgotado  = ($cantidad === 0);
                      $estadoRank = $isAgotado ? 0 : 1;
                      $badge = $isAgotado
                              ? '<span class="badge badge-danger">Agotado</span>'
                              : '<span class="badge badge-warning">Bajo mínimo</span>';

                      echo '<tr>
                        <td>'.$codigo.'</td>
                        <td>'.$nombre.'</td>
                        <td>'.$formato.'</td>
                        <td>'.$tamano.'</td>
                        <td>'.$marca.'</td>
                        <td data-order="'.$cantidad.'">'.number_format($cantidad, 0, ",", ".").'</td>
                        <td>'.$proveedor.'</td>
                        <td data-order="'.$stockMinimo.'">'.number_format($stockMinimo, 0, ",", ".").'</td>
                        <td class="estado" data-order="'.$estadoRank.'">'.$badge.'</td>
                      </tr>';
                    }
                  }
                ?>
              </tbody>
            </table>
          </div>
          <div class="mt-2 small text-muted" id="infoTabla"></div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- JS -->
<script src="vistas/js/stock-critico.js"></script>
<!-- CSS -->
<link rel="stylesheet" href="vistas/css/stock-critico.css">