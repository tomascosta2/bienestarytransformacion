<?php
// Iniciar sesión
session_start();

// Obtener el ID del curso desde la URL
$cursoId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Conexión a la base de datos
include("./connection/database.php");

// Consulta para obtener los detalles del curso
$sqlCurso = "SELECT * FROM cursos_destacados WHERE id = ?";
$stmtCurso = $conn->prepare($sqlCurso);
$stmtCurso->bind_param("i", $cursoId);
$stmtCurso->execute();
$resultCurso = $stmtCurso->get_result();

if ($resultCurso->num_rows > 0) {
    $curso = $resultCurso->fetch_assoc();
} else {
    echo "<div class='max-w-lg mx-auto mt-10 p-4 bg-red-100 text-red-700 rounded-lg shadow-md text-center'>
            <h2 class='text-xl font-semibold'>Curso no encontrado.</h2>
          </div>";
    exit();
}

// Consultar descripción del curso
$sqlDescripcion = "SELECT descripcion FROM cursos_destacados WHERE id = ?";
$stmtDescripcion = $conn->prepare($sqlDescripcion);
$stmtDescripcion->bind_param("i", $cursoId);
$stmtDescripcion->execute();
$resultDescripcion = $stmtDescripcion->get_result();
?>

<div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-lg">
    <h1 class="text-4xl font-bold text-center text-gray-900 mb-6"><?php echo htmlspecialchars($curso['nombre_curso']); ?></h1>

    <div class="overflow-hidden rounded-lg shadow-md">
        <img src="./admin/controllers/<?php echo $curso['imagen_portada']; ?>" alt="Portada del curso" class="w-full h-60 object-cover hover:opacity-90 transition duration-300">
    </div>

    <h2 class="text-2xl font-semibold text-gray-800 border-b-2 border-gray-300 pb-2 mt-6">
        Información del curso
    </h2>

    <?php while ($descripcion = $resultDescripcion->fetch_assoc()): ?>
        <p class="text-gray-700 text-lg leading-relaxed mt-4">
            <?php echo htmlspecialchars($descripcion['descripcion']); ?>
        </p>
    <?php endwhile; ?>

<!-- Botón de Pago con Validación de Sesión -->
<div class="mt-8 flex justify-center items-center gap-4">
    <span class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg font-bold text-lg">
        <?php echo number_format($curso['precio'], 2); ?> ARS
    </span>

    <?php if (isset($_SESSION['id'])): ?>
        <form action="procesar_pago.php" method="GET">
            <input type="hidden" name="id" value="<?php echo $curso['id']; ?>">
            <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded-lg shadow-md hover:bg-blue-600 transition duration-200 text-lg font-semibold">
                Comprar con MercadoPago
            </button>
        </form>
    <?php else: ?>
        <a href="./?page=ingresar" class="bg-blue-500 text-white px-6 py-3 rounded-lg shadow-md hover:bg-blue-600 transition duration-200 text-lg font-semibold">
            Iniciar sesión para comprar
        </a>
    <?php endif; ?>
</div>

</div>
