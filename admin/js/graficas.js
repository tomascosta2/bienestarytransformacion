document.addEventListener('DOMContentLoaded', () => {
    // Llamada AJAX para obtener los datos del gráfico
    fetch('./obtener_datos_usuarios.php')
        .then(response => response.json())
        .then(data => {
            const usersCtx = document.getElementById('usersChart').getContext('2d');
            new Chart(usersCtx, {
                type: 'line',
                data: {
                    labels: data.meses,
                    datasets: [{
                        label: 'Usuarios Nuevos',
                        data: data.cantidades,
                        borderColor: '#a78bfa',
                        backgroundColor: 'rgba(167, 139, 250, 0.2)',
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } }
                }
            });
        })
        .catch(error => console.error('Error al cargar los datos:', error));
});