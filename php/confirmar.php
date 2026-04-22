<?php
    include 'conexion_be.php';

    if(isset($_GET['token'])){
        $token = $_GET['token'];

        $verificar = mysqli_query($conexion, "SELECT * FROM usuarios WHERE token_verificacion = '$token' LIMIT 1");

        if(mysqli_num_rows($verificar) > 0){
            $actualizar = mysqli_query($conexion, "UPDATE usuarios SET estado_verificado = 1, token_verificacion = NULL WHERE token_verificacion = '$token'");

            echo '
                <script>
                    alert("¡Cuenta activada con éxito! Ya puedes iniciar sesión.");
                    window.location = "https://iguana-angler-curliness.ngrok-free.dev/diego_rivera/index.php";
                </script>
            ';
        } else {
            echo "Token inválido o cuenta ya activada.";
        }
    } else {
        header("Location: https://iguana-angler-curliness.ngrok-free.dev/diego_rivera/index.php");
    }
?>