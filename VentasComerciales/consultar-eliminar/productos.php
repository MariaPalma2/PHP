<?php
require_once '../includes/funciones.inc';

$conexion = conectarDb();
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $consulta = $conexion->prepare('DELETE FROM productos WHERE referencia = :referencia');
        $consulta->execute($_POST);
    } catch (Exception $e) {
        $error = 'No se pudo eliminar el producto porque está asociado a una venta.';
    }
}

$productos = [];
try {
    $consulta = $conexion->prepare('SELECT referencia, nombre, descripcion, precio, descuento FROM productos');
    $consulta->execute();
    $productos = $consulta->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = "Se produjo un error al ejecutar la consulta para obtener los productos: {$e->getMessage()}";
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="../styles/globals.css">
</head>
<body>
    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
    <a href="../insertar-editar/producto.php">Añadir producto</a>
    <h1>Listado de productos</h1>
    <table>
        <thead>
            <tr>
                <th>Referencia</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Descuento</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto): ?>
            <?php $referenciaProducto = $producto['referencia'] ?>
                <tr>
                    <td><?= $referenciaProducto ?></td>
                    <td><?= $producto['nombre'] ?></td>
                    <td><?= $producto['descripcion'] ?></td>
                    <td><?= $producto['precio'] ?></td>
                    <td><?= $producto['descuento'] ?></td>
                    <td class="acciones">
                        <form method="post">
                            <input type="hidden" name="referencia" value="<?= $referenciaProducto ?>">
                            <a href="../insertar-editar/producto.php?id=<?= $referenciaProducto ?>">Editar</a>
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