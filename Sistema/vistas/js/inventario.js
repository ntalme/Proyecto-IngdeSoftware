// ---------------------------- CONSTANTES / ENDPOINTS --------------------------------------
// Usa ruta raíz del proyecto
const URL_PRODUCTOS_AJAX = "ajax/productos.ajax.php";

// ---------------------------- INVENTARIO: CONFIGURAR DATATABLE + BÚSQUEDA/ORDEN --------------------------------------
$(function () {

  // (A) Normalización global para búsqueda sin tildes en los datos de la tabla
  $.fn.DataTable.ext.type.search.string = function (d) {
    return d
      ? String(d).toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')
      : '';
  };

  const $crit = $('#criterioOrden');
  const $dir  = $('#direccionOrden');

  const tabla = $('#tablaInventario').DataTable({
    dom: 't<"d-flex justify-content-between align-items-center mt-2"ip>',
    searching: true,
    pageLength: 10,

    // (B) RESTAR 1 al índice seleccionado (base-0 de DataTables)
    order: [[ (parseInt($crit.val(),10) - 1) || 1, ($dir.val()==='asc'?'asc':'desc') ]], // por defecto: Nombre (col 2 -> índice 1)

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
      { targets: 0, orderable: false, searchable: false },   // Imagen
      { targets: 12, orderable: false, searchable: false },  // Acciones
      { targets: 6,  render: numSort }, // Cantidad
      { targets: 7,  render: numSort }, // Precio compra
      { targets: 8,  render: numSort }, // Precio venta
      { targets: 11, render: numSort }, // Stock mínimo
      // Fecha ingreso (col 10 visible) usa data-order="YYYY-MM-DD" en el HTML
    ]
  });

  // Helper para ordenar numéricos con símbolos/formato
  function numSort(data, type) {
    if (type === 'sort' || type === 'type') {
      const n = parseFloat(String(data).replace(/[^0-9.-]/g, ''));
      return isNaN(n) ? 0 : n;
    }
    return data;
  }

  // --- Búsqueda normalizada (input propio)
  const norm = s => (s || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
  const $input = $('#buscarInventario');
  const $btn   = $('#btnBuscarInventario');
  const doSearch = () => tabla.search( norm($input.val()) ).draw();
  $input.on('input', doSearch);
  $btn.on('click', doSearch);

  // --- Orden externo
  const applyOrder = () => {
    // (C) RESTAR 1 aquí también
    const col = ((parseInt($crit.val(), 10) || 2) - 1);
    const dir = $dir.val() === 'asc' ? 'asc' : 'desc';
    tabla.order([col, dir]).draw();
  };
  $crit.on('change', applyOrder);
  $dir.on('change', applyOrder);

  // --- Filtros: Estado y Recientes (se mantienen igual)
  let filtroEstado = $('#filtroEstado').val(); // 'todos' | 'activo' | 'inactivo' | 'sinstock'
  let filtroRecientes = null;

  $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
    if (settings.nTable.id !== 'tablaInventario') return true;

    const row = tabla.row(dataIndex).node();

    // Estado
    const isInactivo = $(row).hasClass('table-secondary') || $(row).hasClass('text-muted');
    const cantidadTxt = data[6] || '0';
    const cantidad = parseFloat(String(cantidadTxt).replace(/[^0-9.-]/g,'')) || 0;
    if (filtroEstado === 'activo'   && isInactivo) return false;
    if (filtroEstado === 'inactivo' && !isInactivo) return false;
    if (filtroEstado === 'sinstock' && cantidad !== 0) return false;

    // Recientes (col 11 visible -> nth-child(11) 1-based)
    if (filtroRecientes instanceof Date) {
      const cell = row.querySelector('td:nth-child(11)');
      const iso = cell ? cell.getAttribute('data-order') : null; // "YYYY-MM-DD"
      if (!iso) return false;
      const d = new Date(iso + 'T00:00:00');
      if (isNaN(d.getTime())) return false;
      if (d < filtroRecientes) return false;
    }

    return true;
  });

  $('#filtroEstado').on('change', function() {
    filtroEstado = this.value || 'todos';
    tabla.draw();
  });

  const getUmbral = (dias) => {
    const n = parseInt(dias,10) || 0;
    const now = new Date();
    const ref = new Date(now.getFullYear(), now.getMonth(), now.getDate());
    ref.setDate(ref.getDate() - n);
    return ref;
  };

  $('#btnVerRecientes').on('click', function() {
    const dias = $('#diasRecientes').val();
    filtroRecientes = getUmbral(dias);
    tabla.draw();
  });

  $('#btnVerTodos').on('click', function() {
    filtroRecientes = null;
    tabla.draw();
  });
});

