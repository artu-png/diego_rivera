<?php
session_start();
include 'conexion_be.php';

if (isset($_SESSION['id_usuario'])) {
    $id_user = $_SESSION['id_usuario'];
    mysqli_query($conexion, "DELETE FROM sesiones_activas WHERE id_usuario = '$id_user'");
}

session_destroy();
header("location: index.php");
exit();
?>