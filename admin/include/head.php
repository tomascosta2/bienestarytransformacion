<?php
// Definir el valor de $page antes de incluir head.php
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<!doctype html>
<html lang="es-AR">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Luz - Mistica</title>
    
    <script src="./js/tinymce/tinymce.min.js"></script>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="./css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="./css/style.css" rel="stylesheet">
    <?php
    // Cargar hoja de estilo específica según la página
    switch ($page) {
        default:
            // Cargar un estilo por defecto o para la página 404
            echo '<link rel="stylesheet" href="./css/default.css">';
            break;
    }
    ?>
  </head>
  <body>
      <script src="js/tinymce/tinymce.min.js"></script>
<script>
  tinymce.init({
    selector: '#descripcion',
        plugins: 'lists link image preview anchor table emoticons', // Configuración de plugins
        toolbar: 'undo redo | formatselect | bold italic | forecolor backcolor | alignleft aligncenter alignright alignjustify | link emoticons',
        });
</script>
