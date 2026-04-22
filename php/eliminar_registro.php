<?php
session_start();
include 'conexion_be.php';
include 'funciones_permisos.php';

if (!tienePermiso('eliminar_contenido') && !tienePermiso('gestionar_usuarios')) {
    echo "<script>
            alert('No tienes autorización para eliminar registros.');
            window.location.href = 'admin_panel.php';
        </script>";
    exit();
}

$id = $_GET['id'];
$tabla = $_GET['tabla'];
$columna = $_GET['columna'];

if($tabla == 'documentos_descargables'){
    $res = mysqli_query($conexion, "SELECT url_archivo FROM $tabla WHERE $columna = $id");
    $reg = mysqli_fetch_assoc($res);
    if(file_exists($reg['url_archivo'])) unlink($reg['url_archivo']);
}

$query = "DELETE FROM $tabla WHERE $columna = $id";
if(mysqli_query($conexion, $query)){
    header("Location: admin_panel.php");
}
?>