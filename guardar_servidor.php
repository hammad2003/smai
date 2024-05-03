<?php
include 'config.php';

// Obtener datos del cuerpo de la solicitud.
$data = json_decode(file_get_contents("php://input"));

// Validar datos (puedes agregar más validaciones según tus requisitos).
if (empty($data->nombreServidor) || empty($data->estilo) || empty($data->version)) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
    exit();
}

// Obtener la ID del usuario desde la sesión del usuario
session_start();
$idUsuario = $_SESSION['id_usuario']; // Asegúrate de usar el nombre correcto de la clave de sesión

// Extraer datos.
$nombreServidor = $conn->real_escape_string($data->nombreServidor);
$estilo = $conn->real_escape_string($data->estilo);
$version = $conn->real_escape_string($data->version);

// Insertar datos en la base de datos.
$sql = "INSERT INTO servidores (nombre, software, version, id_usuario) VALUES ('$nombreServidor', '$estilo', '$version', '$idUsuario')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Datos guardados correctamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar datos: ' . $conn->error]);
}

// Cierra la conexión a la base de datos.
$conn->close();
?>