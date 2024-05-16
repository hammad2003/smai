<?php
include 'config.php';

// Recibir el ID del servidor a eliminar
$data = json_decode(file_get_contents("php://input"));
$servidorId = $conn->real_escape_string($data->servidorId);

// Obtener la ID del contenedor del servidor
$query = "SELECT container_id FROM servidores WHERE id = $servidorId";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $containerId = $result->fetch_assoc()['container_id'];

    // Detener y eliminar el contenedor Docker
    shell_exec("sudo docker stop $containerId");

    // Eliminar el servidor de la base de datos
    $query = "SELECT FROM servidores WHERE id = $servidorId";
    if ($conn->query($query) === TRUE) {
        echo json_encode(array("success" => true));
    } else {
        echo json_encode(array("success" => false, "message" => "Error al parar el servidor"));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Servidor no encontrado"));
}

$conn->close();
?>