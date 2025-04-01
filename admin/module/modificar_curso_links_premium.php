<?php
include("../connection/database.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $curso_id = intval($_POST['curso_id']);
        
        if ($_POST['action'] === 'delete_link') {
            // Eliminar un enlace existente
            $link_id = intval($_POST['link_id']);
            $sql = "DELETE FROM enlaces_cursos_premium WHERE id = ? AND curso_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $link_id, $curso_id);

            if ($stmt->execute()) {
                echo "<p class='text-green-500 text-center'>El enlace ha sido eliminado exitosamente.</p>";
            } else {
                echo "<p class='text-red-500 text-center'>Error al eliminar el enlace: " . $stmt->error . "</p>";
            }
            $stmt->close();
        } elseif ($_POST['action'] === 'edit_link') {
            // Modificar un enlace existente
            $link_id = intval($_POST['link_id']);
            $updated_link = trim($_POST['updated_link']);
            $updated_date = $_POST['updated_date'];

            if (filter_var($updated_link, FILTER_VALIDATE_URL)) {
                $sql = "UPDATE enlaces_cursos_premium SET url_enlace = ?, fecha = ? WHERE id = ? AND curso_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssii", $updated_link, $updated_date, $link_id, $curso_id);

                if ($stmt->execute()) {
                    echo "<p class='text-green-500 text-center'>El enlace ha sido modificado exitosamente.</p>";
                } else {
                    echo "<p class='text-red-500 text-center'>Error al modificar el enlace: " . $stmt->error . "</p>";
                }
                $stmt->close();
            } else {
                echo "<p class='text-red-500 text-center'>Por favor, ingresa un enlace válido.</p>";
            }
        } elseif ($_POST['action'] === 'add_link') {
            // Agregar un nuevo enlace
            $new_link = trim($_POST['new_link']);
            $new_date = $_POST['new_date'];

            if (filter_var($new_link, FILTER_VALIDATE_URL)) {
                $sql = "INSERT INTO enlaces_cursos_premium (curso_id, url_enlace, fecha) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iss", $curso_id, $new_link, $new_date);

                if ($stmt->execute()) {
                    echo "<p class='text-green-500 text-center'>El enlace ha sido agregado exitosamente.</p>";
                } else {
                    echo "<p class='text-red-500 text-center'>Error al agregar el enlace: " . $stmt->error . "</p>";
                }
                $stmt->close();
            } else {
                echo "<p class='text-red-500 text-center'>Por favor, ingresa un enlace válido.</p>";
            }
        }
    }
} else {
    // Obtener el ID del curso
    $curso_id = intval($_GET['id']);

    // Consultar información del curso y enlaces
    $sql = "SELECT ecp.id, ecp.url_enlace, ecp.fecha
            FROM enlaces_cursos_premium ecp
            WHERE ecp.curso_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $curso_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $enlaces = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $enlaces[] = $row;
        }
    }
}
?>

<div class="max-w-2xl mx-auto mt-10 p-5 bg-white rounded shadow-lg">
    <h2 class="text-2xl font-bold mb-6 text-center text-purple-700">Modificar Enlaces del Curso</h2>

    <!-- Formulario para agregar un nuevo enlace -->
    <form action="" method="post">
        <input type="hidden" name="curso_id" value="<?php echo $curso_id; ?>">
        <input type="hidden" name="action" value="add_link">
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2" for="new_link">Nuevo Enlace</label>
            <input type="url" name="new_link" id="new_link" placeholder="https://ejemplo.com" required class="border border-gray-300 rounded p-2 w-full">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2" for="new_date">Fecha y Hora</label>
            <input type="datetime-local" name="new_date" id="new_date" required class="border border-gray-300 rounded p-2 w-full">
        </div>
        <button type="submit" class="bg-purple-600 text-white font-bold py-2 px-4 rounded hover:bg-purple-700">Agregar Enlace</button>
    </form>

    <!-- Listado de enlaces existentes -->
    <h3 class="text-xl font-semibold mt-8 mb-4">Enlaces Actuales:</h3>
    <div class="space-y-4">
        <?php if (!empty($enlaces)): ?>
            <?php foreach ($enlaces as $enlace): ?>
                <div class="flex items-center justify-between p-4 bg-gray-100 rounded shadow">
                    <div>
                        <a href="<?php echo $enlace['url_enlace']; ?>" target="_blank" class="text-blue-500 hover:text-blue-700"><?php echo $enlace['url_enlace']; ?></a>
                        <p class="text-sm text-gray-600">Fecha y hora: <?php echo date("d/m/Y H:i", strtotime($enlace['fecha'])); ?></p>
                    </div>
                    <form action="" method="post" class="flex items-center space-x-2">
                        <input type="hidden" name="curso_id" value="<?php echo $curso_id; ?>">
                        <input type="hidden" name="link_id" value="<?php echo $enlace['id']; ?>">
                        <input type="hidden" name="action" value="edit_link">
                        <input type="url" name="updated_link" value="<?php echo $enlace['url_enlace']; ?>" class="border border-gray-300 rounded p-2" required>
                        <input type="datetime-local" name="updated_date" value="<?php echo date("Y-m-d\TH:i", strtotime($enlace['fecha'])); ?>" class="border border-gray-300 rounded p-2" required>
                        <button type="submit" class="bg-green-500 text-white font-bold py-1 px-3 rounded hover:bg-green-600">Modificar</button>
                    </form>
                    <form action="" method="post" class="ml-4">
                        <input type="hidden" name="curso_id" value="<?php echo $curso_id; ?>">
                        <input type="hidden" name="link_id" value="<?php echo $enlace['id']; ?>">
                        <input type="hidden" name="action" value="delete_link">
                        <button type="submit" class="text-red-500 hover:text-red-700 font-semibold">Eliminar</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-gray-500">No hay enlaces disponibles para este curso.</p>
        <?php endif; ?>
    </div>
</div>

