<?php
session_start();
include 'conexion_be.php';
include 'funciones_permisos.php';

if (!tienePermiso('gestionar_usuarios')) {
    exit("No autorizado");
}

$data = json_decode(file_get_contents('php://input'), true);
$nombre_rol = $data['nombre'];
$permisos = $data['permisos'];

$query_rol = "INSERT INTO roles (nombre_rol) VALUES ('$nombre_rol')";
if (mysqli_query($conexion, $query_rol)) {
    $id_nuevo_rol = mysqli_insert_id($conexion);

    foreach ($permisos as $id_permiso) {
        mysqli_query($conexion, "INSERT INTO permisos_roles (id_rol, id_permiso) VALUES ('$id_nuevo_rol', '$id_permiso')");
    }
    echo "Rol '$nombre_rol' creado exitosamente con sus permisos.";
} else {
    echo "Error al crear el rol: " . mysqli_error($conexion);
}

mysqli_close($conexion);
?>