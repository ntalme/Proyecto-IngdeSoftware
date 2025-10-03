// ---------------------------- MOSTRAR PRODUCTOS --------------------------------------
$(function () {
  const clp = new Intl.NumberFormat('es-CL', {
    style: 'currency',
    currency: 'CLP',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  });
  const intCL = new Intl.NumberFormat('es-CL');

  function numForSort(data) {
    const n = parseFloat(String(data).replace(/[^0-9.-]/g, ''));
    return isNaN(n) ? 0 : n;
  }
  function cantidadRenderer(data, type) {
    if (type === 'sort' || type === 'type') return numForSort(data);
    const n = numForSort(data);
    return isNaN(n) ? data : intCL.format(n);
  }
  function clpRenderer(data, type) {
    if (type === 'sort' || type === 'type') return numForSort(data);
    const n = numForSort(data);
    return isNaN(n) ? data : clp.format(n);
  }

  const tabla = $('#tablaProductos').DataTable({
    dom: 't<"d-flex justify-content-between align-items-center mt-2"ip>',
    searching: true,
    pageLength: 10,
    order: [[2, 'asc']], // Nombre por defecto
    language: { /* tu config de idioma */ },
    columnDefs: [
      { targets: 0, orderable: false, searchable: false },  // Imagen
      { targets: 10, orderable: false, searchable: false }, // Acciones
      { targets: 6, render: cantidadRenderer },
      { targets: 7, render: clpRenderer },
      { targets: 8, render: clpRenderer },
      { targets: 11, type: 'date' } // Fecha vencimiento
    ]
  });

  // --- búsqueda normalizada ---
  const norm = s => (s || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
  $('#buscarProducto').on('input', function () {
    tabla.search(norm(this.value)).draw();
  });
  $('#btnBuscarProducto').on('click', function () {
    tabla.search(norm($('#buscarProducto').val())).draw();
  });

  // --- controles externos de orden ---
  function applyOrderFromControls() {
    const colIdx = parseInt($('#criterioOrdenSC option:selected').data('col'), 10) || 2;
    const dir = $('#direccionOrdenSC').val() === 'desc' ? 'desc' : 'asc';
    tabla.order([colIdx, dir]).draw();
  }

  $('#criterioOrdenSC').on('change', applyOrderFromControls);
  $('#direccionOrdenSC').on('change', applyOrderFromControls);

  // aplicar al inicio
  applyOrderFromControls();
});
