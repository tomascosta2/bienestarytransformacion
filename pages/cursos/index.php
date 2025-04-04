<?php
ob_start();
include '../../connection/database.php';
include("../../include/head.php");
include("../../include/navbar.php");

// Obtener categorías desde la base de datos
try {
    $sqlCategorias = "
		SELECT DISTINCT categoria
		FROM (
			SELECT JSON_EXTRACT(categorias, '$') AS categorias_json FROM cursos_gratuitos
			UNION ALL
			SELECT JSON_EXTRACT(categorias, '$') FROM cursos_premium
			UNION ALL
			SELECT JSON_EXTRACT(categorias, '$') FROM cursos_destacados
		) AS todas
		JOIN JSON_TABLE(
			todas.categorias_json,
			'$[*]' COLUMNS (categoria VARCHAR(255) PATH '$')
		) AS categorias_expandidas
		WHERE categoria IS NOT NULL AND categoria != 'null' AND categoria != '';
	";
	$resultCategorias = $conn->query($sqlCategorias);
	$categorias = $resultCategorias->fetch_all(MYSQLI_ASSOC);

} catch (Exception $e) {
    echo $e;
}

// Parámetros de paginación
$porPagina = 9;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $porPagina;

// Obtener la categoría seleccionada
$categoriaSeleccionada = isset($_GET['categoria']) ? $_GET['categoria'] : '';

// Construir la consulta con filtro de categoría si está seleccionado
$sqlCursos = "SELECT *, 'gratis' AS tipo FROM cursos_gratuitos WHERE 1=1 ";
if ($categoriaSeleccionada) {
	$sqlCursos .= "AND JSON_CONTAINS(categorias, '\"$categoriaSeleccionada\"') ";
}
$sqlCursos .= "UNION ALL SELECT *, 'premium' AS tipo FROM cursos_premium WHERE 1=1 ";
if ($categoriaSeleccionada) {
	$sqlCursos .= "AND JSON_CONTAINS(categorias, '\"$categoriaSeleccionada\"') ";
}
$sqlCursos .= "UNION ALL SELECT *, 'destacado' AS tipo FROM cursos_destacados WHERE 1=1 ";
if ($categoriaSeleccionada) {
	$sqlCursos .= "AND JSON_CONTAINS(categorias, '\"$categoriaSeleccionada\"') ";
}
$sqlCursos .= "LIMIT $offset, $porPagina";
$result = $conn->query($sqlCursos);
$cursos = $result->fetch_all(MYSQLI_ASSOC);

// Contar el total de cursos para la paginación
$sqlTotal = "SELECT COUNT(*) AS total FROM (
	SELECT id FROM cursos_gratuitos WHERE 1=1 ";
if ($categoriaSeleccionada) {
	$sqlTotal .= "AND JSON_CONTAINS(categorias, '\"$categoriaSeleccionada\"') ";
}
$sqlTotal .= "UNION ALL SELECT id FROM cursos_premium WHERE 1=1 ";
if ($categoriaSeleccionada) {
	$sqlTotal .= "AND JSON_CONTAINS(categorias, '\"$categoriaSeleccionada\"') ";
}
$sqlTotal .= "UNION ALL SELECT id FROM cursos_destacados WHERE 1=1 ";
if ($categoriaSeleccionada) {
	$sqlTotal .= "AND JSON_CONTAINS(categorias, '\"$categoriaSeleccionada\"') ";
}
$sqlTotal .= ") AS totalCursos";

$resultTotal = $conn->query($sqlTotal);
$totalCursos = $resultTotal->fetch_assoc()['total'];
$totalPaginas = ceil($totalCursos / $porPagina);
?>

<section>
	<div class="max-w-[1280px] mx-auto md:px-0 px-4 py-[60px]">
		<div class="flex gap-8">
			<!-- Sidebar con filtro de categorías -->
			<div class="bg-gray-100 min-w-[300px] h-[calc(100vh-180px)] p-8 sticky top-[40px] rounded-sm">
				<h3 class="text-xl font-semibold mb-4">Filtrar por Categoría</h3>
				<ul>
					<li>
						<a href="/pages/cursos/" class="block py-2 <?php echo $categoriaSeleccionada == '' ? 'font-bold text-purple-700' : ''; ?>">
							Todas las categorías
						</a>
					</li>
					<?php foreach ($categorias as $cat): ?>
						<li>
							<a href="/pages/cursos/?categoria=<?php echo urlencode($cat['categoria']); ?>"
								class="block py-2 <?php echo $categoriaSeleccionada == $cat['categoria'] ? 'font-bold text-purple-700' : ''; ?>">
								<?php echo $cat['categoria']; ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<!-- Contenido principal -->
			<div>
				<div class="grid grid-cols-3 gap-4">
					<?php foreach ($cursos as $curso): ?>
						<a href="./?page=detalles_cursos&id=<?php echo $curso['id']; ?>" class="p-4 rounded-sm border border-gray-300">
							<img src="/admin/controllers/<?php echo $curso['imagen_portada']; ?>" alt="Portada del curso" class="w-full h-32 object-cover mb-4 rounded-sm md:h-48 bg-gray-300">
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
						<a href="/pages/cursos/?pagina=<?php echo $paginaActual - 1; ?>&categoria=<?php echo urlencode($categoriaSeleccionada); ?>" class="px-4 py-2 bg-gray-300 rounded">Anterior</a>
					<?php endif; ?>

					<span class="px-4 py-2 bg-gray-500 text-white rounded">Página <?php echo $paginaActual; ?> de <?php echo $totalPaginas; ?></span>

					<?php if ($paginaActual < $totalPaginas): ?>
						<a href="/pages/cursos/?pagina=<?php echo $paginaActual + 1; ?>&categoria=<?php echo urlencode($categoriaSeleccionada); ?>" class="px-4 py-2 bg-gray-300 rounded">Siguiente</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php include("../../include/footer.php"); ?>