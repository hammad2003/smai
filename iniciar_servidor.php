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
    } elseif ($servidor['software'] === 'Forge') {
        $comando .= " -e TYPE=FORGE";
    } elseif ($servidor['software'] === 'Spigot') {
        $comando .= " -e TYPE=SPIGOT";
    } elseif ($servidor['software'] === 'Bukkit') {
        $comando .= " -e TYPE=BUKKIT";
    } 
    
    $comando .= " itzg/minecraft-server";

    // Ejecutar el comando y obtener la ID del contenedor
    $containerId = trim(shell_exec($comando));

    if ($containerId) {
        // Obtener la dirección IP de la máquina host
        $ipHost = trim(shell_exec("hostname -I | cut -d' ' -f1"));

        // Concatenar la dirección IP y el puerto
        $direccionIPPuerto = $ipHost . ":" . $puerto;


        // Guardar la ID del contenedor y actualizar el estado en la base de datos
        $updateQuery = "UPDATE servidores SET container_id = '$containerId',  ip_address = '$direccionIPPuerto', estado = 'Activo' WHERE id = $servidorId";

        // $updateQuery = "UPDATE servidores SET container_id = '$containerId', estado = 'activo' WHERE id = $servidorId";
        if ($conn->query($updateQuery) === TRUE) {
            echo json_encode(array("success" => true));
        } else {
            echo json_encode(array("success" => false, "message" => "Error al actualizar la base de datos"));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Error al iniciar el servidor Docker"));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Servidor no encontrado"));
}

$conn->close();
?>