<div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-lg">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800">Regístrate</h2>
                <p class="mt-2 text-sm text-gray-600">Crea una cuenta para acceder a nuestros servicios</p>
            </div>

            <form class="space-y-6" action="./controllers/procesar_registro.php" method="POST">
                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input id="nombre" name="nombre" type="text" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="Tu nombre">
                </div>
                <!-- Apellido -->
                 <div>
                <label for="apellido" class="block text-sm font-medium text-gray-700">Apellido</label>
                    <input id="apellido" name="apellido" type="text" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="Tu apellido">
                </div>

                <!-- Correo Electrónico -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                    <input id="email" name="email" type="email" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="tucorreo@ejemplo.com">
                </div>

                <!-- Contraseña -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input id="password" name="password" type="password" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="********">
                </div>

                <!-- Confirmar Contraseña -->
                <div>
                    <label for="confirm-password" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                    <input id="confirm-password" name="confirm-password" type="password" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="********">
                </div>

            <!-- Selección de Rol -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">Rol</label>
                <select id="role" name="role" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="usuario">Usuario</option>
                    <option value="administrador">Administrador</option>
                </select>
            </div>

                <!-- Botón de Registro -->
                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Registrarse
                    </button>
                </div>
            </form>

            <!-- Opción de Iniciar Sesión -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    ¿Ya tienes una cuenta? <a href="./?page=ingresar" class="text-indigo-600 hover:text-indigo-500">Inicia sesión</a>
                </p>
            </div>
        </div>
    </div>