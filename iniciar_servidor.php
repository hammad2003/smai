<?php
include 'config.php';

// Recibir el ID del servidor a iniciar
$data = json_decode(file_get_contents("php://input"));
$servidorId = $conn->real_escape_string($data->servidorId);

// Obtener el servidor de la base de datos
$query = "SELECT * FROM servidores WHERE id = $servidorId";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $servidor = $result->fetch_assoc();

    // Generar un puerto único para el servidor (por ejemplo, basado en el ID)
    $puerto = 25565 + $servidorId;

    // Ejecutar el comando Docker para iniciar el servidor
    $comando = "sudo docker run -d -it -p $puerto:25565 -e EULA=TRUE -e VERSION={$servidor['version']}";
    if ($servidor['software'] === 'Forge') {
        $comando .= " -e TYPE=FORGE";
    }
    $comando .= " itzg/minecraft-server";

    $output = shell_exec($comando);

    // Guardar la ID del contenedor en la base de datos
    $containerId = trim($output);
    $query = "UPDATE servidores SET container_id = '$containerId' WHERE id = $servidorId";
    if ($conn->query($query) === TRUE) {
        echo json_encode(array("success" => true));
    } else {
        echo json_encode(array("success" => false, "message" => "Error al actualizar la base de datos"));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Servidor no encontrado"));
}

$conn->close();
?>