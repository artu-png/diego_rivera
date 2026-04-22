<?php
    session_start();
    include("conexion_be.php");

    if(!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
        header("Location: index.php");
        exit();
    }

    $id_comunicado = intval($_GET['id']);

    $query = "SELECT c.*, cat.nombre_categoria 
            FROM comunicados c
            LEFT JOIN categoria_comunicados cat ON c.id_categoria = cat.id_categoria 
            WHERE c.id_comunicado = $id_comunicado";
                
    $resultado = mysqli_query($conexion, $query);

    if(mysqli_num_rows($resultado) === 0) {
        header("Location: index.php");
        exit();
    }

    $comunicado = mysqli_fetch_assoc($resultado);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $comunicado['titulo']; ?> | Jose Diego Maria Rivera</title>
    <link rel="stylesheet" href="css/estilos.css?v=1.40"> 
    <link rel="icon" href="img_video/Adobe Express - file.png">
    <script defer src="js/index.js?v=1.5"></script>
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
                    if (tienePermiso('ver_panel_admin')) {
                        $panelGestionLi = '<li><a href="admin_panel.php">⚙️ Panel de Control</a></li>';
                    }

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

<div class="vista-comunicado">
    <h2 class="titulo-texto"><?php echo $comunicado['titulo']; ?></h2>

    <div class="header-comunicado">
        <p class="fecha-oficial"><?php echo date("d/m/Y", strtotime($comunicado['fecha_publicacion'])); ?></p>
        <button onclick="imprimirComunicado()" class="btn-imprimir">Imprimir Comunicado</button>
    </div>

    <hr>

    <div class="cuerpo-comunicado">
        <?php if(!empty($comunicado['ruta_imagen'])): ?>
            <div class="foto-oficial-contenedor">
                <img src="<?php echo $comunicado['ruta_imagen']; ?>" alt="Imagen Oficial" class="foto-oficial">
            </div>
        <?php endif; ?>

        <div class="texto-comunicado">
            <?php echo nl2br($comunicado['contenido']); ?>
        </div>
    </div>

    
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