// ---------------------------- INVENTARIO: CAMBIAR ESTADO (ACTIVAR/INACTIVAR) --------------------------------------
$(document).on("click", ".btnCambiarEstado", function () {

  var idProducto  = $(this).attr("idProducto");
  var nuevoEstado = $(this).attr("nuevoEstado");
  var accion = (nuevoEstado == "0") ? "inactivar" : "activar";

  Swal.fire({
    title: '¿Está seguro?',
    text: "El producto será " + accion + "do.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, ' + accion
  }).then((result) => {
    if (!result.isConfirmed) return;

    var datos = new FormData();
    datos.append("idProducto", idProducto);
    datos.append("nuevoEstado", nuevoEstado);

    $.ajax({
      url: URL_PRODUCTOS_AJAX,  
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      success: function (respuesta) {
        console.log("SUCCESS =>", respuesta);
        if (respuesta && respuesta.trim() === "ok") {
          Swal.fire('Hecho!', 'El producto ha sido ' + accion + 'do.', 'success')
               .then(() => window.location = "inventario");
        } else {
          Swal.fire('Error', 'No se pudo ' + accion + ' el producto.', 'error');
        }
      },
      error: function (xhr, status, err) {
        console.log("ERROR =>", status, err, xhr.responseText);
        Swal.fire('Error', 'Fallo de comunicación con el servidor.', 'error');
      }
    });
  });
});

// ---------------------------- INVENTARIO: FILTRAR POR ESTADO (AJAX) --------------------------------------
$("#filtroEstado").change(function(){
  var estado = $(this).val();

  $.ajax({
    url: "ajax/productos.ajax.php",
    method: "POST",
    data: { accion: "filtrarEstado", estado: estado },
    dataType: "json",
    success: function(respuesta){
      var tbody = $("#tablaInventario tbody");
      tbody.empty();

      if (!respuesta || !respuesta.length) {
        // AHORA la tabla tiene 14 columnas (se agregó Fecha de vencimiento)
        tbody.append('<tr><td colspan="14" class="text-center text-muted">Sin resultados</td></tr>');
        return;
      }

      respuesta.forEach(function(p){
        var inactivo = parseInt(p.estado || 0) === 0;
        var img = p.imagen && p.imagen.length ? p.imagen : 'vistas/imagenes/sinfoto.png';

        // Fecha INGRESO: ordenable + legible
        var fechaISO = "";
        var fechaCL  = "-";
        if (p.fecha_ingreso && p.fecha_ingreso !== "0000-00-00") {
          var ts = new Date(p.fecha_ingreso);
          fechaISO = ts.toISOString().slice(0,10); // yyyy-mm-dd
          fechaCL  = ts.toLocaleDateString("es-CL");
        }

        // NUEVO: Fecha VENCIMIENTO: ordenable + legible + clase si vencido
        var vencISO = "";
        var vencCL  = "-";
        var claseVenc = "";
        if (p.fecha_vencimiento && p.fecha_vencimiento !== "0000-00-00") {
          var tv = new Date(p.fecha_vencimiento + "T00:00:00");
          if (!isNaN(tv.getTime())) {
            vencISO = tv.toISOString().slice(0,10);
            vencCL  = tv.toLocaleDateString("es-CL");
            // marcar en rojo si está vencido
            var hoy = new Date(); hoy.setHours(0,0,0,0);
            if (tv < hoy) claseVenc = "text-danger fw-bold";
          }
        }

        // Botón dinámico según estado
        var botonAccion = inactivo
          ? `<button class="btn btn-success btn-sm btnCambiarEstado"
                      idProducto="${p.id}" nuevoEstado="1" title="Activar producto">
                 <i></i> Activar
             </button>`
          : `<button class="btn btn-danger btn-sm btnCambiarEstado"
                      idProducto="${p.id}" nuevoEstado="0" title="Inactivar producto">
                 <i></i> Inactivar
             </button>`;

        // RESPETA EL ORDEN DEL THEAD
        var fila = `
          <tr class="${inactivo ? 'table-secondary text-muted' : ''}">
            <!-- Imagen -->
            <td class="text-center">
              <img src="${img}" style="width:100px;height:100px;object-fit:cover"
                   class="img-thumbnail"
                   alt="Foto producto"
                   onerror="this.onerror=null;this.src='vistas/imagenes/sinfoto.png';">
            </td>
            <!-- Código de barras -->
            <td>${p.codigo}</td>
            <!-- Nombre -->
            <td>${p.nombre}</td>
            <!-- Formato -->
            <td>${p.formato || ''}</td>
            <!-- Tamaño -->
            <td>${p.tamano || ''}</td>
            <!-- Marca -->
            <td>${p.marca || ''}</td>
            <!-- Cantidad -->
            <td>${p.cantidad}</td>
            <!-- Precio compra -->
            <td>${p.precio_compra}</td>
            <!-- Precio venta -->
            <td>${p.precio_venta}</td>
            <!-- Proveedor -->
            <td>${p.proveedor || ''}</td>
            <!-- Fecha ingreso -->
            <td ${fechaISO ? `data-order="${fechaISO}"` : ''}>${fechaCL}</td>
            <!-- Fecha vencimiento (NUEVA) -->
            <td ${vencISO ? `data-order="${vencISO}"` : ''} class="${claseVenc}">${vencCL}</td>
            <!-- Stock mínimo -->
            <td>${p.stock_minimo}</td>
            <!-- Acciones -->
            <td class="text-center">${botonAccion}</td>
          </tr>`;
        tbody.append(fila);
      });
    }
  });
});

