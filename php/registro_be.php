<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    require 'librerias/PHPMailer-master/src/Exception.php';
    require 'librerias/PHPMailer-master/src/PHPMailer.php';
    require 'librerias/PHPMailer-master/src/SMTP.php';

    include 'conexion_be.php';

    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $usuario_login = $_POST['usuario_login'];
    $password = $_POST['password'];
    $telefono = $_POST['telefono'];

    $password = hash('sha512', $password);

    $verificar_datos = mysqli_query($conexion, "SELECT * FROM usuarios WHERE correo='$correo' OR usuario_login='$usuario_login'");

    if(mysqli_num_rows($verificar_datos) > 0){
        echo '<script>alert("El correo o el nombre de usuario ya están registrados."); window.history.back();</script>';
        exit(); 
    }

    $token = bin2hex(random_bytes(16));

    mysqli_begin_transaction($conexion);

    try {
        $query_usuario = "INSERT INTO usuarios(usuario_login, correo, contraseña, token_verificacion, estado_verificado) VALUES('$usuario_login', '$correo', '$password', '$token', 0)";
        if(!mysqli_query($conexion, $query_usuario)) throw new Exception("Error en Tabla Usuarios");

        $ultimo_id = mysqli_insert_id($conexion);

        $query_perfil = "INSERT INTO perfiles(id_usuario, nombre_usuario, apellido_usuario, telefono) VALUES('$ultimo_id', '$nombre', '$apellido', '$telefono')";
        if(!mysqli_query($conexion, $query_perfil)) throw new Exception("Error en Tabla Perfiles");

        $id_rol_default = 4;
        $query_rol = "INSERT INTO usuario_roles(id_usuario, id_rol) VALUES('$ultimo_id', '$id_rol_default')";
        if(!mysqli_query($conexion, $query_rol)) throw new Exception("Error en Tabla Roles");
        
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = getenv('SMTP_HOST');
        $mail->SMTPAuth   = true;
        $mail->Username   = getenv('SMTP_USER');
        $mail->Password   = getenv('SMTP_PASS');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = getenv('SMTP_PORT');

        $mail->setFrom(getenv('SMTP_USER'), 'Unidad Educativa Privada Jose Diego Maria Rivera');
        $mail->addAddress($correo, $nombre . " " . $apellido); 

        $mail->isHTML(true);
        $mail->Subject = 'Confirma tu registro';
        $url_confirmacion = "https://iguana-angler-curliness.ngrok-free.dev/diego_rivera/confirmar.php?token=" . $token;
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; border: 1px solid #ddd; padding: 20px;'>
                <h1 style='color: #333;'>¡Hola, $nombre!</h1>
                <p>Gracias por registrarte en el sistema de la Unidad Educativa Privada Jose Diego Maria Rivera.</p>
                <p>Para activar tu cuenta, haz clic en el botón:</p>
                <br>
                <a href='$url_confirmacion' style='background-color: #007bff; color: white; padding: 12px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>Confirmar mi cuenta</a>
            </div>";

        $mail->send();

        mysqli_commit($conexion);

        echo '<script>alert("Usuario creado. Revisa tu correo para confirmar."); window.location = "https://iguana-angler-curliness.ngrok-free.dev/diego_rivera/index.php";</script>';

    } catch (Exception $e) {
        mysqli_rollback($conexion);
        
        echo '<script>alert("Error en el registro: ' . $mail->ErrorInfo . '"); window.history.back();</script>';
    }

    mysqli_close($conexion);
?>