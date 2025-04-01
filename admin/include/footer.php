<?php
// Cargar hoja de estilo específica según la página
switch ($page) {
    case 'home':
        echo '<script src="./js/graficas.js"></script>';

        echo '<script>
        // Gráfico de Usuarios Nuevos (Datos dinámicos de PHP)
        document.addEventListener("DOMContentLoaded", () => {
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
        });
        </script>';


        break;
    case 'visitas':
        echo '<script src="./js/visitas.js"></script>';
        echo '<script>
document.addEventListener("DOMContentLoaded", () => {
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
        break;
    case 'gratuito':
        echo '<script src="./js/formulario.js"></script>';
        break;
    case 'destacado':
        echo '<script src="./js/formulario.js"></script>';
        break;
    case 'premium':
        echo '<script src="./js/formulario.js"></script>';
}

?>
<script src="./js/menu.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>