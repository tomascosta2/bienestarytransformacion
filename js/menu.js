    // Alternar la visibilidad del submenú "Nuestros cursos" en la versión de escritorio
    document.getElementById('courses-link').addEventListener('click', function(event) {
        event.preventDefault();
        const submenu = document.getElementById('courses-submenu');
        submenu.classList.toggle('hidden');
    });

    // Alternar la visibilidad del submenú "Nuestros cursos" en la versión móvil
    document.getElementById('mobile-courses-link').addEventListener('click', function(event) {
        event.preventDefault();
        const mobileSubmenu = document.getElementById('mobile-courses-submenu');
        mobileSubmenu.classList.toggle('hidden');
    });

    // Guardar la sección actual al hacer clic en cualquier enlace de la navbar en el index
    document.querySelectorAll('.navbar a').forEach(link => {
        link.addEventListener('click', function(event) {
            localStorage.setItem('lastSection', this.getAttribute('href'));
        });
    });

    // Al cargar el index, navegar automáticamente a la última sección guardada
    window.addEventListener('load', function() {
        const lastSection = localStorage.getItem('lastSection');
        if (lastSection) {
            window.location.hash = lastSection;
            localStorage.removeItem('lastSection');
        }
    });

    // Toggle menú móvil
    document.getElementById('menu-btn').addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    });