<?php
ob_start();
include '../../connection/database.php';
include("../../include/head.php");
include("../../include/navbar.php");

$cursoId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$moduloActivo = isset($_GET['modulo']) ? intval($_GET['modulo']) : 1;
$vista = isset($_GET['view']) ? $_GET['view'] : 'general';

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

// Obtener todos los videos
$sqlVideos = "SELECT id, ruta_video FROM videos_curso_premium WHERE curso_id = ? ORDER BY id ASC";
$stmtVideos = $conn->prepare($sqlVideos);
$stmtVideos->bind_param("i", $cursoId);
$stmtVideos->execute();
$resultVideos = $stmtVideos->get_result();
$videos = [];
while ($video = $resultVideos->fetch_assoc()) {
    $videos[] = $video;
}

// Obtener todos los PDFs
$sqlPdfs = "SELECT id, ruta_pdf FROM archivos_pdf_premium WHERE curso_id = ? ORDER BY id ASC";
$stmtPdfs = $conn->prepare($sqlPdfs);
$stmtPdfs->bind_param("i", $cursoId);
$stmtPdfs->execute();
$resultPdfs = $stmtPdfs->get_result();
$pdfs = [];
while ($pdf = $resultPdfs->fetch_assoc()) {
    $pdfs[] = $pdf;
}

// Obtener imágenes
$sqlImagenes = "SELECT ruta_imagen FROM imagenes_curso_premium WHERE curso_id = ?";
$stmtImagenes = $conn->prepare($sqlImagenes);
$stmtImagenes->bind_param("i", $cursoId);
$stmtImagenes->execute();
$resultImagenes = $stmtImagenes->get_result();

// Obtener enlaces
$sqlEnlaces = "SELECT url_enlace AS enlace, fecha FROM enlaces_cursos_premium WHERE curso_id = ?";
$stmtEnlaces = $conn->prepare($sqlEnlaces);
$stmtEnlaces->bind_param("i", $cursoId);
$stmtEnlaces->execute();
$resultEnlaces = $stmtEnlaces->get_result();

// Obtener descripción
$sqlDescripcion = "SELECT descripcion FROM cursos_premium WHERE id = ?";
$stmtDescripcion = $conn->prepare($sqlDescripcion);
$stmtDescripcion->bind_param("i", $cursoId);
$stmtDescripcion->execute();
$resultDescripcion = $stmtDescripcion->get_result();

// Determinar el número total de módulos (basado en el número de videos)
$totalModulos = count($videos);

// Determinar si el menú de módulos debe estar expandido
$modulosExpandidos = isset($_GET['modulo']);
?>

