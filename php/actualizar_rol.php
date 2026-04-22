<?php
session_start();
include 'conexion_be.php';
include 'funciones_permisos.php';

if (!tienePermiso('gestionar_usuarios')) {
    echo "No tienes autorización para realizar esta acción.";
    exit();
}

$id_usuario = $_POST['id_usuario'];
$id_rol = $_POST['id_rol'];

$query = "UPDATE usuario_roles SET id_rol = '$id_rol' WHERE id_usuario = '$id_usuario'";

if (mysqli_query($conexion, $query)) {
    echo "Rol actualizado con éxito.";
} else {
    echo "Error al actualizar: " . mysqli_error($conexion);
}

mysqli_close($conexion);
?>