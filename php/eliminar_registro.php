<?php
session_start();
include 'conexion_be.php'; /** @var mysqli $conexion */
include 'funciones_permisos.php';

// Obtener parámetros y sanitizar
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$tabla = isset($_GET['tabla']) ? $_GET['tabla'] : '';
$columna = isset($_GET['columna']) ? $_GET['columna'] : '';

if (empty($tabla) || empty($columna) || $id <= 0) {
    echo "<script>alert('Parámetros inválidos.'); window.location.href = 'admin_panel.php';</script>";
    exit();
}

// Si la operación es sobre secciones, requerir permiso específico
if ($tabla === 'secciones_paginas') {
    if (!tienePermiso('administrar_secciones')) {
        echo "<script>
                alert('No tienes autorización para eliminar secciones.');
                window.location.href = 'admin_panel.php';
            </script>";
        exit();
    }
} else {
    // Para otras tablas, mantener la comprobación original
    if (!tienePermiso('eliminar_contenido') && !tienePermiso('gestionar_usuarios')) {
        echo "<script>
                alert('No tienes autorización para eliminar registros.');
                window.location.href = 'admin_panel.php';
            </script>";
        exit();
    }
}

// Si elimina un documento, borrar el archivo físico
if($tabla == 'documentos_descargables'){
    $res = mysqli_query($conexion, "SELECT url_archivo FROM $tabla WHERE $columna = $id");
    $reg = mysqli_fetch_assoc($res);
    if(!empty($reg['url_archivo']) && file_exists($reg['url_archivo'])) unlink($reg['url_archivo']);
}

// Ejecución de la eliminación
$query = "DELETE FROM $tabla WHERE $columna = $id";
if(mysqli_query($conexion, $query)){
    header("Location: admin_panel.php");
    exit();
} else {
    echo "<script>alert('Error al eliminar el registro.'); window.location.href = 'admin_panel.php';</script>";
    exit();
}
?>