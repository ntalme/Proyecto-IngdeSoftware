<?php
$historial = ControladorHistorial::ctrMostrarHistorial();
?>

<!-- Contenedor general -->
<div class="content-wrapper">

  <!-- Encabezado del contenido -->
  <section class="content-header">
    <div class="container-fluid">

      <!-- TITULO PAGINA -->
      <div class="row mb-2 align-items-center">
        <div class="col-sm-6">
          <h1>Historial de Cambios</h1>
        </div>
      </div>

      <!-- DESCRIPCION DE LA PAGINA -->
      <div class="row">
        <div class="col-12">
          <p class="descripcion-pagina mb-0">
            En esta sección podrá revisar todas las acciones realizadas en el 
            sistema, incluyendo registros, modificaciones y eliminaciones efectuadas 
            por los usuarios.
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

          <!-- Caja visual que contiene la tabla -->
          <div class="card">

            <!-- Cuerpo de la caja -->
            <div class="card-body">
              <div class="table-responsive">
                <table id="tablaHistorial" class="table-historial">
                  <!-- Encabezado de la tabla -->
                  <thead class="thead">
                    <tr>
                      <th>Fecha</th>
                      <th>Usuario</th>
                      <th>Módulo</th>
                      <th>Acción</th>
                      <th>ID Registro</th>
                      <th>Detalles</th>
                    </tr>
                  </thead>

                  <!-- Cuerpo de la tabla -->
                    <tbody>
                      <?php if (!empty($historial)): ?>
                        <?php foreach ($historial as $fila): ?>
                          <?php
                            $accion = strtoupper($fila["tipo_accion"]);
                            $jsonFuente = ($accion === "DELETE") ? $fila["valor_anterior"] : $fila["valor_nuevo"];
                            $cambios = json_decode($jsonFuente, true);
                          ?>
                          <tr>
                            <td><?= htmlspecialchars($fila["fecha"]) ?></td>
                            <td><?= htmlspecialchars(ucfirst($fila["usuario"])) ?></td>
                            <td><?= htmlspecialchars($fila["modulo"]) ?></td>

                            <!-- Columna Acción con color dinámico -->
                            <td>
                              <?php
                                $accionClass = strtolower($accion);
                                echo "<span class='accion-label accion-$accionClass'>$accion</span>";
                              ?>
                            </td>

                            <td><?= htmlspecialchars($fila["id_registro_afectado"]) ?></td>

                            <!-- Columna Detalles -->
                            <td class="text-left">
                              <?php
                              /* CASO DELETE */
                              if ($accion === "DELETE") {

                                // Limpieza del JSON antes de decodificar
                                $jsonLimpio = stripslashes($fila["valor_anterior"]); 
                                $cambios = json_decode($jsonLimpio, true);

                                echo "<div class='detalle-bloque delete'>
                                        <div class='detalle-encabezado'>
                                          <strong>Registro eliminado</strong>
                                        </div>";

                                // Si el JSON se decodificó correctamente y contiene datos
                                if (is_array($cambios) && !empty($cambios)) {
                                  foreach ($cambios as $campo => $valor) {
                                    $campoBonito = ucfirst(str_replace('_', ' ', $campo));
                                    $valorLimpio = ($valor === null || $valor === "" || strtolower($valor) === "null")
                                      ? '<em class="text-muted">Sin información</em>'
                                      : htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');

                                    echo "<div class='detalle-item'>
                                            <span class='label'>$campoBonito:</span>
                                            <span class='valor'>$valorLimpio</span>
                                          </div>";
                                  }
                                } 
                                // Si no se pudo decodificar o está vacío
                                else {
                                  echo "<em class='text-muted'>Sin información disponible.</em>";
                                }

                                echo "</div>";
                              }

                              /* CASO STOCK */
                              elseif (strtolower($fila["modulo"]) === "stock") {
                                echo "<div class='detalle-bloque stock'>
                                        <div class='detalle-encabezado'>
                                          <strong>Cambios en el Stock</strong>
                                        </div>";

                                if (is_array($cambios)) {
                                  foreach ($cambios as $campo => $valor) {
                                    $campoBonito = ucfirst(str_replace('_', ' ', $campo));
                                    $valor = ($valor === null || $valor === "" || strtolower($valor) === "null")
                                            ? '<em class=\"text-muted\">Sin información</em>'
                                            : htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
                                    echo "<div class='detalle-item'>
                                            <span class='label'>$campoBonito:</span>
                                            <span class='valor'>$valor</span>
                                          </div>";
                                  }
                                } else {
                                  echo "<em class='text-muted'>No se pudo leer la información del stock agregado.</em>";
                                }

                                echo "</div>";
                              }

                              /* CASO UPDATE */
                              elseif ($accion === "UPDATE") {
                                if (is_array($cambios) && !empty($cambios)) {
                                  foreach ($cambios as $campo => $valores) {
                                    $campoBonito = ucfirst(str_replace('_', ' ', $campo));
                                    $antes = $valores["antes"] ?? '<em class="text-muted">Sin información</em>';
                                    $despues = $valores["después"] ?? '<em class="text-muted">Sin información</em>';

                                    echo "<div class='detalle-bloque update'>
                                            <div class='detalle-encabezado'>
                                              <strong>$campoBonito</strong>
                                            </div>
                                            <div class='detalle-item'>
                                              <span class='label'>Antes:</span>
                                              <span class='valor antes'>$antes</span>
                                            </div>
                                            <div class='detalle-item'>
                                              <span class='label'>Después:</span>
                                              <span class='valor despues'>$despues</span>
                                            </div>
                                          </div>";
                                  }
                                } else {
                                  echo "<em class='text-muted'>Sin cambios detectados</em>";
                                }
                              }

                              /* CASO INSERT */
                              elseif ($accion === "INSERT") {
                                echo "<div class='detalle-bloque insert'>
                                        <div class='detalle-encabezado'>
                                          <strong>Registro agregado</strong>
                                        </div>";

                                if (is_array($cambios)) {
                                  foreach ($cambios as $campo => $valor) {
                                    $campoBonito = ucfirst(str_replace('_', ' ', $campo));
                                    $valor = ($valor === null || $valor === "" || strtolower($valor) === "null")
                                            ? '<em class=\"text-muted\">Sin información</em>'
                                            : htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
                                    echo "<div class='detalle-item'>
                                            <span class='label'>$campoBonito:</span>
                                            <span class='valor'>$valor</span>
                                          </div>";
                                  }
                                } else {
                                  echo "<em class='text-muted'>No se pudo decodificar el registro agregado.</em>";
                                }

                                echo "</div>";
                              }

                              /* OTROS CASOS */
                              else {
                                echo "<em class='text-muted'>Sin detalles disponibles</em>";
                              }
                              ?>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="6" class="text-center text-muted">
                            <em>No existen registros en el historial.</em>
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

<!-- JS -->
<script src="vistas/js/historial.js"></script>
<!-- CSS -->
<link rel="stylesheet" href="vistas/css/historial.css">