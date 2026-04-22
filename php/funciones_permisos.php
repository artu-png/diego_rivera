<?php

function tienePermiso($nombre_permiso) {
    global $conexion;

    if (!isset($_SESSION['id_rol'])) {
        exit("DEBUG: No hay id_rol en la sesión. Sesión actual: " . print_r($_SESSION, true));
    }

    $id_rol = $_SESSION['id_rol'];

    if (!$conexion) {
        exit("DEBUG: La conexión a la BD es nula. Revisa conexion_be.php");
    }

    $query = "SELECT p.nombre_permiso 
            FROM permisos p
            INNER JOIN permisos_roles pr ON p.id_permiso = pr.id_permiso
            WHERE pr.id_rol = '$id_rol' AND p.nombre_permiso = '$nombre_permiso'";

    $resultado = mysqli_query($conexion, $query);

    return mysqli_num_rows($resultado) > 0;
}
?>