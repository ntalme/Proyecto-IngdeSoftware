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
    public static function mdlObtenerDetalle(int $idVenta): array{
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

           
            $precio = (float)(
                $it['precio'] ??
                $it['precio_venta'] ??
                $it['precioUnitario'] ??
                $it['unit_price'] ?? 0.0
            );
            if ($precio <= 0) {
                $precio = self::mdlPrecioDesdeCatalogo($it);
            }

            if ($idProd > 0 && (empty($marca) || empty($formato) || empty($tamano) || empty($nombre))) {
                $cat = self::mdlProductoPorId($idProd);
                if ($cat) {
                    if (!$nombre && !empty($cat['nombre']))   $nombre  = (string)$cat['nombre'];
                    if (!$marca  && !empty($cat['marca']))    $marca   = (string)$cat['marca'];
                    if (!$formato&& !empty($cat['formato']))  $formato = (string)$cat['formato'];
                    if (!$tamano && !empty($cat['tamano']))   $tamano  = (string)$cat['tamano'];
                }
            }

            if ($idProd <= 0) $idProd = 0;
            if ($nombre === '')  $nombre  = 'Producto';
            if ($marca === '')   $marca   = '-';
            if ($formato === '') $formato = '-';
            if ($tamano === '')  $tamano  = '-';
            if ($cant <= 0)      $cant    = 1;
            if ($precio < 0)     $precio  = 0;

            $detalle[] = [
                'id_producto' => $idProd,
                'producto'    => $nombre,
                'marca'       => $marca,
                'formato'     => $formato,
                'tamano'      => $tamano,
                'cantidad'    => $cant,
                'precio'      => $precio,
                'subtotal'    => $cant * $precio,
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
            $v = str_replace('.', '', $v);   // quita miles
            $v = str_replace(',', '.', $v);  // coma → punto
            return is_numeric($v) ? floatval($v) : 0.0;
            };

            $cacheProducto = [];

            $obtenerInfoProducto = function ($idProd) use ($pdo, &$cacheProducto) {
            if (!$idProd) return ["formato"=>"", "tamano"=>"", "precio"=>0.0];
            if (isset($cacheProducto[$idProd])) return $cacheProducto[$idProd];

            $info = ["formato"=>"", "tamano"=>"", "precio"=>0.0];

            try {
                $q = $pdo->prepare("SELECT * FROM producto WHERE id = :id LIMIT 1");
                $q->bindParam(":id", $idProd, PDO::PARAM_INT);
                $q->execute();
                if ($row = $q->fetch(PDO::FETCH_ASSOC)) {
        
                if (isset($row["precio_venta"])) $info["precio"] = floatval($row["precio_venta"]);
                elseif (isset($row["precio"]))   $info["precio"] = floatval($row["precio"]);

                if (isset($row["formato"]) && $row["formato"] !== "") {
                    $info["formato"] = trim($row["formato"]);
                }

                if      (isset($row["tamano"]) && $row["tamano"]!=="")   $info["tamano"] = trim($row["tamano"]);
                elseif  (isset($row["tamaño"]) && $row["tamaño"]!=="")   $info["tamano"] = trim($row["tamaño"]);
                elseif  (isset($row["presentacion"]) && $row["presentacion"]!=="") $info["tamano"] = trim($row["presentacion"]);
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
                $tamanoItem  = $it["tamano"]  ?? ($it["tamaño"] ?? ($it["presentacion"] ?? ""));

                $infoProd = $obtenerInfoProducto($idProd);
                $formato  = ($formatoItem !== "" ? $formatoItem : $infoProd["formato"]);
                $tamano   = ($tamanoItem  !== "" ? $tamanoItem  : $infoProd["tamano"]);

                $precioUnit = 0.0;
                if (isset($it["precioUnitario"]))       $precioUnit = $parseCLP($it["precioUnitario"]);
                elseif (isset($it["precio"]))           $precioUnit = $parseCLP($it["precio"]);
                elseif (isset($it["price"]))            $precioUnit = $parseCLP($it["price"]);
                if ($precioUnit <= 0 && $idProd)        $precioUnit = floatval($infoProd["precio"]);

                $monto = $cantidad * $precioUnit;
                $key = ($idProd !== null && $idProd !== 0) ? "id:$idProd" : "nombre:".$nombre;

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
                if ($acum[$key]["tamano"]  === "" && $tamano  !== "") $acum[$key]["tamano"]  = $tamano;
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
    // ---------------------------- TOTAL DEL DIA ------------------------------------------
    public static function mdlTotalDelDia(): float {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT COALESCE(SUM(total),0) FROM venta WHERE DATE(fecha)=CURDATE()");
        $stmt->execute();
        return (float)$stmt->fetchColumn();
    }

    // ---------------------------- CANTIDAD DEL DIA ---------------------------------------
    public static function mdlCantidadDelDia(): int {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM venta WHERE DATE(fecha)=CURDATE()");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    // ---------------------------- VENTAS DEL DIA -----------------------------------------
    private static function mdlIdsVentasDelDia(): array {
        $pdo = Conexion::conectar();
        $st = $pdo->prepare("SELECT id FROM venta WHERE DATE(fecha)=CURDATE()");
        $st->execute();
        return $st->fetchAll(PDO::FETCH_COLUMN);
    }
    
    // ---------------------------- VESTAS CON DETALLE -------------------------------------
    private static function ventasDelDiaConDetalle(): array {
        $pdo = Conexion::conectar();
        $sql = "SELECT id, fecha, total, metodo_pago, productos
                  FROM venta
                 WHERE DATE(fecha)=CURDATE()
              ORDER BY id ASC";
        $st = $pdo->prepare($sql);
        $st->execute();
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);

        $detalle = [];
        foreach ($rows as $r) {
            $productos = json_decode($r['productos'], true);
            if ($productos === null && json_last_error() !== JSON_ERROR_NONE) {
                // Si el JSON viniera mal formado, lo guardamos como string para no perder la traza
                $productos = $r['productos'];
            }
            $detalle[] = [
                'id_venta'    => (int)$r['id'],
                'fecha'       => $r['fecha'],
                'total'       => (float)$r['total'],
                'metodo_pago' => $r['metodo_pago'],
                'productos'   => $productos, // ← igual a venta.productos
            ];
        }
        return $detalle;
    }

    // ---------------------------- GUARDAR CIERRE -----------------------------------------
    public static function mdlGuardarCierre(int $idUsuario, float $total, int $cantidad) : bool {
        $pdo = Conexion::conectar();

        // Defensa en profundidad: evitar doble cierre por carrera
        if (self::mdlExisteCierreHoy(null /* o $idUsuario si quiere por usuario */)) {
            return false; // ya hay cierre hoy
        }

        $ventas     = self::mdlIdsVentasDelDia();
        $detalle    = self::ventasDelDiaConDetalle();
        $ventasJson = json_encode($detalle, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        try {
            $pdo->beginTransaction();

            // 6 columnas -> 6 valores (2 NOW() + 4 ?)
            $sqlCierre = "INSERT INTO cierre_caja 
                            (fecha_cierre, id_usuario, total_ventas, cantidad_ventas, ventas_json, creado_en)
                        VALUES (NOW(), ?, ?, ?, ?, NOW())";
            $st = $pdo->prepare($sqlCierre);
            $st->execute([$idUsuario, $total, $cantidad, $ventasJson]);

            $idCierre = (int)$pdo->lastInsertId();

            if (!empty($ventas)) {
                $in      = implode(',', array_fill(0, count($ventas), '?'));
                $sqlUpd  = "UPDATE venta SET id_cierre_caja = ? WHERE id IN ($in)";
                $stUpd   = $pdo->prepare($sqlUpd);
                $params  = array_merge([$idCierre], $ventas);
                $stUpd->execute($params);
            }

            $pdo->commit();
            return true;

        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            error_log("[CIERRE_CAJA] ".$e->getMessage());
            echo '<pre style="white-space:pre-wrap;background:#222;color:#eee;padding:10px;border-radius:6px;">'
            .'Error cierre_caja: '.$e->getMessage().'</pre>';
            return false;
        }
    }

    // ---------------------------- OBTENER CIERRES ----------------------------------------
    public static function mdlObtenerCierres(): array {
        $pdo = Conexion::conectar();
        $sql = "SELECT cc.id, cc.fecha_cierre, cc.id_usuario,
                       cc.total_ventas, cc.cantidad_ventas, cc.creado_en,
                       cc.ventas_json,
                       u.nombre AS usuario
                  FROM cierre_caja cc
                  JOIN usuario u ON u.id = cc.id_usuario
              ORDER BY cc.id DESC";
        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // ---------------------------- SI EXISTE CIERRE ---------------------------------------
    public static function mdlExisteCierreHoy(?int $idUsuario = null): bool {
        $pdo = Conexion::conectar();
        $sql = "SELECT 1 FROM cierre_caja WHERE DATE(fecha_cierre) = CURDATE()";
        if ($idUsuario !== null) { $sql .= " AND id_usuario = :idUsuario"; }
        $sql .= " LIMIT 1";
        $st = $pdo->prepare($sql);
        if ($idUsuario !== null) $st->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $st->execute();
        return (bool)$st->fetchColumn();
    }
    
}
