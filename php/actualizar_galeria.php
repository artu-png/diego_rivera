<?php
include 'conexion_be.php';
include 'funciones_permisos.php';

if (!tienePermiso('editar_contenido')) {
    exit("Acceso denegado: No tienes permisos de edición.");
}

if(isset($_POST['id_galeria_edit'])){
    
    $id = $_POST['id_galeria_edit'];
    $titulo = mysqli_real_escape_string($conexion, $_POST['titulo']);

    $query = "UPDATE galeria SET titulo = '$titulo' WHERE id_galeria = '$id'";

    if(mysqli_query($conexion, $query)){
        echo "<script>
                alert('¡Título de la imagen actualizado!');
                window.location = 'admin_panel.php';
            </script>";
    } else {
        echo "Error al actualizar: " . mysqli_error($conexion);
    }
} else {
    echo "No se recibió el id";
}
?>