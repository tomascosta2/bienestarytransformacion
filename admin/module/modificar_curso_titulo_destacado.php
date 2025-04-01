<?php

if (isset($_GET['id'])) {
    $cursoId = $_GET['id'];
    $sql = "SELECT nombre_curso FROM cursos_destacados WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cursoId);
    $stmt->execute();
    $result = $stmt->get_result();
    $curso = $result->fetch_assoc();
    
    if (!$curso) {
        echo "Curso no encontrado.";
        exit;
    }
} else {
    echo "ID de curso no especificado.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nuevoTitulo = $_POST['nombre_curso'];
    
    $sqlUpdate = "UPDATE cursos_destacados SET nombre_curso = ? WHERE id = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("si", $nuevoTitulo, $cursoId);
    
    if ($stmtUpdate->execute()) {  // Aquí se corrigió el nombre de la variable
        echo "<p class='text-green-500 text-center'>Descripción actualizada con éxito.</p>";
    } else {
        echo "<p class='text-red-500 text-center'>Error al actualizar la descripción.</p>";
    }
}
?>

<form method="POST" class="max-w-md mx-auto p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-semibold mb-4 text-gray-700 text-center">Modificar Título del Curso</h2>
    
    <div class="mb-4">
        <label for="nombre_curso" class="block text-sm font-medium text-gray-600">Nuevo Título del Curso:</label>
        <input type="text" name="nombre_curso" value="<?php echo htmlspecialchars($curso['nombre_curso']); ?>" required
               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"/>
    </div>

    <div class="text-center">
        <button type="submit" class="w-full py-2 px-4 bg-purple-600 text-white font-semibold rounded-md hover:bg-purple-700 transition duration-200">
            Actualizar Título
        </button>
    </div>
</form>
