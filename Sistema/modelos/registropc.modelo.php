<?php
require_once "MySQL.php";

class ModeloRegistropc {

    // ---------------------------- LSITAR REGISTROS --------------------------------------
    public static function mdlListar($tabla, $item = null, $valor = null) {

        // Base con JOIN a tabla usuario
        $baseSQL = "
            SELECT p.id, p.fecha, p.producto_id, p.producto_codigo, p.producto_nombre,
                p.cantidad, p.motivo, p.observacion,
                u.nombre AS nombre_usuario
            FROM $tabla p
            LEFT JOIN usuario u ON u.id = p. usuario_id
        ";

        if ($item !== null) {
            $sql = $baseSQL . " WHERE p.$item = :$item ORDER BY p.fecha DESC, p.id DESC";
            $stmt = Conexion::conectar()->prepare($sql);

            // Bind según tipo básico (int o string)
            if (is_numeric($valor)) {
                $stmt->bindParam(":".$item, $valor, PDO::PARAM_INT);
            } else {
                $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
            }

            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            $sql = $baseSQL . " ORDER BY p.fecha DESC, p.id DESC";
            $stmt = Conexion::conectar()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }
    }

    // ---------------------------- REGISTRAR PÉRDIDA / CONSUMO ---------------------------
    public static function mdlRegistrar($tabla, $datos) {

        $pdo = Conexion::conectar();

        /* 1) Verificar stock actual del producto */
        $stmt = $pdo->prepare("SELECT cantidad FROM producto WHERE id = :id");
        $stmt->bindParam(":id", $datos["producto_id"], PDO::PARAM_INT);
        $stmt->execute();
        $stockActual = $stmt->fetchColumn();

        if ($stockActual === false || $stockActual < (int)$datos["cantidad"]) {
            // Producto no existe o stock insuficiente
            return "error";
        }

        /* 2) Descontar stock */
        $stmt = $pdo->prepare("UPDATE producto SET cantidad = cantidad - :cantidad WHERE id = :id");
        $stmt->bindParam(":cantidad", $datos["cantidad"], PDO::PARAM_INT);
        $stmt->bindParam(":id", $datos["producto_id"], PDO::PARAM_INT);

        if (!$stmt->execute()) {
            return "error";
        }

        /* 3) Insertar registro en $tabla con código y nombre directo desde producto */
        $stmt = $pdo->prepare("
            INSERT INTO $tabla
                (producto_id, producto_codigo, producto_nombre, cantidad, motivo, observacion, usuario_id)
            SELECT
                p.id, p.codigo, p.nombre, :cantidad, :motivo, :observacion, :usuario_id
            FROM producto p
            WHERE p.id = :producto_id
        ");

        $stmt->bindParam(":cantidad",    $datos["cantidad"],    PDO::PARAM_INT);
        $stmt->bindParam(":motivo",      $datos["motivo"],      PDO::PARAM_STR);
        $stmt->bindParam(":observacion", $datos["observacion"], PDO::PARAM_STR);

        // Si no hay sesión puede venir null; lo dejamos como PDO::PARAM_NULL o INT según corresponda
        if (empty($datos["usuario_id"])) {
            $usuarioId = null;
            $stmt->bindParam(": suario_id", $usuarioId, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(":usuario_id", $datos["usuario_id"], PDO::PARAM_INT);
        }

        $stmt->bindParam(":producto_id", $datos["producto_id"], PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
    }
}

