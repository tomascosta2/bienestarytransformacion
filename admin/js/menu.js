// Toggle sidebar menu on mobile
const menuToggle = document.getElementById('menu-toggle');
const sidebar = document.getElementById('sidebar');
menuToggle.addEventListener('click', () => {
    sidebar.classList.toggle('hidden');
});

// Toggle dropdown menus
document.querySelectorAll('.dropdown-button').forEach(button => {
    button.addEventListener('click', () => {
        const dropdownMenu = button.nextElementSibling;
        dropdownMenu.classList.toggle('hidden');
    });
});