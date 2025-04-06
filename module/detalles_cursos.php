<?php

// Obtener el ID del curso de la URL
$cursoId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consulta principal para obtener los detalles del curso
$sqlCurso = "SELECT * FROM cursos_gratuitos WHERE id = ?";
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

// Consultar descripcion del curso
$sqlDescripcion = "SELECT descripcion FROM cursos_gratuitos WHERE id = ?";
$stmtDescripcion = $conn->prepare($sqlDescripcion);
$stmtDescripcion->bind_param("i", $cursoId);
$stmtDescripcion->execute();
$resultDescripcion = $stmtDescripcion->get_result();

// Consultar archivos PDF relacionados
$sqlPdf = "SELECT ruta_pdf FROM archivos_pdf_gratuitos WHERE curso_id = ?";
$stmtPdf = $conn->prepare($sqlPdf);
$stmtPdf->bind_param("i", $cursoId);
$stmtPdf->execute();
$resultPdf = $stmtPdf->get_result();

// Consultar imágenes relacionadas
$sqlImagenes = "SELECT ruta_imagen FROM imagenes_curso_gratuitos WHERE curso_id = ?";
$stmtImagenes = $conn->prepare($sqlImagenes);
$stmtImagenes->bind_param("i", $cursoId);
$stmtImagenes->execute();
$resultImagenes = $stmtImagenes->get_result();

// Consultar videos relacionados
$sqlVideo = "SELECT ruta_video FROM videos_curso_gratuitos WHERE curso_id = ?";
$stmtVideo = $conn->prepare($sqlVideo);
$stmtVideo->bind_param("i", $cursoId);
$stmtVideo->execute();
$resultVideo = $stmtVideo->get_result();
?>

<div class="container mx-auto px-4 mt-10">
    <!-- Título del Curso -->
    <h1 class="text-4xl font-bold text-center text-gray-900 mb-10"><?php echo htmlspecialchars($curso['nombre_curso']); ?></h1>

    <!-- Descripción -->
    <?php if ($resultDescripcion->num_rows > 0): ?>
        <section class="mb-10">
            <h2 class="text-3xl font-semibold text-gray-800 border-b-4 border-purple-600 inline-block mb-4">
                Descripción general
            </h2>
            <?php while ($descripcion = $resultDescripcion->fetch_assoc()): ?>
                <p class="text-lg text-gray-700 leading-relaxed bg-gray-50 p-4 rounded shadow-sm">
                    <?php echo nl2br(htmlspecialchars($descripcion['descripcion'])); ?>
                </p>
            <?php endwhile; ?>
        </section>
    <?php endif; ?>

    <!-- Carrusel de videos -->
    <?php if ($resultVideo->num_rows > 0): ?>
        <section class="mb-12 relative">
            <h2 class="text-2xl font-bold text-gray-800 border-b-4 border-purple-600 inline-block mb-6">
                Videos del Curso
            </h2>
            <div class="flex overflow-x-auto space-x-6 snap-x snap-mandatory pb-4" id="videoCarousel">
                <?php $videoIndex = 1; ?>
                <?php while ($video = $resultVideo->fetch_assoc()): ?>
                    <div class="relative flex-shrink-0 snap-center w-80">
                        <div class="absolute top-2 left-2 bg-purple-600 text-white text-sm px-2 py-1 rounded-md font-semibold z-10">
                            Video <?php echo $videoIndex; ?>
                        </div>
                        <video class="w-full h-48 rounded-lg shadow-md" controls controlsList="nodownload">
                            <source src="./admin/controllers/<?php echo htmlspecialchars($video['ruta_video']); ?>" type="video/mp4">
                            Tu navegador no soporta el video.
                        </video>
                    </div>
                    <?php $videoIndex++; ?>
                <?php endwhile; ?>
            </div>

            <!-- Botones de scroll -->
            <button id="scrollLeftButton" class="absolute top-1/2 left-0 -translate-y-1/2 bg-gray-800 text-white p-3 rounded-full shadow-md hover:bg-gray-700 z-10">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button id="scrollRightButton" class="absolute top-1/2 right-0 -translate-y-1/2 bg-gray-800 text-white p-3 rounded-full shadow-md hover:bg-gray-700 z-10">
                <i class="fas fa-chevron-right"></i>
            </button>
        </section>

        <script>
            const carousel = document.getElementById('videoCarousel');
            document.getElementById('scrollLeftButton').onclick = () => {
                carousel.scrollBy({ left: -carousel.offsetWidth, behavior: 'smooth' });
            };
            document.getElementById('scrollRightButton').onclick = () => {
                carousel.scrollBy({ left: carousel.offsetWidth, behavior: 'smooth' });
            };
        </script>
    <?php endif; ?>

    <!-- Archivos PDF -->
    <?php if ($resultPdf->num_rows > 0): ?>
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 border-b-4 border-purple-600 inline-block mb-4">
                Archivos PDF
            </h2>
            <ul class="space-y-3">
                <?php while ($pdf = $resultPdf->fetch_assoc()): ?>
                    <li class="flex items-center space-x-2 bg-white p-3 rounded shadow-sm">
                        <i class="fas fa-file-pdf text-red-600 text-lg"></i>
                        <a href="./admin/controllers/<?php echo htmlspecialchars($pdf['ruta_pdf']); ?>" 
                           class="text-blue-700 underline hover:text-blue-900 transition" 
                           target="_blank">
                            <?php echo htmlspecialchars(basename($pdf['ruta_pdf'])); ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </section>
    <?php endif; ?>

    <!-- Imágenes -->
    <?php if ($resultImagenes->num_rows > 0): ?>
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 border-b-4 border-purple-600 inline-block mb-6">
                Galería de Imágenes
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <?php while ($imagen = $resultImagenes->fetch_assoc()): ?>
                    <div onclick="openModal('<?php echo './admin/controllers/' . htmlspecialchars($imagen['ruta_imagen']); ?>')"
                         class="cursor-pointer hover:scale-105 transform transition duration-300 shadow-md rounded overflow-hidden bg-white">
                        <img src="./admin/controllers/<?php echo htmlspecialchars($imagen['ruta_imagen']); ?>" 
                             alt="Imagen del curso" 
                             class="w-full h-56 object-cover rounded">
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
    <?php endif; ?>
</div>

<!-- Modal de imagen -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-80 hidden items-center justify-center z-50">
    <span onclick="closeModal()" class="absolute top-6 right-6 text-white text-4xl cursor-pointer">&times;</span>
    <img id="modalImage" class="max-w-3xl max-h-[90vh] object-contain rounded shadow-lg" src="" alt="Imagen del curso">
</div>

<script>
    function openModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('imageModal').classList.remove('hidden');
        document.getElementById('imageModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('imageModal').classList.remove('flex');
        document.getElementById('imageModal').classList.add('hidden');
    }
</script>
