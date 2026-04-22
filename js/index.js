const navToggle = document.querySelector(".nav-toggle");
const navMenu = document.querySelector(".nav-menu");

navToggle.addEventListener("click", () => {
    navMenu.classList.toggle("nav-menu_visible");
    const visible = navMenu.classList.contains("nav-menu_visible");
    navToggle.setAttribute("aria-label", visible ? "Cerrar menu" : "Abrir menu");
});

document.addEventListener("click", (event) => {
    if (navMenu.classList.contains("nav-menu_visible") && 
        !navMenu.contains(event.target) && 
        !navToggle.contains(event.target)) {
        
        navMenu.classList.remove("nav-menu_visible");
        navToggle.setAttribute("aria-label", "Abrir menu");
    }
});

window.addEventListener("resize", () => {
    if (window.innerWidth > 768) {
        navMenu.classList.remove("nav-menu_visible");
        navToggle.setAttribute("aria-label", "Abrir menu");
    }
});

function imprimirComunicado() {
    const imgElement = document.querySelector('.foto-oficial');
    if (!imgElement) return;

    const iframe = document.createElement('iframe');
    iframe.style.position = 'fixed';
    iframe.style.right = '0';
    iframe.style.bottom = '0';
    iframe.style.width = '0';
    iframe.style.height = '0';
    iframe.style.border = '0';
    document.body.appendChild(iframe);

    const doc = iframe.contentWindow.document;
    doc.write('<html><body><img src="' + imgElement.src + '" style="width:100%;"></body></html>');
    doc.close();

    iframe.contentWindow.focus();
    iframe.contentWindow.print();

    setTimeout(() => { document.body.removeChild(iframe); }, 1000);
}

