<!-- Contenedor principal del contenido de la página -->
<div class="content-wrapper">

  <!-- Sección del encabezado -->
  <section class="content-header">
    <div class="container-fluid">

      <!-- TITULO PAGINA: INICIO -->
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Bienvenido/a al Sistema de Gestión</h1>
        </div>
      </div>
    </div>
  </section>

  <!--------------------- CONTENIDO PRINCIPAL PAGINA ------------------------>
  <section class="content">

    <!-- Tarjeta (card) de bienvenida -->
    <div class="card">
      
      <!-- Encabezado de la tarjeta -->
      <div class="card-header">
        <!-- NOMBRE USUARIO -->
        <h3 class="card-title">Hola, <strong><?php echo $_SESSION["nombre"]; ?></strong></h3>
      </div>

      <!-- Cuerpo de la tarjeta -->
      <div class="card-body">
        <!-- Rol con el que inició sesión el usuario -->
        <p>Has iniciado sesión como <strong><?php echo $_SESSION["rol"]; ?></strong>.</p>

       <!-- SI ES ADMINISTRADOR -->
<?php if ($_SESSION["rol"] == "Administrador"): ?>
  <p><strong>Como Administrador puedes:</strong></p>
  <ul>
      <!-- Ventas -->
      <li>Crear nuevas ventas</li>
      <li>Ver el listado completo de ventas</li>
      <li>Aplicar promociones vigentes en las ventas</li>

      <!-- Inventario y productos -->
      <li>Consultar y gestionar el inventario</li>
      <li>Registrar nuevos productos</li>
      <li>Añadir stock a productos existentes</li>
      <li>Configurar el stock mínimo de cada producto</li>
      <li>Recibir notificaciones automáticas cuando un producto baja del mínimo</li>

      <!-- Usuarios -->
      <li>Administrar usuarios del sistema</li>
      <li>Gestionar accesos y roles de los usuarios</li>

      <!-- Reportes y cierres -->
      <li>Generar cierres de caja diarios</li>
      <li>Visualizar reportes de ventas diarias</li>
      <li>Visualizar reportes de ventas por hora (tabla + gráfico)</li>
      <li>Descargar el detalle de cierres y reportes</li>

      <!-- Promociones -->
      <li>Programar y gestionar promociones (descuento %, 2x1 o precio fijo)</li>
    </ul>

    <p><strong>Consejos para ti:</strong></p>
    <ul>
      <li>Revisa diariamente los reportes para tomar decisiones informadas.</li>
      <li>Configura niveles de stock mínimo para evitar quiebres de inventario.</li>
      <li>Supervisa las promociones vigentes y ajusta precios cuando sea necesario.</li>
      <li>Descarga y respalda los cierres de caja para mantener un control financiero claro.</li>
    </ul>

    <!-- SI ES VENDEDOR -->
  <?php elseif ($_SESSION["rol"] == "Vendedor"): ?>
    <p><strong>Como Vendedor puedes:</strong></p>
    <ul>
      <!-- Ventas -->
      <li>Registrar nuevas ventas</li>
      <li>Aplicar automáticamente promociones vigentes durante la venta</li>
      <li>Revisar tus ventas anteriores</li>

      <!-- Inventario -->
      <li>Ver el stock disponible de los productos</li>
      <li>Visualizar alertas de stock bajo (solo informativas)</li>

      <!-- Cierres -->
      <li>Visualizar tus propios cierres de caja diarios</li>
      <li>Revisar el total de tus ventas diarias</li>
    </ul>

    <p><strong>Consejos para ti:</strong></p>
    <ul>
      <li>Verifica el stock y las promociones activas antes de realizar una venta.</li>
      <li>Asegúrate de ingresar correctamente los datos en cada venta.</li>
      <li>Consulta al administrador si necesitas ayuda o detectas errores.</li>
      <li>Usa tus cierres de caja para llevar control de tu gestión diaria.</li>
    </ul>
  <?php endif; ?>
  </div>


      <!-- Pie de la tarjeta: muestra fecha y hora del acceso -->
      <?php date_default_timezone_set("America/Santiago"); ?>
      <div class="card-footer">
        Último acceso: <?php echo date("d/m/Y H:i"); ?> hrs.
      </div>
  </section>
</div>
