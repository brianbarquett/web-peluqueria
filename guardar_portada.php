<?php
require_once 'conexion.php';

try {
    // Validar datos recibidos
    if (!isset($_POST['titulo']) || !isset($_POST['descripcion'])) {
        throw new Exception("Título y descripción son obligatorios");
    }

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $imagen = null;

    // Validar que título y descripción no estén vacíos
    if (empty($titulo) || empty($descripcion)) {
        throw new Exception("Título y descripción no pueden estar vacíos");
    }

    // Manejo de la imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['imagen']['type'], $allowed_types)) {
            throw new Exception("Solo se permiten imágenes JPEG, PNG o GIF");
        }

        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $imagen = $target_dir . uniqid() . '_' . basename($_FILES['imagen']['name']);
        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen)) {
            throw new Exception("Error al subir la imagen");
        }
    }

    // Verificar si la portada ya existe
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM portada WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $exists = $stmt->fetchColumn();

    if ($exists) {
        // Actualizar portada existente
        $sql = "UPDATE portada SET titulo = :titulo, descripcion = :descripcion";
        $params = [':titulo' => $titulo, ':descripcion' => $descripcion];
        
        if ($imagen) {
            $sql .= ", imagen = :imagen";
            $params[':imagen'] = $imagen;
        }
        
        $sql .= " WHERE id = :id";
        $params[':id'] = $id;
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    } else {
        // Insertar nueva portada
        $stmt = $pdo->prepare("INSERT INTO portada (id, titulo, descripcion, imagen) VALUES (:id, :titulo, :descripcion, :imagen)");
        $stmt->execute([
            ':id' => $id,
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':imagen' => $imagen
        ]);
    }

    header("Location: index.php");
    exit;
} catch (Exception $e) {
    echo "Error: " . htmlspecialchars($e->getMessage());
}
?>