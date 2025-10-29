<?php

class ControladorPromociones {

    // ---------------------------- CREAR PROMOCIÓN ------------------------------------------
    public static function ctrCrearPromocion() {

        if (!isset($_POST["crearPromocion"])) {
            return;
        }

        // === Definir destino de retorno ===
        $redirectTarget = $_POST['redirect_to'] 
            ?? ($_SERVER['HTTP_REFERER'] ?? 'principal');

        $errores = [];

        // --- Validar tipo ---
        $tipo = $_POST["tipo"] ?? null;
        $tipoValido = in_array($tipo, ["descuento","2x1","precio_fijo"], true);
        if (!$tipoValido) {
            $errores[] = "Tipo de promoción inválido.";
        }

        // --- Validar parámetro según tipo ---
        $parametro = null;
        if ($tipoValido && $tipo === "descuento") {
            $raw = $_POST["parametro"] ?? null;
            if ($raw === null || !is_numeric($raw)) {
                $errores[] = "Indique el porcentaje de descuento (1 a 99).";
            } else {
                $parametro = (float)$raw;
                if ($parametro <= 0 || $parametro >= 100) {
                    $errores[] = "El porcentaje de descuento debe ser entre 1 y 99.";
                }
            }
        } elseif ($tipoValido && $tipo === "precio_fijo") {
            $raw = $_POST["parametro"] ?? null;
            if ($raw === null || !is_numeric($raw)) {
                $errores[] = "Indique el precio fijo.";
            } else {
                $parametro = (float)$raw;
                if ($parametro < 0) {
                    $errores[] = "El precio fijo debe ser igual o mayor a 0.";
                }
            }
        } else {
            // 2x1 no requiere parámetro
            $parametro = null;
        }

        // --- Validar fechas ---
        $fecha_inicio_raw = trim($_POST["fecha_inicio"] ?? "");
        $fecha_fin_raw    = trim($_POST["fecha_fin"] ?? "");

        $tz = new DateTimeZone('America/Santiago');
        $fi = $fecha_inicio_raw !== "" ? DateTime::createFromFormat('Y-m-d\TH:i', $fecha_inicio_raw, $tz) : false;
        $ff = $fecha_fin_raw    !== "" ? DateTime::createFromFormat('Y-m-d\TH:i', $fecha_fin_raw, $tz) : false;

        if (!$fi || !$ff) {
            $errores[] = "Debe indicar fecha de inicio y fin válidas.";
        } else {
            $fecha_inicio = $fi->format('Y-m-d H:i:s');
            $fecha_fin    = $ff->format('Y-m-d H:i:s');
            if (strtotime($fecha_fin) <= strtotime($fecha_inicio)) {
                $errores[] = "La fecha de fin debe ser posterior a la fecha de inicio.";
            }
        }

        // --- Validar productos ---
        $productos = isset($_POST["productos"]) ? (array)$_POST["productos"] : [];
        $productos = array_map('intval', $productos);
        $productos = array_filter($productos, fn($v) => $v > 0);
        $productos = array_values(array_unique($productos));

        if (empty($productos)) {
            $errores[] = "Seleccione al menos un producto.";
        }

        // --- Observación (opcional) ---
        $observacion = isset($_POST["observacion"]) ? trim($_POST["observacion"]) : null;
        if ($observacion === '') $observacion = null;

        // --- Si hay errores, avisar y volver a la misma página ---
        if (!empty($errores)) {
            echo '<script>',
                'Swal.fire({',
                    'icon:"error",',
                    'title:"Error al guardar",',
                    'html:', json_encode(implode("<br>", $errores)) ,',',
                    'confirmButtonText:"Cerrar"',
                '}).then(()=>{ window.location = ', json_encode($redirectTarget) ,' ; });',
                '</script>';
            return;
        }

        // --- Insertar promoción ---
        $tablaPromos    = "promocion";
        $tablaPromoProd = "promocion_producto";
        $creado_por     = isset($_SESSION['id']) ? (int)$_SESSION['id'] : null;

        $datos = [
            "tipo"         => $tipo,
            "parametro"    => $parametro,
            "fecha_inicio" => $fecha_inicio,
            "fecha_fin"    => $fecha_fin,
            "observacion"  => $observacion,
            "creado_por"   => $creado_por, // elimine este índice si su tabla no lo tiene
        ];

        $idPromocion = ModeloPromociones::mdlIngresarPromocion($tablaPromos, $datos);

        if ($idPromocion === "error" || !is_numeric($idPromocion)) {
            echo '<script>',
                'Swal.fire({',
                    'icon:"error",',
                    'title:"Error al registrar",',
                    'text:"No se pudo guardar la promoción.",',
                    'confirmButtonText:"Cerrar"',
                '}).then(()=>{ window.location = ', json_encode($redirectTarget) ,' ; });',
                '</script>';
            return;
        }

        // --- Vincular productos ---
        ModeloPromociones::mdlVincularProductos($tablaPromoProd, (int)$idPromocion, $productos);

        /* =======================================================
        🔹 Registrar en historial (sin modificar la lógica)
        ======================================================= */
        require_once "modelos/historial.modelo.php";
        require_once "controladores/historial.controlador.php";

        $usuario = $_SESSION["usuario"] ?? "desconocido";
        $modulo  = "Promociones";
        $accion  = "INSERT";
        $idRegistro = (int)$idPromocion;

        // 🔹 Obtener nombres e IDs de los productos asociados
        $productosInfo = [];
        foreach ($productos as $idProd) {
            $prod = ModeloProductos::mdlObtenerProductoPorId("producto", $idProd);
            if ($prod && isset($prod["nombre"])) {
                $productosInfo[] = "({$idProd}) " . $prod["nombre"];
            } else {
                $productosInfo[] = "({$idProd}) Desconocido";
            }
        }
        $listaProductos = implode(", ", $productosInfo);

        $valorAnterior = null;
        $valorNuevo = json_encode([
            "tipo"                => $tipo,
            "parametro"           => $parametro ?? "Sin información",
            "fecha_inicio"        => $fecha_inicio,
            "fecha_fin"           => $fecha_fin,
            "productos_asociados" => $listaProductos,
            "observacion"         => $observacion ?? "Sin información"
        ], JSON_UNESCAPED_UNICODE);

        ControladorHistorial::ctrRegistrarCambio(
            $usuario,
            $modulo,
            $accion,
            $idRegistro,
            $valorAnterior,
            $valorNuevo
        );
        /* ======================================================= */

        // --- Éxito: volver a la MISMA página desde donde se abrió el modal ---
        echo '<script>',
            'Swal.fire({',
                'icon:"success",',
                'title:"¡Promoción registrada!",',
                'text:"La promoción y sus productos fueron guardados correctamente.",',
                'confirmButtonText:"Cerrar"',
            '}).then(()=>{ window.location = ', json_encode($redirectTarget) ,' ; });',
            '</script>';
    }

