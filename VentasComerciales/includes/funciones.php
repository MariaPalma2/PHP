<?php

// Conectar a la base de datos
function conectarDb() {
    try {
        // Crear una instancia de PDO para conectar con la base de datos
        $conexion = new PDO('mysql:host=localhost;dbname=ventas_comerciales', 'dam', 'hlc');
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configurar el modo de errores
        return $conexion;
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage()); // Si ocurre un error de conexión, lo muestra
    }
}