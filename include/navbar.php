
<nav class="bg-white p-4 shadow-lg border-b border-gray-200">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <!-- Menú de Navegación -->
        <ul class="hidden md:flex space-x-8 text-gray-800 font-semibold text-lg">
            <?php if (isset($_SESSION['nombre'])): ?>
                <!-- Saludo al inicio -->
                <li class="text-purple-900 font-semibold">Hola, <?php echo htmlspecialchars($_SESSION['nombre']); ?>!</li>
            <?php endif; ?>

            <li class="relative">
                <a href="#cursos-gratuitos" id="courses-link" class="hover:text-purple-700 transition duration-300 ease-in-out navbar flex items-center cursor-pointer">
                    Nuestras propuestas
                    <!-- Icono de flecha -->
                    <svg class="ml-1 h-5 w-5 transition-transform duration-300" id="courses-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </a>
                <!-- Submenú -->
                <ul id="courses-submenu" class="hidden absolute bg-white shadow-lg border border-gray-200 mt-2 rounded-lg space-y-2 p-4 w-80 z-50">
                    <li><a href="#cursosgratuitos" class="hover:text-purple-700 transition duration-300 ease-in-out">Recursos gratuitos</a></li>
                    <li><a href="#cursosdestacados" class="hover:text-purple-700 transition duration-300 ease-in-out">Cursos, talleres y diplomados</a></li>
                    <li><a href="#planpremium" class="hover:text-purple-700 transition duration-300 ease-in-out">Plan Premium</a></li>
                </ul>
            </li>
            <li><a href="#contacto" class="hover:text-purple-700 transition duration-300 ease-in-out navbar">Contacto</a></li>
            <?php if (isset($_SESSION['nombre'])): ?>
                <li><a href="./?page=mis_cursos" class="hover:text-purple-700 transition duration-300 ease-in-out navbar">Mis cursos</a></li>
            <?php endif; ?>
            <li><a href="/pages/sobremi/" class="hover:text-purple-700 transition duration-300 ease-in-out navbar">Conóceme mejor</a></li>


            <?php if (isset($_SESSION['nombre'])): ?>
                <!-- Botón de salir para usuarios autenticados -->
                <li><a href="./controllers/logout.php" class="hover:text-purple-700 transition duration-300 ease-in-out navbar">Salir</a></li>
            <?php else: ?>
                <!-- Enlace de inicio de sesión para usuarios no autenticados -->
                <li><a href="./?page=ingresar" class="hover:text-purple-700 transition duration-300 ease-in-out navbar">Iniciar sesión</a></li>
            <?php endif; ?>
        </ul>

        <!-- Logo y Nombre en el menú (Visible en ambas versiones) -->
        <a href="/">
            <div class="text-right">
                <h1 class="text-2xl font-bold text-purple-900 text-center nav-title">Escuela Bienestar Integral</h1>
                <p class="text-gray-500 text-sm text-center nav-title">Transformación y Bienestar</p>
            </div>
        </a>

        <!-- Botón de Menú Móvil -->
        <div class="md:hidden">
            <button id="menu-btn" class="focus:outline-none">
                <svg class="h-6 w-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Menú desplegable móvil -->
    <div id="mobile-menu" class="hidden md:hidden flex flex-col space-y-4 mt-4 text-gray-800 font-semibold text-lg">
        <?php if (isset($_SESSION['nombre'])): ?>
            <!-- Saludo al inicio del menú móvil -->
            <div class="text-purple-900 font-semibold">Hola, <?php echo htmlspecialchars($_SESSION['nombre']); ?>!</div>
        <?php endif; ?>

        <a id="mobile-courses-link" class="hover:text-purple-700 transition duration-300 ease-in-out navbar flex items-center cursor-pointer">
            Nuestros cursos
            <svg class="ml-1 h-5 w-5 transition-transform duration-300" id="mobile-courses-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </a>
        <!-- Submenú móvil -->
        <div id="mobile-courses-submenu" class="hidden ml-4 flex flex-col space-y-2">
            <a href="#cursosdestacados" class="hover:text-purple-700 transition duration-300 ease-in-out">Cursos y charlas más destacados</a>
            <a href="#cursosgratuitos" class="hover:text-purple-700 transition duration-300 ease-in-out">Cursos y charlas gratuitas</a>
            <a href="#planpremium" class="hover:text-purple-700 transition duration-300 ease-in-out">Plan PREMIUM</a>
        </div>
        <a href="#quiensoy" class="hover:text-purple-700 transition duration-300 ease-in-out navbar">¿Quién Soy?</a>
        <a href="#contacto" class="hover:text-purple-700 transition duration-300 ease-in-out navbar">Contacto</a>

        <?php if (isset($_SESSION['nombre'])): ?>
            <!-- Botón de salir al final del menú móvil -->
            <a href="./controllers/logout.php" class="hover:text-purple-700 transition duration-300 ease-in-out navbar">Salir</a>
        <?php else: ?>
            <!-- Enlace de inicio de sesión para usuarios no autenticados al final del menú móvil -->
            <a href="./?page=ingresar" class="hover:text-purple-700 transition duration-300 ease-in-out navbar">Iniciar sesión</a>
        <?php endif; ?>
    </div>

</nav>