<?php

require_once "MySQL.php";

class ModeloProductos {

    // ---------------------------- MOSTRAR PRODUCTOS --------------------------------------------
    public static function mdlMostrarProductos($tabla, $item, $valor){

        if($item !=null){

            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt -> fetch();
        }
        else{
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
            $stmt->execute();
            return $stmt -> fetchAll();
        }
    }

    // ---------------------------- AÑADIR PRODUCTOS ---------------------------------------------
    public static function mdlIngresarProducto($tabla, $datos) {

        try {
            $pdo = Conexion::conectar();
            $sql = "INSERT INTO $tabla (
                        codigo, nombre, formato, tamano, marca, cantidad,
                        precio_compra, precio_venta, proveedor, fecha_vencimiento, imagen
                    ) VALUES (
                        :codigo, :nombre, :formato, :tamano, :marca, :cantidad,
                        :precio_compra, :precio_venta, :proveedor, :fecha_vencimiento, :imagen
                    )";

            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(":codigo",        $datos["codigo"],        PDO::PARAM_STR);
            $stmt->bindValue(":nombre",        $datos["nombre"],        PDO::PARAM_STR);
            $stmt->bindValue(":formato",       $datos["formato"],       PDO::PARAM_STR);
            $stmt->bindValue(":tamano",        $datos["tamano"],        PDO::PARAM_STR);
            $stmt->bindValue(":marca",         $datos["marca"],         PDO::PARAM_STR);
            $stmt->bindValue(":cantidad",      (int)$datos["cantidad"], PDO::PARAM_INT);
            $stmt->bindValue(":precio_compra", $datos["precio_compra"], PDO::PARAM_STR);
            $stmt->bindValue(":precio_venta",  $datos["precio_venta"],  PDO::PARAM_STR);
            $stmt->bindValue(":proveedor",     $datos["proveedor"],     PDO::PARAM_STR);

            // --- fecha_vencimiento opcional ---
            if (empty($datos["fecha_vencimiento"])) {
                $stmt->bindValue(":fecha_vencimiento", null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(":fecha_vencimiento", $datos["fecha_vencimiento"], PDO::PARAM_STR);
            }

            // --- imagen opcional ---
            if (empty($datos["imagen"])) {
                $stmt->bindValue(":imagen", null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(":imagen", $datos["imagen"], PDO::PARAM_STR);
            }

            if ($stmt->execute()) {
                $stmt = null;
                return "ok";
            } else {
                $error = $stmt->errorInfo();
                $stmt = null;
                return "error: ".$error[2]; // devuelve detalle para debug
            }

        } catch (PDOException $e) {
            return "error: ".$e->getMessage();
        }
    }

    // ---------------------------- ACTUALIZAR PRODUCTO ------------------------------------------
    public static function mdlActualizarProducto($tabla, $item1, $valor1, $valor2){

        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE id = :id");

        $stmt->bindParam(":$item1", $valor1, PDO::PARAM_STR);
        $stmt->bindParam(":id", $valor2, PDO::PARAM_STR);

        if($stmt->execute()){
            return "ok";
        } else {
            return "error";
        }

        $stmt -> close();
        $stmt = null;
    }

    // ---------------------------- ELIMINAR PRODUCTO --------------------------------------------
    public static function mdlEliminarProducto($tabla, $datos) {

        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");
        $stmt->bindParam(":id", $datos, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }

        $stmt -> close();
        $stmt = null;
    }

    // ---------------------------- ACTUALIZAR STOCK ---------------------------------------------
    public static function mdlSumarStock($tabla, $id, $cantidadNueva, $fechaRecepcion, $fechaVencimiento) {

        $stmt = Conexion::conectar()->prepare(
            "UPDATE $tabla 
            SET cantidad = cantidad + :cantidad, 
                fecha_recepcion = :fechaRecepcion, 
                fecha_vencimiento = :fechaVencimiento 
            WHERE id = :id"
        );

        $stmt->bindParam(":cantidad", $cantidadNueva, PDO::PARAM_INT);
        $stmt->bindParam(":fechaRecepcion", $fechaRecepcion, PDO::PARAM_STR);
        $stmt->bindParam(":fechaVencimiento", $fechaVencimiento, PDO::PARAM_STR);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }

        $stmt = null;
    }

