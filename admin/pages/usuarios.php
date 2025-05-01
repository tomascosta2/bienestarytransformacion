<?php
// Consulta para obtener todos los usuarios
$query = "SELECT id, nombre, correo, is_active, created_at, es_premium, premium_activated_at, premium_expires_at FROM usuarios";
$result = mysqli_query($conn, $query);

// Verifica si hubo un error en la consulta
if (!$result) {
    die("Error en la consulta: " . mysqli_error($conn));
}
?>
<div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Usuarios Registrados</h1>
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
            </tbody>
        </table>
    </div>
</div>
<?php
// Libera los recursos y cierra la conexión
mysqli_free_result($result);
mysqli_close($conn);
?>
