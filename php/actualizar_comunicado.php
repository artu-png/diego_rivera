<?php
session_start();
include 'conexion_be.php';
include 'funciones_permisos.php';

if (!tienePermiso('editar_contenido')) {
    exit("Acceso denegado: No tienes permisos de edición.");
}

if(isset($_POST['id_comunicado'])){
    $id = $_POST['id_comunicado'];
    $titulo = mysqli_real_escape_string($conexion, $_POST['titulo']);
    $desc = mysqli_real_escape_string($conexion, $_POST['contenido']);
    $fecha_v = $_POST['fecha_vencimiento'];
    $cat = $_POST['id_categoria'];

    $query = "UPDATE comunicados SET 
            titulo = '$titulo', 
            contenido = '$desc', 
            fecha_vencimiento = '$fecha_v', 
            id_categoria = '$cat'
            WHERE id_comunicado = '$id'";

    if(mysqli_query($conexion, $query)){
        echo "<script>
                alert('¡Comunicado actualizado con éxito!');
                window.location = 'admin_panel.php';
            </script>";
    } else {
        echo "Error al actualizar: " . mysqli_error($conexion);
    }
}
?>