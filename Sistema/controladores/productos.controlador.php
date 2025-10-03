<?php

class ControladorProductos{
    
    /* ===================================== PRODUCTOS =============================================== */
    // ---------------------------- AGREGAR PRODUCTO --------------------------------------
    public static function ctrCrearProducto() { 

        if (!isset($_POST["nuevoCodigo"])) {
            return;
        }

        // Validaciones de campos 
        $ok = (
            preg_match('/^[0-9]+$/', $_POST["nuevoCodigo"]) && 

            // Nombre: hasta 100 caracteres, letras/números + símbolos comerciales
            mb_strlen($_POST["nuevoNombreProducto"], 'UTF-8') <= 100 &&
            preg_match('/^[\p{L}\p{N}\s°\.\,\-\(\)\/&%+\#:\'"]+$/u', $_POST["nuevoNombreProducto"]) && 

            preg_match('/^[a-zA-Z0-9\sáéíóúÁÉÍÓÚñÑ().,-]+$/u', $_POST["nuevoFormato"]) &&   
            preg_match('/^[0-9a-zA-Z\s°mlMLccCCL.]+$/', $_POST["nuevoTamano"]) && 

            // Marca: hasta 50 caracteres, letras/números + símbolos básicos
            mb_strlen($_POST["nuevaMarca"], 'UTF-8') <= 50 &&
            preg_match('/^[\p{L}\p{N}\s\.\-&\'"]+$/u', $_POST["nuevaMarca"]) && 

            preg_match('/^[0-9]+$/', $_POST["nuevaCantidad"]) && 
            preg_match('/^\d+(\.\d{1,2})?$/', $_POST["nuevoPrecioCompra"]) && 
            preg_match('/^\d+(\.\d{1,2})?$/', $_POST["nuevoPrecioVenta"]) && 
            preg_match('/^[a-zA-Z0-9\sáéíóúÁÉÍÓÚñÑ.\-\/&]+$/u', $_POST["nuevoProveedor"])
        );

        if (!$ok) {
            echo '<script>
                Swal.fire({
                    icon:"error",
                    title:"Datos inválidos",
                    text:"Revise los campos del formulario (especialmente Nombre o Marca)."
                });
            </script>';
            return;
        }

        // ---------- Fecha opcional -> NULL si viene vacía ----------
        $fechaV = isset($_POST["nuevaFechaVencimiento"]) ? trim($_POST["nuevaFechaVencimiento"]) : "";
        $fechaV = ($fechaV === "") ? null : $_POST["nuevaFechaVencimiento"];

        // ---------- Procesar imagen (opcional) -> NULL si no hay ----------
        $rutaImagen = null;
        if (isset($_FILES["nuevaImagen"]) && $_FILES["nuevaImagen"]["error"] === UPLOAD_ERR_OK) {
            $tmp  = $_FILES["nuevaImagen"]["tmp_name"];
            $size = $_FILES["nuevaImagen"]["size"];
            $name = $_FILES["nuevaImagen"]["name"];

            if ($size <= 2 * 1024 * 1024) { // 2 MB
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                if ($ext === "jpeg") $ext = "jpg";
                if (in_array($ext, ["jpg", "png"])) {
                    $carpeta = "vistas/imagenes/productos";
                    if (!is_dir($carpeta)) {
                        mkdir($carpeta, 0755, true);
                    }
                    $archivoNuevo = $carpeta . "/" . uniqid("prod_") . "." . $ext;
                    if (move_uploaded_file($tmp, $archivoNuevo)) {
                        $rutaImagen = $archivoNuevo;
                    }
                }
            }
        }

        // ---------- Insertar en tabla ----------
        $tabla = "producto";
        $datos = array( 
            "codigo"            => $_POST["nuevoCodigo"], 
            "nombre"            => $_POST["nuevoNombreProducto"], 
            "formato"           => $_POST["nuevoFormato"], 
            "tamano"            => $_POST["nuevoTamano"], 
            "marca"             => $_POST["nuevaMarca"], 
            "cantidad"          => (int) $_POST["nuevaCantidad"],
            "precio_compra"     => $_POST["nuevoPrecioCompra"],
            "precio_venta"      => $_POST["nuevoPrecioVenta"], 
            "fecha_vencimiento" => $fechaV,        // NULL si vacío
            "proveedor"         => $_POST["nuevoProveedor"], 
            "imagen"            => $rutaImagen     // NULL si no hay
        ); 

        $respuesta = ModeloProductos::mdlIngresarProducto($tabla, $datos);

        if ($respuesta === "ok") { 
            echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "¡Producto registrado!",
                    text: "El producto ha sido guardado correctamente.",
                    confirmButtonText: "Cerrar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = "crear-producto";
                    }
                });
            </script>'; 
        } else {
            $detalle = (is_string($respuesta) && strpos($respuesta, "error") === 0) ? $respuesta : "Ocurrió un problema al guardar el producto.";
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Error al registrar",
                    text: '.json_encode($detalle).',
                    confirmButtonText: "Cerrar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = "crear-producto";
                    }
                });
            </script>'; 
        }
    }

    // --------------------------- MOSTRAR PRODUCTOS --------------------------------------
    public static function ctrMostrarProductos($item, $valor){ 

        // Define la tabla
        $tabla = "producto"; 
        // Llama al modelo para consultar
        $respuesta = ModeloProductos::mdlMostrarProductos($tabla, $item, $valor); 
        return $respuesta; 
    } 

    // --------------------------- EDITAR PRODUCTO ----------------------------------------
    public static function ctrEditarProducto(){

        // Revisa si se envió el formulario de edición
        if(isset($_POST["editarProducto"])){

            // Valida nombre y formato
            if (
                preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]+$/u', $_POST["editarNombreProducto"]) &&
                preg_match('/^[a-zA-Z0-9\sáéíóúÁÉÍÓÚñÑ().,-]+$/u', $_POST["editarFormato"])
            ){

                // ===== Validación y normalización de fecha de vencimiento =====
                $fechaVenc = null; // por defecto null
                if (isset($_POST["editarFechaVencimiento"])) {
                    $fv = trim($_POST["editarFechaVencimiento"]);
                    if ($fv !== "") {
                        // Acepte 'YYYY-MM-DD' o 'DD/MM/YYYY'
                        $dt = DateTime::createFromFormat('Y-m-d', $fv) ?: DateTime::createFromFormat('d/m/Y', $fv);
                        if ($dt) {
                            $fechaVenc = $dt->format('Y-m-d'); // normaliza a YYYY-MM-DD
                        } else {
                            echo '<script>
                                Swal.fire({
                                    icon: "error",
                                    title: "Fecha inválida",
                                    text: "Use el formato YYYY-MM-DD o DD/MM/YYYY para la fecha de vencimiento.",
                                    confirmButtonText: "Cerrar"
                                }).then((r)=>{ if(r.isConfirmed){ window.location="crear-producto"; }});
                            </script>';
                            return;
                        }
                    }
                }

                // Toma la imagen actual si existe
                $rutaImagen = isset($_POST["imagenActual"]) ? trim($_POST["imagenActual"]) : "";

                // Revisa si subieron imagen
                if (isset($_FILES["editarImagen"]) && $_FILES["editarImagen"]["error"] === UPLOAD_ERR_OK) {
                    $tmp  = $_FILES["editarImagen"]["tmp_name"];
                    $size = $_FILES["editarImagen"]["size"];
                    $name = $_FILES["editarImagen"]["name"];

                    // Limita a 2MB
                    if ($size <= 2 * 1024 * 1024) {
                        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                        if ($ext === "jpeg") $ext = "jpg";
                        if (in_array($ext, ["jpg", "png"])) {
                            $carpeta = "vistas/imagenes/productos";
                            if (!is_dir($carpeta)) {
                                mkdir($carpeta, 0755, true);
                            }
                            $archivoNuevo = $carpeta . "/" . uniqid("prod_") . "." . $ext;
                            if (move_uploaded_file($tmp, $archivoNuevo)) {
                                if (!empty($rutaImagen) && file_exists($rutaImagen)) {
                                    @unlink($rutaImagen);
                                }
                                $rutaImagen = $archivoNuevo;
                            }
                        }
                    }
                }

                // Define la tabla
                $tabla = "producto";
                // Arma arreglo con los datos editados
                $datos = array(
                    "id"                 => $_POST["idProducto"],
                    "nombre"             => $_POST["editarNombreProducto"],
                    "codigo"             => $_POST["editarCodigo"],
                    "formato"            => $_POST["editarFormato"],
                    "tamano"             => $_POST["editarTamano"],
                    "marca"              => $_POST["editarMarca"],
                    "cantidad"           => $_POST["editarCantidad"],
                    "precio_compra"      => $_POST["editarPrecioCompra"],
                    "precio_venta"       => $_POST["editarPrecioVenta"],
                    "proveedor"          => $_POST["editarProveedor"],
                    "imagen"             => $rutaImagen,
                    "fecha_vencimiento"  => $fechaVenc // <-- NUEVO
                );

                // Llama al modelo para actualizar
                $respuesta = ModeloProductos::mdlEditarProducto($tabla, $datos);

                // Si todo salió bien
                if ($respuesta == "ok") {
                    echo '<script>
                        Swal.fire({
                            icon: "success",
                            title: "¡Producto editado!",
                            text: "El producto ha sido editado correctamente.",
                            confirmButtonText: "Cerrar"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = "crear-producto";
                            }
                        });
                    </script>';
                }

            } else {
                // Si falló la validación de nombre/formato
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Error al registrar",
                        text: "El nombre y/o formato no pueden llevar caracteres no válidos.",
                        confirmButtonText: "Cerrar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = "crear-producto";
                        }
                    });
                </script>';
            }
        }
    }

    // --------------------------- ELIMINAR PRODUCTO --------------------------------------
    public static function ctrEliminarProducto(){

        // Revisa si viene id por GET
        if(isset($_GET["idProducto"])){

            // Define la tabla
            $tabla = "producto";
            // Toma el id del producto
            $datos = $_GET["idProducto"];

            // Llama al modelo para eliminar
            $respuesta = ModeloProductos::mdlEliminarProducto($tabla, $datos);

            // Si la eliminación fue exitosa
            if ($respuesta == "ok") {
                echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "¡Producto Borrado!",
                        text: "El producto ha sido borrado correctamente.",
                        confirmButtonText: "Cerrar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = "crear-producto";
                        }
                    });
                </script>';
            }
        }
    }

    /* ========================================= STOCK =============================================== */
    // --------------------------- AÑADIR STOCK -------------------------------------------
    public static function ctrAgregarStock() {

        // Revisa si viene el id del producto para stock
        if (isset($_POST["idProductoStock"])) {

            // Define la tabla
            $tabla = "producto";
            // Toma el id del producto
            $id = $_POST["idProductoStock"];
            // Toma la cantidad a sumar
            $cantidadNueva = $_POST["nuevaCantidadStock"];
            // Toma la fecha de recepción
            $fechaRecepcion = $_POST["nuevaFechaRecepcion"];
            // Toma la fecha de vencimiento (si aplica)
            $fechaVencimiento = $_POST["nuevaFechaVencimiento"];

            // Llama al modelo
            $respuesta = ModeloProductos::mdlSumarStock($tabla, $id, $cantidadNueva, $fechaRecepcion, $fechaVencimiento);

            // Si se actualizó correctamente
            if ($respuesta == "ok") {
            echo '<script>
                Swal.fire({
                icon: "success",
                title: "Stock actualizado",
                showConfirmButton: false,
                timer: 1500
                }).then(() => {
                window.location = "anadir-stock";
                });
            </script>';
            }
        }
    }

    // --------------------------- CONFIGURAR STOCK MÍNIMO --------------------------------
    public function ctrConfigurarStockMinimo() {
        if (!isset($_POST['configurarStockMinimo'])) return;

        // === Caso A: lote (producto_id[] + minimos[ID]) ===
        if (!empty($_POST['producto_id']) && is_array($_POST['producto_id'])) {

            $ids  = array_map('intval', $_POST['producto_id']);
            $mins = (isset($_POST['minimos']) && is_array($_POST['minimos'])) ? $_POST['minimos'] : [];

            $ok = 0; $fail = 0; $errs = [];

            foreach ($ids as $id) {
                if ($id <= 0) { $fail++; $errs[] = "ID inválido"; continue; }

                $min = isset($mins[$id]) ? (int)$mins[$id] : 0;
                if ($min < 0) { $fail++; $errs[] = "ID $id: mínimo inválido"; continue; }

                $res = ModeloProductos::mdlConfigurarStockMinimo("producto", [
                    "id"           => $id,
                    "stock_minimo" => $min
                ]);

                if ($res === 'ok') { $ok++; }
                else { $fail++; $errs[] = "ID $id: error BD"; }
            }

            // Mensajes SweetAlert
            if ($ok && !$fail) {
                echo "<script>
                        Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Se actualizaron $ok productos correctamente.',
                        timer: 2000,
                        showConfirmButton: false
                        }).then(() => { window.location.replace(window.location.pathname + window.location.search); });
                    </script>";
            } elseif ($ok && $fail) {
                $errores = htmlspecialchars(implode('; ', $errs));
                echo "<script>
                        Swal.fire({
                        icon: 'warning',
                        title: 'Parcial',
                        html: 'Actualizados $ok productos.<br>Errores: $fail.<br><small>$errores</small>',
                        timer: 3500,
                        showConfirmButton: false
                        }).then(() => { window.location.reload(); });
                    </script>";
            } else {
                $errores = htmlspecialchars(implode('; ', $errs));
                echo "<script>
                        Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: 'No se guardó nada.<br><small>$errores</small>'
                        }).then(() => { window.location.reload(); });
                    </script>";
            }
            return;
        }

        // === Caso B: uno solo (producto_id + nuevoStockMinimo) ===
        if (!empty($_POST['producto_id']) && !is_array($_POST['producto_id'])) {

            $id  = (int) $_POST['producto_id'];
            $min = isset($_POST['nuevoStockMinimo']) ? (int) $_POST['nuevoStockMinimo'] : 0;

            if ($id > 0 && $min >= 0) {
                $res = ModeloProductos::mdlConfigurarStockMinimo("producto", [
                    "id"           => $id,
                    "stock_minimo" => $min
                ]);

                if ($res === 'ok') {
                    echo "<script>
                            Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: 'Stock mínimo actualizado.',
                            timer: 2000,
                            showConfirmButton: false
                            }).then(() => { window.location.reload(); });
                        </script>";
                } else {
                    echo "<script>
                            Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo actualizar.'
                            }).then(() => { window.location.reload(); });
                        </script>";
                }
            } else {
                echo "<script>
                        Swal.fire({
                        icon: 'warning',
                        title: 'Datos incompletos',
                        text: 'Por favor, revise la información.'
                        }).then(() => { window.location.reload(); });
                    </script>";
            }
        }
    }

    // --------------------------- OBTENER LISTADO DE STOCK CRÍTICO -----------------------
    public static function ctrProductosStockCritico() {

        // Define la tabla
        $tabla = "producto";
        // Devuelve listado crítico desde el modelo
        return ModeloProductos::mdlProductosStockCritico($tabla);
    }

    // --------------------------- OBTENER DESCARGAR LISTADO DE STOCK CRÍTICO -------------
    public static function ctrExportarStockCriticoPDF() {
        
        // Llama al propio método para obtener los datos
        $datos = self::ctrProductosStockCritico();

        // Fecha/hora Chile
        $tz    = new DateTimeZone('America/Santiago');
        $ahora = new DateTime('now', $tz);
        $fecha = $ahora->format('d-m-Y H:i'); // 24h

        // Revisa si está disponible IntlDateFormatter
        if (class_exists('IntlDateFormatter')) {
                // Crea formateador local
                $fmt = new IntlDateFormatter('es_CL', IntlDateFormatter::LONG, IntlDateFormatter::SHORT, 'America/Santiago');
            $fecha = ucfirst($fmt->format($ahora));
        }

        // Construir HTML del reporte
        $html = '
        <html>
        <head>
        <meta charset="utf-8">
        <style>
            @page { margin: 24px 24px 40px 24px; }
            body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
            h1 { font-size: 18px; margin: 0 0 6px 0; }
            .sub { color:#666; margin-bottom:12px; }
            table { width:100%; border-collapse: collapse; }
            th, td { border:1px solid #ddd; padding:6px 8px; }
            th { background:#f5f5f5; text-align:left; }
            .text-right { text-align:right; }
            .badge { display:inline-block; padding:2px 6px; border-radius:4px; font-size:11px; }
            .bg-danger { background:#dc3545; color:#fff; }
            .bg-warning { background:#ffc107; color:#222; }
            footer { position: fixed; bottom: -10px; left:0; right:0; text-align:center; font-size:10px; color:#666; }
        </style>
        </head>
        <body>
        <h1>Listado de Stock Crítico</h1>
        <div class="sub">Generado: ' . htmlspecialchars($fecha) . '</div>
        <table>
            <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Formato</th>
                <th>Tamaño</th>
                <th>Marca</th>
                <th class="text-right">Cantidad</th>
                <th class="text-right">Stock mínimo</th>
                <th class="text-right">Precio venta</th>
                <th>Proveedor</th>
                <th>Estado</th>
            </tr>
            </thead>
            <tbody>';

        // Si no hay productos críticos
        if (empty($datos)) {
            $html .= '<tr><td colspan="9" style="text-align:center;color:#777;padding:16px">No hay productos críticos</td></tr>';
        // Si hay datos
        } else {
            foreach ($datos as $p) {
                $estado = ((int)$p["cantidad"] === 0)
                    ? '<span class="badge bg-danger">Agotado</span>'
                    : '<span class="badge bg-warning">Bajo mínimo</span>';

                $html .= '<tr>
                    <td>'.htmlspecialchars($p["codigo"]).'</td>
                    <td>'.htmlspecialchars($p["nombre"]).'</td>
                    <td>'.htmlspecialchars($p["formato"]).'</td>
                    <td>'.htmlspecialchars($p["tamano"]).'</td>
                    <td>'.htmlspecialchars($p["marca"]).'</td>
                    <td class="text-right">'.(int)$p["cantidad"].'</td>
                    <td class="text-right">'.(int)($p["stock_minimo"] ?? 0).'</td>
                    <td class="text-right">$'.number_format((float)$p["precio_venta"], 0, ",", ".").'</td>
                    <td>'.htmlspecialchars($p["proveedor"] ?? "").'</td>
                    <td>'.$estado.'</td>
                </tr>';
            }
        }

        // Render con Composer
        $html .= '</tbody></table>
        <footer>Botillería Joaquín — Reporte de Stock Crítico</footer>
        </body></html>';

        // Render con Composer
        require_once __DIR__ . "/../vendor/autoload.php";

        // Crea instancia de Dompdf con opciones
        $dompdf = new Dompdf\Dompdf([
            "chroot" => __DIR__ . "/../",
            "isRemoteEnabled" => true
        ]);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->render();

        // Descargar
        $dompdf->stream("stock_critico.pdf", ["Attachment" => true]);
        exit;
    }

    /* ========================================= FILTRAR =============================================== */
    // --------------------------- CAMBIAR ESTADO PRODUCTO --------------------------------
    static public function ctrCambiarEstadoProducto(){

        // Verifica que vengan ambos datos por POST
        if(isset($_POST["idProducto"]) && isset($_POST["nuevoEstado"])){

            // Define la tabla
            $tabla = "producto";

            // Arma arreglo con id y nuevo estado
            $datos = array(
                "id" => $_POST["idProducto"],
                "estado" => $_POST["nuevoEstado"]
            );

            // Llama al modelo para actualizar
            $respuesta = ModeloProductos::mdlCambiarEstadoProducto($tabla, $datos);

            // Devuelve la respuesta 
            return $respuesta;
        }
    }

    //--------------------------- MOSTRAR SOLO PRODUCTOS ACTIVOS --------------------------
    public static function ctrMostrarProductosActivos(){
        // Define la tabla
        $tabla = "producto";
        // Llama al modelo y devuelve
        return ModeloProductos::mdlMostrarProductosActivos($tabla);
    }

    // --------------------------- FILTRAR PRODUCTOS POR ESTADO ---------------------------
    public static function ctrFiltrarProductosPorEstado($estado) {
        // Define la tabla
        $tabla = "producto";
        // Llama al modelo y devuelve
        return ModeloProductos::mdlFiltrarProductosPorEstado($tabla, $estado);
    }

    // --------------------------- MOSTRAR PRODUCTOS RECIENTES ----------------------------
    public static function ctrMostrarProductosRecientes($dias = 7, $limite = 50){
        // Define la tabla
        $tabla = "producto";
        // Llama al modelo y devuelve
        return ModeloProductos::mdlMostrarProductosRecientes($tabla, $dias, $limite);
    }

}
