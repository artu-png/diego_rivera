<?php
include 'conexion_be.php';
include 'funciones_permisos.php';

if (!tienePermiso('editar_contenido')) {
    exit("Acceso denegado: No tienes permisos de edición.");
}

if(isset($_POST['id_documentos_edit'])){
    $id = $_POST['id_documentos_edit'];
    $titulo = mysqli_real_escape_string($conexion, $_POST['titulo_documento']);
    $desc = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    
    $query = "UPDATE documentos_descargables SET 
            titulo_documento = '$titulo', 
            descripcion = '$desc' 
            WHERE id_documentos = '$id'";

    if(mysqli_query($conexion, $query)){
        echo "<script>
                alert('¡Documento actualizado!');
                window.location = 'admin_panel.php';
            </script>";
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?>