function mostrar(modulo) {
    const area = document.getElementById('area-trabajo');

    if(modulo === 'comunicados') {
        area.innerHTML = `
            <div class="formulario-container">
                <h3>Nuevo Comunicado</h3>
                <form action="guardar_comunicado.php" enctype="multipart/form-data" method="POST">
                    <input type="hidden" name="id_seccion" value="3">
                    <div class="campo">
                        <label>Título:</label>
                        <input type="text" name="titulo" required placeholder="Ej: Nueva Directiva">
                    </div>

                    <div class="fila-doble" style="display: flex; gap: 20px;">
                        <div class="campo" style="flex: 1;">
                            <label>Categoría:</label>
                            <select name="id_categoria" required>
                                <option value="1">Informativo</option>
                                <option value="2">Urgente</option>
                                <option value="3">Evento</option>
                            </select>
                        </div>
                    </div>

                    <div class="campo">
                        <label>Fecha de Vencimiento:</label>
                        <input type="date" name="fecha_vencimiento" required>
                    </div>

                    <div class="campo">
                        <label>Contenido del Mensaje:</label>
                        <textarea name="contenido" rows="6" class="textarea-fijo" required></textarea>
                    </div>

                    <div class="campo">
                        <label>Imagen del Comunicado (Opcional):</label>
                        <input type="file" name="imagen_comunicado" accept="image/*">
                    </div>

                    <button type="submit" class="btn-guardar">Publicar Comunicado</button>
                </form>

                <hr>

                <h3>Comunicados Publicados</h3>
                <div class="tabla-gestion"> <table class="tabla-admin">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Vencimiento</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${listadoComunicados} 
                    </tbody>
                </table>
                </div>
            </div>
        `;
    }

    else if(modulo === 'galeria') {
    area.innerHTML = `
        <div class="formulario-container">
            <h3>Subir Foto a la Galería</h3>
            <form action="guardar_galeria.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_seccion" value="2">
                <div class="campo">
                    <label>Título de la Imagen:</label>
                    <input type="text" name="titulo" required>
                </div>
                
                <div class="campo">
                    <label>Seleccionar Imagen:</label>
                    <input type="file" name="archivo_foto" accept="image/*" required>
                </div>

                <button type="submit" class="btn-guardar">Subir Imagen</button>
            </form>

            <hr>
            <h3>Fotos en Galería</h3>
            <div class="tabla-gestion"> <table class="tabla-admin">
                <thead>
                    <tr>
                        <th>Miniatura</th>
                        <th>Título</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    ${listadoGaleria} 
                </tbody>
            </table>
            </div>
        </div>
    `;
}

    else if(modulo === 'eventos') {
    area.innerHTML = `
        <div class="formulario-container">
            <h3>Publicar Evento</h3>
            <form action="guardar_evento.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_seccion" value="4">
                <div class="campo">
                    <label>Título del Evento:</label>
                    <input type="text" name="titulo_evento" required>
                </div>

                <div class="fila-doble" style="display: flex; gap: 20px;">
                    <div class="campo" style="flex: 1;">
                        <label>Fecha Inicio:</label>
                        <input type="date" name="fecha_inicio" required>
                    </div>
                    <div class="campo" style="flex: 1;">
                        <label>Fecha Fin:</label>
                        <input type="date" name="fecha_fin">
                    </div>
                </div>

                <div class="campo">
                <label>Descripción del Evento:</label>
                <textarea name="descripcion_evento" class="textarea-fijo" rows="4"></textarea>
            </div>

            <div class="fila-doble" style="display: flex; gap: 20px; margin-bottom: 15px; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 140px;">
                    <label>Categoría:</label>
                    <select name="id_categoria" style="width: 100%; padding: 8px;" required>
                        <option value="">Seleccione...</option>
                        ${opcionesCategorias}
                    </select>
                </div>
                <div style="flex: 1; min-width: 140px;">
                    <label>Estado del Evento:</label>
                    <select name="id_estado" style="width: 100%; padding: 8px;" required>
                        <option value="">Seleccione...</option>
                        ${opcionesEstados}
                    </select>
                </div>
            </div>

            <div class="campo" style="margin-bottom: 20px;">
                <label>Lugar / Ubicación:</label>
                <input type="text" name="nombre_lugar" placeholder="Ej: Auditorio de Primaria" style="width: 100%;" required>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: flex-end; gap: 20px;">
                <div class="campo" style="flex: 1; margin-bottom: 0;">
                    <label>Fotos del Evento (Selecciona varias):</label>
                    <input type="file" name="archivos_multimedia[]" multiple style="width: 100%;">
                </div>        
            </div>

            <div class="contenedor-boton">
                <button type="submit" class="btn-guardar" style="padding: 15px 30px; height: fit-content;">Publicar Evento</button>
            </div>
            </form>

            <hr>
            <h3>Eventos Publicados</h3>
        <div class="tabla-gestion"> <table class="tabla-admin">
            <thead>
            <tr>
                <th>Evento</th>
                <th>Fecha Inicio</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
                ${listadoEventos} 
            </tbody>
        </table>
        </div>

        </div>
    `;
    }

    if(modulo === 'documentos') {
        area.innerHTML = `
        
        <div class="formulario-container">
            <h3>Subir Documento</h3>
            <form action="guardar_documento.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_usuario_creador" value="${idUsuarioActivo}">
            <input type="hidden" name="id_seccion" value="5">
                <div class="campo">
                    <label>Título del Documento:</label>
                    <input type="text" name="titulo_documento" required placeholder="Ej: Calendario Escolar 2026">
                </div>

                <div class="fila-double" style="display: flex; gap: 20px;">
                    <div class="campo" style="flex: 1;">
                        <label>Categoría del Archivo:</label>
                        <select name="id_categoria_doc" required>
                            <option value="">Seleccione categoría...</option>
                            ${opcionesCategoriasDocs} 
                        </select>
                    </div>
                    <div class="campo" style="flex: 1;">
                        <label>Archivo (PDF):</label>
                        <input type="file" name="archivo_pdf" accept=".pdf" required>
                    </div>
                </div>

                <div class="campo">
                    <label>Descripción breve:</label>
                    <textarea name="descripcion" rows="3" class="textarea-fijo"></textarea>
                </div>

                <button type="submit" class="btn-admin">Publicar Documento</button>
            </form>

            <h3>Documentos Publicados</h3>
            <div class="tabla-gestion" style="overflow-x: auto; width: 100%;">
                <table class="tabla-admin">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Categoría</th>
                            <th style="text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="lista-documentos-admin">
                        ${listadoDocumentos}
                    </tbody>
                </table>
            </div>
        </div>`;
    }

    if (modulo === 'administrar') {
        let html = `
            <div class="formulario-container">
            <h2>Panel de Administración y Perfil</h2>
            
            <section class="admin-section">
                <h3><i class="fas fa-user-circle"></i> Mi Información Personal</h3>
                <div class="perfil-card">
                    <div class="perfil-detalles">
                        <p><strong><i class="fas fa-id-card"></i> Nombre Completo:</strong> ${nombreUsuarioActual}</p>
                        <p><strong><i class="fas fa-user"></i> Usuario:</strong> ${loginUsuarioActual}</p>
                        <p><strong><i class="fas fa-envelope"></i> Correo:</strong> ${correoUsuarioActual}</p>
                        <p><strong><i class="fas fa-shield-alt"></i> Rango:</strong> <span class="badge-rol">${rolUsuarioActual}</span></p>
                    </div>
                    <div class="perfil-acciones">
                        <button class="btn-edit" onclick="abrirModalPerfil()">
                            <i class="fas fa-sync-alt"></i> Actualizar Datos
                        </button>
                    </div>
                </div>
            </section>
            <hr>`;

        if (permisosUser.canGestionarUsuarios) {
            html += `
                <section class="admin-section">
                    <h3><i class="fas fa-users"></i> Gestión de Usuarios y Roles</h3>
                    <div class="tabla-gestion"> <table class="tabla-admin">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Correo</th>
                                    <th>Rol Actual</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${listadoUsuariosHTML}
                            </tbody>
                        </table>
                    </div> </section>
                <hr>`;
        }

        if (permisosUser.canGestionarUsuarios) {
            html += `
                <section class="admin-section">
                    <h3><i class="fas fa-plus-circle"></i> Crear Nuevo Rol de Sistema</h3>
                    <div class="formulario-admin">
                        <input type="text" id="nuevo-rol-nombre" placeholder="Nombre del Rol">
                        <h4>Asignar Permisos:</h4>
                        <div class="contenedor-checks">
                            ${checksPermisosHTML}
                        </div>
                        <button class="btn-save" onclick="guardarNuevoRol()">Guardar Rol</button>
                    </div>
                </section>`;
        }

        if (permisosUser.canAdminSecciones) {
    html += `
        <section class="admin-section">
            <h3><i class="fas fa-layer-group"></i> Secciones del Menú</h3>
            
            <div class="formulario-admin" style="margin-bottom: 20px; display: flex; gap: 10px;">
                <input type="text" id="nueva-seccion-nombre" placeholder="Nombre de la nueva sección" style="flex: 1;">
                <button class="btn-add-inline" onclick="guardarNuevaSeccion()" style="width: auto; padding: 0 20px;">
                    <i class="fas fa-plus"></i> Añadir
                </button>
            </div>

            <table class="col-acciones">
                <thead>
                    <tr>
                        <th>Nombre de la Sección</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    ${listadoSecciones}
                </tbody>
            </table>
        </section>`;
        }

        html += `</div>`;
        document.getElementById('area-trabajo').innerHTML = html;
    
        setTimeout(() => {
    const contenedor = document.querySelector('.contenedor-checks');
    
    if (contenedor) {
        let contenido = contenedor.innerHTML;
        const regex = /[a-z]+_[a-z_]+/g;
        const nuevoContenido = contenido.replace(regex, (match) => {
            return humanizarTexto(match);
        });
        
        contenedor.innerHTML = nuevoContenido;
        console.log("Limpieza completada mediante reemplazo de texto.");
        } else {
            console.log("Error: No se encontró la clase .contenedor-checks");
        }
            }, 100);
    }
}


