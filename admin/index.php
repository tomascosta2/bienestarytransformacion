<?php

session_name("admin_session");
session_start();
include './connection/database.php';

// Verificar sesión y rol de administrador
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'administrador') {
    header("Location:ingresar");
    exit();
}


include("./include/head.php");


try { 
    $conn->query("SET GLOBAL sql_mode = ''");
} catch (Exception $e) {
    echo '<div class="bg-warning fixed bottom-[20px] rigth-[20px] p-4 rounded-sm">' . $e . '</div>';
}

// Consulta para obtener el total de usuarios registrados
$totalUsuariosQuery = "SELECT COUNT(*) as total FROM usuarios";
$totalUsuariosResult = $conn->query($totalUsuariosQuery);
$totalUsuarios = $totalUsuariosResult->fetch_assoc()['total'];

// Consulta para obtener el total de visitas
$totalVisitasQuery = "SELECT SUM(cantidad) as total_visitas FROM visitas";
$totalVisitasResult = $conn->query($totalVisitasQuery);
$totalVisitas = $totalVisitasResult->fetch_assoc()['total_visitas'];

try {
    // Consulta para obtener el número de usuarios registrados por mes (último año)
    $usuariosPorMesQuery = "
        SELECT 
            DATE_FORMAT(MIN(created_at), '%b') AS mes, 
            COUNT(*) AS cantidad 
        FROM usuarios 
        WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)
        GROUP BY YEAR(created_at), MONTH(created_at)
        ORDER BY YEAR(created_at), MONTH(created_at);
    ";
    $usuariosPorMesResult = $conn->query($usuariosPorMesQuery);
} catch (Exception $e) {
    echo 'Error en la consulta: ' . $e->getMessage();
    exit();
}

// Preparar datos para el JSON de usuarios nuevos
$meses = [];
$cantidades = [];
while ($row = $usuariosPorMesResult->fetch_assoc()) {
    $meses[] = $row['mes'];
    $cantidades[] = $row['cantidad'];
}

try {
    // Consulta para obtener el número de visitas por mes (último año)
    $visitasPorMesQuery = "
        SELECT 
            DATE_FORMAT(fecha, '%b') AS mes, 
            SUM(cantidad) AS total_visitas,
            MIN(fecha) AS fecha_orden 
        FROM visitas 
        WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)
        GROUP BY YEAR(fecha), MONTH(fecha) 
        ORDER BY fecha_orden;
    ";
    $visitasPorMesResult = $conn->query($visitasPorMesQuery);
} catch (Exception $e) {
    echo 'Error en la consulta: ' . $e->getMessage();
    exit();
}

// Preparar datos para el JSON de visitas mensuales
$mesesVisitas = [];
$cantidadesVisitas = [];
while ($row = $visitasPorMesResult->fetch_assoc()) {
    $mesesVisitas[] = $row['mes'];
    $cantidadesVisitas[] = $row['total_visitas'];
}

?>

