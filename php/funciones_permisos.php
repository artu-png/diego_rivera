<?php

function tienePermiso($nombre_permiso) {
    global $conexion;

    if (!isset($_SESSION['id_rol'])) {
        // DEBUG original preserved for reference (no eliminar):
        // exit("DEBUG: No hay id_rol en la sesión. Sesión actual: " . print_r($_SESSION, true));
        // Registro genérico y retorno seguro
        error_log("Usted no tiene permisos para esta acción.");
        return false;
    }

    $id_rol = $_SESSION['id_rol'];

    if (!isset($conexion) || !$conexion) {
        // DEBUG original preserved for reference (no eliminar):
        // exit("DEBUG: La conexión a la BD es nula. Revisa conexion_be.php");
        // Registro genérico y retorno seguro
        error_log("Usted no tiene permisos para esta acción.");
        return false;
    }

    $query = "SELECT p.nombre_permiso 
            FROM permisos p
            INNER JOIN permisos_roles pr ON p.id_permiso = pr.id_permiso
            WHERE pr.id_rol = '$id_rol' AND p.nombre_permiso = '$nombre_permiso'";

    $resultado = mysqli_query($conexion, $query);

    return mysqli_num_rows($resultado) > 0;
}
?>