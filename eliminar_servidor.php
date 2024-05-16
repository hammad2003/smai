<?php
include 'config.php';

// Recibir el ID del servidor a eliminar
$data = json_decode(file_get_contents("php://input"));
$servidorId = $conn->real_escape_string($data->servidorId);

// Obtener la ID del usuario del servidor a eliminar
$query_usuario = "SELECT id_usuario FROM servidores WHERE id = $servidorId";
$result_usuario = $conn->query($query_usuario);

if ($result_usuario->num_rows > 0) {
    $idUsuario = $result_usuario->fetch_assoc()['id_usuario'];

    // Obtener la cantidad actual de servidores del usuario
    $query_servidores = "SELECT COUNT(id) AS cantidad FROM servidores WHERE id_usuario = $idUsuario";
    $result_servidores = $conn->query($query_servidores);
    $row = $result_servidores->fetch_assoc();
    $cantidad_servidores = $row['cantidad'];

    // Verificar si la cantidad de servidores es mayor a cero
    if ($cantidad_servidores > 0) {
        // Disminuir en uno la cantidad de servidores del usuario
        $cantidad_servidores--;

        // Actualizar el campo servidores_creados en la tabla usuarios
        $query_update = "UPDATE usuarios SET servidores_creados = $cantidad_servidores WHERE id = $idUsuario";
        if ($conn->query($query_update) === FALSE) {
            echo json_encode(array("success" => false, "message" => "Error al actualizar el contador de servidores creados"));
            exit();
        }
    }
} else {
    echo json_encode(array("success" => false, "message" => "Error al obtener el ID de usuario del servidor"));
    exit();
}


// Recibir el ID del servidor a eliminar
$data = json_decode(file_get_contents("php://input"));
$servidorId = $conn->real_escape_string($data->servidorId);

// Obtener la ID del contenedor del servidor
$query = "SELECT container_id FROM servidores WHERE id = $servidorId";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $containerId = $result->fetch_assoc()['container_id'];

    // Detener y eliminar el contenedor Docker
    // shell_exec("sudo docker stop $containerId");
    // shell_exec("sudo docker rm $containerId");

    // Eliminar el contenedor Docker
    shell_exec("sudo docker rm $containerId");

    // Eliminar el servidor de la base de datos
    $query = "DELETE FROM servidores WHERE id = $servidorId";
    if ($conn->query($query) === TRUE) {
        echo json_encode(array("success" => true));
    } else {
        echo json_encode(array("success" => false, "message" => "Error al eliminar el servidor"));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Servidor no encontrado"));
}

$conn->close();
?>