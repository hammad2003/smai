<?php
include 'config.php';

// Verificar si se han recibido el ID del servidor y el comando
if (isset($_POST['servidorId']) && isset($_POST['comando'])) {
    // Obtener y limpiar los datos recibidos
    $servidorId = $conn->real_escape_string($_POST['servidorId']);
    $comando = $conn->real_escape_string($_POST['comando']);

    // Consultar la base de datos para obtener el ID del contenedor asociado al servidor
    $query = "SELECT container_id FROM servidores WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $servidorId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $containerId = $result->fetch_assoc()['container_id'];

        // Ejecutar el comando en el contenedor
        $escapedCommand = escapeshellarg($comando);
        $escapedContainerId = escapeshellarg($containerId);
        $output = shell_exec("docker attach $escapedContainerId $escapedCommand 2>&1");

        // Devolver la salida del comando como respuesta
        echo nl2br(htmlspecialchars($output));
    } else {
        echo "Error: Servidor no encontrado";
    }

    $stmt->close(); // Cerrar la consulta preparada
} else {
    echo "Error: ID de servidor o comando no recibido";
}

$conn->close();
?>