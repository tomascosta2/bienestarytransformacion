  // Script para alternar la visibilidad de la contraseña
  document.querySelectorAll(".togglePassword").forEach(button => {
    button.addEventListener("click", function () {
      // Encuentra el campo de contraseña asociado al botón
      const passwordField = this.previousElementSibling;
      
      // Alternar el tipo de campo entre "password" y "text"
      const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
      passwordField.setAttribute("type", type);
      
      // Cambiar el icono del candado a un ojo cuando la contraseña esté visible
      this.querySelector("i").classList.toggle("fa-lock");
      this.querySelector("i").classList.toggle("fa-unlock");
    });
  });