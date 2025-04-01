  // Script para alternar la visibilidad de la contraseña
  const togglePassword = document.getElementById("togglePassword");
  const passwordField = document.getElementById("password");

  togglePassword.addEventListener("click", function () {
    // Alternar el tipo de campo entre "password" y "text"
    const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
    passwordField.setAttribute("type", type);
    
    // Cambiar el icono del candado a un ojo cuando la contraseña esté visible
    this.querySelector("i").classList.toggle("fa-lock");
    this.querySelector("i").classList.toggle("fa-unlock");
  });
  