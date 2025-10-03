// ---------------------------- CONSTANTES / ENDPOINTS --------------------------------------
const URL_REPORTES_AJAX = "ajax/reportes.ajax.php";

// ---------------------------- REPORTE: TABLA PRODUCTOS VENDIDOS (INIT + BÚSQUEDA) --------------------------------------
$(function () {
  const tabla = $("#tablaProductosVendidos").DataTable({
    searching: false, // 👈 esto borra el buscador nativo
    language: {
      url: "vistas/plugins/datatables/spanish.json",
      emptyTable: "No hay productos vendidos en la fecha seleccionada"
    },
    order: [[0, "asc"]],
    pageLength: 25
  });

  // Buscar mientras escribe
  $("#buscarVentas").on("keyup", function () {
    tabla.search(this.value).draw();
  });

  // Buscar al presionar el botón
  $("#btnBuscarVentas").on("click", function () {
    tabla.search($("#buscarVentas").val()).draw();
  });

  const formatearCLP = (num) => {
    if (num == null) return "$0";
    return new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP', maximumFractionDigits: 0 }).format(num);
  };

  // ---------------------------- REPORTE: CONSULTA POR DÍA (AJAX + RENDER FOOTER) --------------------------------------
  $("#formConsultaProductosDia").on("submit", function (e) {
    e.preventDefault();

    const fecha = $("#fechaConsulta").val();
    if (!fecha) {
      Swal.fire("Atención", "Debe seleccionar una fecha.", "warning");
      return;
    }

    const datos = new FormData();
    datos.append("accion", "productosVendidosPorDia");
    datos.append("fecha", fecha);

    $.ajax({
      url: URL_REPORTES_AJAX,
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function () {
        Swal.fire({
          title: "Consultando...",
          text: "Obteniendo datos de productos vendidos",
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading()
        });
      },
      success: function (resp) {
        Swal.close();

        if (!resp || resp.status !== "ok") {
          const msg = (resp && resp.msg) ? resp.msg : "No fue posible obtener el reporte.";
          Swal.fire("Error", msg, "error");
          return;
        }

        // ⬇️ Aviso cuando no hay ventas
        if (!resp.data || resp.data.length === 0) {
          tabla.clear().draw();
          $("#footCantidad").text("0");
          $("#footMonto").text("$0");

          Swal.fire({
            icon: "info",
            title: "Sin ventas",
            text: "No hubo ventas en la fecha seleccionada.",
            confirmButtonText: "Aceptar",
            confirmButtonColor: "#f7c738" // amarillo elegante
          });

          return;
        }

        tabla.clear();
        let totalCantidad = 0;
        let totalMonto = 0;

        (resp.data || []).forEach(row => {
          const idProd   = row.id_producto ?? "-";
          const nombre   = row.nombre_producto ?? "-";
          const formato  = row.formato ?? "-";
          const tamano   = row.tamano ?? "-";
          const cant     = parseFloat(row.cantidad_total || 0);
          const monto    = parseFloat(row.monto_total || 0);

          totalCantidad += cant;
          totalMonto += monto;

          tabla.row.add([
            idProd,
            nombre,
            formato,
            tamano,
            cant.toLocaleString('es-CL'),
            formatearCLP(monto)
          ]);
        });

        tabla.draw();
        $("#footCantidad").text(totalCantidad.toLocaleString('es-CL'));
        $("#footMonto").text(formatearCLP(totalMonto));
      },
      error: function (xhr) {
        Swal.close();
        console.error("Error AJAX:", xhr.status, xhr.responseText);
        Swal.fire("Error", "Ocurrió un problema al consultar el reporte.", "error");
      }
    });
  });
});

// ---------------------------- REPORTE: TOP PRODUCTOS DEL MES (GRÁFICO CIRCULAR) --------------------------------------
let chartTopMes = null;

function formatearCLP(n){ 
  return new Intl.NumberFormat('es-CL',{style:'currency',currency:'CLP',maximumFractionDigits:0}).format(n ?? 0);
}

