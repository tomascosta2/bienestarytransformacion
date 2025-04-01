<?php
// echo 'Hola';
// Incrementar la cantidad de visitas para el día actual
// $dateToday = date('Y-m-d');
// $insertVisitQuery = "
//     INSERT INTO visitas (fecha, cantidad)
//     VALUES ('$dateToday', 1)
//     ON DUPLICATE KEY UPDATE cantidad = cantidad + 1;
// ";
// $conn->query($insertVisitQuery);
?>
<section class="relative text-left" style="min-height: 90vh;">
    <!-- Video de fondo con brillo ajustado -->
    <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover" style="filter: brightness(0.5);">
        <source src="./images/background.mp4" type="video/mp4">
        Tu navegador no soporta video en HTML5.
    </video>
    <!-- Overlay en gradiente para un efecto más sofisticado -->
    <div class="absolute inset-0 bg-gradient-to-br from-purple-900 to-purple-700 opacity-80"></div>
    <div class="container mx-auto relative z-10 px-6 md:px-0 py-20 flex flex-col items-start justify-center space-y-6">
        <!-- Título con tipografía serif y mayor tamaño -->
        <h1 class="text-5xl md:text-6xl font-bold text-white mb-4 font-playfair">
            Bienvenidos a Luz Mística
        </h1>
        <!-- Descripción con tipografía sans-serif y espaciado amplio -->
        <p class="text-xl md:text-2xl text-gray-300 max-w-2xl mb-8 font-lato leading-relaxed">
            En Luz Mística, ofrecemos un espacio dedicado al bienestar personal y desarrollo profesional. Con herramientas hacia el equilibrio y crecimiento en todas las etapas de tu vida. Integrando lo mental, emocional y energético
        </p>
        <!-- Botones de CTA con estilo refinado -->
        <div class="flex justify-start space-x-4">
            <a href="#cursos" class="bg-gray-700 text-white text-sm md:text-lg px-6 md:px-10 py-2 md:py-4 rounded-full border border-gray-500 shadow-lg hover:bg-gray-800 transition duration-300">
                VER NUESTROS CURSOS
            </a>
            <a href="#contacto" class="bg-purple-600 text-white text-sm md:text-lg px-6 md:px-10 py-2 md:py-4 rounded-full border border-purple-500 shadow-lg hover:bg-purple-700 transition duration-300">
                AGENDA UNA SESIÓN
            </a>
        </div>
        <!-- Testimonio breve con separación y cursiva mejorada -->
        <blockquote class="mt-12 italic text-gray-400 text-lg md:text-xl max-w-xl border-l-4 border-purple-500 pl-4">
            “Nuestro compromiso es acompañarte con seriedad y dedicación en tu camino hacia el bienestar y la paz interior.”
        </blockquote>
    </div>
</section>
<section class="bg-gray-100 py-16">
    <div class="container mx-auto text-center mb-10 px-4">
        <h2 class="text-3xl font-bold" style="color: #c09ecc;">Únete a nuestra comunidad de aprendizaje espiritual</h2>
        <p class="text-gray-600">¡Contamos con diferentes opciones para que siempre puedas ser parte!</p>
    </div>
    <!-- Contenedor con imagen de fondo -->
    <div class="relative">
        <!-- Imagen de fondo detrás de las cartas -->
        <div class="absolute inset-0 bg-center bg-no-repeat bg-cover" style="background-image: url('./images/background-card.png'); top: 30px; bottom: 30px;">
        </div>

        <!-- Cartas con contenido -->
        <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 px-4 relative z-10">
            <!-- Carta 1: Charlas Gratuitas -->
            <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                <h3 class="text-2xl font-semibold mb-4" style="color: #c09ecc;">CHARLAS GRATUITAS</h3>
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
                <h3 class="text-2xl font-semibold mb-4" style="color: #c09ecc;">CURSOS Y TALLERES</h3>
                <p class="text-gray-700 mb-6">
                    Cursos diseñados para profundizar en temas de sanacion y autoconocimiento. Explora nuestras capacitaciones y elegi las que mejor complementen tu camino profesional y personal. Podras consultarme ante dudas. Te acompaño en cada paso del proceso.
                </p>
                <a href="#cursosdestacados" class="inline-block text-white font-semibold px-6 py-2 rounded-full transition duration-300" style="background-color: #c09ecc;">
                    Ver Cursos y Talleres
                </a>
            </div>
            <!-- Carta 3: Plan Premium -->
            <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                <h3 class="text-2xl font-semibold mb-4" style="color: #c09ecc;">PLAN PREMIUM</h3>
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
</section>
<section id="cursosgratuitos" class="bg-gray-100 py-16">
    <div class="container mx-auto text-center mb-10 px-4">
        <h2 class="text-3xl font-bold" style="color: #c09ecc;">CURSOS Y CHARLAS GRATUITAS</h2>
        <p class="text-gray-600">Explora nuestras capacitaciones y elige las que mejor complementen tu camino!</p>
    </div>

    <?php
    // Consulta para obtener todos los cursos
    $sqlCursos = "SELECT * FROM cursos_gratuitos";
    $resultCursos = $conn->query($sqlCursos);
    ?>
    <!-- Cursos y charlas gratuitas -->
    <div class="relative">
        <!-- Cartas con contenido -->
        <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 px-4 relative z-10">
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
    </div>
</section>


<section id="cursosdestacados" class="bg-gray-100 py-16">
    <div class="container mx-auto text-center mb-10 px-4">
        <h2 class="text-4xl font-bold mb-4" style="color: #c09ecc;">CURSOS Y TALLERES</h2>
        <p class="text-gray-600 text-lg max-w-2xl mx-auto">Explora nuestras capacitaciones y elige las que mejor complementen tu camino profesional y personal!</p>
    </div>

    <?php
    // Consulta para obtener todos los cursos
    $sqlCursos = "SELECT * FROM cursos_destacados";
    $resultCursos = $conn->query($sqlCursos);
    ?>

    <div class="container mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10 px-4">
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
</section>



