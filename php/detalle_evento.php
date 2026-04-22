<?php
    session_start();
    include 'conexion_be.php';

    if(!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
        header("Location: index.php");
        exit();
    }

    $id_evento = intval($_GET['id']);

    $sql = "SELECT e.*, cat.nombre_categoria, est.estado, ubi.nombre_lugar
            FROM eventos e
            LEFT JOIN categorias_eventos cat ON e.id_categoria = cat.id_categoria
            LEFT JOIN estado_evento est ON e.id_estado = est.id_estado
            LEFT JOIN ubicacion_eventos ubi ON e.id_ubicacion = ubi.id_ubicacion
            WHERE e.id_evento = $id_evento";
            
    $res = mysqli_query($conexion, $sql);

    if(!$res || mysqli_num_rows($res) === 0) {
        header("Location: index.php");
        exit();
    }

    $evento = mysqli_fetch_assoc($res);

    $sql_img = "SELECT ruta_archivo FROM multimedia_eventos WHERE id_evento = $id_evento";
    $res_img = mysqli_query($conexion, $sql_img);
    
    $num_imagenes = mysqli_num_rows($res_img);

    $clase_dinamica = ($num_imagenes === 1) ? 'galeria-unitaria' : 'galeria-multiple';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $evento['titulo_evento']; ?> | Jose Diego Maria Rivera?></title>

    <meta name="description" content="<?php echo substr(strip_tags($evento['descripcion_evento']), 0, 160); ?>...">
    <meta property="og:title" content="<?php echo $evento['titulo_evento']; ?>">
    <meta property="og:description" content="Infórmate sobre nuestro próximo evento institucional.">
    <meta property="og:type" content="article">
    <meta property="og:url" content="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">

    <?php 
    mysqli_data_seek($res_img, 0);
    $img_seo = mysqli_fetch_assoc($res_img);
    if($img_seo): 
    ?>
        <meta property="og:image" content="http://<?php echo $_SERVER['HTTP_HOST']; ?>/img_eventos/<?php echo $img_seo['ruta_archivo']; ?>">
    <?php 
    endif; 
    mysqli_data_seek($res_img, 0); 
    ?>
    <link rel="stylesheet" href="css/estilos.css?v=1.10">
    <link rel="icon" href="img_video/Adobe Express - file.png">
    <script defer src="js/index.js?v=1.10"></script>

    <!--libreria-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
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

<div class="contenido">
    <div class="header-evento">
        <h1 class="tittle-grande"><?php echo $evento['titulo_evento']; ?></h1>

        <div class="badges-row">
            <span class="status-badge"><?php echo $evento['estado']; ?>
            </span>
        </div>
    </div>

    <hr class="divisor">

    <div class="galeria-evento <?php echo $clase_dinamica; ?>">
        <?php while($img = mysqli_fetch_assoc($res_img)): ?>
            <a href="<?php echo $img['ruta_archivo']; ?>" data-fancybox="gallery"><img src="<?php echo $img['ruta_archivo']; ?>" alt="Imagen de <?php echo $evento['titulo_evento']; ?> - Colegio María Reina Roda""></a>
        <?php endwhile; ?>
    </div>

        <div class="info-detallada">
            <div class="cintillo-datos">
                <div class="dato-item">
                    <span>📅</span>
                    <strong>Periodo:</strong> 
                    <?php $inicio = date("d/m/Y", strtotime($evento['fecha_inicio']));
                    $fin = date("d/m/Y", strtotime($evento['fecha_fin']));
                    echo "$inicio - $fin";
                    ?>
                </div>
                <div class="dato-item">
                    <span>📍</span>
                    <strong>Lugar:</strong> <?php echo $evento['nombre_lugar']; ?>
                </div>
                <div class="dato-item">
                    <span>🏷️</span>
                    <strong>Categoría:</strong> <?php echo $evento['nombre_categoria']; ?>
                </div>
                <div class="dato-item">
                    <a href="https://api.whatsapp.com/send?text=..." class="btn-wsp-small">Compartir</a>
                </div>
            </div>

            <div class="descripcion-texto">
                <?php echo nl2br(htmlspecialchars($evento['descripcion_evento'])); ?>
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
    <script>
        Fancybox.bind("[data-fancybox='gallery']", {
            infinite: true,
            Toolbar: {
                display: {
                    left: ["infobar"],
                    middle: [],
                    right: ["iterateZoom", "slideshow", "fullScreen", "download", "thumbs", "close"],
                },
            },
        });
    </script>
</body>
</html>