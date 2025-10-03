// ---------------------------- STOCK CRÍTICO (DATATABLE + BÚSQUEDA) --------------------------------------
// Recomendado: lanzar errores en consola para depurar DataTables si algo no calza
$.fn.dataTable.ext.errMode = 'throw';

$(function () {

  // Si ya estaba inicializada, destruir para evitar doble init
  if ($.fn.DataTable.isDataTable('#tablaStockCritico')) {
    $('#tablaStockCritico').DataTable().destroy();
  }

  // Normalizar búsquedas (sin tildes, case-insensitive)
  const norm = s => (s || '')
    .toString()
    .toLowerCase()
    .normalize('NFD').replace(/[\u0300-\u036f]/g, '');

  // Extender el buscador global para normalizar
  $.fn.dataTable.ext.type.search.string = function (d) {
    return d ? norm(d) : '';
  };

  const tabla = $('#tablaStockCritico').DataTable({
    // Solo tabla + info+paginación debajo
    dom: 't<"d-flex justify-content-between align-items-center mt-2"ip>',

    // Opciones
    pageLength: 10,
    order: [[1, 'asc']], // por defecto: Producto (col 1)

    // Definir tipos de columnas (índices desde 0)
    columnDefs: [
      { targets: [5, 7], type: 'num' }, // Cantidad (5) y Stock mínimo (7)
      // Estado (8) ya usa data-order en el TD, DataTables lo respeta
    ],

    // Textos en español
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
    }
  });

  // Controles externos de orden
  const $crit = $('#criterioOrdenSC');
  const $dir  = $('#direccionOrdenSC');

  function applyOrder() {
    const col = parseInt($crit.val(), 10) || 0;
    const dir = ($dir.val() === 'asc') ? 'asc' : 'desc';
    tabla.order([col, dir]).draw();
  }

  $crit.on('change', applyOrder);
  $dir.on('change', applyOrder);

  // Búsqueda externa
  $('#buscarSC').on('input', function(){
    tabla.search(norm(this.value)).draw();
  });

  // O buscar solo cuando se hace click en la lupa
  $('#btnBuscarSC').on('click', function() {
    tabla.search(norm($('#buscarSC').val())).draw();
  });
});
