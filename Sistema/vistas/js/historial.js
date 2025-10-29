$(function () {
  const tabla = $('#tablaHistorial').DataTable({
    dom: 't<"d-flex justify-content-between align-items-center mt-2"ip>',
    searching: true,
    pageLength: 10,
    order: [[0, 'desc']], // ordena por fecha descendente al inicio

    language: {
      decimal: ",", thousands: ".",
      processing: "Procesando...",
      lengthMenu: "Mostrar _MENU_ registros",
      info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
      infoEmpty: "Mostrando 0 a 0 de 0 registros",
      infoFiltered: "(filtrado de _MAX_ registros totales)",
      loadingRecords: "Cargando...",
      zeroRecords: "No se encontraron resultados",
      emptyTable: "Ningún dato disponible en la tabla",
      paginate: {
        first: "Primero",
        previous: "Anterior",
        next: "Siguiente",
        last: "Último"
      },
      aria: {
        sortAscending: ": activar para ordenar ascendente",
        sortDescending: ": activar para ordenar descendente"
      }
    },

    columnDefs: [
      { // Fecha -> convierte dd/mm/yyyy o yyyy-mm-dd a formato ISO para ordenar
        targets: 0,
        render: function (data, type) {
          if ((type === 'sort' || type === 'type') && data) {
            const m = String(data).match(/(\d{2})[\/\-](\d{2})[\/\-](\d{4})/);
            if (m) return `${m[3]}-${m[2]}-${m[1]} 00:00:00`;
          }
          return data || '';
        }
      },
      { targets: 1, type: 'string' }, // Usuario
      { targets: 2, type: 'string' }, // Módulo

      { // Acción -> orden lógico: INSERT(1) < UPDATE(2) < DELETE(3)
        targets: 3,
        render: function (data, type) {
          const plain = String(data).replace(/<[^>]*>/g, '').trim().toUpperCase();
          const orden = { 'INSERT': 1, 'UPDATE': 2, 'DELETE': 3 };

          if (type === 'sort' || type === 'type') {
            return (plain in orden) ? orden[plain] : 99;
          }
          return data;
        }
      },

      { // ID Registro -> numérico
        targets: 4,
        render: function (data, type) {
          if (type === 'sort' || type === 'type') {
            const n = parseInt(String(data).replace(/[^0-9]/g, ''), 10);
            return isNaN(n) ? 0 : n;
          }
          return data;
        }
      },

      { // Detalles -> no ordenable, texto plano para búsquedas
        targets: 5,
        orderable: false,
        searchable: true,
        render: function (data, type) {
          // Para búsquedas, elimina etiquetas HTML
          if (type === 'filter') {
            return String(data).replace(/<[^>]+>/g, ' ').trim();
          }
          return data;
        }
      }
    ]
  });
});
