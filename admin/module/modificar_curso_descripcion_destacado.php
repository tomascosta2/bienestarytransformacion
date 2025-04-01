<?php
// Obtener el ID del curso de la URL
$curso_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Consultar la descripción actual del curso
$sql = "SELECT nombre_curso, descripcion FROM cursos_destacados WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $curso_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $curso = $result->fetch_assoc();
} else {
    echo "<p class='text-red-500 text-center'>Curso no encontrado.</p>";
    exit;
}

// Procesar la actualización de la descripción si se envía el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nueva_descripcion = $_POST['descripcion'];

    $sql_update = "UPDATE cursos_destacados SET descripcion = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("si", $nueva_descripcion, $curso_id);

    if ($stmt_update->execute()) {
        echo "<p class='text-green-500 text-center'>Descripción actualizada con éxito.</p>";
    } else {
        echo "<p class='text-red-500 text-center'>Error al actualizar la descripción.</p>";
    }
}

$stmt->close();
$conn->close();
?>

<div class="max-w-2xl mx-auto mt-10 p-8 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-purple-700 mb-6 text-center">Modificar Descripción del Curso</h2>
    <form method="POST" class="space-y-6">
        <div>
            <label for="nombre_curso" class="block text-sm font-medium text-gray-700 mb-2">Título del Curso:</label>
            <input type="text" id="nombre_curso" name="nombre_curso" value="<?php echo htmlspecialchars($curso['nombre_curso']); ?>" disabled
                class="w-full px-4 py-2 border rounded-lg bg-gray-100 cursor-not-allowed" />
        </div>

        <div>
            <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">Nueva Descripción:</label>
            <textarea id="descripcion" name="descripcion" rows="5" required
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent"><?php echo htmlspecialchars($curso['descripcion']); ?></textarea>
        </div>

        <div class="text-center">
            <button type="submit" class="bg-purple-600 text-white font-semibold py-2 px-6 rounded-lg hover:bg-purple-700 transition duration-200">
                Actualizar Descripción
            </button>
        </div>
    </form>
</div>
