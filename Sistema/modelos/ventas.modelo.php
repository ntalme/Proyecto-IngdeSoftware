<?php

require_once "MySQL.php";

class ModeloVentas {

/* ========================================== VENTAS =================================================== */
    // ---------------------------- MOSTRAR VENTAS -------------------------------------------
    public static function mdlMostrarVentas($tabla, $item, $valor) {

        if ($item != null) {

            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY fecha DESC");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch();

        } else {

            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY fecha DESC");
            $stmt->execute();

            return $stmt->fetchAll();
        }

        $stmt = null;
    }

    // ---------------------------- INGRESAR VENTAS -----------------------------------------
    public static function mdlIngresarVenta($tabla, $datos) {
        $pdo = Conexion::conectar();
        $sql = "INSERT INTO $tabla
                (id_usuario, productos, promociones_aplicadas, total, metodo_pago, observacion, fecha)
                VALUES
                (:id_usuario, :productos, :promociones_aplicadas, :total, :metodo_pago, :observacion, :fecha)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
        $stmt->bindParam(":productos", $datos["productos"], PDO::PARAM_STR);
        $stmt->bindParam(":promociones_aplicadas", $datos["promociones_aplicadas"], PDO::PARAM_STR);
        $stmt->bindParam(":total", $datos["total"]);
        $stmt->bindParam(":metodo_pago", $datos["metodo_pago"], PDO::PARAM_STR);
        $stmt->bindParam(":observacion", $datos["observacion"], PDO::PARAM_STR);
        $stmt->bindParam(":fecha", $datos["fecha"], PDO::PARAM_STR);

        return ($stmt->execute()) ? "ok" : "error";
    }

