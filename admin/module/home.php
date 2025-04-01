<main class="flex-1 p-8 bg-gray-100 overflow-y-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Dashboard</h1>

    <!-- Sección de estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total de Usuarios Registrados -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Usuarios Registrados</h3>
            <p class="text-3xl font-bold text-purple-500 mt-4"><?php echo $totalUsuarios; ?></p>
        </div>

        <!-- Total de Visitas -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Visitas Totales</h3>
            <p class="text-3xl font-bold text-pink-500 mt-4"><?php echo $totalVisitas; ?></p>
        </div>
    </div>

    <!-- Sección de gráficos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Gráfico de Usuarios Nuevos -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Usuarios Nuevos</h3>
            <canvas id="usersChart"></canvas>
        </div>

        <!-- Gráfico de Visitas Mensuales -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Visitas Mensuales</h3>
            <canvas id="visitsChart"></canvas>
        </div>
    </div>
</main>
</div>

<?php
echo '<script>
// Gráfico de Usuarios Nuevos (Datos dinámicos de PHP)
document.addEventListener("DOMContentLoaded", () => {
    // Gráfico de Usuarios Nuevos
    const usersCtx = document.getElementById("usersChart").getContext("2d");
    new Chart(usersCtx, {
        type: "line",
        data: {
            labels: ' . json_encode($meses) . ',
            datasets: [{
                label: "Usuarios Nuevos",
                data: ' . json_encode($cantidades) . ',
                borderColor: "#a78bfa",
                backgroundColor: "rgba(167, 139, 250, 0.2)",
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });

    // Gráfico de Visitas Mensuales
    const visitsCtx = document.getElementById("visitsChart").getContext("2d");
    new Chart(visitsCtx, {
        type: "bar",
        data: {
            labels: ' . json_encode($mesesVisitas) . ',
            datasets: [{
                label: "Visitas Mensuales",
                data: ' . json_encode($cantidadesVisitas) . ',
                backgroundColor: "rgba(244, 114, 182, 0.7)",
                borderColor: "#f472b6",
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>';
?>
