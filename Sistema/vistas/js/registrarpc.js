// ---------------------------- MOSTRAR PÉRDIDAS (DATATABLE) --------------------------------------
$(function () {
  const $crit = $('#criterioOrden');
  const $dir  = $('#direccionOrden');

  const tabla = $('#tablaPerdidas').DataTable({
    dom: 't<"d-flex justify-content-between align-items-center mt-2"ip>',
    searching: true,
    pageLength: 10,
    // match inicial al select: Producto (3) desc
    order: [[parseInt($crit.val(),10)||3, ($dir.val()==='asc'?'asc':'desc')]],
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
      { // Col 0: "#"/ID -> ordenar numérico aun si viene con prefijo
        targets: 0,
        render: function (data, type) {
          if (type === 'sort' || type === 'type') {
            const n = parseInt(String(data).replace(/[^0-9]/g, ''), 10);
            return isNaN(n) ? 0 : n;
          }
          return data;
        }
      },
      { // Col 4: Cantidad -> numérico
        targets: 4,
        render: function (data, type) {
          if (type === 'sort' || type === 'type') {
            const n = parseFloat(String(data).replace(/[^0-9.-]/g, ''));
            return isNaN(n) ? 0 : n;
          }
          return data;
        }
      }
      // Col 1 (Fecha): ya usa data-order="YYYY-MM-DD HH:mm:ss" en tu HTML, DataTables lo toma tal cual.
    ]
  });

  // Normaliza búsqueda (minúsculas + sin acentos)
  const norm = s => (s || '')
    .toLowerCase()
    .normalize('NFD').replace(/[\u0300-\u036f]/g, '');

  // Conectar input + ícono
  const $input = $('#buscarPerdidas');
  const $btn   = $('#btnBuscarPerdidas');
  const doSearch = () => tabla.search( norm($input.val()) ).draw();

  $input.on('input', doSearch);
  $btn.on('click', doSearch);

  // Controles de orden externos
  const applyOrder = () => {
    const col = parseInt($crit.val(), 10) || 0;
    const dir = $dir.val() === 'asc' ? 'asc' : 'desc';
    tabla.order([col, dir]).draw();
  };
  $crit.on('change', applyOrder);
  $dir.on('change', applyOrder);
});