    // ---------------------------- OBTENER VENTA -------------------------------------------
    public static function mdlObtenerVenta(int $idVenta){
        $pdo  = Conexion::conectar();
        $stmt = $pdo->prepare("
            SELECT
            id, fecha, total, metodo_pago, id_usuario, productos,
            promociones_aplicadas AS promociones_aplicadas   -- << traer promo
            FROM venta
            WHERE id = :id
            LIMIT 1
        ");
        $stmt->bindParam(":id", $idVenta, PDO::PARAM_INT);
        $stmt->execute();
        $venta = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = null;
        return $venta ?: null;
    }
/* ========================================== PRODUCTOS =================================================== */
    // ---------------------------- PRODUCTOS POR ID ----------------------------------------
    private static function mdlProductoPorId(int $id): ?array{
        if ($id <= 0) return null;
        $pdo = Conexion::conectar();
        $st  = $pdo->prepare("
            SELECT id, nombre, marca, formato, tamano, precio_venta
            FROM producto
            WHERE id = :id
            LIMIT 1
        ");
        $st->bindParam(':id', $id, PDO::PARAM_INT);
        $st->execute();
        $r = $st->fetch(PDO::FETCH_ASSOC);
        return $r ?: null;
    }

    // ---------------------------- PRECIO DESDE CATALOGO -----------------------------------
    private static function mdlPrecioDesdeCatalogo(array $it): float{
        // 1) por id_producto
        if (!empty($it['id_producto']) || !empty($it['id'])) {
            $id = !empty($it['id_producto']) ? (int)$it['id_producto'] : (int)$it['id'];
            $p  = self::mdlProductoPorId($id);
            if ($p && isset($p['precio_venta'])) return (float)$p['precio_venta'];
        }

        // 2) por codigo
        if (!empty($it['codigo'])) {
            $pdo = Conexion::conectar();
            $st  = $pdo->prepare("SELECT precio_venta FROM producto WHERE codigo = :codigo LIMIT 1");
            $st->bindParam(':codigo', $it['codigo'], PDO::PARAM_STR);
            $st->execute();
            $r = $st->fetch(PDO::FETCH_ASSOC);
            if ($r && isset($r['precio_venta'])) return (float)$r['precio_venta'];
        }

        // 3) por nombre
        if (!empty($it['nombre'])) {
            $pdo = Conexion::conectar();
            $st  = $pdo->prepare("SELECT precio_venta FROM producto WHERE nombre = :nombre LIMIT 1");
            $st->bindParam(':nombre', $it['nombre'], PDO::PARAM_STR);
            $st->execute();
            $r = $st->fetch(PDO::FETCH_ASSOC);
            if ($r && isset($r['precio_venta'])) return (float)$r['precio_venta'];
        }

        return 0.0;
    }

    // ---------------------------- OBTENER DETALLE -----------------------------------------
    // ---------------------------- OBTENER DETALLE -----------------------------------------
  public static function mdlObtenerDetalle(int $idVenta): array {
      $venta = self::mdlObtenerVenta($idVenta);
      if (!$venta || empty($venta['productos'])) return [];

      $items = json_decode($venta['productos'], true);
      if (!is_array($items)) return [];

      $detalle = [];

      foreach ($items as $it) {

          $idProd = (int)($it['id_producto'] ?? $it['id'] ?? 0);
          $nombre  = (string)($it['nombre'] ?? $it['producto'] ?? '');
          $marca   = (string)($it['marca'] ?? '');
          $formato = (string)($it['formato'] ?? $it['presentacion'] ?? '');
          $tamano  = (string)($it['tamano'] ?? $it['tamaño'] ?? $it['size'] ?? '');
          $cant    = (float)($it['cantidad'] ?? 1);

          // 🔹 Tomar siempre el precio final histórico del JSON de la venta
          $precio = (float)($it['precio_final'] ?? $it['precio'] ?? $it['precioUnitario'] ?? 0);

          // 🔹 Si no hay precio, usar el total_linea / cantidad como respaldo
          if ($precio <= 0 && isset($it['total_linea']) && $cant > 0) {
              $precio = (float)$it['total_linea'] / $cant;
          }

          // 🔹 No consultar la tabla productos — solo completar datos faltantes descriptivos
          if ($idProd > 0 && (empty($marca) || empty($formato) || empty($tamano) || empty($nombre))) {
              $cat = self::mdlProductoPorId($idProd);
              if ($cat) {
                  if (!$nombre && !empty($cat['nombre']))   $nombre  = (string)$cat['nombre'];
                  if (!$marca  && !empty($cat['marca']))    $marca   = (string)$cat['marca'];
                  if (!$formato&& !empty($cat['formato']))  $formato = (string)$cat['formato'];
                  if (!$tamano && !empty($cat['tamano']))   $tamano  = (string)$cat['tamano'];
              }
          }

          // 🔹 Validaciones básicas
          if ($idProd <= 0) $idProd = 0;
          if ($nombre === '')  $nombre  = 'Producto';
          if ($marca === '')   $marca   = '-';
          if ($formato === '') $formato = '-';
          if ($tamano === '')  $tamano  = '-';
          if ($cant <= 0)      $cant    = 1;
          if ($precio < 0)     $precio  = 0;

          // 🔹 Calcular subtotal histórico
          $subtotal = (float)($it['total_linea'] ?? ($cant * $precio));

          $detalle[] = [
              'id_producto' => $idProd,
              'producto'    => $nombre,
              'marca'       => $marca,
              'formato'     => $formato,
              'tamano'      => $tamano,
              'cantidad'    => $cant,
              'precio'      => $precio,
              'subtotal'    => $subtotal,
          ];
      }

      return $detalle;
  }


/* ========================================== REPORTES =================================================== */
    // ---------------------------- PRODUCTOS VENDIDOS --------------------------------------
public static function mdlProductosVendidosPorDia($tablaVenta, $fechaStr) {
    try {
        $pdo = Conexion::conectar();

        $sql = "SELECT id, productos FROM {$tablaVenta} WHERE DATE(fecha) = :fecha";
        $st  = $pdo->prepare($sql);
        $st->bindParam(":fecha", $fechaStr, PDO::PARAM_STR);
        $st->execute();

        $ventas = $st->fetchAll(PDO::FETCH_ASSOC);

        $acum = [];
        $parseCLP = function ($v) {
            if ($v === null) return 0.0;
            if (is_numeric($v)) return floatval($v);
            $v = (string)$v;
            $v = str_replace(['$', 'CLP', ' ', "\xc2\xa0"], '', $v);
            $v = str_replace('.', '', $v);   // quita separadores de miles
            $v = str_replace(',', '.', $v);  // convierte coma en punto
            return is_numeric($v) ? floatval($v) : 0.0;
        };

        $cacheProducto = [];

        $obtenerInfoProducto = function ($idProd) use ($pdo, &$cacheProducto) {
            if (!$idProd) return ["formato" => "", "tamano" => "", "precio" => 0.0];
            if (isset($cacheProducto[$idProd])) return $cacheProducto[$idProd];

            $info = ["formato" => "", "tamano" => "", "precio" => 0.0];

            try {
                $q = $pdo->prepare("SELECT * FROM producto WHERE id = :id LIMIT 1");
                $q->bindParam(":id", $idProd, PDO::PARAM_INT);
                $q->execute();
                if ($row = $q->fetch(PDO::FETCH_ASSOC)) {

                    if (isset($row["precio_venta"])) $info["precio"] = floatval($row["precio_venta"]);
                    elseif (isset($row["precio"]))   $info["precio"] = floatval($row["precio"]);

                    if (!empty($row["formato"])) $info["formato"] = trim($row["formato"]);

                    if (!empty($row["tamano"])) $info["tamano"] = trim($row["tamano"]);
                    elseif (!empty($row["tamaño"])) $info["tamano"] = trim($row["tamaño"]);
                    elseif (!empty($row["presentacion"])) $info["tamano"] = trim($row["presentacion"]);
                }
            } catch (\Throwable $e) {}

            return $cacheProducto[$idProd] = $info;
        };

        foreach ($ventas as $v) {
            $raw = $v["productos"];
            if (!$raw) continue;

            $items = json_decode($raw, true);
            if (!is_array($items)) continue;

            foreach ($items as $it) {
                $idProd   = isset($it["id"]) ? (int)$it["id"] : null;
                $nombre   = isset($it["nombre"]) ? trim($it["nombre"]) : "-";
                $cantidad = isset($it["cantidad"]) ? floatval($it["cantidad"]) : 0.0;

                $formatoItem = $it["formato"] ?? "";
                $tamanoItem  = $it["tamano"] ?? ($it["tamaño"] ?? ($it["presentacion"] ?? ""));

                $infoProd = $obtenerInfoProducto($idProd);
                $formato  = ($formatoItem !== "" ? $formatoItem : $infoProd["formato"]);
                $tamano   = ($tamanoItem  !== "" ? $tamanoItem  : $infoProd["tamano"]);

                // --- PRECIO REAL DE LA VENTA ---
                $precioUnit = 0.0;

                if (isset($it["precio_final"])) {
                    $precioUnit = $parseCLP($it["precio_final"]);
                } elseif (isset($it["total_linea"]) && $cantidad > 0) {
                    $precioUnit = $parseCLP($it["total_linea"]) / $cantidad;
                } elseif (isset($it["precio_base"])) {
                    // solo como respaldo, en caso de que no haya descuento
                    $precioUnit = $parseCLP($it["precio_base"]);
                }

                // si no hay nada, dejar en 0
                if ($precioUnit <= 0) $precioUnit = 0.0;

                $monto = $cantidad * $precioUnit;
                $key = ($idProd !== null && $idProd !== 0) ? "id:$idProd" : "nombre:" . $nombre;

                if (!isset($acum[$key])) {
                    $acum[$key] = [
                        "id_producto"     => $idProd,
                        "nombre_producto" => $nombre,
                        "formato"         => $formato,
                        "tamano"          => $tamano,
                        "cantidad_total"  => 0.0,
                        "monto_total"     => 0.0,
                    ];
                } else {
                    if ($acum[$key]["formato"] === "" && $formato !== "") $acum[$key]["formato"] = $formato;
                    if ($acum[$key]["tamano"] === "" && $tamano !== "")   $acum[$key]["tamano"]  = $tamano;
                }

                $acum[$key]["cantidad_total"] += $cantidad;
                $acum[$key]["monto_total"]    += $monto;
            }
        }

        $lista = array_values($acum);
        usort($lista, function ($a, $b) {
            if ($a["cantidad_total"] == $b["cantidad_total"]) return 0;
            return ($a["cantidad_total"] > $b["cantidad_total"]) ? -1 : 1;
        });

        return $lista;

    } catch (Exception $e) {
        return false;
    }
}

    // ---------------------------- VENTAS DIARIAS ------------------------------------------
    public static function mdlVentasDiariasPorHora($tablaVenta, $fechaStr) {
        try {
        $pdo = Conexion::conectar();

        $sql = "
            SELECT 
            HOUR(fecha) AS hora,
            COUNT(*)    AS ventas,
            COALESCE(SUM(total),0) AS monto_total
            FROM {$tablaVenta}
            WHERE DATE(fecha) = :fecha
            GROUP BY HOUR(fecha)
            ORDER BY hora ASC
        ";
        $st = $pdo->prepare($sql);
        $st->bindParam(":fecha", $fechaStr, PDO::PARAM_STR);
        $st->execute();
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);

        $map = [];
        foreach ($rows as $r) {
            $map[(int)$r["hora"]] = [
            "hora"        => (int)$r["hora"],
            "ventas"      => (int)$r["ventas"],
            "monto_total" => (float)$r["monto_total"],
            ];
        }

        $out = [];
        for ($h = 0; $h <= 23; $h++) {
            if (isset($map[$h])) {
            $ventas = $map[$h]["ventas"];
            $monto  = $map[$h]["monto_total"];
            } else {
            $ventas = 0;
            $monto  = 0.0;
            }
            $ticket = $ventas > 0 ? ($monto / $ventas) : 0.0;

            $out[] = [
            "hora"         => $h,                 // 0..23
            "ventas"       => $ventas,
            "monto_total"  => $monto,
            "ticket_prom"  => $ticket
            ];
        }

        return $out;

        } catch (Exception $e) {
        return false;
        }
    }

/* ========================================== CIERRE =================================================== */
// ---------------------------- TOTAL DEL DÍA ----------------------------------------
  public static function mdlTotalDelDia(): float {
    $pdo = Conexion::conectar();
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(total),0) FROM venta WHERE DATE(fecha)=CURDATE()");
    $stmt->execute();
    return (float)$stmt->fetchColumn();
  }

