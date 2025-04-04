<?php

// Obtener el ID del curso de la URL
$cursoId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consulta principal para obtener los detalles del curso
$sqlCurso = "SELECT * FROM cursos_premium WHERE id = ?";
$stmtCurso = $conn->prepare($sqlCurso);
$stmtCurso->bind_param("i", $cursoId);
$stmtCurso->execute();
$resultCurso = $stmtCurso->get_result();

if ($resultCurso->num_rows > 0) {
    $curso = $resultCurso->fetch_assoc();
} else {
    echo "<div class='container mx-auto mt-10'><h2 class='text-red-600 text-center text-lg'>Curso no encontrado.</h2></div>";
    exit();
}

// Consultar archivos PDF relacionados
$sqlPdf = "SELECT ruta_pdf FROM archivos_pdf_premium WHERE curso_id = ?";
$stmtPdf = $conn->prepare($sqlPdf);
$stmtPdf->bind_param("i", $cursoId);
$stmtPdf->execute();
$resultPdf = $stmtPdf->get_result();

// Consultar imágenes relacionadas
$sqlImagenes = "SELECT ruta_imagen FROM imagenes_curso_premium WHERE curso_id = ?";
$stmtImagenes = $conn->prepare($sqlImagenes);
$stmtImagenes->bind_param("i", $cursoId);
$stmtImagenes->execute();
$resultImagenes = $stmtImagenes->get_result();

// Consultar videos relacionados
$sqlVideo = "SELECT ruta_video FROM videos_curso_premium WHERE curso_id = ?";
$stmtVideo = $conn->prepare($sqlVideo);
$stmtVideo->bind_param("i", $cursoId);
$stmtVideo->execute();
$resultVideo = $stmtVideo->get_result();

// Consultar enlaces relacionados con sus fechas
$sqlEnlaces = "SELECT url_enlace AS enlace, fecha
               FROM enlaces_cursos_premium 
               WHERE curso_id = ?";
$stmtEnlaces = $conn->prepare($sqlEnlaces);
$stmtEnlaces->bind_param("i", $cursoId);
$stmtEnlaces->execute();
$resultEnlaces = $stmtEnlaces->get_result();



$sqlDescripcion = "SELECT descripcion FROM cursos_premium WHERE id = ?";
$stmtDescripcion = $conn->prepare($sqlDescripcion);
$stmtDescripcion->bind_param("i", $cursoId);
$stmtDescripcion->execute();
$resultDescripcion = $stmtDescripcion->get_result();

?>

