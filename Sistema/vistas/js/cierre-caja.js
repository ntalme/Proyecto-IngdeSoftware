// ---------------------------- MODAL VER VENTAS --------------------------------
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".verVentas").forEach(boton => {
    boton.addEventListener("click", () => {
      const ventasJSON = boton.getAttribute("data-json");
      let ventas;

      try {
        ventas = JSON.parse(ventasJSON);
      } catch {
        ventas = [];
      }

      const tbody = document.querySelector("#tablaVentas tbody");
      tbody.innerHTML = "";

      if (!ventas || ventas.length === 0) {
        tbody.innerHTML = "<tr><td colspan='5' class='text-muted'>No hay ventas registradas en este cierre.</td></tr>";
        return;
      }

      ventas.forEach(v => {
        const id = v.id_venta ?? v.id ?? "-";
        const fecha = v.fecha ?? "-";
        const total = v.total ? "$" + parseFloat(v.total).toLocaleString("es-CL") : "-";
        const metodo = v.metodo_pago ?? "-";

        let productos = "";

        try {
          const arr = typeof v.productos === "string" ? JSON.parse(v.productos) : v.productos;
          if (Array.isArray(arr)) {
            productos = arr.map(p => {
              const nombre = p.nombre ?? "Producto";
              const cantidad = p.cantidad ?? 1;
              const totalLinea = p.total_linea ? "$" + parseFloat(p.total_linea).toLocaleString("es-CL") : "";
              return `${nombre} (${cantidad}) ${totalLinea}`;
            }).join("<br>");
          } else {
            productos = JSON.stringify(v.productos);
          }
        } catch {
          productos = v.productos;
        }

        const fila = `
          <tr>
            <td>${id}</td>
            <td>${fecha}</td>
            <td>${total}</td>
            <td>${metodo}</td>
            <td class="text-left">${productos}</td>
          </tr>`;
        tbody.insertAdjacentHTML("beforeend", fila);
      });

      $("#modalVentas").modal("show");
    });
  });
});

// ---------------------------- MODAL VER RESUMEN --------------------------------
$(document).ready(function() {

  // Formateador CLP uniforme
  const formatoCLP = new Intl.NumberFormat("es-CL", {
    style: "currency",
    currency: "CLP",
    currencyDisplay: "symbol",
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  });

  // Evento: abrir modal resumen desde botón amarillo
  $(document).on('click', '.modalResumen', function() {
    const dataResumen = $(this).data('resumen');
    if (!dataResumen) return;

    // Asignar valores formateados
    $('#totalGeneral').text(formatoCLP.format(dataResumen.total_general || 0));
    $('#boletasDigitales').text(formatoCLP.format(dataResumen.boletas_digitales || 0));
    $('#retencionBoletas').text('-' + formatoCLP.format(dataResumen.retencion_boletas || 0));
    $('#retencionTarjeta').text('-' + formatoCLP.format(dataResumen.retencion_tarjeta || 0));
    $('#descuentoContadora').text('-' + formatoCLP.format(dataResumen.descuento_contadora || 0));
    $('#gananciaDia').text(formatoCLP.format(dataResumen.ganancia_dia || 0));
    $('#reinversion').text(formatoCLP.format(dataResumen.reinversion || 0));
    $('#ventasTarjeta').text(formatoCLP.format(dataResumen.ventas_tarjeta_monto || 0));
    $('#ventasEfectivo').text(formatoCLP.format(dataResumen.ventas_efectivo_monto || 0));

    // Mostrar modal
    $('#modalResumen').modal('show');
  });


  // ======================= OPCIONAL: MODAL TABLA DETALLE =======================
  document.querySelectorAll(".verResumen").forEach(btn => {
    btn.addEventListener("click", () => {
      const resumenData = JSON.parse(btn.getAttribute("data-resumen"));
      const tbody = document.getElementById("tablaResumenBody");
      if (!tbody) return;

      const filas = [
        { label: "Fecha del Cierre", value: resumenData.fecha_cierre ?? "-" },
        { label: "Total General", value: formatoCLP.format(resumenData.total_general ?? 0) },
        { label: "Boletas Digitales", value: formatoCLP.format(resumenData.boletas_digitales ?? 0) },
        { label: "Retención Boletas (20%)", value: "-" + formatoCLP.format(resumenData.retencion_boletas ?? 0) },
        { label: "Retención Tarjeta (20%)", value: "-" + formatoCLP.format(resumenData.retencion_tarjeta ?? 0) },
        { label: "Descuento Contadora", value: "-" + formatoCLP.format(resumenData.descuento_contadora ?? 0) },
        { label: "Total Final del Día", value: formatoCLP.format(resumenData.total_final ?? 0) },
        { label: "Ganancia del Día (20%)", value: formatoCLP.format(resumenData.ganancia_dia ?? 0) },
        { label: "Reinversión (80%)", value: formatoCLP.format(resumenData.reinversion ?? 0) },
        { label: "Observaciones", value: resumenData.observaciones ?? "-" }
      ];

      tbody.innerHTML = filas.map(f => `
        <tr>
          <td class="font-weight-bold">${f.label}</td>
          <td class="text-right">${f.value}</td>
        </tr>
      `).join("");

      $("#modalResumen").modal("show");
    });
  });
});

