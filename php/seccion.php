<?php
    session_start();
    include 'conexion_be.php';
    include_once 'funciones_permisos.php';

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header("Location: index.php");
        exit();
    }

    $id = intval($_GET['id']);

    $query_nom = mysqli_query($conexion, "SELECT nombre_seccion FROM secciones_paginas WHERE id_seccion = $id");
    
    if (mysqli_num_rows($query_nom) === 0) {
        header("Location: index.php");
        exit();
    }

    $res_nom = mysqli_fetch_assoc($query_nom);
    $nombre_seccion = $res_nom['nombre_seccion'];

    $sql_unificado = "
    SELECT 'evento' as tipo, e.id_evento as id, e.titulo_evento as titulo, 
        e.descripcion_evento as info, e.fecha_inicio as fecha, MIN(mul.ruta_archivo) as ruta_imagen, 
            cat.nombre_categoria as categoria, est.estado as estado_evento
    FROM eventos e
    LEFT JOIN categorias_eventos cat ON e.id_categoria = cat.id_categoria
    LEFT JOIN estado_evento est ON e.id_estado = est.id_estado
    LEFT JOIN multimedia_eventos mul ON e.id_evento = mul.id_evento
    WHERE e.id_seccion = $id
    GROUP BY e.id_evento, e.titulo_evento, e.descripcion_evento, e.fecha_inicio, cat.nombre_categoria, est.estado
    
    UNION
    
    SELECT 'comunicado', c.id_comunicado, c.titulo, 
        c.contenido, c.fecha_publicacion, c.ruta_imagen, 
            cat.nombre_categoria, ''
    FROM comunicados c
    LEFT JOIN categoria_comunicados cat ON c.id_categoria = cat.id_categoria
    WHERE c.id_seccion = $id
    
    UNION
    
    SELECT 'galeria', id_galeria, titulo, 
        '' as info, '' fecha, ruta_imagen, 
            '' as categoria, ''
    FROM galeria WHERE id_seccion = $id

    UNION

        SELECT 'documento', id_documentos, titulo_documento AS titulo, descripcion AS info, fecha_subida AS fecha, '', '', ''
        FROM documentos_descargables WHERE id_seccion = $id
        
        ORDER BY fecha DESC";

    $res_contenido = mysqli_query($conexion, $sql_unificado);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jose Diego Maria Rivera - <?php echo strtolower($nombre_seccion); ?></title>
    <link rel="stylesheet" href="css/estilos.css?v=1.5">
    <link rel="icon" href="img_video/Adobe Express - file.png">
    <script defer src="js/index.js?v=1.11"></script>

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

    <main style="display: flex; flex-direction: column; flex: 1; margin-top: 10px;">
        <h2 class= "contenido-titulo" style="text-align: center; margin-bottom: 30px; text-transform: lowercase;">
            <?php echo $nombre_seccion; ?>
        </h2>

        <div class="seccion-layout-fijo">
            
            <?php
                if ($id != '5') {
                    if(mysqli_num_rows($res_contenido) > 0) {
                    while($item = mysqli_fetch_assoc($res_contenido)) {
                        $titulo = strtolower($item['titulo']);
                        $categoria = $item['categoria'];
                        $desc = $item['info'];
                        $tipo = $item['tipo'];
                        $id_item = $item['id'];
                        $ruta = $item['ruta_imagen'];
                        $nombre_estado = $item['estado_evento'];
                        $fecha = date("d/m/Y", strtotime($item['fecha']));

                        switch($tipo) {
                            case 'galeria':
                                $ruta_img = $item['ruta_imagen'];
                                $titulo_forzado = "<span style='color: #000000 !important; font-weight: bold;'>" . $titulo . '</span>';
                                echo '
                                <div class="tarjeta-foto-pura">
                                <a href="'.$ruta.'" data-fancybox="gallery" data-caption="'.$titulo_forzado.'">
                                    <img src="'.$ruta_img.'" alt="'.$titulo.'" class="foto-galeria">
                                    <div class="overlay-titulo">'.$titulo.'</div>
                                </a>
                                </div>';
                                break;

                            case 'comunicado':
                                $id_item = $item['id'];
                                $nom_cat = $item['categoria'];
                                $enlace = "detalle_comunicado.php?id=" . $id_item;
                                $ruta = $item['ruta_imagen'];
                                if (isset($item['ruta_imagen']) && !empty(trim($item['ruta_imagen']))) {
                                    $ruta = $item['ruta_imagen'];
                                } else {
                                    $ruta = "img_video/descarga.jpg"; 
                                }
                                echo '
                                <div class="comunicados">
                                    <a class="enlaces zoom" href="'.$enlace.'">
                                        <img src="'.$ruta.'" alt="Comunicado">
                                    </a>
                                    <h3 class="tittle">
                                        <a href="'.$enlace.'">'.$titulo.'</a>
                                    </h3>
                                    <p class="sinopsis">'.$nom_cat.'</p>
                                </div>';
                                break;

                            case 'evento':
                                $nom_cat = $item['categoria'];
                                $nombre_estado = $item['estado_evento'];
                                $clase_css_estado = strtolower($nombre_estado);
                                $enlace = "detalle_evento.php?id=" . $id_item;
                                $fecha = date("d/m/Y", strtotime($item['fecha']));
                                $ruta = $item['ruta_imagen'];
                                if (isset($item['ruta_imagen']) && !empty(trim($item['ruta_imagen']))) {
                                    $ruta = $item['ruta_imagen'];
                                } else {
                                    $ruta = "img_video/descarga.jpg"; 
                                }

                                echo '
                                <div class="comunicados">
                                    <a class="enlaces zoom" href="'.$enlace.'">
                                        <img src="'.$ruta.'" alt="Evento">
                                    </a>
                                    <h3 class="tittle">
                                        <a href="'.$enlace.'">'.$titulo.'</a>
                                    </h3>
                                    <p class="sinopsis">'.$fecha.'</p>
                                    <p class="sinopsis status-badge '.$clase_css_estado.'">
                                        '.$nombre_estado.'
                                    </p>
                                    <p class="sinopsis"><strong>Categoría:</strong> '.$nom_cat.'</p>
                                </div>';
                                break;

                            
                        }
                    }
                    } else {
                        echo "<p>Aún no hay actividades publicadas en esta sección.</p>";
                    }

                } else { 
    $sql_cat = "SELECT * FROM categorias_documentos";
    $res_cat = mysqli_query($conexion, $sql_cat);
    
    $sql_docs = "SELECT d.*, c.nombre_categoria 
                FROM documentos_descargables d
                INNER JOIN categorias_documentos c ON d.id_categoria_doc = c.id_categoria_doc
                WHERE d.id_seccion = $id 
                ORDER BY d.fecha_subida DESC";
    
    $res_docs = mysqli_query($conexion, $sql_docs);

    echo '<div class="repositorio-full expandir-padre">';
    echo '<aside class="repo-nav">';
        echo '<div class="nav-header">Categorías</div>';
        echo '<button class="btn-admin active" onclick="filtrarDocs(\'todos\', this)">Todos los Archivos</button>';
        
        mysqli_data_seek($res_cat, 0); 
        while($cat = mysqli_fetch_assoc($res_cat)){
            echo '<button class="btn-admin" onclick="filtrarDocs('.$cat['id_categoria_doc'].', this)">';
            echo $cat['nombre_categoria'];
            echo '</button>';
        }
    echo '</aside>';

    echo '<main class="repo-canvas">';
        echo '<div class="canvas-header"><h4>Listado de Documentos</h4></div>';
        echo '<div class="listado-documentos">';
            
            mysqli_data_seek($res_docs, 0);
            while($doc = mysqli_fetch_assoc($res_docs)){
                echo '<div class="fila-doc" data-cat="'.$doc['id_categoria_doc'].'">';
                    
                    echo '<div class="doc-info">';
                        echo '<span class="tag-categoria">'.$doc['nombre_categoria'].'</span>';
                        echo '<strong class="doc-titulo">'.$doc['titulo_documento'].'</strong>';
                        echo '<p class="doc-desc">'.$doc['descripcion'].'</p>';
                    echo '</div>';

                    echo '<div class="doc-acciones">';
                        echo '<a href="'.$doc['url_archivo'].'" target="_blank" class="btn-admin">Ver</a>';
                        echo '<a href="'.$doc['url_archivo'].'" download class="btn-admin">Descargar</a>';
                    echo '</div>';
                    
                echo '</div>';
            }
            
        echo '</div>';
    echo '</main>';
echo '</div>';
    }
            ?>

        </div>
    </main>
            <!--libreria-->
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