function prepararEdicionFinal(btn, nombreId, destino) {
    try {
        const base64 = btn.getAttribute('data-info');
        
        const datos = JSON.parse(decodeURIComponent(escape(atob(base64))));
        
        prepararEdicion(btn, datos, nombreId, destino);
        
    } catch (e) {
        console.error("Error en decodificación:", e);
        alert("Error: No se pudieron cargar los datos para editar.");
    }
}

function prepararEdicion(elemento, datos, nombreIdOculto, archivoDestino) {
    const area = document.getElementById('area-trabajo');
    const form = area.querySelector('form');
    if(!form) return;

    form.action = archivoDestino;

    Object.keys(datos).forEach(llave => {
        const input = form.querySelector(`[name="${llave}"]`);
        if(input) {
            input.value = datos[llave];
        }
    });

    let inputId = form.querySelector(`[name="${nombreIdOculto}"]`);
    if(!inputId){
        inputId = document.createElement('input');
        inputId.type = 'hidden';
        inputId.name = nombreIdOculto;
        form.appendChild(inputId);
    }
    
    // [LOGICA DE RESCATE]: Si datos[nombreIdOculto] es undefined, buscamos el ID real
    // Esto cubre: Comunicados, Eventos, Galería y Documentos.
    const idReal = datos[nombreIdOculto] || datos['id_evento'] || datos['id_comunicado'] || datos['id_galeria'] || datos['id_documento'];
    
    inputId.value = idReal;

    // 4. Cambiamos visualmente el botón para que el usuario sepa que está editando
    const btnSubmit = form.querySelector('.btn-guardar') || form.querySelector('.btn-admin') || form.querySelector('button[type="submit"]');
    if(btnSubmit) {
        btnSubmit.innerText = "💾 Guardar Cambios";
        btnSubmit.style.background = "#27ae60";
    }

    window.scrollTo({ top: 0, behavior: 'smooth' });

    // Quitamos la obligatoriedad de la foto al editar (porque ya tiene una en el servidor)
    const inputFoto = form.querySelector('input[type="file"]');
    if (inputFoto) {
        inputFoto.required = false;
    }
}

function llaveIdReal(datos) {
    // Lista de todas las posibles llaves de ID que usas en tu DB
    const posiblesLlaves = ['id_comunicado', 'id_galeria', 'id_evento', 'id_documentos'];
    
    // Buscamos cuál de estas llaves existe realmente dentro del objeto 'datos'
    return posiblesLlaves.find(llave => datos.hasOwnProperty(llave)) || null;
}

