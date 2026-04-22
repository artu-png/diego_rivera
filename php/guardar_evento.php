<?php
session_start();
include 'conexion_be.php';
include 'funciones_permisos.php';

if (!tienePermiso('publicar_contenido')) {
    exit("Error: No tienes permisos para crear contenido.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $titulo = $_POST['titulo_evento'];
    $f_inicio = $_POST['fecha_inicio'];
    $f_fin = !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : NULL;
    $desc = $_POST['descripcion_evento'];
    $seccion = $_POST['id_seccion'];
    $categoria = $_POST['id_categoria'];
    $estado = $_POST['id_estado'];
    $lugar_texto = $_POST['nombre_lugar'];
    
    $usuario_id = $_SESSION['id_usuario']; 

    $query_ubi = mysqli_query($conexion, "SELECT id_ubicacion FROM ubicacion_eventos WHERE nombre_lugar = '$lugar_texto'");
    if ($row_ubi = mysqli_fetch_assoc($query_ubi)) {
        $id_ubicacion = $row_ubi['id_ubicacion'];
    } else {
        mysqli_query($conexion, "INSERT INTO ubicacion_eventos (nombre_lugar) VALUES ('$lugar_texto')");
        $id_ubicacion = mysqli_insert_id($conexion);
    }

    $sql_evento = "INSERT INTO eventos (titulo_evento, fecha_inicio, fecha_fin, descripcion_evento, id_estado, id_usuario_creador, id_ubicacion, id_categoria, id_seccion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conexion, $sql_evento);
    mysqli_stmt_bind_param($stmt, "ssssiiiii", $titulo, $f_inicio, $f_fin, $desc, $estado, $usuario_id, $id_ubicacion, $categoria, $seccion);

    if (mysqli_stmt_execute($stmt)) {
        $id_evento_nuevo = mysqli_insert_id($conexion);

        if (!empty($_FILES['archivos_multimedia']['name'][0])) {
            
            if (!file_exists('img_eventos')) {
                mkdir('img_eventos', 0777, true);
            }

            foreach ($_FILES['archivos_multimedia']['name'] as $key => $val) {
                $nombre_original = $_FILES['archivos_multimedia']['name'][$key];
                $ruta_temp = $_FILES['archivos_multimedia']['tmp_name'][$key];
                
                $nombre_final = time() . "_" . $nombre_original;
                $ruta_destino = "img_eventos/" . $nombre_final;

                if (move_uploaded_file($ruta_temp, $ruta_destino)) {
                    $sql_img = "INSERT INTO multimedia_eventos (ruta_archivo, tipo_archivo, titulo_archivo, id_evento) VALUES (?, 'image', ?, ?)";
                    $stmt_img = mysqli_prepare($conexion, $sql_img);
                    mysqli_stmt_bind_param($stmt_img, "ssi", $ruta_destino, $nombre_original, $id_evento_nuevo);
                    mysqli_stmt_execute($stmt_img);
                }
            }
        }
        echo '<script>alert("¡Evento publicado con éxito!"); window.location = "https://iguana-angler-curliness.ngrok-free.dev/diego_rivera/admin_panel.php";</script>';
    } else {
        echo "Error al guardar el evento: " . mysqli_error($conexion);
    }
}
?>