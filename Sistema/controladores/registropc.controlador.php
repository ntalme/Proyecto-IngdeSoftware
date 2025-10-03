<?php

class ControladorRegistropc {

  // ---------------------------- MOSTRAR REGISTROS -----------------------------------
  public static function ctrMostrarRegistros($item, $valor) {

    $tabla = "perdida"; 
    return ModeloRegistropc::mdlListar($tabla, $item, $valor);
  }

  // ---------------------------- REGISTRAR ------------------------------------------
  public static function ctrRegistrar($post) {
    if (session_status() === PHP_SESSION_NONE) session_start();

    $productoId  = (int)($post["producto_id"] ?? 0);
    $cantidad    = (int)($post["cantidad"] ?? 0);
    $motivo      = strtolower(trim($post["motivo"] ?? ""));
    $observacion = trim($post["observacion"] ?? "");
    $usuarioId   = isset($_SESSION["id"]) ? (int)$_SESSION["id"] : null;

    if ($productoId <= 0 || $cantidad <= 0) {
      return ["status"=>"error","message"=>"Producto o cantidad inválidos."];
    }

    $permitidos = ["rotura","vencimiento","perdida","merma","consumo_interno","otros"];
    if (!in_array($motivo, $permitidos, true)) $motivo = "otros";

    $datos = [
      "producto_id" => $productoId,
      "cantidad"    => $cantidad,
      "motivo"      => $motivo,
      "observacion" => $observacion,
      "usuario_id"   => $usuarioId
    ];

    $resp = ModeloRegistropc::mdlRegistrar("perdida", $datos);

    return $resp === "ok" 
      ? ["status"=>"ok"] 
      : ["status"=>"error","message"=>"No se pudo registrar."];
  }

  // ---------------------------- CREAR REGISTRO -------------------------------------
  public function ctrCrearRegistro() {

    if (isset($_POST["accion"]) && $_POST["accion"] === "registrarPC") {

      if (session_status() === PHP_SESSION_NONE) session_start();

      $productoId  = (int)($_POST["producto_id"] ?? 0);
      $cantidad    = (int)($_POST["cantidad"] ?? 0);
      $motivo      = strtolower(trim($_POST["motivo"] ?? ""));
      $observacion = trim($_POST["observacion"] ?? "");
      $usuarioId   = isset($_SESSION["id"]) ? (int)$_SESSION["id"] : null;

      if ($productoId <= 0 || $cantidad <= 0) {
        echo "<script>
          Swal.fire({icon:'error', title:'Datos inválidos', text:'Producto o cantidad incorrectos.'});
        </script>";
        return;
      }

      $permitidos = ["rotura","vencimiento","perdida","merma","consumo_interno","otros"];
      if (!in_array($motivo, $permitidos, true)) $motivo = "otros";

      $datos = [
        "producto_id" => $productoId,
        "cantidad"    => $cantidad,
        "motivo"      => $motivo,
        "observacion" => $observacion,
        "usuario_id"  => $usuarioId
      ];

      $respuesta = ModeloRegistropc::mdlRegistrar("perdida", $datos);

      if ($respuesta === "ok") {
        echo "<script>
          Swal.fire({
            icon: 'success',
            title: '¡Registrado!',
            text: 'La pérdida/consumo se guardó correctamente.'
          }).then((r)=>{ if(r.isConfirmed){ window.location = \"perdidas\"; }});
        </script>";
      } else {
        echo "<script>
          Swal.fire({icon:'error', title:'No se pudo registrar', text:'Intente nuevamente.'});
        </script>";
      }
    }
  }
}
