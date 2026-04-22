<?php
session_start();

include 'conexion_be.php';
mysqli_set_charset($conexion, "utf8mb4");
include 'funciones_permisos.php';

if (!tienePermiso('publicar_contenido')) {
    die("Error: Tu rol (ID: " . ($_SESSION['id_rol'] ?? 'Nulo') . ") no tiene asignado el permiso 'subir_contenido' en la base de datos.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $contenido = $_POST['contenido'];
    $vencimiento = $_POST['fecha_vencimiento'];
    $id_seccion = $_POST['id_seccion'];
    $id_categoria = $_POST['id_categoria'];
    $id_usuario = $_SESSION['id_usuario'];
    $ruta_final = NULL;

    if (isset($_FILES['imagen_comunicado']) && $_FILES['imagen_comunicado']['error'] == 0) {
        $directorio = "uploads/comunicados/";
        
        if (!file_exists($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $nombre_archivo = time() . "_" . basename($_FILES['imagen_comunicado']['name']);
        $ruta_destino = $directorio . $nombre_archivo;

        if (move_uploaded_file($_FILES['imagen_comunicado']['tmp_name'], $ruta_destino)) {
            $ruta_final = $ruta_destino;
        }
    }

    $sql = "INSERT INTO comunicados (titulo, contenido, ruta_imagen, fecha_publicacion, fecha_vencimiento, id_usuario_creador, id_seccion, id_categoria) 
            VALUES (?, ?, ?, NOW(), ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conexion, $sql);
    
    mysqli_stmt_bind_param($stmt, "ssssiii", $titulo, $contenido, $ruta_final, $vencimiento, $id_usuario, $id_seccion, $id_categoria);
    
    if(mysqli_stmt_execute($stmt)){
        echo '<script>
                alert("¡Comunicado publicado con éxito!");
                window.location = "https://iguana-angler-curliness.ngrok-free.dev/diego_rivera/admin_panel.php";
                </script>';
    } else {
        echo "Error al guardar: " . mysqli_stmt_error($stmt);
    }
}
?>