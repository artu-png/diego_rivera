<?php
include(__DIR__ . "/conexion_be.php");
include(__DIR__ . "/control_sesion.php");

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'administrador') {
    die("Acceso denegado: No tienes permisos suficientes.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = mysqli_real_escape_string($conexion, $_POST['id_seccion']);
    $nombre = mysqli_real_escape_string($conexion, $_POST['nuevo_nombre']);

    if (empty(trim($nombre))) {
        die("Error: El nombre de la sección no puede estar vacío.");
    }

    $sql = "UPDATE secciones_paginas SET nombre_seccion = '$nombre' WHERE id_seccion = '$id'";

    if (mysqli_query($conexion, $sql)) {
        echo "success";
    } else {
        echo "Error al actualizar la base de datos.";
    }
} else {
    die("Método no permitido.");
}
?>