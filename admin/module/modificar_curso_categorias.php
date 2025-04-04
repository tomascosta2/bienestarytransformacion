<?php
// Obtener todas las categorías disponibles
$sqlCategorias = "SELECT * FROM categorias";
$resultCategorias = $conn->query($sqlCategorias);
$categoriasDisponibles = $resultCategorias->fetch_all(MYSQLI_ASSOC);

// Validar ID y tipo
if (!isset($_GET['id']) || !isset($_GET['tipo'])) {
    echo "ID o tipo de curso no especificado.";
    exit;
}

$cursoId = (int) $_GET['id'];
$tipoCurso = $_GET['tipo']; // puede ser 'gratis', 'premium' o 'destacado'

// Determinar tabla
$tabla = "";
switch ($tipoCurso) {
    case "gratuito":
        $tabla = "cursos_gratuitos";
        break;
    case "premium":
        $tabla = "cursos_premium";
        break;
    case "destacado":
        $tabla = "cursos_destacados";
        break;
    default:
        echo "Tipo de curso inválido.";
        exit;
}

// Obtener categorías actuales del curso
$sql = "SELECT categorias FROM $tabla WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cursoId);
$stmt->execute();
$result = $stmt->get_result();
$curso = $result->fetch_assoc();

if (!$curso) {
    echo "Curso no encontrado.";
    exit;
}

$categoriasActuales = json_decode($curso['categorias'], true) ?? [];

// Procesar actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nuevasCategorias = $_POST['categorias'] ?? [];
    $categoriasJson = json_encode($nuevasCategorias);

    $sqlUpdate = "UPDATE $tabla SET categorias = ? WHERE id = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("si", $categoriasJson, $cursoId);

    if ($stmtUpdate->execute()) {
        echo "<p class='text-green-500 text-center'>Categorías actualizadas con éxito.</p>";
        $categoriasActuales = $nuevasCategorias;
    } else {
        echo "<p class='text-red-500 text-center'>Error al actualizar las categorías.</p>";
    }
}
?>

<form method="POST" class="max-w-md mx-auto p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-semibold mb-4 text-gray-700 text-center">Modificar Categorías del Curso</h2>

    <div class="mb-4">
        <label for="categorias" class="block text-gray-700 font-bold mb-1">Categorías</label>
        <select name="categorias[]" id="categorias" class="w-full p-2 border border-gray-300 rounded-md" multiple>
            <?php foreach ($categoriasDisponibles as $categoria): ?>
                <option value="<?php echo $categoria['nombre']; ?>"
                    <?php echo in_array($categoria['nombre'], $categoriasActuales) ? 'selected' : ''; ?>>
                    <?php echo $categoria['nombre']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="text-center">
        <button type="submit" class="w-full py-2 px-4 bg-purple-600 text-white font-semibold rounded-md hover:bg-purple-700 transition duration-200">
            Actualizar Categorías
        </button>
    </div>
</form>

<!-- TomSelect -->
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
<script>
    new TomSelect("#categorias", {
        plugins: ['remove_button'],
        persist: false,
        create: false,
        placeholder: "Selecciona una o más categorías"
    });
</script>
