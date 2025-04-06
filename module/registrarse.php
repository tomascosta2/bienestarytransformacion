<div class="py-[60px] flex items-center justify-center bg-gradient-to-r from-purple-200 via-pink-100 to-purple-200">
  <a href="./" class="absolute top-4 left-4 bg-gradient-to-r from-purple-600 to-pink-500 text-white py-2 px-4 rounded-full font-semibold hover:from-purple-700 hover:to-pink-600 hover:scale-105 transition duration-300 shadow-lg transform">
    <i class="fas fa-arrow-left mr-2"></i> Volver al inicio
  </a>
  <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md border border-black">
  <h2 class="text-3xl font-semibold text-purple-800 text-center mb-6">
      Registro en Escuela Bienestar Integral
    </h2>
    <p class="text-gray-600 text-center mb-8">Únete a nuestra comunidad y comienza tu viaje de transformación.</p>
    <form method="POST" id="protectedForm" action="./controllers/procesar_registro.php" class="space-y-4">
      <div class="w-full">
        <label for="name" class="block text-gray-600 font-medium">Nombre completo</label>
        <div class="flex items-center border border-gray-300 rounded-md p-2 focus-within:border-purple-400 w-full">
          <i class="fas fa-user text-white bg-gradient-to-r from-purple-600 to-pink-500 p-2 rounded-full mr-2 transition duration-300 transform shadow-lg"></i>
          <input type="text" name="name" id="name" class="w-full outline-none" placeholder="Ingresa tu nombre completo" required>
        </div>
      </div>

      <div class="w-full">
        <label for="email" class="block text-gray-600 font-medium">Correo electrónico</label>
        <div class="flex items-center border border-gray-300 rounded-md p-2 focus-within:border-purple-400 w-full">
          <i class="fas fa-envelope text-white bg-gradient-to-r from-purple-600 to-pink-500 p-2 rounded-full mr-2 transition duration-300 transform shadow-lg"></i>
          <input type="email" name="email" id="email" class="w-full outline-none" placeholder="Ingresa tu correo electrónico" required>
        </div>
      </div>

      <div class="w-full">
        <label for="password" class="block text-gray-600 font-medium">Contraseña</label>
        <div class="flex items-center border border-gray-300 rounded-md p-2 focus-within:border-purple-400 w-full">
          <input type="password" name="password" id="password" class="w-full outline-none" placeholder="Ingresa tu contraseña" required>
          <button type="button" class="togglePassword focus:outline-none p-2 rounded-md ml-2">
            <i class="fas fa-lock text-white bg-gradient-to-r from-purple-600 to-pink-500 p-2 rounded-full transition duration-300 transform shadow-lg"></i>
          </button>
        </div>
      </div>

      <div class="w-full">
        <label for="confirm_password" class="block text-gray-600 font-medium">Confirma tu contraseña</label>
        <div class="flex items-center border border-gray-300 rounded-md p-2 focus-within:border-purple-400 w-full">
          <input type="password" name="confirm_password" id="confirm_password" class="w-full outline-none" placeholder="Confirma tu contraseña" required>
          <button type="button" class="togglePassword focus:outline-none p-2 rounded-md ml-2">
            <i class="fas fa-lock text-white bg-gradient-to-r from-purple-600 to-pink-500 p-2 rounded-full transition duration-300 transform shadow-lg"></i>
          </button>
        </div>
      </div>

      <button 
        data-sitekey="6LfpewsrAAAAAA7pA-mHwzet43F5dCnAzboMyr13" 
        data-callback='onSubmit' 
        data-action='submit'
        type="submit" 
        class="w-full g-recaptcha bg-gradient-to-r from-purple-600 to-pink-500 text-white py-3 rounded-full font-semibold hover:from-purple-700 hover:to-pink-600 transition duration-300 flex items-center justify-center shadow-lg transform hover:scale-105 mt-4"
      >
        <i class="fas fa-user-plus mr-2"></i> Registrarme
      </button>

      <!-- Mensaje "¿Ya tienes cuenta?" -->
      <p class="text-center text-gray-500 mt-4">
        ¿Ya tienes cuenta? <a href="./?page=ingresar" class="text-purple-600 font-semibold hover:text-purple-700">Inicia sesión aquí</a>
      </p>
    </form>
  </div>
</div>