// ---------------------------- INVENTARIO: RENDERIZAR LISTA FILTRADA (HELPERS + UI) --------------------------------------
// Helpers
const esc = s => String(s ?? '').replace(/[&<>"']/g, m => ({
  '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'
}[m]));
const fmtInt = n => isFinite(+n) ? (+n).toLocaleString('es-CL') : '0';
const fmtCLP = n => isFinite(+n) ? (+n).toLocaleString('es-CL') : '0';
const fmtFecha = s => {
  if (!s || s.startsWith('0000-00-00') || isNaN(Date.parse(s))) return { iso:'', cl:'-' };
  const d = new Date(s);
  return { iso: d.toISOString().slice(0,10), cl: d.toLocaleDateString('es-CL') };
};

// ---------------------------- INVENTARIO: RENDER CON RESALTADO ----------------------------
function renderInventarioFiltrado(lista, diasRecientes){
  const $tbody = $("#tablaInventario tbody");
  $tbody.empty();

  if (!lista || !lista.length) {
    $tbody.append('<tr><td colspan="14" class="text-center text-muted py-4">Sin resultados</td></tr>');
    return;
  }

  // Si se pasó un número de días, calculamos el umbral
  let umbral = null;
  if (diasRecientes && parseInt(diasRecientes, 10) > 0) {
    const n = parseInt(diasRecientes, 10);
    const hoy = new Date();
    umbral = new Date(hoy.getFullYear(), hoy.getMonth(), hoy.getDate());
    umbral.setDate(umbral.getDate() - n);
  }

  lista.forEach(function(p){
    const inactivo = parseInt(p?.estado ?? 0, 10) === 0;
    const img = (p?.imagen && p.imagen.length) ? p.imagen : 'vistas/imagenes/sinfoto.png';

    // Fechas
    const { iso: fechaISO, cl: fechaCL } = fmtFecha(p?.fecha_ingreso);
    const { iso: vencISO,  cl: vencCL } = fmtFecha(p?.fecha_vencimiento);

    // Clase para vencidos
    let claseVenc = '';
    if (vencISO) {
      const hoy = new Date(); hoy.setHours(0,0,0,0);
      const tv  = new Date(vencISO + 'T00:00:00');
      if (!isNaN(tv.getTime()) && tv < hoy) {
        claseVenc = 'text-danger fw-bold';
      }
    }

    // ¿Es reciente según el umbral?
    let esReciente = false;
    if (umbral && fechaISO) {
      const fi = new Date(fechaISO + 'T00:00:00');
      if (!isNaN(fi.getTime()) && fi >= umbral) {
        esReciente = true;
      }
    }

    const btnAccion = inactivo
      ? `<button class="btn btn-success btn-sm btnCambiarEstado" idProducto="${esc(p?.id)}" nuevoEstado="1" title="Activar producto">
           <i class="fa fa-check me-1"></i> Activar
         </button>`
      : `<button class="btn btn-danger btn-sm btnCambiarEstado" idProducto="${esc(p?.id)}" nuevoEstado="0" title="Inactivar producto">
           <i class="fa fa-ban me-1"></i> Inactivar
         </button>`;

    const claseFila = [
      inactivo ? 'table-secondary text-muted' : '',
      esReciente ? 'fila-reciente' : ''
    ].join(' ').trim();

    // Armado de fila (14 columnas)
    const fila = `
      <tr class="${claseFila}">
        <td class="text-center">
          <img src="${esc(img)}" class="img-thumbnail"
               style="width:100px;height:100px;object-fit:cover"
               alt="Foto producto"
               onerror="this.onerror=null;this.src='vistas/imagenes/sinfoto.png';">
        </td>
        <td>${esc(p?.codigo)}</td>
        <td>${esc(p?.nombre)}</td>
        <td>${esc(p?.formato)}</td>
        <td>${esc(p?.tamano)}</td>
        <td>${esc(p?.marca)}</td>
        <td>${fmtInt(p?.cantidad)}</td>
        <td>${fmtCLP(p?.precio_compra)}</td>
        <td>${fmtCLP(p?.precio_venta)}</td>
        <td>${esc(p?.proveedor)}</td>
        <td ${fechaISO ? `data-order="${fechaISO}"` : ''}>${esc(fechaCL)}</td>
        <td ${vencISO  ? `data-order="${vencISO}"`   : ''} class="${claseVenc}">${esc(vencCL)}</td>
        <td>${fmtInt(p?.stock_minimo)}</td>
        <td class="text-center text-nowrap">${btnAccion}</td>
      </tr>`;

    $tbody.append(fila);
  });
}

