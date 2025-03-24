<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombreCorreos = file_exists('correos.json') ? json_decode(file_get_contents('correos.json'), true) : [];
    $nombreCorreos[] = [
        'nombre' => $_POST['nombre'],
        'correo' => $_POST['correo']
    ];
    file_put_contents('correos.json', json_encode($nombreCorreos));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles/index.css">
</head>
<body>
    <form class="formulario-correo" method="post">
        <article class="formulario-correo-contenedor">
            <label for="">Nombre</label>
            <input type="text" name="nombre">
        </article>
        <article class="formulario-correo-contenedor">
            <label for="">Correo</label>
            <input type="email" name="correo">
        </article>
        <button>Enviar</button>
    </form>
    <a href="listado.php">Ver listado</a>
</body>
</html>