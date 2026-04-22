<?php
session_start();
include 'conexion_be.php';
mysqli_set_charset($conexion, "utf8mb4");
include 'funciones_permisos.php';

if (!tienePermiso('editar_contenido')) {
    exit("Acceso denegado: No tienes permisos de edición.");
}

if (isset($_POST['id_evento_edit'])) {
    $id = $_POST['id_evento_edit'];
    if (empty($id) || $id == 'undefined') {
    die("Error crítico: El sistema no pudo recuperar el ID del evento. Por favor, recarga la página e intenta de nuevo.");
    }
    
    $titulo = mysqli_real_escape_string($conexion, $_POST['titulo_evento']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion_evento']);
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $categoria = $_POST['id_categoria'];
    $estado = $_POST['id_estado'];
    $lugar = mysqli_real_escape_string($conexion, $_POST['nombre_lugar']);

    $queryUbicacion = "UPDATE ubicacion_eventos u 
                    INNER JOIN eventos e ON e.id_ubicacion = u.id_ubicacion 
                    SET u.nombre_lugar = '$lugar' 
                    WHERE e.id_evento = '$id'";
    mysqli_query($conexion, $queryUbicacion);

    $queryEvento = "UPDATE eventos SET 
                    titulo_evento = '$titulo', 
                    descripcion_evento = '$descripcion', 
                    fecha_inicio = '$fecha_inicio', 
                    fecha_fin = '$fecha_fin', 
                    id_categoria = '$categoria', 
                    id_estado = '$estado' 
                    WHERE id_evento = '$id'";
    
    if (mysqli_query($conexion, $queryEvento)) {
        
        if (isset($_FILES['archivos_multimedia']) && $_FILES['archivos_multimedia']['error'][0] == 0) {
            foreach ($_FILES['archivos_multimedia']['tmp_name'] as $key => $tmp_name) {
                $nombre_archivo = $_FILES['archivos_multimedia']['name'][$key];
                $ruta_destino = "uploads/eventos/" . time() . "_" . $nombre_archivo;
                
                if (move_uploaded_file($tmp_name, $ruta_destino)) {
                    $queryFoto = "INSERT INTO multimedia_eventos (id_evento, ruta_archivo, tipo_archivo) 
                                VALUES ('$id', '$ruta_destino', 'imagen')";
                    mysqli_query($conexion, $queryFoto);
                }
            }
        }

        echo "<script>
                alert('Evento actualizado correctamente');
                window.location = 'admin_panel.php';
            </script>";
    } else {
        echo "Error al actualizar: " . mysqli_error($conexion);
    }
}
?>