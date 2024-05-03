<?php
include 'config.php';

// Verificar si hay un servidor activo en la base de datos
$sql = "SELECT puerto FROM servidores WHERE estado = 'activo' LIMIT 1";
$resultado = $conn->query($sql);

if ($resultado->num_rows > 0) {
    // Si ya hay un servidor activo, informar al usuario y no hacer nada más
    echo json_encode(array("success" => false, "message" => "Ya hay un servidor activo."));
} else {
    // Obtener el puerto asociado a este servidor
    $sqlUltimoPuerto = "SELECT MAX(puerto) AS ultimo_puerto FROM servidores";
    $resultadoPuerto = $conn->query($sqlUltimoPuerto);
    $ultimoPuerto = 25565; // Puerto predeterminado si no hay registros en la tabla
    if ($resultadoPuerto->num_rows > 0) {
        $filaPuerto = $resultadoPuerto->fetch_assoc();
        $ultimoPuerto = intval($filaPuerto['ultimo_puerto']);
    }

    // Incrementar el puerto para el nuevo servidor
    $nuevoPuerto = $ultimoPuerto + 1;

    // Construir el comando SSH para iniciar el servidor
    $comandoSSH = "ssh usuario@192.168.22.133 \"sudo docker run -d -p $nuevoPuerto:25565 -e EULA=TRUE itzg/minecraft-server\"";

    // Ejecutar el comando SSH
    $resultado = shell_exec($comandoSSH);

    // Verificar si se pudo iniciar el servidor
    if ($resultado !== null) {
        // Si se inicia el servidor, actualizar el estado y puerto en la base de datos
        $sqlActualizar = "UPDATE servidores SET estado = 'activo', puerto = $nuevoPuerto WHERE estado = 'inactivo'";
        if ($conn->query($sqlActualizar) === TRUE) {
            // Ejecutar los comandos de Ngrok
            $comandoNgrok = "curl -s https://ngrok-agent.s3.amazonaws.com/ngrok.asc \
                            | sudo tee /etc/apt/trusted.gpg.d/ngrok.asc >/dev/null \
                            && echo \"deb https://ngrok-agent.s3.amazonaws.com buster main\" \
                            | sudo tee /etc/apt/sources.list.d/ngrok.list \
                            && sudo apt update \
                            && sudo apt install ngrok -y \
                            && ngrok config add-authtoken 2f0WFwPNttdQg8v1i8mlPxqnUjV_7EMRGeHRdNYbPVfkf92Ht \
                            && ngrok tcp $nuevoPuerto";

            $resultadoNgrok = shell_exec($comandoNgrok);

            // Verificar si se pudo obtener la dirección IP a través de Ngrok
            if ($resultadoNgrok !== null) {
                echo json_encode(array("success" => true, "message" => "Servidor iniciado correctamente en el puerto $nuevoPuerto."));
            } else {
                echo json_encode(array("success" => false, "message" => "Error al obtener la dirección IP a través de Ngrok."));
            }
        } else {
            echo json_encode(array("success" => false, "message" => "Error al actualizar el estado y puerto en la base de datos."));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Error al iniciar el servidor."));
    }
}

// Cierra la conexión a la base de datos.
$conn->close();
?>