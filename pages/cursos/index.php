<?php
ob_start();
include '../../connection/database.php';
include("../../include/head.php");
include("../../include/navbar.php");

// Definir la cantidad de cursos por página
$porPagina = 9;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $porPagina;

// Consulta con paginación
$sqlCursos = "
    SELECT *, 'gratis' AS tipo FROM cursos_gratuitos 
    UNION ALL 
    SELECT *, 'premium' AS tipo FROM cursos_premium
    LIMIT $offset, $porPagina
";

$result = $conn->query($sqlCursos);
$cursos = $result->fetch_all(MYSQLI_ASSOC);

// Obtener el total de cursos para calcular el número de páginas
$sqlTotal = "
    SELECT COUNT(*) AS total FROM (
        SELECT id FROM cursos_gratuitos
        UNION ALL
        SELECT id FROM cursos_premium
    ) AS totalCursos
";

$resultTotal = $conn->query($sqlTotal);
$totalCursos = $resultTotal->fetch_assoc()['total'];
$totalPaginas = ceil($totalCursos / $porPagina);
?>

<section>
	<div class="max-w-[1280px] mx-auto md:px-0 px-4 py-[60px]">
		<div class="flex gap-8">
			<div class="bg-gray-100 min-w-[300px] h-[calc(100vh-180px)] p-8 sticky top-[40px] rounded-sm">

			</div>
			<div>
				<div class="grid grid-cols-3 gap-4">
					<?php foreach ($cursos as $curso): ?>
						<a href="./?page=detalles_cursos&id=<?php echo $curso['id']; ?>" class="p-4 rounded-sm border border-gray-300">
							<img src="./admin/controllers/<?php echo $curso['imagen_portada']; ?>" alt="Portada del curso" class="w-full h-32 object-cover mb-4 rounded-sm md:h-48 bg-gray-300">
							<h4 class="text-xl font-semibold mb-2" style="color: #c09ecc;"><?php echo $curso['nombre_curso']; ?></h4>
							<p class="text-gray-700 mb-4">
								<?php echo substr($curso['descripcion'], 0, 100) . '...'; ?>
							</p>
						</a>
					<?php endforeach; ?>
				</div>

				<!-- Paginación -->
				<div class="mt-6 flex justify-center space-x-4">
					<?php if ($paginaActual > 1): ?>
						<a href="./?pagina=<?php echo $paginaActual - 1; ?>" class="px-4 py-2 bg-gray-300 rounded">Anterior</a>
					<?php endif; ?>

					<span class="px-4 py-2 bg-gray-500 text-white rounded">Página <?php echo $paginaActual; ?> de <?php echo $totalPaginas; ?></span>

					<?php if ($paginaActual < $totalPaginas): ?>
						<a href="/pages/cursos/?pagina=<?php echo $paginaActual + 1; ?>" class="px-4 py-2 bg-gray-300 rounded">Siguiente</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php include("../../include/footer.php"); ?>