<div class="container mx-auto mt-10">
    <h1 class="text-4xl font-bold text-center text-gray-900 mb-6"><?php echo htmlspecialchars($curso['nombre_curso']); ?></h1>

    <!-- Descripción -->
    <?php if ($resultDescripcion->num_rows > 0): ?>
        <h2 class="text-3xl font-semibold text-gray-800 bg-gray-200 py-2 relative mb-6 mt-6">
            General
            <span class="absolute bottom-0 left-0 w-full h-1 bg-gray-500"></span>
        </h2>

        <?php while ($descripcion = $resultDescripcion->fetch_assoc()): ?>
            <p class="text-gray-800 text-base leading-relaxed">
                <?php echo ($descripcion['descripcion']); ?>
            </p>
        <?php endwhile; ?>
    <?php endif; ?>
    <br>

    <!-- Carrusel de videos -->
    <?php if ($resultVideo->num_rows > 0): ?>
        <h2 class="text-2xl font-medium text-gray-900 bg-gray-200 py-2 relative mb-6">
            Videos del Curso
            <span class="absolute bottom-0 left-0 w-full h-1 bg-gray-500"></span>
        </h2>

        <div class="relative">
            <div class="flex space-x-4 overflow-x-auto snap-x snap-mandatory" id="videoCarousel">
                <?php
                $videoIndex = 1;
                while ($video = $resultVideo->fetch_assoc()): ?>
                    <div class="relative flex-shrink-0 w-full max-w-xs snap-center">
                        <div class="absolute top-2 left-2 bg-purple-600 text-white px-3 py-1 rounded-md text-sm font-semibold">
                            Video <?php echo $videoIndex; ?>
                        </div>
                        <video class="w-full h-64 rounded-lg shadow-md" controls controlsList="nodownload">
                            <source src="./admin/controllers/<?php echo htmlspecialchars($video['ruta_video']); ?>" type="video/mp4">
                            Tu navegador no soporta el elemento de video.
                        </video>
                    </div>
                <?php
                    $videoIndex++;
                endwhile; ?>
            </div>

            <button id="scrollLeftButton" class="absolute top-1/2 left-0 transform -translate-y-1/2 bg-gray-800 text-white p-2 rounded-full hover:bg-gray-700 focus:outline-none">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button id="scrollRightButton" class="absolute top-1/2 right-0 transform -translate-y-1/2 bg-gray-800 text-white p-2 rounded-full hover:bg-gray-700 focus:outline-none">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <script>
            const carousel = document.getElementById('videoCarousel');
            const scrollLeftButton = document.getElementById('scrollLeftButton');
            const scrollRightButton = document.getElementById('scrollRightButton');

            function scrollCarouselLeft() {
                carousel.scrollBy({
                    left: -carousel.offsetWidth,
                    behavior: 'smooth'
                });
            }

            function scrollCarouselRight() {
                carousel.scrollBy({
                    left: carousel.offsetWidth,
                    behavior: 'smooth'
                });
            }

            scrollLeftButton.addEventListener('click', scrollCarouselLeft);
            scrollRightButton.addEventListener('click', scrollCarouselRight);
        </script>
    <?php endif; ?>

    <!-- Sección de PDFs -->
    <?php if ($resultPdf->num_rows > 0): ?>
        <h2 class="text-2xl font-medium text-gray-900 bg-gray-200 py-2 relative mb-6">
            Archivos PDF
            <span class="absolute bottom-0 left-0 w-full h-1 bg-gray-500"></span>
        </h2>

        <ul class="list-disc list-inside mb-6 space-y-2">
            <?php while ($pdf = $resultPdf->fetch_assoc()): ?>
                <li class="flex items-center">
                    <i class="fas fa-file-pdf text-red-600 mr-2"></i>
                    <a href="./admin/controllers/<?php echo htmlspecialchars($pdf['ruta_pdf']); ?>" class="text-blue-600 underline hover:text-blue-800 transition duration-200" target="_blank">
                        <?php echo htmlspecialchars(basename($pdf['ruta_pdf'])); ?>
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>

    <!-- Sección de imágenes -->
    <?php if ($resultImagenes->num_rows > 0): ?>
        <h2 class="text-2xl font-medium text-gray-900 bg-gray-200 py-2 relative mb-6">
            Imágenes
            <span class="absolute bottom-0 left-0 w-full h-1 bg-gray-500"></span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php while ($imagen = $resultImagenes->fetch_assoc()): ?>
                <div class="bg-white shadow-lg rounded-lg overflow-hidden transition transform hover:scale-105 duration-300 cursor-pointer" onclick="openModal('<?php echo './admin/controllers/' . htmlspecialchars($imagen['ruta_imagen']); ?>')">
                    <img src="./admin/controllers/<?php echo htmlspecialchars($imagen['ruta_imagen']); ?>" alt="Imagen del curso" class="w-full h-100 object-cover rounded-lg">
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
    <br>

    <!-- Enlaces del curso -->
    <?php if ($resultEnlaces->num_rows > 0): ?>
        <h2 class="text-2xl font-medium text-gray-900 bg-gray-200 py-2 relative mb-6">
            Clases en vivo
            <span class="absolute bottom-0 left-0 w-full h-1 bg-gray-500"></span>
        </h2>

        <div class="bg-gray-100 border border-gray-300 rounded-lg p-4">
            <?php while ($enlace = $resultEnlaces->fetch_assoc()): ?>
                <div class="mb-4">
                    <a href="<?php echo htmlspecialchars($enlace['enlace']); ?>" 
                       class="text-blue-600 hover:text-blue-800 underline" target="_blank">
                        <?php echo htmlspecialchars($enlace['enlace']); ?>
                    </a>
                    <p class="text-sm text-gray-600 mt-2">
                        Fecha y hora: <?php echo htmlspecialchars(date("d/m/Y H:i", strtotime($enlace['fecha']))); ?>
                    </p>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-red-600">No hay enlaces disponibles para este curso.</p>
    <?php endif; ?>

    <br>
</div>

<!-- Modal para imagen en pantalla completa -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden flex items-center justify-center z-50">
    <span class="absolute top-5 right-5 text-white cursor-pointer text-3xl" onclick="closeModal()">&times;</span>
    <img id="modalImage" class="max-w-full max-h-full object-contain" src="" alt="Imagen a pantalla completa">
</div>

<script>
    function openModal(imageSrc) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageSrc;
        modal.classList.remove('hidden');
    }

    function closeModal() {
        const modal = document.getElementById('imageModal');
        modal.classList.add('hidden');
    }
</script>
