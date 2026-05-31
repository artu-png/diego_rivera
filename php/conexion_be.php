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
                $_SERVER[trim($nombre)] = trim($valor); 
                putenv(trim($nombre) . "=" . trim($valor));
            }
        }
    }
}

cargarEnv(dirname(__DIR__) . '/.env');

$conexion = mysqli_connect(
    $_ENV['DB_HOST'] ?? '',
    $_ENV['DB_USER'] ?? '',
    $_ENV['DB_PASS'] ?? '',
    $_ENV['DB_NAME'] ?? ''
);

if (!$conexion) {
    die("<b style='color:red;'>Error crítico de conexión:</b> " . mysqli_connect_error());
}

mysqli_set_charset($conexion, "utf8");
?>