document.addEventListener("click", function (e) {
  if (!e.target.closest(".modalResumen")) return;

  const btn = e.target.closest(".modalResumen");
  const resumen = JSON.parse(btn.dataset.resumen);

  const formatoCLP = new Intl.NumberFormat("es-CL", {
    style: "currency",
    currency: "CLP",
    minimumFractionDigits: 0,
  });

  // Campos básicos
  document.getElementById("totalGeneral").textContent = formatoCLP.format(resumen.total_general || 0);
  document.getElementById("ventasTarjeta").textContent = formatoCLP.format(resumen.ventas_tarjeta_monto || 0);
  document.getElementById("ventasEfectivo").textContent = formatoCLP.format(resumen.ventas_efectivo_monto || 0);
  document.getElementById("boletasDigitales").textContent = formatoCLP.format(resumen.boletas_digitales || 0);
  document.getElementById("retencionBoletas").textContent = formatoCLP.format(resumen.retencion_boletas || 0);
  document.getElementById("retencionTarjeta").textContent = formatoCLP.format(resumen.retencion_tarjeta || 0);
  document.getElementById("descuentoContadora").textContent = formatoCLP.format(resumen.descuento_contadora || 0);
  document.getElementById("gananciaDia").textContent = formatoCLP.format(resumen.ganancia_dia || 0);
  document.getElementById("reinversion").textContent = formatoCLP.format(resumen.reinversion || 0);

  // 🔹 Campos del domingo (se mostrarán solo si aplica)
  const tarjetaReinversionSemanal = document.querySelector(".reinversion-semanal");
  const tarjetaDescuentoLuz = document.querySelector(".descuento-luz");
  const tarjetaReinversionFinal = document.querySelector(".reinversion-final");

  if (resumen.es_domingo == 1) {
    tarjetaReinversionSemanal.classList.remove("d-none");
    tarjetaDescuentoLuz.classList.remove("d-none");
    tarjetaReinversionFinal.classList.remove("d-none");

    document.getElementById("reinversionSemanal").textContent = formatoCLP.format(resumen.reinversion_semana || 0);
    document.getElementById("descuentoLuz").textContent = formatoCLP.format(resumen.descuento_luz || 0);
    document.getElementById("reinversionSemanalFinal").textContent = formatoCLP.format(resumen.reinversion_semana_final || 0);
  } else {
    // Si no es domingo, ocultar las tres tarjetas
    tarjetaReinversionSemanal.classList.add("d-none");
    tarjetaDescuentoLuz.classList.add("d-none");
    tarjetaReinversionFinal.classList.add("d-none");
  }

  // Mostrar modal
  $("#modalResumen").modal("show");
});
