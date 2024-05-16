<?php
include 'config.php';

// Obtener datos del cuerpo de la solicitud.
$data = json_decode(file_get_contents("php://input"));

// Validar datos (puedes agregar más validaciones según tus requisitos).
if (empty($data->nombreServidor) || empty($data->software) || empty($data->version)) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
    exit();
}

// Obtener la ID del usuario desde la sesión del usuario
session_start();
$idUsuario = $_SESSION['id_usuario']; // Asegúrate de usar el nombre correcto de la clave de sesión

// Obtener la cantidad actual de servidores del usuario
$sql = "SELECT COUNT(id) AS cantidad FROM servidores WHERE id_usuario = '$idUsuario'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$cantidad_servidores = $row['cantidad'];

// Verificar si el usuario ha alcanzado el límite de servidores
if ($cantidad_servidores >= 4) {
    echo json_encode(['success' => false, 'message' => 'Has alcanzado el límite de servidores. No puedes crear más.']);
    exit();
}

// Extraer datos.
$nombreServidor = $conn->real_escape_string($data->nombreServidor);
$software = $conn->real_escape_string($data->software);
$version = $conn->real_escape_string($data->version);

// Insertar datos en la base de datos.
$sql = "INSERT INTO servidores (nombre, software, version, id_usuario) VALUES ('$nombreServidor', '$software', '$version', '$idUsuario')";

if ($conn->query($sql) === TRUE) {
    // Incrementar el contador de servidores del usuario
    $cantidad_servidores++;
    $sql_update = "UPDATE usuarios SET servidores_creados = '$cantidad_servidores' WHERE id = '$idUsuario'";
    
    // Actualizar el campo servidores_creados en la tabla usuarios
    if ($conn->query($sql_update) === FALSE) {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el contador de servidores creados: ' . $conn->error]);
        exit();
    
    }
    
    echo json_encode(['success' => true, 'message' => 'Datos guardados correctamente.', 'cantidad_servidores' => $cantidad_servidores]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar datos: ' . $conn->error]);
}

// Cierra la conexión a la base de datos.
$conn->close();
?>