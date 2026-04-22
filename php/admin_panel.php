<?php
    include 'control_sesion.php';
    include_once 'conexion_be.php'; 
    include_once 'funciones_permisos.php';

    $query_secciones = mysqli_query($conexion, "SELECT * FROM secciones_paginas");
    $opciones_html = "";
    $listadoSeccionesHTML = "";
    while($row = mysqli_fetch_assoc($query_secciones)){
    $opciones_html .= "<option value='{$row['id_seccion']}'>{$row['nombre_seccion']}</option>";
    $listadoSeccionesHTML .= "
    <tr>
        <td>{$row['nombre_seccion']}</td>
        <td>
            <div style='display: flex; justify-content: center; gap: 15px; align-items: center;'>
                <button class='btn-edit' onclick='editarSeccion({$row['id_seccion']}, \"{$row['nombre_seccion']}\")' style='margin: 0;'>
                    Editar
                </button>
                <a href='eliminar_seccion.php?id={$row['id_seccion']}' class='btn-delete' style='text-decoration: none; margin: 0;' onclick='return confirm(\"¿Estás seguro de eliminar esta sección?\")'>
                    Eliminar
                </a>
            </div>
        </td>
    </tr>";
    }

    // Categorías de Eventos
    $query_cat = mysqli_query($conexion, "SELECT * FROM categorias_eventos");
    $opcionesCategorias = "";
    while($row = mysqli_fetch_assoc($query_cat)){
        $opcionesCategorias .= "<option value='{$row['id_categoria']}'>{$row['nombre_categoria']}</option>";
    }

    // Categorías de Documentos
    $query_cat_docs = mysqli_query($conexion, "SELECT * FROM categorias_documentos");
    $opcionesDocsHTML = "";
    while($row = mysqli_fetch_assoc($query_cat_docs)){
        $opcionesDocsHTML .= "<option value='".$row['id_categoria_doc']."'>".$row['nombre_categoria']."</option>";
    }

    // Roles
    $query_roles = mysqli_query($conexion, "SELECT * FROM roles");
    $opcionesRolesHTML = "";
    while($r = mysqli_fetch_assoc($query_roles)){
        $opcionesRolesHTML .= "<option value='{$r['id_rol']}'>{$r['nombre_rol']}</option>";
    }

    // Estados
    $query_estados = mysqli_query($conexion, "SELECT * FROM estado_evento");
    $opcionesEstados = "";
    while($row = mysqli_fetch_assoc($query_estados)){
        $opcionesEstados .= "<option value='{$row['id_estado']}'>{$row['estado']}</option>";
    }

    // 5. Tablas de Edicion
    $query_com = mysqli_query($conexion, "SELECT * FROM comunicados ORDER BY fecha_vencimiento DESC");
    $tablaComunicadosHTML = "";

    while($com = mysqli_fetch_assoc($query_com)){
        $datosBase64 = base64_encode(json_encode($com));

        $botonEditar = "";
        if(tienePermiso('editar_contenido')){
            $botonEditar = "<button type='button' class='btn-edit' data-info='$datosBase64' onclick='prepararEdicionFinal(this, \"id_comunicado\", \"actualizar_comunicado.php\")'>Editar</button>";
        }
        $botonEliminar = "";
        if(tienePermiso('eliminar_contenido')){
            $botonEliminar = "<a href='eliminar_registro.php?id={$com['id_comunicado']}&tabla=comunicados&columna=id_comunicado' class='btn-delete' onclick='return confirm(\"¿Borrar?\")'>Eliminar</a>";
        }

        $tablaComunicadosHTML .= "
        <tr>
            <td>{$com['titulo']}</td>
            <td>{$com['fecha_vencimiento']}</td>
            <td class='acciones-tabla'>
                $botonEditar
                $botonEliminar
            </td>
        </tr>";
    }
    /////////////////////////////////////////////////////////////
    $queryGal = "SELECT * FROM galeria ORDER BY id_galeria DESC";
        $resGal = mysqli_query($conexion, $queryGal);
        $listadoGaleria = "";
        while($foto = mysqli_fetch_assoc($resGal)){
            $datosJson = htmlspecialchars(json_encode($foto, JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8');

            $botonEditar = "";
            if(tienePermiso('editar_contenido')){
            $botonEditar = "<button type='button' class='btn-edit' onclick='prepararEdicion(this, {$datosJson}, \"id_galeria_edit\", \"actualizar_galeria.php\")'>Editar</button>";
            }

            $botonEliminar = "";
            if(tienePermiso('eliminar_contenido')){
                $botonEliminar = "<a href='eliminar_registro.php?id={$foto['id_galeria']}&tabla=galeria&columna=id_galeria' class='btn-delete' onclick='return confirm(\"¿Eliminar esta foto?\")'>Eliminar</a>";
            }

        $listadoGaleria .= "
        <tr>
            <td><img src='{$foto['ruta_imagen']}' width='50'></td>
            <td>{$foto['titulo']}</td>
            <td class='acciones-tabla'>
                $botonEditar
                $botonEliminar
            </td>
        </tr>";
    }

    //////////////////////////////////////////////////////////////////////
        $queryEv = "SELECT e.*, u.nombre_lugar
        FROM eventos e
        INNER JOIN ubicacion_eventos u ON e.id_ubicacion = u.id_ubicacion
        ORDER BY e.fecha_inicio DESC";
        $resEv = mysqli_query($conexion, $queryEv);
        $listadoEventos = "";

        while($ev = mysqli_fetch_assoc($resEv)){
        $ev['id_evento_edit'] = $ev['id_evento'];
        $ev['descripcion_evento'] = str_replace(array("\r", "\n"), ' ', $ev['descripcion_evento']);
        $base64 = base64_encode(json_encode($ev, JSON_UNESCAPED_UNICODE));
        $titulo_limpio = htmlspecialchars($ev['titulo_evento'], ENT_QUOTES, 'UTF-8');

        $botonEditar = "";
            if(tienePermiso('editar_contenido')){
            $botonEditar = "<button type='button' class='btn-edit' 
                        data-info='{$base64}'onclick='prepararEdicionFinal(this, \"id_evento_edit\", \"actualizar_evento.php\")'> Editar </button>";
            }

            $botonEliminar = "";
            if(tienePermiso('eliminar_contenido')){
                $botonEliminar = "<a href='eliminar_registro.php?id={$ev['id_evento']}&tabla=eventos&columna=id_evento' class='btn-delete' onclick='return confirm(\"¿Eliminar este evento?\")'>Eliminar</a>";
            }

        $listadoEventos .= "
        <tr>
            <td>{$titulo_limpio}</td>
            <td>{$ev['fecha_inicio']}</td>
            <td class='acciones-tabla'>
                $botonEditar
                $botonEliminar
            </td>
        </tr>";
    }
    ///////////////////////////////////////////////////////////
    $queryDoc = "SELECT d.*, cat.nombre_categoria
    FROM documentos_descargables d
    INNER JOIN categorias_documentos cat ON d.id_categoria_doc = cat.id_categoria_doc
    ORDER BY d.fecha_subida DESC";
    $resDoc = mysqli_query($conexion, $queryDoc);
    $listadoDocumentos = "";
    while($doc = mysqli_fetch_assoc($resDoc)){
        $doc['id_documentos_edit'] = $doc['id_documentos']; 
        $doc['descripcion'] = str_replace(array("\r", "\n"), ' ', $doc['descripcion']);
        $base64 = base64_encode(json_encode($doc, JSON_UNESCAPED_UNICODE));

        $botonEditar = "";
            if(tienePermiso('editar_contenido')){
            $botonEditar = "<button type='button' class='btn-edit' 
                        data-info='{$base64}' 
                        onclick='prepararEdicionFinal(this, \"id_documentos_edit\", \"actualizar_documento_be.php\")'> Editar </button>";
            }

            $botonEliminar = "";
            if(tienePermiso('eliminar_contenido')){
                $botonEliminar = "<a href='eliminar_registro.php?id={$doc['id_documentos']}&tabla=documentos_descargables&columna=id_documentos&archivo={$doc['url_archivo']}' 
                class='btn-delete' onclick='return confirm(\"¿Estás seguro de eliminar este documento?\")'>Eliminar</a>";
            }

        $listadoDocumentos .= "
        <tr>
            <td>{$doc['titulo_documento']}</td>
            <td>{$doc['nombre_categoria']}</td>
            <td class='acciones-tabla'>
                $botonEditar
                $botonEliminar
            </td>
        </tr>";
    }
    /////////////////////////////////////////////////
    $query_p = mysqli_query($conexion, "SELECT * FROM permisos");
    $checksPermisosHTML = "";
    while($p = mysqli_fetch_assoc($query_p)){
        $checksPermisosHTML .= "
            <div class='permiso-item'>
                <input type='checkbox' class='check-permiso' value='{$p['id_permiso']}'> 
                <span>{$p['nombre_permiso']}</span>
            </div>";
    }
    /////////////////////////////////////////////////
    $query_u = "SELECT u.id_usuario, u.usuario_login, u.correo, r.nombre_rol, r.id_rol 
            FROM usuarios u
            INNER JOIN usuario_roles ur ON u.id_usuario = ur.id_usuario
            INNER JOIN roles r ON ur.id_rol = r.id_rol";
    $res_u = mysqli_query($conexion, $query_u);

    $listadoUsuariosHTML = "";
    while($usr = mysqli_fetch_assoc($res_u)){
        $esAutenticado = ($usr['id_usuario'] == $_SESSION['id_usuario']) ? "disabled" : "";
        $opcionesDiferentes = "";
        $query_roles = mysqli_query($conexion, "SELECT * FROM roles");
        while($r = mysqli_fetch_assoc($query_roles)){
            if($r['id_rol'] != $usr['id_rol']){
                $opcionesDiferentes .= "<option value='{$r['id_rol']}'>{$r['nombre_rol']}</option>";
            }
        }
        
        $listadoUsuariosHTML .= "
        <tr>
            <td>{$usr['usuario_login']}</td>
            <td>{$usr['correo']}</td>
            <td>
                <select class='select-rol' onchange='cambiarRol({$usr['id_usuario']}, this.value)' $esAutenticado>
                    <option value=''>{$usr['nombre_rol']} (Cambiar)</option>
                    $opcionesDiferentes
                </select>
            </td>
            <td>
                <button class='btn-delete' onclick='eliminarUsuario({$usr['id_usuario']})' $esAutenticado>🗑️</button>
            </td>
        </tr>";
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Colegio Maria Reina Roda</title>
    <link rel="stylesheet" href="css/estilos.css?v=1.114">
    <link rel="icon" href="img_video/Adobe Express - file.png">
    <script>
        const permisosUser = {
        canAdminSecciones: <?php echo tienePermiso('administrar_secciones') ? 'true' : 'false'; ?>,
        canGestionarUsuarios: <?php echo tienePermiso('gestionar_usuarios') ? 'true' : 'false'; ?>
        };

        const nombreUsuarioActual = <?php echo json_encode($_SESSION['nombre_completo'] ?? 'Usuario'); ?>;
        const correoUsuarioActual = <?php echo json_encode($_SESSION['correo'] ?? ''); ?>;
        const rolUsuarioActual = <?php echo json_encode($_SESSION['rol'] ?? 'Sin Rol'); ?>;
        const loginUsuarioActual = <?php echo json_encode($_SESSION['usuario'] ?? ''); ?>;

        const checksPermisosHTML = `<?php echo $checksPermisosHTML; ?>`;
        const listadoUsuariosHTML = <?php echo json_encode($listadoUsuariosHTML); ?>;
        const listadoDocumentos = `<?php echo $listadoDocumentos; ?>`;
        const idUsuarioActivo = <?php echo json_encode($_SESSION['id_usuario'] ?? null); ?>;
        const userBtn = document.getElementById('userBtn');
        const userPanel = document.getElementById('userPanel');
        const listadoComunicados = <?php echo json_encode($tablaComunicadosHTML); ?>;
        const listadoGaleria = <?php echo json_encode($listadoGaleria); ?>;
        const listadoEventos = `<?php echo $listadoEventos; ?>`;
        console.log("Sistema de secciones listo");
        const opcionesEstados = <?php echo json_encode($opcionesEstados); ?>;
        const opcionesCategorias = <?php echo json_encode($opcionesCategorias); ?>;
        const opcionesCategoriasDocs = <?php echo json_encode($opcionesDocsHTML); ?>;
        const listadoSecciones = <?php echo json_encode($listadoSeccionesHTML); ?>;
    </script>
    
    <script defer src="js/index.js?v=1.101"></script>
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

    <div class="contenido">
    <div class="top-bar">
        <h2 class="titulo-texto">Bienvenido al Panel de Gestión, <?php echo $_SESSION['usuario']; ?></h2>
        <a class="btn-cerrar" href="cerrar_sesion.php">Cerrar Sesión</a>
    </div>

    <div class="panel-layout">
        <div class="contenedor-botones">
        <?php if (tienePermiso('ver_panel_admin')): ?>
        <button class="btn-admin" onclick="mostrar('comunicados')">Comunicados</button>
        <button class="btn-admin" onclick="mostrar('galeria')">Galería</button>
        <button class="btn-admin" onclick="mostrar('eventos')">Eventos</button>
        <button class="btn-admin" onclick="mostrar('documentos')">Documentos</button>
        <?php endif; ?>

        <button class="btn-admin" onclick="mostrar('administrar')">Administrar</button>
        </div>

        <div id="area-trabajo" class="area-trabajo">
            <p>Selecciona una opción del menú para comenzar.</p>
        </div>
    </div>
    </div>

    <div id="modalPerfil" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModalPerfil()">&times;</span>
        <h2><i class="fas fa-user-edit"></i> Actualizar mi Perfil</h2>
        <form id="formActualizarPerfil">
            <div class="input-group">
                <label>Nombre Completo:</label>
                <input type="text" id="editNombre" name="nombre" required>
            </div>
            <div class="input-group">
                <label>Correo Electrónico:</label>
                <input type="email" id="editCorreo" name="correo" required>
            </div>
            <hr>
            <p class="instruccion">Deja en blanco la contraseña si no deseas cambiarla.</p>
            <div class="input-group">
                <label>Nueva Contraseña:</label>
                <input type="password" id="editPass" name="nueva_pass">
            </div>
            <div class="input-group">
                <label>Confirmar Nueva Contraseña:</label>
                <input type="password" id="confirmPass">
            </div>
            <button type="submit" class="btn-save">Guardar Cambios</button>
        </form>
    </div>
</div>

</body>
</html>