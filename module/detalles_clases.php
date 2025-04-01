<?php
// Consulta para obtener las clases en vivo
$sql = "SELECT nombre_clase, enlace_clase, fecha_clase FROM clases_en_vivo";
$result = $conn->query($sql);
?>
<div class="flex items-center justify-center min-h-screen bg-gray-50">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-3xl">
        <h2 class="text-2xl font-semibold text-purple-800 mb-6 text-center">Lista de Clases en Vivo</h2>   
        
        <?php if ($result->num_rows > 0): ?>
            <table class="min-w-full bg-white border">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border-b-2 border-purple-200 text-purple-700 text-center">Nombre de la Clase</th>
                        <th class="px-4 py-2 border-b-2 border-purple-200 text-purple-700 text-center">Enlace de la Clase</th>
                        <th class="px-4 py-2 border-b-2 border-purple-200 text-purple-700 text-center">Fecha de la Clase</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="px-4 py-2 border-b border-purple-100 text-gray-800 text-center"><?= htmlspecialchars($row['nombre_clase']) ?></td>
                            <td class="px-4 py-2 border-b border-purple-100 text-blue-500 text-center">
                                <a href="<?= htmlspecialchars($row['enlace_clase']) ?>" target="_blank">
                                    Entrar a la clase!
                                </a>
                            </td>
                            <td class="px-4 py-2 border-b border-purple-100 text-gray-800 text-center"><?= htmlspecialchars($row['fecha_clase']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center text-gray-600">No hay clases en vivo agregadas.</p>
        <?php endif; ?>
        
        <?php $conn->close(); ?>
    </div>
</div>
