<?php
include 'conexion_be.php';
include 'funciones_permisos.php';

if (!tienePermiso('administrar_secciones')) {
    exit("Error: No tienes permisos para administrar secciones.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre_seccion']);

    if (!empty($nombre)) {
        $sql = "INSERT INTO secciones_paginas (nombre_seccion) VALUES ('$nombre')";
        
        if (mysqli_query($conexion, $sql)) {
            echo '<script>alert("Sección agregada con éxito"); window.location = "https://iguana-angler-curliness.ngrok-free.dev/diego_rivera/admin_panel.php";</script>';
        } else {
            echo "Error: " . mysqli_error($conexion);
        }
    }
}
?>