document.addEventListener('DOMContentLoaded', function() {
    const userBtn = document.getElementById('userBtn');
    const userPanel = document.getElementById('userPanel');

    if (userBtn && userPanel) {
        userBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userPanel.classList.toggle('active');
        });

        document.addEventListener('click', function(e) {
            if (!userPanel.contains(e.target) && !userBtn.contains(e.target)) {
                userPanel.classList.remove('active');
            }
        });
    }
});

// documentos
function filtrarDocs(catId, element) {
    document.querySelectorAll('.repo-nav .btn-admin').forEach(btn => btn.classList.remove('active'));
    element.classList.add('active');

    const filas = document.querySelectorAll('.fila-doc');
    filas.forEach(fila => {
        if (catId === 'todos' || fila.getAttribute('data-cat') == catId) {
            fila.style.display = 'flex';
        } else {
            fila.style.display = 'none';
        }
    });
}

function guardarNuevoRol() {
    const nombre = document.getElementById('nuevo-rol-nombre').value;
    const checks = document.querySelectorAll('.check-permiso:checked');
    const permisos = Array.from(checks).map(c => c.value);

    if (nombre === "" || permisos.length === 0) {
        alert("Debes poner un nombre y seleccionar al menos un permiso.");
        return;
    }

    // Enviamos los datos al backend
    fetch('guardar_rol.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nombre: nombre, permisos: permisos })
    })
    .then(res => res.text())
    .then(data => {
        alert(data);
        location.reload();
    });
}

function guardarNuevaSeccion() {
    const nombre = document.getElementById('nueva-seccion-nombre').value;

    if (nombre.trim() === "") {
        alert("Por favor, escribe un nombre para la sección.");
        return;
    }

    fetch('guardar_seccion.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `nombre_seccion=${encodeURIComponent(nombre)}`
    })
    .then(res => res.text())
    .then(data => {
        alert("Sección creada con éxito");
        location.reload();
    })
    .catch(error => console.error('Error:', error));
}

// Cambiar rol de usuario
function cambiarRol(idUsuario, nuevoRol) {
    if (!nuevoRol || nuevoRol === "") return;

    if (confirm("¿Estás seguro de cambiar el rango a este usuario?")) {
        fetch('actualizar_rol.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id_usuario=${idUsuario}&id_rol=${nuevoRol}`
        })
        .then(res => res.text())
        .then(data => {
            alert(data);
            location.reload();
        });
    }
}

// Eliminar Usuario
function eliminarUsuario(idUsuario) {
    if (confirm("¡ATENCIÓN! Esto eliminará al usuario y su perfil permanentemente. ¿Proceder?")) {
        window.location.href = `eliminar_registro.php?id=${idUsuario}&tabla=usuarios&columna=id_usuario`;
    }
}

function abrirModalPerfil() {
    document.getElementById('editNombre').value = nombreUsuarioActual;
    document.getElementById('editCorreo').value = correoUsuarioActual;
    
    document.getElementById('modalPerfil').style.display = 'block';
}

function cerrarModalPerfil() {
    document.getElementById('modalPerfil').style.display = 'none';
}

// Cerrar si el usuario hace clic fuera del cuadro blanco
window.onclick = function(event) {
    let modal = document.getElementById('modalPerfil');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

function humanizarTexto(texto) {
    let resultado = texto.replace(/_/g, ' ');
    return resultado.charAt(0).toUpperCase() + resultado.slice(1);
}

document.getElementById('formActualizarPerfil').addEventListener('submit', function(e) {
    e.preventDefault();

    const pass = document.getElementById('editPass').value;
    const confirm = document.getElementById('confirmPass').value;

    if (pass !== "" && pass !== confirm) {
        alert("Las nuevas contraseñas no coinciden.");
        return;
    }

    const formData = new FormData(this);

    fetch('actualizar_perfil.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            alert("¡Datos actualizados!");
            location.reload();
        } else {
            alert(data.message);
            if (data.redireccionar) {
                window.location.href = "login.php";
            }
        }
    })
    .catch(error => console.error('Error:', error));
});

function editarSeccion(id, nombreActual) {
    const nuevoNombre = prompt("Nuevo nombre para la sección:", nombreActual);
    
    if (nuevoNombre && nuevoNombre.trim() !== "" && nuevoNombre !== nombreActual) {
        const formData = new FormData();
        formData.append('id_seccion', id);
        formData.append('nuevo_nombre', nuevoNombre.trim());

        fetch('actualizar_seccion.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.text())
        .then(data => {
            if (data.includes("success")) {
                alert("Sección actualizada correctamente");
                location.reload();
            } else {
                alert("Error: " + data);
            }
        })
        .catch(err => console.error("Error:", err));
    }
}