<?php
session_start();
include 'conexion_be.php';
include 'funciones_permisos.php';

if (!tienePermiso('publicar_contenido')) {
    exit("Error: No tienes permisos para crear contenido.");
}

$titulo = $_POST['titulo_documento'];
$id_cat = $_POST['id_categoria_doc'];
$id_sec = $_POST['id_seccion'];
$desc   = $_POST['descripcion'];
$id_user = $_POST['id_usuario_creador'];
$fecha  = date('Y-m-d');

// manejo del archivo
$nombre_archivo = time() . "_" . $_FILES['archivo_pdf']['name'];
$ruta_temporal  = $_FILES['archivo_pdf']['tmp_name'];
$ruta_destino   = "archivos_docs/" . $nombre_archivo;

if(move_uploaded_file($ruta_temporal, $ruta_destino)){
    $sql = "INSERT INTO documentos_descargables 
            (titulo_documento, url_archivo, fecha_subida, descripcion, id_categoria_doc, id_seccion, id_usuario_creador) 
            VALUES 
            ('$titulo', '$ruta_destino', '$fecha', '$desc', '$id_cat', '$id_sec', '$id_user')";
    
    if(mysqli_query($conexion, $sql)){
        echo "<script>alert('Documento registrado por el administrador ID: $id_user'); window.location='https://iguana-angler-curliness.ngrok-free.dev/diego_rivera/admin_panel.php';</script>";
    } else {
        echo "Error al registrar en BD: " . mysqli_error($conexion);
    }
}
?>