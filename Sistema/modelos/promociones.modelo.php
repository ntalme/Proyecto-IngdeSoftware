<?php
require_once "MySQL.php";

class ModeloPromociones {

    // ---------------------------- MOSTRAR PROMOCIONES --------------------------------------
    public static function mdlMostrarPromociones($tabla, $item, $valor) {
        if ($item != null) {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
        } else {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare("SELECT * FROM $tabla");
            $stmt->execute();
            return $stmt->fetchAll();
        }
    }

    // ---------------------------- INGRESAR PROMOCION --------------------------------------
    public static function mdlIngresarPromocion($tabla, $datos) {
        $pdo  = Conexion::conectar();
        $stmt = $pdo->prepare(
            "INSERT INTO $tabla (tipo, parametro, fecha_inicio, fecha_fin, observacion) 
            VALUES (:tipo, :parametro, :fecha_inicio, :fecha_fin, :observacion)"
        );

        $stmt->bindValue(":tipo", $datos["tipo"], PDO::PARAM_STR);

        if (is_null($datos["parametro"])) {
            $stmt->bindValue(":parametro", null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(":parametro", $datos["parametro"], PDO::PARAM_STR);
        }

        $stmt->bindValue(":fecha_inicio", $datos["fecha_inicio"], PDO::PARAM_STR);
        $stmt->bindValue(":fecha_fin",    $datos["fecha_fin"],    PDO::PARAM_STR);

        if (is_null($datos["observacion"]) || $datos["observacion"] === '') {
            $stmt->bindValue(":observacion", null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(":observacion", $datos["observacion"], PDO::PARAM_STR);
        }

        if ($stmt->execute()) {
            return $pdo->lastInsertId(); // misma conexión
        } else {
            return "error";
        }
    }

    // ---------------------------- VINCULAR PRODUCTOS --------------------------------------
    public static function mdlVincularProductos($tabla, $idPromocion, $productos) {
        if (empty($productos)) return "ok";
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("INSERT INTO $tabla (id_promocion, id_producto) VALUES (:id_promocion, :id_producto)");
        foreach ($productos as $idProducto) {
            $stmt->execute([
                ':id_promocion' => (int)$idPromocion,
                ':id_producto'  => (int)$idProducto
            ]);
        }
        return "ok";
    }

    // ---------------------------- PROMOCIONES ACTIVAS --------------------------------------
    public static function mdlPromocionesActivas($ahora = null) {
        $pdo = Conexion::conectar();
        if ($ahora === null) {
            date_default_timezone_set('America/Santiago');
            $ahora = date('Y-m-d H:i:s');
        }

        $sql = "
            SELECT 
                p.id, p.tipo, p.parametro, p.fecha_inicio, p.fecha_fin, p.observacion, p.estado,
                COALESCE(GROUP_CONCAT(prod.nombre SEPARATOR ', '), '') AS productos
            FROM promocion p
            LEFT JOIN promocion_producto pp ON pp.id_promocion = p.id
            LEFT JOIN producto prod ON prod.id = pp.id_producto
            WHERE p.estado = 1
            AND p.fecha_inicio <= :ahora
            AND :ahora < (
                    CASE 
                    WHEN TIME(p.fecha_fin) = '00:00:00' THEN p.fecha_fin + INTERVAL 1 DAY
                    ELSE p.fecha_fin + INTERVAL 1 SECOND
                    END
            )
            GROUP BY p.id
            ORDER BY p.fecha_fin ASC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':ahora', $ahora, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ---------------------------- BORRAR PROMOCION --------------------------------------
    public static function mdlBorrarPromocion($tabla, $idPromocion) {
        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");
        $stmt->bindParam(":id", $idPromocion, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $stmt = null;
            return "ok";
        } else {
            $stmt = null;
            return "error";
        }
    }

    // ---------------------------- BORRAR VINCULO --------------------------------------
    public static function mdlBorrarVinculos($tabla, $idPromocion) {
        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id_promocion = :id");
        $stmt->bindParam(":id", $idPromocion, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $stmt = null;
            return "ok";
        } else {
            $stmt = null;
            return "error";
        }
    }

    // ---------------------------- PROMOCIONES ACTIVAS POR PRODUCTOS --------------------------------------
    public static function mdlPromosActivasPorProducto($idProducto){
        $pdo = Conexion::conectar();
        $sql = "SELECT p.id, p.tipo, p.parametro, p.fecha_inicio, p.fecha_fin, p.observacion
                FROM promocion p
                JOIN promocion_producto pp ON pp.id_promocion = p.id
                WHERE pp.id_producto = :id
                    AND p.estado = 1
                    AND NOW() BETWEEN p.fecha_inicio AND p.fecha_fin
                ORDER BY p.id DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $idProducto, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ---------------------------- MAPA PROMOS ACTIVAS --------------------------------------
    public static function mdlMapaPromosActivasPorProducto() {
        $pdo = Conexion::conectar();
        $sql = "SELECT pp.id_producto,
                        p.id, p.tipo, p.parametro, p.fecha_inicio, p.fecha_fin, p.observacion
                FROM promocion p
                JOIN promocion_producto pp ON pp.id_promocion = p.id
                WHERE p.estado = 1
                    AND NOW() BETWEEN p.fecha_inicio AND p.fecha_fin
                ORDER BY p.id DESC";
        $stmt = $pdo->query($sql);
        $rows = $stmt->fetchAll();
        $map = [];
        foreach ($rows as $r) {
            $pid = (int)$r['id_producto'];
            if (!isset($map[$pid])) $map[$pid] = [];
            $map[$pid][] = [
            'id'         => (int)$r['id'],
            'tipo'       => $r['tipo'],           // descuento | 2x1 | precio_fijo
            'parametro'  => (float)$r['parametro'],
            'obs'        => $r['observacion'] ?? ''
            ];
        }
        return $map;
        }


}
