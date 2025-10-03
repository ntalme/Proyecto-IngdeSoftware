// ---------------------------- LISTAR VENTAS (DATATABLE + BÚSQUEDA/ORDEN) --------------------------------------
$(function () {
  const tablaVentas = $('#tablaVentas').DataTable({
    // Ocultamos el filtro nativo y dejamos solo tabla + info/paginación
    dom: 't<"d-flex justify-content-between align-items-center mt-2"ip>',
    searching: true,   // usamos búsqueda, pero con TU input
    pageLength: 10,
    order: [[4, 'desc']],
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
      paginate: { first: "Primero", previous: "Anterior", next: "Siguiente", last: "Último" },
      aria: { sortAscending: ": activar para ordenar la columna ascendente", sortDescending: ": activar para ordenar la columna descendente" }
    },
    columnDefs: [
      { // ID "#123" -> ordenar por número
        targets: 0,
        render: function (data, type) {
          if (type === 'sort' || type === 'type') {
            const n = parseInt(String(data).replace(/[^0-9]/g, ''), 10);
            return isNaN(n) ? 0 : n;
          }
          return data;
        }
      },
      { // Total "$1.234" -> ordenar por número
        targets: 2,
        render: function (data, type) {
          if (type === 'sort' || type === 'type') {
            const n = parseFloat(String(data).replace(/[^0-9.-]/g, ''));
            return isNaN(n) ? 0 : n;
          }
          return data;
        }
      },
      { // Fecha "dd/mm/YYYY HH:mm" -> ordenar como ISO
        targets: 4,
        render: function (data, type) {
          if (type === 'sort' || type === 'type') {
            const m = String(data).match(/(\d{2})\/(\d{2})\/(\d{4}) (\d{2}):(\d{2})/);
            if (m) return `${m[3]}-${m[2]}-${m[1]} ${m[4]}:${m[5]}:00`;
          }
          return data;
        }
      }
    ]
  });

  // Normaliza (sin acentos + minúsculas) para que la búsqueda sea más flexible
  const norm = s => (s || '')
    .toLowerCase()
    .normalize('NFD').replace(/[\u0300-\u036f]/g, '');

  // Conectar tu input al DataTable
  const $input = $('#buscarVentas');
  const $btn   = $('#btnBuscarVentas');

  const doSearch = () => tablaVentas.search( norm($input.val()) ).draw();

  $input.on('input', doSearch);
  $btn.on('click', doSearch);

  // Selects de orden dinámico
  $('#criterioOrdenVentas, #direccionOrdenVentas').on('change', function () {
    const criterio = parseInt($('#criterioOrdenVentas').val(), 10); // índice de columna (0..6)
    const dir = $('#direccionOrdenVentas').val(); // 'asc' | 'desc'
    tablaVentas.order([criterio, dir]).draw();
  });
});
