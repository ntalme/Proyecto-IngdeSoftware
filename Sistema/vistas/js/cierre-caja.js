// ---------------------------- MOSTRAR DETALLE DE VENTAS EN MODAL --------------------------------------
// Espera a que todo el DOM esté cargado
document.addEventListener('DOMContentLoaded', () => {

  // Recorre todos los botones con la clase .verVentas
  document.querySelectorAll('.verVentas').forEach(btn => {

    // Al hacer click en un botón "Ver Ventas"
    btn.addEventListener('click', () => {

      // Obtiene el JSON de ventas almacenado en el atributo data-json del botón
      const ventasJson = btn.dataset.json || "[]";

      // Intenta parsear el JSON, si falla devuelve un arreglo vacío
      let ventas = [];
      try { 
        ventas = JSON.parse(ventasJson); 
      } catch(e) { 
        ventas = []; 
      }

      // Selecciona el cuerpo de la tabla del modal
      const tbody = document.querySelector('#tablaVentas tbody');

      // Limpia el contenido previo
      tbody.innerHTML = "";

      // Si no hay ventas, muestra un mensaje
      if (!Array.isArray(ventas) || ventas.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted"><em>No hay ventas registradas</em></td></tr>';
      } else {
        // Si hay ventas, recorre cada venta
        ventas.forEach(v => {

          // Texto de productos
          let productosTxt = "-";

          // Si el campo productos es un array, arma un texto con descripción y cantidad
          if (Array.isArray(v.productos)) {
            productosTxt = v.productos.map(p => {
              const nombre = p.descripcion || p.nombre || p.producto || "Producto"; // Busca el nombre en distintas claves
              const cant   = p.cantidad || 1; // Cantidad por defecto = 1
              return `${nombre} (x${cant})`;
            }).join(", ");
          }

          // Construye la fila de la tabla con los datos de la venta
          const fila = `
            <tr>
              <td>${v.id_venta ?? ''}</td>
              <td>${v.fecha ?? ''}</td>
              <td>$${Number(v.total ?? 0).toLocaleString("es-CL")}</td>
              <td>${v.metodo_pago ?? ''}</td>
              <td>${productosTxt}</td>
            </tr>
          `;

          // Inserta la fila en la tabla
          tbody.insertAdjacentHTML("beforeend", fila);
        });
      }

      // Finalmente abre el modal
      $('#modalVentas').modal('show');
    });
  });
});
