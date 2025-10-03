// ---------------------------- TOOLTIPS (BOOTSTRAP) --------------------------------------
$('[data-toggle="tooltip"]').tooltip({
  animation: false,
  delay: { show: 0, hide: 100 }
});

// ---------------------------- REGISTRAR PÉRDIDA (MODAL NAVBAR) --------------------------------------
(function() {
  const $modal   = $('#modalNavbarPerdida');
  const $tabla   = $('#tablaProductosNavbar');
  const $buscar  = $('#buscarProductoNavbar');
  const $hidden  = $('#productoNavbarId');      // <input type="hidden" name="producto_id" id="productoNavbarId">
  const $stockB  = $('#badgeStock');
  const $ayuda   = $('#ayudaStock');
  const $cantIn  = $('#cantidadInput');
  const $errCant = $('#errorCantidad');
  const $btnReg  = $('#btnRegistrarPerdida');
  const $form    = $('#formNavbarPerdida');

  function getSelId() {
    // lee del hidden; si no, del dataset guardado en la tabla (fallback)
    return $hidden.val() || $tabla.data('selectedId') || '';
  }

  function setSeleccion(id, stock) {
    $hidden.val(id);
    $tabla.data('selectedId', String(id));

    // UI
    $stockB.text('Stock: ' + stock);
    $ayuda.show();

    // Max de cantidad
    $cantIn.attr('max', stock);
    const v = parseInt($cantIn.val() || 0, 10);
    if (v > stock) $cantIn.val(stock ? stock : '');

    $errCant.hide();
    actualizarBoton();
  }

  function actualizarBoton() {
    const tieneId = !!getSelId();
    const cant = parseInt($cantIn.val() || 0, 10);
    const max  = parseInt($cantIn.attr('max') || 999999, 10);
    const okCantidad = cant >= 1 && cant <= max;
    $btnReg.prop('disabled', !(tieneId && okCantidad));
  }

  // Buscar en tabla
  $buscar.on('input', function() {
    const q = $(this).val().trim().toLowerCase();
    $tabla.find('tbody tr').each(function() {
      const tr = $(this);
      const txt = (tr.data('codigo') + ' ' + tr.data('nombre') + ' ' + tr.data('marca') + ' ' + tr.data('formato') + ' ' + tr.data('tamano')).toLowerCase();
      tr.toggle(txt.indexOf(q) !== -1 || q === '');
    });
  });

  // Click en fila: selecciona
  $tabla.on('click', 'tbody tr.fila-prod-navbar', function() {
    const $tr = $(this);

    // marcar visualmente
    $tabla.find('tbody tr').removeClass('seleccionado');
    $tr.addClass('seleccionado');

    // set selección
    const id    = $tr.data('id');
    const stock = parseInt($tr.data('stock') || 0, 10);
    if (!id) return;
    setSeleccion(id, stock);
  });

  // Validación cantidad <= stock
  $cantIn.on('input', function() {
    const valor = parseInt($(this).val() || 0, 10);
    const max   = parseInt($(this).attr('max') || 999999, 10);
    const invalida = (valor < 1 || valor > max);
    $errCant.toggle(invalida);
    actualizarBoton();
  });

  // Envío AJAX
  $form.on('submit', function(e) {
    e.preventDefault();

    const productoId = getSelId();
    const cantidad   = parseInt($cantIn.val() || 0, 10);
    const max        = parseInt($cantIn.attr('max') || 999999, 10);

    if (!productoId) {
      Swal.fire('Atención', 'Debes seleccionar un producto', 'warning');
      return;
    }
    if (!cantidad || cantidad < 1) {
      Swal.fire('Atención', 'La cantidad debe ser mayor a 0', 'warning');
      return;
    }
    if (cantidad > max) {
      Swal.fire('Error', 'La cantidad no puede superar el stock disponible', 'error');
      return;
    }

    // Datos para el backend
    const datos = new FormData();
    datos.append('accion', 'registrarPC');
    datos.append('producto_id', productoId);
    datos.append('cantidad', cantidad);
    datos.append('motivo', $('#motivoSelect').val());
    datos.append('observacion', $('textarea[name="observacion"]').val());

    $.ajax({
      url: 'ajax/registropc.ajax.php',
      method: 'POST',
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType: 'json',
      success: function(resp) {
        if (resp && resp.status === 'ok') {
          Swal.fire('Éxito', 'Registro guardado correctamente', 'success')
            .then(() => {
              // mejor PRG: ir a GET de la misma página
              window.location.replace(window.location.pathname + window.location.search);
            });
          $('#modalNavbarPerdida').modal('hide');
          $form[0].reset();
          $ayuda.hide();
        } else {
          Swal.fire('Error', (resp && resp.message) || 'No se pudo registrar', 'error');
        }
      },
      error: function(xhr, status, error) {
        Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
        console.error('AJAX Error:', status, error);
      }
    });
  });

  // Reset al abrir el modal
  $modal.on('shown.bs.modal', function() {
    $buscar.val('');
    $cantIn.val('').removeAttr('max');
    $hidden.val('');
    $tabla.removeData('selectedId');
    $ayuda.hide();
    $stockB.text('Stock: 0');
    $tabla.find('tbody tr').removeClass('seleccionado').show();
    actualizarBoton();
  });

  // init
  actualizarBoton();
})();

