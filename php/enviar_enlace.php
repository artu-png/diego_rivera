<?php
include 'conexion_be.php';

use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    require 'librerias/PHPMailer-master/src/Exception.php';
    require 'librerias/PHPMailer-master/src/PHPMailer.php';
    require 'librerias/PHPMailer-master/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conexion, $_POST['email']);

    $check_user = mysqli_query($conexion, "SELECT id_usuario, usuario_login FROM usuarios WHERE correo = '$email'");
    
    if (mysqli_num_rows($check_user) > 0) {
        $usuario = mysqli_fetch_assoc($check_user);
        $id_usuario = $usuario['id_usuario'];
        $nombre = $usuario['usuario_login'];

        $token = bin2hex(random_bytes(16));
        $expiracion = date("Y-m-d H:i:s", strtotime('+1 hour'));

        mysqli_query($conexion, "DELETE FROM recuperacion_password WHERE id_usuario = '$id_usuario'");
        
        $query_insert = "INSERT INTO recuperacion_password (id_usuario, token, expiracion, usados) 
                        VALUES ('$id_usuario', '$token', '$expiracion', 0)";

        if (mysqli_query($conexion, $query_insert)) {
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host       = getenv('SMTP_HOST'); 
                $mail->SMTPAuth   = true;
                $mail->Username   = getenv('SMTP_USER'); 
                $mail->Password   = getenv('SMTP_PASS'); 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = getenv('SMTP_PORT');

                $mail->setFrom(getenv('SMTP_USER'), 'Unidad Educativa Privada Jose Diego Maria Rivera');
                $mail->addAddress($email, $nombre);

                $url = "https://iguana-angler-curliness.ngrok-free.dev/diego_rivera/restablecer.php?token=" . $token;

                $mail->isHTML(true);
                $mail->Subject = 'Recuperacion de Acceso - Colegio Rivera';
                $mail->Body    = "
                    <html>
                    <body style='font-family: Arial, sans-serif;'>
                        <h2>Hola, $nombre</h2>
                        <p>Has solicitado restablecer tu contraseña para el portal institucional.</p>
                        <p>Haz clic en el siguiente botón para continuar (Válido por 1 hora):</p>
                        <a href='$url' style='background:#003366; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>Restablecer Contraseña</a>
                        <br><br>
                        <p>Si no solicitaste esto, puedes ignorar este mensaje.</p>
                    </body>
                    </html>";

                $mail->send();
                echo "<script>alert('Enlace enviado con éxito. Revisa tu correo.'); window.location='https://iguana-angler-curliness.ngrok-free.dev/diego_rivera/login.php';</script>";

            } catch (Exception $e) {
                echo "Error al enviar: {$mail->ErrorInfo}";
            }
        }
    } else {
        echo "<script>alert('El correo no está registrado.'); window.history.back();</script>";
    }
}
?>