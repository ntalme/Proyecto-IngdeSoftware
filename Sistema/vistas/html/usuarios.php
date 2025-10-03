<!-- Contenedor general -->
<div class="content-wrapper">

  <!-- Encabezado del contenido -->
  <section class="content-header">
    <div class="container-fluid">

      <!-- TITULO PAGINA: ADMINISTRAR USUARIOS -->
      <div class="row mb-2 align-items-center">
        <div class="col-sm-6">
          <h1>Administrar Usuarios</h1>
        </div>

        <!-- BOTON: AGREGAR USUARIO -->
        <div class="col-sm-6 text-right">
          <button class="btn btn-agregar btn-sm" data-toggle="modal" data-target="#modalAgregarUsuario">
            <i class="fas fa-user-plus me-1"></i> Agregar usuario
          </button>
        </div>
      </div>

      <!-- DESCRIPCION DE LA PAGINA -->
      <div class="row">
        <div class="col-12">
          <p class="descripcion-pagina mb-0">
            En esta sección podrá gestionar los usuarios del sistema, 
            creando nuevos perfiles, modificando la información existente 
            y asignando roles o permisos según corresponda.
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

          <!-- Caja visual que contiene la tabla -->
          <div class="card">

            <!-- Cuerpo de la caja -->
            <div class="card-body">
              <div class="table-responsive">
                <table id="tablaUsuarios" class="table-usuarios">
                  <!-- Encabezado de la tabla -->
                  <thead class="thead">
                    <tr>
                      <th style="width: 40px;">#</th>
                      <th>Nombre</th>
                      <th>Usuario</th>
                      <th>Rol</th>
                      <th style="width: 120px;">Acciones</th>
                    </tr>
                  </thead>

                  <!-- Cuerpo de la tabla -->
                  <tbody>
                    <?php
                      $item = null;
                      $valor = null;
                      $usuarios = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);

                      foreach ($usuarios as $key => $value){
                        echo '<tr>
                                <td>'.$value["id"].'</td>
                                <td>'.$value["nombre"].'</td>
                                <td>'.$value["usuario"].'</td>
                                <td>'.$value["rol"].'</td>
                                <td>
                                  <div class="btn-group">
                                    <!-- Botón Editar -->
                              <button class="btn btn-editar btn-sm btnEditarUsuario"
                                      idUsuario="'.$value["id"].'"
                                      data-toggle="modal"
                                      data-target="#modalEditarUsuario"
                                      title="Editar Usuario">
                                <i class="fas fa-edit"></i>
                              </button>

                              <!-- Botón Eliminar -->
                              <button class="btn btn-danger btn-sm btnEliminarUsuario"
                                     idUsuario="'.$value["id"].'"
                                      title="Eliminar Usuario">
                                <i class="fas fa-trash-alt"></i>
                              </button>
                                  </div>
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
      </div>
    </div>
  </section>
</div>

<!--------------------- MODAL: AGREGAR USUARIO ------------------------>
<div class="modal fade" id="modalAgregarUsuario" tabindex="-1" role="dialog" aria-labelledby="modalAgregarUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content shadow-lg border-0 rounded-xl overflow-hidden">

      <!-- Formulario para crear usuario -->
      <form role="form" method="post" enctype="multipart/form-data">

        <!-- Título del modal -->
        <div class="modal-header modal-header-custom text-white">
          <div class="d-flex align-items-center">
            <div class="modal-icon mr-2"><i class="fas fa-user-plus"></i></div>
            <h5 class="modal-title mb-0" id="modalAgregarUsuarioLabel">Agregar nuevo usuario</h5>
          </div>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <!-- Campos del formulario -->
        <div class="modal-body p-4">
          <div class="row">
            <!-- Nombre -->
            <div class="col-md-12 mb-3">
              <label class="font-weight-semibold" for="nuevoNombre">Nombre completo</label>
              <div class="input-group input-group-lg">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                </div>
                <input type="text" class="form-control" id="nuevoNombre" name="nuevoNombre" placeholder="Ingrese nombre completo" required>
              </div>
            </div>

            <!-- Usuario -->
            <div class="col-md-12 mb-3">
              <label class="font-weight-semibold" for="nuevoUsuario">Nombre de usuario</label>
              <div class="input-group input-group-lg">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-at"></i></span>
                </div>
                <input type="text" class="form-control" id="nuevoUsuario" name="nuevoUsuario" placeholder="Ingrese nombre de usuario" required>
              </div>
            </div>

            <!-- Contraseña -->
            <div class="col-md-12 mb-3">
              <label class="font-weight-semibold" for="nuevaPassword">Contraseña</label>
              <div class="input-group input-group-lg">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-lock"></i></span>
                </div>
                <input type="password" class="form-control" id="nuevaPassword" name="nuevaPassword" placeholder="Ingrese una contraseña" required>
              </div>
            </div>

            <!-- Rol -->
            <div class="col-md-12 mb-0">
              <label class="font-weight-semibold" for="nuevoRol">Rol</label>
              <div class="input-group input-group-lg">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-user-shield"></i></span>
                </div>
                <select class="custom-select custom-select-lg" id="nuevoRol" name="nuevoRol" required>
                  <option value="">Seleccione un rol</option>
                  <option value="Administrador">Administrador</option>
                  <option value="Vendedor">Vendedor</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Botones del modal -->
        <div class="modal-footer border-0 pt-0 px-4 pb-3 d-flex justify-content-between">
          <button type="button" class="btn btn-cancelar" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i> Cancelar
          </button>
          <button type="submit" class="btn btn-guardar-usuario">
            <i class="fas fa-save mr-1"></i> Guardar usuario
          </button>
        </div>

        <!-- Procesa el formulario desde PHP -->
        <?php
          $crearUsuario = new ControladorUsuarios();
          $crearUsuario -> ctrCrearUsuario();
        ?>
      </form>
    </div>
  </div>
</div>

<!--------------------- MODAL: EDITAR USUARIO  ------------------------>
<div class="modal fade" id="modalEditarUsuario" tabindex="-1" role="dialog" aria-labelledby="modalAgregarUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content shadow-lg border-0 rounded-xl overflow-hidden">

      <!-- Formulario para editar usuario -->
      <form role="form" method="post" enctype="multipart/form-data">

        <!-- Encabezado del modal -->
        <div class="modal-header modal-header-custom text-white">
          <div class="d-flex align-items-center">
            <div class="modal-icon mr-2"><i class="fas fa-user-edit"></i></div>
            <h5 class="modal-title mb-0">Editar usuario</h5>
          </div>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <!-- Campos para editar -->
        <div class="modal-body p-4">
          <div class="row">
            <!-- Nombre -->
            <div class="col-md-12 mb-3">
              <label class="font-weight-semibold" for="editarNombre">Nombre completo</label>
              <div class="input-group input-group-lg">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                </div>
                <input type="text" class="form-control" id="editarNombre" name="editarNombre" required>
              </div>
            </div>

            <!-- Usuario (solo lectura) -->
            <div class="col-md-12 mb-3">
              <label class="font-weight-semibold" for="editarUsuario">Nombre de usuario</label>
              <div class="input-group input-group-lg">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-at"></i></span>
                </div>
                <input type="text" class="form-control" id="editarUsuario" name="editarUsuario" readonly>
              </div>
            </div>

            <!-- Contraseña -->
            <div class="col-md-12 mb-3">
              <label class="font-weight-semibold" for="editarPassword">Contraseña</label>
              <div class="input-group input-group-lg">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-lock"></i></span>
                </div>
                <input type="password" class="form-control" id="editarPassword" name="editarPassword" placeholder="Déjalo vacío si no deseas cambiarla">
                <input type="hidden" id="passwordActual" name="passwordActual">
              </div>
            </div>

            <!-- Rol -->
            <div class="col-md-12 mb-0">
              <label class="font-weight-semibold" for="editarRol">Rol</label>
              <div class="input-group input-group-lg">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-user-shield"></i></span>
                </div>
                <select class="custom-select custom-select-lg" id="editarRol" name="editarRol" required>
                  <option value="" id="editarRol"></option>
                  <option value="Administrador">Administrador</option>
                  <option value="Vendedor">Vendedor</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Botones del modal -->
        <div class="modal-footer border-0 pt-0 px-4 pb-3 d-flex justify-content-between">
          <button type="button" class="btn btn-cancelar" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i> Cancelar
          </button>
          <button type="submit" class="btn btn-guardar-usuario">
            <i class="fas fa-save mr-1"></i> Guardar usuario
          </button>
        </div>

        <!-- Procesamiento desde PHP -->
        <?php
          $editarUsuario = new ControladorUsuarios();
          $editarUsuario -> ctrEditarUsuario();
        ?>
      </form>
    </div>
  </div>
</div>

<!-- Borrar usuarios cuando se llama el botón eliminar -->
<?php
  $borrarUsuario = new ControladorUsuarios();
  $borrarUsuario  -> ctrBorrarUsuario();
?>

<!-- JS -->
<script src="vistas/js/usuarios.js"></script>
<!-- CSS -->
<link rel="stylesheet" href="vistas/css/usuarios.css">
