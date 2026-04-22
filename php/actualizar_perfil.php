<?php
error_reporting(0); 
header('Content-Type: application/json');

include 'conexion_be.php';
session_start();

if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['token_sesion'])) {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Sesión expirada'
    ]);
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

$nombre_completo = mysqli_real_escape_string($conexion, $_POST['nombre'] ?? '');
$correo_nuevo = mysqli_real_escape_string($conexion, $_POST['correo'] ?? '');
$nueva_pass = $_POST['nueva_pass'] ?? '';

$partes = explode(" ", $nombre_completo, 2);
$nombre = $partes[0];
$apellido = isset($partes[1]) ? $partes[1] : '';

mysqli_begin_transaction($conexion);

try {
    $query1 = "UPDATE perfiles SET nombre_usuario = '$nombre', apellido_usuario = '$apellido' WHERE id_usuario = '$id_usuario'";
    if(!mysqli_query($conexion, $query1)) throw new Exception("Error en perfiles");

    $query2 = "UPDATE usuarios SET correo = '$correo_nuevo' WHERE id_usuario = '$id_usuario'";
    if(!mysqli_query($conexion, $query2)) throw new Exception("Error en usuarios");

    if (!empty($nueva_pass)) {
        $pass_encriptada = hash('sha512', $nueva_pass);
        $query3 = "UPDATE usuarios SET contraseña = '$pass_encriptada' WHERE id_usuario = '$id_usuario'";
        if(!mysqli_query($conexion, $query3)) throw new Exception("Error en password");
    }

    mysqli_commit($conexion);

    $_SESSION['nombre_completo'] = $nombre_completo;
    $_SESSION['correo'] = $correo_nuevo;

    echo json_encode(['status' => 'success']);

} catch (Exception $e) {
    mysqli_rollback($conexion);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

mysqli_close($conexion);
?>