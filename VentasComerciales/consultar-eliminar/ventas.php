<?php
require_once '../includes/funciones.inc';

$conexion = conectarDb();
$error = null;
// Si se envían los datos, eliminarlos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $consulta = $conexion->prepare('DELETE FROM ventas WHERE codComercial = :codComercial AND refProducto = :refProducto');
        $consulta->execute($_POST);
    } catch (Exception $e) {
        $error = "Se produjo un error al ejecutar la eliminar la venta: {$e->getMessage()}";
    }
}

$ventas = [];
try {
    $consulta = $conexion->prepare('SELECT codComercial, refProducto, cantidad, fecha FROM ventas');
    $consulta->execute();
    $ventas = $consulta->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = "Se produjo un error al ejecutar la consulta para obtener las ventas: {$e->getMessage()}";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas</title>
    <link rel="stylesheet" href="../styles/globals.css">
</head>
<body>
    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
    <a href="../insertar-editar/venta.php">Añadir venta</a>
    <h1>Listado de ventas</h1>
    <table>
        <thead>
            <tr>
                <th>Código del comercial</th>
                <th>Referencia del producto</th>
                <th>Cantidad</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ventas as $venta): ?>
            <?php $codigoComercial = $venta['codComercial'] ?>
            <?php $referenciaProducto = $venta['refProducto'] ?>
                <tr>
                    <td><?= $codigoComercial ?></td>
                    <td><?= $referenciaProducto ?></td>
                    <td><?= $venta['cantidad'] ?></td>
                    <td><?= $venta['fecha'] ?></td>
                    <td class="acciones">
                        <form method="post">
                            <input type="hidden" name="codComercial" value="<?= $codigoComercial ?>">
                            <input type="hidden" name="refProducto" value="<?= $referenciaProducto ?>">
                            <a href="../insertar-editar/venta.php?id-comercial=<?= $codigoComercial ?>&id-producto=<?= $referenciaProducto ?>">Editar</a>
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