<?php
// Get the course ID from the URL
$curso_id = isset($_GET['curso_id']) ? $_GET['curso_id'] : 0;

// Query to fetch all videos for this course
$sql = "SELECT vcg.id, vcg.ruta_video FROM videos_curso_premium vcg WHERE vcg.curso_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $curso_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="max-w-7xl mx-auto mt-10 p-5">
    <h2 class="text-3xl font-bold mb-6 text-center text-purple-700">Videos del Curso</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 shadow-lg rounded-lg overflow-hidden">
            <thead class="bg-purple-600 text-white">
                <tr>
                    <th class="py-3 px-4 border">Video</th>
                    <th class="py-3 px-4 border">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-gray-100">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="hover:bg-gray-200 transition duration-200">
                            <td class="py-3 px-4 border">
                                <!-- Display video as a thumbnail -->
                                <video width="150" height="100" controls>
                                    <source src="../admin/controllers/<?php echo htmlspecialchars($row['ruta_video']); ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </td>
                            <td class="py-3 px-4 border">
                                <!-- Delete button for the video -->
                                <form action="./controllers/delete_video_premium.php" method="POST" class="inline-block">
                                    <input type="hidden" name="video_id" value="<?php echo $row['id']; ?>"> <!-- ID del video -->
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-semibold">X</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" class="py-3 px-4 text-center text-gray-600">No hay videos disponibles para este curso.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Button to add new videos -->
        <div class="mt-6 text-center">
            <a href="./?page=agregar_video_premium&curso_id=<?php echo $curso_id; ?>" class="bg-purple-600 text-white py-2 px-6 rounded hover:bg-purple-700">Agregar Videos</a>
        </div>
    </div>
</div>