function renderInventarioFiltrado(lista, resaltarRecientes){
  const $tbody = $("#tablaInventario tbody");
  $tbody.empty();

  if (!lista || !lista.length) {
    // La tabla ahora tiene 14 columnas (se agregó Fecha de vencimiento)
    $tbody.append('<tr><td colspan="14" class="text-center text-muted py-4">Sin resultados</td></tr>');
    return;
  }

  lista.forEach(function(p){
    const inactivo = parseInt(p?.estado ?? 0, 10) === 0;
    const img = (p?.imagen && p.imagen.length) ? p.imagen : 'vistas/imagenes/sinfoto.png';

    // Fechas formateadas
    const { iso: fechaISO, cl: fechaCL } = fmtFecha(p?.fecha_ingreso);
    const { iso: vencISO,  cl: vencCL  } = fmtFecha(p?.fecha_vencimiento);

    // Clase para vencidos (rojo si vencido)
    let claseVenc = '';
    if (vencISO) {
      const hoy = new Date(); hoy.setHours(0,0,0,0);
      const tv  = new Date(vencISO + 'T00:00:00');
      if (!isNaN(tv.getTime()) && tv < hoy) {
        claseVenc = 'text-danger fw-bold';
      }
    }

    const btnAccion = inactivo
      ? `<button class="btn btn-success btn-sm btnCambiarEstado" idProducto="${esc(p?.id)}" nuevoEstado="1" title="Activar producto">
           <i class="fa fa-check me-1"></i> Activar
         </button>`
      : `<button class="btn btn-danger btn-sm btnCambiarEstado" idProducto="${esc(p?.id)}" nuevoEstado="0" title="Inactivar producto">
           <i class="fa fa-ban me-1"></i> Inactivar
         </button>`;

    const claseFila = [
      inactivo ? 'table-secondary text-muted' : '',
      resaltarRecientes ? 'fila-reciente' : ''
    ].join(' ').trim();

    const fila = `
      <tr class="${claseFila}">
        <td class="text-center">
          <img src="${esc(img)}" class="img-thumbnail"
               style="width:100px;height:100px;object-fit:cover"
               alt="Foto producto"
               onerror="this.onerror=null;this.src='vistas/imagenes/sinfoto.png';">
        </td>
        <td>${esc(p?.codigo)}</td>
        <td>${esc(p?.nombre)}</td>
        <td>${esc(p?.formato)}</td>
        <td>${esc(p?.tamano)}</td>
        <td>${esc(p?.marca)}</td>
        <td>${fmtInt(p?.cantidad)}</td>
        <td>${fmtCLP(p?.precio_compra)}</td>
        <td>${fmtCLP(p?.precio_venta)}</td>
        <td>${esc(p?.proveedor)}</td>
        <td ${fechaISO ? `data-order="${fechaISO}"` : ''}>${esc(fechaCL)}</td>
        <td ${vencISO  ? `data-order="${vencISO}"`   : ''} class="${claseVenc}">${esc(vencCL)}</td>
        <td>${fmtInt(p?.stock_minimo)}</td>
        <td class="text-center text-nowrap">${btnAccion}</td>
      </tr>`;

    $tbody.append(fila);
  });
}

