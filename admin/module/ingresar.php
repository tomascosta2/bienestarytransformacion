<!doctype html>
<html lang="es-AR">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Luz Mística - Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="./css/style.css" rel="stylesheet">
  </head>
  <body>
    <!-- Formulario de login -->
    <div class="min-h-screen flex">
        <!-- Columna de la Imagen -->
        <div class="relative bg-cover bg-center w-1/2 hidden lg:block"
             style="background-image: url('https://via.placeholder.com/1920x1080');">
            <div class="absolute inset-0 bg-purple-900 opacity-60"></div>
        </div>
        <!-- Columna de Ingreso -->
        <div class="flex w-full lg:w-1/2 justify-center items-center p-8 lg:p-16 bg-gradient-to-r from-purple-100 to-pink-100">
            <div class="max-w-md w-full bg-white p-8 rounded-xl shadow-lg">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-purple-800">Admin Login</h2>
                    <p class="mt-2 text-sm text-gray-600">Inicia sesión con tu cuenta de administrador</p>
                </div>

                <form class="space-y-6" action="./controllers/procesar_ingreso.php" method="POST">
                    <!-- Correo Electrónico -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                        <input id="email" name="email" type="email" required
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                            placeholder="tucorreo@ejemplo.com">
                    </div>

                    <!-- Contraseña -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                        <input id="password" name="password" type="password" required
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                            placeholder="********">
                    </div>
                    <!-- Botón de Inicio de Sesión -->
                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent text-lg font-medium rounded-lg text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-300">
                            Iniciar Sesión
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  </body>
</html>
