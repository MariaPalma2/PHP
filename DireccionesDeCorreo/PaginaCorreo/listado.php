<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado</title>
</head>
<body>
    <?php
    $nombresCorreos = json_decode(file_get_contents('correos.json'), true);
    foreach ($nombresCorreos as $nombreCorreo) {
        echo "<p>Nombre: " . $nombreCorreo['nombre'] . "</p>";
        echo "<p>Correo: " . $nombreCorreo['correo'] . "</p>";
        echo "<br>";
    }
    ?>
</body>
</html>