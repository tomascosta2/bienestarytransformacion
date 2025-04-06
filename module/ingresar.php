<?php
if (isset($_SESSION['error'])) {
    echo "<p style='color: red;'>" . $_SESSION['error'] . "</p>";
    unset($_SESSION['error']);
}
?>

<br><br><br><br><br>
<div class="flex items-center justify-center h-screen bg-gradient-to-r from-purple-200 via-pink-100 to-purple-200">
  <!-- Botón Volver al inicio en la esquina superior izquierda -->
  <a href="./" class="absolute top-4 left-4 bg-gradient-to-r from-purple-600 to-pink-500 text-white py-2 px-4 rounded-full font-semibold hover:from-purple-700 hover:to-pink-600 transition duration-300 shadow-lg transform hover:scale-105">
    <i class="fas fa-arrow-left mr-2"></i> Volver al inicio
  </a>

  <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md border border-black">
  <h2 class="text-3xl font-semibold text-purple-800 text-center mb-6 mt-10">
      Iniciar sesión en Escuela Bienestar Integral
    </h2>
    <p class="text-gray-600 text-center mb-8">Accede a tu cuenta y continúa tu viaje de transformación.</p>
    
    <form method="POST" id="protectedForm" action="./controllers/procesar_ingreso.php" class="space-y-6">
      <div>
        <label for="email" class="block text-gray-600 font-medium">Correo electrónico</label>
        <div class="flex items-center border border-gray-300 rounded-md p-2 focus-within:border-purple-400">
          <i class="fas fa-envelope text-white mr-2 bg-gradient-to-r from-purple-600 to-pink-500 p-2 rounded-full transition duration-300 transform shadow-lg"></i>
          <input type="email" name="email" id="email" class="w-full outline-none" placeholder="Ingresa tu correo electrónico" required>
        </div>
      </div>

      <div>
        <label for="password" class="block text-gray-600 font-medium">Contraseña</label>
        <div class="flex items-center border border-gray-300 rounded-md p-2 focus-within:border-purple-400">
          <input type="password" name="password" id="password" class="w-full outline-none" placeholder="Ingresa tu contraseña" required>
          <button type="button" id="togglePassword" class="focus:outline-none bg-gradient-to-r from-purple-600 to-pink-500 p-2 rounded-full ml-2 transition duration-300 transform shadow-lg">
            <i class="fas fa-lock text-white"></i>
          </button>
        </div>
      </div>

      <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-pink-500 text-white py-3 rounded-full font-semibold hover:from-purple-700 hover:to-pink-600 transition duration-300 flex items-center justify-center shadow-lg transform hover:scale-105">
        <i class="fas fa-sign-in-alt mr-2"></i> Ingresar
      </button>

      <p class="text-center text-gray-500 mt-4">
        <a href="#" class="text-purple-600 font-semibold hover:underline">¿Olvidaste tu contraseña?</a>
      </p>
      <p class="text-center text-gray-500 mt-2">
        ¿No tienes cuenta? <a href="./?page=registrarse" class="text-purple-600 font-semibold hover:underline">Regístrate</a>
      </p>
    </form>
  </div>
</div>
<br><br><br><br><br>