<?php
$cursoId = isset($_GET['id']) ? intval($_GET['id']) : 0;

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

$sqlPdf = "SELECT ruta_pdf FROM archivos_pdf_premium WHERE curso_id = ?";
$stmtPdf = $conn->prepare($sqlPdf);
$stmtPdf->bind_param("i", $cursoId);
$stmtPdf->execute();
$resultPdf = $stmtPdf->get_result();

$sqlImagenes = "SELECT ruta_imagen FROM imagenes_curso_premium WHERE curso_id = ?";
$stmtImagenes = $conn->prepare($sqlImagenes);
$stmtImagenes->bind_param("i", $cursoId);
$stmtImagenes->execute();
$resultImagenes = $stmtImagenes->get_result();

$sqlVideo = "SELECT ruta_video FROM videos_curso_premium WHERE curso_id = ?";
$stmtVideo = $conn->prepare($sqlVideo);
$stmtVideo->bind_param("i", $cursoId);
$stmtVideo->execute();
$resultVideo = $stmtVideo->get_result();

$sqlEnlaces = "SELECT url_enlace AS enlace, fecha FROM enlaces_cursos_premium WHERE curso_id = ?";
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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<div class="mx-auto max-w-[1040px] px-4 py-[80px]">

    <h1 class="text-4xl font-bold text-center text-gray-900 mb-6">
        <?php echo htmlspecialchars($curso['nombre_curso']); ?>
    </h1>

    <!-- Descripción -->
    <?php if ($resultDescripcion->num_rows > 0): ?>
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 border-b-4 border-purple-600 inline-block mb-6">
                General
            </h2>
            <?php while ($descripcion = $resultDescripcion->fetch_assoc()): ?>
                <p class="text-gray-800 text-base leading-relaxed">
                    <?php echo $descripcion['descripcion']; ?>
                </p>
            <?php endwhile; ?>
        </section>
    <?php endif; ?>

    <!-- Videos -->
    <?php if ($resultVideo->num_rows > 0): ?>
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 border-b-4 border-purple-600 inline-block mb-6">
                Videos del Curso
            </h2>

            <div class="swiper mySwiper w-full rounded-lg">
                <div class="swiper-wrapper">
                    <?php $videoIndex = 1; ?>
                    <?php while ($video = $resultVideo->fetch_assoc()): ?>
                        <div class="swiper-slide">
                            <div class="relative w-full">
                                <div class="absolute top-3 left-3 bg-purple-600 text-white text-sm px-3 py-1 rounded-md z-10">
                                    Video <?php echo $videoIndex; ?>
                                </div>
                                <video class="w-full aspect-video object-cover rounded-lg" controls controlsList="nodownload">
                                    <source src="./admin/controllers/<?php echo htmlspecialchars($video['ruta_video']); ?>" type="video/mp4">
                                    Tu navegador no soporta el video.
                                </video>
                            </div>
                        </div>
                        <?php $videoIndex++; ?>
                    <?php endwhile; ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination mt-4"></div>
            </div>
        </section>
    <?php endif; ?>

    <!-- PDFs -->
    <?php if ($resultPdf->num_rows > 0): ?>
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 border-b-4 border-purple-600 inline-block mb-6">
                Archivos PDF
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
        </section>
    <?php endif; ?>

    <!-- Imágenes -->
    <?php if ($resultImagenes->num_rows > 0): ?>
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 border-b-4 border-purple-600 inline-block mb-6">
                Imágenes
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php while ($imagen = $resultImagenes->fetch_assoc()): ?>
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden transition transform hover:scale-105 duration-300 cursor-pointer" onclick="openModal('<?php echo './admin/controllers/' . htmlspecialchars($imagen['ruta_imagen']); ?>')">
                        <img src="./admin/controllers/<?php echo htmlspecialchars($imagen['ruta_imagen']); ?>" alt="Imagen del curso" class="w-full h-100 object-cover rounded-lg">
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- Enlaces -->
    <?php if ($resultEnlaces->num_rows > 0): ?>
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 border-b-4 border-purple-600 inline-block mb-6">
                Clases en vivo
            </h2>
            <div class="bg-gray-100 border border-gray-300 rounded-lg p-4">
                <?php while ($enlace = $resultEnlaces->fetch_assoc()): ?>
                    <div class="mb-4">
                        <a href="<?php echo htmlspecialchars($enlace['enlace']); ?>" class="text-blue-600 hover:text-blue-800 underline" target="_blank">
                            <?php echo htmlspecialchars($enlace['enlace']); ?>
                        </a>
                        <p class="text-sm text-gray-600 mt-2">
                            Fecha y hora: <?php echo htmlspecialchars(date("d/m/Y H:i", strtotime($enlace['fecha']))); ?>
                        </p>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
    <?php endif; ?>
</div>

<!-- Modal imagen -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden flex items-center justify-center z-50">
    <span class="absolute top-5 right-5 text-white cursor-pointer text-3xl" onclick="closeModal()">&times;</span>
    <img id="modalImage" class="max-w-full max-h-full object-contain" src="" alt="Imagen a pantalla completa">
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
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

    function openModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('imageModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }
</script>
