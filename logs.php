<?php
include 'config.php';

// Recibir el ID del servidor desde la solicitud GET
if (isset($_GET['servidorId'])) {
    $servidorId = $conn->real_escape_string($_GET['servidorId']); 

    // Consulta SQL para obtener el ID del contenedor asociado al servidor
    $query = "SELECT container_id FROM servidores WHERE id = $servidorId";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $containerId = $result->fetch_assoc()['container_id'];

        // Comando para obtener los últimos logs del contenedor
        $comando = "sudo docker logs --tail 1 $containerId";

        // Ejecutar el comando y obtener los logs
        $logs = shell_exec($comando);

        // Mostrar los logs
        echo "<pre>$logs</pre>";
    } else {
        echo "Servidor no encontrado";
    }
} else {
    echo "ID de servidor no recibido";
}

$conn->close();
?>