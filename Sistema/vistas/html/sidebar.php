<?php 
// Detectar en qué página estamos
$rutaActiva = $_GET["ruta"] ?? "inicio"; 
?>

<!--------------------- BARRA LATEAL ------------------------>
<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- NOMBRE DEL SISTEMA -->
    <a href="inicio" class="brand-link d-flex align-items-center px-3">
        <span class="brand-text titulo-logo">Botillería Joaquín</span>
    </a>

    <!--------------------- CONTENIDO NAVBAR ------------------------>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false">

                <!-- Opción: Inicio -->
                <li class="nav-item">
                    <a href="inicio" class="nav-link <?php echo ($rutaActiva == 'inicio') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Inicio</p>
                    </a>
                </li>

                <!-- Opción: Crear Venta -->
                <li class="nav-item">
                    <a href="crear-venta" class="nav-link <?php echo ($rutaActiva == 'crear-venta') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>Crear venta</p>
                    </a>
                </li>

                <!-- Opción: Ver ventas -->
                <li class="nav-item">
                    <a href="ver-ventas" class="nav-link <?php echo ($rutaActiva == 'ver-ventas') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-list-alt"></i>
                        <p>Listado de Ventas</p>
                    </a>
                </li>

                <!-- Opción: Cierre Caja -->
                <li class="nav-item">
                    <a href="cierre-caja" class="nav-link <?php echo ($rutaActiva == 'cierre-caja') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-cash-register"></i>
                        <p>Cierre Caja </p>
                    </a>
                </li>

                <!-- Opción: Inventario -->
                <li class="nav-item">
                    <a href="inventario" class="nav-link <?php echo ($rutaActiva == 'inventario') ? 'active' : ''; ?>">
                        <i class="nav-icon fa-solid fa-basket-shopping"></i>
                        <p>Inventario</p>
                    </a>
                </li>

                <!-- Opción: Registrar pérdidas / consumos -->
                <li class="nav-item">
                    <a href="registrarpc" class="nav-link <?php echo ($rutaActiva == 'registrarpc') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-trash-restore-alt"></i>
                        <p>Pérdidas / Consumos </p>
                    </a>
                </li>
                
                <!-- Opción: Listado de Stock Crítico -->
                <li class="nav-item">
                    <a href="stock-critico" class="nav-link <?php echo ($rutaActiva == 'stock-critico') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-exclamation-triangle"></i>
                        <p>Listado de Stock Crítico </p>
                    </a>
                </li>

                <!-- Sección de menú: Productos -->
                <li class="nav-item has-treeview <?php echo $activoProductos ? 'menu-open' : ''; ?>">
                <a href="#"
                    class="nav-link <?php echo $activoProductos ? 'active' : ''; ?>">
                    <i class="nav-icon fa-solid fa-clipboard"></i>
                    <p>
                        Productos
                    <i class="right fas fa-angle-down"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <!-- Opción: Crear productos -->
                    <li class="nav-item">
                    <!-- Solo si es Admin -->
                    <a href="<?php echo ($_SESSION["rol"] === "Administrador") ? 'crear-producto' : '#'; ?>"
                        class="nav-link <?php echo ($rutaActiva == 'crear-producto') ? 'active' : ''; ?>"
                        <?php if ($_SESSION["rol"] !== "Administrador"): ?> onclick="sinPermisoMenu(); return false;" <?php endif; ?>>
                        <i class="nav-icon fas fa-plus-circle"></i>
                            <p>Crear Productos</p>
                    </a>
                    </li>
                    <!-- Opción: Añadir stock -->
                    <li class="nav-item">
                    <!-- Solo si es Admin -->
                    <a href="<?php echo ($_SESSION["rol"] === "Administrador") ? 'anadir-stock' : '#'; ?>"
                        class="nav-link <?php echo ($rutaActiva == 'anadir-stock') ? 'active' : ''; ?>"
                        <?php if ($_SESSION["rol"] !== "Administrador"): ?> onclick="sinPermisoMenu(); return false;" <?php endif; ?>>
                        <i class="nav-icon fas fa-boxes"></i>
                            <p>Añadir Stock</p>
                    </a>
                    </li>
                </ul>
                </li>
                <!-- Sección de menú: Reportes -->    
                <li class="nav-item has-treeview <?php echo $activoReportes ? 'menu-open' : ''; ?>">
                <a href="#"
                    class="nav-link <?php echo $activoReportes ? 'active' : ''; ?>">
                    <i class="nav-icon fas fa-chart-pie"></i>
                    <p>
                        Reportes
                    <i class="right fas fa-angle-down"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <!-- Opción: Productos Vendidos -->
                    <li class="nav-item">
                        <!-- Solo si es Admin -->
                        <a href="<?php echo ($_SESSION["rol"] === "Administrador") ? 'productos-vendidos' : '#'; ?>"
                            class="nav-link <?php echo ($rutaActiva == 'productos-vendidos') ? 'active' : ''; ?>"
                            <?php if ($_SESSION["rol"] !== "Administrador"): ?> onclick="sinPermisoMenu(); return false;" <?php endif; ?>>
                            <i class="nav-icon fas fa-chart-line"></i>
                            <p>Productos Vendidos</p>
                        </a>
                    </li>
                    <!-- Opción: Reporte Ventas -->
                    <li class="nav-item">
                        <!-- Solo si es Admin -->
                        <a href="<?php echo ($_SESSION["rol"] === "Administrador") ? 'reporte-ventas' : '#'; ?>"
                            class="nav-link <?php echo ($rutaActiva == 'reporte-ventas') ? 'active' : ''; ?>"
                            <?php if ($_SESSION["rol"] !== "Administrador"): ?> onclick="sinPermisoMenu(); return false;" <?php endif; ?>>
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>Reporte Ventas</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="ganancias" class="nav-link">
                            <i class="fas fa-chart-pie nav-icon"></i>
                            <p>Ganancias</p>
                        </a>
                        </li>

                </ul>
                </li>
                <!-- Opción: Administrar Usuarios -->                
                <li class="nav-item">
                    <!-- Solo si es Admin -->
                    <a href="<?php echo ($_SESSION["rol"] === "Administrador") ? 'usuarios' : '#'; ?>"
                        class="nav-link <?php echo ($rutaActiva == 'usuarios') ? 'active' : ''; ?>"
                        <?php if ($_SESSION["rol"] !== "Administrador"): ?> onclick="sinPermisoMenu(); return false;" <?php endif; ?>>
                        <i class="nav-icon fa-solid fa-users-cog"></i>
                        <p>Administrar Usuarios</p>
                    </a>
                </li>
                <!-- Opción: Historial -->
                <li class="nav-item">
                    <a href="<?php echo ($_SESSION["rol"] === "Administrador") ? 'historial' : '#'; ?>"
                        class="nav-link <?php echo ($rutaActiva == 'historial') ? 'active' : ''; ?>"
                        <?php if ($_SESSION["rol"] !== "Administrador"): ?> onclick="sinPermisoMenu(); return false;" <?php endif; ?>>
                        <i class="nav-icon fas fa-history"></i>
                        <p>Historial de Cambios</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<!-- CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="vistas/css/sidebar.css">

<!-- SI NO ES ADMIN -->
<?php if ($_SESSION["rol"] !== "Administrador"): ?>
<script>
  function sinPermisoMenu(){
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        icon: 'error',
        title: 'Acceso denegado',
        text: 'Esta sección solo está disponible para administradores.',
        confirmButtonText: 'Entendido'
      });
    } else {
      alert('Acceso denegado. Esta sección solo está disponible para administradores.');
    }
  }
</script>
<?php endif; ?>