  // ---------------------------- CANTIDAD DEL DÍA -------------------------------------
  public static function mdlCantidadDelDia(): int {
    $pdo = Conexion::conectar();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM venta WHERE DATE(fecha)=CURDATE()");
    $stmt->execute();
    return (int)$stmt->fetchColumn();
  }

  // ---------------------------- EXISTE CIERRE HOY ------------------------------------
  public static function mdlExisteCierreHoy(?int $idUsuario = null): bool {
    $pdo = Conexion::conectar();
    $sql = "SELECT 1 FROM cierre_caja WHERE DATE(fecha_cierre)=CURDATE()";
    if ($idUsuario !== null) $sql .= " AND id_usuario=:idUsuario";
    $sql .= " LIMIT 1";
    $st = $pdo->prepare($sql);
    if ($idUsuario !== null) $st->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
    $st->execute();
    return (bool)$st->fetchColumn();
  }

  // ---------------------------- GUARDAR CIERRE DIARIO --------------------------------
  public static function mdlGuardarCierreDiario(array $datos): bool {
    $pdo = Conexion::conectar();

    // Obtener detalle completo de ventas del día
    $detalleVentas = self::ventasDelDiaConDetalle();
    $ventasJson = json_encode($detalleVentas, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    // Calcular ventas con tarjeta y efectivo
    $sqlPagos = "
      SELECT metodo_pago, 
            COUNT(*) AS cantidad, 
            SUM(total) AS monto 
      FROM venta 
      WHERE DATE(fecha) = CURDATE() 
      GROUP BY metodo_pago
    ";

    $ventasTarjeta = 0;
    $cantidadTarjeta = 0;
    $ventasEfectivo = 0;
    $cantidadEfectivo = 0;

    try {
      $stmtPagos = $pdo->query($sqlPagos);
      $pagos = $stmtPagos->fetchAll(PDO::FETCH_ASSOC);

      foreach ($pagos as $pago) {
        $metodo = strtolower(trim($pago['metodo_pago']));
        if ($metodo === 'tarjeta') {
          $ventasTarjeta = (float)$pago['monto'];
          $cantidadTarjeta = (int)$pago['cantidad'];
        } elseif ($metodo === 'efectivo') {
          $ventasEfectivo = (float)$pago['monto'];
          $cantidadEfectivo = (int)$pago['cantidad'];
        }
      }

      // Detectar si es domingo
      $esDomingo = date('w') == 0;
      $descuentoLuz = $esDomingo ? 80000 : 0;

      // Calcular reinversión semanal final
      $reinversionSemanal = $datos['reinversion_semana'] ?? 0;
      $reinversionSemanalFinal = max($reinversionSemanal - $descuentoLuz, 0);

      // Insertar en cierre_caja
      $sql = "INSERT INTO cierre_caja (
                fecha_cierre, id_usuario, total_general, cantidad_ventas, ventas_json,
                boletas_digitales, retencion_boletas, retencion_tarjeta, descuento_contadora,
                total_final, ganancia_dia, reinversion, observaciones,
                ventas_tarjeta_cantidad, ventas_tarjeta_monto,
                ventas_efectivo_cantidad, ventas_efectivo_monto,
                es_domingo, descuento_luz, reinversion_semana, reinversion_semana_final
              ) VALUES (
                NOW(), :id_usuario, :total_general, :cantidad_ventas, :ventas_json,
                :boletas_digitales, :retencion_boletas, :retencion_tarjeta, :descuento_contadora,
                :total_final, :ganancia_dia, :reinversion, :observaciones,
                :ventas_tarjeta_cantidad, :ventas_tarjeta_monto,
                :ventas_efectivo_cantidad, :ventas_efectivo_monto,
                :es_domingo, :descuento_luz, :reinversion_semana, :reinversion_semana_final
              )";

      $stmt = $pdo->prepare($sql);

      // Vincular valores del arreglo base
      foreach ($datos as $key => $value) {
        $stmt->bindValue(":$key", $value);
      }

      // Datos adicionales calculados
      $stmt->bindParam(":ventas_json", $ventasJson);
      $stmt->bindParam(":ventas_tarjeta_cantidad", $cantidadTarjeta, PDO::PARAM_INT);
      $stmt->bindParam(":ventas_tarjeta_monto", $ventasTarjeta);
      $stmt->bindParam(":ventas_efectivo_cantidad", $cantidadEfectivo, PDO::PARAM_INT);
      $stmt->bindParam(":ventas_efectivo_monto", $ventasEfectivo);

      // Campos dominicales y reinversión
      $stmt->bindValue(":es_domingo", $esDomingo ? 1 : 0, PDO::PARAM_INT);
      $stmt->bindValue(":descuento_luz", $descuentoLuz, PDO::PARAM_INT);
      $stmt->bindValue(":reinversion_semana", $reinversionSemanal);
      $stmt->bindValue(":reinversion_semana_final", $reinversionSemanalFinal);

      return $stmt->execute();

    } catch (Throwable $e) {
      error_log("[mdlGuardarCierreDiario] " . $e->getMessage());
      return false;
    }
  }

