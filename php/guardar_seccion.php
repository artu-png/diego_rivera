<?php
include 'conexion_be.php'; /** @var mysqli $conexion */
include 'funciones_permisos.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
    exit();
}

if (!function_exists('tienePermiso')) {
    echo json_encode(["status" => "error", "message" => "Error interno: funciones no cargadas."]);
    exit();
}

if (!tienePermiso('administrar_secciones')) {
    echo json_encode(["status" => "error", "message" => "Usted no tiene permisos para esta acción."]);
    exit();
}

$nombre = isset($_POST['nombre_seccion']) ? mysqli_real_escape_string($conexion, $_POST['nombre_seccion']) : '';

if (empty($nombre)) {
    echo json_encode(["status" => "error", "message" => "El nombre de la sección no puede estar vacío."]);
    exit();
}

if (!isset($conexion) || !$conexion) {
    echo json_encode(["status" => "error", "message" => "Error en la conexión a la base de datos."]);
    exit();
}

$sql = "INSERT INTO secciones_paginas (nombre_seccion) VALUES ('$nombre')";

if (mysqli_query($conexion, $sql)) {
    echo json_encode(["status" => "success", "message" => "Sección agregada con éxito"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error al insertar: " . mysqli_error($conexion)]);
}

?>