    // ---------------------------- MOSTRAR PROMOCIONES --------------------------------------
    public static function ctrMostrarPromociones($item, $valor) {
        $tabla = "promocion"; 
        return ModeloPromociones::mdlMostrarPromociones($tabla, $item, $valor);
    }

    // ---------------------------- PROMOCIONES ACTIVAS --------------------------------------
    public static function ctrPromocionesActivas() {
        return ModeloPromociones::mdlPromocionesActivas();
    }

    // ---------------------------- ELIMINAR PROMOCIÓN ---------------------------------------
    public static function ctrBorrarPromocion() {

        if (isset($_GET["idPromocion"])) {

            $tabla = "promocion";
            $idPromocion = $_GET["idPromocion"];

            // 🔹 Obtener datos antes de eliminar (para el historial)
            $promocionAntes = ModeloPromociones::mdlObtenerPromocionPorId($tabla, $idPromocion);
            $productosVinculados = ModeloPromociones::mdlObtenerProductosPorPromocion("promocion_producto", $idPromocion);

            // Crear listado con nombres e IDs de productos
            $productosInfo = [];
            if (!empty($productosVinculados)) {
                foreach ($productosVinculados as $p) {
                    $prod = ModeloProductos::mdlObtenerProductoPorId("producto", $p["id_producto"]);
                    if ($prod && isset($prod["nombre"])) {
                        $productosInfo[] = "({$p['id_producto']}) " . $prod["nombre"];
                    } else {
                        $productosInfo[] = "({$p['id_producto']}) Desconocido";
                    }
                }
            }

            // Guardar datos legibles para historial (asegurando que el JSON sea válido)
            $valorAnteriorArray = [
                "tipo"                => $promocionAntes["tipo"] ?? "Sin información",
                "parametro"           => $promocionAntes["parametro"] ?? "Sin información",
                "fecha_inicio"        => $promocionAntes["fecha_inicio"] ?? "Sin información",
                "fecha_fin"           => $promocionAntes["fecha_fin"] ?? "Sin información",
                "productos_asociados" => !empty($productosInfo) ? implode(", ", $productosInfo) : "Sin información",
                "observacion"         => $promocionAntes["observacion"] ?? "Sin información"
            ];

            try {
                $valorAnterior = json_encode($valorAnteriorArray, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
            } catch (Exception $e) {
                $valorAnterior = json_encode(["error_json" => "No se pudo codificar la promoción eliminada"], JSON_UNESCAPED_UNICODE);
            }

            // Primero eliminar vínculos en la tabla pivote
            ModeloPromociones::mdlBorrarVinculos("promocion_producto", $idPromocion);

            // Luego eliminar la promoción
            $respuesta = ModeloPromociones::mdlBorrarPromocion($tabla, $idPromocion);

            if ($respuesta == "ok") {

                /* =======================================================
                🔹 Registrar en historial
                ======================================================= */
                require_once "modelos/historial.modelo.php";
                require_once "controladores/historial.controlador.php";

                $usuario = $_SESSION["usuario"] ?? "desconocido";
                $modulo  = "Promociones";
                $accion  = "DELETE";
                $idRegistro = (int)$idPromocion;
                $valorNuevo = null;

                ControladorHistorial::ctrRegistrarCambio(
                    $usuario,
                    $modulo,
                    $accion,
                    $idRegistro,
                    $valorAnterior,
                    $valorNuevo
                );
                /* ======================================================= */

                echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "¡Promoción eliminada!",
                        text: "La promoción ha sido borrada correctamente.",
                        confirmButtonText: "Cerrar"
                    }).then((r)=>{ 
                        if(r.isConfirmed){ 
                            window.location = "crear-venta"; 
                        } 
                    });
                </script>';
            }
        }
    }

    // ---------------------------- MEJOR PROMOCION ------------------------------------------
    private static function escogerMejorPromo($promos, $precioUnit, $cantidad) {
        if (empty($promos)) return null;

        $mejor = null;
        $mejorTotal = $precioUnit * $cantidad;

        foreach ($promos as $p) {
            $sim = self::simularLineaConPromo($p, $precioUnit, $cantidad);
            if ($sim['total_linea'] < $mejorTotal) {
                $mejorTotal = $sim['total_linea'];
                $mejor = $p;
            }
        }
        return $mejor;
    }

    // ---------------------------- SIMULAR PROMO -------------------------------------------
    public static function simularLineaConPromo($promo, $precioUnit, $cantidad) {
        $tipo     = $promo['tipo'];              // enum('descuento','2x1','precio_fijo')
        $param    = isset($promo['parametro']) ? (float)$promo['parametro'] : 0.0;
        $origUnit = (float)$precioUnit;

        $out = [
            'tipo'          => $tipo,
            'parametro'     => $param,
            'precio_unit'   => $origUnit,               // visible
            'paga_unidades' => $cantidad,               // unidades que se pagan
            'gratis'        => 0,
            'total_linea'   => $origUnit * $cantidad,   // total a pagar
            'etiqueta'      => '',
            'detalle'       => '',
            'show_tachado'  => false
        ];

        switch ($tipo) {
            case 'descuento': // parámetro: porcentaje (ej. 25.00)
                $precioDesc          = round($origUnit * (1 - $param/100), 0);
                $out['precio_unit']  = $precioDesc;
                $out['total_linea']  = $precioDesc * $cantidad;
                $out['etiqueta']     = "-".rtrim(rtrim(number_format($param,2,'.',''), '0'),'.')."%";
                $out['detalle']      = "Descuento de {$param}% aplicado";
                $out['show_tachado'] = true;
                break;

            case 'precio_fijo': // parámetro: precio unitario final
                $precioFijo          = round($param, 0);
                $out['precio_unit']  = $precioFijo;
                $out['total_linea']  = $precioFijo * $cantidad;
                $out['etiqueta']     = "Precio fijo";
                $out['detalle']      = "Precio fijo promocional \${$precioFijo}";
                $out['show_tachado'] = true;
                break;

            case '2x1': // por cada 2 paga 1
                $pares               = intdiv($cantidad, 2);
                $resto               = $cantidad % 2;
                $paga                = $pares + $resto;
                $gratis              = $cantidad - $paga;

                $out['paga_unidades']= $paga;
                $out['gratis']       = $gratis;
                $out['total_linea']  = $paga * $origUnit;
                $out['etiqueta']     = "2x1";
                $out['detalle']      = "Promo 2x1: {$gratis} unidad(es) gratis";
                // El precio unitario visual puede quedar igual (el beneficio va al total)
                break;
        }
        return $out;
    }

    // ---------------------------- MEJOR PROMOCION 2----------------------------------------
    public static function mejorPromoParaProducto($idProducto, $precioUnit, $cantidad = 1) {
        $promos = ModeloPromociones::mdlPromosActivasPorProducto($idProducto);
        if (empty($promos)) return null;

        $mejor = self::escogerMejorPromo($promos, $precioUnit, $cantidad);
        if (!$mejor) return null;

        $sim = self::simularLineaConPromo($mejor, $precioUnit, $cantidad);
        $sim['id_promocion'] = (int)$mejor['id'];
        $sim['observacion']  = $mejor['observacion'] ?? '';
        return $sim;
    }

    // ---------------------------- MAPA PROMOS ---------------------------------------------
    public static function ctrMapaPromosActivasPorProducto() {
        return ModeloPromociones::mdlMapaPromosActivasPorProducto();
    }
}
