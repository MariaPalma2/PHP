<?php
require_once '../includes/funciones.inc';

$conexion = conectarDb();
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $consulta = $conexion->prepare('DELETE FROM comerciales WHERE codigo = :codigo');
        $consulta->execute($_POST);
    } catch (Exception $e) {
        $error = 'No se pudo eliminar el comercial porque está asociado a una venta.';
    }
}

$comerciales = [];
try {
    $consulta = $conexion->prepare('SELECT codigo, nombre, salario, hijos, fNacimiento FROM comerciales');
    $consulta->execute();
    $comerciales = $consulta->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = "Se produjo un error al ejecutar la consulta para obtener los comerciales: {$e->getMessage()}";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comerciales</title>
    <link rel="stylesheet" href="../styles/globals.css">
</head>
<body>
    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
    <a href="../insertar-editar/comercial.php">Añadir comercial</a>
    <h1>Listado de comerciales</h1>
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Salario</th>
                <th>Número de hijos</th>
                <th>Fecha de nacimiento</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($comerciales as $comercial): ?>
            <?php $codigoComercial = $comercial['codigo'] ?>
                <tr>
                    <td><?= $codigoComercial ?></td>
                    <td><?= $comercial['nombre'] ?></td>
                    <td><?= $comercial['salario'] ?></td>
                    <td><?= $comercial['hijos'] ?></td>
                    <td><?= $comercial['fNacimiento'] ?></td>
                    <td class="acciones">
                        <form method="post">
                            <input type="hidden" name="codigo" value="<?= $codigoComercial ?>">
                            <a href="../insertar-editar/comercial.php?id=<?= $codigoComercial ?>">Editar</a>
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="../index.php">Volver al inicio</a>
</body>
</html>