<?php
// Inicia la sesión si aún no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<section id="planpremium" class="bg-gray-100 py-16">
    <div class="container mx-auto text-center mb-10 px-4">
        <h2 class="text-3xl font-bold" style="color: #c09ecc;">PLAN PREMIUM</h2>
        <p class="text-gray-600">En este Plan vas a Tener Acceso a Todos los Cursos, talleres y contenido exclusivo para miembros.</p>
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
            <p class="text-gray-700 text-lg">Esta sección es para usuarios premium. Si deseas inscribirte en nuestro plan, pulsa el botón de abajo.</p>
            <a href="./?page=plan_premium" class="inline-block bg-purple-500 text-white px-4 py-2 mt-4 rounded-lg hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-300">
                Inscribirse en el Plan Premium
            </a>
        </div>
    <?php endif; ?>
</section>








<!-- <div id="quiensoy" class="flex flex-col md:flex-row items-center md:items-stretch h-screen bg-white">
     Sección de imagen a la izquierda 
    <div class="md:w-1/2 bg-cover bg-center" style="background-image: url('./images/prueba.jpeg');">
        La imagen se define como fondo de esta sección para ocupar todo el espacio
    </div>

     Sección de texto a la derecha 
    <div class="md:w-1/2 flex flex-col justify-center items-start p-10 space-y-4">
        <h2 class="text-3xl font-semibold text-gray-800">Quién soy</h2>
        <p class="text-gray-600 text-lg leading-relaxed">
            Bienvenido/a a un espacio de transformación y bienestar. Soy un guía en el camino de la sanación y el crecimiento interior. A través de prácticas holísticas y personalizadas, acompaño a cada persona en su viaje hacia el equilibrio, la paz y la conexión profunda con su esencia.
        </p>
        <p class="text-gray-600 text-lg leading-relaxed">
            Mi misión es ayudarte a redescubrir tu propio poder y sabiduría interior, proporcionando herramientas que fomenten el amor propio, la calma y la claridad. Desde sesiones personalizadas hasta talleres grupales, cada encuentro está diseñado para armonizar mente, cuerpo y espíritu.
        </p>
        <p class="text-gray-600 text-lg leading-relaxed">
            Si sientes el llamado a reconectar contigo mismo/a y explorar tu verdadero potencial, estaré aquí para acompañarte con amor y respeto en cada paso de tu viaje.
        </p>
    </div>
</div> -->
<div id="contacto" class="flex flex-col md:flex-row items-center md:items-stretch h-screen bg-white">
    <!-- Sección de formulario de contacto a la izquierda -->
    <div class="md:w-1/2 flex flex-col justify-center items-start p-10 space-y-6 order-2 md:order-1">
        <h2 class="text-4xl font-semibold text-purple-700 flex items-center">
            <i class="fas fa-envelope-open-text mr-3 text-pink-400"></i> Contáctanos
        </h2>
        <p class="text-gray-500 text-lg">Nos encantaría saber de ti. Envíanos un mensaje y te responderemos pronto.</p>
        <!-- Formulario -->
        <form id="contactForm" class="w-full max-w-md space-y-4">
            <div class="flex items-center">
                <i class="fas fa-user text-pink-400 mr-3"></i>
                <label for="name" class="block text-gray-600">Nombre</label>
            </div>
            <input type="text" id="name" name="name" required class="w-full p-3 border border-gray-300 rounded-md focus:border-pink-400 focus:ring-pink-200" placeholder="Tu nombre">

            <div class="flex items-center mt-4">
                <i class="fas fa-comment text-pink-400 mr-3"></i>
                <label for="message" class="block text-gray-600">Mensaje</label>
            </div>
            <textarea id="message" name="message" rows="4" required class="w-full p-3 border border-gray-300 rounded-md focus:border-pink-400 focus:ring-pink-200" placeholder="Escribe tu mensaje"></textarea>

            <!-- Botón de envío -->
            <button type="submit" class="w-full bg-pink-500 text-white px-4 py-2 rounded-md hover:bg-pink-600 transition duration-300 mt-6 flex items-center justify-center">
                <i class="fas fa-paper-plane mr-2"></i> Enviar
            </button>
        </form>
    </div>

    <!-- Sección de imagen a la derecha -->
    <div class="md:w-1/2 bg-cover bg-center order-1 md:order-2 h-64 md:h-auto" style="background-image: url('./images/prueba.jpeg');">
        <!-- La imagen se define como fondo de esta sección para ocupar todo el espacio -->
    </div>
</div>

<!-- Script para redirigir a WhatsApp con el mensaje -->
<script>
    document.getElementById("contactForm").addEventListener("submit", function(event) {
        event.preventDefault(); // Evita que el formulario se envíe tradicionalmente

        // Captura los valores de los campos
        const nombre = document.getElementById("name").value;
        const mensaje = document.getElementById("message").value;

        // Construir el mensaje de WhatsApp
        const textoMensaje = `Hola, soy ${nombre}, ${mensaje}`;

        // Número de WhatsApp con el código de país y área
        const numeroWhatsApp = "5492915386276";

        // URL de WhatsApp con el mensaje formateado
        const urlWhatsApp = `https://wa.me/${numeroWhatsApp}?text=${encodeURIComponent(textoMensaje)}`;

        // Abrir la URL de WhatsApp en una nueva pestaña
        window.open(urlWhatsApp, "_blank");
    });
</script>


</div>