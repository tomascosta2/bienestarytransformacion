<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'delete_pdf') {
        // Obtener los datos del PDF a eliminar
        $pdf_ruta = $_POST['pdf_ruta'];
        $curso_id = intval($_POST['curso_id']);

        // Eliminar el archivo del sistema de archivos
        $file_path = "../admin/controllers/" . $pdf_ruta;
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // Eliminar el registro de la base de datos
        $sql = "DELETE FROM archivos_pdf_gratuitos WHERE curso_id = ? AND ruta_pdf = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $curso_id, $pdf_ruta);

        if ($stmt->execute()) {
            echo "<p class='text-green-500 text-center'>El archivo PDF ha sido eliminado exitosamente.</p>";
        } else {
            echo "<p class='text-red-500 text-center'>Error al eliminar el archivo PDF: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        // Obtener el ID del curso
        $curso_id = intval($_POST['curso_id']);
        $target_dir = "../admin/controllers/uploads/"; // Directorio de destino para PDF
        $uploadOk = 1;
        $pdfFileType = strtolower(pathinfo($_FILES["pdf_curso"]["name"], PATHINFO_EXTENSION));

        // Permitir solo archivos PDF
        if ($pdfFileType != 'pdf') {
            echo "<p class='text-red-500 text-center'>Lo siento, solo se permiten archivos PDF.</p>";
            $uploadOk = 0;
        }

        // Limitar el tamaño del archivo (ejemplo: 10MB)
        if ($_FILES["pdf_curso"]["size"] > 10000000) {
            echo "<p class='text-red-500 text-center'>Lo siento, el archivo es demasiado grande.</p>";
            $uploadOk = 0;
        }

        // Verificar si se debe proceder con la carga del archivo
        if ($uploadOk === 1) {
            $target_file = $target_dir . basename($_FILES["pdf_curso"]["name"]);
            if (move_uploaded_file($_FILES["pdf_curso"]["tmp_name"], $target_file)) {
                // Insertar la nueva ruta del PDF en la base de datos
                $sql = "INSERT INTO archivos_pdf_gratuitos (curso_id, ruta_pdf) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $pdf_ruta = "uploads/" . basename($_FILES["pdf_curso"]["name"]); // Ruta a guardar en la base de datos
                $stmt->bind_param("is", $curso_id, $pdf_ruta);

                if ($stmt->execute()) {
                    echo "<p class='text-green-500 text-center'>El archivo PDF ha sido agregado exitosamente.</p>";
                } else {
                    echo "<p class='text-red-500 text-center'>Error al agregar el archivo PDF: " . $stmt->error . "</p>";
                }
            } else {
                echo "<p class='text-red-500 text-center'>Lo siento, hubo un error al cargar su archivo.</p>";
            }
        }

        $stmt->close();
    }
    $conn->close();
} else {
    // Obtener el ID del curso a modificar
    $curso_id = intval($_GET['id']);

    // Consultar información del curso
    $sql = "SELECT nombre_curso, descripcion, imagen_portada, 
                   GROUP_CONCAT(DISTINCT ruta_pdf SEPARATOR ',') AS pdfs
            FROM cursos_gratuitos cg
            LEFT JOIN archivos_pdf_gratuitos apg ON cg.id = apg.curso_id
            WHERE cg.id = ?";
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
}
?>
<div class="max-w-2xl mx-auto mt-10 p-5 bg-white rounded shadow-lg">
    <h2 class="text-2xl font-bold mb-6 text-center text-purple-700">Modificar Archivos PDF del Curso</h2>

    <!-- Formulario para agregar un nuevo PDF -->
    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="curso_id" value="<?php echo $curso_id; ?>">
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2" for="pdf_curso">Nuevo Archivo PDF</label>
            <input type="file" name="pdf_curso" id="pdf_curso" accept=".pdf" required class="border border-gray-300 rounded p-2 w-full">
        </div>
        <button type="submit" class="bg-purple-600 text-white font-bold py-2 px-4 rounded hover:bg-purple-700">Agregar PDF</button>
    </form>

    <!-- Listado de PDFs existentes -->
    <h3 class="text-xl font-semibold mt-8 mb-4">Archivos PDF Actuales:</h3>
    <div class="space-y-4">
        <?php if (!empty($curso['pdfs'])):
            $pdfs = explode(',', $curso['pdfs']);
            foreach ($pdfs as $pdf): ?>
                <div class="flex items-center justify-between p-4 bg-gray-100 rounded shadow">
                    <a href="../admin/controllers/<?php echo $pdf; ?>" target="_blank" class="text-blue-500 hover:text-blue-700"><?php echo basename($pdf); ?></a>
                    <form action="" method="POST" class="ml-4">
                        <input type="hidden" name="curso_id" value="<?php echo $curso_id; ?>">
                        <input type="hidden" name="pdf_ruta" value="<?php echo $pdf; ?>">
                        <input type="hidden" name="action" value="delete_pdf">
                        <button type="submit" class="text-red-500 hover:text-red-700 font-semibold">Eliminar</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-gray-500">No hay archivos PDF disponibles para este curso.</p>
        <?php endif; ?>
    </div>
</div>
