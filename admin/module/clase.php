<div class="flex items-center justify-center min-h-screen bg-gray-100">
  <div class="bg-purple-100 p-8 rounded-lg shadow-lg max-w-md w-full">
      <h2 class="text-2xl font-semibold text-purple-800 mb-6 text-center">Agregar Clase en Vivo</h2>
      <form action="./controllers/procesar_clase" method="POST">
        <!-- Nombre de la clase -->
        <div class="mb-4">
          <label for="class-name" class="block text-purple-700 text-sm font-bold mb-2">Nombre de la Clase</label>
          <input type="text" id="class-name" name="class_name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
        </div>
        <!-- Enlace de la clase -->
        <div class="mb-4">
          <label for="class-link" class="block text-purple-700 text-sm font-bold mb-2">Enlace de la Clase</label>
          <input type="url" id="class-link" name="class_link" placeholder="https://enlace-a-la-clase.com" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
        </div>
        <!-- Fecha de la clase -->
        <div class="mb-4">
          <label for="class-date" class="block text-purple-700 text-sm font-bold mb-2">Fecha de la Clase</label>
          <input type="date" id="class-date" name="class_date" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
        </div>
        <!-- Botón de enviar -->
        <div class="mt-6">
          <button type="submit" class="w-full bg-purple-500 text-white font-semibold py-2 rounded-lg hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:ring-offset-2">
            Agregar Clase en vivo
          </button>
        </div>
      </form>
  </div>
</div>