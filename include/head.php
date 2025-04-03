<?php
session_start(); // Inicia la sesión para verificar el inicio de sesión
?>
<?php
// Definir el valor de $page antes de incluir head.php
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luz - Mistica</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <!-- TailwindCSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Tipografías -->
    <link href="https://fonts.googleapis.com/css2?family=Alice&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;400;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Hoja de estilos principal -->
    <link href="./css/style.css" rel="stylesheet">

    <!-- Cargar hoja de estilo específica según la página -->
    <?php
    // Array con las páginas y sus respectivas hojas de estilo
    $stylesheets = [
        'home' => 'home.css',
    ];

    // Verificar si la página actual tiene una hoja de estilo asignada
    if (isset($stylesheets[$page])) {
        echo '<link rel="stylesheet" href="./css/' . $stylesheets[$page] . '">';
    } else {
        // Cargar un estilo por defecto o para la página 404
        echo '<link rel="stylesheet" href="./css/default.css">';
    }
    ?>
</head>

<body>