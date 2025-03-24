<?php
require_once '../includes/funciones.inc';

$id = $_GET['id'] ?? '';

$titulo = $id ? 'Editar' : 'Insertar';

$error = null;
$conexion = conectarDb();
$comercial = [
    'nombre' => '',
    'descripcion' => '',
    'precio' => '',
    'descuento' => ''
];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = '';
        if ($id) {
            $sql = 'UPDATE productos
                    SET nombre = :nombre, descripcion = :descripcion, precio = :precio, descuento = :descuento
                    WHERE referencia = :referencia';
            $_POST['referencia'] = $id;
        } else {
            $sql = 'INSERT INTO productos (referencia, nombre, descripcion, precio, descuento)
                    VALUES (:referencia, :nombre, :descripcion, :precio, :descuento)';
        }
        $consulta = $conexion->prepare($sql);
        $consulta->execute($_POST);
    } catch (Exception $e) {
        $error = "Se produjo un error al ejecutar la consulta: {$e->getMessage()}";
    }
}

if ($id) {
    try {
        $consulta = $conexion->prepare(
            'SELECT referencia, nombre, descripcion, precio, descuento
            FROM productos WHERE referencia = :referencia'
        );
        $consulta->execute([
            ':referencia' => $id
        ]);
        $comercial = $consulta->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $error = "Se produjo un error al obtener el producto: {$e->getMessage()}";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Insertar Datos</title>
</head>
<body>
    <?php if ($error): ?>
        <p><?= $error ?></p>
    <?php endif; ?>
    <h2><?= $titulo ?> Datos</h2>
    <form method="post">
        <?php if (!$id): ?>
            <article>
                <label for="referencia">Referencia:</label>
                <input type="text" name="referencia" id="referencia" max="6" required>
            </article>
        <?php endif; ?>
        <article>
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" max="30" value="<?= $comercial['nombre'] ?>" required>
        </article>
        <article>
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion"><?= $comercial['descripcion'] ?></textarea>
        </article>
        <article>
            <label for="precio">Número de precio:</label>
            <input type="number" name="precio" id="precio" value="<?= $comercial['precio'] ?>" required>
        </article>
        <article>
            <label for="descuento">Descuento:</label>
            <input type="number" name="descuento" id="descuento" value="<?= $comercial['descuento'] ?>" required>
        </article>
        <button type="submit"><?= $titulo ?></button>
    </form>
    <a href="../consultar-eliminar/productos.php">Volver a los productos</a>
</body>
</html>
