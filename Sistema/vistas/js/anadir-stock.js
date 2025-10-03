// ---------------------------- MOSTRAR STOCK --------------------------------------
$(function () {
  const tabla = $('#tablaAddStock').DataTable({
    dom: 't<"d-flex justify-content-between align-items-center mt-2"ip>',
    searching: true,      // usamos búsqueda, pero con input propio
    pageLength: 10,
    order: [[0, 'desc']], // por defecto por ID
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
      aria: { sortAscending: ": activar para ordenar ascendente", sortDescending: ": activar para ordenar descendente" }
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
      { // Cantidad numérica
        targets: 6,
        render: function (data, type) {
          if (type === 'sort' || type === 'type') {
            const n = parseFloat(String(data).replace(/[^0-9.-]/g, ''));
            return isNaN(n) ? 0 : n;
          }
          return data;
        }
      },
      { // Fecha recepción "dd/mm/YYYY" -> ordenar como ISO
        targets: 7,
        render: function (data, type) {
          if ((type === 'sort' || type === 'type') && data) {
            const m = String(data).match(/(\d{2})\/(\d{2})\/(\d{4})/);
            if (m) return `${m[3]}-${m[2]}-${m[1]} 00:00:00`;
          }
          return data || '';
        }
      },
      { // Fecha vencimiento "dd/mm/YYYY" -> ordenar como ISO
        targets: 8,
        render: function (data, type) {
          if ((type === 'sort' || type === 'type') && data) {
            const m = String(data).match(/(\d{2})\/(\d{2})\/(\d{4})/);
            if (m) return `${m[3]}-${m[2]}-${m[1]} 00:00:00`;
          }
          return data || '';
        }
      }
    ]
  });

  // Normalizador para búsqueda (minúsculas + sin acentos)
  const norm = s => (s || '')
    .toLowerCase()
    .normalize('NFD').replace(/[\u0300-\u036f]/g, '');

  // Conectar input/botón al DataTable
  const $input = $('#buscarStock');
  const $btn   = $('#btnBuscarStock');
  const doSearch = () => tabla.search( norm($input.val()) ).draw();

  $input.on('input', doSearch);
  $btn.on('click', doSearch);

  // Controles de orden externos
  const $crit = $('#criterioOrdenStock');
  const $dir  = $('#direccionOrdenStock');
  const applyOrder = () => {
    const col = parseInt($crit.val(), 10) || 0;
    const dir = $dir.val() === 'asc' ? 'asc' : 'desc';
    tabla.order([col, dir]).draw();
  };
  $crit.on('change', applyOrder);
  $dir.on('change', applyOrder);
});
