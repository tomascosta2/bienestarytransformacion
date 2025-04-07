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

// Obtener el término de búsqueda
$busqueda = isset($_GET['busqueda']) ? $conn->real_escape_string($_GET['busqueda']) : '';

// Construir la consulta con filtro de categoría y búsqueda si están seleccionados
$sqlCursos = "SELECT *, 'gratis' AS tipo FROM cursos_gratuitos WHERE 1=1 ";
if ($categoriaSeleccionada) {
	$sqlCursos .= "AND JSON_CONTAINS(categorias, '\"$categoriaSeleccionada\"') ";
}
if ($busqueda) {
	$sqlCursos .= "AND nombre_curso LIKE '%$busqueda%' ";
}
$sqlCursos .= "UNION ALL SELECT *, 'premium' AS tipo FROM cursos_premium WHERE 1=1 ";
if ($categoriaSeleccionada) {
	$sqlCursos .= "AND JSON_CONTAINS(categorias, '\"$categoriaSeleccionada\"') ";
}
if ($busqueda) {
	$sqlCursos .= "AND nombre_curso LIKE '%$busqueda%' ";
}
$sqlCursos .= "UNION ALL SELECT *, 'destacado' AS tipo FROM cursos_destacados WHERE 1=1 ";
if ($categoriaSeleccionada) {
	$sqlCursos .= "AND JSON_CONTAINS(categorias, '\"$categoriaSeleccionada\"') ";
}
if ($busqueda) {
	$sqlCursos .= "AND nombre_curso LIKE '%$busqueda%' ";
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
if ($busqueda) {
	$sqlTotal .= "AND nombre_curso LIKE '%$busqueda%' ";
}
$sqlTotal .= "UNION ALL SELECT id FROM cursos_premium WHERE 1=1 ";
if ($categoriaSeleccionada) {
	$sqlTotal .= "AND JSON_CONTAINS(categorias, '\"$categoriaSeleccionada\"') ";
}
if ($busqueda) {
	$sqlTotal .= "AND nombre_curso LIKE '%$busqueda%' ";
}
$sqlTotal .= "UNION ALL SELECT id FROM cursos_destacados WHERE 1=1 ";
if ($categoriaSeleccionada) {
	$sqlTotal .= "AND JSON_CONTAINS(categorias, '\"$categoriaSeleccionada\"') ";
}
if ($busqueda) {
	$sqlTotal .= "AND nombre_curso LIKE '%$busqueda%' ";
}
$sqlTotal .= ") AS totalCursos";

$resultTotal = $conn->query($sqlTotal);
$totalCursos = $resultTotal->fetch_assoc()['total'];
$totalPaginas = ceil($totalCursos / $porPagina);
// echo '<pre>';
// print_r($cursos);
// echo '</pre>';

// print_r($_SESSION);
$usuario_es_premium = $_SESSION['es_premium'];
?>

<section>
	<div class="max-w-[1280px] mx-auto md:px-0 px-4 py-[60px]">
		<div class="flex flex-col md:flex-row gap-8">
			<!-- Sidebar con filtro de categorías -->
			<div class="bg-gray-100 min-w-[300px] md:h-[calc(100vh-180px)] p-8 md:sticky md:top-[40px] rounded-sm">				
				<h3 class="text-xl font-semibold mb-4">Filtrar por Categoría</h3>
				<ul>
					<li>
						<a href="<?php echo '/pages/cursos/' . ($busqueda ? '?busqueda=' . urlencode($busqueda) : ''); ?>" class="block py-2 <?php echo $categoriaSeleccionada == '' ? 'font-bold text-purple-700' : ''; ?>">
							Todas las categorías
						</a>
					</li>
					<?php foreach ($categorias as $cat): ?>
						<li>
							<a href="<?php echo '/pages/cursos/?categoria=' . urlencode($cat['categoria']) . ($busqueda ? '&busqueda=' . urlencode($busqueda) : ''); ?>"
								class="block py-2 <?php echo $categoriaSeleccionada == $cat['categoria'] ? 'font-bold text-purple-700' : ''; ?>">
								<?php echo $cat['categoria']; ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<!-- Contenido principal -->
			<div>
				<!-- Formulario de búsqueda -->
				<div class="mb-6">
					<form class="flex gap-4 justify-end" action="/pages/cursos/" method="GET" class="flex flex-col gap-2">
						<?php if ($categoriaSeleccionada): ?>
							<input type="hidden" name="categoria" value="<?php echo htmlspecialchars($categoriaSeleccionada); ?>">
						<?php endif; ?>
						<input 
							type="text" 
							name="busqueda" 
							placeholder="Buscar por nombre..." 
							value="<?php echo htmlspecialchars($busqueda); ?>"
							class="w-[300px] max-w-full p-2 border border-gray-300 rounded-sm"
						>
						<button type="submit" class="bg-purple-700 text-white py-2 px-4 rounded-sm hover:bg-purple-800">
							Buscar
						</button>
					</form>
				</div>
				<?php if ($busqueda): ?>
					<div class="mb-6">
						<h2 class="text-2xl font-semibold">Resultados para: "<?php echo htmlspecialchars($busqueda); ?>"</h2>
						<?php if (count($cursos) == 0): ?>
							<p class="mt-4 text-gray-600">No se encontraron cursos que coincidan con tu búsqueda.</p>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				
				<div class="grid md:grid-cols-3 gap-4">
					<?php foreach ($cursos as $curso): 
						$curso_es_premium = $curso['tipo'] == 'premium';
						
						if ($curso_es_premium && $usuario_es_premium) {
							$url = '/pages/detalles-cursos-premium?id=' . $curso['id'];
						} else if ($curso['tipo'] == 'destacado') {
							$url = '/?page=detalles_curso_destacado&id=' . $curso['id'];
						} else if ($curso['tipo'] == 'gratis') {
							$url = '/pages/detalles-cursos?id=' . $curso['id'];
						} else {
							$url = '/#planpremium';
						}
					?>
						<a 	
							href="<?php echo $url; ?>" 
							class="p-4 rounded-sm border border-gray-300 relative"
						>
							<?php if ($curso_es_premium) : ?>
								<span class="absolute top-2 right-2 bg-purple-700 text-white text-xs font-semibold px-2 py-2 rounded-full">
									<svg class="size-[15px] fill-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"/></svg>
								</span>
							<?php endif; ?>
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
						<a href="/pages/cursos/?pagina=<?php echo $paginaActual - 1; ?>&categoria=<?php echo urlencode($categoriaSeleccionada); ?><?php echo $busqueda ? '&busqueda=' . urlencode($busqueda) : ''; ?>" class="px-4 py-2 bg-gray-300 rounded">Anterior</a>
					<?php endif; ?>

					<span class="px-4 py-2 bg-gray-500 text-white rounded">Página <?php echo $paginaActual; ?> de <?php echo $totalPaginas; ?></span>

					<?php if ($paginaActual < $totalPaginas): ?>
						<a href="/pages/cursos/?pagina=<?php echo $paginaActual + 1; ?>&categoria=<?php echo urlencode($categoriaSeleccionada); ?><?php echo $busqueda ? '&busqueda=' . urlencode($busqueda) : ''; ?>" class="px-4 py-2 bg-gray-300 rounded">Siguiente</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php include("../../include/footer.php"); ?>