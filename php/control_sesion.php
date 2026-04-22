<?php
session_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'conexion_be.php';
include 'funciones_permisos.php';

if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['token_sesion'])) {
    session_destroy();
    header("location: login.php");
    exit();
}

$id_user = $_SESSION['id_usuario'];
$token_web = $_SESSION['token_sesion'];

$query_seguridad = "SELECT * FROM sesiones_activas 
                    WHERE id_usuario = '$id_user' 
                    AND token_sesion = '$token_web'";

$resultado_seguridad = mysqli_query($conexion, $query_seguridad);

if (mysqli_num_rows($resultado_seguridad) == 0) {
    session_destroy();
    echo '<script>alert("Sesión inválida."); window.location = "login.php";</script>';
    exit();
}

if (!tienePermiso('ver_panel_admin')) {
    header("location: index.php"); 
    exit();
}

mysqli_query($conexion, "UPDATE sesiones_activas SET ultima_actividad = NOW() WHERE id_usuario = '$id_user'");
?>