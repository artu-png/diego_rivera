<?php
    session_start();
    include 'conexion_be.php'; /** @var mysqli $conexion */
    include_once 'funciones_permisos.php';

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header("Location: ../index.php");
        exit();
    }

    $id = intval($_GET['id']);

    // Captura de filtros desde la URL
    $buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
    $filtro_cat = isset($_GET['filtro_categoria']) ? trim($_GET['filtro_categoria']) : '';
    $filtro_est = isset($_GET['filtro_estado']) ? trim($_GET['filtro_estado']) : '';

    // ✅ DECLARACIÓN SIEMPRE ACTIVA: No dependen de ningún IF, existen sí o sí
    $buscar_clean = !empty($buscar) ? mysqli_real_escape_string($conexion, $buscar) : '';
    $cat_clean = !empty($filtro_cat) ? intval($filtro_cat) : 0;
    $est_clean = !empty($filtro_est) ? intval($filtro_est) : 0;

    // Inicializamos los fragmentos de SQL vacíos para el conteo de arriba
    $condicion_buscar_conteo = !empty($buscar_clean) ? " AND titulo LIKE '%$buscar_clean%' " : "";
    $condicion_cat_conteo = ($cat_clean > 0) ? " AND id_categoria = $cat_clean " : "";
    $condicion_est_conteo = ($est_clean > 0) ? " AND id_estado = $est_clean " : "";

    $query_nom = mysqli_query($conexion, "SELECT nombre_seccion FROM secciones_paginas WHERE id_seccion = $id");
    
    if (mysqli_num_rows($query_nom) === 0) {
        header("Location: ../index.php");
        exit();
    }

    $res_nom = mysqli_fetch_assoc($query_nom);
    $nombre_seccion = $res_nom['nombre_seccion'];