<div class="flex flex-col md:flex-row mx-auto max-w-[1200px] min-h-screen">
    <!-- Sidebar -->
    <div class="w-full md:w-64 bg-gray-100 p-4 border-r border-gray-300">
        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b-2 border-purple-600 pb-2">
            <?php echo htmlspecialchars($curso['nombre_curso']); ?>
        </h2>
        
        <nav>
            <ul class="space-y-2">
                <li>
                    <a href="./?id=<?php echo $cursoId; ?>" class="flex items-center p-2 rounded-md hover:bg-purple-100 <?php echo ($vista == 'general' && !isset($_GET['modulo'])) ? 'bg-purple-200 text-purple-800 font-medium' : ''; ?>">
                        <i class="fas fa-info-circle mr-2"></i>
                        <span>Información general</span>
                    </a>
                </li>

                <!-- Material de estudio -->
                <li>
                    <a href="./?id=<?php echo $cursoId; ?>&view=material" class="flex items-center p-2 rounded-md hover:bg-purple-100 <?php echo ($vista == 'material') ? 'bg-purple-200 text-purple-800 font-medium' : ''; ?>">
                        <i class="fas fa-book mr-2"></i>
                        <span>Material de estudio</span>
                    </a>
                </li>
                
                <!-- Módulos desplegables -->
                <li>
                    <div class="menu-item">
                        <button id="modulos-toggle" class="flex items-center justify-between w-full p-2 rounded-md hover:bg-purple-100 <?php echo isset($_GET['modulo']) ? 'bg-purple-200 text-purple-800 font-medium' : ''; ?>">
                            <div class="flex items-center">
                                <i class="fas fa-play-circle mr-2"></i>
                                <span>Módulos</span>
                            </div>
                            <i class="fas <?php echo $modulosExpandidos ? 'fa-chevron-down' : 'fa-chevron-right'; ?>"></i>
                        </button>
                        
                        <ul id="modulos-submenu" class="pl-4 mt-1 space-y-1 <?php echo $modulosExpandidos ? '' : 'hidden'; ?>">
                            <?php for ($i = 0; $i < $totalModulos; $i++): ?>
                            <li>
                                <a href="./?id=<?php echo $cursoId; ?>&modulo=<?php echo ($i + 1); ?>" 
                                   class="flex items-center p-2 rounded-md hover:bg-purple-100 <?php echo (isset($_GET['modulo']) && $_GET['modulo'] == ($i + 1)) ? 'bg-purple-300 text-purple-800 font-medium' : ''; ?>">
                                    <i class="fas fa-circle text-xs mr-2"></i>
                                    <span>Módulo <?php echo ($i + 1); ?></span>
                                </a>
                            </li>
                            <?php endfor; ?>
                        </ul>
                    </div>
                </li>
                
                <?php if ($resultImagenes->num_rows > 0): ?>
                <li>
                    <a href="./?id=<?php echo $cursoId; ?>&view=imagenes" class="flex items-center p-2 rounded-md hover:bg-purple-100 <?php echo ($vista == 'imagenes') ? 'bg-purple-200 text-purple-800 font-medium' : ''; ?>">
                        <i class="fas fa-images mr-2"></i>
                        <span>Imágenes</span>
                    </a>
                </li>
                <?php endif; ?>
                
                <?php if ($resultEnlaces->num_rows > 0): ?>
                <li>
                    <a href="./?id=<?php echo $cursoId; ?>&view=enlaces" class="flex items-center p-2 rounded-md hover:bg-purple-100 <?php echo ($vista == 'enlaces') ? 'bg-purple-200 text-purple-800 font-medium' : ''; ?>">
                        <i class="fas fa-link mr-2"></i>
                        <span>Clases en vivo</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <!-- Contenido principal -->
    <div class="flex-1 p-6 max-w-[750px] mx-auto">
        <?php if ($vista == 'material'): ?>
            <!-- Vista de Material de Estudio -->
            <section id="material" class="mb-12">
                <h1 class="text-3xl font-bold text-gray-900 mb-6 border-b-4 border-purple-600 pb-2">
                    Material de estudio
                </h1>
                
                <?php if (count($pdfs) > 0): ?>
                    <div class="bg-white shadow-md rounded-lg p-6 border border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Documentos del curso</h2>
                        
                        <div class="space-y-4">
                            <?php foreach ($pdfs as $index => $pdf): ?>
                                <div class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                                    <div class="bg-red-100 p-3 rounded-full mr-4">
                                        <i class="fas fa-file-pdf text-red-600 text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-medium text-gray-800">Material del Módulo <?php echo ($index + 1); ?></h3>
                                        <p class="text-sm text-gray-500">PDF - <?php echo htmlspecialchars(basename($pdf['ruta_pdf'])); ?></p>
                                    </div>
                                    <div>
                                        <a href="/admin/controllers/<?php echo htmlspecialchars($pdf['ruta_pdf']); ?>" 
                                           class="inline-flex items-center px-3 py-1 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition duration-200"
                                           target="_blank">
                                            <i class="fas fa-download mr-2"></i> Descargar
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    No hay materiales de estudio disponibles para este curso.
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>                
            </section>
        <?php elseif ($vista == 'imagenes'): ?>
            <!-- Vista de Imágenes -->
            <section id="imagenes" class="mb-12">
                <h1 class="text-3xl font-bold text-gray-900 mb-6 border-b-4 border-purple-600 pb-2">
                    Imágenes
                </h1>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php 
                    // Reiniciar el puntero del resultado
                    $resultImagenes->data_seek(0);
                    while ($imagen = $resultImagenes->fetch_assoc()): 
                    ?>
                        <div class="bg-white shadow-lg rounded-lg overflow-hidden transition transform hover:scale-105 duration-300 cursor-pointer" 
                             onclick="openModal('<?php echo './admin/controllers/' . htmlspecialchars($imagen['ruta_imagen']); ?>')">
                            <img src="./admin/controllers/<?php echo htmlspecialchars($imagen['ruta_imagen']); ?>" 
                                 alt="Imagen del curso" 
                                 class="w-full h-48 object-cover">
                        </div>
                    <?php endwhile; ?>
                </div>
            </section>
        <?php elseif ($vista == 'enlaces'): ?>
            <!-- Vista de Enlaces -->
            <section id="enlaces" class="mb-12">
                <h1 class="text-3xl font-bold text-gray-900 mb-6 border-b-4 border-purple-600 pb-2">
                    Clases en vivo
                </h1>
                <div class="bg-gray-100 border border-gray-300 rounded-lg p-4">
                    <?php 
                    // Reiniciar el puntero del resultado
                    $resultEnlaces->data_seek(0);
                    while ($enlace = $resultEnlaces->fetch_assoc()): 
                    ?>
                        <div class="mb-4 p-3 bg-white rounded-md shadow-sm">
                            <a href="<?php echo htmlspecialchars($enlace['enlace']); ?>" 
                               class="text-blue-600 hover:text-blue-800 underline text-lg" 
                               target="_blank">
                                <i class="fas fa-video mr-2"></i>
                                <?php echo htmlspecialchars($enlace['enlace']); ?>
                            </a>
                            <p class="text-sm text-gray-600 mt-2">
                                <i class="far fa-calendar-alt mr-1"></i>
                                Fecha y hora: <?php echo htmlspecialchars(date("d/m/Y H:i", strtotime($enlace['fecha']))); ?>
                            </p>
                        </div>
                    <?php endwhile; ?>
                </div>
            </section>
        <?php elseif (isset($_GET['modulo'])): ?>
            <!-- Módulo específico -->
            <?php 
            $indiceModulo = $moduloActivo - 1;
            $videoActual = isset($videos[$indiceModulo]) ? $videos[$indiceModulo] : null;
            $pdfActual = isset($pdfs[$indiceModulo]) ? $pdfs[$indiceModulo] : null;
            ?>
            
            <section class="mb-12">
                <h1 class="text-3xl font-bold text-gray-900 mb-6 border-b-4 border-purple-600 pb-2">
                    Módulo <?php echo $moduloActivo; ?>
                </h1>
                
                <!-- Video del módulo -->
                <?php if ($videoActual): ?>
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">
                        Video del módulo
                    </h2>
                    <div class="relative w-full">
                        <div class="absolute top-3 left-3 bg-purple-600 text-white text-sm px-3 py-1 rounded-md z-10">
                            Módulo <?php echo $moduloActivo; ?>
                        </div>
                        <video class="w-full aspect-video object-cover rounded-lg shadow-lg" controls controlsList="nodownload">
                            <source src="/admin/controllers/<?php echo htmlspecialchars($videoActual['ruta_video']); ?>" type="video/mp4">
                            Tu navegador no soporta el video.
                        </video>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- PDF del módulo -->
                <?php if ($pdfActual): ?>
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">
                        Material de estudio
                    </h2>
                    <div class="bg-white shadow-md rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center">
                            <i class="fas fa-file-pdf text-red-600 text-2xl mr-3"></i>
                            <a href="/admin/controllers/<?php echo htmlspecialchars($pdfActual['ruta_pdf']); ?>" 
                               class="text-blue-600 underline hover:text-blue-800 transition duration-200 text-lg" 
                               target="_blank">
                                <?php echo htmlspecialchars(basename($pdfActual['ruta_pdf'])); ?>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Navegación entre módulos -->
                <div class="flex justify-between mt-8">
                    <?php if ($moduloActivo > 1): ?>
                    <a href="?id=<?php echo $cursoId; ?>&modulo=<?php echo ($moduloActivo - 1); ?>" 
                       class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md transition duration-200">
                        <i class="fas fa-arrow-left mr-2"></i> Módulo Anterior
                    </a>
                    <?php else: ?>
                    <div></div>
                    <?php endif; ?>
                    
                    <?php if ($moduloActivo < $totalModulos): ?>
                    <a href="?id=<?php echo $cursoId; ?>&modulo=<?php echo ($moduloActivo + 1); ?>" 
                       class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md transition duration-200">
                        Siguiente Módulo <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </section>
        <?php else: ?>
            <!-- Descripción General -->
            <section id="general" class="mb-12">
                <h1 class="text-3xl font-bold text-gray-900 mb-6 border-b-4 border-purple-600 pb-2">
                    Información general
                </h1>
                
                <?php if ($resultDescripcion->num_rows > 0): ?>
                    <?php while ($descripcion = $resultDescripcion->fetch_assoc()): ?>
                        <p class="text-gray-800 text-base leading-relaxed mb-4">
                            <?php echo $descripcion['descripcion']; ?>
                        </p>
                    <?php endwhile; ?>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </div>
