<?php
// Incrementar la cantidad de visitas para el día actual
$dateToday = date('Y-m-d');
$insertVisitQuery = "
    INSERT INTO visitas (fecha, cantidad)
    VALUES ('$dateToday', 1)
    ON DUPLICATE KEY UPDATE cantidad = cantidad + 1;
";
$conn->query($insertVisitQuery);
?>
<div>
    <section class="relative text-left" style="min-height: 90vh;">
        <!-- Video de fondo con brillo ajustado -->
        <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover" style="filter: brightness(0.5);">
            <source src="./images/background.mp4" type="video/mp4">
            Tu navegador no soporta video en HTML5.
        </video>
        <div class="max-w-6xl xl:max-w-7xl mx-auto">
            <!-- Overlay en gradiente para un efecto más sofisticado -->
            <div class="absolute inset-0 bg-gradient-to-br from-purple-900 to-purple-700 opacity-80"></div>
            <div class="container mx-auto relative z-10 px-6 md:px-0 py-20 flex flex-col items-start justify-center space-y-6">
                <!-- Título con tipografía serif y mayor tamaño -->
                <h1 class="text-5xl md:text-6xl font-bold text-white mb-4 font-playfair">
                    Bienvenidos a Escuela Bienestar Integral 
                </h1>
                <!-- Descripción con tipografía sans-serif y espaciado amplio -->
                <p class="text-xl md:text-2xl text-gray-300 max-w-2xl mb-8 font-lato leading-relaxed">
                    Escuela Bienestar integral, es un espacio creado por
                    Karen Manzi, donde tu trasformación personal y
                    profesional son simples y accesibles. Ciencia y energia
                    se combinan para brindarte herramientas practicas y
                    aplicables, mas alla de la información.
                </p>
                <!-- Botones de CTA con estilo refinado -->
                <div class="flex justify-start gap-8 md:gap-4 md:flex-row flex-col md:w-fit w-full">
                    <div class="relative">
                        <span class="absolute -rotate-12 px-12 top-[6px] left-[calc(50%-90px)] md:left-[35px] py-3 bg-red-700 text-white rounded-md text-[14px]">Agenda llena</span>
                        <button href="#contacto" class="md:w-fit w-full bg-gray-600 cursor-default text-white text-sm md:text-lg px-6 md:px-10 py-4 md:py-4 rounded-full shadow-lg">
                            AGENDA UNA SESIÓN
                        </button>
                    </div>
                    <a href="#cursosdestacados" class="md:w-fit w-full bg-purple-600 text-center text-white text-sm md:text-lg px-6 md:px-10 py-4 md:py-4 rounded-full border border-purple-500 shadow-lg hover:bg-purple-700 transition duration-300">
                        VER NUESTROS CURSOS
                    </a>
                </div>
                <!-- Testimonio breve con separación y cursiva mejorada -->
                <blockquote class="mt-12 italic text-gray-400 text-lg md:text-xl max-w-xl border-l-4 border-purple-500 pl-4">
                    “Expande tu consciencia, transforma tu vida.”
                </blockquote>
            </div>
        </div>
    </section>
    <section class="bg-gray-100 py-16">
        <div class="max-w-6xl xl:max-w-7xl mx-auto">
            <div class="container mx-auto text-center mb-10 px-4">
                <h2 class="text-3xl font-bold" style="color: #c09ecc;">Únete a nuestra comunidad de aprendizaje espiritual</h2>
                <p class="text-gray-600">¡Contamos con diferentes opciones para que siempre puedas ser parte!</p>
            </div>
            <!-- Contenedor con imagen de fondo -->
            <div class="relative">
                <!-- Cartas con contenido -->
                <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 px-4 relative z-10">
                    <!-- Carta 1: Charlas Gratuitas -->
                    <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                        <h3 class="text-2xl font-semibold mb-4" style="color: #c09ecc;">Recursos Gratuitos</h3>
                        <p class="text-gray-700 mb-6">
                            Accede a nuestros cursos gratuitos y conoce las bases del equilibrio emocional y físico. Perfecto para dar el primer paso en tu camino de autoconocimiento.
                            Recibe recursos exclusivos como guías, ejercicios y meditaciones para iniciar tu transformación personal de manera gratuita.
                        </p>
                        <a href="#cursosgratuitos" class="inline-block text-white font-semibold px-6 py-2 rounded-full transition duration-300" style="background-color: #c09ecc;">
                            Explorar Contenido Gratuito
                        </a>
                    </div>

                    <!-- Carta 2: Cursos y Talleres -->
                    <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                        <h3 class="text-2xl font-semibold mb-4" style="color: #c09ecc;">Cursos, Talleres y Diplomados</h3>
                        <p class="text-gray-700 mb-6">
                            Cursos diseñados para profundizar en temas de sanacion y autoconocimiento. Explora nuestras capacitaciones y elegi las que mejor complementen tu camino profesional y personal. Podras consultarme ante dudas. Te acompaño en cada paso del proceso.
                        </p>
                        <a href="#cursosdestacados" class="inline-block text-white font-semibold px-6 py-2 rounded-full transition duration-300" style="background-color: #c09ecc;">
                            Ver Cursos y Talleres
                        </a>
                    </div>
                    <!-- Carta 3: Plan Premium -->
                    <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                        <h3 class="text-2xl font-semibold mb-4" style="color: #c09ecc;">Plan Premium</h3>
                        <p class="text-gray-700 mb-6">
                            Acceso ilimitado a todos los cursos y talleres de nuestro catálogo con una suscripción mensual. Explora sin límites y a tu propio ritmo.
                            Recibe contenidos especiales cada mes, como nuevos módulos, actualizaciones y charlas exclusivas solo para miembros premium.
                        </p>
                        <a href="#planpremium" class="inline-block text-white font-semibold px-6 py-2 rounded-full transition duration-300" style="background-color: #c09ecc;">
                            Unirse al Plan Premium
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="cursosgratuitos" class="bg-gray-100 py-16">
        <div class="max-w-6xl xl:max-w-7xl mx-auto">
            <div class="container mx-auto text-center mb-10 px-4">
                <h2 class="text-3xl font-bold" style="color: #c09ecc;">Recursos Gratuitos</h2>
                <p class="text-gray-600">Explora nuestras capacitaciones y elige las que mejor complementen tu camino!</p>
            </div>

            <?php
            // Consulta para obtener todos los cursos
            $sqlCursos = "SELECT * FROM cursos_gratuitos";
            $resultCursos = $conn->query($sqlCursos);
            ?>
            <!-- Cursos y charlas gratuitas -->
            <div class="relative px-4">
                <!-- Cartas con contenido -->
                <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">
                    <?php if ($resultCursos->num_rows > 0): ?>
                        <?php while ($curso = $resultCursos->fetch_assoc()): ?>
                            <a href="./?page=detalles_cursos&id=<?php echo $curso['id']; ?>" class="bg-white shadow-lg rounded-lg p-6 text-center transition-transform transform hover:scale-105 shadow-lg hover:shadow-2xl">
                                <img src="./admin/controllers/<?php echo $curso['imagen_portada']; ?>" alt="Portada del curso" class="w-full h-32 object-cover mb-4 rounded-lg md:h-48"> <!-- Ajustar altura para dispositivos móviles -->
                                <h4 class="text-xl font-semibold mb-2" style="color: #c09ecc;"><?php echo $curso['nombre_curso']; ?></h4>
                                <p class="text-gray-700 mb-4">
                                    <?php echo substr($curso['descripcion'], 0, 100) . '...'; // Muestra los primeros 100 caracteres 
                                    ?>
                                </p>
                            </a>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-gray-700 text-center">No hay cursos disponibles en este momento.</p>
                    <?php endif; ?>
                </div>
                <a href="#cursosdestacados" class="block mt-8 mx-auto md:w-fit w-full bg-purple-600 text-center text-white text-sm md:text-lg px-6 md:px-10 py-4 md:py-4 rounded-full border border-purple-500 shadow-lg hover:bg-purple-700 transition duration-300">
                    Ver todos
                </a>
            </div>
        </div>
    </section>


    <section id="cursosdestacados" class="bg-gray-100 py-16">
        <div class="max-w-6xl xl:max-w-7xl mx-auto px-4">
            <div class="container mx-auto text-center mb-10 px-4">
                <h2 class="text-4xl font-bold mb-4" style="color: #c09ecc;">Cursos, Talleres y Diplomados</h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">Explora nuestras capacitaciones y elige las que mejor complementen tu camino profesional y personal!</p>
            </div>

            <?php
            // Consulta para obtener todos los cursos
            $sqlCursos = "SELECT * FROM cursos_destacados";
            $resultCursos = $conn->query($sqlCursos);
            ?>

            <div class="container mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10">
                <?php if ($resultCursos->num_rows > 0): ?>
                    <?php while ($curso = $resultCursos->fetch_assoc()): ?>
                        <div class="bg-white shadow-lg rounded-lg overflow-hidden transform hover:scale-105 transition duration-300">
                            <a href="./?page=detalles_cursos_destacado&id=<?php echo $curso['id']; ?>" class="bg-white shadow-lg rounded-lg p-6 text-center transition-transform transform hover:scale-105 shadow-lg hover:shadow-2xl">
                                <img src="./admin/controllers/<?php echo $curso['imagen_portada']; ?>" alt="Portada del curso" class="w-full h-48 object-cover group-hover:opacity-90 transition duration-300">
                                <div class="p-6">
                                    <h4 class="text-2xl font-semibold text-purple-600 group-hover:text-purple-700 transition duration-200 mb-2">
                                        <?php echo $curso['nombre_curso']; ?>
                                    </h4>
                                    <p class="text-gray-700 mb-4"><?php echo substr($curso['descripcion'], 0, 80) . '...'; ?></p>
                                </div>
                            </a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-gray-700 text-center">No hay cursos disponibles en este momento.</p>
                <?php endif; ?>
            </div>
            <a href="#cursosdestacados" class="block mt-8 mx-auto md:w-fit w-full bg-purple-600 text-center text-white text-sm md:text-lg px-6 md:px-10 py-4 md:py-4 rounded-full border border-purple-500 shadow-lg hover:bg-purple-700 transition duration-300">
                Ver todos
            </a>
        </div>
    </section>



    <?php
    // Inicia la sesión si aún no está iniciada
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    ?>

    <section id="planpremium" class="bg-gray-100 py-16">
        <div class="container mx-auto text-center mb-10 px-4">
            <h2 class="text-3xl font-bold" style="color: #c09ecc;">Plan Premium</h2>
            <p class="text-gray-600 mt-4 max-w-[500px] mx-auto">
                En este plan, tendrás acceso exclusivo a todos los <strong>cursos, talleres, prácticas en
                vivo, contenido especial y nuevos cursos</strong> todos los meses por $24.900 por mes.
                <br><br>
                Si deseas inscribirte y ser parte de la comunidad, pulsa el botón de abajo.
            </p>
        </div>

        <?php if (isset($_SESSION['es_premium']) && $_SESSION['es_premium']): ?>
            <?php
            $sqlCursos = "SELECT * FROM cursos_premium";
            $resultCursos = $conn->query($sqlCursos);
            ?>
            <div class="relative container mx-auto px-4">
                <?php if ($resultCursos->num_rows > 0): ?>
                    <?php while ($curso = $resultCursos->fetch_assoc()): ?>
                        <div class="flex flex-col md:flex-row items-center bg-white shadow-lg rounded-lg p-6 mb-4">
                            <!-- Imagen del curso -->
                            <a href="./?page=detalles_cursos_premium&id=<?php echo $curso['id']; ?>" class="md:w-1/3 w-full mb-4 md:mb-0">
                                <img src="./admin/controllers/<?php echo $curso['imagen_portada']; ?>" alt="Portada del curso" class="w-full h-32 object-cover rounded-lg md:h-48">
                            </a>

                            <!-- Detalles del curso -->
                            <div class="md:w-2/3 md:pl-6">
                                <h4 class="text-xl font-semibold mb-2" style="color: #c09ecc;">
                                    <?php echo $curso['nombre_curso']; ?>
                                </h4>
                                <p class="text-gray-700 mb-4">
                                    <?php echo substr($curso['descripcion'], 0, 100) . '...'; ?>
                                </p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-gray-700 text-center">No hay cursos disponibles en este momento.</p>
                <?php endif; ?>

                <div class="text-center mt-6">
                    <a href="./?page=detalles_clases" class="inline-block bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-300">
                        Ver Clases en Vivo
                    </a>
                </div>
            </div>
        <?php else: ?>
            <!-- Mensaje para usuarios no premium -->
            <div class="container mx-auto text-center mt-10 px-4">
                <!-- <p class="text-gray-700 text-lg">Esta sección es para usuarios premium. Si deseas inscribirte en nuestro plan, pulsa el botón de abajo.</p> -->
                <a href="./?page=registrarse&action=pay" class="block mt-8 mx-auto md:w-fit w-full bg-purple-600 text-center text-white text-sm md:text-lg px-6 md:px-10 py-4 md:py-4 rounded-full border border-purple-500 shadow-lg hover:bg-purple-700 transition duration-300">
                    Inscribirse en el Plan Premium
                </a>
            </div>
        <?php endif; ?>
    </section>
</div>

</div>