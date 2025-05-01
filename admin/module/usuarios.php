<?php
// Inicializar variables de búsqueda
$buscar_nombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$buscar_correo = isset($_GET['correo']) ? $_GET['correo'] : '';
$buscar_telefono = isset($_GET['telefono']) ? $_GET['telefono'] : '';

// Construir la consulta base
$query = "SELECT id, nombre, correo, telefono, is_active, created_at, es_premium, premium_activated_at, premium_expires_at FROM usuarios WHERE 1=1";

// Agregar condiciones de búsqueda si se proporcionaron
if (!empty($buscar_nombre)) {
    $buscar_nombre = mysqli_real_escape_string($conn, $buscar_nombre);
    $query .= " AND nombre LIKE '%$buscar_nombre%'";
}

if (!empty($buscar_correo)) {
    $buscar_correo = mysqli_real_escape_string($conn, $buscar_correo);
    $query .= " AND correo LIKE '%$buscar_correo%'";
}

if (!empty($buscar_telefono)) {
    $buscar_telefono = mysqli_real_escape_string($conn, $buscar_telefono);
    $query .= " AND telefono LIKE '%$buscar_telefono%'";
}

// Ejecutar la consulta
$result = mysqli_query($conn, $query);

// Verifica si hubo un error en la consulta
if (!$result) {
    die("Error en la consulta: " . mysqli_error($conn));
}
?>
<div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Usuarios Registrados</h1>
    
    <!-- Formulario de búsqueda -->
    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
        <h2 class="text-lg font-semibold text-gray-700 mb-3">Buscar Usuarios</h2>
        <form method="GET" action="" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($buscar_nombre) ?>" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="correo" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                <input type="text" id="correo" name="correo" value="<?= htmlspecialchars($buscar_correo) ?>" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($buscar_telefono) ?>" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="md:col-span-3 flex justify-end">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-search mr-2"></i> Buscar
                </button>
                <?php if (!empty($buscar_nombre) || !empty($buscar_correo) || !empty($buscar_telefono)): ?>
                    <a href="<?= $_SERVER['PHP_SELF'] ?>" class="ml-2 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-times mr-2"></i> Limpiar
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
    
    <!-- Resultados de la búsqueda -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-800 text-white text-left">
                    <th class="py-3 px-6">Nombre</th>
                    <th class="py-3 px-6">Correo</th>
                    <th class="py-3 px-6">Activo</th>
                    <th class="py-3 px-6">Creado en</th>
                    <th class="py-3 px-6">Premium</th>
                    <th class="py-3 px-6">Premium Activado</th>
                    <th class="py-3 px-6">Premium Expira</th>
                    <th class="py-3 px-6 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr class="hover:bg-gray-100 border-b border-gray-200">
                            <td class="py-3 px-6 text-gray-700"><?= htmlspecialchars($row['nombre']) ?></td>
                            <td class="py-3 px-6 text-gray-700"><?= htmlspecialchars($row['correo']) ?></td>
                            <td class="py-3 px-6 text-center">
                                <?php if ($row['is_active']): ?>
                                    <i class="fas fa-check-circle text-green-500" title="Activo"></i>
                                <?php else: ?>
                                    <i class="fas fa-times-circle text-red-500" title="Inactivo"></i>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-6 text-gray-700"><?= htmlspecialchars($row['created_at']) ?></td>
                            <td class="py-3 px-6 text-center">
                                <?php if ($row['es_premium']): ?>
                                    <i class="fas fa-star text-yellow-500" title="Premium"></i>
                                <?php else: ?>
                                    <i class="fas fa-star text-gray-400" title="No Premium"></i>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-6 text-gray-700"><?= htmlspecialchars($row['premium_activated_at']) ?></td>
                            <td class="py-3 px-6 text-gray-700"><?= htmlspecialchars($row['premium_expires_at']) ?></td>
                            <td class="py-3 px-6 text-center flex">
                                <!-- Botón para enviar email -->
                                <a href="./controllers/send_email.php?id=<?= $row['id'] ?>" class="text-blue-500 hover:text-blue-700 mx-2" title="Enviar email">
                                    <i class="fas fa-envelope"></i>
                                </a>
                                <!-- Botón para activar Premium -->
                                <a href="./controllers/activate_premium.php?id=<?= $row['id'] ?>" class="text-green-500 hover:text-green-700 mx-2" title="Activar Premium">
                                    <i class="fas fa-crown"></i>
                                </a>
                                <!-- Botón para eliminar usuario -->
                                <a href="./controllers/delete_user.php?id=<?= $row['id'] ?>" class="text-red-500 hover:text-red-700 mx-2" title="Eliminar usuario" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="py-4 px-6 text-center text-gray-500">No se encontraron usuarios con los criterios de búsqueda.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
// Libera los recursos y cierra la conexión
mysqli_free_result($result);
mysqli_close($conn);
?>