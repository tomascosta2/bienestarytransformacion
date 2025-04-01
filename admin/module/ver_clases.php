<?php
// Consulta para obtener las clases en vivo
$query = "SELECT id, nombre_clase, enlace_clase, fecha_clase FROM clases_en_vivo";
$result = mysqli_query($conn, $query);

// Verifica si hubo un error en la consulta
if (!$result) {
    die("Error en la consulta: " . mysqli_error($conn));
}
?>
<div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Clases en Vivo</h1>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-800 text-white text-left">
                    <th class="py-3 px-6">Nombre de la Clase</th>
                    <th class="py-3 px-6">Enlace</th>
                    <th class="py-3 px-6">Fecha</th>
                    <th class="py-3 px-6 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr class="hover:bg-gray-100 border-b border-gray-200">
                        <td class="py-3 px-6 text-gray-700"><?= htmlspecialchars($row['nombre_clase']) ?></td>
                        <td class="py-3 px-6 text-blue-500">
                            <a href="<?= htmlspecialchars($row['enlace_clase']) ?>" target="_blank" class="underline hover:text-blue-700">
                                Ver Clase
                            </a>
                        </td>
                        <td class="py-3 px-6 text-gray-700"><?= htmlspecialchars($row['fecha_clase']) ?></td>
                        <td class="py-3 px-6 text-center flex justify-center space-x-4">
                            <!-- Botón para Editar -->
                            <a href="./?page=editar_clase&id=<?= $row['id'] ?>" 
                               class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700 transition duration-300">
                                Editar
                            </a>
                            <!-- Botón para Eliminar -->
                            <a href="./?page=eliminar_clase&id=<?= $row['id'] ?>" 
                               class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700 transition duration-300"
                               onclick="return confirm('¿Estás seguro de que deseas eliminar esta clase?');">
                                Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
// Libera los recursos y cierra la conexión
mysqli_free_result($result);
mysqli_close($conn);
?>
