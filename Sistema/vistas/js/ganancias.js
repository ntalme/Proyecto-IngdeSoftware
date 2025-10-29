// ---------------------- REPORTE: GANANCIAS SEMANALES O MENSUALES ----------------------
const URL_GANANCIAS_AJAX = "ajax/ganancias.ajax.php";

document.addEventListener("DOMContentLoaded", () => {
  const btn = document.getElementById("btnConsultar");
  const tipoSelect = document.getElementById("tipoPeriodo");
  const colSemana = document.getElementById("colSemana");
  const colMes = document.getElementById("colMes");
  const inputSemana = document.getElementById("inputSemana");
  const inputMes = document.getElementById("inputMes");
  const contenedor = document.getElementById("resultadoGanancias");
  const wrapGrafico = document.getElementById("wrapGrafico");
  let chart = null;

  // --- Formateador CLP ---
  const clp = n => new Intl.NumberFormat('es-CL', {
    style: 'currency', currency: 'CLP', maximumFractionDigits: 0
  }).format(n ?? 0);

  // --- Cambiar entre semana/mes ---
  tipoSelect.addEventListener("change", () => {
    if (tipoSelect.value === "semana") {
      colSemana.style.display = "block";
      colMes.style.display = "none";
      inputSemana.value = "";
    } else {
      colSemana.style.display = "none";
      colMes.style.display = "block";
      inputMes.value = "";
    }
    contenedor.style.display = "none";
    wrapGrafico.style.display = "none";
  });

  // --- Botón Consultar ---
  btn.addEventListener("click", async () => {
    const tipo = tipoSelect.value;
    const valor = tipo === "semana" ? inputSemana.value : inputMes.value;

    if (!valor) {
      Swal.fire("Atención", `Debe seleccionar la ${tipo === "semana" ? "semana" : "mes"} que desea revisar.`, "warning");
      return;
    }

    // Ocultar contenido previo
    contenedor.style.display = "none";
    wrapGrafico.style.display = "none";

    try {
      Swal.fire({
        title: "Consultando...",
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
      });

      const params = new URLSearchParams();
      params.append("accion", "calcularGanancia");
      params.append("periodo", tipo);
      params.append("fecha", valor);

      const resp = await fetch(URL_GANANCIAS_AJAX, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: params
      });

      const data = await resp.json();
      Swal.close();

      if (!data || !data.ok) {
        Swal.fire("Error", data?.error || "No fue posible obtener las ganancias.", "error");
        return;
      }

      const sinVentas = !data.detalle || data.detalle.length === 0 ||
        data.detalle.every(f => (+f.total_final === 0));

      if (sinVentas) {
        Swal.fire("Sin ventas", `No hubo ventas ${tipo === "semana" ? "esa semana" : "ese mes"}.`, "info");
        return;
      }

      // --- Mostrar resultados ---
      contenedor.style.display = "block";

      // Totales
      document.getElementById("gananciaTotal").textContent = clp(data.totales.ganancia_total || 0);
      document.getElementById("reinversionTotal").textContent = clp(data.totales.reinversion_total || 0);
      document.getElementById("ventasTotales").textContent = clp(data.totales.ventas_total || 0);

      // Tabla
      const tbody = document.getElementById("tablaDetalle");
      tbody.innerHTML = "";
      data.detalle.forEach(fila => {
        tbody.innerHTML += `
          <tr>
            <td>${fila.fecha}</td>
            <td>${clp(fila.total_final)}</td>
            <td>${clp(fila.ganancia_dia)}</td>
            <td>${clp(fila.reinversion)}</td>
          </tr>`;
      });

      // --- Preparar gráfico ---
      const labels = data.detalle.map(f => f.fecha);
      const valores = data.detalle.map(f => parseFloat(f.total_final || 0));

      if (chart) chart.destroy();

      const esSemana = tipo === "semana";

      // Mostrar contenedor antes de renderizar el gráfico
      wrapGrafico.style.display = "block";

      // Pequeña espera para asegurar tamaño del canvas
      setTimeout(() => {
        const ctx = document.getElementById("graficoGanancias").getContext("2d");

        chart = new Chart(ctx, {
          type: esSemana ? "bar" : "line",
          data: {
            labels,
            datasets: [{
              label: esSemana ? "Ventas por Día (CLP)" : "Tendencia de Ventas (CLP)",
              data: valores,
              backgroundColor: esSemana ? "rgba(224, 170, 20, 0.53)"  : "rgba(224, 170, 20, 0.53)",
              borderColor: esSemana ? "rgba(153, 116, 15, 0.53)": "rgba(153, 116, 15, 0.53)",
              borderWidth: 2,
              fill: !esSemana,
              tension: esSemana ? 0 : 0.3,
              pointRadius: esSemana ? 0 : 4,
              pointHoverRadius: 6
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: { labels: { usePointStyle: true } },
              tooltip: {
                callbacks: {
                  label: (ctx) => `Monto: ${clp(ctx.parsed.y)}`
                }
              },
              title: {
                display: true,
                text: esSemana
                  ? "Ventas Semanales (por Día)"
                  : "Ventas Mensuales (por Día)"
              }
            },
            scales: {
              x: { title: { display: true, text: esSemana ? "Día" : "Fecha" } },
              y: {
                title: { display: true, text: "Monto (CLP)" },
                ticks: {
                  callback: v => clp(v).replace("$", "$ ")
                },
                beginAtZero: true
              }
            }
          }
        });
      }, 150); // espera para asegurar tamaño del canvas

    } catch (err) {
      Swal.close();
      console.error("Error al consultar ganancias:", err);
      Swal.fire("Error", "Ocurrió un problema al consultar las ganancias.", "error");
    }
  });
});