<div class="flex h-screen">
    <!-- Botón de menú hamburguesa para móviles -->
    <div class="bg-purple-200 text-white flex-shrink-0 p-4 lg:hidden">
        <button id="menu-toggle" class="focus:outline-none text-gray-700">
            <i class="fas fa-bars text-2xl"></i>
        </button>
    </div>

    <!-- Menú lateral izquierdo -->
    <aside id="sidebar" class="w-64 bg-gradient-to-br from-purple-300 via-pink-200 to-purple-300 text-gray-900 flex-shrink-0 hidden lg:flex lg:flex-col shadow-xl overflow-hidden">
        <div class="p-6 bg-purple-500 flex flex-col items-center justify-center">
            <h1 class="text-2xl font-bold text-white">Admin Panel</h1>
            <p class="text-sm text-purple-200">Luz Mística</p>
        </div>
        <nav class="mt-6 flex-grow overflow-y-auto scrollbar-thin scrollbar-thumb-purple-500 scrollbar-track-purple-200">
            <ul class="space-y-2">
                <li>
                    <a href="./?page=home" class="flex items-center p-3 text-gray-700 hover:bg-purple-400 hover:text-white transition duration-300 rounded-md mx-3 my-1">
                        <i class="fas fa-home mr-2 text-lg"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="./?page=visitas" class="flex items-center p-3 text-gray-700 hover:bg-purple-400 hover:text-white transition duration-300 rounded-md mx-3 my-1">
                        <i class="fas fa-eye mr-2 text-lg"></i> Visitas
                    </a>
                </li>
                <li class="relative">
                    <button type="button" class="flex justify-between items-center w-full p-3 text-gray-700 hover:bg-purple-400 hover:text-white transition duration-300 rounded-md mx-3 my-1 dropdown-button">
                        <span class="flex items-center"><i class="fas fa-chalkboard-teacher mr-2 text-lg"></i> Cursos Gratuitos</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="mt-1 rounded-md shadow-lg bg-purple-100 hidden dropdown-menu z-20">
                        <a href="./?page=gratuito" class="block px-4 py-2 text-gray-700 hover:bg-purple-200 rounded-md">Subir Charla/Cursos</a>
                        <a href="./?page=ver_gratuito" class="block px-4 py-2 text-gray-700 hover:bg-purple-200 rounded-md">Ver Charlas/Cursos</a>
                    </div>
                </li>
                <li class="relative">
                    <button type="button" class="flex justify-between items-center w-full p-3 text-gray-700 hover:bg-purple-400 hover:text-white transition duration-300 rounded-md mx-3 my-1 dropdown-button">
                        <span class="flex items-center"><i class="fas fa-star mr-2 text-lg"></i> Cursos Destacados</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="mt-1 rounded-md shadow-lg bg-purple-100 hidden dropdown-menu z-20">
                        <a href="./?page=destacado" class="block px-4 py-2 text-gray-700 hover:bg-purple-200 rounded-md">Subir Charla/Cursos</a>
                        <a href="./?page=ver_destacado" class="block px-4 py-2 text-gray-700 hover:bg-purple-200 rounded-md">Ver Charlas/Cursos</a>
                    </div>
                </li>
                <li class="relative">
                    <button type="button" class="flex justify-between items-center w-full p-3 text-gray-700 hover:bg-purple-400 hover:text-white transition duration-300 rounded-md mx-3 my-1 dropdown-button">
                        <span class="flex items-center"><i class="fas fa-crown mr-2 text-lg"></i> Plan PREMIUM</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="mt-1 rounded-md shadow-lg bg-purple-100 hidden dropdown-menu z-20">
                        <a href="./?page=premium" class="block px-4 py-2 text-gray-700 hover:bg-purple-200 rounded-md">Subir Charla/Cursos</a>
                        <a href="./?page=ver_premium" class="block px-4 py-2 text-gray-700 hover:bg-purple-200 rounded-md">Ver Charlas/Cursos</a>
                    </div>
                </li>
                <li class="relative">
                    <button type="button" class="flex justify-between items-center w-full p-3 text-gray-700 hover:bg-purple-400 hover:text-white transition duration-300 rounded-md mx-3 my-1 dropdown-button">
                        <span class="flex items-center"><i class="fas fa-chalkboard-teacher mr-2 text-lg"></i>Clases en Vivo</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="mt-1 rounded-md shadow-lg bg-purple-100 hidden dropdown-menu z-20">
                        <a href="./?page=clase" class="flex items-center p-3 text-gray-700 hover:bg-purple-400 hover:text-white transition duration-300 rounded-md mx-3 my-1">Subir Clase En vivo</a>
                        <a href="./?page=ver_clase" class="block px-4 py-2 text-gray-700 hover:bg-purple-200 rounded-md">Ver Clases en vivo</a>
                    </div>
                </li>
                <li>
                    <a href="./?page=usuarios" class="flex items-center p-3 text-gray-700 hover:bg-purple-400 hover:text-white transition duration-300 rounded-md mx-3 my-1">
                        <i class="fas fa-users mr-2 text-lg"></i> Usuarios
                    </a>
                </li>
                <li>
                    <a href="./?page=salir" class="flex items-center p-3 text-gray-700 hover:bg-red-400 hover:text-white transition duration-300 rounded-md mx-3 my-1">
                        <i class="fas fa-sign-out-alt mr-2 text-lg"></i> Salir
                    </a>
                </li>
            </ul>
        </nav>
    </aside>



    <!-- Contenido principal -->
    <main class="flex-1 p-8 bg-gray-100 overflow-y-auto">
        <?php
        // Incluir el contenido correspondiente según el valor de 'page'
        switch ($page) {
            case 'home':
                include './module/home.php';
                break;
            case 'visitas':
                include './module/visitas.php';
                break;
            case 'gratuito':
                include './module/gratuito.php';
                break;
            case 'ver_gratuito':
                include './module/ver_gratuito.php';
                break;
            case 'modificar_curso_gratuito':
                include './module/modificar_curso_gratuito.php';
                break;
            case 'modificar_curso_titulo':
                include './module/modificar_curso_titulo.php';
                break;
            case 'modificar_curso_descripcion':
                include './module/modificar_curso_descripcion.php';
                break;
            case 'modificar_curso_portada':
                include './module/modificar_curso_portada.php';
                break;
            case 'modificar_curso_video':
                include './module/modificar_curso_video.php';
                break;
            case 'modificar_curso_pdf':
                include './module/modificar_curso_pdf.php';
                break;
            case 'modificar_curso_imagenes':
                include './module/modificar_curso_imagenes.php';
                break;
            case 'ver_videos':
                include './module/ver_videos.php';
                break;
            case 'agregar_video':
                include './module/agregar_video.php';
                break;
            case 'destacado':
                include './module/destacado.php';
                break;
            case 'ver_destacado':
                include './module/ver_destacado.php';
                break;
            case 'ver_videos_destacados':
                include './module/ver_videos_destacados.php';
                break;
            case 'agregar_video_destacados':
                include './module/agregar_video_destacados.php';
                break;
            case 'modificar_curso_titulo_destacado':
                include './module/modificar_curso_titulo_destacado.php';
                break;
            case 'modificar_curso_descripcion_destacado';
                include './module/modificar_curso_descripcion_destacado.php';
                break;
            case 'modificar_curso_portada_destacado':
                include './module/modificar_curso_portada_destacado.php';
                break;
            case 'modificar_curso_pdf_destacado':
                include './module/modificar_curso_pdf_destacado.php';
                break;
            case 'modificar_curso_imagenes_destacado':
                include './module/modificar_curso_imagenes_destacado.php';
                break;
            case 'premium':
                include './module/premium.php';
                break;
            case 'ver_premium':
                include './module/ver_premium.php';
                break;
            case 'modificar_curso_titulo_premium':
                include './module/modificar_curso_titulo_premium.php';
                break;
            case 'modificar_curso_descripcion_premium':
                include './module/modificar_curso_descripcion_premium.php';
                break;
            case 'modificar_curso_portada_premium':
                include './module/modificar_curso_portada_premium.php';
                break;
            case 'modificar_curso_pdf_premium':
                include './module/modificar_curso_pdf_premium.php';
                break;
            case 'modificar_curso_imagenes_premium':
                include './module/modificar_curso_imagenes_premium.php';
                break;
            case 'videos_curso_premium':
                include './module/videos_curso_premium.php';
                break;
            case 'agregar_video_premium':
                include './module/agregar_video_premium.php';
                break;
            case 'clase':
                include './module/clase.php';
                break;
            case 'ver_clase':
                include './module/ver_clases.php';
                break;
            case 'editar_clase':
                include './module/editar_clase.php';
                break;
            case 'modificar_curso_links_premium':
                include './module/modificar_curso_links_premium.php';
                break;
            case 'eliminar_clase':
                include './module/eliminar_clase.php';
                break;
            case 'usuarios':
                include './module/usuarios.php';
                break;
            case 'salir':
                include './module/salir.php';
                break;
            default:
                include './module/404.php'; // Página 404 si no se encuentra
                break;
        }
        ?>
    </main>
</div>
<?php include("./include/footer.php"); ?>