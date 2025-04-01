<?php
// Inicia sesión y verifica si el usuario está autenticado
// Verifica si el usuario está autenticado
if (!isset($_SESSION['id'])) {
    // Redirige al usuario a la página de inicio de sesión si no está autenticado
    header("Location: https://espaciobienestarintegral.com/?page=ingresar");
    exit();
}
// ID del usuario autenticado
$id = $_SESSION['id'];

// Consulta para obtener los cursos comprados por el usuario
$sql = "
    SELECT 
        c.id, 
        c.nombre_curso, 
        c.descripcion, 
        c.imagen_portada
    FROM 
        usuarios_cursos uc
    JOIN 
        cursos_destacados c ON uc.curso_id = c.id
    WHERE 
        uc.usuario_id = ? AND uc.estado_pago = 1
    ORDER BY 
        uc.fecha_pago DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultCursos = $stmt->get_result();

// Cerrar conexión
$conn->close();
?>
    <div class="relative py-12">
        <h1 class="text-3xl font-bold text-center mb-8" style="color: #c09ecc;">Mis Cursos Comprados</h1>
        <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 px-4 relative z-10">
            <?php if ($resultCursos->num_rows > 0): ?>
                <?php while ($curso = $resultCursos->fetch_assoc()): ?>
                    <a href="./?page=detalles_mi_curso_destacado&id=<?php echo $curso['id']; ?>" class="bg-white shadow-lg rounded-lg p-6 text-center transition-transform transform hover:scale-105 shadow-lg hover:shadow-2xl">
                        <img src="./admin/controllers/<?php echo $curso['imagen_portada']; ?>" alt="Portada del curso" class="w-full h-32 object-cover mb-4 rounded-lg md:h-48">
                        <h4 class="text-xl font-semibold mb-2" style="color: #c09ecc;"><?php echo $curso['nombre_curso']; ?></h4>
                        <p class="text-gray-700 mb-4">
                            <?php echo substr($curso['descripcion'], 0, 100) . '...'; ?>
                        </p>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-gray-700 text-center">No hay cursos disponibles en este momento.</p>
            <?php endif; ?>
        </div>
    </div>

