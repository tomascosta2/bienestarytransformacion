<?php

session_name("admin_session");
session_start();
include '../../../connection/database.php';

// Verificar sesión y rol de administrador
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'administrador') {
    header("Location:ingresar");
    exit();
}


include("../../../include/head.php");


try { 
    $conn->query("SET GLOBAL sql_mode = ''");
} catch (Exception $e) {
    echo '<div class="hidden --warning">' . $e . '</div>';
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
            <p class="text-sm text-purple-200">Escuela Bienestar Integral</p>
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
                        <a href="./?page=clase" class="block px-4 py-2 text-gray-700 hover:bg-purple-200 rounded-md">Subir Clase En vivo</a>
                        <a href="./?page=ver_clase" class="block px-4 py-2 text-gray-700 hover:bg-purple-200 rounded-md">Ver Clases en vivo</a>
                    </div>
                </li>
                <li>
                    <a href="/admin/pages/usuarios/index.php" class="flex items-center p-3 text-gray-700 hover:bg-purple-400 hover:text-white transition duration-300 rounded-md mx-3 my-1">
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
    <div class="flex-1 flex flex-col overflow-hidden">
        <main class="flex-1 overflow-y-auto p-4">
            <?php
            // Consulta para obtener todos los usuarios
            // Inicializar las variables de búsqueda
            $search = '';
            $expiry_date = '';
            if (isset($_GET['search'])) {
                $search = mysqli_real_escape_string($conn, $_GET['search']);
            }
            if (isset($_GET['expiry_date'])) {
                $expiry_date = mysqli_real_escape_string($conn, $_GET['expiry_date']);
            }

            // Modificar la consulta para incluir la búsqueda por nombre
            $query = "SELECT id, nombre, correo, is_active, created_at, es_premium, premium_activated_at, premium_expires_at FROM usuarios";
            
            // Agregar condiciones de búsqueda
            $whereConditions = [];
            
            if (!empty($search)) {
                $whereConditions[] = "nombre LIKE '%$search%'";
            }
            
            if (!empty($expiry_date)) {
                $whereConditions[] = "DATE(premium_expires_at) = '$expiry_date'";
            }
            
            // Añadir las condiciones WHERE a la consulta
            if (!empty($whereConditions)) {
                $query .= " WHERE " . implode(" AND ", $whereConditions);
            }
            
            $result = mysqli_query($conn, $query);

            // Verifica si hubo un error en la consulta
            if (!$result) {
                die("Error en la consulta: " . mysqli_error($conn));
            }
            ?>
            <div class="container mx-auto mt-10 p-6 bg-white rounded-lg">
                <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Usuarios Registrados</h1>
                
                <!-- Formulario de búsqueda -->
                <div class="mb-6 max-w-[600px] ms-auto">
                    <form action="" method="GET" class="flex flex-wrap items-center gap-2">
                        <div class="relative flex-1 min-w-[200px]">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Buscar por nombre..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                        </div>
                        
                        <div class="relative flex-1 min-w-[200px]">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar text-gray-400"></i>
                            </div>
                            <input type="date" name="expiry_date" value="<?= htmlspecialchars($expiry_date) ?>" placeholder="Fecha de vencimiento" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                        </div>
                        
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            Buscar
                        </button>
                        
                        <?php if (!empty($search) || !empty($expiry_date)): ?>
                            <a href="<?= strtok($_SERVER["REQUEST_URI"], '?') ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                Limpiar
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border-collapse border border-gray-200">
                        <thead>
                            <tr class="bg-gray-800 text-white text-left">
                                <th class="py-3 px-4">Nombre</th>
                                <th class="py-3 px-4">Correo</th>
                                <th class="py-3 px-4">Activo</th>
                                <th class="py-3 px-4">Creado en</th>
                                <th class="py-3 px-4">Premium</th>
                                <th class="py-3 px-4">Premium Activado</th>
                                <th class="py-3 px-4">Premium Expira</th>
                                <th class="py-3 px-4 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr class="hover:bg-gray-100 border-b border-gray-200">
                                        <td class="py-3 px-4 text-gray-700"><?= htmlspecialchars($row['nombre']) ?></td>
                                        <td class="py-3 px-4 text-gray-700"><?= htmlspecialchars($row['correo']) ?></td>
                                        <td class="py-3 px-4 text-center">
                                            <?php if ($row['is_active']): ?>
                                                <i class="fas fa-check-circle text-green-500" title="Activo"></i>
                                            <?php else: ?>
                                                <i class="fas fa-times-circle text-red-500" title="Inactivo"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3 px-4 text-gray-700"><?= htmlspecialchars($row['created_at']) ?></td>
                                        <td class="py-3 px-4 text-center">
                                            <?php if ($row['es_premium']): ?>
                                                <i class="fas fa-star text-yellow-500" title="Premium"></i>
                                            <?php else: ?>
                                                <i class="fas fa-star text-gray-400" title="No Premium"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3 px-4 text-gray-700"><?= htmlspecialchars($row['premium_activated_at'] ?? '-') ?></td>
                                        <td class="py-3 px-4 text-gray-700"><?= htmlspecialchars($row['premium_expires_at'] ?? '-') ?></td>
                                        <td class="py-3 px-4 text-center">
                                            <div class="flex justify-center">
                                                <!-- Botón para enviar email -->
                                                <a href="/admin/controllers/send_email.php?id=<?= $row['id'] ?>" class="text-blue-500 hover:text-blue-700 mx-2" title="Enviar email">
                                                    <i class="fas fa-envelope"></i>
                                                </a>
                                                <!-- Botón para activar Premium -->
                                                <a href="/admin/controllers/activate_premium.php?id=<?= $row['id'] ?>" class="text-green-500 hover:text-green-700 mx-2" title="Activar Premium">
                                                    <i class="fas fa-crown"></i>
                                                </a>
                                                <!-- Botón para eliminar usuario -->
                                                <a href="/admin/controllers/delete_user.php?id=<?= $row['id'] ?>" class="text-red-500 hover:text-red-700 mx-2" title="Eliminar usuario" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="py-6 px-4 text-center text-gray-500">No se encontraron usuarios con ese nombre</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<?php
// Libera los recursos y cierra la conexión
mysqli_free_result($result);
mysqli_close($conn);
?>

<script>
    // Script para manejar los menús desplegables
    document.addEventListener('DOMContentLoaded', function() {
        // Manejo del menú hamburguesa para móviles
        const menuToggle = document.getElementById('menu-toggle');
        const sidebar = document.getElementById('sidebar');
        
        if (menuToggle) {
            menuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('hidden');
            });
        }
        
        // Manejo de los menús desplegables
        const dropdownButtons = document.querySelectorAll('.dropdown-button');
        
        dropdownButtons.forEach(button => {
            button.addEventListener('click', function() {
                const dropdownMenu = this.nextElementSibling;
                dropdownMenu.classList.toggle('hidden');
            });
        });
    });
</script>

<?php include("../../../include/footer.php"); ?>
