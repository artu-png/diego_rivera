<?php
if (!function_exists('cargarEnv')) {
    function cargarEnv($ruta) {
        if (!file_exists($ruta)) return false;

        $lineas = file($ruta, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lineas as $linea) {
            if (strpos(trim($linea), '#') === 0) continue; 
            
            if (strpos($linea, '=') !== false) {
                list($nombre, $valor) = explode('=', $linea, 2);
                $_ENV[trim($nombre)] = trim($valor);
                putenv(trim($nombre) . "=" . trim($valor));
            }
        }
    }
}



cargarEnv(__DIR__ . '/.env');

$conexion = mysqli_connect(
    getenv('DB_HOST'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_NAME')
);

mysqli_set_charset($conexion, "utf8");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>