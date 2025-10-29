<?php
require_once "MySQL.php";

class ModeloHistorial {

    // ---------------------------- REGISTRAR CAMBIO EN HISTORIAL -----------------------------------------
    public static function mdlRegistrarCambio($usuario, $modulo, $accion, $idRegistro, $valorAnterior, $valorNuevo) {

        $stmt = Conexion::conectar()->prepare("
            INSERT INTO historial_cambio(usuario, modulo, tipo_accion, id_registro_afectado, valor_anterior, valor_nuevo)
            VALUES (:usuario, :modulo, :accion, :id_registro, :valor_anterior, :valor_nuevo)
        ");

        $stmt->bindParam(":usuario", $usuario, PDO::PARAM_STR);
        $stmt->bindParam(":modulo", $modulo, PDO::PARAM_STR);
        $stmt->bindParam(":accion", $accion, PDO::PARAM_STR);
        $stmt->bindParam(":id_registro", $idRegistro, PDO::PARAM_INT);
        $stmt->bindParam(":valor_anterior", $valorAnterior, PDO::PARAM_STR);
        $stmt->bindParam(":valor_nuevo", $valorNuevo, PDO::PARAM_STR);

        return $stmt->execute();
    }

    // ---------------------------- OBTENER HISTORIAL COMPLETO -----------------------------------------
    public static function mdlObtenerHistorial() {
        $stmt = Conexion::conectar()->prepare("
            SELECT * FROM historial_cambio ORDER BY fecha DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
