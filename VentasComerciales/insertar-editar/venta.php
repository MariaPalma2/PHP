<?php
require_once '../includes/funciones.inc';

$idComercial = $_GET['id-comercial'] ?? '';
$idProducto = $_GET['id-producto'] ?? '';

if ($idComercial && !$idProducto) {
    header('Location: ../consultar-eliminar/ventas.php');
}

$titulo = $idComercial && $idProducto ? 'Editar' : 'Insertar';

$error = null;
$conexion = conectarDb();
$venta = [
    'cantidad' => '',
    'fecha' => ''
];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = '';
        if ($idComercial) {
            $sql = 'UPDATE ventas
                    SET cantidad = :cantidad
                    WHERE codComercial = :codComercial AND refProducto = :refProducto';
            $_POST['codComercial'] = $idComercial;
            $_POST['refProducto'] = $idProducto;
        } else {
            $sql = 'INSERT INTO ventas (codComercial, refProducto, cantidad, fecha)
                    VALUES (:codComercial, :refProducto, :cantidad, :fecha)';
        }
        $consulta = $conexion->prepare($sql);
        $consulta->execute($_POST);
    } catch (Exception $e) {
        $error = "Se produjo un error al ejecutar la consulta: {$e->getMessage()}";
    }
}

if ($idComercial) {
    try {
        $consulta = $conexion->prepare(
            'SELECT codComercial, refProducto, cantidad, fecha
            FROM ventas WHERE codComercial = :codComercial AND refProducto = :refProducto'
        );
        $consulta->execute([
            ':codComercial' => $idComercial,
            ':refProducto' => $idProducto
        ]);
        $venta = $consulta->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $error = "Se produjo un error al obtener la venta: {$e->getMessage()}";
    }
}

// Obtener todos los comerciales
$comerciales = [];
try {
    $consulta = $conexion->prepare('SELECT codigo, nombre FROM comerciales');
    $consulta->execute();
    $comerciales = $consulta->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = "Se produjo un error al obtener los comerciales";
}

// Obtener todos los productos
$productos = [];
try {
    $consulta = $conexion->prepare('SELECT referencia FROM productos');
    $consulta->execute();
    $productos = $consulta->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = "Se produjo un error al obtener los productos";
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
        <article>
            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad" id="cantidad" value="<?= $venta['cantidad'] ?>" required>
        </article>
        <?php if (!$idComercial): ?>
            <article>
                <label for="fecha">Fecha:</label>
                <input type="date" name="fecha" id="fecha" value="<?= $venta['fecha'] ?>" required>
            </article>
            <article>
                <label for="codComercial">Comercial:</label>
                <select name="codComercial" id="codComercial">
                    <?php foreach ($comerciales as $comercial): ?>
                        <option value="<?= $comercial['codigo'] ?>"><?= $comercial['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>
            </article>
            <article>
                <label for="refProducto">Producto:</label>
                <select name="refProducto" id="refProducto">
                    <?php foreach ($productos as $producto): ?>
                        <?php $referenciaProducto = $producto['referencia'] ?>
                        <option value="<?= $referenciaProducto ?>"><?= $referenciaProducto ?></option>
                    <?php endforeach; ?>
                </select>
            </article>
        <?php endif; ?>
        <button type="submit"><?= $titulo ?></button>
    </form>
    <a href="../consultar-eliminar/ventas.php">Volver a las ventas</a>
</body>
</html>
