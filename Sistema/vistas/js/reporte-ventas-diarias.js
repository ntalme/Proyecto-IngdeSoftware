// ---------------------------- REPORTE: VENTAS POR HORA (TABLA + GRÁFICO) --------------------------------------
const URL_REPORTES_AJAX = "ajax/reportes.ajax.php";

$(function () {
  const tabla = $("#tablaVentasPorHora").DataTable({
    language: { 
      // Carga archivo oficial en español (déjalo así si ya lo tienes)
      url: "vistas/plugins/datatables/spanish.json",

      // Fallback: por si no se encuentra el archivo, mantén todo en español
      decimal: ",",
      thousands: ".",
      processing: "Procesando...",
      search: "Buscar:",
      lengthMenu: "Mostrar _MENU_ registros",
      info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
      infoEmpty: "Mostrando 0 a 0 de 0 registros",
      infoFiltered: "(filtrado de _MAX_ registros en total)",
      loadingRecords: "Cargando...",
      zeroRecords: "No se encontraron registros coincidentes",
      emptyTable: "No hay ventas registradas en la fecha seleccionada",
      paginate: { first: "Primero", previous: "Anterior", next: "Siguiente", last: "Último" },
      aria: {
        sortAscending:  ": activar para ordenar la columna de manera ascendente",
        sortDescending: ": activar para ordenar la columna de manera descendente"
      }
    },
    order: [[0, "asc"]],
    pageLength: 24,
    lengthMenu: [24, 48],
    columnDefs: [
      { targets: [1], className: "text-right" },
      { targets: [2], className: "text-right" },
      { targets: [3], className: "text-right" }
    ]
  });

  // Formato CLP en español de Chile
  const clp = n => new Intl.NumberFormat('es-CL', {
    style:'currency', currency:'CLP', maximumFractionDigits:0
  }).format(n ?? 0);

  let chartHoras = null;

  // Reinicia el reporte (limpia tabla y oculta el gráfico)
  function resetReporte() {
    tabla.clear().draw();
    $("#resumenDia").hide();
    $("#rFecha,#rVentas,#rMonto,#rTicket,#rHoraPico").text("—");
    $("#wrapGrafico").hide();
    if (chartHoras) { chartHoras.destroy(); chartHoras = null; }
  }

  // Si usas Chart.js, usa estas opciones para ejes/tooltip en español:
  // (Al crear el gráfico, pasa 'options: chartOptsEs')
  const chartOptsEs = {
    plugins: {
      legend: { labels: { usePointStyle: true } },
      tooltip: {
        callbacks: {
          label: (ctx) => `Monto: ${clp(ctx.parsed.y)}`
        }
      }
    },
    scales: {
      x: { title: { display: true, text: "Hora" } },
      y: { title: { display: true, text: "Monto (CLP)" } }
    }
  };

  $("#formReporteVentasDia").on("submit", function(e){
    e.preventDefault();

    const fecha = $("#fechaReporte").val();
    if(!fecha){
      Swal.fire("Atención","Debe seleccionar una fecha.","warning");
      return;
    }

    const datos = new FormData();
    datos.append("accion", "ventasDiariasPorHora");
    datos.append("fecha", fecha);

    $.ajax({
      url: URL_REPORTES_AJAX,
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      beforeSend(){
        $("#wrapGrafico").hide();      // ocultar mientras carga
        Swal.fire({title:"Consultando...",allowOutsideClick:false,didOpen:()=>Swal.showLoading()});
      },
      success(resp){
        Swal.close();

        if(!resp || resp.status !== "ok"){
          Swal.fire("Error", (resp && resp.msg) ? resp.msg : "No fue posible obtener el reporte.", "error");
          return;
        }

        const resumen = resp.resumen || {};
        const sinVentas =
          (+resumen.ventas === 0) ||
          !resp.data ||
          (Array.isArray(resp.data) && resp.data.every(h => (+h.ventas === 0 && +h.monto_total === 0)));

        if (sinVentas) {
          resetReporte();
          // ↓↓↓ Dejar explícitamente todos los datos en 0 si no hubo ventas ↓↓↓
          $("#rVentas").text("0");
          $("#rMonto").text(clp(0));
          $("#rTicket").text(clp(0));
          $("#rHoraPico").text("—");
          $("#tVentas").text("0");
          $("#tMonto").text(clp(0));
          $("#tTicket").text(clp(0));
          $("#rFecha").text( new Date(fecha + "T00:00:00").toLocaleDateString("es-CL") );
          // ↑↑↑ Fin del ajuste de ceros ↑↑↑
          Swal.fire("Sin ventas", "No hubo ventas ese día.", "info");
          return; // no llenar nada
        }

        // Hay ventas: mostrar resumen y gráfico
        $("#resumenDia").show();
        $("#wrapGrafico").show();       // ✅ ahora sí se muestra el gráfico

        $("#rFecha").text( new Date(resumen.fecha+"T00:00:00").toLocaleDateString("es-CL") );
        $("#rVentas").text( (resumen.ventas||0).toLocaleString('es-CL') );
        $("#rMonto").text( clp(resumen.monto_dia||0) );
        $("#rTicket").text( clp(resumen.ticket_prom||0) );
        $("#rHoraPico").text( (resumen.hora_pico ?? "-") );

        tabla.clear();
        let tv=0, tm=0;

        (resp.data||[]).forEach(h=>{
          const hora    = (h.hora ?? 0).toString().padStart(2,'0') + ":00";
          const ventas  = parseInt(h.ventas||0);
          const monto   = parseFloat(h.monto_total||0);
          const ticket  = parseFloat(h.ticket_prom||0);
          tv += ventas; tm += monto;

          tabla.row.add([hora, ventas.toLocaleString('es-CL'), clp(monto), clp(ticket)]);
        });

        tabla.draw();
        $("#tVentas").text(tv.toLocaleString('es-CL'));
        $("#tMonto").text(clp(tm));
        $("#tTicket").text( tv>0 ? clp(tm/tv) : clp(0) );

        const labels  = (resp.data||[]).map(h => (h.hora??0).toString().padStart(2,'0'));
        const valores = (resp.data||[]).map(h => parseFloat(h.monto_total||0));
        const ctx = document.getElementById("graficoHoras").getContext("2d");
        if (chartHoras) chartHoras.destroy();
        chartHoras = new Chart(ctx, {
          type: "bar",
          data: { labels, datasets: [{ label: "Monto por hora (CLP)", data: valores }] },
          options: {
            responsive: true,
            plugins: { legend: { display: true } },
            scales: {
              x: { title: { display: true, text: "Hora" } },
              y: { title: { display: true, text: "Monto (CLP)" },
                   ticks: { callback: v => clp(v).replace("$","$ ") } }
            }
          }
        });

      },
      error(xhr){
        Swal.close();
        $("#wrapGrafico").hide();
        console.error("AJAX error:", xhr.status, xhr.responseText);
        Swal.fire("Error","Ocurrió un problema al consultar el reporte.","error");
      }
    });

  });

});
