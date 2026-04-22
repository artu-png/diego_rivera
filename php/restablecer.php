<?php
    include 'conexion_be.php';
    $token_valido = false;
    $id_usuario_recuperar = null;

    if (isset($_GET['token'])) {
        $token = mysqli_real_escape_string($conexion, $_GET['token']);
        
        $query = "SELECT id_usuario FROM recuperacion_password 
                WHERE token = '$token' AND expiracion > NOW() AND usados = 0";
        $resultado = mysqli_query($conexion, $query);

        if (mysqli_num_rows($resultado) > 0) {
            $datos = mysqli_fetch_assoc($resultado);
            $id_usuario_recuperar = $datos['id_usuario'];
            $token_valido = true;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_usuario'])) {
        $id_user = $_POST['id_usuario'];
        $token_usado = $_POST['token_form'];
        $nueva_pass = $_POST['nueva_pass'];
        
        $pass_encriptada = password_hash($nueva_pass, PASSWORD_BCRYPT);

        $update_user = "UPDATE usuarios SET contraseña = '$pass_encriptada' WHERE id_usuario = '$id_user'";
        
        if (mysqli_query($conexion, $update_user)) {
            mysqli_query($conexion, "UPDATE recuperacion_password SET usados = 1 WHERE token = '$token_usado'");
            
            echo "<script>alert('Contraseña actualizada con éxito.'); window.location='login.php';</script>";
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="css/estilos.css?v=1.50"> </head>
<body>
    <div class="contenido_formulario">
        <h2>Nueva Contraseña</h2>
        
        <?php if ($token_valido): ?>
            <form action="restablecer.php" method="POST" class="formulario-registro">
                <p style="font-size: 0.85rem; color: #555; text-align: center;">
                    Ingresa tu nueva clave de acceso para el portal.
                </p>
                
                <input type="hidden" name="id_usuario" value="<?php echo $id_usuario_recuperar; ?>">
                <input type="hidden" name="token_form" value="<?php echo $token; ?>">

                <div class="grupo-input">
                    <label>Nueva Contraseña</label>
                    <input type="password" name="nueva_pass" class="input-moderno" required minlength="6">
                </div>
                
                <button type="submit" class="boton-moderno">Actualizar Contraseña</button>
            </form>
        <?php else: ?>
            <div style="text-align: center; padding: 20px;">
                <p>El enlace es inválido, ya fue utilizado o ha expirado.</p>
                <a href="recuperar.php" class="boton-moderno">Solicitar nuevo enlace</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>