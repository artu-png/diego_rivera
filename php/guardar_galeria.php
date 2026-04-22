<?php
session_start();
include 'conexion_be.php';
include 'funciones_permisos.php';

if (!tienePermiso('publicar_contenido')) {
    exit("Error: No tienes permisos para crear contenido.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $seccion = $_POST['id_seccion'];
    
    $nombre_archivo = $_FILES['archivo_foto']['name'];
    $ruta_temporal = $_FILES['archivo_foto']['tmp_name'];
    $carpeta_destino = "img_galeria/" . $nombre_archivo;

    if(move_uploaded_file($ruta_temporal, $carpeta_destino)) {
        
        $sql = "INSERT INTO galeria (titulo, ruta_imagen, id_seccion) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $titulo, $carpeta_destino, $seccion);
        
        if(mysqli_stmt_execute($stmt)) {
            echo '<script>alert("Imagen subida con éxito"); window.location = "https://iguana-angler-curliness.ngrok-free.dev/diego_rivera/admin_panel.php";</script>';
        }
    } else {
        echo "Error al subir el archivo físico.";
    }
}
?>