function cargarTopMes() {
  const mes = $("#mesTop").val();
  const limite = parseInt($("#topLimite").val() || 10, 10); // 👈 Top 3/5/10
  if(!mes){
    Swal.fire("Atención","Seleccione un mes.","warning");
    return;
  }

  const fd = new FormData();
  fd.append("accion","topProductosMes");
  fd.append("mes", mes);
  fd.append("limite", limite);

  $.ajax({
    url: URL_REPORTES_AJAX,
    method: "POST",
    data: fd,
    cache: false,
    contentType: false,
    processData: false,
    beforeSend(){
      $("#wrapTopMes").hide();
    },
    success(resp){
      if(!resp || resp.status !== "ok"){
        Swal.fire("Error", (resp && resp.msg)? resp.msg : "No fue posible obtener el top.", "error");
        return;
      }

      let labels  = (resp.labels || []).slice(0, limite);
      let valores = (resp.cantidades || []).slice(0, limite);

      const sinData = !labels.length || valores.every(v => (+v) === 0);
      if (sinData){
        if (chartTopMes) { chartTopMes.destroy(); chartTopMes = null; }
        $("#wrapTopMes").hide();
        Swal.fire("Sin datos","No hay productos vendidos en el mes seleccionado.","info");
        return;
      }

      $("#wrapTopMes").show();

      const ctx = document.getElementById("graficoTopMes").getContext("2d");
      if (chartTopMes) chartTopMes.destroy();

      // registrar datalabels si no está
      if (!Chart.registry.plugins.get('datalabels')) {
        Chart.register(ChartDataLabels);
      }

      chartTopMes = new Chart(ctx, {
        type: "pie",
        data: {
          labels,
          datasets: [{
            data: valores,
            backgroundColor: [
              "#1E3A8A","#374151","#F59E0B","#6B7280","#2563EB",
              "#9CA3AF","#0F172A","#4B5563","#D97706","#111827"
            ],
            borderColor: "#ffffff",
            borderWidth: 2
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: "right",
              labels: {
                color: "#111827",
                font: { size: 13 },
                generateLabels: function(chart) {
                  const data = chart.data;
                  const vals = data.datasets[0].data;
                  const colors = data.datasets[0].backgroundColor || [];
                  return data.labels.map((lbl, i) => ({
                    text: `${lbl} — ${Number(vals[i] || 0).toLocaleString('es-CL')}`,
                    fillStyle: colors[i],
                    strokeStyle: colors[i],
                    hidden: isNaN(vals[i]) || vals[i] === null,
                    index: i
                  }));
                }
              }
            },
            title: {
              display: true,
              text: `Top ${limite} productos más vendidos del mes`,
              color: "#1E293B",
              font: { size: 16, weight: "bold" }
            },
            tooltip: {
              callbacks: {
                label: function(ctx){
                  const total = ctx.dataset.data.reduce((a,b)=>a + (+b||0), 0);
                  const val   = +ctx.parsed || 0;
                  const pct   = total ? ((val/total)*100).toFixed(1) : 0;
                  return `${ctx.label}: ${val.toLocaleString('es-CL')} (${pct}%)`;
                }
              }
            },
            datalabels: {
              color: "#fff",              // blanco para contrastar
              font: { weight: "bold", size: 13 },
              formatter: (value, context) => {
                const total = context.dataset.data.reduce((a,b)=>a + (+b||0), 0);
                if (!total) return "";
                const pct = Math.round((value / total) * 100);
                return `${pct}%`;       // 👈 porcentaje dentro
              },
              anchor: "center",
              align: "center"
            }
          }
        }
      });
    },
    error(xhr){
      console.error("AJAX error:", xhr.status, xhr.responseText);
      Swal.fire("Error", "Ocurrió un problema al consultar el top del mes.", "error");
    }
  });
}

// ---------------------------- REPORTE: TOP MES - EVENTOS --------------------------------------
$("#btnTopMes").on("click", cargarTopMes);

// ---------------------------- REPORTE: TOP MES - VALOR INICIAL DEL SELECTOR --------------------------------------
$(document).ready(function(){
  const hoy = new Date();
  const ym = hoy.getFullYear() + "-" + String(hoy.getMonth()+1).padStart(2,'0');
  $("#mesTop").val(ym);
});

