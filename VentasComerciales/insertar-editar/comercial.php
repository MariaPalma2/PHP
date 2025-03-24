<?php
require_once '../includes/funciones.inc';

$id = $_GET['id'] ?? '';

$titulo = $id ? 'Editar' : 'Insertar';

$error = null;
$conexion = conectarDb();
$comercial = [
    'nombre' => '',
    'salario' => '',
    'hijos' => '',
    'fNacimiento' => ''
];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = '';
        if ($id) {
            $sql = 'UPDATE comerciales
                    SET nombre = :nombre, salario = :salario, hijos = :hijos, fNacimiento = :fNacimiento
                    WHERE codigo = :codigo';
            $_POST['codigo'] = $id;
        } else {
            $sql = 'INSERT INTO comerciales (codigo, nombre, salario, hijos, fNacimiento)
                    VALUES (:codigo, :nombre, :salario, :hijos, :fNacimiento)';
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
            'SELECT codigo, nombre, salario, hijos, fNacimiento
            FROM comerciales WHERE codigo = :codigo'
        );
        $consulta->execute([
            ':codigo' => $id
        ]);
        $comercial = $consulta->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $error = "Se produjo un error al obtener el comercial: {$e->getMessage()}";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?> Datos</title>
</head>
<body>
    <?php if ($error): ?>
        <p><?= $error ?></p>
    <?php endif; ?>
    <h2><?= $titulo ?> Datos</h2>
    <form method="post">
        <?php if (!$id): ?>
            <article>
                <label for="codigo">Código:</label>
                <input type="text" name="codigo" id="codigo" max="3" required>
            </article>
        <?php endif; ?>
        <article>
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" max="30" value="<?= $comercial['nombre'] ?>" required>
        </article>
        <article>
            <label for="salario">Salario:</label>
            <input type="number" name="salario" id="salario" min="1" step="any" value="<?= $comercial['salario'] ?>" required>
        </article>
        <article>
            <label for="hijos">Número de hijos:</label>
            <input type="number" name="hijos" id="hijos" value="<?= $comercial['hijos'] ?>" required>
        </article>
        <article>
            <label for="fNacimiento">Fecha de nacimiento:</label>
            <input type="date" name="fNacimiento" id="fNacimiento" value="<?= $comercial['fNacimiento'] ?>" required>
        </article>
        <button type="submit"><?= $titulo ?></button>
    </form>
    <a href="../consultar-eliminar/comerciales.php">Volver a los comerciales</a>
</body>
</html>
