<?php
    session_start();
    include_once 'funciones_permisos.php'
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jose Diego Maria Rivera</title>
    <link rel="stylesheet" href="css/estilos.css?v=1.110">
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

    <div class="contenido contenido-inicio">

    <div class="contenedor-padre">
        <div class="bloque-contenido">
            <div class="texto-columna">
                <h2>¿Quienes somos?</h2>
                <p>
                    La Unidad Educativa Privada (U.E.P.) José Diego María Rivera, ubicada en (Insertar ubicación) es una institución educativa de carácter privado que se ha consolidado como un referente en el ámbito educativo local. Con una trayectoria de al menos 19 años de servicio ininterrumpido, el "Rivera" ha dejado una huella imborrable en generaciones de estudiantes, caracterizándose por brindar espacios formativos que trascienden la mera transmisión de conocimientos. Su enfoque se centra en la felicidad y el desarrollo integral de sus niños, niñas y adolescentes, posicionándose como una casa de estudios reconocida por su compromiso con la excelencia académica y la formación en valores.
                    </p>
                    <p>
                    La Gran Familia Dieguista: Una Identidad Única. Más que un colegio, la U.E.P. José Diego María Rivera es una verdadera comunidad. Se le conoce comúnmente como "La Gran Familia Dieguista", un apelativo que refleja el profundo sentido de pertenencia, compañerismo y apoyo mutuo que se respira en sus aulas y pasillos. Este espíritu familiar no solo une a los estudiantes, sino que también involucra activamente a docentes, personal administrativo, obrero y, fundamentalmente, a los padres y representantes, quienes son pilares fundamentales en el proceso educativo.
                </p>
            </div>

            <div class="imagen-columna">
                <video autoplay controls src="img_video/WhatsApp Video 2026-03-02 at 4.46.59 PM.mp4"></video>
            </div>
        </div>
    </div>
<hr class="linea-separadora">
    <div class="contenedor-padre">
        <div class="bloque-contenido">
            <div class="texto-columna">
                <h2>"Educar en Valores es Nuestro Compromiso"</h2>
                <p>
                        La misión principal de nuestra institución es clara y ambiciosa: crear un entorno donde los estudiantes sean genuinamente felices. Se parte de la premisa de que un niño feliz es un niño más receptivo al aprendizaje, más creativo y más propenso a desarrollar todas sus potencialidades. Para lograrlo, la U.E.P. José Diego María Rivera combina la enseñanza académica de alto nivel con un enfoque pedagógico que prioriza el bienestar emocional. Se fomenta la inteligencia emocional, la empatía, la resiliencia y la autoestima, brindando a los estudiantes las herramientas necesarias para enfrentar los desafíos de la vida con confianza y optimismo.
                    </p>
                    <p>
                        En la mención de Ciencias y Tecnología, impulsamos el espíritu investigador y la curiosidad intelectual. Nuestros laboratorios y aulas son espacios de innovación donde el error se ve como una oportunidad de aprendizaje y el éxito como el resultado de la constancia. Al egresar, nuestros bachilleres no solo llevan consigo un certificado de alta competitividad, sino también la brújula moral necesaria para navegar con éxito en un mundo en constante cambio. En la U.E.P. José Diego María Rivera, no solo preparamos para la universidad; preparamos para la vida, cultivando mentes brillantes y corazones nobles que sean orgullo de sus familias y de nuestra nación.
                    </p>
            </div>

            <div class="imagen-columna">
                <img src="img_video/Valores.jpeg" alt="Valores Institucionales">
            </div>
        </div>
    </div>
<hr class="linea-separadora">
    <div class="contenedor-padre">
        <div class="bloque-contenido">
            <div class="texto-columna">
                <h2>Formación en Valores</h2>
                    <p>
                    El Colegio José Diego María Rivera se fundamenta en una filosofía de formación integral donde la excelencia académica converge con la sensibilidad social y el pensamiento creativo. Nuestra institución entiende que educar es un acto de transformación constante, por lo que priorizamos el desarrollo de ciudadanos íntegros que actúen bajo principios de honestidad, respeto mutuo y solidaridad comunitaria. 
                </p>
                <p>
                    Fomentamos un entorno de aprendizaje dinámico donde la curiosidad intelectual se equilibra con la responsabilidad ética, impulsando a cada estudiante a descubrir su potencial único para convertirse en un líder consciente y comprometido con el bienestar de su entorno. Creemos firmemente que la disciplina y el esfuerzo son los pilares que permiten convertir el talento en una herramienta de servicio, logrando así que nuestros egresados no solo dominen el conocimiento técnico, sino que también posean la calidad humana necesaria para construir una sociedad más justa, innovadora y profundamente humana.
                </p>
            </div>
            <div class="imagen-columna">
                <img src="img_video/Formacion.jpeg" alt="Formación y Recursos">
            </div>
        </div>
    </div>
<hr class="linea-separadora">
        <div class="contenedor-padre">
            <h2>Infraestructura y Recursos</h2>
            <div class="bloque-anclado">
                <div class="texto-anclado">
                    <p>
                        La institución se enorgullece de contar con instalaciones modernas y vanguardistas, meticulosamente diseñadas para favorecer el desarrollo integral de los estudiantes en las dimensiones académica, deportiva, artística y cultural. Nuestra infraestructura no es solo un conjunto de edificios, sino un ecosistema educativo donde el aprendizaje cobra vida a través de aulas amplias, iluminadas y con ventilación óptima que garantizan un ambiente de confort y máxima concentración.
                    </p>
                </div>
                <div class="imagen-anclada">
                    <img src="img_video/Infraestructura.jpeg" alt="Instalaciones">
                </div>
            </div>

            <div class="texto-libre">
                <p>
                Este compromiso con la calidad se extiende a nuestros laboratorios de ciencias y computación, los cuales están equipados con tecnología de última generación para transformar la teoría en experiencia práctica y fomentar las competencias digitales necesarias en el mundo actual. Asimismo, nuestra biblioteca se constituye como un centro de pensamiento y recursos bibliográficos donde el silencio y la investigación invitan al crecimiento intelectual constante.
                </p>
                <p>
                Más allá de lo académico, entendemos la importancia del equilibrio físico y emocional, por lo que disponemos de áreas deportivas de alto nivel y espacios recreativos al aire libre que promueven la sana convivencia y el trabajo en equipo. Cada rincón del campus, desde los salones dedicados a la expresión artística hasta las zonas verdes de esparcimiento, ha sido creado para potenciar las habilidades naturales de los alumnos, brindándoles las herramientas necesarias para una recreación constructiva y una formación de excelencia que trasciende el aula de clases.
                </p>
                
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