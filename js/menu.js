document.addEventListener('DOMContentLoaded', () => {
    const links = document.querySelectorAll('#courses-link, #mobile-courses-link');
    const submenus = document.querySelectorAll('#courses-submenu, #mobile-courses-submenu');

    links.forEach((link, index) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            submenus[index].classList.toggle('hidden');
        });
    });

    document.addEventListener('click', (event) => {
        if (![...links, ...submenus].some(el => el.contains(event.target))) {
            submenus.forEach(submenu => submenu.classList.add('hidden'));
        }
    });
});

// Guardar la sección actual al hacer clic en cualquier enlace de la navbar en el index
document.querySelectorAll('.navbar a').forEach(link => {
    link.addEventListener('click', function (event) {
        localStorage.setItem('lastSection', this.getAttribute('href'));
    });
});

// Al cargar el index, navegar automáticamente a la última sección guardada
window.addEventListener('load', function () {
    const lastSection = localStorage.getItem('lastSection');
    if (lastSection) {
        window.location.hash = lastSection;
        localStorage.removeItem('lastSection');
    }
});

// Toggle menú móvil
document.getElementById('menu-btn').addEventListener('click', function () {
    const mobileMenu = document.getElementById('mobile-menu');
    mobileMenu.classList.toggle('hidden');
});