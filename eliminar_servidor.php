<?php
include 'config.php';

// Recibe la solicitud POST con el ID del servidor a eliminar
$id = json_decode(file_get_contents('php://input'), true)['id'];

// Consulta para obtener información del servidor
$sql = "SELECT * FROM servidores WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nombreServidor = $row["nombre"];
    
    // Ejecuta el comando ssh para detener y eliminar el servidor de Docker en el servidor remoto
    $output = shell_exec('ssh usuario@192.168.22.133 "docker stop ' . $id . ' && docker rm ' . $id . '"');

    // Verifica si el comando se ejecutó correctamente
    if ($output) {
        // Actualiza el estado del servidor a 'detenido' en la base de datos
        $sql_update = "UPDATE servidores SET estado = 'detenido' WHERE id = $id";
        $conn->query($sql_update);
        
        // Devuelve una respuesta de éxito
        echo json_encode(array('success' => true, 'nombreServidor' => $nombreServidor));
    } else {
        // Devuelve una respuesta de error
        echo json_encode(array('success' => false, 'message' => 'No se pudo eliminar el servidor.'));
    }
} else {
    // Si no se encuentra el servidor, devuelve un mensaje de error
    echo json_encode(array('success' => false, 'message' => 'No se encontró el servidor.'));
}

$conn->close();
?>