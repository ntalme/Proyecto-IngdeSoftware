// ---------------------------- ELIMINAR PRODUCTO (CONFIRMACIÓN) --------------------------------------
$(".btnEliminarProducto").click(function(){

    var idProducto = $(this).attr("idProducto");

    Swal.fire({
        title: '¿Estás seguro de eliminar el producto?',
        html: '<p class="swal-text">Esta acción no se puede deshacer.</p>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, borrar producto',
        cancelButtonText: 'Cancelar',
        buttonsStyling: false,
        reverseButtons: true,
        customClass: {
            popup:        'swal-theme',
            title:        'swal-title',
            htmlContainer:'swal-body',
            confirmButton:'btn btn-primary btn-lg swal-confirm',
            cancelButton: 'btn btn-outline-danger btn-lg swal-cancel',
            icon:         'swal-icon'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = "index.php?ruta=crear-producto&idProducto=" + idProducto;
        }
    });
});

// ---------------------------- AÑADIR STOCK (ASIGNAR ID EN MODAL) --------------------------------------
$(".btnAgregarStock").click(function () {

    var idProducto = $(this).attr("idProducto");
    $("#idProductoStock").val(idProducto);
    
});

// ---------------------------- EDITAR PRODUCTO (CARGA VIA AJAX) --------------------------------------
$(".btnEditarProducto").click(function () {

    var idProducto = $(this).attr("idProducto");

    var datos = new FormData();
    datos.append("idProducto", idProducto);

    $.ajax({
        url: "ajax/productos.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuesta) {

            // Helper para normalizar fecha a YYYY-MM-DD
            function normalizaFechaYMD(v) {
                if (!v) return "";
                v = (v + "").trim();
                // Si viene con tiempo (YYYY-MM-DD HH:MM:SS) o (YYYY-MM-DDTHH:MM:SS)
                if (/^\d{4}-\d{2}-\d{2}/.test(v)) return v.substring(0, 10);
                // Si viene como DD/MM/YYYY
                var m = v.match(/^(\d{2})\/(\d{2})\/(\d{4})$/);
                if (m) return `${m[3]}-${m[2]}-${m[1]}`;
                return ""; // formato desconocido
            }

            // Campos
            $("#idProducto").val(respuesta["id"] || "");
            $("#editarNombreProducto").val(respuesta["nombre"] || "");
            $("#editarCodigo").val(respuesta["codigo"] || "");
            $("#editarFormato").val(respuesta["formato"] || "");  
            $("#editarTamano").val(respuesta["tamano"] || "");
            $("#editarMarca").val(respuesta["marca"] || "");
            $("#editarCantidad").val(respuesta["cantidad"] || "");
            $("#editarPrecioCompra").val(respuesta["precio_compra"] || "");
            $("#editarPrecioVenta").val(respuesta["precio_venta"] || "");
            $("#editarProveedor").val(respuesta["proveedor"] || "");

            // Fecha de vencimiento (normalizada a YYYY-MM-DD)
            var fv = normalizaFechaYMD(respuesta["fecha_vencimiento"]);
            $("#editarFechaVencimiento").val(fv);

            // Foto actual
            var foto = (respuesta["imagen"] && respuesta["imagen"].trim() !== "")
                        ? respuesta["imagen"]
                        : "vistas/imagenes/sinfoto.png";
            $("#previewEditar").attr("src", foto);
            $("#imagenActual").val(respuesta["imagen"] || "");

            // limpiar input file (no se puede setear por JS con valor arbitrario)
            $("#editarImagen").val("");
        },
    });
});

// ---------------------------- SUBIR FOTO (NUEVO) + PREVIEW --------------------------------------
$("#nuevaImagen").change(function(){

    var imagen = this.files[0];

    if(imagen){
        if(imagen.type != "image/jpeg" && imagen.type != "image/png"){
            $(this).val(""); // limpiar input
            Swal.fire({
                icon: "error",
                title: "Formato no permitido",
                text: "La imagen debe estar en formato JPG o PNG."
            });
        }
        else if(imagen.size > 2000000){
            $(this).val(""); // limpiar input
            Swal.fire({
                icon: "error",
                title: "Imagen demasiado grande",
                text: "La imagen no debe superar los 2 MB."
            });
        }
        else{
            var lector = new FileReader();
            lector.onload = function(e){
                $("#previewFoto").attr("src", e.target.result);
            };
            lector.readAsDataURL(imagen);
        }
    }
});

// ---------------------------- SUBIR FOTO (EDITAR) + PREVIEW --------------------------------------
$("#editarImagen").change(function(){

    var imagen = this.files[0];

    if(imagen){
        if(imagen.type != "image/jpeg" && imagen.type != "image/png"){
            $(this).val(""); // limpiar input
            Swal.fire({
                icon: "error",
                title: "Formato no permitido",
                text: "La imagen debe estar en formato JPG o PNG."
            });
            // volver a la imagen actual
            var fallback = $("#imagenActual").val() || "vistas/imagenes/sinfoto.png";
            $("#previewEditar").attr("src", fallback);
        }
        else if(imagen.size > 2000000){
            $(this).val(""); // limpiar input
            Swal.fire({
                icon: "error",
                title: "Imagen demasiado grande",
                text: "La imagen no debe superar los 2 MB."
            });
            // volver a la imagen actual
            var fallback2 = $("#imagenActual").val() || "vistas/imagenes/sinfoto.png";
            $("#previewEditar").attr("src", fallback2);
        }
        else{
            var lector = new FileReader();
            lector.onload = function(e){
                $("#previewEditar").attr("src", e.target.result);
            };
            lector.readAsDataURL(imagen);
        }
    }
});




