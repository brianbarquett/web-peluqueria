<?php
try {
    $host = 'localhost'; // Cambia según tu servidor
    $dbname = 'contenedores_db'; // Cambia por el nombre de tu base de datos
    $username = 'root'; // Cambia por tu usuario de base de datos
    $password = ''; // Cambia por tu contraseña de base de datos
    
    // Crear conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Configurar PDO para lanzar excepciones en caso de error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>