<?php
    session_start();
    include 'conexion_be.php';

    $login_input = mysqli_real_escape_string($conexion, $_POST['correo']); 
    $password = $_POST['password'];
    $ip_acceso = $_SERVER['REMOTE_ADDR'];

    $query = "SELECT u.id_usuario, u.usuario_login, u.contraseña, u.correo, u.estado_verificado, 
                    p.nombre_usuario, p.apellido_usuario, 
                    r.id_rol, r.nombre_rol 
            FROM usuarios u 
            INNER JOIN perfiles p ON u.id_usuario = p.id_usuario 
            INNER JOIN usuario_roles ur ON u.id_usuario = ur.id_usuario
            INNER JOIN roles r ON ur.id_rol = r.id_rol
            WHERE u.usuario_login = '$login_input'";

    $resultado = mysqli_query($conexion, $query);

    if(mysqli_num_rows($resultado) > 0){
        $datos = mysqli_fetch_assoc($resultado);
        
        $password_db = $datos['contraseña'];
        
        $es_valida = false;

        if (password_verify($password, $password_db)) {
            $es_valida = true; 
        } elseif (hash('sha512', $password) === $password_db) {
            $es_valida = true;
        }

        if($es_valida){
            
            if($datos['estado_verificado'] == 0){
                echo '<script>
                    alert("Cuenta no verificada. Revisa tu correo.");
                    window.location = "login.php";
                </script>';
                exit();
            }

            $id_usuario = $datos['id_usuario'];
            $token_sesion = bin2hex(random_bytes(32));

            mysqli_query($conexion, "DELETE FROM sesiones_activas WHERE id_usuario = '$id_usuario'");

            $query_sesion = "INSERT INTO sesiones_activas (id_usuario, token_sesion, ip_acceso) VALUES ('$id_usuario', '$token_sesion', '$ip_acceso')";
            mysqli_query($conexion, $query_sesion);

            $query_historial = "INSERT INTO historial_logins (id_usuario, fecha_hora) VALUES ('$id_usuario', NOW())";
            mysqli_query($conexion, $query_historial);

            $_SESSION['id_usuario'] = $id_usuario;
            $_SESSION['token_sesion'] = $token_sesion; 
            $_SESSION['usuario'] = $datos['usuario_login']; 
            $_SESSION['nombre_completo'] = $datos['nombre_usuario'] . " " . $datos['apellido_usuario'];
            $_SESSION['id_rol'] = $datos['id_rol'];
            $_SESSION['correo'] = $datos['correo'];
            $_SESSION['rol'] = $datos['nombre_rol'];

            header("location: index.php");
            exit();

        } else {
            echo '<script>
                alert("Usuario o contraseña incorrectos.");
                window.location = "login.php"; 
            </script>';
            exit();
        }
        
    } else {
        echo '<script>
            alert("Usuario o contraseña incorrectos.");
            window.location = "login.php"; 
        </script>';
        exit();
    }

    mysqli_close($conexion);
?>