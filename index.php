<?php
// Activar el buffer de salida para evitar problemas con el header
ob_start();
?>

<link rel="icon" href="./images/ico.png" type="image/x-icon">

<?php
include './connection/database.php'; // Asegúrate de que la ruta sea correcta
include("./include/head.php");

// Obtener el valor del parámetro 'page' de la URL, por defecto 'home'
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Mostrar la navbar solo si la página no es "ingresar" ni "registrarse"
if ($page !== 'ingresar' && $page !== 'registrarse' && $page) {
    include("./include/navbar.php");
}

// Incluir el contenido correspondiente según el valor de 'page'
switch ($page) {
    case 'home':
        include './module/home.php';
        break;
    case 'ingresar':
        include './module/ingresar.php';
        break;
    case 'registrarse':
        include './module/registrarse.php';
        break;
    case 'mis_cursos':
        include './module/mis_cursos.php';
        break;
    case 'detalles_cursos':
        include './module/detalles_cursos.php';
        break;
    case 'detalles_cursos_destacado':
        include './module/detalles_curso_destacado.php';
        break;
    case 'detalles_mi_curso_destacado':
        include './module/detalles_mi_curso_destacado.php';
        break;
    case 'detalles_cursos_premium':
        include './module/detalles_cursos_premium.php';
        break;
    case 'plan_premium':
        include './module/plan_premium.php';
        break;
    case 'detalles_clases':
        include './module/detalles_clases.php';
        break;
    case 'pago_exitoso.php':
        include './module/pago_exitoso.php';
        break;
    default:
        include './module/404.php'; // Página 404 si no se encuentra
        break;
}

include("./include/footer.php");

// Enviar el contenido acumulado en el buffer al navegador
ob_end_flush();
?>
