<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="css/estilos.css?v=1.20">
    <link rel="icon" href="img_video/Adobe Express - file.png">
    <script defer src="js/index.js"></script>
</head>
<body>
    <header class="header">
        <nav class="nav">
            <a href="index.php" class="logo">
                <img class="img" src="img_video/WhatsApp_Image_2026-03-02_at_4.15.30_PM-removebg-preview.png" alt="">
                <h1>Jose Diego Maria Rivera</h1>
            </a>
            <button class="nav-toggle" aria-label="Abrir menu">☰</button>
            
            <ul class="nav-menu" id="navlinks">
                <li class="nav-menu-item"><a href="index.php" class="nav-menu-link">inicio</a></li>

                <?php
                include 'conexion_be.php';
                
                $query_menu = mysqli_query($conexion, "SELECT * FROM secciones_paginas");

                while($seccion = mysqli_fetch_assoc($query_menu)) {
                    $nombre = $seccion['nombre_seccion'];
                    echo '<li class="nav-menu-item">
                            <a href="seccion.php?id='.$seccion['id_seccion'].'" class="nav-menu-link">'.strtolower($nombre).'</a>
                        </li>';
                }

                $panelGestionLi = ""; 

                if (isset($_SESSION['usuario'])) {
                    // Si tiene permiso, guardamos el <li> del panel en la variable
                    if (tienePermiso('ver_panel_admin')) {
                        $panelGestionLi = '<li><a href="admin_panel.php">⚙️ Panel de Control</a></li>';
                    }

                    // 2. Imprimimos el bloque completo
                    echo '
                    <li class="nav-menu-item user-panel-container">
                        <button id="userBtn" class="nav-menu-link user-btn" style="border: none; cursor: pointer; display: flex; align-items: center;">
                            👤 ' . htmlspecialchars($_SESSION['usuario']) . '
                        </button>
                        <div id="userPanel" class="user-panel-dropdown">
                            <div class="user-info">
                                <p class="user-name"><strong>' . htmlspecialchars($_SESSION['nombre_completo'] ?? $_SESSION['usuario']) . '</strong></p>
                                <span class="user-role">' . strtoupper($_SESSION['rol']) . '</span>
                            </div>
                            <hr>
                            <ul class="user-actions">
                                ' . $panelGestionLi . ' 
                                <li><a href="cerrar_sesion.php" class="logout-link">🚪 Cerrar Sesión</a></li>
                            </ul>
                        </div>
                    </li>';
                } else {
                    echo '
                    <li class="nav-menu-item">
                        <a href="login.php" class="nav-menu-link" style="font-weight: bold;"> Inicia sesión</a>
                    </li>';
                }
                ?>
            </ul>
        </nav>
    </header>

    <body>
    <div class="contenido_formulario">
        <h2>Crear Nuevo Usuario</h2>
        <form action="registro_be.php" method="POST" class="formulario-registro">
        
        <div class="grupo-input">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" placeholder="Nombre" required class="input-moderno">
        </div>

        <div class="grupo-input">
            <label for="apellido">Apellido</label>
            <input type="text" id="apellido" name="apellido" placeholder="Apellido" required class="input-moderno">
        </div>

        <div class="grupo-input">
            <label for="telefono">Teléfono de Contacto</label>
            <input type="text" id="telefono" name="telefono" placeholder="Ej: +58 412..." class="input-moderno">
        </div>

        <div class="grupo-input">
            <label for="usuario">Usuario</label>
            <input type="text" name="usuario_login" placeholder="Nombre de usuario" required class="input-moderno">
        </div>

        <div class="grupo-input">
            <label for="correo">Correo</label>
            <input type="email" id="correo" name="correo" placeholder="Correo Electrónico" required class="input-moderno">
        </div>

        <div class="grupo-input">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Contraseña" required class="input-moderno">
        </div>

        <button type="submit" name="registrar" class="boton-moderno">Registrar Usuario</button>
    </form>
</div>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<footer class="footer-container">
    
    <div class="footer-row">
        <div class="footer-item">
            <i class="fa-solid fa-phone"></i>
            <span>0412-6486575</span>
        </div>

        <div class="footer-item">
            <i class="fa-solid fa-location-dot"></i>
            <a href="https://maps.app.goo.gl/P2JfVgBKfsBDXrfXA" target="_blank">
                Urb. El Caujaro Lote IJ, Av. 49 G-01 / San Fco.
            </a>
        </div>

        <div class="footer-item">
            <i class="fa-brands fa-instagram"></i>
            <a href="https://www.instagram.com/uep.diegorivera" target="_blank">@uep.diegorivera</a>
        </div>
    </div>
</footer>


</body>
</html>