</div>

<!-- Modal imagen -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden flex items-center justify-center z-50">
    <span class="absolute top-5 right-5 text-white cursor-pointer text-3xl" onclick="closeModal()">&times;</span>
    <img id="modalImage" class="max-w-full max-h-full object-contain" src="/placeholder.svg" alt="Imagen a pantalla completa">
</div>

<script>
    function openModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('imageModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }
    
    // Manejo del menú desplegable de módulos
    document.addEventListener('DOMContentLoaded', function() {
        const modulosToggle = document.getElementById('modulos-toggle');
        const modulosSubmenu = document.getElementById('modulos-submenu');
        const chevronIcon = modulosToggle.querySelector('.fas');
        
        modulosToggle.addEventListener('click', function() {
            modulosSubmenu.classList.toggle('hidden');
            
            // Cambiar el ícono
            if (chevronIcon.classList.contains('fa-chevron-right')) {
                chevronIcon.classList.remove('fa-chevron-right');
                chevronIcon.classList.add('fa-chevron-down');
            } else {
                chevronIcon.classList.remove('fa-chevron-down');
                chevronIcon.classList.add('fa-chevron-right');
            }
        });
        
        // Scroll to sections when clicking on sidebar links
        const sidebarLinks = document.querySelectorAll('nav a[href^="#"]');
        
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 20,
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
</script>

<?php include("../../include/footer.php"); ?>