// ---------------------------- INVENTARIO: BOTÓN "VER RECIENTES" --------------------------------------
$("#btnVerRecientes").on("click", function(){
  const dias = $("#diasRecientes").val() || 7;
  $.ajax({
    url: "ajax/productos.ajax.php",
    method: "POST",
    data: { accion: "getRecientes", dias: dias, limite: 100 },
    dataType: "json",
    success: function(r){
      if (r && r.status === "ok") {
        renderInventarioFiltrado(r.data, true); // resalta recientes
      } else {
        swal({ title: "Error", text: "No fue posible cargar los recientes", type: "error" });
      }
    },
    error: function(){
      swal({ title: "Error", text: "Fallo de comunicación con el servidor.", type: "error" });
    }
  });
});

// ---------------------------- INVENTARIO: BOTÓN "VER TODOS" --------------------------------------
$("#btnVerTodos").on("click", function(){
  $("#filtroEstado").val("todos").trigger("change");
});

// ---------------------------- MODAL STOCK MÍNIMO: GESTIÓN MASIVA --------------------------------------
(function(){
  var $tabla    = document.getElementById('tablaProductosMin');
  var $buscar   = document.getElementById('buscarProductoTabla');
  var $btnSave  = document.getElementById('btnGuardarMinimo');
  var $checkAll = document.getElementById('checkAll');
  var $countSel = document.getElementById('countSel');
  var $minMas   = document.getElementById('minMasivo');
  var $btnMas   = document.getElementById('btnAplicarMasivo');
  var $msg      = document.getElementById('msgAplicar');

  var aplicadoMasivo = false; // <-- obligatorio presionar Aplicar

  function filasVisibles(){
    return Array.from($tabla.tBodies[0].rows).filter(function(r){ return r.style.display !== 'none'; });
  }
  function numValOrNull(v){
    v = (v==null?'':String(v)).trim();
    if (v==='') return null;
    var n = Number(v);
    return (isFinite(n) && n >= 0) ? n : null;
  }
  function actualizarControles(){
    var seleccionados = $tabla.tBodies[0].querySelectorAll('input.chk:checked').length;
    var alguno = seleccionados > 0;

    // Guardar sólo si hubo Aplicar
    $btnSave.disabled = !(alguno && aplicadoMasivo);

    // Botón Aplicar requiere selección + valor válido
    var valMas = numValOrNull($minMas ? $minMas.value : '');
    if ($btnMas) $btnMas.disabled = !(alguno && valMas !== null);

    // Contador
    if ($countSel) $countSel.textContent = String(seleccionados);

    // Mostrar mensaje si hay selección pero aún no se aplicó
    if ($msg) $msg.style.display = (alguno && !aplicadoMasivo) ? 'block' : 'none';

    // Estado del checkAll sobre visibles
    if ($checkAll){
      var visibles = filasVisibles();
      var visiblesChk = visibles.filter(function(tr){ var c=tr.querySelector('input.chk'); return c && c.checked; }).length;
      $checkAll.indeterminate = (visiblesChk>0 && visiblesChk<visibles.length);
      $checkAll.checked = (visibles.length>0 && visiblesChk===visibles.length);
    }
  }

  // Buscar
  if ($buscar){
    $buscar.addEventListener('input', function(){
      var q = this.value.trim().toLowerCase();
      Array.from($tabla.tBodies[0].rows).forEach(function(tr){
        var txt = (tr.dataset.codigo+' '+tr.dataset.nombre+' '+tr.dataset.marca+' '+tr.dataset.formato+' '+tr.dataset.tamano).toLowerCase();
        tr.style.display = (q==='' || txt.indexOf(q)!==-1) ? '' : 'none';
      });
      actualizarControles();
    });
  }

  // Click en fila (toggle)
  $tabla.addEventListener('click', function(e){
    var tr = e.target.closest('tr.fila-prod');
    if (!tr) return;
    if (e.target.matches('input, .form-control')) return;
    var chk = tr.querySelector('input.chk');
    if (!chk) return;
    chk.checked = !chk.checked;
    chk.dispatchEvent(new Event('change'));
  });

  // Cambios de selección
  $tabla.addEventListener('change', function(e){
    if (e.target && e.target.matches('input.chk')){
      var tr = e.target.closest('tr.fila-prod');
      var inputMin = tr.querySelector('.min-input');
      if (e.target.checked){
        tr.classList.add('seleccionado');
        if (inputMin) inputMin.disabled = false;
      } else {
        tr.classList.remove('seleccionado');
        if (inputMin) inputMin.disabled = true;
      }
      // Si cambian la selección después de aplicar, volvemos a exigir "Aplicar"
      if (aplicadoMasivo) aplicadoMasivo = false;
      actualizarControles();
    }
  });

  // Editar mínimos manualmente: también invalida el "aplicado"
  $tabla.addEventListener('input', function(e){
    if (e.target && e.target.matches('.min-input')){
      if (aplicadoMasivo) aplicadoMasivo = false;
      actualizarControles();
    }
  });

  // Seleccionar todo (visibles)
  if ($checkAll){
    $checkAll.addEventListener('change', function(){
      filasVisibles().forEach(function(tr){
        var chk = tr.querySelector('input.chk');
        var inMin = tr.querySelector('.min-input');
        if (!chk) return;
        chk.checked = $checkAll.checked;
        if (inMin) inMin.disabled = !chk.checked;
        tr.classList.toggle('seleccionado', chk.checked);
      });
      if (aplicadoMasivo) aplicadoMasivo = false;
      actualizarControles();
    });
  }

  // Aplicar masivo (obligatorio)
  function aplicarMasivo(){
    var val = numValOrNull($minMas ? $minMas.value : null);
    if (val === null) return;
    var seleccionados = $tabla.tBodies[0].querySelectorAll('input.chk:checked');
    seleccionados.forEach(function(chk){
      var tr = chk.closest('tr.fila-prod');
      var inMin = tr.querySelector('.min-input');
      if (inMin){ inMin.value = String(val); inMin.disabled = false; }
    });
    aplicadoMasivo = true;  // <-- habilita Guardar
    actualizarControles();
  }
  if ($btnMas){
    $btnMas.addEventListener('click', aplicarMasivo);
  }
  if ($minMas){
    $minMas.addEventListener('keydown', function(e){
      if (e.key === 'Enter'){ e.preventDefault(); if (!$btnMas.disabled) aplicarMasivo(); }
    });
    $minMas.addEventListener('input', actualizarControles);
  }

  // Envío: valida selección y fuerza habilitar inputs seleccionados
  var form = document.querySelector('#modalConfigStockMinimo form');
  if (form){
    form.addEventListener('submit', function(e){
      var alguno = $tabla.tBodies[0].querySelector('input.chk:checked');
      if (!alguno || !aplicadoMasivo){
        e.preventDefault();
        // pista adicional
        if ($msg) { $msg.style.display='block'; $msg.classList.add('text-danger'); }
        return false;
      }
      // habilitar mínimos seleccionados
      $tabla.tBodies[0].querySelectorAll('tr.fila-prod input.chk:checked').forEach(function(chk){
        var inMin = chk.closest('tr').querySelector('.min-input');
        if (inMin) inMin.disabled = false;
      });
    });
  }

  // Init
  actualizarControles();
})();
