<?php
require '../connection/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtén los datos del formulario
    $id = $_POST['id'];
    $nombre_clase = $_POST['nombre_clase'];
    $enlace_clase = $_POST['enlace_clase'];
    $fecha_clase = $_POST['fecha_clase'];

    // Actualiza los datos en la base de datos
    $query = "UPDATE clases_en_vivo SET nombre_clase = ?, enlace_clase = ?, fecha_clase = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $nombre_clase, $enlace_clase, $fecha_clase, $id);

    if ($stmt->execute()) {
        echo "Clase actualizada exitosamente.";
        exit();
    } else {
        echo "Error al actualizar la clase: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // Obtén los datos de la clase que se va a editar
    $id = $_GET['id'];
    $query = "SELECT * FROM clases_en_vivo WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $clase = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
}
?>

    <div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Editar Clase en Vivo</h1>
        <form method="POST" action="" class="space-y-4">
            <input type="hidden" name="id" value="<?= htmlspecialchars($clase['id']) ?>">
            
            <div>
                <label for="nombre_clase" class="block text-gray-700 font-bold">Nombre de la Clase:</label>
                <input type="text" id="nombre_clase" name="nombre_clase" value="<?= htmlspecialchars($clase['nombre_clase']) ?>" 
                       class="w-full mt-2 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <div>
                <label for="enlace_clase" class="block text-gray-700 font-bold">Enlace de la Clase:</label>
                <input type="text" id="enlace_clase" name="enlace_clase" value="<?= htmlspecialchars($clase['enlace_clase']) ?>" 
                       class="w-full mt-2 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <div>
                <label for="fecha_clase" class="block text-gray-700 font-bold">Fecha de la Clase:</label>
                <input type="date" id="fecha_clase" name="fecha_clase" value="<?= htmlspecialchars($clase['fecha_clase']) ?>" 
                       class="w-full mt-2 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <div class="flex justify-center">
                <button type="submit" class="px-6 py-2 bg-purple-500 text-white rounded-md hover:bg-purple-700 transition duration-300">Guardar Cambios</button>
                <a href="index.php" class="ml-4 px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-700 transition duration-300">Cancelar</a>
            </div>
        </form>
    </div>