    // ---------------------------- EDITAR PRODUCTO ----------------------------------------------
    public static function mdlEditarProducto($tabla, $datos) {

        $stmt = Conexion::conectar()->prepare("UPDATE $tabla 
            SET nombre            = :nombre,
                codigo            = :codigo,
                formato           = :formato,
                tamano            = :tamano,
                marca             = :marca,
                cantidad          = :cantidad,
                precio_compra     = :precio_compra,
                precio_venta      = :precio_venta,
                proveedor         = :proveedor,
                imagen            = :imagen,
                fecha_vencimiento = :fecha_vencimiento
            WHERE id = :id");

        $stmt->bindParam(":nombre",            $datos["nombre"],            PDO::PARAM_STR);
        $stmt->bindParam(":codigo",            $datos["codigo"],            PDO::PARAM_STR);
        $stmt->bindParam(":formato",           $datos["formato"],           PDO::PARAM_STR);
        $stmt->bindParam(":tamano",            $datos["tamano"],            PDO::PARAM_STR);
        $stmt->bindParam(":marca",             $datos["marca"],             PDO::PARAM_STR);
        $stmt->bindParam(":cantidad",          $datos["cantidad"],          PDO::PARAM_INT);
        $stmt->bindParam(":precio_compra",     $datos["precio_compra"],     PDO::PARAM_STR);
        $stmt->bindParam(":precio_venta",      $datos["precio_venta"],      PDO::PARAM_STR);
        $stmt->bindParam(":proveedor",         $datos["proveedor"],         PDO::PARAM_STR);
        $stmt->bindParam(":imagen",            $datos["imagen"],            PDO::PARAM_STR);
        $stmt->bindParam(":fecha_vencimiento", $datos["fecha_vencimiento"], PDO::PARAM_STR); // <-- nuevo
        $stmt->bindParam(":id",                $datos["id"],                PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }

        $stmt = null;
    }

    // ---------------------------- CONFIGURAR STOCK MINIMO --------------------------------------
    public static function mdlConfigurarStockMinimo($tabla, $datos){
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla 
                                            SET stock_minimo = :stock_minimo
                                            WHERE id = :id");
        $stmt->bindParam(":stock_minimo", $datos["stock_minimo"], PDO::PARAM_INT);
        $stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);
        if ($stmt->execute()) { $stmt = null; return "ok"; }
        $stmt = null; return "error";
    }

    // ---------------------------- ACTUALIZAR STOCK MINIMO --------------------------------------
    public static function mdlActualizarStockMinimo($idProducto, $minimo){
        try {
        $pdo = Conexion::conectar();
        // OJO con el nombre real de la tabla: normalmente es "productos"
        $sql = "UPDATE producto SET stock_minimo = :min WHERE id = :id LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':min', (int)$minimo, PDO::PARAM_INT);
        $stmt->bindValue(':id',  (int)$idProducto, PDO::PARAM_INT);
        return $stmt->execute() ? 'ok' : 'error';
        } catch (Throwable $e) {
        // error_log($e->getMessage());
        return 'error';
        }
    }

    // ---------------------------- LISTADO DE PRODUCTOS CON STOCK CRÍTICO -----------------------
    public static function mdlProductosStockCritico($tabla) {
        $sql = "SELECT id, codigo, nombre, formato, tamano, marca, cantidad, stock_minimo, precio_venta, proveedor
                FROM $tabla
                WHERE cantidad = 0
                   OR (stock_minimo > 0 AND cantidad <= stock_minimo)
                ORDER BY (cantidad = 0) DESC, (cantidad - stock_minimo) ASC, nombre ASC";

        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ---------------------------- ACTIVAR / INACTIVAR PRODUCTO ---------------------------------
    public static  function mdlCambiarEstadoProducto($tabla, $datos){

        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET estado = :estado WHERE id = :id");

        $stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_INT);
        $stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);

        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

        $stmt = null;
    }

    // ---------------------------- MOSTRAR PRODUCTOS ACTIVOS ------------------------------------
    public static function mdlMostrarProductosActivos($tabla){
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE estado = 1");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ---------------------------- FILTRAR PRODUCTOS POR ESTADO ---------------------------------
    public static function mdlFiltrarProductosPorEstado($tabla, $estado) {
        if ($estado === "activo") {
            $sql = "SELECT * FROM $tabla WHERE estado = 1";
        } elseif ($estado === "inactivo") {
            $sql = "SELECT * FROM $tabla WHERE estado = 0";
        } elseif ($estado === "sinstock") {
            $sql = "SELECT * FROM $tabla WHERE cantidad <= 0";
        } else {
            $sql = "SELECT * FROM $tabla"; // todos
        }

        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ---------------------------- MOSTRAR PRODUCTOS RECIENTES ----------------------------------
    public static function mdlMostrarProductosRecientes($tabla, $dias = 7, $limite = 50){
        if($dias == 0){
            $sql = "SELECT * FROM $tabla
                    WHERE DATE(fecha_ingreso) = CURDATE()
                    ORDER BY fecha_ingreso DESC
                    LIMIT :limite";
            $stmt = Conexion::conectar()->prepare($sql);
            $stmt->bindValue(":limite", (int)$limite, PDO::PARAM_INT);
        } else {
            $sql = "SELECT * FROM $tabla
                    WHERE fecha_ingreso IS NOT NULL
                    AND fecha_ingreso <> '0000-00-00 00:00:00'
                    AND fecha_ingreso >= DATE_SUB(NOW(), INTERVAL :dias DAY)
                    ORDER BY fecha_ingreso DESC
                    LIMIT :limite";
            $stmt = Conexion::conectar()->prepare($sql);
            $stmt->bindValue(":dias", (int)$dias, PDO::PARAM_INT);
            $stmt->bindValue(":limite", (int)$limite, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function mdlObtenerProductoPorId($tabla, $id){
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE id = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}