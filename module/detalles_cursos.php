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
<!-- Swiper CSS -->
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
/>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<div class="mx-auto max-w-[1040px] px-4 py-[80px]">
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

    <?php if ($resultVideo->num_rows > 0): ?>
    <section class="mb-12">
        <h2 class="text-2xl font-bold text-gray-800 border-b-4 border-purple-600 inline-block mb-6">
            Videos del Curso
        </h2>

        <!-- Swiper -->
        <div class="swiper mySwiper w-full rounded-lg">
            <div class="swiper-wrapper">
                <?php $videoIndex = 1; ?>
                <?php while ($video = $resultVideo->fetch_assoc()): ?>
                    <div class="swiper-slide">
                        <div class="relative w-full">
                            <!-- Etiqueta de video -->
                            <div class="absolute top-3 left-3 bg-purple-600 text-white text-sm px-3 py-1 rounded-md z-10">
                                Video <?php echo $videoIndex; ?>
                            </div>

                            <!-- Video -->
                            <video class="w-full aspect-video object-cover rounded-lg" controls controlsList="nodownload">
                                <source src="./admin/controllers/<?php echo htmlspecialchars($video['ruta_video']); ?>" type="video/mp4">
                                Tu navegador no soporta el video.
                            </video>
                        </div>
                    </div>
                    <?php $videoIndex++; ?>
                <?php endwhile; ?>
            </div>

            <!-- Botones de navegación -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>

            <!-- Paginación opcional -->
            <div class="swiper-pagination"></div>
        </div>

        <!-- Swiper Init -->
        <script>
            const swiper = new Swiper(".mySwiper", {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: false,
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
            });
        </script>
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

    <!-- El resto (PDFs e Imágenes) sigue igual... -->
</div>