// ==========================================================
    // ⚙️ PARÁMETROS MATEMÁTICOS PARA EL PAGINADO (8 REGISTROS)
    // ==========================================================
    $registros_por_pagina = 8;
    $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    if ($pagina_actual < 1) { $pagina_actual = 1; }
    $offset = ($pagina_actual - 1) * $registros_por_pagina;

    $res_contenido = false;
    $total_paginas = 1;
    // ==========================================================
    // 🔀 FLUJO 1: SECCIONES ESTÁNDAR (Eventos, Comunicados, Galería)
    // ==========================================================
    if ($id != '5') {
        
        // 1. Conteo total optimizado (Sin documentos)
        $sql_conteo = "
        SELECT COUNT(*) AS total FROM (
            SELECT id_evento FROM eventos WHERE id_seccion = $id " . (!empty($buscar_clean) ? " AND titulo_evento LIKE '%$buscar_clean%' " : "") . " " . ($cat_clean > 0 ? " AND id_categoria = $cat_clean " : "") . " " . ($est_clean > 0 ? " AND id_estado = $est_clean " : "") . "
            UNION ALL
            SELECT id_comunicado FROM comunicados WHERE id_seccion = $id " . (!empty($buscar_clean) ? " AND titulo LIKE '%$buscar_clean%' " : "") . " " . ($cat_clean > 0 ? " AND id_categoria = $cat_clean " : "") . "
            UNION ALL
            SELECT id_galeria FROM galeria WHERE id_seccion = $id " . (!empty($buscar_clean) ? " AND titulo LIKE '%$buscar_clean%' " : "") . "
        ) AS conteo_total";

        $res_conteo = mysqli_query($conexion, $sql_conteo);
        $total_registros = mysqli_fetch_assoc($res_conteo)['total'];
        $total_paginas = ceil($total_registros / $registros_por_pagina);

        // 2. Consulta UNION limpia y paginada desde afuera
        $sql_unificado = "
        SELECT * FROM (
            SELECT 'evento' as tipo, e.id_evento as id, e.titulo_evento as titulo, 
                e.descripcion_evento as info, e.fecha_inicio as fecha, MIN(mul.ruta_archivo) as ruta_imagen, 
                cat.nombre_categoria as categoria, est.estado as estado_evento
            FROM eventos e
            LEFT JOIN categorias_eventos cat ON e.id_categoria = cat.id_categoria
            LEFT JOIN estado_evento est ON e.id_estado = est.id_estado
            LEFT JOIN multimedia_eventos mul ON e.id_evento = mul.id_evento
            WHERE e.id_seccion = $id 
            " . (!empty($buscar_clean) ? " AND e.titulo_evento LIKE '%$buscar_clean%' " : "") . "
            " . ($cat_clean > 0 ? " AND e.id_categoria = $cat_clean " : "") . "
            " . ($est_clean > 0 ? " AND e.id_estado = $est_clean " : "") . "
            GROUP BY e.id_evento, e.titulo_evento, e.descripcion_evento, e.fecha_inicio, cat.nombre_categoria, est.estado
            
            UNION
            
            SELECT 'comunicado', c.id_comunicado, c.titulo, 
                c.contenido, c.fecha_publicacion, c.ruta_imagen, 
                cat.nombre_categoria, ''
            FROM comunicados c
            LEFT JOIN categoria_comunicados cat ON c.id_categoria = cat.id_categoria
            WHERE c.id_seccion = $id 
            " . (!empty($buscar_clean) ? " AND c.titulo LIKE '%$buscar_clean%' " : "") . "
            " . ($cat_clean > 0 ? " AND c.id_categoria = $cat_clean " : "") . "
            
            UNION
            
            SELECT 'galeria', id_galeria, titulo, 
                '' as info, fecha_subida as fecha, ruta_imagen, 
                '' as categoria, ''
            FROM galeria 
            WHERE id_seccion = $id 
            " . (!empty($buscar_clean) ? " AND titulo LIKE '%$buscar_clean%' " : "") . "
        ) AS todo_junto
        ORDER BY fecha DESC
        LIMIT $registros_por_pagina OFFSET $offset";

        $res_contenido = mysqli_query($conexion, $sql_unificado);
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jose Diego Maria Rivera - <?php echo strtolower($nombre_seccion); ?></title>
    <link rel="stylesheet" href="../css/estilos.css?v=1.50">
    <link rel="icon" href="../img_video/Adobe Express - file.png">
    <script defer src="../js/index.js?v=1.11"></script>

    <!--libreria-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
</head>

<body>
    <header class="header">
        <nav class="nav">
            <a href="../index.php" class="logo">
                <img class="img" src="../img_video/WhatsApp_Image_2026-03-02_at_4.15.30_PM-removebg-preview.png" alt="">
                <h1>Jose Diego Maria Rivera</h1>
            </a>
            <button class="nav-toggle" aria-label="Abrir menu">☰</button>
            
            <ul class="nav-menu" id="navlinks">
                <li class="nav-menu-item"><a href="../index.php" class="nav-menu-link">inicio</a></li>

                <?php
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

        <div class="seccion-layout-fijo">

            <?php if ($id != '5'): ?>
        <div class="panel-busqueda-pildora" style="grid-column: 1 / -1; width: 100%; max-width: 1100px; margin: auto; padding: 10px 25px; background: #f4f8fb; border: 1px solid #dbe6f0; border-radius: 50px; display: flex; align-items: center; justify-content: space-between; gap: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); font-family: sans-serif;">
            
            <form action="" method="GET" style="display: flex; align-items: center; width: 100%; gap: 15px; margin: 0;">
                <input type="hidden" name="id" value="<?php echo $id; ?>">

                <div style="display: flex; align-items: center; gap: 8px; flex: 1;">
                    <span style="font-size: 18px;">🔍</span>
                    <strong style="color: #002244; white-space: nowrap;">Buscar:</strong>
                    <input type="text" name="buscar" value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>" placeholder="Escribe un título..." style="width: 100%; padding: 6px 12px; border: 1px solid #ccc; border-radius: 20px; outline: none;">
                </div>

                <?php 
                // 🏷️ 2. Select Dinámico de Categorías (Eventos o Comunicados)
                // Aquí hacemos las consultas rápidas según la sección actual
                $mostrar_select_cat = false;
                $opciones_cat = [];

                if ($id == '4') { // Eventos
                    $mostrar_select_cat = true;
                    $res_filtro_cat = mysqli_query($conexion, "SELECT id_categoria AS id, nombre_categoria AS nombre FROM categorias_eventos");
                    while($r = mysqli_fetch_assoc($res_filtro_cat)) { $opciones_cat[] = $r; }
                } elseif ($id == '3') { // Comunicados
                    $mostrar_select_cat = true;
                    $res_filtro_cat = mysqli_query($conexion, "SELECT id_categoria AS id, nombre_categoria AS nombre FROM categoria_comunicados");
                    while($r = mysqli_fetch_assoc($res_filtro_cat)) { $opciones_cat[] = $r; }
                }

                if ($mostrar_select_cat): 
                ?>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="font-size: 18px;">🏷️</span>
                    <strong style="color: #002244; white-space: nowrap;">Categoría:</strong>
                    <select name="filtro_categoria" style="padding: 6px 12px; border: 1px solid #ccc; border-radius: 20px; background: white; outline: none;">
                        <option value="">Todas</option>
                        <?php foreach($opciones_cat as $cat_opc): ?>
                            <option value="<?php echo $cat_opc['id']; ?>" <?php echo (isset($_GET['filtro_categoria']) && $_GET['filtro_categoria'] == $cat_opc['id']) ? 'selected' : ''; ?>>
                                <?php echo $cat_opc['nombre']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>

                <?php 
                // 🚦 3. Select Estados sección Eventos
                if ($id == '4'): 
                    $res_filtro_est = mysqli_query($conexion, "SELECT id_estado, estado FROM estado_evento");
                ?>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="font-size: 18px;">📍</span>
                    <strong style="color: #002244; white-space: nowrap;">Estado:</strong>
                    <select name="filtro_estado" style="padding: 6px 12px; border: 1px solid #ccc; border-radius: 20px; background: white; outline: none;">
                        <option value="">Todos</option>
                        <?php while($est_opc = mysqli_fetch_assoc($res_filtro_est)): ?>
                            <option value="<?php echo $est_opc['id_estado']; ?>" <?php echo (isset($_GET['filtro_estado']) && $_GET['filtro_estado'] == $est_opc['id_estado']) ? 'selected' : ''; ?>>
                                <?php echo $est_opc['estado']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <?php endif; ?>

                <button type="submit" style="padding: 8px 24px; background: #1cd05d; color: white; border: none; border-radius: 25px; font-weight: bold; cursor: pointer; transition: background 0.2s;">
                    Filtrar
                </button>
                
                <?php if(isset($_GET['buscar']) || isset($_GET['filtro_categoria']) || isset($_GET['filtro_estado'])): ?>
                    <a href="?id=<?php echo $id; ?>" style="color: #666; text-decoration: none; font-size: 13px; white-space: nowrap;">Limpiar</a>
                <?php endif; ?>
            </form>
        </div>

        <?php endif; ?>
            
            <?php
                if ($id != '5') {
                if(mysqli_num_rows($res_contenido) > 0) {
                    while($item = mysqli_fetch_assoc($res_contenido)) {
                        $titulo = strtolower($item['titulo']);
                        $categoria = $item['categoria'];
                        $desc = $item['info'];
                        $tipo = $item['tipo'];
                        $id_item = $item['id'];
                        $nombre_estado = $item['estado_evento'];
                        $fecha = date("d/m/Y", strtotime($item['fecha']));

                        if (isset($item['ruta_imagen']) && !empty(trim($item['ruta_imagen']))) {
                            $ruta_final = "../" . trim($item['ruta_imagen']);
                        } else {
                            $ruta_final = "../img_video/descarga.jpg"; 
                        }

                        switch($tipo) {
                            case 'galeria':
                                $titulo_forzado = "<span style='color: #000000 !important; font-weight: bold;'>" . $titulo . '</span>';
                                echo '
                                <div class="tarjeta-foto-pura">
                                <a href="'.$ruta_final.'" data-fancybox="gallery" data-caption="'.$titulo_forzado.'">
                                    <img src="'.$ruta_final.'" alt="'.$titulo.'" class="foto-galeria">
                                    <div class="overlay-titulo">'.$titulo.'</div>
                                </a>
                                </div>';
                                break;

                            case 'comunicado':
                                $enlace = "detalle_comunicado.php?id=" . $id_item;
                                echo '
                                <div class="comunicados">
                                    <a class="enlaces zoom" href="'.$enlace.'">
                                        <img src="'.$ruta_final.'" alt="Comunicado">
                                    </a>
                                    <h3 class="tittle">
                                        <a href="'.$enlace.'">'.$titulo.'</a>
                                    </h3>
                                    <p class="sinopsis">'.$categoria.'</p>
                                </div>';
                                break;

                            case 'evento':
                                $clase_css_estado = strtolower($nombre_estado);
                                $enlace = "detalle_evento.php?id=" . $id_item;
                                echo '
                                <div class="comunicados">
                                    <a class="enlaces zoom" href="'.$enlace.'">
                                        <img src="'.$ruta_final.'" alt="Evento">
                                    </a>
                                    <h3 class="tittle">
                                        <a href="'.$enlace.'">'.$titulo.'</a>
                                    </h3>
                                    <p class="sinopsis">'.$fecha.'</p>
                                    <p class="sinopsis status-badge '.$clase_css_estado.'">
                                        '.$nombre_estado.'
                                    </p>
                                    <p class="sinopsis"><strong>Categoría:</strong> '.$categoria.'</p>
                                </div>';
                                break;
                        }
                    }

                    // 🌟 ¡PUNTO EXACTO DE INYECCIÓN! 🌟
                    // Se ejecuta justo al terminar el bucle while de las fotos/comunicados, rompiendo la estructura para irse abajo en el medio.
                    if ($total_paginas > 1) {
    echo '<div style="grid-column: 1 / -1; width: 100%; flex-basis: 100%; display: flex; justify-content: center; margin-top: 40px; margin-bottom: 20px; clear: both;">';
        echo '<div class="paginacion-container" style="display: flex; justify-content: center; align-items: center; gap: 10px; font-family: sans-serif;">';
            
            if ($pagina_actual > 1) {
                echo '<a href="?id='.$id.'&pagina='.($pagina_actual - 1).'" style="padding: 8px 16px; border: 1px solid #002244; color: #002244; text-decoration: none; border-radius: 4px; font-weight: bold;">Anterior</a>';
            }

            for ($i = 1; $i <= $total_paginas; $i++) {
                if ($i == $pagina_actual) {
                    echo '<span style="padding: 8px 14px; background: #002244; color: white; border: 1px solid #002244; border-radius: 4px; font-weight: bold;">'.$i.'</span>';
                } else {
                    echo '<a href="?id='.$id.'&pagina='.$i.'" style="padding: 8px 14px; border: 1px solid #ccc; color: #333; text-decoration: none; border-radius: 4px;">'.$i.'</a>';
                }
            }

            if ($pagina_actual < $total_paginas) {
                echo '<a href="?id='.$id.'&pagina='.($pagina_actual + 1).'" style="padding: 8px 16px; border: 1px solid #002244; color: #002244; text-decoration: none; border-radius: 4px; font-weight: bold;">Siguiente</a>';
            }

        echo '</div>';
    echo '</div>';
}

                } else {
                    echo "<p>Aún no hay actividades publicadas en esta sección.</p>";
                }

                } else { 
        $sql_conteo_docs = "SELECT COUNT(*) AS total FROM documentos_descargables WHERE id_seccion = $id";
        $res_conteo_docs = mysqli_query($conexion, $sql_conteo_docs);
        $total_registros = mysqli_fetch_assoc($res_conteo_docs)['total'];
        $total_paginas = ceil($total_registros / $registros_por_pagina);

        $sql_cat = "SELECT * FROM categorias_documentos";
        $res_cat = mysqli_query($conexion, $sql_cat);
        
        $sql_docs = "SELECT d.*, c.nombre_categoria 
                    FROM documentos_descargables d
                    INNER JOIN categorias_documentos c ON d.id_categoria_doc = c.id_categoria_doc
                    WHERE d.id_seccion = $id 
                    ORDER BY d.fecha_subida DESC
                    LIMIT $registros_por_pagina OFFSET $offset";
        
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
                $ruta_doc_final = "../" . ltrim($doc['url_archivo'], './');
                echo '<div class="fila-doc" data-cat="'.$doc['id_categoria_doc'].'">';
                    
                    echo '<div class="doc-info">';
                        echo '<span class="tag-categoria">'.$doc['nombre_categoria'].'</span>';
                        echo '<strong class="doc-titulo">'.$doc['titulo_documento'].'</strong>';
                        echo '<p class="doc-desc">'.$doc['descripcion'].'</p>';
                    echo '</div>';

                    echo '<div class="doc-acciones">';
                        echo '<a href="'.$ruta_doc_final.'" target="_blank" class="btn-admin">Ver</a>';
                        echo '<a href="'.$ruta_doc_final.'" download class="btn-admin">Descargar</a>';
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