  // ---------------------------- OBTENER CIERRES --------------------------------------
  public static function mdlObtenerCierres(): array {
    $pdo = Conexion::conectar();
    $sql = "SELECT cd.*, u.nombre AS usuario
            FROM cierre_caja cd
            JOIN usuario u ON u.id = cd.id_usuario
            ORDER BY cd.id DESC";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  }

  /* ========================================== RESUMEN FINANCIERO =================================================== */
  // ---------------------------- RESUMEN MÉTODO DE PAGO -------------------------------
  public static function mdlResumenMetodoPago(): array {
    try {
      $stmt = Conexion::conectar()->prepare("
        SELECT metodo_pago, COUNT(*) AS cantidad_ventas, SUM(total) AS monto_total
        FROM venta
        WHERE DATE(fecha)=CURDATE()
        GROUP BY metodo_pago
      ");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [["error" => $e->getMessage()]];
    }
  }
    // ---------------------------- VENTAS CON DETALLE -----------------------------------
  public static function ventasDelDiaConDetalle(): array {
    $pdo = Conexion::conectar();
    $sql = "SELECT id, fecha, total, metodo_pago, productos
            FROM venta
            WHERE DATE(fecha)=CURDATE()
            ORDER BY id ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // ---------------------------- TOTAL CON TARJETA ------------------------------------
  static public function mdlObtenerTotalConTarjeta($tabla): float {
    $stmt = Conexion::conectar()->prepare("
      SELECT SUM(total) AS total_tarjeta
      FROM $tabla
      WHERE DATE(fecha) = CURDATE() AND metodo_pago = 'tarjeta'
    ");
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    return (float)($resultado['total_tarjeta'] ?? 0);
  }

  // ---------------------------- CALCULAR REINVERSIÓN SEMANAL ----------------------------
  public static function mdlCalcularReinversionSemanal(float $reinversionDiaActual = 0): float {
      $pdo = Conexion::conectar();

      try {
          // Sumar todas las reinversiones registradas en la semana actual (lunes a domingo)
          $sql = "
              SELECT SUM(reinversion) AS total_reinversion
              FROM cierre_caja
              WHERE YEARWEEK(fecha_cierre, 1) = YEARWEEK(CURDATE(), 1)
          ";
          $stmt = $pdo->query($sql);
          $result = $stmt->fetch(PDO::FETCH_ASSOC);

          // Total acumulado + la reinversión del día actual
          $total = (float)($result['total_reinversion'] ?? 0) + (float)$reinversionDiaActual;

          return $total;

      } catch (Throwable $e) {
          error_log('[mdlCalcularReinversionSemanal] ' . $e->getMessage());
          return 0;
      }
  }

  // ---------------------------- CALCULAR GANANCIAS ----------------------------
  public static function mdlObtenerGanancias($periodo = 'semana', $fechaSeleccionada = null) {
    $pdo = Conexion::conectar();

    // Determinar el rango de fechas según el tipo de período
    if ($periodo === 'semana') {
      // Si no se seleccionó semana, usar la actual
      $semanaIso = $fechaSeleccionada ?? date('o-\WW');
      [$inicio, $fin] = self::obtenerRangoSemana($semanaIso);
    } else {
      // Si no se seleccionó mes, usar el actual
      $mesIso = $fechaSeleccionada ?? date('Y-m');
      [$inicio, $fin] = self::obtenerRangoMes($mesIso);
    }

    // DETALLE
    $sqlDetalle = "
      SELECT 
        DATE(fecha_cierre) AS fecha,
        total_final,
        ganancia_dia,
        reinversion
      FROM cierre_caja
      WHERE DATE(fecha_cierre) BETWEEN :inicio AND :fin
      ORDER BY fecha_cierre ASC
    ";
    $stmtDetalle = $pdo->prepare($sqlDetalle);
    $stmtDetalle->bindParam(":inicio", $inicio);
    $stmtDetalle->bindParam(":fin", $fin);
    $stmtDetalle->execute();
    $detalle = $stmtDetalle->fetchAll(PDO::FETCH_ASSOC);

    // TOTALES
    $sqlTotales = "
      SELECT 
        SUM(total_final) AS ventas_total,
        SUM(ganancia_dia) AS ganancia_total,
        SUM(reinversion) AS reinversion_total
      FROM cierre_caja
      WHERE DATE(fecha_cierre) BETWEEN :inicio AND :fin
    ";
    $stmtTotales = $pdo->prepare($sqlTotales);
    $stmtTotales->bindParam(":inicio", $inicio);
    $stmtTotales->bindParam(":fin", $fin);
    $stmtTotales->execute();
    $totales = $stmtTotales->fetch(PDO::FETCH_ASSOC) ?: [];

    return [
      "detalle" => $detalle,
      "totales" => [
        "ventas_total" => $totales["ventas_total"] ?? 0,
        "ganancia_total" => $totales["ganancia_total"] ?? 0,
        "reinversion_total" => $totales["reinversion_total"] ?? 0
      ]
    ];
  }

  // ---------------------------- FUNCIONES AUXILIARES PARA RANGO DE FECHAS ----------------------------
  private static function obtenerRangoSemana($semanaIso) {
    // Formato ISO: "2025-W45"
    [$year, $week] = explode('-W', $semanaIso);
    $dto = new DateTime();
    $dto->setISODate((int)$year, (int)$week);
    $inicio = $dto->format('Y-m-d');
    $dto->modify('+6 days');
    $fin = $dto->format('Y-m-d');
    return [$inicio, $fin];
  }

  private static function obtenerRangoMes($mesIso) {
    // Formato: "2025-11"
    $inicio = $mesIso . '-01';
    $fin = date('Y-m-t', strtotime($inicio)); // Último día del mes
    return [$inicio, $fin];
  }

}

